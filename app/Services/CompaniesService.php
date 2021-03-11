<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Core\BaseService;
use App\Libraries\GuzzleLib;
use App\Validators\CompaniesValidator;
use Illuminate\Http\Request;

/**
 * CompaniesService
 * @package App\Services
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   10/03/2021
 * @version 1.0
 */
class CompaniesService extends BaseService
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
        $sApiFormat = '%s/v1/companies';
        $sApiRoute = config(AppConstants::API_ROUTE);
        $this->sApiV1Path = sprintf($sApiFormat, $sApiRoute);
        $this->oRequest = $oRequest;
    }

    /**
     * Get all companies
     *
     * @return array|mixed
     */
    public function getAllCompanies()
    {
        $aOption = $this->initRequest($this->oRequest->all());

        return GuzzleLib::guzzleRequest($this->sApiV1Path, $aOption);
    }

    /**
     * Create company
     *
     * @return array|mixed
     */
    public function createCompany()
    {
        $aValidatedFields = CompaniesValidator::validateCompanyInput($this->oRequest->all());
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
     * Update company
     *
     * @param $iCompanyId
     * @return array|mixed
     */
    public function updateCompany($iCompanyId)
    {
        $aValidatedFields = CompaniesValidator::validateCompanyInput($this->oRequest->all());
        $bValidStatus = $aValidatedFields[AppConstants::VALID_STATUS];
        if ($bValidStatus === true) {
            $sUrl = sprintf('%s/%s', $this->sApiV1Path, $iCompanyId);
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
     * Delete company
     *
     * @param $iCompanyId
     * @return array|mixed
     */
    public function deleteCompany($iCompanyId)
    {
        $sUrl = sprintf('%s/%s', $this->sApiV1Path, $iCompanyId);
        $aOption = $this->initRequest();

        return GuzzleLib::guzzleRequest($sUrl, $aOption, 'DELETE');
    }
}
