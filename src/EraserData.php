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
     * @var string $profilerDataDirectory Путь к кэшу.
     */
    private $profilerDataDirectory;

    /**
     * @param string                            $profilerDataDirectory    Путь к кэшу.
     * @param UniqualizatorProfileDataInterface $uniqualizatorProfileData Уникализатор.
     */
    public function __construct(
        UniqualizatorProfileDataInterface $uniqualizatorProfileData,
        string $profilerDataDirectory
    ) {
        $this->profilerDataDirectory = $profilerDataDirectory;
        $this->uniqualizatorProfileData = $uniqualizatorProfileData;
    }

    /**
     * Движуха.
     *
     * @return integer Количество удаленных файлов.
     */
    public function clear() : int
    {
        $this->rrmdir('/bitrix/cache/profiler');

        $baseFilename = $this->uniqualizatorProfileData->baseFilename($this->profilerDataDirectory);
        if (!$this->profilerDataDirectory) {
            return 0;
        }

        $files = scandir($this->profilerDataDirectory);

        $count = 0;
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (stripos($file, $baseFilename) !== false) {
                @unlink($this->profilerDataDirectory . '/' . $file);
                $count++;
            }
        }

        return $count;
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
        if (!@file_exists($_SERVER['DOCUMENT_ROOT'] . $dir)) {
            return;
        }

        $dir = $_SERVER['DOCUMENT_ROOT'] . $dir;

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