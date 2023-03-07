<?php

namespace clean;

use pocketmine\entity\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;

class clean extends PluginBase {

	private $cleanupInterval = 5 * 60 * 20; // 5 minutes in ticks

	public function onEnable() : void {
		$this->getScheduler()->scheduleRepeatingTask(new ItemCleanupTask($this), $this->cleanupInterval);
		$this->getLogger()->info("ItemCleanupPlugin enabled!");
	}

	public function onDisable() : void {
		$this->getLogger()->info("ItemCleanupPlugin disabled!");
	}

	public function cleanupItems() {
		foreach ($this->getServer()->getDefaultLevel()->getEntities() as $entity) {
			if ($entity instanceof Item && $entity->age > 6000) { // 5 minutes in ticks
				$entity->close();
			}
		}
	}
}

class ItemCleanupTask extends Task {

	private $plugin;

	public function __construct(ItemCleanupPlugin $plugin) {
		$this->plugin = $plugin;
	}

	public function onRun(int $currentTick) : void {
		$this->plugin->cleanupItems();
	}
}

