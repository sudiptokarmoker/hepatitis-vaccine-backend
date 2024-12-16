<?php

namespace Axilweb\Vaccine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVaccinationDetailsModel extends Model
{
    use HasFactory;
    protected $table='user_vaccination_details';
    protected $fillable = [
        'user_id',
        'vaccine_scheduled_date',
        'center_id',
        'status'
    ];
}
