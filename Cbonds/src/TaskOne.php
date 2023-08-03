<?php

namespace Cbonds;

class TaskOne extends SessionService
{
    const OUTPUT_FILE_NAME = 'server_data.html';

    public static function dumpPage(): string
    {
        $htmlPage = self::getRequestedPage();
        # здесь без DOM простым сохранением
        try {
            $file = fopen(__DIR__. '/../output/' . self::OUTPUT_FILE_NAME, 'w+');
            fwrite($file, $htmlPage);
            fclose($file);
        } catch (\Throwable $e) {
            return ($e);
        }
        return 'Success';
    }

}