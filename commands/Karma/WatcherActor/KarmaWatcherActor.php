<?php

namespace SoerBot\Commands\Karma\WatcherActor;

use CharlotteDunois\Yasmin\Models\Message;
use SoerBot\Commands\Karma\Implementations\UserModel;
use SoerBot\Watcher\Interfaces\WatcherActorInterface;

class KarmaWatcherActor implements WatcherActorInterface
{
    /**
     * @var UserModel
     */
    private $user;

    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        $this->client = $client;
        $this->user = new UserModel();
    }

    /**
     * @return SoerBot\Commands\Karma\Implementations\UserModel;
     */
    public function getUser(): UserModel
    {
        return $this->user;
    }

    /**
     * Проверяет соответствует ли сообщение требованиям Watcher-а.
     *
     * @param $message
     * @return boolean;
     */
    public function isPassRequirements(Message $message)
    {
        if (!$message->author->bot) {
            return true;
        }

        return false;
    }

    /**
     * Выполняет действие, заложенное в Wathcher.
     *
     * @param $message
     * @return void
     */
    public function run(Message $message)
    {
        try {
            $this->user->incrementUserKarma($message->author->username);
        } catch (InvalidUserNameException $error) {
            $this->client->emit('debug', $error->getMessage());
        }
    }
}
