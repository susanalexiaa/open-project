<?php

namespace App\Domains\Nomenclature\Repositories;

use App\Domains\Foundation\Contracts\SelectableRepositoryInterface;
use App\Domains\Foundation\Repositories\BaseRepository;
use App\Domains\Foundation\Traits\SelectableRepository;
use App\Domains\Nomenclature\Models\Nomenclature;

class NomenclatureRepository extends BaseRepository implements SelectableRepositoryInterface
{

    protected $select2field = 'name';

    protected $modelClass = Nomenclature::class;

    use SelectableRepository;

    public function all()
    {
        return $this->model->all();
    }
}
