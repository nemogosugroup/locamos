<?php

namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface PostRepositoryInterface extends RepositoryInterface
{
    public function getAllPublic( array $param );
    public function getAllPost( array $param );
    public function addModel( $model );
    public function getPostById( $id );
    public function getCategories();
    public function setModelCategory( $modelCategory );
    public function setModelPostMeta( $modelPostMeta );
    public function createPostMeta( array $params );
    public function updatePostMeta( $id, array $params );
    public function deletePostMeta( $id );
    public function setModelTranslate($modelTranslate);
    public function createTranslation(array $params, int $post_id);
    public function destroy(int $id);
}