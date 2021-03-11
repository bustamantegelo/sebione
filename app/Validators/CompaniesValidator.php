<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

/**
 * CompaniesValidator
 * @package App\Validators
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   11/03/2021
 * @version 1.0
 */
class CompaniesValidator
{
    /**
     * Validate employees input
     *
     * @param $aRequest
     * @return array
     */
    public static function validateCompanyInput($aRequest)
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
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['string', 'email', 'max:255', 'nullable'],
            'logo'    => ['nullable'],
            'website' => ['string', 'url', 'max:2083', 'nullable']
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
