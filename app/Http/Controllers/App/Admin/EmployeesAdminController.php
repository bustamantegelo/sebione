<?php

namespace App\Http\Controllers\App\Admin;

use App\Core\BaseTransformer;
use App\Services\EmployeesService;

/**
 * EmployeesAdminController
 * @package App\Http\Controllers\App\Admin
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   10/03/2021
 * @version 1.0
 */
class EmployeesAdminController
{
    /**
     * Employees service
     *
     * @var EmployeesService
     */
    protected $oEmployeesService;

    /**
     * EmployeesAdminController constructor.
     *
     * @param EmployeesService $oEmployeesService
     */
    public function __construct(EmployeesService $oEmployeesService)
    {
        $this->oEmployeesService = $oEmployeesService;
    }

    /**
     * Get all companies
     *
     * @return array
     */
    public function index()
    {
        try {
            $aEmployees = $this->oEmployeesService->getAllEmployees();

            return BaseTransformer::transformResponse($aEmployees);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'getCompanies', 'EmployeesAdminController');
        }
    }

    /**
     * Create employee
     *
     * @return array
     */
    public function store()
    {
        try {
            $aEmployeeCreated = $this->oEmployeesService->createEmployee();

            return BaseTransformer::transformResponse($aEmployeeCreated);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'store', 'EmployeesAdminController');
        }
    }

    /**
     * Update employee
     *
     * @param $iEmployeeId
     * @return array
     */
    public function update($iEmployeeId)
    {
        try {
            $aEmployeeUpdated = $this->oEmployeesService->updateEmployee($iEmployeeId);

            return BaseTransformer::transformResponse($aEmployeeUpdated);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'update', 'EmployeesAdminController');
        }
    }

    /**
     * Delete employee
     *
     * @param $iEmployeeId
     * @return array
     */
    public function destroy($iEmployeeId)
    {
        try {
            $aEmployeeDeleted = $this->oEmployeesService->deleteEmployee($iEmployeeId);

            return BaseTransformer::transformResponse($aEmployeeDeleted);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'destroy', 'EmployeesAdminController');
        }
    }
}
