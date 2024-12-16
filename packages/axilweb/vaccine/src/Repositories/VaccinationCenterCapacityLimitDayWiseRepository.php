<?php

namespace Axilweb\Vaccine\Repositories;

use Axilweb\Vaccine\Interfaces\VaccinationCenterCapacityLimitDayWiseRepositoryInterface;
use Axilweb\Vaccine\Models\VaccinationCenterCapacityLimitDayWiseModel;
use Axilweb\Vaccine\Models\VaccinationCenterModel;

class VaccinationCenterCapacityLimitDayWiseRepository implements VaccinationCenterCapacityLimitDayWiseRepositoryInterface
{
    public function create(array $data){
        $vaccinationCenterModel = VaccinationCenterModel::find($data['center_id']);
        if($vaccinationCenterModel){
            return VaccinationCenterCapacityLimitDayWiseModel::updateOrCreate(
                [
                    'center_id' => $data['center_id'],
                    'date_of_vaccination' => $data['date_of_vaccination']
                ],
                [
                    'center_id' => $data['center_id'],
                    'date_of_vaccination' => $data['date_of_vaccination'],
                    'capacity_limit' => $data['capacity_limit']
                ]
            );
        } else {
            throw new \Exception('center not found');
        }
    }
}
