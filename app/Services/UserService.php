<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Constants\UsersConstants;
use App\Core\BaseService;
use App\Libraries\GuzzleLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * UserService
 * @package App\Services
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   09/03/2021
 * @version 1.0
 */
class UserService extends BaseService
{
    /**
     * @var string $sApiV1Path
     */
    protected $sApiV1Path;

    /**
     * UserService constructor.
     *
     * @param Request $oRequest
     */
    public function __construct(Request $oRequest)
    {
        $sApiFormat = '%s/v1/users';
        $sApiRoute = config(AppConstants::API_ROUTE);
        $this->sApiV1Path = sprintf($sApiFormat, $sApiRoute);
        $this->oRequest = $oRequest;
    }

    /**
     * Login user
     *
     * @param $sEmail
     * @param $sPassword
     * @return array|void
     * @throws \Exception
     */
    public function login($sEmail, $sPassword)
    {
        $aUsersRequestData = $this->getAllUsers()[AppConstants::DATA];
        $iUsersCount = $aUsersRequestData[AppConstants::COUNT];
        $aUserDetails = end($aUsersRequestData[AppConstants::USERS]);

        if ((bool) $iUsersCount === false) {
            return [
                AppConstants::STATUS => false,
                AppConstants::MESSAGE => sprintf('%s is not associated with any account.', $sEmail)
            ];
        }
        $bValidEmail = $aUserDetails[UsersConstants::EMAIL] === $sEmail;
        $bValidPassword = Hash::check($sPassword, $aUserDetails[UsersConstants::PASSWORD]);
        $bLoginAttemptStatus = (($bValidEmail === true) && ($bValidPassword === true));
        $sMessage = ($bLoginAttemptStatus === true) ? sprintf('Welcome %s', $sEmail) : 'The password you\'ve entered is incorrect.';

        return [
            AppConstants::STATUS  => $bLoginAttemptStatus,
            AppConstants::MESSAGE => $sMessage
        ];
    }

    /**
     * Get all users
     *
     * @return array|mixed
     */
    private function getAllUsers()
    {
        $aParam = [
            UsersConstants::EMAIL => $this->oRequest->get(UsersConstants::EMAIL)
        ];
        $aOption = $this->initRequest($aParam);

        return GuzzleLib::guzzleRequest($this->sApiV1Path, $aOption);
    }
}
