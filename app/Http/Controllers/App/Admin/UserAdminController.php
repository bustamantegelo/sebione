<?php

namespace App\Http\Controllers\App\Admin;

use App\Constants\AppConstants;
use App\Constants\UsersConstants;
use Illuminate\Http\Request;
use App\Core\BaseController;
use App\Core\BaseTransformer;
use App\Services\UserService;
use App\Validators\Users\UsersLoginValidator;

/**
 * UserAdminController
 * @package App\Http\Controllers\App\Admin
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   09/03/2021
 * @version 1.0
 */
class UserAdminController extends BaseController
{
    /**
     * User service
     *
     * @var UserService
     */
    protected $oUserService;

    /**
     * UserAdminController constructor.
     *
     * @param Request $oRequest
     * @param UserService $oUserService
     */
    public function __construct(Request $oRequest, UserService $oUserService)
    {
        $this->oUserService = $oUserService;
        $this->oRequest = $oRequest;
    }

    /**
     * Login user
     *
     * @return array
     */
    public function login()
    {
        try {
            $aValidatedFields = UsersLoginValidator::validateUserLogin($this->oRequest->all());
            $bValidStatus = $aValidatedFields[AppConstants::VALID_STATUS];
            if ($bValidStatus !== false) {
                $sEmail = $this->oRequest->get(UsersConstants::EMAIL);
                $sPassword = $this->oRequest->get(UsersConstants::PASSWORD);
                $aLogin = $this->oUserService->login($sEmail, $sPassword);
                session([
                    'user' => $sEmail
                ]);

                return BaseTransformer::transformResponse($aLogin);
            }

            return BaseTransformer::transformResponse([
                AppConstants::STATUS  => $bValidStatus,
                AppConstants::MESSAGE => end($aValidatedFields[AppConstants::ERROR_MESSAGES])
            ]);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'login', 'UserAdminController');
        }
    }
}
