<?php

namespace App\Core;

use App\Constants\AppConstants;

/**
 * BaseTransformer
 * @package App\Core
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   09/03/2021
 * @version 1.0
 */
class BaseTransformer
{
    /**
     * Successful response format
     *
     * @param array $aData
     * @return array
     */
    public static function transformResponse($aData)
    {
        return [
            AppConstants::CODE    => 200,
            AppConstants::MESSAGE => 'Success',
            AppConstants::DATA    => $aData
        ];
    }

    /**
     * Error response format
     *
     * @param $sCode
     * @param $sMessage
     * @param $sMethod
     * @param $sController
     * @return array
     */
    public static function transformErrorResponse($sCode, $sMessage, $sMethod, $sController)
    {
        return [
            AppConstants::CODE    => $sCode,
            AppConstants::MESSAGE => $sMessage,
            AppConstants::DATA    => [
                'controller' => $sController,
                'method'     => $sMethod
            ]
        ];
    }
}
