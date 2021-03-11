<?php

namespace App\Http\Controllers\Api\v1;

use App\Core\BaseTransformer;
use App\Repositories\CompaniesRepository;

/**
 * CompaniesApiController
 * @package App\Http\Controllers\Api\v1
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   10/03/2021
 * @version 1.0
 */
class CompaniesApiController
{
    /**
     * Companies Repository
     *
     * @var CompaniesRepository
     */
    protected $oCompaniesRepository;

    /**
     * CompaniesApiController constructor.
     *
     * @param CompaniesRepository $oCompaniesRepository
     */
    public function __construct(CompaniesRepository $oCompaniesRepository)
    {
        $this->oCompaniesRepository = $oCompaniesRepository;
    }

    /**
     * Display a listing of the companies
     *
     * @return array
     */
    public function index()
    {
        try {
            $aCompanies = $this->oCompaniesRepository->getAllCompanies();

            return BaseTransformer::transformResponse($aCompanies);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'index', 'CompaniesApiController');
        }
    }

    /**
     * Store a newly created company in db.
     *
     * @return array
     */
    public function store()
    {
        try {
            $aCompanyStored = $this->oCompaniesRepository->createCompany();

            return BaseTransformer::transformResponse($aCompanyStored);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'store', 'CompaniesApiController');
        }
    }

    /**
     * Display the specified company
     *
     * @param $iCompanyId
     * @return array
     */
    public function show($iCompanyId)
    {
        try {
            $aCompanyDetails = $this->oCompaniesRepository->getCompanyDetails($iCompanyId);

            return BaseTransformer::transformResponse($aCompanyDetails);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'show', 'CompaniesApiController');
        }
    }

    /**
     * Update the specified company in db.
     *
     * @param $iCompanyId
     * @return array
     */
    public function update($iCompanyId)
    {
        try {
            $aCompanyUpdated = $this->oCompaniesRepository->updateCompany($iCompanyId);

            return BaseTransformer::transformResponse($aCompanyUpdated);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'update', 'CompaniesApiController');
        }
    }

    /**
     * Remove the specified company from db.
     *
     * @param $iCompanyId
     * @return array
     */
    public function destroy($iCompanyId)
    {
        try {
            $aCompanyDeleted = $this->oCompaniesRepository->deleteCompany($iCompanyId);

            return BaseTransformer::transformResponse($aCompanyDeleted);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'destroy', 'CompaniesApiController');
        }
    }
}
