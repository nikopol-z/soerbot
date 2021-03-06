<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Commands\Devs\Exceptions\TopicException;
use SoerBot\Commands\Devs\Implementations\TopicModel;
use SoerBot\Commands\Devs\Exceptions\TopicExceptionFileNotFound;

class TopicModelTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Exceptions.
     */
    public function testConstructorThrowExceptionWhenDirectoryNotExist()
    {
        $input = 'second';
        $path = __DIR__ . '/not_exist/';

        $this->expectException(TopicExceptionFileNotFound::class);
        $this->expectExceptionMessage('Directory ' . $path . ' does not exists. Check directory source.');

        new TopicModel($input, $path);
    }

    public function testConstructorThrowExceptionWhenFileNotExist()
    {
        $input = 'not_exist';
        $path = __DIR__ . '/testfiles/';
        $extension = '.topic.md';
        $file = $path . $input . $extension;

        $this->expectException(TopicExceptionFileNotFound::class);
        $this->expectExceptionMessage('File ' . $file . ' does not exists. Check file source.');

        new TopicModel($input, $path);
    }

    public function testConstructorThrowExceptionWhenFileIsEmpty()
    {
        $input = 'empty';
        $path = __DIR__ . '/testfiles/';
        $extension = '.topic.md';
        $file = $path . $input . $extension;

        $this->expectException(TopicException::class);
        $this->expectExceptionMessage('File ' . $file . ' is empty. Check file source.');

        new TopicModel($input, $path);
    }

    public function testValidateThrowExceptionWhenDirectoryNotExist()
    {
        $input = 'second';
        $goodPath = __DIR__ . '/testfiles/';
        $wrongPath = __DIR__ . '/not_exist/';
        $topic = new TopicModel($input, $goodPath);
        $method = $this->getPrivateMethod($topic, 'validate');

        $this->expectException(TopicExceptionFileNotFound::class);
        $this->expectExceptionMessage('Directory ' . $wrongPath . ' does not exists. Check directory source.');

        $method->invokeArgs($topic, [$wrongPath]);
    }

    /**
     * Corner cases.
     */

    /**
     * Functionality.
     */
    public function testGetContentReturnExpected()
    {
        $input = 'second';
        $path = __DIR__ . '/testfiles/';

        $topic = new TopicModel($input, $path);

        $this->assertSame('test file 2', $topic->getContent());
    }

    public function testValidateReturnDefaultWhenDirectoryIsEmpty()
    {
        $input = 'second';
        $path = __DIR__ . '/testfiles/';
        $topic = new TopicModel($input, $path);

        $default = $this->getPrivateVariableValue($topic, 'directory');
        $method = $this->getPrivateMethod($topic, 'validate');

        $this->assertEquals($default, $method->invokeArgs($topic, ['']));
    }

    public function testValidateReturnExpectedWhenDirectoryExist()
    {
        $input = 'second';
        $path = __DIR__ . '/testfiles/';
        $topic = new TopicModel($input, $path);

        $method = $this->getPrivateMethod($topic, 'validate');

        $this->assertEquals($path, $method->invokeArgs($topic, [$path]));
    }

    public function testIsTopicReturnTrueWhenRightFile()
    {
        $input = 'second';
        $path = __DIR__ . '/testfiles/';

        $topic = new TopicModel($input, $path);

        $method = $this->getPrivateMethod($topic, 'isTopic');

        $this->assertTrue($method->invokeArgs($topic, [__DIR__ . '/testfiles/second.topic.md']));
    }

    public function testIsTopicReturnFalseWhenWrongFile()
    {
        $input = 'second';
        $path = __DIR__ . '/testfiles/';

        $topic = new TopicModel($input, $path);
        $method = $this->getPrivateMethod($topic, 'isTopic');

        $this->assertFalse($method->invokeArgs($topic, [__DIR__ . '/testfiles/wrong_extension.md']));
    }
}
