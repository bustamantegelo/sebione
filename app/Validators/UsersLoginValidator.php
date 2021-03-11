<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

/**
 * UsersLoginValidator
 * @package App\Validators\Users
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   09/03/2021
 * @version 1.0
 */
class UsersLoginValidator
{
    /**
     * Validate user login
     *
     * @param $aRequest
     * @return array
     */
    public static function validateUserLogin($aRequest)
    {
        $oValidator = Validator::make($aRequest, self::rules());

        return self::checkValidation($oValidator);
    }

    /**
     * Product Upload Rules
     *
     * @return array
     */
    private static function rules()
    {
        return [
            'email'    => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:60']
        ];
    }

    /**
     * Check validation status
     *
     * @param object $oValidator
     * @return array
     */
    protected static function checkValidation($oValidator)
    {
        if ($oValidator->fails()) {
            return [
                'valid_status'   => false,
                'error_messages' => self::getErrorMessages($oValidator)
            ];
        }

        return [
            'valid_status' => true
        ];
    }

    /**
     * Get Error Messages
     *
     * @param object $oValidator
     * @return array
     */
    protected static function getErrorMessages($oValidator)
    {
        $oErrors = $oValidator->errors();
        $aErrorMessages = [];
        foreach ($oErrors->all() as $message) {
            array_push($aErrorMessages, $message);
        }

        return $aErrorMessages;
    }
}
