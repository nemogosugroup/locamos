<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Interfaces\QuestRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\SubCategoryMission;
class QuestController extends Controller
{
    /**
     * @var QuestRepositoryInterface
     */
    protected $repo;
    protected $repoUser;
    protected $msg;
    protected $helper;
    protected $model;
    public function __construct(
        QuestRepositoryInterface $repo, 
        UserRepositoryInterface $user, 
        Message $message, 
        Helpers $helper
    )
    {
        $this->repo = $repo;
        $this->repoUser = $user;
        $this->msg = $message;
        $this->helper = $helper;
    }

    public function index(Request $request)
    {
        try {   
            $params = $request->all();     
            $data = $this->repo->getAllQuestByChapter($params);
            //$data = $data->pluck('id');
            $results = array(
                'success' => true,
                'data' => $data->toArray(),
                'message' => $this->msg->getSuccess(),
                'status' => Response::HTTP_OK
            );
            if( !isset($params['chapter']) ){
                $chapters = $this->repo->getAllChapter();
                $results['chapters'] = $chapters;
            }
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
    // insert nhiêm vụ by user
    public function create(Request $request)
    {
        try {   
            $params = $request->all();     
            $data = $this->repo->createQuestByUser($params);
            $results = array(
                'success' => true,
                'data' => $data->toArray(),
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
    // update status nhiệm vụ by user
    public function update(Request $request)
    {
        try {   
            $params = $request->all();  
            $data = $this->repo->updateQuestByUser($params);
            $results = array(
                'success' => false,
                'data' => false,
                'message' => $this->msg->getSuccess(),
                'status' => Response::HTTP_OK
            ); 
            if ($data) {
                $results['success'] = true;
                $results['data'] = $data;
            }                       
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
