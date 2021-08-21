<?php

namespace Prokl\WebProfilierBundle\Utils;

use Prokl\WebProfilierBundle\Contract\DataFileHandlerInterface;
use Prokl\WebProfilierBundle\Contract\UniqualizatorProfileDataInterface;
use RuntimeException;

/**
 * Class DataFileHandler
 * @package Prokl\WebProfilierBundle\Utils
 *
 * @since 20.08.2021
 */
class DataFileHandler implements DataFileHandlerInterface
{
    /**
     * @var UniqualizatorProfileDataInterface $uniqualizatorProfileData Уникализатор имени файла с данными.
     */
    private $uniqualizatorProfileData;

    /**
     * @var string $basePath
     */
    private $basePath;

    /**
     * @param UniqualizatorProfileDataInterface $uniqualizatorProfileData Уникализатор имени файла с данными.
     * @param string                            $basePath                 Базовый путь.
     */
    public function __construct(UniqualizatorProfileDataInterface $uniqualizatorProfileData, string $basePath)
    {
        $this->uniqualizatorProfileData = $uniqualizatorProfileData;
        $this->basePath = $basePath;

        if (!@file_exists($basePath)) {
            @mkdir($basePath);
        }
    }

    /**
     * @inheritDoc
     */
    public function write(array $data) : string
    {
        $profilerDataFile = $this->uniqualizatorProfileData->unique($this->basePath);

        // Подтянуть старые данные.
        $final = [];
        if (@file_exists($profilerDataFile)) {
            $profilerData = @file_get_contents($profilerDataFile);
            $final = unserialize($profilerData);
        }

        $final = array_merge($data, $final);

        @unlink($profilerDataFile);
        $serialized = serialize($final);

        $result = file_put_contents($profilerDataFile, $serialized);

        if ($result === false) {
            throw new RuntimeException(
                'Writing of data of profiler failed.'
            );
        }

        @unlink($profilerDataFile);
        $serialized = serialize($final);

        $result = file_put_contents($profilerDataFile, $serialized);

        if ($result === false) {
            throw new RuntimeException('Writing of data of profiler failed.');
        }

        return $profilerDataFile;
    }

    /**
     * @inheritDoc
     */
    public function read() : array
    {
        $profilerDataFile = $this->uniqualizatorProfileData->unique($this->basePath);

        if (!@file_exists($profilerDataFile)) {
            $data = [];
        } else {
            $profilerData = @file_get_contents($profilerDataFile);
            $data = unserialize($profilerData);

            if (!$data) {
                throw new RuntimeException('Error unserializing profiling data.');
            }
        }

        return $data;
    }
}
