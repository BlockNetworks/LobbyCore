<?php

namespace Core\Task;

use pocketmine\{Player,Server};
use pocketmine\level\{Level,Position};
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityLevelChangeEvent;
use Core\Main;
use onebone\economyapi\EconomyAPI;

class Broadcaster extends Task {
	
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function sendMessage(){
		$msg = [
		"Message 1",
		"Message 2",
		"Message 3",
		"Message 4",
		"Message 5",
		];
		$msgs = $msg[array_rand($msg)];
		$title = array("§7[§6Network§7]§8 »§7 ");
		$titles = $title[array_rand($title)];
		$this->plugin->getServer()->broadcastMessage($titles.$msgs);
	}
		
	public function onRun(int $currentTick){
		$this->sendMessage();
	}
}