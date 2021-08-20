<?php

namespace Prokl\WebProfilierBundle;

/**
 * Class EraserData
 * @package Prokl\WebProfilierBundle
 *
 * @since 17.08.2021
 */
class EraserData
{
    /**
     * @var string $jsonPath
     */
    private $jsonPath = '';

    /**
     * @param string $jsonPath Путь к кэшу.
     */
    public function __construct(string $jsonPath)
    {
        $this->jsonPath = $jsonPath;
    }

    /**
     * Движуха.
     *
     * @return void
     */
    public function clear() : void
    {
        $this->rrmdir($_SERVER['DOCUMENT_ROOT'] . '/bitrix/cache/profiler');
        // ToDo - привести к реалиям.
        @unlink($this->jsonPath);
    }

    /**
     * Рекурсивно удалить папку со всем файлами и папками.
     *
     * @param string $dir Директория.
     *
     * @return void
     */
    private function rrmdir(string $dir) : void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object !== '.' && $object !== '..') {
                    if (filetype($dir. '/' .$object) === 'dir') {
                        $this->rrmdir($dir . '/' . $object);
                    } else {
                        unlink($dir. '/' . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}