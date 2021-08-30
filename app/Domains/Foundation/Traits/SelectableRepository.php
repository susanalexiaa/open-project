<?php
namespace App\Domains\Foundation\Traits;

trait SelectableRepository{
    public function findForSelect(string $name): array
    {
        $elements = [];
        $items = $this->model->where($this->select2field, 'like', '%' . $name . '%')
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
