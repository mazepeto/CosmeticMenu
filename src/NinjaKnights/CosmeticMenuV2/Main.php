<?php

namespace NinjaKnights\CosmeticMenuV2;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\level\Location;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\inventory\PlayerInventory;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\{SlotChangeAction,DropItemAction};

use pocketmine\utils\Config;

use jojoe77777\FormAPI\FormAPI;

use NinjaKnights\CosmeticMenuV2\forms\MainForm;
use NinjaKnights\CosmeticMenuV2\forms\GadgetForm;
use NinjaKnights\CosmeticMenuV2\forms\ParticleForm;
use NinjaKnights\CosmeticMenuV2\forms\MorphForm;
use NinjaKnights\CosmeticMenuV2\forms\TrailForm;
use NinjaKnights\CosmeticMenuV2\forms\HatForm;
use NinjaKnights\CosmeticMenuV2\EventListener;

use NinjaKnights\CosmeticMenuV2\cosmetics\Gadgets\GadgetsEvents;

use NinjaKnights\CosmeticMenuV2\cosmetics\Particles\BlizzardAura;
use NinjaKnights\CosmeticMenuV2\cosmetics\Particles\BloodHelix;
use NinjaKnights\CosmeticMenuV2\cosmetics\Particles\BulletHelix;
use NinjaKnights\CosmeticMenuV2\cosmetics\Particles\ConduitHalo;
use NinjaKnights\CosmeticMenuV2\cosmetics\Particles\CupidsLove;
use NinjaKnights\CosmeticMenuV2\cosmetics\Particles\EmeraldTwirl;
use NinjaKnights\CosmeticMenuV2\cosmetics\Particles\FlameRings;
use NinjaKnights\CosmeticMenuV2\cosmetics\Particles\RainCloud;
use NinjaKnights\CosmeticMenuV2\cosmetics\Particles\WitchCurse;

class Main extends PluginBase implements Listener {

	private $formapi;

	public $world;
    /**
     * @var Forms
     */
	private $forms;
	private $gadgets;
	private $particles;
	private $morphs;
	private $trails;
	private $hats;

	public $particle1 = array("Rain Cloud");
	public $particle2 = array("Flame Rings");
	public $particle3 = array("Blizzard Aura");
    public $particle4 = array("Cupid's Love");
    public $particle5 = array("Bullet Helix");
    public $particle6 = array("Conduit Halo");
    public $particle7 = array("Witch Curse");
    public $particle8 = array("Blood Helix");
    public $particle9 = array("Emerald Twril");
    public $particle10 = array("Test");

    public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new GadgetsEvents($this), $this);
		$this->getScheduler()->scheduleRepeatingTask(new BlizzardAura($this), 3);
		$this->getScheduler()->scheduleRepeatingTask(new BloodHelix($this), 3);
		$this->getScheduler()->scheduleRepeatingTask(new BulletHelix($this), 3);
		$this->getScheduler()->scheduleRepeatingTask(new ConduitHalo($this), 3);
		$this->getScheduler()->scheduleRepeatingTask(new CupidsLove($this), 3);
		$this->getScheduler()->scheduleRepeatingTask(new EmeraldTwirl($this), 3);
		$this->getScheduler()->scheduleRepeatingTask(new FlameRings($this), 3);
		$this->getScheduler()->scheduleRepeatingTask(new RainCloud($this), 3);
		$this->getScheduler()->scheduleRepeatingTask(new WitchCurse($this), 3);
		
		$this->loadPlugins();
		$this->loadFormClass();
		
		$configPath = $this->getDataFolder()."config.yml";
        $this->saveDefaultConfig();
		$this->config = new Config($configPath, Config::YAML);
		$this->config->getAll();
        $version = $this->config->get("Version");
        $this->pluginVersion = $this->getDescription()->getVersion();
        if($version < "2.0"){
            $this->getLogger()->warning("You have updated CosmeticMenu to v".$this->pluginVersion." but have a config from v$version! Please delete your old config for new features to be enabled and to prevent unwanted errors! The Plugin will remain disabled.");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }

		if($this->config->getNested("Cosmetic.Enabled")){
			$this->cosmeticSupport = true;
			$this->cosmeticName = (str_replace("&", "§", $this->config->getNested("Cosmetic.Name")));
            $this->cosmeticDes = [str_replace("&", "§", $this->config->getNested("Cosmetic.Des"))];
			$this->cosmeticItemType = $this->config->getNested("Cosmetic.Item");
            $this->cosmeticForceSlot = $this->config->getNested("Cosmetic.Force-Slot");
        } else{
            $this->cosmeticSupport = false;
            $this->getLogger()->info("The Cosmetic Item is disabled in the config.");
        }
	}

	private function loadFormClass() : void {
		$this->forms = new MainForm($this);
		$this->gadgets = new GadgetForm($this);
		$this->particles = new ParticleForm($this);
		$this->morphs = new MorphForm($this);
		$this->trails = new TrailForm($this);
		$this->hats = new HatForm($this);
    }
	
	private function loadPlugins() : void {
        $this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
	}

	function getMain() : Main {
        return $this;
	}

    /**
     * @return FormAPI
     */
    function getForm() : FormAPI {
        return $this->formapi;
	}
	
	function getForms() : MainForm {
        return $this->forms;
	}
	
	function getGadgets() : GadgetForm {
        return $this->gadgets;
	}
	function getParticles() : ParticleForm {
        return $this->particles;
	}
	function getMorphs() : MorphForm {
        return $this->morphs;
	}
	function getTrails() : TrailForm {
        return $this->trails;
	}
	function getHats() : HatForm {
        return $this->hats;
    }

}