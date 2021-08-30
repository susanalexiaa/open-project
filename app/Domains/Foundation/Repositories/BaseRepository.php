<?php

namespace App\Domains\Foundation\Repositories;

use Illuminate\Database\Eloquent\Model;
use Domain\Foundation\Exceptions\RepositoryException;

abstract class BaseRepository
{
    /**
     * @var string
     */
    protected $modelClass;


    /**
     * @var string
     */

    protected $select2field;

    /**
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @throws \Domain\Foundation\Exceptions\RepositoryException
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Set the repository model class to instantiate.
     *
     * @return \Domain\Foundation\Repositories\BaseRepository
     * @throws \Domain\Foundation\Exceptions\RepositoryException
     */
    public function setModel(): BaseRepository
    {
        if (empty($this->modelClass)) {
            throw new RepositoryException('Child classes of BaseRepository must define {modelClass} property');
        }

        $this->model = \App::make($this->modelClass);

        if (! $this->model instanceof Model) {
            throw new RepositoryException("Class {$this->model} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this;
    }

    /**
     * Finds a model by its id
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Creates an entry in the database
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create($data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Updates current model by id
     *
     * @param int $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, $data): Model
    {
        $this->model = $this->model->find($id);
        $this->model->update($data);

        return $this->model;
    }

    /**
     * Deletes current model by id
     *
     * @param int $id
     * @return int
     */
    public function delete($id): int
    {
        return $this->model->destroy($id);
    }
}
