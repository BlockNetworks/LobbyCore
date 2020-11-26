<?php

namespace Core;

use pocketmine\{Player,Server};
use pocketmine\event\player\{PlayerJoinEvent,PlayerQuitEvent,PlayerInteractEvent,PlayerDropItemEvent,PlayerExhaustEvent,PlayerPreLoginEvent,PlayerItemHeldEvent,PlayerRespawnEvent,PlayerMoveEvent,PlayerChatEvent};
use pocketmine\event\entity\{EntityDamageEvent,EntityDamageByEntityEvent,EntityLevelChangeEvent};
use pocketmine\level\{Level,Position,Location};
use pocketmine\math\{Vector2,Vector3};
use pocketmine\utils\{Config,TextFormat as TE};
use pocketmine\plugin\{Plugin,PluginBase as Pb};
use pocketmine\event\{Event,Listener as Lt};
use pocketmine\level\sound\{EndermanTeleportSound, FizzSound, PopSound};
use Core\Task\{Broadcaster,Particulas};
use Core\Form\FormUI;
use Core\Others\Gadgets;
use Core\Commands\{Hub,StaffChat,};
use _64FF00\PurePerms\PurePerms;

class Main extends Pb implements Lt{
	
	use FormUI;
	public $red = array();
	public $green = array();
	public $blue = array();
	public $cc = array();
	public $item = TE::BOLD."§8[§cITEM§r";
	public $prefix = TE::BOLD."§f§lNetwork§8 » §r";
	
	public function onEnable(){
		$this->getLogger()->info("Plugin Activated");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getScheduler()->scheduleRepeatingTask(new Broadcaster($this), 3 * 60 * 15);
		$this->getScheduler()->scheduleRepeatingTask(new Particulas($this), 10);
		$this->getServer()->getCommandMap()->register("/hub", new Hub($this));
		$this->getServer()->getCommandMap()->register("/sc", new StaffChat($this));
		$this->getResource("config.yml");
		$this->saveDefaultConfig();
		@mkdir($this->getDataFolder());
	}
	
	public function Gamemode($pl){
		$form = $this->createSimpleForm(function (Player $pl, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
				$pl->sendMessage($this->prefix."§fYour GameMode has been set to §eSurvival");
				$pl->setGamemode(0);
				break;
				$pl->sendMessage($this->prefix."§fYour GameMode has been set to §eCreativo");
				$pl->setGamemode(1);
				break;
				$pl->sendMessage($this->prefix."§fYour GameMode has been set to §eSpectator");
				$pl->setGamemode(3);
				break;
			}
		});
		$form->setTitle("§0§lGAMEMODEUI MENU");
		$form->addButton("§0§lSURVIVAL");
		$form->addButton("§0§lCREATIVO");
		$form->addButton("§0§lESPECTADOR");
		$form->sendToPlayer($pl);
		return $form;
	}
	
	public function onItemID(PlayerItemHeldEvent $ev){
		$pl = $ev->getPlayer();
		if ($pl->isOp()){
			$pl->sendTip($this->item." ".$ev->getItem()->getId()."§f:§f".$ev->getItem()->getDamage()."§l§8]");
		}
	}
	
	public function getArticulos(){
		return new Gadgets($this);
	}
	
	public function onJoin(PlayerJoinEvent $ev){
		$pl = $ev->getPlayer();
		$lvl = $pl->getLevel();
		$rank = $this->getServer()->getPluginManager()->getPlugin("PurePerms")->getUserDataMgr($pl)->getGroup($pl);
		$lvl->addSound(new EndermanTeleportSound(new Vector3($pl->getX(), $pl->getY(), $pl->getZ())));
		$ev->setJoinMessage("");
		$pl->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
		$pl->setGamemode(0);
		$pl->setHealth(20);
		$pl->setFood(20);
		$pl->setMaxHealth(20);
		$pl->setScale(1);
		$pl->setImmobile(false);
		$pl->removeAllEffects();
		$pl->getInventory()->clearAll();
		$pl->getArmorInventory()->clearAll();
		$pl->addTitle("§6§lWelcome to", "§r§g§lServer Name");
		$this->getArticulos()->give($pl);
		$this->getServer()->broadcastMessage(TE::BOLD."§7[§a+§7]§r§b ".$pl->getName(). "§f Joined The Server");
	}
	
	public function onQuit(PlayerQuitEvent $ev){
		$pl = $ev->getPlayer();
		$lvl = $pl->getLevel();
		$lvl->addSound(new FizzSound(new Vector3($pl->getX(), $pl->getY(), $pl->getZ())));
		$ev->setQuitMessage("");
		$this->getServer()->broadcastMessage(TE::BOLD."§7[§c-§7]§r§b ".$pl->getName(). "§f Left The Server");
	}
	
	public function onRespawn(PlayerRespawnEvent $ev){
		$pl = $ev->getPlayer();
		$pl->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
		$pl->setGamemode(0);
		$pl->setHealth(20);
		$pl->setFood(20);
		$pl->setMaxHealth(20);
		$pl->setScale(1);
		$pl->setImmobile(false);
		$pl->removeAllEffects();
		$pl->getInventory()->clearAll();
		$pl->getArmorInventory()->clearAll();
		$this->getArticulos()->give($pl);
	}
	
	public function onInteract(PlayerInteractEvent $ev){
		$pl = $ev->getPlayer();
		$item = $pl->getInventory()->getItemInHand();
		if($item->getName() == TE::BOLD."§bInformation§r"."\n"."§7[§eClick view§7]"){
			$this->getArticulos()->Info($pl);
		}else if($item->getName() == TE::BOLD."§aServers§r"."\n"."§7[§eClick view§7]"){
			$this->getArticulos()->MiniGM($pl);
		}else if($item->getName() == TE::BOLD."§5Cosmetics§r"."\n"."§7[§eClick view§7]"){
			if($pl->hasPermission("core.cosmeticos")){
				$this->getArticulos()->Cms($pl);
			} else {
				$pl->sendMessage("§7[§c§l!§r§7]§e You dont have permissions to use.");
			}
		}else if($item->getName() === TE::BOLD."§cReport§r"."\n"."§7[§eClick view§7]"){
			$this->getServer()->dispatchCommand($pl, "report");
		}else if($item->getName() === TE::BOLD."§6Hub§r"."\n"."§7[§eClick view§7]"){
			$this->getServer()->dispatchCommand($pl, "hub");
		}
	}
	
	public function onExhaust(PlayerExhaustEvent $ev){
		$pl = $ev->getPlayer();
		if($pl->getLevel()->getFolderName() == $this->getServer()->getDefaultLevel()->getFolderName()){
			$ev->setCancelled();
		}
	}
	
	public function onDrop(PlayerDropItemEvent $ev){
		$pl = $ev->getPlayer();
		if($pl->getLevel()->getFolderName() == $this->getServer()->getDefaultLevel()->getFolderName()){
			$ev->setCancelled();
		}
	}
	
	public function onMove(PlayerMoveEvent $ev){
		$pl = $ev->getPlayer();
		if($pl->getLevel()->getFolderName() === $this->getServer()->getDefaultLevel()->getFolderName()){
			$pl->getArmorInventory()->clearAll();
			$pl->removeAllEffects();
		}
	}
	
	public function onChat(PlayerChatEvent $ev){
		$pl = $ev->getPlayer();
		$msg = $ev->getMessage();
		$name = $pl->getName();
		$final = "";
		$len = mb_strlen($msg) - 1;
		$colores = ["§d", "§a", "§c", "§b", "§e", "§9", "§5", "§7", "§2"];
		$i = 0;
		$type = 0;
		while($i <= $len){
			$final .= $colores[$type] . $msg[$i];
			$i++;
			$type++;
			if($type == count($colores)){
				$type = 0;
			}
		}
		
		if(in_array($name, $this->cc)){
			$ev->setMessage(str_replace($msg, "$final", $msg));
		}
	}
	
	public function onChange(EntityLevelChangeEvent $ev){
		$pl = $ev->getEntity();
		if($pl instanceof Player){
			$pl->setHealth(20);
			$pl->setFood(20);
			if(in_array($pl->getName(), $this->red)){
				unset($this->red[array_search($pl->getName(), $this->red)]);
				}elseif (in_array($pl->getName(), $this->green)){
			    unset($this->green[array_search($pl->getName(), $this->green)]);
			    }elseif (in_array($pl->getName(), $this->blue)){
				unset($this->blue[array_search($pl->getName(), $this->blue)]);
			}
		}
	}
}
?>