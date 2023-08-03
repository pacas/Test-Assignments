<?php

namespace Cbonds;

require_once realpath(__DIR__.'/vendor/autoload.php');

try {
    TaskTwo::createXMLTable();
    print_r('Success');
} catch (\DOMException $e) {
    print_r($e);
}

