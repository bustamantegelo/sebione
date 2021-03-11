<?php

namespace App\Models;

use App\Constants\CompaniesConstants;
use App\Constants\EmployeesConstants;
use Illuminate\Database\Eloquent\Model;

/**
 * Employees
 * @package App\Models
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   10/03/2021
 * @version 1.0
 */
class Employees extends Model
{
    /** @var string Table Name */
    protected $table = EmployeesConstants::T_EMPLOYEES;

    /** @var string Primary Key */
    protected $primaryKey = EmployeesConstants::EMPLOYEE_ID;

    /** @var array Public fields that can be filled */
    protected $fillable = [
        EmployeesConstants::FIRST_NAME,
        EmployeesConstants::LAST_NAME,
        EmployeesConstants::COMPANY_ID,
        EmployeesConstants::EMAIL,
        EmployeesConstants::PHONE
    ];

    /**
     * t_companies table relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function t_companies()
    {
        return $this->belongsTo(
            'App\Models\Companies',
            EmployeesConstants::COMPANY_ID,
            CompaniesConstants::COMPANY_ID
        );
    }
}
