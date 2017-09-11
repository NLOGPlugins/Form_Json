<?php

namespace nlog;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class TestForm extends PluginBase implements Listener {
	
  public function onEnable(): void {
	$this->getServer()->getPluginManager()->registerEvents($this, $this);
	
	@mkdir($this->getDataFolder());
	$this->saveResource("form.json");
  }
  
  public function onRecievePacket (DataPacketReceiveEvent $ev): void {
  	$pk = $ev->getPacket();
  	if ($pk instanceof ModalFormResponsePacket) {
  		if ($pk->formId === 7654) {
  			$data = json_decode($pk->formData, true);
			$ev->getPlayer()->sendMessage("You select no. {$data} button");
  		}
  	}
  }
  
  public function onCommand(CommandSender $sender,Command $command,string $label,array $args): bool {
  	$dir = file_get_contents($this->getDataFolder() . "form.json");
  	$pk = new ModalFormRequestPacket();
  	$pk->formId = 7654;
  	$pk->formData = $dir;
  	
  	$sender->dataPacket($pk);
  	return true;
  }
 

}

?>