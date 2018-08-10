# AutoRestarter

A PocketMine-MP plugin that automatically restart your server once the chosen time is up and who transfers your players to the IP and PORT chosen just before restarting !

### Config

You can choose in the config the delay between each restart in minutes, the message if you want one to be broadcasted every 10 mins if the time to reboot is > 5 mins and to what IP and PORT are the players transferred just before the restart.

Something that you can do is transfer them to the server that is going to be restarted by leaving "default" to serverIp and serverPort in the config. 

### Events
This plugin is calling the following events:
- `PreRestartEvent`: Called when the time is up and the server is going to restart, you can cancel this event and set a new delay in mins
```
use miste\autorestarter\event\PreRestartEvent;

/*
  To cancel server reboot and re schedule it 30 mins after
  NB: If you forget to setDelay() the event will be called the next min
*/

public function onPreRestartEvent(PreRestartEvent $ev){
        $ev->setDelay(30);
        $ev->setCancelled(true); 
}
```
- `MidRestartEvent`: Called once all the players have just been transferred and before the plugins are disabled. It can be very useful to deal with configs to save your players data. You can get all the transferred players by doing getTransferredPlayers()
```
use miste\autorestarter\event\MidRestartEvent;

public function onMidRestartEvent(MidRestartEvent $ev){
        foreach ($ev->getTransferredPlayers() as $playerName){
          //your code here
        }
}
```

### API
If you want to reboot the server at any time you can call the method `AutoRestarter::restart(Server $server, ?string $serverIp = "default", ?int $serverPort = 19132);`
```
/*
  This will restart the server and transfer all the players online to play.lbsg.net:19132.
*/
use miste\autorestarter\AutoRestarter;

AutoRestarter::restart($this->getServer(), "play.lbsg.net", 19132);

/*
  This will restart the server and transfer all the players online the IP and port provided in the config.
*/
use miste\autorestarter\AutoRestarter;

AutoRestarter::restart($this->getServer());
```

**NB**: You need pcntl extension to be enabled and compiled with PHP (by default on PM Linux binaries but not on Windows one).