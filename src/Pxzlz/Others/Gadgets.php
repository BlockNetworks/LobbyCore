<?php

namespace Core\Others;

use pocketmine\item\Item;
use pocketmine\{Player,Server};
use pocketmine\utils\TextFormat as TE;
use pocketmine\utils\Config;
use Core\Main;
use Core\Form\FormUI;

class Gadgets {
	
	private $plugin;
	use FormUI;
	
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function give($pl){
		$pl->getInventory()->clearAll();
		$pl->getArmorInventory()->clearAll();
		$pl->getInventory()->setItem(0,Item::get(339,0,1)->setCustomName(TE::BOLD."§bInformation§r"."\n"."§7[§eClick view§7]"));
		$pl->getInventory()->setItem(1,Item::get(345,0,1)->setCustomName(TE::BOLD."§aServers§r"."\n"."§7[§eClick view§7]"));
		$pl->getInventory()->setItem(2,Item::get(467,0,1)->setCustomName(TE::BOLD."§5Cosmetics§r"."\n"."§7[§eClick view§7]"));
		$pl->getInventory()->setItem(7,Item::get(402,0,1)->setCustomName(TE::BOLD."§cReport§r"."\n"."§7[§eClick view§7]"));
		$pl->getInventory()->setItem(8,Item::get(399,0,1)->setCustomName(TE::BOLD."§6Hub§r"."\n"."§7[§eClick view§7]"));
	}
	
	public function Info($pl){
		$form = $this->createSimpleForm(function (Player $pl, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
				$this->Server($pl);
				break;
				case 1:
				$this->Ranks($pl);
				break;
				case 2:
				$this->Rules($pl);
				break;
			}
		});
		$form->setTitle("§b§lInformation Menu");
		$form->setContent("§7Select an option to view");
		$form->addButton("§0§lServer§r\n§3Click view",0,"textures/items/book_portfolio");
		$form->addButton("§0§lRanks§r\n§3Click view",0,"textures/ui/icon_setting");
		$form->addButton("§0§lRules§r\n§3Click view",0,"textures/ui/inventory_icon");
		$form->sendToPlayer($pl);
		return $form;
	}
	
	public function Server($pl){
		$form = $this->createSimpleForm(function (Player $pl, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
				break;
			}
		});
		$form->setTitle("§0§lServer Menu");
		$form->setContent($this->plugin->getConfig()->get("Server-info"));
		$form->addButton("§l§aComprendo",0,"textures/ui/check");
		$form->sendToPlayer($pl);
		return $form;
	}
	
	public function Ranks($pl){
		$form = $this->createSimpleForm(function (Player $pl, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
				break;
			}
		});
		$form->setTitle("§0§lRanks Menu");
		$form->setContent($this->plugin->getConfig()->get("Ranks-info"));
		$form->addButton("§l§aComprendo",0,"textures/ui/check");
		$form->sendToPlayer($pl);
		return $form;
	}
	
	public function Rules($pl){
		$form = $this->createSimpleForm(function (Player $pl, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
				break;
			}
		});
		$form->setTitle("§0§lRules Menu");
		$form->setContent($this->plugin->getConfig()->get("Rules-info"));
		$form->addButton("§l§aComprendo",0,"textures/ui/check");
		$form->sendToPlayer($pl);
		return $form;
	}
	
	public function MiniGM($pl){
		$form = $this->createSimpleForm(function (Player $pl, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
				$this->plugin->getServer()->dispatchCommand($pl, $this->plugin->getConfig()->get("command1"));
				break;
				case 1:
				$this->plugin->getServer()->dispatchCommand($pl, $this->plugin->getConfig()->get("command2"));
				break;
				case 2:
				$this->plugin->getServer()->dispatchCommand($pl, $this->plugin->getConfig()->get("command3"));
				break;
				case 3:
				$this->plugin->getServer()->dispatchCommand($pl, $this->plugin->getConfig()->get("command4"));
				break;
			}
		});
		$form->setTitle("§a§lServer Selector");
		$form->setContent("§7Click a Game to Join!");
		$form->addButton("§0§lSkyBlock§r\n§3Click view",0,"textures/blocks/grass_block");
		$form->addButton("§0§lKitPVP§r\n§3Click view",0,"textures/items/diamond_sword");
		$form->addButton("§0§lMiniGames§r\n§3Click view",0,"textures/ui/icon_carrot");
		$form->addButton("§0§lDuels§r\n§3Click view",0,"textures/ui/icon_new");
		$form->sendToPlayer($pl);
		return $form;
	}
	
	public function Cms($pl){
		$form = $this->createSimpleForm(function (Player $pl, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
				if($pl->hasPermission("core.fly")){
					if($pl->getAllowFlight()){
						$pl->setAllowFlight(false);
						$pl->setFlying(false);
						$pl->sendMessage("§cFly Deactivated!");
					} else {
						$pl->setAllowFlight(true);
						$pl->sendMessage("§aFly Activated!");
					}
				} else {
					$pl->sendMessage("§7[§c§l!§r§7]§e You dont have permissions to use.");
				}
				break;
				case 1:
				if($pl->hasPermission("core.size")){
					$this->Size($pl);
				} else {
					$pl->sendMessage("§7[§c§l!§r§7]§e You dont have permissions to use.");
				}
				break;
				case 2:
				if($pl->hasPermission("core.time")){
					$this->Time($pl);
				} else {
					$pl->sendMessage("§7[§c§l!§r§7]§e You dont have permissions to use.");
				}
				break;
				case 3:
				if($pl->hasPermission("core.particle")){
					$this->Particles($pl);
				} else {
					$pl->sendMessage("§7[§c§l!§r§7]§e You dont have permissions to use.");
				}
				break;
			}
		});
		$form->setTitle("§5§lCosmetics Menu");
		$form->setContent("§7Click an option to View");
		$form->addButton("§0§lFLY§r\n§3Click use",0,"textures/items/fireworks");
		$form->addButton("§0§lSIZE§r\n§3Click use",0,"textures/items/totem");
		$form->addButton("§0§lTIME§r\n§3Click use",0,"textures/items/clock_item");
		$form->addButton("§0§lParticles§r\n§3Click use",0,"textures/ui/icon_staffpicks");

		$form->sendToPlayer($pl);
		return $form;
	}
	
	public function ColorChat($pl){
		$form = $this->createSimpleForm(function (Player $pl, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
				if(!in_array($pl->getName(), $this->plugin->cc)){
					$this->plugin->cc[] = $pl->getName();
					$pl->sendMessage($this->plugin->prefix."§fActivated Color Chat");
				}
				break;
				case 1:
				if(in_array($pl->getName(), $this->plugin->cc)){
					unset($this->plugin->cc[array_search($pl->getName(), $this->plugin->cc)]);
					$pl->sendMessage($this->plugin->prefix."§fDeactivated Color Chat");
				}
				break;
			}
		});
		$form->setTitle("§l§0Color Chat Menu");
		$form->addButton("§l§0Activate§r\n§3Click use");
		$form->addButton("§0§lDeactivate§r\n§3Click use");
		$form->sendToPlayer($pl);
		return $form;
	}
	
	public function Size($pl){
		$form = $this->createSimpleForm(function (Player $pl, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
				$pl->setScale(0.5);
				$pl->sendMessage($this->plugin->prefix."§fYou have successfully resized to §6Small§f");
				break;
				case 1:
			    $pl->setScale(1.0);
				$pl->sendMessage($this->plugin->prefix."§fYou have successfully resized to §6Normal§f");
				break;
				case 2:
				$pl->setScale(1.5);
				$pl->sendMessage($this->plugin->prefix."§fYou have successfully resized to §6Big§f");
				break;
			}
		});
		$form->setTitle("§b§lSize Menu");
		$form->addButton("§0§lSmall§r\n§3Click use");
		$form->addButton("§0§lNormal§r\n§3Click use");
		$form->addButton("§0§lBig§r\n§3Click use");
		$form->sendToPlayer($pl);
	    return $form;
	}
	
	public function Time($pl){
		$form = $this->createSimpleForm(function (Player $pl, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
				$lvl = $this->plugin->getServer()->getDefaultLevel();
				$lvl->setTime(0);
				$lvl->stopTime();
				$pl->sendMessage($this->plugin->prefix."§fYou have successfully set your time to §6Day§f");
				break;
				case 1:
				$lvl = $this->plugin->getServer()->getDefaultLevel();
				$lvl->setTime(20000);
				$lvl->stopTime();
				$pl->sendMessage($this->plugin->prefix."§fYou have successfully set your time to §6Night§f");
				break;
			}
		});
		$form->setTitle("§b§lTime Menu");
		$form->addButton("§0§lDay§r\n§3Click use");
		$form->addButton("§0§lNight§r\n§3Click use");
		$form->sendToPlayer($pl);
	    return $form;
	}
	
	public function Particles($pl){
		$form = $this->createSimpleForm(function (Player $pl, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
				if($pl->hasPermission("core.blue")){
					if(!in_array($pl->getName(), $this->plugin->blue)){
						$this->plugin->blue[] = $pl->getName();
						$pl->sendMessage($this->plugin->prefix.TE::WHITE."Has activado el trial: ".TE::BLUE."Blue");
						if(in_array($pl->getName(), $this->plugin->red)){
							unset($this->plugin->red[array_search($pl->getName(), $this->plugin->red)]);
						}elseif (in_array($pl->getName(), $this->plugin->green)){
						    unset($this->plugin->green[array_search($pl->getName(), $this->plugin->green)]);
						}
					}else{
						unset($this->plugin->blue[array_search($pl->getName(), $this->plugin->blue)]);
						$pl->sendMessage($this->plugin->prefix.TE::WHITE."Particle Deactivated: ".TE::BLUE."Blue");
					}
				}else{
					$pl->sendMessage("§7[§c§l!§r§7]§e You dont have permissions to use.");
				}
				break;
				case 1:
				if($pl->hasPermission("core.red")){
					if(!in_array($pl->getName(), $this->plugin->red)){
						$this->plugin->red[] = $pl->getName();
						$pl->sendMessage($this->plugin->prefix.TE::WHITE."Particle Enabled: ".TE::RED."Red");
						if(in_array($pl->getName(), $this->plugin->blue)){
							unset($this->plugin->blue[array_search($pl->getName(), $this->plugin->blue)]);
						}elseif (in_array($pl->getName(), $this->plugin->green)){
						    unset($this->plugin->green[array_search($pl->getName(), $this->plugin->green)]);
						}
					}else{
						unset($this->plugin->red[array_search($pl->getName(), $this->plugin->red)]);
						$pl->sendMessage($this->plugin->prefix.TE::WHITE."Particle Disabled: ".TE::RED."Red");
					}
				}else{
					$pl->sendMessage("§7[§c§l!§r§7]§e You dont have permissions to use.");
				}
				break;
				case 2:
				if($pl->hasPermission("core.green")){
					if(!in_array($pl->getName(), $this->plugin->green)){
						$this->plugin->green[] = $pl->getName();
						$pl->sendMessage($this->plugin->prefix.TE::WHITE."Particle Enabled: ".TE::GREEN."Green");
						if(in_array($pl->getName(), $this->plugin->blue)){
							unset($this->plugin->blue[array_search($pl->getName(), $this->plugin->blue)]);
						}elseif (in_array($pl->getName(), $this->plugin->red)){
						    unset($this->plugin->red[array_search($pl->getName(), $this->plugin->red)]);
						}
					}else{
						unset($this->plugin->green[array_search($pl->getName(), $this->plugin->green)]);
						$pl->sendMessage($this->plugin->prefix.TE::WHITE."Particle Disabled: ".TE::GREEN."Green");
					}
				}else{
					$pl->sendMessage("§7[§c§l!§r§7]§e You dont have permissions to use.");
				}
				break;
			}
		});
		$form->setTitle("§0§lParticle Menu");
		$form->addButton("§0§lBlue§r\n§3Click use");
		$form->addButton("§0§lRed§r\n§3Click use");
		$form->addButton("§0§lGreen§r\n§3Click use");
		$form->sendToPlayer($pl);
	    return $form;
	}
}