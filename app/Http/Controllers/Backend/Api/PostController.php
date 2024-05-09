<?php

namespace App\Http\Controllers\Backend\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Interfaces\Backend\PostRepositoryInterface;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\Post;
use App\Models\Category;
use App\Models\PostMeta;
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
        Post $model
        //PostMeta $postMeta 
    )
    {
        $this->repo = $repo;
        $this->msg = $message;
        $this->helper = $helper;
        $this->repo->addModel($model);
        $this->repo->setModelCategory($modelCategory);
        //$this->repo->setModelPostMeta($postMeta);
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
            $model = new Post();
            $param['slug'] = $this->helper->getSlug($param['title'], $model);
            $data = $this->repo->create($param);
            $result = $this->repo->getPostById($data->id);
            $results = array(
                'success' => true,
                'data' => $result,
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
            $data = $this->repo->update($id, $params);
            $result = $this->repo->getPostById($id);
            $results = array(
                'success' => true,
                'data' => $result,
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
            $this->repo->delete($id);
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
