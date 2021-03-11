<?php

namespace App\Repositories;

use App\Constants\AppConstants;
use App\Constants\CompaniesConstants;
use App\Constants\EmployeesConstants;
use App\Core\BaseRepository;
use App\Libraries\ImageLib;
use App\Models\Companies;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * CompaniesRepository
 * @package App\Repositories
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   10/03/2021
 * @version 1.0
 */
class CompaniesRepository extends BaseRepository
{
    /**
     * @var Companies $oCompanies
     */
    protected $oCompanies;

    /**
     * @var array $aFilter
     */
    protected $aFilter = [
        CompaniesConstants::NAME,
        CompaniesConstants::EMAIL,
        CompaniesConstants::LOGO,
        CompaniesConstants::WEBSITE
    ];

    /**
     * CompaniesRepository constructor.
     *
     * @param Request $oRequest
     * @param Companies $oCompanies
     */
    public function __construct(Request $oRequest, Companies $oCompanies)
    {
        $this->oRequest = $oRequest;
        $this->oCompanies = $oCompanies;
    }

    /**
     * Store company data
     *
     * @return mixed
     * @throws \Exception
     */
    public function createCompany()
    {
        $aData = $this->getCompanyDataInput();
        if ($this->oRequest->get('logo') !== null) {
            $mFile = ImageLib::saveBase64($this->oRequest->get('logo'));
            if (empty($mFile)) {
                return $this->throwException('500', 'Image save unsuccessful');
            }
            $aData = $this->getCompanyDataInput($mFile);
        }

        return $this->oCompanies->create($aData);
    }

    /**
     * Get all companies
     *
     * @return array
     */
    public function getAllCompanies()
    {
        $aParam = $this->getFilter($this->aFilter);
        $aCompaniesQuery = $this->oCompanies::with(EmployeesConstants::T_EMPLOYEES)
            ->where($aParam);
        $iOffset = $this->oRequest->get('offset');
        if ($iOffset !== null) {
            $aCompaniesQuery = $aCompaniesQuery
                ->skip($iOffset)
                ->take(10);
        }
        $aCompanies = $aCompaniesQuery->get()->toArray();
        $aCount = $this->getAllCountCompanies();

        return array_merge($aCount, [AppConstants::COMPANIES => $aCompanies]);
    }

    /**
     * Get company details
     *
     * @param $iCompanyId
     * @return array
     */
    public function getCompanyDetails($iCompanyId)
    {
        $aCompanyDetail = $this->oCompanies::with(EmployeesConstants::T_EMPLOYEES)
            ->where(CompaniesConstants::COMPANY_ID, $iCompanyId)
            ->first()
            ->toArray();

        return [
            AppConstants::COMPANY => $aCompanyDetail
        ];
    }

    /**
     * Count companies
     *
     * @return array
     */
    public function getAllCountCompanies()
    {
        $aParam = $this->getFilter($this->aFilter);
        $iCount = $this->oCompanies
            ->where($aParam)
            ->count();

        return [
            AppConstants::COUNT => $iCount
        ];
    }

    /**
     * Update company
     *
     * @param $iCompanyId
     * @return array
     * @throws \Exception
     */
    public function updateCompany($iCompanyId)
    {
        $aCompany = $this->getCompanyDataInput();
        $sLogo = $this->oRequest->get(CompaniesConstants::LOGO);
        if ($this->isBase64($sLogo) === true) {
            $mFile = ImageLib::saveBase64($sLogo);
            if (empty($mFile)) {
                return $this->throwException('500', 'Image save unsuccessful');
            }
            $this->deleteLogoFile($iCompanyId);
            $aCompany = $this->getCompanyDataInput($mFile);
        }

        $aUpdateCompany = $this->oCompanies
            ->where(CompaniesConstants::COMPANY_ID, $iCompanyId)
            ->update($aCompany);

        return [
            AppConstants::STATUS  => (bool) $aUpdateCompany,
            AppConstants::COMPANY => $aCompany
        ];
    }

    /**
     * Delete company
     *
     * @param $iCompanyId
     * @return array
     */
    public function deleteCompany($iCompanyId)
    {
        $this->deleteLogoFile($iCompanyId);
        $bDeletedCompany = $this->oCompanies
            ->where(CompaniesConstants::COMPANY_ID, $iCompanyId)
            ->delete();
        $sMessage = sprintf('%s deleted company id %s', ((bool) $bDeletedCompany) ? 'Successfully' : 'Unsuccessfully', $iCompanyId);

        return [
            AppConstants::STATUS  => (bool) $bDeletedCompany,
            AppConstants::MESSAGE => $sMessage
        ];
    }

    /**
     * Get company request data input
     *
     * @param string $sFileName
     * @return array
     */
    private function getCompanyDataInput($sFileName = '')
    {
        return [
            CompaniesConstants::NAME    => $this->oRequest->get(CompaniesConstants::NAME),
            CompaniesConstants::EMAIL   => $this->oRequest->get(CompaniesConstants::EMAIL),
            CompaniesConstants::LOGO    => ($sFileName === '') ? $this->oRequest->get(CompaniesConstants::LOGO) : $sFileName,
            CompaniesConstants::WEBSITE => $this->oRequest->get(CompaniesConstants::WEBSITE)
        ];
    }

    /**
     * Delete logo file
     *
     * @param $iCompanyId
     */
    private function deleteLogoFile($iCompanyId)
    {
        $aCompanyDetails = $this->getCompanyDetails($iCompanyId)[AppConstants::COMPANY];
        $sLogoFileName = $aCompanyDetails[CompaniesConstants::LOGO];
        $sFilePath = ImageLib::getUploadPath() . DIRECTORY_SEPARATOR . $sLogoFileName;

        if ($aCompanyDetails[CompaniesConstants::LOGO] !== null) {
            File::delete($sFilePath);
        }
    }

    /**
     * Check if string is base64
     *
     * @param $sStr
     * @return bool
     */
    private function isBase64($sStr)
    {
        return !(bool) preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $sStr);
    }
}
