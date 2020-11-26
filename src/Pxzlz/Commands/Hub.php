<?php

namespace Core\Commands;

use pocketmine\Player;
use pocketmine\utils\TextFormat as TE;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

use Core\Main;

class Hub extends PluginCommand
{
	
	private $plugin;
	
	public function __construct(Main $plugin)
	{
		parent::__construct("hub", $plugin);
		$this->plugin = $plugin;
		$this->setDescription("Teleported to Hub");
	}
	
	public function execute(CommandSender $sender, $label, array $args)
	{
		if($sender instanceof Player){
			$sender->teleport($this->plugin->getServer()->getDefaultLevel()->getSafeSpawn());
			$sender->removeAllEffects();
			$sender->setHealth(20);
			$sender->setMaxHealth(20);
			$sender->setScale(1.0); 
			$sender->setGamemode(0);
			$sender->setFood(20);
			$sender->getArmorInventory()->clearAll();
			$sender->getInventory()->clearAll();
			$sender->setAllowFlight(false);
			$sender->sendMessage($this->plugin->prefix."§5Teleported to Spawn");
			$this->plugin->getArticulos()->give($sender);
		}
	}
}

