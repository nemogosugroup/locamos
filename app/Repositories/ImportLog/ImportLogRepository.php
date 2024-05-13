<?php

namespace App\Repositories\Post;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\Backend\ImportLogRepositoryInterface;

class ImportLogRepository extends BaseRepository implements ImportLogRepositoryInterface
{
    protected $model;

    public function __construct()
    {
    }

    public function getModel()
    {
        return $this->model;
    }

    public function addModel($modelData)
    {
        $this->model = $modelData;
    }

    public function getAllLog(array $params)
    {
        $model = $this->model->query();
        $data = $model->with(['posts' => function ($query) {
            $query->select('id');
        }])->latest()->paginate($params['limit']);

        return $data->toArray();
    }

    public function getLogById(int $id)
    {
        $data = $this->model->with(['posts' => function ($query) {
            $query->select('id');
        }])->find($id);

        return $data ? $data->toArray() : [];
    }
}