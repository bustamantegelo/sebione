<?php

namespace App\Models;

use App\Constants\EmployeesConstants;
use App\Constants\UsersConstants;
use Illuminate\Database\Eloquent\Model;

/**
 * Users
 * @package App\Models
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   08/03/2021
 * @version 1.0
 */
class Users extends Model
{
    /** @var string Table Name */
    protected $table = UsersConstants::T_USERS;

    /** @var string Primary Key */
    protected $primaryKey = UsersConstants::USER_ID;

    /** @var array Public fields that can be filled */
    protected $fillable = [
        UsersConstants::EMAIL,
        UsersConstants::PASSWORD
    ];
}
