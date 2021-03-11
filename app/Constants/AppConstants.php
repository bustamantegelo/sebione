<?php

namespace App\Constants;

/**
 * AppConstants
 * @package App\Constants
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   09/03/2021
 * @version 1.0
 */
class AppConstants
{
    /** Constant variables for config arguments */
    const API_ROUTE = 'app.apiroute';
    const APP_ROUTE = 'app.approute';

    /** Constant variables used in parameters and arguments */
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /** Constant data */
    const CODE = 'code';
    const DATA = 'data';
    const MESSAGE = 'message';
    const JSON = 'json';
    const COUNT = 'count';
    const DESCENDING = 'descending';
    const ASCENDING = 'ascending';
    const VALID_STATUS = 'valid_status';
    const ERROR_MESSAGES = 'error_messages';
    const STATUS = 'status';

    /** Users route constants */
    const USERS = 'users';

    /** Comapnies route constants */
    const COMPANIES = 'companies';
    const COMPANY = 'company';

    /** @Employees route constants */
    const EMPLOYEES = 'employees';
    const EMPLOYEE = 'employee';
}
