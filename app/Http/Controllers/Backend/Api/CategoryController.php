<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Backend\Interfaces\BaseCategoryRepositoryInterface;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\Category;
use App\Models\CategoryTranslation;

class CategoryController extends Controller
{
    /**
     * @var BaseCategoryRepositoryInterface
     */
    protected $cateRepo;
    protected $msg;
    protected $helper;

    public function __construct(BaseCategoryRepositoryInterface $cateRepo, Message $message, Helpers $helper, Category $model, CategoryTranslation $modalTranslation)
    {
        $this->cateRepo = $cateRepo;
        $this->msg = $message;
        $this->helper = $helper;
        $this->cateRepo->addModel($model);
        $this->cateRepo->setModelTranslate($modalTranslation);
    }

    public function index(Request $request)
    {
        try {
            $params = $request->all();
            // $testData = Category::translatedIn('en')->latest()->paginate(1);
            // dd($testData);
            $lists = $this->cateRepo->getAllCategories($params);
            $results = array(
                'success' => true,
                'data' => $lists,
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
        // $product = $this->cateRepo->find($id);

        // return view('home.product', ['product' => $product]);
    }

    public function create(Request $request)
    {
        try {
            $param = $request->all();  
            $params = $param['vi'];
            $params['icon'] = $param['icon'];
            $data = $this->cateRepo->create($params);           
            $data = $this->cateRepo->createTranslation($param, $data->id);           
            $results = array(
                'success' => true,
                'data' => $data,
                'message' => $this->msg->createSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                //'message' => $this->msg->createError(),
                'message' => $th->getMessage(),
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
            $data = $this->cateRepo->update($id, $params);
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
            $this->cateRepo->delete($id);
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
