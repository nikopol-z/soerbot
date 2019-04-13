<?php



return function ($client) {
    return (new class($client) extends \CharlotteDunois\Livia\Commands\Command
    {
        function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, array(
                'name' => 'sum2num', // Give command name
                'aliases' => array(),
                'group' => 'utils', // Group in ['command', 'util']
                'description' => 'The command takes 2 numbers and returns the sum of them.', // Fill the description
                'guildOnly' => false,
                'throttling' => array(
                    'usages' => 5,
                    'duration' => 10
                ),
                'guarded' => true,
                'args' => array( // If you need some variables you should either fill this section or remove it
                    array(
                        'key' => 'first_arg',
                        'label' => 'first_arg',
                        'prompt' => 'Введите первое число.',
                        'type' => 'string',
                    ),
                    array(
                        'key' => 'second_arg',
                        'label' => 'second_arg',
                        'prompt' => 'Введите второе число.',
                        'type' => 'string',
                    )
                    
                )
            ));
        }

        function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            $parsed1 = trim($args['first_arg']);
            $parsed2 = trim($args['second_arg']);

            if (empty($parsed1) || empty($parsed2)) {
                return $message->say('Один или оба аргумента не введены!');
            }
            
            if (is_numeric($args['first_arg'])){
                $arg1 = (float)$args['first_arg'];
            } else {
                return $message->say("Первый аргумент не число!");
            }

            if (is_numeric($args['second_arg'])){
                $arg2 = (float)$args['second_arg'];
            } else {
                return $message->say("Второй аргумент не число!");
            }

            $answer = $arg1 + $arg2;
            return $message->say("Сумма введенных чисел: {$answer}");

        }
    });
};