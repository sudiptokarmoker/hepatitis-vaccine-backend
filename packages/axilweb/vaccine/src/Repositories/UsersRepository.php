<?php

namespace Axilweb\Vaccine\Repositories;

use App\Enums\ScheduleStatus;
use App\Models\User;
use Axilweb\Vaccine\Interfaces\UsersRepositoryInterface;
use Axilweb\Vaccine\Models\UserVaccinationDetailsModel;
use Axilweb\Vaccine\Models\VaccinationCenterCapacityLimitDayWiseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersRepository implements UsersRepositoryInterface
{
    public function create(array $data){
        $user = new User();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        //$user->password = Hash::make($data['password']);
        $user->password = Hash::make('password123!');
        $user->uid = (string) Str::uuid();
        $user->nid = $data['nid'];
        $user->save();

        return $user;
    }
    public function update(array $data){

    }
    public function find($id){

    }
    public function findAll($id){

    }
    public function delete($id)
    {

    }
    public function login(array $data){
        //$user = User::where('email', $data['email'])->first();
        $user = User::where('email', $data['email'])
                ->where('isAdmin', true)
                ->first();
        return $user;
    }
    public function sendUserOtp(array $data){
        $otpData=[];
        $otpData['checkUserEmailExists']= $checkUserEmailExists = User::where('email',$data['email'])->first();
        if(isset($checkUserEmailExists->id)) {
            // otp set & email send
            $uniqueNumber = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $otpData['updateData']= $updateData = User::where('id',$checkUserEmailExists->id)->update(['otp'=>$uniqueNumber]);
            $otpData['userData']= $userData = User::find($checkUserEmailExists->id);
            //email send start
            if (isset($uniqueNumber) && isset($userData->name)) {
                $details = [
                    'otp'  =>  $uniqueNumber,
                    'name'      =>  $userData->name
                ];
                // \Mail::to($request->email)->send(new \App\Mail\SendOtp($details));
            }
        }
        return $otpData;
    }
    public function changePassword(array $data)
    {
        $userId = $data['user_id'];
        $password = Hash::make($data['new_password']);
        User::updateUserPassword($userId, $password);
    }
    public function userInfo($id){
        $userData = User::leftjoin('user_personal_details','user_personal_details.user_id','users.id')
            ->leftjoin('user_contact_details','user_contact_details.user_id','users.id')
            ->leftjoin('user_media','user_media.user_id','users.id')
            ->where('users.id',$id)
            ->first();
        return $userData;
    }
    public function lists($request)
    {
        //$userLists = User::where('isAdmin', '=', false)->get();
        //return $userLists;

        $results = DB::table('users')
            ->leftJoin('user_vaccination_details', 'users.id', '=', 'user_vaccination_details.user_id')
            ->leftJoin('vaccination_center', 'vaccination_center.id', '=', 'user_vaccination_details.center_id')
            ->select(
                'users.id as user_id',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.email as user_email',
                'users.nid as nid',
                'user_vaccination_details.vaccine_scheduled_date as vaccine_scheduled_date',
                'user_vaccination_details.status as status',
                'vaccination_center.center_name as center_name'
            )
            ->where('users.isAdmin', '=', false) // Check if the user is not an admin
            ->get();

        return $results;
    }
    /**
     * assign scheduled to user
     */
    public function assignScheduleToUser($user, $date)
    {
        $today = Carbon::today()->format('Y-m-d');
        $centerLoadByDateCheckObj = VaccinationCenterCapacityLimitDayWiseModel::where('date_of_vaccination', '>', $today)->get();
        /*
         * check all rows where capacity still now exists
         */
        $userVaccinationDetailsRepository = new UserVaccinationDetailsRepository();
        $userVaccinationModel = new UserVaccinationDetailsModel();
        $scheduledTrigger = false;

        $date1 = Carbon::parse($date);

        foreach ($centerLoadByDateCheckObj as $item) {
            $date2 = Carbon::parse($item->date_of_vaccination);
            if($userVaccinationDetailsRepository->checkIsAllowUserOnThisCenterOnThisDate($item->center_id, $item->date_of_vaccination) && $date1->equalTo($date2)){
                $userVaccinationModel->user_id = $user->id;
                $userVaccinationModel->vaccine_scheduled_date = $item->date_of_vaccination;
                $userVaccinationModel->center_id  = $item->center_id;
                $userVaccinationModel->status = ScheduleStatus::Scheduled;
                $userVaccinationModel->save();

                $scheduledTrigger = true;
                break;
            }
        }
        /**
         * set not scheduled
         */
        if(!$scheduledTrigger){
            $userVaccinationModel->user_id = $user->id;
            $userVaccinationModel->status = ScheduleStatus::NotScheduled;
            $userVaccinationModel->save();
            return ScheduleStatus::NotScheduled;
        } else {
            return ScheduleStatus::Scheduled;
        }
    }
    public function assignScheduleToUser___bk($user)
    {
        $today = Carbon::today()->format('Y-m-d');
        $centerLoadByDateCheckObj = VaccinationCenterCapacityLimitDayWiseModel::where('date_of_vaccination', '>', $today)->get();
        /*
         * check all rows where capacity still now exists
         */
        $userVaccinationDetailsRepository = new UserVaccinationDetailsRepository();
        $userVaccinationModel = new UserVaccinationDetailsModel();
        $scheduledTrigger = false;
        foreach ($centerLoadByDateCheckObj as $item) {
            if($userVaccinationDetailsRepository->checkIsAllowUserOnThisCenterOnThisDate($item->center_id, $item->date_of_vaccination)){
                $userVaccinationModel->user_id = $user->id;
                $userVaccinationModel->vaccine_scheduled_date = $item->date_of_vaccination;
                $userVaccinationModel->center_id  = $item->center_id;
                $userVaccinationModel->status = ScheduleStatus::Scheduled;
                $userVaccinationModel->save();

                $scheduledTrigger = true;
                break;
            }
        }
        /**
         * set not scheduled
         */
        if(!$scheduledTrigger){
            $userVaccinationModel->user_id = $user->id;
            $userVaccinationModel->status = ScheduleStatus::NotScheduled;
            $userVaccinationModel->save();
            return ScheduleStatus::NotScheduled;
        } else {
            return ScheduleStatus::Scheduled;
        }
    }

    public function returnDateByCapacityId($id)
    {
        $obj = VaccinationCenterCapacityLimitDayWiseModel::find($id);
        if($obj){
            return $obj->date_of_vaccination;
        } else {
            return null;
        }
    }

}
