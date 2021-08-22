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
     * @param Profiler $profiler Profiler.
     */
    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
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
            $headers->add('X-Debug-Token-Link', '/_profiler/token/render/?token=' . $token);
            $headers->set('X-Debug-Token', $token);

            $GLOBALS['APPLICATION']->AddPanelButton(
                [
                    "ID" => "bitrix-profilier", //определяет уникальность кнопки
                    "TEXT" => "Профайлер текущего запроса",
                    "TYPE" => "BIG", //BIG - большая кнопка, иначе маленькая
                    "MAIN_SORT" => 10000, //индекс сортировки для групп кнопок
                    "SORT" => 10, //сортировка внутри группы
                    "HREF" => '/_profiler/token/render/?token=' . $token, //или javascript:MyJSFunction())
                    "ICON" => "bitrix-profilier-icon", //название CSS-класса с иконкой кнопки
                    "SRC" => "",
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
        }
    }
}