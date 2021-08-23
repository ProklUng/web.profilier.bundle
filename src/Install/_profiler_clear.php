<?php

use Prokl\WebProfilierBundle\Controller\EraserDataController;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

if (!function_exists('container')) {
    throw new \RuntimeException(
        'You must install https://github.com/ProklUng/bitrix.core.symfony or realize helper
               for recieve Symfony container instance. 
      '
    );
}

/** @var  $controller EraserDataController */
$controller = container()->get(EraserDataController::class);
$content = $controller->action();
$content->sendContent();

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog.php';