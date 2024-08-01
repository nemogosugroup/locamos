<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Repositories\Interfaces\Backend\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\Category;

class MapController extends Controller
{
    protected $postRepo;
    protected $msg;
    protected $helpers;
    protected $defaultLocale;

    public function __construct(
        PostRepositoryInterface $postRepository,
        Post $model,
        Message $message,
        Helpers $helpers,
        Category $category
    ) {
        $this->postRepo = $postRepository;
        $this->msg = $message;
        $this->helpers = $helpers;
        $this->postRepo->addModel($model);
        $this->postRepo->setModelCategory($category);
        $this->defaultLocale = 'vi';
    }

    public function getAll(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $params = [
                'locale' => $request->input('locale') ?? $this->defaultLocale,
                'title' => $request->input('title'),
            ];
            $data = $this->postRepo->getAllPublic($params);
            $data = $this->helpers->convertArrayByLocale($data, $params['locale']);
            $results = array(
                'success' => true,
                'data' => $data,
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

    public function getList(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $params = $request->all();
            $params['locale'] = $request->input('locale') ?? $this->defaultLocale;
            $data = $this->postRepo->getAllPost($params);
            $data['data'] = $this->helpers->convertArrayByLocale($data['data'], $params['locale']);
            $categories = $this->postRepo->getCategories();
            $data['categories'] = $this->helpers->convertArrayCategoriesByLocale($categories, $params['locale']);
            $results = array(
                'success' => true,
                'data' => $data,
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

    public function getDetail(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->postRepo->getPostById($id);
            $data = $this->helpers->convertSingleByLocale(
                $data,
                $request->input('locale') ?? $this->defaultLocale
            );
            $results = array(
                'success' => true,
                'data' => $data,
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
}