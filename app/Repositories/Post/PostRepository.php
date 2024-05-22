<?php

namespace App\Repositories\Post;

use App\Models\Post;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\Backend\PostRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    protected $model;
    protected $modelTranslate;
    protected $modelCategory;
    protected $modelPostMeta;

    public function __construct()
    {
    }

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

    public function createPostMeta(array $params)
    {
        return $this->modelPostMeta->create($params);
    }

    public function updatePostMeta($id, array $params)
    {
        $result = $this->modelPostMeta->find($id);
        if ($result) {
            $result->update($params);
            return $result;
        }
        return false;
    }

    public function deletePostMeta($id)
    {
        $result = $this->modelPostMeta->find($id);
        if ($result) {
            $result->delete();
            return true;
        }
        return false;
    }

    // get all post
    public function getAllPublic(array $params)
    {
        $posts = Cache::get('all_post_public');

        if (isset($posts)) {
            $result = unserialize($posts);
        } else {
            $model = $this->model->query();
            $data = $model->with(['category' => function ($query) {
                $query->select('id', 'icon');
            }])->latest()->get();

            Cache::put('all_post_public', serialize($data->toArray()), now()->addMonth());
            $result = $data->toArray();
        }

        return $result;
    }

    // get all post
    public function getAllPost(array $params)
    {
        $model = $this->model->query();
        ## Search by title ##
        if ($params['title'] != null) {
            $model = Post::whereTranslationLike('title', '%' . $params['title'] . '%');
        }
        ## Search by category ##
        if (isset($params['category_id']) && $params['category_id'] != null) {
            $model->where('category_id', $params['category_id']);
        }
        $data = $model->with(['category' => function ($query) {
            $query->select('id', 'icon');
        }])->latest()->paginate($params['limit']);

        return $data->toArray();
    }

    // get post by id
    public function getPostById($id)
    {
        $data = $this->model->with(['category' => function ($query) {
            $query->select('id');
        }])->find($id);

        return $data ? $data->toArray() : [];
    }

    // get all categories
    public function getCategories()
    {
        return $this->modelCategory->select('id', 'slug')->get()->toArray();
    }

    public function setModelTranslate($modelTranslate)
    {
        $this->modelTranslate = $modelTranslate;
    }

    public function createTranslation(array $params, int $post_id)
    {
        $en = $params['en'];
        $en['post_id'] = $post_id;
        $en['locale'] = 'en';
        $en['created_at'] = now();
        $en['updated_at'] = now();

        return $this->modelTranslate->insert([$en]);
    }

    public function destroy(int $id)
    {
        $this->modelTranslate->query()
            ->where('post_id', $id)
            ->delete();
        $this->model->query()
            ->where('id', $id)
            ->delete();
    }

    public function deleteByImportLogId(int $importId)
    {
        $this->model->query()
            ->where('import_log_id', $importId)
            ->each(function ($item) {
                $this->destroy($item->id);
            });
        sleep(1);
        $exists = $this->model->query()->where('import_log_id', $importId)->exists();
        if ($exists) {
            $this->deleteByImportLogId($importId);
        }
    }

    public function checkLatLongExisted($lat, $long, $categoryId)
    {
        return $this->model->query()
            ->where([
                'category_id' => $categoryId,
                'lat' => $lat,
                'long' => $long,
            ])->exists();
    }
}