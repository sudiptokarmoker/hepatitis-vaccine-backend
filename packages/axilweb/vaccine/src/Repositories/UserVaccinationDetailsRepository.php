<?php

namespace Axilweb\Vaccine\Repositories;

use Axilweb\Vaccine\Interfaces\UserVaccinationDetailsRepositoryInterface;
use Axilweb\Vaccine\Models\UserVaccinationDetailsModel;
use Axilweb\Vaccine\Models\VaccinationCenterCapacityLimitDayWiseModel;
use Axilweb\Vaccine\Models\VaccinationCenterModel;

class UserVaccinationDetailsRepository implements UserVaccinationDetailsRepositoryInterface
{
    public function getTotalCurrentUserVaccinatedScheduled($center_id, $date)
    {
        return UserVaccinationDetailsModel::where('center_id', $center_id)
            ->where('vaccine_scheduled_date', $date)
            ->get();
    }
    public function checkIsAllowUserOnThisCenterOnThisDate($center_id, $date)
    {
        /*
         * if the center is valid or not
         * how many scheduled already there (may be vaccinated already - no problem)
         * check the capacity with the vaccination_center_capacity_limit_day_wise table
         * if allow still now then return true or return false
         */
        $vaccinationCenterModel = VaccinationCenterModel::find($center_id);
        if($vaccinationCenterModel){
            $userVaccinationDetailsCountByScheduled = UserVaccinationDetailsModel::where('center_id', $center_id)
                    ->where('vaccine_scheduled_date', $date)
                    ->count();
            $capacityCheckObj = VaccinationCenterCapacityLimitDayWiseModel::where('center_id', $center_id)
                    ->where('date_of_vaccination', $date)
                    ->first();

            if($userVaccinationDetailsCountByScheduled < $capacityCheckObj['capacity_limit']){
                return true;
            } else {
                return false;
            }
        } else {
            throw new \Exception('center not found');
        }
    }
    public function assignCenterToUser($center_id, $user_id)
    {
        /*
         * first loop throw all center if there is any available
         */
        $collection = VaccinationCenterModel::where('status', true)->get();
        if($collection && count($collection) > 0){
            foreach ($collection as $item) {
                $dailyCapacity = $item->daily_capacity_limit;
                //if($dailyCapacity)
            }
        }
    }
}
