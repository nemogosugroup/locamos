<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ImportLog;
use App\Repositories\Interfaces\Backend\ImportLogRepositoryInterface;
use App\Repositories\Interfaces\Backend\PostRepositoryInterface;
use App\Services\ImportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Message;
use App\Helpers\Helpers;

class ImportController extends Controller
{
    /**
     * @var ImportLogRepositoryInterface
     */
    protected $importRepo;
    protected $postRepo;
    protected $importService;
    protected $msg;
    protected $helper;

    public function __construct(
        ImportLogRepositoryInterface $importRepo,
        PostRepositoryInterface $postRepo,
        Message $message,
        Helpers $helper,
        ImportLog $importModel,
        ImportLog $postModel,
        Category $modelCategory,
        ImportService $importService
    )
    {
        $this->importRepo = $importRepo;
        $this->postRepo = $postRepo;
        $this->msg = $message;
        $this->helper = $helper;
        $this->importRepo->addModel($importModel);
        $this->postRepo->addModel($postModel);
        $this->postRepo->setModelCategory($modelCategory);
        $this->importService = $importService;
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $params = $request->all();
            $lists = $this->importRepo->getAllLog($params);
            $listCategories = $this->postRepo->getCategories();
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
            return response()->json([$results], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function import(Request $request): \Illuminate\Http\JsonResponse
    {
        $params = $request->all();
        $data = $this->importService->import($params);
        if ($data['success']) {
            $results = array(
                'success' => true,
                'data' => $data['data'],
                'message' => $data['message'],
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
        } else {
            $results = array(
                'message' => $data['message'],
                'success' => false,
                'status' => Response::HTTP_OK
            );
            return response()->json([$results], Response::HTTP_OK);
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->importService->deleteAllPostByLogId($id);
            $results = array(
                'success' => true,
                'message' => $this->msg->deleteSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);

        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'success' => false,
                'status' => Response::HTTP_OK
            );
            return response()->json([$results], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
