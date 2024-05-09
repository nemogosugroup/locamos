<?php

namespace App\Repositories\Post;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\Backend\PostRepositoryInterface;
class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    protected $model;
    protected $modelCategory;
    protected $modelPostMeta;
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
    //set model post meta  
    public function setModelPostMeta($modelPostMeta)
    {
        $this->modelPostMeta = $modelPostMeta;
    }    
    public function createPostMeta(array $params){
        return $this->modelPostMeta->create($params);
    }
    public function updatePostMeta($id, array $params){
        $result = $this->modelPostMeta->find($id);
        if ($result) {
            $result->update($params);
            return $result;
        }
        return false;
    }
    public function deletePostMeta($id){
        $result = $this->modelPostMeta->find($id);
        if ($result) {
            $result->delete();
            return true;
        }
        return false;
    }
    // get all post
    public function getAllPost(array $params)
    {
        $model = $this->model->query();       
        ## Search by category ##
        if(isset($params['slug']) && $params['slug'] != null){
            $category_id = $this->modelCategory->where('slug', $params['slug'])->select('id')->first();
            if($category_id)
                $model->where('category_id', $category_id['id']);
        }
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
        return $this->modelCategory->select('id','slug','title')->get()->toArray();
    }
}