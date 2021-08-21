<?php

namespace Prokl\WebProfilierBundle;

use Prokl\WebProfilierBundle\Contract\UniqualizatorProfileDataInterface;

/**
 * Class EraserData
 * @package Prokl\WebProfilierBundle
 *
 * @since 17.08.2021
 */
class EraserData
{
    /**
     * @var UniqualizatorProfileDataInterface $uniqualizatorProfileData Уникализатор.
     */
    private $uniqualizatorProfileData;

    /**
     * @var string $jsonPath
     */
    private $jsonPath = '';

    /**
     * @param string                            $jsonPath                 Путь к кэшу.
     * @param UniqualizatorProfileDataInterface $uniqualizatorProfileData Уникализатор.
     */
    public function __construct(
        UniqualizatorProfileDataInterface $uniqualizatorProfileData,
        string $jsonPath
    ) {
        $this->jsonPath = $jsonPath;
        $this->uniqualizatorProfileData = $uniqualizatorProfileData;
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

        $userId = $GLOBALS['USER']->GetId();
        $files = scandir($this->jsonPath);

        $baseFilename = $this->uniqualizatorProfileData->baseFilename($this->jsonPath);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (stripos($file, $baseFilename) !== false) {
                @unlink($this->jsonPath . '/' . $file);
            }
        }
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