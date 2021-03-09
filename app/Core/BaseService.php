<?php


namespace App\Core;

use App\Constants\AppConstants;

/**
 * BaseService
 * @package App\Core
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   09/03/2021
 * @version 1.0
 */
class BaseService
{
    /**
     * Request Instance
     *
     * @var object $oRequest
     */
    protected $oRequest;

    /**
     * Initialize request
     *
     * @param array $aOption
     * @return array
     */
    public function initRequest($aOption = [])
    {
        return [
            AppConstants::JSON => $aOption
        ];
    }

    /**
     * Throw exception
     *
     * @param string $sCode
     * @param string $sMessage
     * @throws \Exception
     */
    public function throwException($sCode, $sMessage)
    {
        throw new \Exception($sMessage, $sCode);
    }
}
