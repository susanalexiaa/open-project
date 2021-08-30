<?php


namespace App\Domains\Nomenclature\Repositories;


use App\Domains\Foundation\Contracts\SelectableRepositoryInterface;
use App\Domains\Foundation\Repositories\BaseRepository;
use App\Domains\Foundation\Traits\SelectableRepository;
use App\Domains\Nomenclature\Models\NomenclatureCategory;

class NomenclatureCategoryRepository extends \App\Domains\Foundation\Repositories\BaseRepository implements SelectableRepositoryInterface
{

    protected $select2field = 'name';

    protected $modelClass = NomenclatureCategory::class;

    use SelectableRepository;

    public function all()
    {
        return $this->model->all();
    }
}
