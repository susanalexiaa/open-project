<?php

namespace App\Domains\User\Repositories;

use App\Domains\Foundation\Contracts\SelectableRepositoryInterface;
use App\Domains\Foundation\Repositories\BaseRepository;
use App\Domains\Foundation\Traits\SelectableRepository;
use App\Domains\Nomenclature\Models\Nomenclature;
use App\Domains\Nomenclature\Models\NomenclatureProduct;
use App\Models\User;

class UserRepository extends BaseRepository implements SelectableRepositoryInterface
{

    protected $select2field = 'name';

    protected $modelClass = User::class;

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
        return User::query()->where('recording_allowed', true)->select('name', 'id')->get();
    }

}
