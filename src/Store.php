<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */ 


namespace Plux;

/**
 * An interface for Stores.
 * Consider using StoreTrait to implement "register"
 */ 
interface Store {

	/**
	 * @param Dispatcher $dispatcher - the Dispatcher we will be registered to
	 */ 
	public function register (Dispatcher $dispatcher);
	
	/**
	 * Must handle every given Action. Called by the dispatcher the store is
	 * registered to.
	 * @param Action $action
	 */
	public function handle (Action $action);
}