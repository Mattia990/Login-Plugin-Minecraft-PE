<?php

namespace mancio;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\level\Location;
use pocketmine\Player;

class Main extends PluginBase implements Listener {
	
	public static $login=true;
	public static $tentativi=0;
	public static $exLoc;
	public static $name="";
	
	public function onEnable() {
		$this->getLogger()->info("Plugin abilitato!");
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		
		if($this->getConfig()->get("Password") == null) {
			if(!file_exists($this->getDataFolder())) {
		            @mkdir($this->getDataFolder());
		            $this->saveDefaultConfig();
			}
		}
	}
	
	public function onPlayerJoin(PlayerJoinEvent $event) {
		$player=$event->getPlayer();
		
		$player->sendMessage(TextFormat::GREEN . "Benvenuto " . $player->getName() . " nel mio server.\n" . TextFormat::DARK_AQUA . "Per favore utilizza /login <password> per loggare nel server");
		Main::$exLoc=$player->getLocation();
		Main::$login=false;
		Main::$name=$player->getName();
	}
	
	public function onPlayerMove(PlayerMoveEvent $event) {
		$player=$event->getPlayer();
		
		if(strcasecmp($player->getName(), Main::$name) == 0) {
			if(!Main::$login) {
				$loc=new Location(142,71,914);
				$player->teleport($loc);
			}
		}
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		$player=$this->getServer()->getPlayer($sender->getName());
		
		if(strcasecmp($cmd->getName(), "login") == 0 && $sender instanceof Player) {
			if(!Main::$login) {
				if($args[0] != $this->getConfig()->get("Password")) {
					Main::$tentativi++;
					
					if(Main::$tentativi== 3) {
						$player->kick(TextFormat::RED . "Hai sbagliato la password per 3 volte consecutive");
					}
					$player->sendMessage(TextFormat::YELLOW . "Hai sbagliato la passowrd!, Riprova");
					Main::$login=false;
					
				} else {
					Main::$login=true;
					$player->sendMessage(TextFormat::GRAY . "Password corretta, ora puoi giocare nel server");
					$player->teleport(Main::$exLoc);
				}
			} else {
				$player->sendMessage(TextFormat::RED . "Login gia' eseguito!");
			}
			return true;
		}
		
		if(strcasecmp($cmd->getName(), "cambiapass") == 0 && $sender instanceof Player) {
			if($player->isOp()) {
				$this->getConfig()->set("Password", $args[0]);
				$this->saveConfig();
				$player->sendMessage(TextFormat::GREEN . "La password e' stata cambiata con successo!");
			} else {
				$player->sendMessage(TextFormat::RED . "Non puoi utilizzare questo comando, non sei OP!");
			}
			return true;
		}
		
		if(strcasecmp($cmd->getName(), "mostrapass") == 0 && $sender instanceof Player) {
			if($player->isOp()) {
				$password=$this->getConfig()->get("Password");
				$player->sendMessage(TextFormat::BOLD . "La password attuale e' " . $password);
			} else {
				$player->sendMessage(TextFormat::RED . "Non puoi utilizzare questo comando, non sei OP!");
			}
			return true;
		}
		
		if(strcasecmp($cmd->getName(), "resetpass") == 0 && $sender instanceof Player) {
			if($player->isOp()) {
				$this->getConfig()->set("Password", "gatto986");
				$this->saveConfig();
				$player->sendMessage(TextFormat::GREEN . "La password e' stata ripristinata con successo!");
			} else {
				$player->sendMessage(TextFormat::RED . "Non puoi utilizzare questo comando, non sei OP!");
			}
			return true;
		}
		return false;
	}
	
	public function onDisable() {
		$this->getLogger()->info("Plugin disabilitato!");
		$this->saveResource("config.yml");
	}
}<?php

namespace mancio;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\level\Location;
use pocketmine\Player;

class Main extends PluginBase implements Listener {
	
	public static $login=true;
	public static $tentativi=0;
	public static $exLoc;
	public static $name="";
	
	public function onEnable() {
		$this->getLogger()->info("Plugin abilitato!");
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		
		if($this->getConfig()->get("Password") == null) {
			if(!file_exists($this->getDataFolder())) {
		            @mkdir($this->getDataFolder());
		            $this->saveDefaultConfig();
			}
		}
	}
	
	public function onPlayerJoin(PlayerJoinEvent $event) {
		$player=$event->getPlayer();
		
		$player->sendMessage(TextFormat::GREEN . "Benvenuto " . $player->getName() . " nel mio server.\n" . TextFormat::DARK_AQUA . "Per favore utilizza /login <password> per loggare nel server");
		Main::$exLoc=$player->getLocation();
		Main::$login=false;
		Main::$name=$player->getName();
	}
	
	public function onPlayerMove(PlayerMoveEvent $event) {
		$player=$event->getPlayer();
		
		if(strcasecmp($player->getName(), Main::$name) == 0) {
			if(!Main::$login) {
				$loc=new Location(142,71,914);
				$player->teleport($loc);
			}
		}
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		$player=$this->getServer()->getPlayer($sender->getName());
		
		if(strcasecmp($cmd->getName(), "login") == 0 && $sender instanceof Player) {
			if(!Main::$login) {
				if($args[0] != $this->getConfig()->get("Password")) {
					Main::$tentativi++;
					
					if(Main::$tentativi== 3) {
						$player->kick(TextFormat::RED . "Hai sbagliato la password per 3 volte consecutive");
					}
					$player->sendMessage(TextFormat::YELLOW . "Hai sbagliato la passowrd!, Riprova");
					Main::$login=false;
					
				} else {
					Main::$login=true;
					$player->sendMessage(TextFormat::GRAY . "Password corretta, ora puoi giocare nel server");
					$player->teleport(Main::$exLoc);
				}
			} else {
				$player->sendMessage(TextFormat::RED . "Login gia' eseguito!");
			}
			return true;
		}
		
		if(strcasecmp($cmd->getName(), "cambiapass") == 0 && $sender instanceof Player) {
			if($player->isOp()) {
				$this->getConfig()->set("Password", $args[0]);
				$this->saveConfig();
				$player->sendMessage(TextFormat::GREEN . "La password e' stata cambiata con successo!");
			} else {
				$player->sendMessage(TextFormat::RED . "Non puoi utilizzare questo comando, non sei OP!");
			}
			return true;
		}
		
		if(strcasecmp($cmd->getName(), "mostrapass") == 0 && $sender instanceof Player) {
			if($player->isOp()) {
				$password=$this->getConfig()->get("Password");
				$player->sendMessage(TextFormat::BOLD . "La password attuale e' " . $password);
			} else {
				$player->sendMessage(TextFormat::RED . "Non puoi utilizzare questo comando, non sei OP!");
			}
			return true;
		}
		
		if(strcasecmp($cmd->getName(), "resetpass") == 0 && $sender instanceof Player) {
			if($player->isOp()) {
				$this->getConfig()->set("Password", "gatto986");
				$this->saveConfig();
				$player->sendMessage(TextFormat::GREEN . "La password e' stata ripristinata con successo!");
			} else {
				$player->sendMessage(TextFormat::RED . "Non puoi utilizzare questo comando, non sei OP!");
			}
			return true;
		}
		return false;
	}
	
	public function onDisable() {
		$this->getLogger()->info("Plugin disabilitato!");
		$this->saveResource("config.yml");
	}
}
