<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */ 


namespace Plux;

/**
 * Main, fully static, class of the Plux micro-framework.
 * The Plux class allows accessing to the various pieces of the framework
 */ 
class Plux {
	
	/** @var Dispatcher */
	private static $dispatcher;
	
	/** var StoreTrait[] */
	private static $stores;
	
	public static function initialize () {
		self::$dispatcher = new Dispatcher ();
		self::$stores = [];
	}
	
	/**
	 * @return Dispatcher - THE Dispatcher
	 */ 
	public static function getDispatcher () {
		return self::$dispatcher;
	}
	
	/**
	 * @return StoreTrait[]
	 */ 
	public static function getStores () {
		return self::$stores;
	}
	
	/**
	 * @param string $name
	 * @param StoreTrait $store
	 */ 
	public static function addStore ($name, $store) {
		$store->register(self::getDispatcher());
		self::$stores[$name] = $store;
	}
	
	/**
	 * @param string $name
	 * @return StoreTrait
	 */ 
	public static function getStore ($name) {
		if (isset(self::$stores[$name])) {
			return self::$stores[$name];
		} else {
			return null;
		}
	}
}