<?php

namespace LobbySystem;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class MainClass extends PluginBase implements Listener{

    public $prefix = "§8[§3DeinServer§8]§7 ";

    public function onEnable(){
        $this->getLogger()->info("wurde geladen!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $playerJoinEvent){
        $player = $playerJoinEvent->getPlayer();
        $playerJoinEvent->setJoinMessage($this->prefix.$player->getName()." hat das Spiel betreten!");
        $this->getItems($player);
    }

    public function onQuit(PlayerQuitEvent $playerQuitEvent){
        $player = $playerQuitEvent->getPlayer();
        $playerQuitEvent->setQuitMessage($this->prefix.$player->getName()." hat das Spiel verlassen!");
    }

    public function getItems(Player $player){
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $compass = Item::get(Item::COMPASS);
        $compass->setCustomName("§7Teleporter");
        $hider = Item::get(Item::BLAZE_ROD);
        $hider->setCustomName("§7Player-Hider");
        $gadgets = Item::get(Item::CHEST);
        $gadgets->setCustomName("§7Gadgets");
        $player->getInventory()->setItem(0, $hider);
        $player->getInventory()->setItem(4, $compass);
        $player->getInventory()->setItem(8, $gadgets);
    }

    public function setCompass(PlayerInteractEvent $playerInteractEvent){
        $player = $playerInteractEvent->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        if ($item->getCustomName() == "§7Teleporter"){
            $player->getInventory()->clearAll();
            $bedwars = Item::get(Item::BED);
            $bedwars->setCustomName("§7BedWars");
            $zurück = Item::get(Item::REDSTONE);
            $zurück->setCustomName("§7Zurück");
            $player->getInventory()->setItem(0, $bedwars);
            $player->getInventory()->setItem(8, $zurück);
        }elseif ($item->getCustomName() == "§7Zurück"){
            $player->getInventory()->clearAll();
            $this->getItems($player);
        }
    }

    public function setHider(PlayerInteractEvent $playerInteractEvent){

    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        if ($command == "team") {
            $sender->sendMessage($this->prefix . "Unser Teammitglieder:");
            $sender->sendMessage("§n§4Owner§r§8:");
            $sender->sendMessage("§7ExoCode");
            return true;
        }
        if ($command == "info"){
            $sender->sendMessage($this->prefix."LobbySystem by Exo §8|§7 v1.0-BETA");
            return true;
        }
    }

    public function onChat(PlayerChatEvent $playerChatEvent){
        $player = $playerChatEvent->getPlayer();
        $playerChatEvent->setCancelled(true);
        $player->sendMessage($this->prefix."Kauf dir ein Rang um schreiben zu können!");
        if ($player->hasPermission("lobby.chat")){
            $playerChatEvent->setCancelled(false);
        }
    }

    public function onDamage(EntityDamageEvent $entityDamageEvent){
        if ($entityDamageEvent->getCause() == EntityDamageEvent::CAUSE_FALL){
            $entityDamageEvent->setCancelled(true);
        }
    }

    public function onDrop(PlayerDropItemEvent $playerDropItemEvent){
        $playerDropItemEvent->setCancelled(true);
    }

    public function onPickUp(InventoryPickupItemEvent $inventoryPickupItemEvent){
        $inventoryPickupItemEvent->setCancelled(true);
    }

    public function onBreak(BlockBreakEvent $blockBreakEvent){
        $blockBreakEvent->setCancelled(true);
    }

    public function onPlace(BlockPlaceEvent $blockPlaceEvent){
        $blockPlaceEvent->setCancelled(true);
    }

    public function onHunger(PlayerExhaustEvent $playerExhaustEvent){
        $playerExhaustEvent->setCancelled(true);
    }
}