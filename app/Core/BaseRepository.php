<?php

namespace App\Core;

/**
 * BaseRepository
 * @package App\Core
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   09/03/2021
 * @version 1.0
 */
class BaseRepository
{
    /**
     * Request Instance
     *
     * @var object Request
     */
    protected $oRequest;

    /**
     * Get filter param
     *
     * @param $aFilter
     * @return array
     */
    public function getFilter($aFilter)
    {
        $aParam = [];
        foreach ($aFilter as $aFilterKey) {
            $mValue = $this->oRequest->get($aFilterKey);
            if ($mValue !== null) {
                array_push($aParam, [$aFilterKey, '=', $mValue]);
            }
        }

        return $aParam;
    }
}
