<?php

namespace App\Libraries;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * GuzzleLib
 * @package App\Libraries
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   14/01/2021
 * @version 1.0
 */
class GuzzleLib
{
    /**
     * Main request
     * @param string $sUrl
     * @param string $sMethod
     * @param array  $aOption
     * @return array|mixed
     */
    public static function guzzleRequest($sUrl, $aOption = [], $sMethod = 'GET')
    {
        $bVerify = config('app.env') !== 'local';
        $oClient = new Client(['verify' => $bVerify]);
        try {
            $sResult = $oClient->request($sMethod, $sUrl, $aOption)->getBody()->getContents();

            return json_decode($sResult, true);
        } catch (GuzzleException $e) {
            $bNoResponse = is_null($e->getResponse());
            if ($bNoResponse) {
                $iCode = $e->getCode();
                $sMessage = $e->getMessage();
                $mBody = $e->getRequest()->getBody();
            } else {
                $iCode = $e->getResponse()->getStatusCode();
                $sMessage = $e->getResponse()->getReasonPhrase();
                $mBody = $e->getResponse()->getBody();
            }
            $sLog = sprintf('%s: %s', $iCode, $sMessage);
            Log::info($sLog);

            return [
                'code'    => $iCode,
                'message' => $sMessage,
                'data'    => json_decode($mBody, true)
            ];
        }
    }
}
