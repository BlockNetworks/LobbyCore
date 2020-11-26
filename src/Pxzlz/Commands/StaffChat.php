<?php

namespace Core\Commands;

use pocketmine\{Player, Server}; 
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\{Config, TextFormat as TE}; 

use Core\Main; 

class StaffChat extends PluginCommand { 
	
	private $plugin; 
	
	public function __construct(Main $plugin){
		parent::__construct("sc", $plugin); 
		$this->setDescription("Command for staff");
		$this->plugin = $plugin; 
	}
	
	public function execute(CommandSender $sender, $label, array $args){
		if(!$sender instanceof Player){
			$sender->sendMessage("Use command in the game");
			return false;
		}
		if($sender->hasPermission("core.staffchat")){
			if(isset($args[0])){ 
				foreach($this->plugin->getServer()->getOnlinePlayers() as $pl){ 
					if($pl->hasPermission("core.staffchat")){
						$pl->sendMessage(TE::BOLD.TE::GRAY."(".TE::DARK_PURPLE."STAFFCHAT".TE::GRAY.") ".TE::RESET.TE::GRAY.$sender->getName().TE::YELLOW." » ".TE::DARK_AQUA.implode(" ", $args));
					}
				}
			}else{
				$sender->sendMessage($this->plugin->prefix.TE::RED."Usa : /sc <message>"); 
			}
		}else{
			$sender->sendMessage(TE::RED."You do not have permission to use");
		}
		return true; 
	}
}