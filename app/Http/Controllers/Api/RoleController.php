<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Acl;
use App\Helpers\Message;
class RoleController extends Controller
{
    protected $msg;
    public function __construct(Message $message)
    {
        $this->middleware('auth');
        $this->msg = $message;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        try {
            $result = array(
                array('key' => Acl::ROLE_USER, 'name' => 'User'),
                array('key' => Acl::ROLE_ADMIN, 'name' => 'Administrator'),
                array('key' => Acl::ROLE_SUPER_ADMIN, 'name' => 'Super Administrator'),
            );
            $results = array(
                'message' => $this->msg->getSuccess(),
                'data' => $result,
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
}
