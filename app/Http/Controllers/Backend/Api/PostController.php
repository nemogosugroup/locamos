<?php

namespace App\Http\Controllers\Backend\Api;
use App\Http\Controllers\Controller;
use App\Models\PostTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Interfaces\Backend\PostRepositoryInterface;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\Post;
use App\Models\Category;
class PostController extends Controller
{
    /**
     * @var PostRepositoryInterface
     */
    protected $repo;
    protected $msg;
    protected $helper;

    public function __construct(
        PostRepositoryInterface $repo, 
        Message $message, 
        Helpers $helper, 
        Category $modelCategory, 
        Post $model,
        PostTranslation $modelTranslation
    )
    {
        $this->repo = $repo;
        $this->msg = $message;
        $this->helper = $helper;
        $this->repo->addModel($model);
        $this->repo->setModelCategory($modelCategory);
        $this->repo->setModelTranslate($modelTranslation);
    }

    public function index(Request $request)
    {
        try {
            $params = $request->all();
            $lists = $this->repo->getAllPost($params);
            $listCategories = $this->repo->getCategories();
            $results = array(
                'success' => true,
                'data' => $lists,
                'categories' => $listCategories,
                'message' => $this->msg->getSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'success' => false,
                'status' => Response::HTTP_OK
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        }           
    }

    public function show($id)
    {
        // $product = $this->repo->find($id);

        // return view('home.product', ['product' => $product]);
    }

    public function create(Request $request)
    {
        try {
            $param = $request->all();
            $params = $param['vi'];
            $params['category_id'] = $param['category_id'];
            $params['manager'] = $param['manager'];
            $params['lat'] = $param['lat'];
            $params['long'] = $param['long'];
            $params['feature_image'] = $param['feature_image'];
            $params['images'] = json_encode(explode(',', $param['images']));
            $data = $this->repo->create($params);
            $data = $this->repo->createTranslation($param, $data->id);
            $results = array(
                'success' => true,
                'data' => $data,
                'message' => $this->msg->createSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                'message' => $this->msg->createError(),
                'success' => false,
                'status' => Response::HTTP_OK
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    public function update(Request $request, $id)
    {       
        try {
            $params = $request->all();
            $params['images'] = json_encode($params['images']);
            $data = $this->repo->update($id, $params);
            $results = array(
                'success' => true,
                'data' => $data,
                'message' => $this->msg->updateSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                'message' => $this->msg->updateError(),
                'success' => false,
                'status' => Response::HTTP_OK
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    public function destroy($id)
    {
        try {           
            $this->repo->destroy($id);
            $results = array(
                'success' => true,
                'message' => $this->msg->deleteSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                'message' => $this->msg->deleteError(),
                'success' => false,
                'status' => Response::HTTP_OK
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }
}
