<?php

namespace Database\Seeders;

use App\Constants\UsersConstants;
use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UsersSeeder
 * @package Database\Seeders
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   08/03/2021
 * @version 1.0
 */
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Users::truncate();
        $aAdminUserDetail = [
            UsersConstants::EMAIL    => 'admin@admin.com',
            UsersConstants::PASSWORD => Hash::make('password')
        ];
        Users::create($aAdminUserDetail);
    }
}
