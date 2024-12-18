<?php

namespace Axilweb\Vaccine\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Axilweb\Vaccine\Events\VaccineEmailNotificationToEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Axilweb\Vaccine\Interfaces\UsersRepositoryInterface;

class AuthController extends Controller
{
    protected $userObj;
    public function __construct(UsersRepositoryInterface $userObjInterface)
    {
        $this->userObj = $userObjInterface;
    }
    /**
     * Create User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'first_name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    //'password' => 'required',
                    'nid' => 'required|unique:users,nid'
                ]
            );
            if ($validateUser->fails()) {
                return self::return_response($validateUser->errors(),  false, [], 0, 401);
            }

            $user = $this->userObj->create($request->all());
            $selectedDate = $this->userObj->returnDateByCapacityId($request->center_capacity_root_id);

            if ($selectedDate) {
                /**
                 * send email to user
                 */
                $mailData = [
                    //'email' => $user->email,
                    'title' => 'Vacination Scheduled',
                    'body' => 'Scheulded successfully. Your vaccination date is : '.$selectedDate
                ];
                // Dispatch the event
                VaccineEmailNotificationToEvent::dispatch($user->email, $mailData);
            }

            return $user ? self::return_response(
                'User Created Successfully',
                true,
                [
                    'token' => $user->createToken("API TOKEN")->plainTextToken,
                    'scheduled_status' => $this->userObj->assignScheduleToUser($user, $selectedDate, $request->center_capacity_root_id)
                ],
                1,
                200
            ) :
                self::return_response(
                    'User not created',
                    false,
                    [
                        'token' => null,
                        'scheduled_status' => false
                    ],
                    0,
                    200
                );
        } catch (\Exception $e) {
            return self::return_response($e->getMessage(),  false, [], 0, 422);
        }
    }
    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );
            if ($validateUser->fails()) {
                return self::return_response($validateUser->errors(),  false, [], 0, 401);
            }
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return self::return_response('Email & Password does not match.',  false, [], 0, 401);
            }
            $user = $this->userObj->login($request->all());

            return self::return_response('User Logged In Successfully',  true, ['token' => $user->createToken("API TOKEN")->plainTextToken], 0, 200);

            //return self::return_response('User Logged In Successfully',  true,[], 0, 200);

        } catch (\Throwable $th) {
            return self::return_response($th->getMessage(),  false, [], 0, 500);
        }
    }
    public function resetPassword(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'new_password' => 'required|same:confirm_new_password',
                    'confirm_new_password' => 'required',
                    'user_id' => 'required'
                ]
            );
            if ($validateUser->fails()) {
                return self::return_response($validateUser->errors(),  false, [], 0, 401);
            }
            if ($request->has('user_id')) {
                $this->userObj->changePassword($request->all());
                return self::return_response('Password changed Successfully.',  true, [], 0, 200);
            } else {
                return self::return_response('Invalid User.',  false, [], 0, 417);
            }
        } catch (\Throwable $th) {
            return self::return_response($th->getMessage(),  false, [], 0, 500);
        }
    }
    /**
     * logout user
     */
    /*
    public function logoutUser(Request $request){
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        //Auth::logout();
        return self::return_response('User Logged Out Successfully',  true,[], 0, 200);
    }
    */
    public function logoutUser(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        //$request->user()->currentAccessToken()->delete();

        Auth::logout();
        return self::return_response('User Logged Out Successfully',  true, [], 0, 200);
    }
}
