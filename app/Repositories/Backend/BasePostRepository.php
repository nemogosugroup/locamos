<?php

namespace App\Repositories\Backend;

use App\Repositories\Backend\Interfaces\BasePostRepositoryInterface;
use App\Repositories\BaseRepository;
class BasePostRepository extends BaseRepository implements BasePostRepositoryInterface
{
    protected $model;
    protected $modelCategory;
    public function __construct(){}
    //get model
    public function getModel()
    {
        return $this->model;
    }
    // set model
    public function addModel($modelData)
    {
        $this->model = $modelData;
    }
    // set model category
    public function setModelCategory($modelCategory)
    {
        $this->modelCategory = $modelCategory;
    }    
    // get all post
    public function getAllPost(array $params)
    {
        $model = $this->model->query();
        ## Search by title ##
        if ($params['title'] != null) {
            $model->where('title', 'Like', '%' . $params['title'] . '%');
        }
        $data = $model->with(['category' => function ($query) {
            $query->select('id', 'title');
        }])->latest()->paginate($params['limit']);
        return $data->toArray();
    }
    // get post by id
    public function getPostById($id){
        $data = $this->model->with(['category' => function ($query) {
            $query->select('id', 'title');
        }])->find($id);
        return $data->toArray();
    }
    // get all categories
    public function getCategories(){
        return $this->modelCategory->select('id', 'title')->get()->toArray();
    }

    // get all level
    public function getAllLevel(array $params)
    {
        $model = $this->model->query();
        ## Search by title ##
        if ($params['level'] != null) {
            $model->where('level', 'Like', '%' . $params['level'] . '%');
        }
        $data = $model->latest()->paginate($params['limit']);
        return $data->toArray();
    }

     // get all mission
    public function getAllMission(array $params)
    {
        $model = $this->model->query();
         ## Search by title ##
        if ($params['title'] != null) {
            $model->where('title', 'Like', '%' . $params['title'] . '%');
        }
        $data = $model->with(['category' => function ($query) {
            $query->select('id', 'title');
        }, 'equipment', 'level', 'childrents'])->latest()->paginate($params['limit']);
        return $data->toArray();
    }

    public function getAll(){
        return $this->model->where('parent_id', 0)->get()->toArray();
    }

    // get post by id mission
    public function getMissionById($id){
        $data = $this->model->with(['category' => function ($query) {
            $query->select('id', 'title');
        }, 'equipment', 'level', 'childrents'])->find($id);
        return $data->toArray();
    }
}