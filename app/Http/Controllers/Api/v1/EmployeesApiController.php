<?php

namespace App\Http\Controllers\Api\v1;

use App\Core\BaseTransformer;
use App\Repositories\EmployeesRepository;

/**
 * EmployeesApiController
 * @package App\Http\Controllers\Api\v1
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   10/03/2021
 * @version 1.0
 */
class EmployeesApiController
{
    /**
     * Employees Repository
     *
     * @var EmployeesRepository
     */
    protected $oEmployeesRepository;

    /**
     * EmployeesApiController constructor.
     *
     * @param EmployeesRepository $oEmployeesRepository
     */
    public function __construct(EmployeesRepository $oEmployeesRepository)
    {
        $this->oEmployeesRepository = $oEmployeesRepository;
    }

    /**
     * Display a listing of the employees
     *
     * @return array
     */
    public function index()
    {
        try {
            $aEmployees = $this->oEmployeesRepository->getAllEmployees();

            return BaseTransformer::transformResponse($aEmployees);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'index', 'EmployeesApiController');
        }
    }

    /**
     * Store a newly created employee in db.
     *
     * @return array
     */
    public function store()
    {
        try {
            $aEmployeeStored = $this->oEmployeesRepository->createEmployee();

            return BaseTransformer::transformResponse($aEmployeeStored);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'store', 'EmployeesApiController');
        }
    }

    /**
     * Display the specified employee
     *
     * @param $iEmployeeId
     * @return array
     */
    public function show($iEmployeeId)
    {
        try {
            $aEmployeeDetails = $this->oEmployeesRepository->getEmployeeDetails($iEmployeeId);

            return BaseTransformer::transformResponse($aEmployeeDetails);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'show', 'EmployeesApiController');
        }
    }

    /**
     * Update the specified employee in db.
     *
     * @param $iEmployeeId
     * @return array
     */
    public function update($iEmployeeId)
    {
        try {
            $aEmployeeUpdated = $this->oEmployeesRepository->updateEmployee($iEmployeeId);

            return BaseTransformer::transformResponse($aEmployeeUpdated);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'update', 'EmployeesApiController');
        }
    }

    /**
     * Remove the specified employee from db.
     *
     * @param $iEmployeeId
     * @return array
     */
    public function destroy($iEmployeeId)
    {
        try {
            $aEmployeeDeleted = $this->oEmployeesRepository->deleteEmployee($iEmployeeId);

            return BaseTransformer::transformResponse($aEmployeeDeleted);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'destroy', 'EmployeesApiController');
        }
    }
}
