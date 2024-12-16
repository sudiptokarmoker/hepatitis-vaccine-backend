<?php

namespace Axilweb\Vaccine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationCenterCapacityLimitDayWiseModel extends Model
{
    use HasFactory;
    protected $table = 'vaccination_center_capacity_limit_day_wise';
    protected $fillable = [
        'center_id',
        'date_of_vaccination',
        'capacity_limit'
    ];
}
