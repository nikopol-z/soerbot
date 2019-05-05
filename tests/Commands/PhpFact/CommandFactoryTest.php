<?php

namespace Tests\Commands\PhpFact;

use PHPUnit\Framework\TestCase;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Abstractions\CommandInterface;
use SoerBot\Commands\PhpFact\Implementations\CommandFactory;
use SoerBot\Commands\PhpFact\Exceptions\CommandNotFoundException;
use SoerBot\Commands\PhpFact\Implementations\Commands\FactCommand;
use SoerBot\Commands\PhpFact\Implementations\Commands\ListCommand;
use SoerBot\Commands\PhpFact\Implementations\Commands\StatCommand;
use SoerBot\Commands\PhpFact\Implementations\Commands\SearchCommand;

class CommandFactoryTest extends TestCase
{
    private $facts;

    protected function setUp()
    {
        try {
            $file = __DIR__ . '/phpfacts.txt';
            $storage = new FileStorage($file);
            $this->facts = new PhpFacts($storage);
        } catch (\Throwable $e) {
            $this->fail('Exception with ' . $e->getMessage() . ' was thrown is setUp method!');
        }

        parent::setUp();
    }

    /**
     * Exceptions.
     */
    public function testBuildThrowExceptionWhenCommandNotExistInsidePregMatch()
    {
        $this->expectException(CommandNotFoundException::class);

        CommandFactory::build($this->facts, 'not_exist');
    }

    public function testBuildThrowExceptionWhenCommandNotExistOusidePregMatch()
    {
        $this->expectException(CommandNotFoundException::class);

        CommandFactory::build($this->facts, 'not_exist not_exist not_exist not_exist');
    }

    /**
     * Corner cases.
     */

    /**
     * Functionality.
     */
    public function testBuildMakeObjectWhichImplementsRightInterface()
    {
        $command = CommandFactory::build($this->facts, 'list');

        $this->assertInstanceOf(CommandInterface::class, $command);
    }

    public function testBuildMakeRightObjectWhenFactCommand()
    {
        $command = CommandFactory::build($this->facts, 'fact');

        $this->assertInstanceOf(FactCommand::class, $command);
    }

    public function testBuildMakeRightObjectWhenFactCommandWithNumberArgument()
    {
        $command = CommandFactory::build($this->facts, 'fact 22');

        $this->assertInstanceOf(FactCommand::class, $command);
    }

    public function testBuildMakeRightObjectWhenSearchCommandWithNumberArgument()
    {
        $command = CommandFactory::build($this->facts, 'search not_exist');

        $this->assertInstanceOf(SearchCommand::class, $command);
    }

    public function testBuildMakeRightObjectWhenStatCommand()
    {
        $command = CommandFactory::build($this->facts, 'stat');

        $this->assertInstanceOf(StatCommand::class, $command);
    }

    public function testBuildMakeRightObjectWhenListCommand()
    {
        $command = CommandFactory::build($this->facts, 'list');

        $this->assertInstanceOf(ListCommand::class, $command);
    }
}
