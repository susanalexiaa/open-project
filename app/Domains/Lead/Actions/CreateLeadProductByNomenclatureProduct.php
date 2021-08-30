<?php


namespace App\Domains\Lead\Actions;


use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Models\LeadProduct;
use App\Domains\Nomenclature\Models\NomenclatureProduct;

class CreateLeadProductByNomenclatureProduct
{

    protected $lead;
    protected $product;


    public function __construct(Lead $lead, NomenclatureProduct $product)
    {
        $this->lead = $lead;
        $this->product = $product;
    }

    public function execute(): bool
    {
        LeadProduct::query()->create([
            'name' => $this->product['name'],
            'quantity' => 1,
            'price' => $this->product->getPrice(1),
            'discount' => 0,
            'sum' => $this->product->getPrice(1),
            'vat_rate' => 0,
            'sum_vat_rate' => 0,
            'total' => $this->product->getPrice(1),
            'nomenclature_product_id' => $this->product->id,
            'lead_id' => $this->lead->id,
        ]);
        return true;
    }
}
