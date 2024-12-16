<?php

namespace Axilweb\Vaccine\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Axilweb\Vaccine\Interfaces\VaccinationCenterCapacityLimitDayWiseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VaccinationCenterCapacityLimitDayWiseController extends Controller
{
    protected $vaccinationCenterCapacityLimitDayWiseRepositoryObj;

    public function __construct(VaccinationCenterCapacityLimitDayWiseRepositoryInterface $vaccinationCenterCapacityLimitDayWiseRepositoryInterface)
    {
        $this->vaccinationCenterCapacityLimitDayWiseRepositoryObj = $vaccinationCenterCapacityLimitDayWiseRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
                    'center_id' => 'required|integer',
                    'date_of_vaccination' => 'required|date|after_or_equal:today',
                    'capacity_limit' => 'required|integer'
                ]
            );
            $data = $this->vaccinationCenterCapacityLimitDayWiseRepositoryObj->create($request->all());
            return self::return_response('Vaccinaiton Day Wise Capacity Limit Saved Successfully', true, $data, 0, 200);
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
                'center_id' => 'required|integer',
                'date_of_vaccination' => 'required|date|after_or_equal:today',
                'capacity_limit' => 'required|integer'
            ]);

            $vaccinationCenterObj = $this->vaccinationCenterCapacityLimitDayWiseRepositoryObj->update($request->all(), $id);
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
}
