<?php

namespace Axilweb\Vaccine\Repositories;

use Axilweb\Vaccine\Interfaces\VaccinationCenterRepositoryInterface;
use Axilweb\Vaccine\Models\VaccinationCenterCapacityLimitDayWiseModel;
use Axilweb\Vaccine\Models\VaccinationCenterModel;
use Carbon\Carbon;

class VaccinationCenterRepository implements VaccinationCenterRepositoryInterface
{
    public function create(array $data){
        $vaccinationCenterModel = new VaccinationCenterModel();
        $vaccinationCenterModel->center_name = $data['center_name'];
        if(isset($data['status'])){
            $vaccinationCenterModel->status = $data['status'];
        }
        $vaccinationCenterModel->save();

        return $vaccinationCenterModel;
    }

    public function update(array $data, $id){
        $vaccinationCenterModel = VaccinationCenterModel::find($id);
        if($vaccinationCenterModel != null)
        {
            $vaccinationCenterModel->center_name = $data['center_name'];
            if(isset($data['status'])){
                $vaccinationCenterModel->status = $data['status'];
            }
            $vaccinationCenterModel->save();

            return $vaccinationCenterModel;
        }
        else {
            return false;
        }
    }

    public function find($id){
        $vaccinationCenter = VaccinationCenterModel::find($id);
        if($vaccinationCenter){
            return $vaccinationCenter;
        } else {
            throw new \Exception('Vaccination center not found');
        }
    }
    public function findAll($id){
        return VaccinationCenterModel::all();
    }
    public function findAvailableCenterForRegistration()
    {
        /*
         * we may need to transfer this code to listener
         */
        $today = Carbon::today()->format('Y-m-d');
        $centerLoadByDateCheckObj = VaccinationCenterCapacityLimitDayWiseModel::where('date_of_vaccination', '>', $today)->get();
        /*
         * check all rows where capacity still now exists
         */
        $userVaccinationDetailsRepository = new UserVaccinationDetailsRepository();
        $centerDataArray = [];
        foreach ($centerLoadByDateCheckObj as $item) {
            if($userVaccinationDetailsRepository->checkIsAllowUserOnThisCenterOnThisDate($item->center_id, $item->date_of_vaccination)){
                $centerModel = VaccinationCenterModel::find($item->center_id);
                $centerDataArray[] = [
                    'center_id' => $item->center_id,
                    'center_name' => $centerModel->center_name.' '.$item->date_of_vaccination,
                    'scheduled_date' => $item->date_of_vaccination,
                    'center_capacity_root_id' => $item->id
                ];
            }
        }
        return $centerDataArray;
    }
    public function delete($id)
    {
        $vaccinationCenter = VaccinationCenterModel::findOrFail($id);
        $vaccinationCenter->delete();
        return true;
    }
}
