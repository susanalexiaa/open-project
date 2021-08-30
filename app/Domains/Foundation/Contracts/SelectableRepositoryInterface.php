<?php
namespace App\Domains\Foundation\Contracts;

/**
 * Interface SelectableRepositoryInterface
 * @package App\Domains\Foundation\Contracts
 * @property string $select2field
 */
interface SelectableRepositoryInterface {
    public function findForSelect(string $name);
}
