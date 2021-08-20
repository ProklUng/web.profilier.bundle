<?php

namespace Prokl\WebProfilierBundle\Contract;

/**
 * Interface DataFileHandlerInterface
 *
 * @package Prokl\WebProfilierBundle\Contract
 * @since 20.08.2021
 */
interface DataFileHandlerInterface
{
    /**
     * Записать файл с данными профайлера.
     *
     * @param array $data Данные.
     *
     * @return string
     */
    public function write(array $data) : string;

    /**
     * Считать данные из файла.
     *
     * @return array
     */
    public function read() : array;
}