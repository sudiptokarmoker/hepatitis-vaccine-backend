<?php

namespace Axilweb\Vaccine\Repositories;

use Axilweb\Vaccine\Interfaces\VaccinationCenterRepositoryInterface;
use Axilweb\Vaccine\Models\VaccinationCenterCapacityLimitDayWiseModel;
use Axilweb\Vaccine\Models\VaccinationCenterModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VaccinationCenterRepository implements VaccinationCenterRepositoryInterface
{
    public function create(array $data){
        $vaccinationCenterModel = new VaccinationCenterModel();
        $vaccinationCenterModel->center_name = $data['center_name'];
        if(isset($data['status'])){
            $vaccinationCenterModel->status = $data['status'];
        }
        $vaccinationCenterModel->save();

        /**
         * and now update the vaccinaiton cpacity details start
         */
        $vaccinationCenterModel = VaccinationCenterModel::find($vaccinationCenterModel->id);
        if($vaccinationCenterModel) {
            VaccinationCenterCapacityLimitDayWiseModel::updateOrCreate(
                [
                    'center_id' => $vaccinationCenterModel->id,
                    'date_of_vaccination' => $data['date_of_vaccination']
                ],
                [
                    'center_id' => $vaccinationCenterModel->id,
                    'date_of_vaccination' => $data['date_of_vaccination'],
                    'capacity_limit' => $data['capacity_limit']
                ]
            );
        }

            /*
             * vaccinaiton capacity details end
             */

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

    public function findAvailableCenterWithCapacityDetailsAndScheduled()
    {
        //vaccination_center
        //vaccination_center_capacity_limit_day_wise
        $results = DB::table('vaccination_center')
            ->leftJoin('vaccination_center_capacity_limit_day_wise', 'vaccination_center_capacity_limit_day_wise.center_id', '=', 'vaccination_center.id')
            ->select(
                'vaccination_center.id as center_id',
                'vaccination_center.center_name as center_name',
                'vaccination_center_capacity_limit_day_wise.date_of_vaccination as date_of_vaccination',
                'vaccination_center_capacity_limit_day_wise.capacity_limit as capacity_limit'
            )
            ->get();

            //dd($results);

            $centerDataArray = [];

            foreach ($results as $item) {
                    $centerDataArray[] = [
                        'center_id' => $item->center_id,
                        'center_name' => $item->center_name,
                        'date_of_vaccination' => $item->date_of_vaccination,
                        'capacity_limit' => $item->capacity_limit
                    ];
                
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
