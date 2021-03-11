<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

/**
 * EmployeesValidator
 * @package App\Validators
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   10/03/2021
 * @version 1.0
 */
class EmployeesValidator
{
    /**
     * Validate employees input
     *
     * @param $aRequest
     * @return array
     */
    public static function validateEmployeesInput($aRequest)
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
            'first_name' => ['required', 'string', 'max:35'],
            'last_name'  => ['required', 'string', 'max:50'],
            'company_id' => ['required', 'int'],
            'email'      => ['string', 'email', 'max:254', 'nullable'],
            'phone'      => ['int', 'max:15', 'nullable']
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
