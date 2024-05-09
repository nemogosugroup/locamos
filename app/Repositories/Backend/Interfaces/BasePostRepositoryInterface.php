<?php
namespace App\Repositories\Backend\Interfaces;

use App\Repositories\RepositoryInterface;

interface BasePostRepositoryInterface extends RepositoryInterface
{    
    public function getAllPost( array $param );
    public function addModel( $model );
    public function getPostById( $id );
    public function getCategories();
    public function setModelCategory($modelCategory);
    public function getAllLevel(array $params);
    public function getAllMission(array $params);
    public function getMissionById(array $params);
}