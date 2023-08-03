<?php

namespace Cbonds;

use CurlHandle;

class SessionService
{
    const BASE_URL = 'http://cbonds.info/sandbox/some_source.php';
    const COOKIE_FILE = __DIR__. '/../output/cookies.txt';


    /**
     * Собирает данные со страницы
     * @return string
     */
    protected static function getRequestedPage(): string
    {
        self::getCookies();
        $session = curl_init();
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_ENCODING, 'gzip');
        curl_setopt($session, CURLOPT_URL, self::BASE_URL);
        curl_setopt($session, CURLOPT_COOKIEFILE, self::COOKIE_FILE);
        curl_setopt($session, CURLOPT_COOKIEJAR,  self::COOKIE_FILE);
        $htmlPage = curl_exec($session);
        #обход двух кодировок по содержимому таблицы
        $encodeBypass = explode('</thead>', $htmlPage);
        $decodedPage = iconv('Windows-1251','UTF-8', $encodeBypass[0]) . $encodeBypass[1];
        curl_close($session);
        return $decodedPage;
    }

    private static function getCookies(): void
    {
        $session = curl_init();
        curl_setopt($session,  CURLOPT_URL,  self::BASE_URL);
        curl_setopt($session,  CURLOPT_RETURNTRANSFER,  1);
        curl_setopt($session,  CURLOPT_HEADER,  1);
        curl_setopt($session, CURLOPT_COOKIEFILE, self::COOKIE_FILE);
        curl_setopt($session, CURLOPT_COOKIEJAR,  self::COOKIE_FILE);
        curl_exec($session);
    }
}