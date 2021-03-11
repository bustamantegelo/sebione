<?php

namespace App\Models;

use App\Constants\CompaniesConstants;
use Illuminate\Database\Eloquent\Model;

/**
 * Companies
 * @package App\Models
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   10/03/2021
 * @version 1.0
 */
class Companies extends Model
{
    /** @var string Table Name */
    protected $table = CompaniesConstants::T_COMPANIES;

    /** @var string Primary Key */
    protected $primaryKey = CompaniesConstants::COMPANY_ID;

    /** @var array Public fields that can be filled */
    protected $fillable = [
        CompaniesConstants::NAME,
        CompaniesConstants::EMAIL,
        CompaniesConstants::LOGO,
        CompaniesConstants::WEBSITE
    ];

    /**
     * Relationship with t_employees table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function t_employees()
    {
        return $this->hasMany(
            'App\Models\Employees',
            $this->primaryKey
        );
    }
}
