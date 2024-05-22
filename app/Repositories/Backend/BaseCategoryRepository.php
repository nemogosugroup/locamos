<?php

namespace App\Repositories\Backend;

use App\Models\Post;
use App\Repositories\Backend\Interfaces\BaseCategoryRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Category;
class BaseCategoryRepository extends BaseRepository implements BaseCategoryRepositoryInterface
{
    protected $model;
    protected $modelTranslate;
    public function __construct(){}
    //lấy model tương ứng
    public function getModel()
    {
        return $this->model;
    }

    public function addModel($modelData)
    {
        $this->model = $modelData;
    }
    //lấy model danh mục cha tương ứng
    public function setModelTranslate($modelTranslate)
    {
        $this->modelTranslate = $modelTranslate;
    }

    public function getAllCategories(array $params)
    {
        $models = $this->model;
        ## Search by title ##
         if ($params['title'] != null) {
             $models = Category::whereTranslationLike('title', '%' . $params['title'] . '%');
         }
        $data = $models->latest()->paginate($params['limit']);

        return $data->toArray();
    }

    public function getCategoriesParents( ){
        return $this->modelTranslate->select('id', 'title')->get()->toArray();
    }

    public function createTranslation(array $params, $category_id){
        $en = $params['en'];
        $en['category_id'] = $category_id;
        $en['locale'] = 'en';
        $en['created_at'] = now();
        $en['updated_at'] = now();

        return $this->modelTranslate->insert([$en]);
    }

    public function getDetailById(int $id)
    {
        return $this->model->query()->find($id);
    }

    public function destroy(int $id)
    {
        $this->modelTranslate->query()
            ->where('category_id', $id)
            ->delete();
        $this->model->query()
            ->where('id', $id)
            ->delete();
    }
}