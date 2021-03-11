<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Core\BaseService;
use App\Libraries\GuzzleLib;
use App\Validators\EmployeesValidator;
use Illuminate\Http\Request;

/**
 * EmployeesService
 * @package App\Services
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   10/03/2021
 * @version 1.0
 */
class EmployeesService extends BaseService
{
    /**
     * @var string $sApiV1Path
     */
    protected $sApiV1Path;

    /**
     * EmployeesServices constructor.
     *
     * @param Request $oRequest
     */
    public function __construct(Request $oRequest)
    {
        $sApiFormat = '%s/v1/employees';
        $sApiRoute = config(AppConstants::API_ROUTE);
        $this->sApiV1Path = sprintf($sApiFormat, $sApiRoute);
        $this->oRequest = $oRequest;
    }

    /**
     * Get all employees
     *
     * @return array|mixed
     */
    public function getAllEmployees()
    {
        $aOption = $this->initRequest($this->oRequest->all());

        return GuzzleLib::guzzleRequest($this->sApiV1Path, $aOption);
    }

    /**
     * Create employee
     *
     * @return array|mixed
     */
    public function createEmployee()
    {
        $aValidatedFields = EmployeesValidator::validateEmployeesInput($this->oRequest->all());
        $bValidStatus = $aValidatedFields[AppConstants::VALID_STATUS];
        if ($bValidStatus === true) {
            $aOption = $this->initRequest($this->oRequest->all());

            return GuzzleLib::guzzleRequest($this->sApiV1Path, $aOption, 'POST');
        }

        return [
            AppConstants::CODE    => 200,
            AppConstants::DATA    => [
                AppConstants::STATUS  => $bValidStatus,
                AppConstants::MESSAGE => end($aValidatedFields[AppConstants::ERROR_MESSAGES])
            ],
            AppConstants::MESSAGE => 'Success'
        ];
    }

    /**
     * Update employee
     *
     * @param $iEmployeeId
     * @return array|mixed
     */
    public function updateEmployee($iEmployeeId)
    {
        $aValidatedFields = EmployeesValidator::validateEmployeesInput($this->oRequest->all());
        $bValidStatus = $aValidatedFields[AppConstants::VALID_STATUS];
        if ($bValidStatus === true) {
            $sUrl = sprintf('%s/%s', $this->sApiV1Path, $iEmployeeId);
            $aOption = $this->initRequest($this->oRequest->all());

            return GuzzleLib::guzzleRequest($sUrl, $aOption, 'PUT');
        }

        return [
            AppConstants::CODE    => 200,
            AppConstants::DATA    => [
                AppConstants::STATUS  => $bValidStatus,
                AppConstants::MESSAGE => end($aValidatedFields[AppConstants::ERROR_MESSAGES])
            ],
            AppConstants::MESSAGE => 'Success'
        ];
    }

    /**
     * Delete employee
     *
     * @param $iEmployeeId
     * @return array|mixed
     */
    public function deleteEmployee($iEmployeeId)
    {
        $sUrl = sprintf('%s/%s', $this->sApiV1Path, $iEmployeeId);
        $aOption = $this->initRequest();

        return GuzzleLib::guzzleRequest($sUrl, $aOption, 'DELETE');
    }
}
