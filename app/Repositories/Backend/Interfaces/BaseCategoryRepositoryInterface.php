<?php
namespace App\Repositories\Backend\Interfaces;

use App\Repositories\RepositoryInterface;

interface BaseCategoryRepositoryInterface extends RepositoryInterface
{
    
    public function getAllCategories( array $param );
    public function addModel( $model );
    public function getCategoriesParents();
    public function setModelTranslate($modelTranslate);
    public function createTranslation(array $params, $category_id );
}