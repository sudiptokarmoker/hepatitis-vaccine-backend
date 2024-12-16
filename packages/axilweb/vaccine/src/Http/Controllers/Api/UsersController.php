<?php

namespace Axilweb\Vaccine\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Axilweb\Vaccine\Interfaces\UsersRepositoryInterface;

class UsersController extends Controller
{
    public $user;
    public $userRepObj;
    public function __construct(UsersRepositoryInterface $userRepositoryInterfaceObj)
    {
        $this->userRepObj = $userRepositoryInterfaceObj;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $userCollectionObj = $this->userRepObj->lists($request);
            return self::return_response('User lists',  true, $userCollectionObj, count($userCollectionObj), 200);
        } catch (\Exception $e){
            return self::return_response('User lists error',  false, [
                'message' => $e->getMessage()
            ], 0, 417);
        }
    }

}
