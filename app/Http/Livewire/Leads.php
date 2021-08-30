<?php

namespace App\Http\Livewire;

use App\Domains\Comment\Models\Comment;
use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Models\LeadProduct;
use App\Domains\Lead\Models\LeadSource;
use App\Domains\Lead\Models\LeadStatus;
use App\Traits\CustomLivewireValidate;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Domains\Contractor\Models\Contractor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;




class Leads extends Component
{
    use WithPagination, CustomLivewireValidate;

    public $activeTab = 'main';
    public $modalFormVisible = false;
    public $modalNewLeadVisible = false;
    public $confirmingUserDeletion = false;
    public $modalLeadInProcess = false;

    public $products = [];

    public $lead;
    public $contractors = [];

    public $statuses;
    public $sources;

    public $status_search = -1;
    public $key_search;

    public $checked_ids = [];
    public $checkLeadsOnPage = false;
    public $checkAllLeads = false;
    public $massAction = -1;
    public $confirmingMassDeletion = false;

    public $confirmingMassStatusUpdate = false;
    public $newStatus = -1;

    public $date_start;
    public $date_end;

    public $showMessageNewLeads = false;
    public $userOpenedTheLead;

    public $openCloseViewLeads = [];

    protected $listeners = [
        'refreshLeadsCompnent' => '$refresh',
        'contractorHasBeenSaved' => 'contractorHasBeenSaved',
        'leadsHaveBeenAdded' => 'leadsHaveBeenAdded',
        'setNomenclatureSearcher' => 'setProductNomenclature'
    ];

    public function getModels()
    {
        $isSearch = false;

        if( $this->key_search ){
            $leads = Lead::search($this->key_search)->orderByDesc('id');
            $isSearch = true;
        }else{
            $leads = Lead::where('team_id', Auth::user()->currentTeam->id);
        }

        if( $this->status_search != -1 ){
            $leads->where('status_id', $this->status_search);
        }

        if( $this->date_start != '' ){
            $leads->where('created_at', '>=', Carbon::parse($this->date_start, Auth::user()->timezone)->setTimezone('UTC'));
            $isSearch = true;
        }

        if( $this->date_end != '' ){
            $leads->where('created_at', '<=', Carbon::parse($this->date_end, Auth::user()->timezone)->endOfDay()->setTimezone('UTC'));
            $isSearch = true;
        }

        if( !$isSearch ){
            $leadsWithoutContractors = Lead::doesntHave('contractors')->selectRaw('leads.*, null as contractor_id');

            $leads->join( DB::raw('(select max(leads.id) as id from `leads` inner join `contractor_lead` on `contractor_lead`.`lead_id` = `leads`.`id` where `leads`.`deleted_at` is null group by `contractor_id`) LatestLead'),
                        function($join) {
                            $join->on('leads.id', '=', 'LatestLead.id');
                        })
                ->join('contractor_lead', 'contractor_lead.lead_id', '=', 'leads.id')
                ->selectRaw('leads.*, contractor_lead.contractor_id')
                ->union($leadsWithoutContractors)
                ->orderByDesc('id');
        }

        return $leads;
    }

    public function getPaginatedModels()
    {
        $leads = $this->getModels();
        return $leads->paginate(10);
    }

    public function render()
    {
        $leads = $this->getPaginatedModels();

        $this->checkLeadsOnPage = $this->areLeadsChecked( $this->getLeadsAndRelativeLeadsIds($leads) );
        $this->checkAllLeads = $this->areAllLeadsChecked();

        return view('livewire.leads', [
            'leads' => $this->getPaginatedModels(),
        ]);
    }

    public function areAllLeadsChecked()
    {
        $result = Lead::chunk(50, function($leads){
            if( !$this->areLeadsChecked( $leads->pluck('id')->toArray() ) ) {
                return false;
            }
        });

        return $result;
    }

    public function searchByKey($key)
    {
        $this->key_search = $key;
        $this->goToPage(1);
    }

    public function sortByStatus($status)
    {
        $this->status_search = $status;
        $this->goToPage(1);
    }

    public function mount()
    {
        $this->statuses = LeadStatus::getActive();
        $this->sources = LeadSource::all();
    }

    public function cleanVar()
    {
        $this->lead = null;
        $this->contractors = [];
        $this->massAction = -1;
        $this->newStatus = -1;
    }

    public function deleteShowModal($id)
    {
        $this->lead = Lead::find($id);
        $this->confirmingUserDeletion = true;
    }

    public function delete()
    {
        $this->lead->delete();

        $this->confirmingUserDeletion = false;
        $this->cleanVar();
    }

    public function openModalFormVisible($id)
    {
        $lead = Lead::findOrFail($id);
        $this->lead = $lead;
        $this->products = [];
        foreach ($lead->products as $product) {
            $this->products[] = [
                'name' => $product['name'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'discount' => $product['discount'],
                'sum' => $product['sum'],
                'vat_rate' => $product['vat_rate'],
                'sum_vat_rate' => $product['sum_vat_rate'],
                'total' => $product['total'],
                'nomenclature_product_id' => $product['nomenclature_product_id'],
                'lead_id' => $product['lead_id'],
                'lead_product_id' => $product['id']
            ];
        }

        if( $lead->isAvailableToWorkingWith() ){
            $lead->startWorkingWith();
            $lead->read();
            $this->modalFormVisible = true;
            $this->emit('modalsUpdated');
        }else{
            $this->showModalLeadInProcess();
        }
    }

    public function closeFormVisible()
    {
        if( is_null($this->lead) ) return;

        $this->lead->endWorkingWith();

        $this->cleanVar();
        $this->modalFormVisible = false;

        $this->emit('modalsUpdated');
    }

    public function openModalNewLeadVisible()
    {
        $this->contractors[] = Contractor::find(1);
        $this->modalNewLeadVisible = true;
        $this->emit('modalsUpdated');
    }

    public function closeModalNewLeadVisible()
    {
        $this->contractors = [];
        $this->modalNewLeadVisible = false;
        $this->emit('modalsUpdated');
    }

    public function contractorHasBeenSaved($id)
    {
        $this->contractors[] = Contractor::find($id);
    }

    public function validateRequest($request)
    {
        return Validator::make(
            [
                'status_id' => $request['status_id'],
                //'description' => $request['lead-trixFields[description]']
            ],
            [
                'status_id' => 'required',
                //'description' => 'required'
            ]
        )->validate();
    }

    public function update($request)
    {
        $lead = $this->lead;

        $this->validateRequest($request);

        $data = [
            'source_id' => $request['source_id'],
            'status_id' => $request['status_id'],
            'lead-trixFields' => ['description' => $request['lead-trixFields[description]'] ],
            'attachment-lead-trixFields' => [ 'description' => $request['attachment-lead-trixFields[description]'] ],
            'responsible_id' => $request['responsible_id']
        ];

        if( isset($request['team_id']) ){
            $data['team_id'] = $request['team_id'];
        }

        $lead->update($data);

        if( count($this->contractors) > 0 ){
            $lead->attachContractors( array_column($this->contractors, 'id'));
        }

        $this->closeFormVisible();
    }

    public function create($request)
    {
        $this->validateRequest($request);

        $data =[
            'user_id' => Auth::id(),
            'source_id' => $request['source_id'],
            'status_id' => $request['status_id'],
            'lead-trixFields' => ['description' => $request['lead-trixFields[description]'] ],
            'attachment-lead-trixFields' => [ 'description' => $request['attachment-lead-trixFields[description]'] ],
            'responsible_id' => $request['responsible_id']
        ];

        if( isset($request['team_id']) ){
            $data['team_id'] = $request['team_id'];
        } else {
            $data['team_id'] = Auth::user()->currentTeam->id;
        }

        $lead = Lead::create($data);

        if( count($this->contractors) > 0 ){
            $lead->attachContractors( array_column($this->contractors, 'id'));
        }

        $this->modalNewLeadVisible = false;

        return redirect()->route('dashboard');
    }

    public function chooseContractor()
    {
        $this->emit('contractorChoose');
    }

    public function startEditContractor($id)
    {
        $this->emit('startEditContractor', $id);
    }

    public function deleteContractorFromLead($id)
    {
        $this->lead->deleteContractor($id);
        $this->emit('refreshLeadsCompnent');
    }

    public function addComment($request)
    {
        if( !$request['comment-trixFields[description]'] ){
            return;
        }

        $lead = $this->lead;

        $trix = [
            'comment-trixFields' => ['description' => $request['comment-trixFields[description]'] ],
            'attachment-comment-trixFields' => [ 'description' => $request['attachment-comment-trixFields[description]'] ]
        ];

        $lead->attachComment($trix);

        //$this->emit('commentAdded');
        $this->emit('refreshLeadsCompnent');
    }

    protected function getLeadsAndRelativeLeadsIds($models)
    {
        $ids = [];

        foreach($models as $model){
            $related_ids = $this->getLeadAndRelativeLeadsIds($model);
            $ids = array_merge($ids, $related_ids);
        }

        return $ids;
    }

    protected function getLeadAndRelativeLeadsIds($model)
    {
        $related_ids = $model->getRelatedContractorsLeads()->pluck('id')->toArray();
        $ids = array_merge($related_ids, [$model->id]);

        return $ids;
    }

    public function toggleCheckLeadsOnPage()
    {
        $models = $this->getPaginatedModels();
        $ids = $this->getLeadsAndRelativeLeadsIds($models);

        $this->updateCheckedStatusLeads($ids, $this->checkLeadsOnPage);
    }

    public function updateCheckedStatusLeads($ids, $value)
    {
        foreach( $ids as $id ){
            $this->checked_ids[$id] = $value;
        }
    }

    public function toggleAllCheckLeads()
    {
        $idItems = Lead::all()->pluck('id')->toArray();
        $this->updateCheckedStatusLeads($idItems, $this->checkAllLeads);
    }

    public function areLeadsChecked($leads_id)
    {
        foreach($leads_id as $lead_id){
            if( !isset( $this->checked_ids[$lead_id] ) || !$this->checked_ids[$lead_id] ){
                return false;
            }
        }
        return true;
    }

    public function chooseMassAction()
    {
        switch ($this->massAction) {
            case '1':
                $this->showChangeStatusModal();
                break;
            case '2':
                $this->showDeleteCheckedModal();
                break;
            case '3':
                $this->readChecked();
        }
    }

    public function showDeleteCheckedModal()
    {
        $this->confirmingMassDeletion = true;
    }

    public function deleteChecked()
    {
        $ids = array_keys( $this->checked_ids, true);

        Lead::whereIn('id', $ids)->delete();
        $this->confirmingMassDeletion = false;
        $this->cleanVar();
    }

    public function readChecked()
    {
        $ids = array_keys( $this->checked_ids, true);
        $leads = Lead::whereIn('id', $ids)->get();
        foreach($leads as $lead){
            $lead->read();
        }
    }

    public function showChangeStatusModal()
    {
        $this->confirmingMassStatusUpdate = true;
    }

    public function updateStatusesOfCheckedLeads()
    {
        $ids = array_keys( $this->checked_ids, true);

        if( $this->newStatus != -1 ){
            Lead::whereIn('id', $ids)->update([
                'status_id' => $this->newStatus
            ]);
        }

        $this->confirmingMassStatusUpdate = false;
        $this->cleanVar();
    }

    public function searchByDateStart($date)
    {
        $this->date_start = $date;
        $this->goToPage(1);
    }

    public function searchByDateEnd($date)
    {
        $this->date_end = $date;
        $this->goToPage(1);
    }

    public function resetFilters()
    {
        $this->status_search = -1;
        $this->key_search = null;
        $this->date_start = null;
        $this->date_end = null;
        $this->goToPage(1);
    }

    public function leadsHaveBeenAdded()
    {
        $this->showMessageNewLeads = true;
        $this->emit('refreshLeadsCompnent');
    }

    public function closeMessageNewLeads()
    {
        $this->showMessageNewLeads = false;
    }

    public function deleteComment($id)
    {
        Comment::find($id)->deleteComment();
        $this->emit('refreshLeadsCompnent');
    }

    public function showModalLeadInProcess()
    {
        $this->userOpenedTheLead = $this->lead->getUserOpenedTheLead();
        $this->modalLeadInProcess = true;
    }

    public function closeModalLeadInProcess()
    {
        $this->modalLeadInProcess = false;
        $this->userOpenedTheLead = null;
    }

    public function toggleRelatedView($id)
    {
        $ids = $this->openCloseViewLeads;

        if( ($pos = array_search($id, $ids)) !== false ){
            unset($ids[$pos]);
        }else{
            $ids[] = $id;
        }

        $this->openCloseViewLeads = $ids;
    }

    public function toggleCheckedByFolder($id)
    {
        $folder = Lead::find($id);

        $ids = $this->getLeadAndRelativeLeadsIds($folder);

        $this->updateCheckedStatusLeads( $ids, $this->checked_ids[$folder->id] );
    }

    public function addEmptyProduct(){
        $this->products[] = [
            'name' => null,
            'quantity' => 1,
            'price' => 0,
            'discount' => 0,
            'sum' => 0,
            'vat_rate' => 0,
            'sum_vat_rate' => 0,
            'total' => 0,
            'nomenclature_product_id' => null,
            'lead_id' => $this->lead->id,
            'lead_product_id' => null,
        ];
    }

    public function saveProducts(){
        foreach ($this->products as $productKey => $product) {
            if (!empty($product['lead_product_id'])){
                $leadProduct = LeadProduct::query()->find($product['lead_product_id']);
            }else {
                $leadProduct = new LeadProduct();
            }
            $leadProduct->fill([
                'name' => $product['name'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'discount' => $product['discount'],
                'sum' => $product['sum'],
                'vat_rate' => $product['vat_rate'],
                'sum_vat_rate' => $product['sum_vat_rate'],
                'total' => $product['total'],
                'nomenclature_product_id' => $product['nomenclature_product_id'],
                'lead_id' => $product['lead_id'],
            ]);
            $leadProduct->save();
            $this->products[$productKey]['lead_product_id'] = $leadProduct->id;
        }
        $this->alert('success', '', 'Товары успешно сохранены!');
    }

    public function deleteProduct($productKey){
        if (!empty($this->products[$productKey]['lead_product_id'])){
            LeadProduct::query()->where('id', $this->products[$productKey]['lead_product_id'])->delete();
        }
        unset($this->products[$productKey]);
        $this->alert('success', '', 'Товар удален из лида!');
    }

    public function setProductNomenclature($event){
        $this->products[$event['productKey']]['name'] = $event['name'];
        $this->products[$event['productKey']]['nomenclature_product_id'] = $event['id'];
    }

    public function updatedProducts($value, $path){
//        dd($value, $path, $this->products);
        foreach ($this->products as $productKey => $product) {
            $this->products[$productKey]['sum'] = $product['price'] * $product['quantity'] - $product['discount'];
            $this->products[$productKey]['sum_vat_rate'] = (int)$product['vat_rate'] / 100 * $this->products[$productKey]['sum'];
            $this->products[$productKey]['total'] = $this->products[$productKey]['sum'] + $this->products[$productKey]['sum_vat_rate'];
        }
    }
}
