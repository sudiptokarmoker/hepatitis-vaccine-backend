<?php

namespace Axilweb\Vaccine\Repositories;

use Axilweb\Vaccine\Interfaces\SearchRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SearchRepository implements SearchRepositoryInterface
{
    public function searchByQuery($query)
    {
        $results = DB::table('users')
            ->leftJoin('user_vaccination_details', 'users.id', '=', 'user_vaccination_details.user_id')
            ->leftJoin('vaccination_center', 'vaccination_center.id', '=', 'user_vaccination_details.center_id')
            ->select(
                'users.id as user_id',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.email as user_email',
                'users.nid as nid',
                'user_vaccination_details.vaccine_scheduled_date as vaccine_scheduled_date',
                'user_vaccination_details.status as status',
                'vaccination_center.center_name as center_name'
            )
            ->where('users.isAdmin', '=', false) // Check if the user is not an admin
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('users.nid', 'LIKE', "%{$query}%")
                    ->orWhere('users.first_name', 'LIKE', "%{$query}%")
                    ->orWhere('users.last_name', 'LIKE', "%{$query}%")
                    ->orWhere('users.email', 'LIKE', "%{$query}%");
            })
            ->get();

        return $results;
    }
}
