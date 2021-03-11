<?php

namespace App\Http\Controllers\App\Admin;

use App\Core\BaseTransformer;
use App\Services\CompaniesService;

/**
 * CompaniesAdminController
 * @package App\Http\Controllers\App\Admin
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   10/03/2021
 * @version 1.0
 */
class CompaniesAdminController
{
    /**
     * Companies service
     *
     * @var CompaniesService
     */
    protected $oCompaniesService;

    /**
     * CompaniesAdminController constructor.
     *
     * @param CompaniesService $oCompaniesService
     */
    public function __construct(CompaniesService $oCompaniesService)
    {
        $this->oCompaniesService = $oCompaniesService;
    }

    /**
     * Get all companies
     *
     * @return array
     */
    public function index()
    {
        try {
            $aCompanies = $this->oCompaniesService->getAllCompanies();

            return BaseTransformer::transformResponse($aCompanies);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'index', 'CompaniesAdminController');
        }
    }

    /**
     * Create company
     *
     * @return array
     */
    public function store()
    {
        try {
            $aCompanyCreated = $this->oCompaniesService->createCompany();

            return BaseTransformer::transformResponse($aCompanyCreated);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'store', 'CompaniesAdminController');
        }
    }

    /**
     * Update company
     *
     * @param $iCompanyId
     * @return array
     */
    public function update($iCompanyId)
    {
        try {
            $aCompanyUpdated = $this->oCompaniesService->updateCompany($iCompanyId);

            return BaseTransformer::transformResponse($aCompanyUpdated);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'updateCompany', 'CompaniesAdminController');
        }
    }

    /**
     * Delete company
     *
     * @param $iCompanyId
     * @return array
     */
    public function destroy($iCompanyId)
    {
        try {
            $aCompanyDeleted = $this->oCompaniesService->deleteCompany($iCompanyId);

            return BaseTransformer::transformResponse($aCompanyDeleted);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'destroy', 'CompaniesAdminController');
        }
    }
}
