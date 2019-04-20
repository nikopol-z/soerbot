<?php

namespace Tests\Commands;

use Tests\TestCase;
use React\Promise\Promise;
use ArrayObject;

class Sum2NumCommandTest extends TestCase
{

    private $command;

    protected function setUp()
    {
        #$commandCreate = require __DIR__ . '/../../commands/Sum2Num/sum2num.command.php';
        $commandCreate = require 'D:\_Docs\soerbot\commands\Sum2num\sum2num.command.php';
        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(2))->method('has')->willReturn(true);
        $registry->expects($this->exactly(4))->method('__get')->with('types')->willReturn($types);
        $this->client->expects($this->exactly(4))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testSum2NumBasics()
    {
       $this->assertEquals($this->command->name, 'sum2num');
       $this->assertEquals($this->command->description, 'The command takes 2 numbers and returns the sum of them.');
       $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testSum2NumArguments()
    {
       $this->assertEquals(sizeof($this->command->args), 2);
       $this->assertArrayHasKey('key', $this->command->args[0]);
       $this->assertArrayHasKey('label', $this->command->args[0]);
       $this->assertArrayHasKey('prompt', $this->command->args[0]);
       $this->assertArrayHasKey('type', $this->command->args[0]);
    }

    public function testSimpleResponseToTheDiscord(): void
    {

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () { });

        $commandMessage->expects($this->once())->method('say')->with('...')->willReturn($promise);
 
        $this->command->run($commandMessage, new ArrayObject(['first_arg' => '1','second_arg' => '2']), false);
    }


    /**
     * @dataProvider myProvider
     */
    public function testSimpleConditions($a, $b, $expected)
    {
        $this->assertSame($expected, $a+$b);
    }

    public function myProvider()
    {
        return [
            [0,0,0],
            [0,1,1],
            [1,0,1],
            [1,1,2],
            [2,2,4],
            [2,3,5]
        ];
    }

    public function __sleep()
    {
        $this->command = null;
    }

}

