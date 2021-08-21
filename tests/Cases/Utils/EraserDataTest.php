<?php

namespace Prokl\WebProfilierBundle\Tests\Cases\Utils;

use Mockery;
use Prokl\TestingTools\Base\BaseTestCase;
use Prokl\WebProfilierBundle\Contract\UniqualizatorProfileDataInterface;
use Prokl\WebProfilierBundle\EraserData;

/**
 * Class EraserDataTest
 *
 * @since 21.08.2021
 */
class EraserDataTest extends BaseTestCase
{
    /**
     * @var EraserData
     */
    protected $obTestObject;

    private $cachePath = __DIR__ . '/../Fixture/cache';

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        @unlink(__DIR__ . '/../Fixture/cache/package.txt');
        $this->obTestObject = new EraserData(
            $this->getMockUniqualizatorProfileDataInterface(),
            ''
        );
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->rrmdir(__DIR__ . '/../Fixture/cache');
    }

    /**
     * clear().
     *
     * @return void
     */
    public function testClear() : void
    {
        $result = $this->obTestObject->clear();

        $this->assertSame(0, $result);
    }

    /**
     * clear().
     *
     * @return void
     */
    public function testClearWithFixture() : void
    {
        $this->obTestObject = new EraserData(
            $this->getMockUniqualizatorProfileDataInterface(1),
            $this->cachePath
        );

        if (!@file_exists($this->cachePath)) {
            @mkdir($this->cachePath);
        }

        file_put_contents($this->cachePath . '/package.txt', 'OK');

        $result = $this->obTestObject->clear();

        $this->assertSame(1, $result);
        $this->assertFalse(@file_exists($this->cachePath . '/package.txt'));
    }

    /**
     * Мок UniqualizatorProfileDataInterface
     *
     * @param int $times
     *
     * @return mixed
     */
    private function getMockUniqualizatorProfileDataInterface(int $times = 1)
    {
        $mock = Mockery::mock(UniqualizatorProfileDataInterface::class);
        $mock = $mock->shouldReceive('baseFilename')->times($times)->andReturn('package');

        return $mock->getMock();
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
        if (!@file_exists($dir)) {
            return;
        }

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