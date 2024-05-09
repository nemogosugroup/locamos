<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Validator;
use App\Models\User;
use App\Helpers\GosuApi;
use App\Helpers\Message;
use App\Helpers\Helpers;
use Carbon\Carbon;
use App\Models\Acl;
use App\Models\Role;
use App\Repositories\User\UserRepository;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    protected $gosuApi;
    protected $userRepo;
    protected $msg;
    protected $helpers;
    public function __construct(GosuApi $gosuApi, UserRepository $userRepository, Message $message, Helpers $helpers)
    {
        //$this->middleware('auth');
        $this->gosuApi  = $gosuApi;
        $this->userRepo = $userRepository;
        $this->msg = $message;
        $this->helpers = $helpers;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($data)
    {
        $results = User::create($data);
        $role = Role::findByName(Acl::ROLE_USER);
        $results->syncRoles($role);
        return $results;
    }
    public function login(Request $request)
    {
        try {
            $email = $request->email;
            $password = $request->password;
            $results = $this->userRepo->loginUser(['email'=>$email, 'password'=>$password]);            
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'data' => false,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
       
    }
    public function logout(Request $request)
    {
        try {
            $currentUser = Auth::user();
            $currentUser->tokens()->delete();
            $data['logout'] = true;
            $results = array(
                'message' => $this->msg->logoutSuccess(),
                'data' => $data,
                'success' => true,
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'success' => false,
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
        }
       
    }
    public function roles(Request $request)
    {        
        try {
            $token= request()->bearerToken();
            if (!$token) {
                return response()->json([
                    'message' => $this->msg->getError(), 
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $response = explode('|',$token);
            $token = $response[1];
            $data['roles'] = Auth::user()->roles->pluck('name');
            return response()->json([
                'message' => $this->msg->getSuccess(), 
                'data' => $data,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $this->msg->getError(), 
                'error' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function list(Request $request){
        try {            
            $page  = $request->page ? $request->page : 1;
            $limit = $request->limit ? $request->limit : 1;
            $name  = $request->fullname ? $request->fullname : null;
            $email = $request->email ? $request->email : null;
            $params = array(
                'page'  => $page,
                'limit' => $limit,
                'name'  => $name,
                'email' => $email,
            );
            $data = $this->userRepo->getAllUsers($params);
            $results = array(
                'message' => $this->msg->getSuccess(),
                'data' => $data,
                'success' => true,
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'data' => false,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    // cập nhập thông tin user
    public function update(Request $request, $id){
        try { 
            $file       = $request->file('file');
            $dataUpdate = json_decode($request->input('data'), true);
            if($file){
                $dataUpdate['avatar'] = $this->helpers->upLoadFiles($file);
            }
            $dataUpdate['achievements'] = empty($dataUpdate['achievements']) ? null : $dataUpdate['achievements'];
            $params = array(
                'id' => $id,
                'field' => $dataUpdate,
            );            
            $data = $this->userRepo->updateUsers($params); 
            $results = [];  
            $results['status'] = Response::HTTP_OK;
            $results['data'] = false;
            $results['success'] = false;
            $results['message'] = $this->msg->updateError();
            if (!$data) {
                return response()->json($results);
            }
            $results['data'] = $data;
            $results['success'] = true;
            $results['message'] = $this->msg->updateSuccess();
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'data' => false,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json($results);
        }        
    }
    public function export(){
        try { 
            $data = $this->userRepo->exportUsers();    
            $results = [];  
            $results['status'] = Response::HTTP_OK;
            $results['success'] = false;
            $results['message'] = $this->msg->getSuccess();
            if (!$data) {
                $results['message'] = $this->msg->getError();
                return response()->json();
            }    
            $results['data'] = $data;
            $results['success'] = true;
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'data' => false,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json($results);
        }        
    }

    public function info(){
        try {
            $results = $this->userRepo->infoUser();            
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'data' => false,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function trainingInfo(Request $request) {
        try {
            $results = $this->userRepo->trainingInfoUser($request->all());
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'data' => false,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function trainingDetailInfo(Request $request) {
        try {
            $results = $this->userRepo->trainingDetailInfoUser($request->all());
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'data' => false,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //lấy tất cả nhân sự đang làm việc
    public function employee(){
        try {
            $results = $this->userRepo->listEmployee();            
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'data' => false,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // tìm kiếm thông tin nhân sự
    public function store(Request $request){
        try {
            $profile_id = $request->profile_id ? $request->profile_id : null;
            $params = array(
                'profile_id'  => $profile_id
            );
            $results = $this->userRepo->store($params);         
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'data' => false,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json($results);
        }
    }

    // mua khoá học hoặc mua trang bị 
    public function add(Request $request){
        try {
            $params = $request->all();
            $results = $this->userRepo->addCourseEquipment($params);         
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'data' => false,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json($results);
        }
    }

    // mua khoá học hoặc mua trang bị 
    public function getCourseEquipment(Request $request){
        try {
            $params = $request->all();
            $results = $this->userRepo->getCourseEquipment($params);         
            return response()->json($results);
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'data' => false,
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json($results);
        }
    }
    public function user(Request $request){
        $user = $request->user();
        $results = array(
            'message' => 'success',
            'data' => [
                'email'=> $user->email,
                'name' => $user->first_name.' '.$user->last_name,
                'email_verified_at' => $user->email_verified_at
            ],
            'success' => true,
            'status' => 200
        );
        
        return response()->json($results);
    }

    

    public function checkLogin(Request $request)
    {
        $state =$request->state;
        $redirectUrl =$request->redirect_uri;
        return redirect($redirectUrl. '?token=abcd&state='.$state);
              
    }
}
