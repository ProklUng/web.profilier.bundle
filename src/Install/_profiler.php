<?php

use Prokl\WebProfilierBundle\Controller\ProfilerAdminController;
use Symfony\Component\HttpFoundation\Request;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

if (!function_exists('container')) {
    throw new \RuntimeException(
        'You must install https://github.com/ProklUng/bitrix.core.symfony or realize helper
               for recieve Symfony container instance. 
      '
    );
}

/** @var  $controller ProfilerAdminController */
$controller = container()->get(ProfilerAdminController::class);
$content = $controller->action(new Request());
$content->sendContent();

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog.php';