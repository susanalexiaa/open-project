<?php

namespace App\Domains\Nomenclature\Repositories;

use App\Domains\Foundation\Contracts\SelectableRepositoryInterface;
use App\Domains\Foundation\Repositories\BaseRepository;
use App\Domains\Foundation\Traits\SelectableRepository;
use App\Domains\Nomenclature\Models\Nomenclature;
use App\Domains\Nomenclature\Models\NomenclatureProduct;

class NomenclatureProductRepository extends BaseRepository implements SelectableRepositoryInterface
{

    protected $select2field = 'name';

    protected $modelClass = NomenclatureProduct::class;

    use SelectableRepository;

    public function all()
    {
        return $this->model->all();
    }

    public function findForSelect(string $name): array
    {
        $elements = [];
        $query = explode(' ', $name);
        $query = '*' .  implode('**', $query) . '*';
        $items = $this->model->query()
            ->selectRaw("`id`, `name`, MATCH(name) AGAINST('". $query ."' IN BOOLEAN MODE) AS `score`")
            ->orderByDesc('score')
            ->take(10)
            ->get();
        foreach ($items as $item){
            $element = [
                'id' => $item->id,
                'text' => $item->name
            ];
            $elements[] = $element;
        }
        return $elements;
    }

    public function export(){
        $result = [];
        $products = NomenclatureProduct::query()->where('export', true)->get();
        foreach ($products as $product) {
            $result[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->getPrice(1)
            ];
        }
        return $result;
    }

}
