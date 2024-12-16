<?php

namespace Axilweb\Vaccine\Interfaces;

interface VaccinationCenterRepositoryInterface
{
    public function create(array $data);
    public function update(array $data, $id);
    public function find($id);
    public function findAll($id);
    public function delete($id);
    public function findAvailableCenterForRegistration();
}
