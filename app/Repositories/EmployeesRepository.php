<?php

namespace App\Repositories;

use App\Constants\AppConstants;
use App\Constants\CompaniesConstants;
use App\Constants\EmployeesConstants;
use App\Core\BaseRepository;
use App\Models\Companies;
use App\Models\Employees;
use Illuminate\Http\Request;

/**
 * EmployeesRepository
 * @package App\Repositories
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   10/03/2021
 * @version 1.0
 */
class EmployeesRepository extends BaseRepository
{
    /**
     * @var Employees $oEmployees
     */
    protected $oEmployees;

    /**
     * @var array $aFilter
     */
    protected $aFilter = [
        EmployeesConstants::FIRST_NAME,
        EmployeesConstants::LAST_NAME,
        EmployeesConstants::COMPANY_ID,
        EmployeesConstants::EMAIL,
        EmployeesConstants::PHONE
    ];

    /**
     * EmployeesRepository constructor.
     *
     * @param Request $oRequest
     * @param Employees $oEmployees
     */
    public function __construct(Request $oRequest, Employees $oEmployees)
    {
        $this->oRequest = $oRequest;
        $this->oEmployees = $oEmployees;
    }

    /**
     * Store employee data
     *
     * @return mixed
     */
    public function createEmployee()
    {
        $aData = $this->getEmployeeDataInput();

        return $this->oEmployees->create($aData);
    }

    /**
     * Get all users
     *
     * @return array
     */
    public function getAllEmployees()
    {
        $iOffset = $this->oRequest->get('offset');
        $aParam = $this->getFilter($this->aFilter);
        $aEmployeesQuery = $aEmployees = $this->oEmployees::with(CompaniesConstants::T_COMPANIES)
            ->where($aParam);
        if ($iOffset !== null) {
            $aEmployeesQuery = $aEmployeesQuery
                ->skip($iOffset)
                ->take(10);
        }
        $aEmployees = $aEmployeesQuery->get()->toArray();
        $aCount = $this->getAllCountEmployees();

        return array_merge($aCount, [AppConstants::EMPLOYEES => $aEmployees]);
    }

    /**
     * Get employee details
     *
     * @param $iEmployeeId
     * @return array
     */
    public function getEmployeeDetails($iEmployeeId)
    {
        $aEmployeeDetail = $this->oEmployees::with(CompaniesConstants::T_COMPANIES)
            ->where(EmployeesConstants::EMPLOYEE_ID, $iEmployeeId)
            ->first()
            ->toArray();

        return [
            AppConstants::EMPLOYEE => $aEmployeeDetail
        ];
    }

    /**
     * Count users
     *
     * @return array
     */
    public function getAllCountEmployees()
    {
        $aParam = $this->getFilter($this->aFilter);
        $iCount = $this->oEmployees
            ->where($aParam)
            ->count();

        return [
            AppConstants::COUNT => $iCount
        ];
    }

    /**
     * Update employee
     *
     * @param $iEmployeeId
     * @return array
     */
    public function updateEmployee($iEmployeeId)
    {
        $aEmployee = $this->getEmployeeDataInput();
        $aUpdateCompany = $this->oEmployees
            ->where(EmployeesConstants::EMPLOYEE_ID, $iEmployeeId)
            ->update($aEmployee);

        return [
            AppConstants::STATUS  => (bool) $aUpdateCompany,
            AppConstants::COMPANY => $aEmployee
        ];
    }

    /**
     * Delete employee
     *
     * @param $iEmployeeId
     * @return array
     */
    public function deleteEmployee($iEmployeeId)
    {
        $bDeletedEmployee = $this->oEmployees
            ->where(EmployeesConstants::EMPLOYEE_ID, $iEmployeeId)
            ->delete();
        $sMessage = sprintf('%s deleted employee id %s', ((bool) $bDeletedEmployee) ? 'Successfully' : 'Unsuccessfully', $iEmployeeId);

        return [
            AppConstants::STATUS  => (bool) $bDeletedEmployee,
            AppConstants::MESSAGE => $sMessage
        ];
    }

    /**
     * Get employee request data input
     *
     * @return array
     */
    private function getEmployeeDataInput()
    {
        return [
            EmployeesConstants::FIRST_NAME => $this->oRequest->get(EmployeesConstants::FIRST_NAME),
            EmployeesConstants::LAST_NAME  => $this->oRequest->get(EmployeesConstants::LAST_NAME),
            EmployeesConstants::COMPANY_ID => $this->oRequest->get(EmployeesConstants::COMPANY_ID),
            EmployeesConstants::EMAIL      => $this->oRequest->get(EmployeesConstants::EMAIL),
            EmployeesConstants::PHONE      => $this->oRequest->get(EmployeesConstants::PHONE)
        ];
    }
}
