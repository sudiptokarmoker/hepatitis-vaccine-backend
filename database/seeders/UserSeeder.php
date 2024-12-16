<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_param = array(
            array('email' => 'vaccinesuperadmin@test.email', 'first_name' => 'superadmin', 'last_name' => '', 'password' => 'password', 'username' => 'vaccinesuperadmin')
            /**
             * here any superadmin related role will be placed
             */
        );
        foreach ($user_param as $row) {
            $user_data = User::where('email', $row['email'])->first();
            if (is_null($user_data)) {
                $userModel = new User();
                $userModel->first_name = $row['first_name'];
                $userModel->last_name = $row['last_name'];
                $userModel->email = $row['email'];
                $userModel->password = Hash::make($row['password']);
                $userModel->isAdmin = true;
                $userModel->uid = (string) Str::uuid();
                $userModel->save();
            }
        }
    }
}
