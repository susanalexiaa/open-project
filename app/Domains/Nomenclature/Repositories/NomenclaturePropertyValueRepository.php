<?php

namespace App\Domains\Nomenclature\Repositories;

use App\Domains\Foundation\Contracts\SelectableRepositoryInterface;
use App\Domains\Foundation\Repositories\BaseRepository;
use App\Domains\Foundation\Traits\SelectableRepository;
use App\Domains\Nomenclature\Models\Nomenclature;
use App\Domains\Nomenclature\Models\NomenclaturePropertyValue;

class NomenclaturePropertyValueRepository extends BaseRepository
{

    protected $select2field = 'name';

    protected $modelClass = NomenclaturePropertyValue::class;

    use SelectableRepository;

    public function all()
    {
        return $this->model->all();
    }

    public function findForSelect(string $name, int $propertyId): array
    {
        $elements = [];
        $items = $this->model->where($this->select2field, 'like', '%' . $name . '%')
            ->where('property_id', $propertyId)
            ->select(['id', 'name'])
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
}
