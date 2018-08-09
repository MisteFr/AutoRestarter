<?php

declare(strict_types = 1);

namespace miste\autorestarter;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;
use pocketmine\utils\Internet;

class AutoRestarter extends PluginBase{

    public $delay, $message;
    public static $serverIp;
    public static $serverPort;

    public function onEnable() : void{
        if (!is_dir($this->getDataFolder())){
            mkdir($this->getDataFolder());
        }
        $this->saveResource("config.yml");
        $config = new Config($this->getDataFolder() . "config.yml");
        $this->delay = $config->get("delay");
        $this->message = $config->get("message");
        self::$serverIp = $config->get("serverIp");
        self::$serverPort = $config->get("serverPort");
        $this->getScheduler()->scheduleRepeatingTask(new task\CheckTimeTask($this), 20*60);
    }

    public static function restart(Server $server, ?string $serverIp = "default", ?int $serverPort = 19132){
        var_dump((int) $server->getApiVersion());
        if(version_compare($server->getApiVersion(), '3.2.0') >= 0){
            $serverIp = $serverIp === "default" ? Internet::getIp() : $serverIp;
        }else{
            $serverIp = $serverIp === "default" ? Utils::getIp() : $serverIp;
        }
        $server->getPluginManager()->callEvent($ev = new event\PreRestartEvent($server->getPluginManager()->getPlugin("AutoRestarter")));
        if($ev->isCancelled()){
            return true;
        }else{
            $transferred = [];
            foreach ($server->getOnlinePlayers() as $p){
                $transferred[] = $p->getName();
                $p->transfer($serverIp, $serverPort);
            }

            $server->getPluginManager()->callEvent($ev = new event\MidRestartEvent($transferred));

            register_shutdown_function(function () {
                pcntl_exec("./start.sh");
            });

            $server->shutdown();
        }
    }
}