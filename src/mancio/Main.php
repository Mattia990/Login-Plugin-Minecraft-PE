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
	private $login = true;
    private $tentativi = 0;
    private $exLoc;
    private $name = "";
    private $first;
	
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
		$this->exLoc=$player->getLocation();
		$this->login=false;
		$this->name=$player->getName();
		$this->first=$this->getConfig()->get("First Start");
		
		if($this->first) {
			$player->sendMessage(TextFormat::RED . "Se sei OP usa il comando /config <password> <x> <y> <z> per configurare il plugin!");
		}
	}
	
	public function onPlayerMove(PlayerMoveEvent $event) {
		$player=$event->getPlayer();
		/*$parsed = array(3);
		$str = array();*/

        $safeLoc = (bool) $this->getConfig()->get("SafeSpawn");
        if(!$this->login){
            if($safeLoc){
                $player->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
            }else{
                $event->setCancelled(true);
            }
        }
		/*if(strcasecmp($player->getName(), $this->name) == 0) {
			if(!$this->login) {
				$str[0]=(String) $this->getConfig()->get("X");
				$str[1]=(String) $this->getConfig()->get("Y");
				$str[2]=(String) $this->getConfig()->get("Z");
				
				for($i=0;$i<3;$i++) {
					$parsed[$i]=intval($str[$i]);
				}
				
				$loc=new Location($parsed[0], $parsed[1], $parsed[2]);
				$player->teleport($loc);
			}
		}*/
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		$player=$this->getServer()->getPlayer($sender->getName());
		$cmd = strtolower($command->getName());

		if(!$this->first && $sender instanceof Player){
            switch($cmd) {
                case "login":
                    if (!$this->login) {
                        if ($args[0] != $this->getConfig()->get("Password")) {
                            $this->tentativi++;

                            if ($this->tentativi == 3) {
                                $player->kick(TextFormat::RED . "Hai sbagliato la password per 3 volte consecutive");
                            }
                            $player->sendMessage(TextFormat::YELLOW . "Hai sbagliato la passowrd!, Riprova");
                            $this->login = false;

                        } else {
                            $this->login = true;
                            $player->sendMessage(TextFormat::GRAY . "Password corretta, ora puoi giocare nel server");
                            $player->teleport($this->exLoc);
                        }
                    } else {
                        $player->sendMessage(TextFormat::RED . "Login gia' eseguito!");
                    }
                    return true;

                case "cambiapass":
                    if ($player->isOp()) {
                        $this->getConfig()->set("Password", $args[0]);
                        $this->saveConfig();
                        $player->sendMessage(TextFormat::GREEN . "La password e' stata cambiata con successo!");
                    } else {
                        $player->sendMessage(TextFormat::RED . "Non puoi utilizzare questo comando, non sei OP!");
                    }
                    return true;

                case "mostrapass":
                    if ($player->isOp()) {
                        $password = $this->getConfig()->get("Password");
                        $player->sendMessage(TextFormat::BOLD . "La password attuale e' " . $password);
                    } else {
                        $player->sendMessage(TextFormat::RED . "Non puoi utilizzare questo comando, non sei OP!");
                    }
                    return true;

                case "config":
                    $this->getConfig()->set("Password", $args[0]);
                    $this->getConfig()->set("First Start", false);
                    $this->getConfig()->set("SafeSpawn", true);

                    $this->saveConfig();
                    $player->sendMessage("Plugin configurato correttamente!");
                    return true;
                default:
                    $player->sendMessage("Comando non trovato");
                /*if(strcasecmp($cmd->getName(), "resetpass") == 0 && $sender instanceof Player) {
                    if($player->isOp()) {
                        $this->getConfig()->set("Password", $this->getConfig()->get("Password"));
                        $this->saveConfig();
                        $player->sendMessage(TextFormat::GREEN . "La password e' stata ripristinata con successo!");
                    } else {
                        $player->sendMessage(TextFormat::RED . "Non puoi utilizzare questo comando, non sei OP!");
                    }
				    return true;
			    }*/
            }
		}else{
			if($cmd == "config"){
                $this->getConfig()->set("Password", $args[0]);
                $this->getConfig()->set("First Start", false);
                $this->getConfig()->set("SafeSpawn", true);
				
				$this->saveConfig();
				
				$player->sendMessage("Plugin configurato correttamente!");
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
