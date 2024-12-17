<?php

namespace Axilweb\Vaccine\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Axilweb\Vaccine\Interfaces\VaccinationCenterRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VaccinationCenterController extends Controller
{
    protected $vaccinationCenterRepositoryObj;

    public function __construct(VaccinationCenterRepositoryInterface $vaccinationCenterRepositoryInterface)
    {
        $this->vaccinationCenterRepositoryObj = $vaccinationCenterRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->vaccinationCenterRepositoryObj->findAvailableCenterForRegistration();
            return self::return_response('Vaccinaiton Center Faced Successfully', true, $data, count(($data)), 200);
        } catch (\Exception $e) {
            return self::return_response($e->getMessage(), false, [], 0, 417);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(Auth::user()->isAdmin == false){
            return self::return_response('Not permitted', false, [], 0, 403);
        }
        try {
            $request->validate(
                [
                    'center_name' => 'required|max:255|unique:vaccination_center',
                    'status' => 'boolean'
                ]
            );
            $data = $this->vaccinationCenterRepositoryObj->create($request->all());
            return self::return_response('Vaccinaiton Created Successfully', true, $data, 0, 200);
        } catch (\Exception $e) {
            return self::return_response($e->getMessage(), false, [], 0, 417);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if(Auth::user()->isAdmin == false){
            return self::return_response('Not permitted', false, [], 0, 403);
        }
        try {
            $request->validate([
                'center_name' => ['required', 'max:255', Rule::unique('vaccination_center')->ignore($id)],
                'status' => 'boolean'
            ]);

            $vaccinationCenterObj = $this->vaccinationCenterRepositoryObj->update($request->all(), $id);
            if ($vaccinationCenterObj) {
                return self::return_response('Vaccinaiton Center Updated Successfully', true, $vaccinationCenterObj, 0, 200);
            } else {
                return self::return_response('Invalid Email.', false, [], 0, 417);
            }
        } catch (\Exception $e) {
            return self::return_response($e->getMessage(), false, [], 0, 417);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if(Auth::user()->isAdmin == false){
            return self::return_response('Not permitted', false, [], 0, 403);
        }
        try {
            $data = $this->vaccinationCenterRepositoryObj->delete($id);
            return self::return_response('Vaccinaiton Deleted Successfully', true, $data, 0, 200);
        } catch (\Exception $e) {
            return self::return_response($e->getMessage(), false, [], 0, 417);
        }
    }
    public function getAllAvailableCentersList()
    {
        try {
            $data = $this->vaccinationCenterRepositoryObj->findAvailableCenterForRegistration();
            return self::return_response('Vaccinaiton Center Faced Successfully', true, $data, count(($data)), 200);
        } catch (\Exception $e) {
            return self::return_response($e->getMessage(), false, [], 0, 417);
        }
    }
    public function getAllAvailableCentersListAndScheduled()
    {
        try {
            $data = $this->vaccinationCenterRepositoryObj->findAvailableCenterWithCapacityDetailsAndScheduled();
            return self::return_response('Vaccinaiton Center Faced Successfully', true, $data, count(($data)), 200);
        } catch (\Exception $e) {
            return self::return_response($e->getMessage(), false, [], 0, 417);
        }
    }
}
