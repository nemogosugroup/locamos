<?php

namespace App\Repositories\ImportLog;

use App\Models\ImportLog;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\Backend\ImportLogRepositoryInterface;

class ImportLogRepository extends BaseRepository implements ImportLogRepositoryInterface
{
    protected $model;

    public function __construct(ImportLog $model)
    {
        $this->addModel($model);
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
        $data = $model->whereIn('status', [IMPORT_STATUS['SUCCESS'], IMPORT_STATUS['DELETED']])
            ->latest()->paginate($params['limit']);

        return $data->toArray();
    }

    public function getLogById(int $id)
    {
        $data = $this->model->find($id);

        return $data ? $data->toArray() : [];
    }
}