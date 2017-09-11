<?php

namespace nlog;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class TestModal extends PluginBase implements Listener {

  private $json;
	
  public function onEnable(): void {
	$this->getServer()->getPluginManager()->registerEvents($this, $this);
	
	@mkdir($this->getDataFolder());
	$this->saveResource("modal.json");
  }
  
  public function onRecievePacket (DataPacketReceiveEvent $ev): void {
  	$pk = $ev->getPacket();
  	if ($pk instanceof ModalFormResponsePacket) {
  		if ($pk->formId === 1964) {
  			$data = json_decode($pk->formData, true);
  			if ($data) {
				$data = "true";
			}else{
				$data = "false";
			}
			$ev->getPlayer()->sendMessage("You select {$data} button");
  		}
  	}
  }
  
  public function onCommand(CommandSender $sender,Command $command,string $label,array $args): bool {
  	$dir = file_get_contents($this->getDataFolder() . "modal.json");
  	$pk = new ModalFormRequestPacket();
  	$pk->formId = 1964;
  	$pk->formData = $dir;
  	
  	$sender->dataPacket($pk);
  	return true;
  }
 

}

?>