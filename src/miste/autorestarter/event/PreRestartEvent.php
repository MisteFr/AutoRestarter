<?php

declare(strict_types=1);

namespace miste\autorestarter\event;

use pocketmine\event\Event;
use pocketmine\event\Cancellable;
use miste\autorestarter\AutoRestarter;

class PreRestartEvent extends Event implements Cancellable{

    protected $plugin;

    public function __construct(AutoRestarter $plugin){
        $this->plugin = $plugin;
    }

    public function setDelay(int $mins){
        $this->plugin->delay = $mins;
    }
}