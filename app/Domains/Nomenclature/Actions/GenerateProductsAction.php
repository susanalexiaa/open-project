<?php


namespace App\Domains\Nomenclature\Actions;


use App\Domains\Nomenclature\Models\Nomenclature;
use App\Domains\Nomenclature\Models\NomenclatureProduct;
use App\Domains\Nomenclature\Models\NomenclaturePropertyValue;
use Illuminate\Support\Collection;

class GenerateProductsAction
{
    /**
     * @var Nomenclature
     */
    protected $nomenclature;

    /**
     * @var array
     */
    protected $startArray;

    /**
     * @var Collection
     */
    protected $preparedValues = [];


    public function __construct(Nomenclature $nomenclature)
    {
        $this->nomenclature = $nomenclature;
    }

    public function execute($startArray): bool
    {
        $this->startArray = $startArray;
        $matrix = $this->getAllIds();
        $result = $this->multiplicationMatrix($matrix);
        $this->prepareProperties($matrix);
        $this->createProducts($result);
        return true;
    }

    protected function getNotEqualProperties($propertyId, $ids): \Illuminate\Support\Collection
    {
        return NomenclaturePropertyValue::query()->where('property_id', $propertyId)->whereNotIn('id', $ids)->pluck('id');
    }

    protected function getAllIds(): array
    {
        $result = [];
        foreach ($this->startArray as $item) {
            if (!empty($item['values'])){
                if ((int)$item['condition'] === 1){
                    $result[] = $item['values'];
                }else{
                    $result[] = $this->getNotEqualProperties($item['id'], $item['values']);
                }
            }
        }
        return $result;
    }

    protected function multiplicationMatrix(array $matrix): \Illuminate\Support\Collection
    {
        $result = [];
        if (!empty($matrix[0])){
            foreach ($matrix[0] as $startCellKey => $cell) {
                $arr = [$cell];
                $loop = true;
                $i2 = 1;
                while ($loop === true){
                    if (!empty($matrix[$i2])){
                        foreach ($arr as $itemKey => $item) {
                            for ($j = 0; $j < count($matrix[$i2]); $j++){
                                if ($j === 0){
                                    $arr[$itemKey] .= '_' . $matrix[$i2][$j];
                                }else{
                                    $arr[] = $item . '_' . $matrix[$i2][$j];
                                }
                            }
                        }
                        $i2++;
                    }else{
                        $result[$startCellKey] = $arr;
                        $loop = false;
                    }
                }
            }
        }
        return collect($result)->collapse();
    }

    protected function createProducts(Collection $result){
        foreach ($result as $item) {
            $props = explode('_', $item);
            $name = $this->nomenclature->name;
            $propNames = [];
            foreach ($props as $prop) {
                $model = $this->preparedValues->firstWhere('id', $prop);
                $propNames[] = $model->property->name . ': ' . $model->name;
            }
            $name .= ' (' . implode(', ', $propNames) . ')';
            $product = new NomenclatureProduct();
            $product->name = $name;
            $product->sort = 0;
            $product->nomenclature_id = $this->nomenclature->id;
            $product->save();
            $product->values()->sync($props);
        }
    }

    protected function prepareProperties($matrix){
        $collect = collect($matrix)->collapse();
        $this->preparedValues = NomenclaturePropertyValue::query()->whereIn('id', $collect)->with('property')->get();
    }
}
