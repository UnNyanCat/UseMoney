<?php

namespace nyancat\usemoney;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;

use pocketmine\Server;

use pocketmine\utils\TextFormat;

use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener {

	public $playerList = [];

	public function onEnable()
	{
		$this->getLogger()->notice("Actif !");
	}

	public function onDisable()
	{
		$this->getLogger()->notice("Non actif !");
	}

	public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool
	{
		switch($command->getName()){
			case "usemoney":
				if($player instanceof Player){
					$this->openServerForm($player);
				}
			break;
		}
		return true;
	}

	public function openServerForm($player) {
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
					$myMoney = EconomyAPI::getInstance()->myMoney($player);

					$player->sendMessage("Vous avez : " . $myMoney . " en monnaie.");
				break;

				case 1:
					EconomyAPI::getInstance()->addMoney($player, 25);

					$myMoney = EconomyAPI::getInstance()->myMoney($player);

					$player->sendMessage("Vous avez : " . $myMoney . " en monnaie.");
				break;

				case 2:
					EconomyAPI::getInstance()->reduceMoney($player, 25);

					$myMoney = EconomyAPI::getInstance()->myMoney($player);

					$player->sendMessage("Vous avez : " . $myMoney . " en monnaie.");
				break;
			}
		});
		$form->setTitle("Money UI");
		$form->setContent("Que veux tu faire ?");
		$form->addButton("Voir mon argent");
		$form->addButton("Ajouter 25$ à mon compte");
		$form->addButton("Enlever 25$ à mon compte");
		$form->sendToPlayer($player);
		return $form;
	}
}