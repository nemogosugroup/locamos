<?php

namespace App\Repositories\Backend;

use App\Repositories\Backend\Interfaces\BaseCategoryRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\CategoryMission;
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
        // if ($params['title'] != null) {
        //     //$models->where('title', 'Like', '%' . $params['title'] . '%');
        //     $models->whereTranslationLike('title', '%' . $params['title'] . '%');
        // }
        //$data = $models->latest()->paginate($params['limit']);
        $data = Category::translatedIn(app()->getLocale())->latest()->paginate($params['limit']);
        return $data->toArray();
    }

    public function getCategoriesParents( ){
        return $this->modelTranslate->select('id', 'title')->get()->toArray();
    }

    public function createTranslation(array $params, $category_id ){
        //dd($this->modelTranslate);
        $vi = $params['vi'];
        $vi['category_id'] = $category_id;
        $vi['locale'] = 'vi';
        $en = $params['en'];
        $en['category_id'] = $category_id;
        $en['locale'] = 'en';
        return $this->modelTranslate->insert([$en, $vi]);
        //return $this->modelTranslate->create($vi);
    }
}