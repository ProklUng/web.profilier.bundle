<?php

namespace Prokl\WebProfilierBundle\Events;

use Bitrix\Main\Application;
use Symfony\Component\HttpKernel\Profiler\Profiler;

/**
 * Class BitrixAddButtonMenu
 *
 * @since 22.08.2021
 */
class BitrixAddButtonMenu
{
    /**
     * @var Profiler $profiler Profiler.
     */
    private $profiler;

    /**
     * @var string $baseUrl
     */
    private $baseUrl;

    /**
     * @var string $uniqueId
     */
    private $uniqueId;

    /**
     * @param Profiler $profiler Profiler.
     */
    public function __construct(
        Profiler $profiler,
        string $baseUrl = '/_profiler/token/render/',
        string $uniqueId = 'bitrix-profilier'
    ) {
        $this->profiler = $profiler;
        $this->baseUrl = $baseUrl;
        $this->uniqueId = $uniqueId;
    }

    /**
     * @return void
     */
    public function handle() : void
    {
        // Только админам!
        if (!$GLOBALS['USER']->IsAdmin()) {
            return;
        }

        $context = Application::getInstance()->getContext();
        $bitrixResponse = $context->getResponse();
        $headers = $bitrixResponse->getHeaders();

        if ($latest = current($this->profiler->find(null, null, 1, null, null, null))) {
            $token = $latest['token'];
            $headers->add('X-Debug-Token-Link', $this->baseUrl . '/?token=' . $token);
            $headers->set('X-Debug-Token', $token);

            $GLOBALS['APPLICATION']->AddPanelButton(
                [
                    "ID" => $this->uniqueId, //определяет уникальность кнопки
                    "TEXT" => "Профайлер <br/> текущего запроса",
                    "TYPE" => "BIG", //BIG - большая кнопка, иначе маленькая
                    "MAIN_SORT" => 10000, //индекс сортировки для групп кнопок
                    "SORT" => 10, //сортировка внутри группы
                    "HREF" => $this->baseUrl .'?token=' . $token, //или javascript:MyJSFunction())
                    "ICON" => "bitrix-profilier-icon", //название CSS-класса с иконкой кнопки
                    "SRC" => "/bitrix/images/symfony.png",
                    "ALT" => "Профайлер", //старый вариант
                    "HINT" => [ //тултип кнопки
                        "TITLE" => "Профайлер",
                        "TEXT" => "Профайл текущего запроса" //HTML допускается
                    ],
                    "HINT_MENU" => [
                        "TITLE" => "Профайлер",
                        "TEXT" => "Профайл текущего запроса" //HTML допускается
                    ],
                    "MENU" => []
                ],
                false
            );

            $GLOBALS['APPLICATION']->AddPanelButton(
                [
                    "ID" => $this->uniqueId . "-latest",
                    "TEXT" => "Профайлер <br/> последнего запроса",
                    "TYPE" => "BIG",
                    "MAIN_SORT" => 10000,
                    "SORT" => 10,
                    "HREF" => $this->baseUrl .'?token=latest',
                    "ICON" => "bitrix-profilier-icon",
                    "SRC" => "/bitrix/images/symfony.png",
                    "ALT" => "Профайлер последнего запроса",
                    "HINT" => [
                        "TITLE" => "Профайлер последнего запроса",
                        "TEXT" => "Профайлер последнего запроса"
                    ],
                    "HINT_MENU" => [
                        "TITLE" => "Профайлер последнего запроса",
                        "TEXT" => "Профайлер последнего запроса"
                    ],
                    "MENU" => []
                ],
                false
            );
        }
    }
}