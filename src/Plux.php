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
	
	/** var Store[] */
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
	 * @return Store[]
	 */ 
	public static function getStores () {
		return self::$stores;
	}
	
	/**
	 * @param string $name
	 * @param Store $store
	 */ 
	public static function addStore ($name, Store $store) {
		$store->register(self::getDispatcher());
		self::$stores[$name] = $store;
	}
	
	/**
	 * Retrieves the wanted Store, or null
	 * @param string $name
	 * @return Store
	 */ 
	public static function getStore ($name) {
		if (isset(self::$stores[$name])) {
			return self::$stores[$name];
		} else {
			return null;
		}
	}
}