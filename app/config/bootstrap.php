<?php

use Symfony\Component\ErrorHandler\Debug;

require_once dirname(__DIR__).'/vendor/autoload.php';

if ($_SERVER['APP_DEBUG'] ?? false) {
    umask(0000);
    Debug::enable();
}
