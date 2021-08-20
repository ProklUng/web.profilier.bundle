<?php

use Prokl\WebProfilierBundle\Controller\ProfilerAdminController;
use Symfony\Component\HttpFoundation\Request;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

/** @var  $controller ProfilerAdminController */
$controller = container()->get(ProfilerAdminController::class);
$content = $controller->action(new Request());
$content->sendContent();

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog.php';