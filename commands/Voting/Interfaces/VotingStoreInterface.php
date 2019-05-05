<?php

namespace SoerBot\Commands\Voting\Interfaces;

interface VotingStoreInterface
{
    public function get();

    public function add(array $args);
	public function load();
    public function save();
}
