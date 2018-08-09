<?php

declare(strict_types=1);

namespace miste\autorestarter\event;

use pocketmine\event\Event;

class MidRestartEvent extends Event{

    protected $players;

    public function __construct(array $players){
        $this->players = $players;
    }

    public function getTransferredPlayers() : array{
        return $this->players;
    }
}