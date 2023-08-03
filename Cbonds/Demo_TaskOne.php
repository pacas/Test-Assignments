<?php

namespace Cbonds;

require_once realpath(__DIR__.'/vendor/autoload.php');

$dumpLoad = TaskOne::dumpPage();
print_r($dumpLoad);