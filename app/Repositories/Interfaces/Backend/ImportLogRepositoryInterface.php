<?php

namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface ImportLogRepositoryInterface extends RepositoryInterface
{
    public function addModel($model);
    public function getAllLog(array $param);
    public function getLogById(int $id);
}