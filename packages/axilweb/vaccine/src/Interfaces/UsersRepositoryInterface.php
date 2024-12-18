<?php

namespace Axilweb\Vaccine\Interfaces;

use Illuminate\Http\Client\Request;

interface UsersRepositoryInterface
{
    public function create(array $data);
    public function update(array $data);
    public function find($id);
    public function findAll($id);
    public function delete($id);
    public function login(array $data);
    public function changePassword(array $data);
    public function lists(Request $request);
    public function assignScheduleToUser($user, $date, $center_capacity_root_id);
    public function returnDateByCapacityId($id);
}
