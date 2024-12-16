<?php

namespace App\Enums;

enum ScheduleStatus: string
{
    case NotRegistered = 'not_registered';
    case Scheduled = 'scheduled';
    case NotScheduled = 'not_scheduled';
    case Vaccinated = 'vaccinated';
}
