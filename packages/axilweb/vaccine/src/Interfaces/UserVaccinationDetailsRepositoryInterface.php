<?php

namespace Axilweb\Vaccine\Interfaces;

interface UserVaccinationDetailsRepositoryInterface
{
    public function assignCenterToUser($center_id, $user_id);
    public function checkIsAllowUserOnThisCenterOnThisDate($center_id, $date);
}
