<?php

declare(strict_types=1);

namespace miste\autorestarter\task;

use pocketmine\scheduler\Task;
use miste\autorestarter\AutoRestarter;

class CheckTimeTask extends Task{

    private $plugin;

    public function __construct(AutoRestarter $plugin){
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick) : void{
        if($this->plugin->delay === 0){
            $this->plugin->restart($this->plugin->getServer(), AutoRestarter::$serverIp, AutoRestarter::$serverPort);
        }else{
            if($this->plugin->delay > 5){
                if($this->plugin->delay % 10 === 0){
                    if($this->plugin->message !== ""){
                        $this->plugin->getServer()->broadcastMessage(str_replace("%value%", $this->plugin->delay, $this->plugin->message));
                    }
                }
            }else{
                if($this->plugin->message !== ""){
                    $this->plugin->getServer()->broadcastMessage(str_replace("%value%", $this->plugin->delay, $this->plugin->message));
                }
            }
            $this->plugin->delay = $this->plugin->delay - 1;
        }
    }
}