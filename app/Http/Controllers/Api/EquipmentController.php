<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserEquipment;
use App\Repositories\Interfaces\Backend\UserEquipmentRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\GosuApi;
use App\Helpers\Message;
use App\Helpers\Helpers;

class EquipmentController extends Controller
{
    protected $gosuApi;
    protected $userRepo;
    protected $userEquipmentRepo;
    protected $msg;
    protected $helpers;
    public function __construct(
        GosuApi $gosuApi,
        UserRepositoryInterface $userRepository,
        UserEquipmentRepositoryInterface $userEquipmentRepository,
        UserEquipment $model,
        Message $message,
        Helpers $helpers
    )
    {
        $this->gosuApi  = $gosuApi;
        $this->userRepo = $userRepository;
        $this->userEquipmentRepo = $userEquipmentRepository;
        $this->msg = $message;
        $this->helpers = $helpers;
        $this->userEquipmentRepo->addModel($model);
    }

    public function getInventoryItems(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $params = [
                'position' => $request->input('position'),
                'user_id' => auth()->user()->id,
            ];
            $list = $this->userEquipmentRepo->getInventoryItemsByUserId($params);
            $results = array(
                'success' => true,
                'data' => $list,
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

    public function getEquippedItems(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!is_null($request->input('profile_id'))) {
                $user = $this->userRepo->getUserByProfileId($request->input('profile_id'));
                $userId = $user->id ?? auth()->user()->id;
            }
            $list = $this->userEquipmentRepo->getEquippedItemsByUserId($userId ?? auth()->user()->id);
            $results = array(
                'success' => true,
                'data' => $list,
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

    public function removeEquippedItem(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->userEquipmentRepo->removeEquippedItemById($request->input('ue_id'));
            $results = array(
                'success' => true,
                'message' => $this->msg->updateSuccess(),
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

    public function useEquipment(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->userEquipmentRepo->useEquipmentById($request->input('ue_id'), $request->input('position'));
            $results = array(
                'success' => true,
                'message' => $this->msg->updateSuccess(),
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
}
