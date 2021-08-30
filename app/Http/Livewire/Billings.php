<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Billings extends Component
{
    protected $listeners = [
        'makePayment' => 'openModalCreateBilling',
        'startEditBilling' => 'openModalEditBilling',
        'deleteBilling' => 'deleteBilling'
    ];

    public $modalCreateBilling = false;
    public $modalEditBilling = false;


    public $lead_id = null;
    public $billing_id = null;

    //var

    public $type = "Наличная оплата";
    public $total;
    public $made_at;

    public function openModalCreateBilling($id)
    {
        $this->lead_id = $id;
        $this->modalCreateBilling = true;
        $this->made_at = Carbon::now()->setTimezone(Auth::user()->timezone)->format('Y-m-d H:i');
    }

    public function closeModalCreateBilling()
    {
        $this->cleanBillingVar();
        $this->modalCreateBilling = false;
    }

    public function cleanBillingVar()
    {
        $this->type = "Наличная оплата";
        $this->total = null;
        $this->made_at = null;
    }

    public function getBillingVar()
    {
        $made_at = $this->made_at ? Carbon::parse($this->made_at, Auth::user()->timezone)->setTimezone('UTC')
                                : Carbon::now();

        return [
            'type' => $this->type,
            'total' => $this->total,
            'made_at' => $made_at
        ];
    }

    public function render()
    {
        return view('livewire.billings');
    }

    public function addBilling()
    {
        Billing::create( array_merge( 
            ['lead_id' => $this->lead_id], $this->getBillingVar()
        ));

        $this->cleanBillingVar();
        $this->closeModalCreateBilling();
        $this->emit('refreshLeadsCompnent');
    }

    public function openModalEditBilling($id)
    {
        $this->billing_id = $id;

        $billing = Billing::find($id);
        $this->type = $billing->type;
        $this->total = $billing->total;
        $this->made_at = $billing->made_at->format('Y-m-d H:i');

        $this->modalEditBilling = true;
    }

    public function closeModalEditBilling()
    {
        $this->billing_id = null;
        $this->cleanBillingVar();
        $this->modalEditBilling = false;
    }

    public function saveBilling()
    {
        Billing::find($this->billing_id)->update( $this->getBillingVar() );
        
        $this->emit('refreshLeadsCompnent');
        $this->closeModalEditBilling();
    }

    public function deleteBilling($id)
    {
        Billing::find($id)->delete();
        $this->emit('refreshLeadsCompnent');
    }
}