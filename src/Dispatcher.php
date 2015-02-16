<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */ 


namespace Plux;

/**
 * A Dispatcher is responsible for transmitting Actions to Stores via a bus
 * Every registered Store will receive all the dispatched Actions
 */ 
class Dispatcher {
	
	/**
	 * @var array - the registered callables
	 */
	private $callables = [];
	
	/**
	 * @var boolean - true when dispatching
	 */ 
	private $dispatching = false;
	
	/**
	 * Registers a callable to the dispatcher. The callable will be called
	 * whenever something goes into the bus
	 * @param callable $callable - the callable to register to the dispatcher
	 * @return string - the registration id
	 */ 
	public function register (callable $callable) {
		$id = uniqid();
		$this->callables[$id] = $callable;
		return $id;
	}
	
	/**
	 * Unregisters the given callable
	 * @param string $id - the registration id of the callable to unregister
	 */ 
	public function unregister ($id) {
		unset($this->callables[$id]);
	}

	/**
	 * Dispatches the payload to the registered callables
	 * @param Action $action
	 */ 
	public function dispatch (Action $action) {
		$this->dispatching = true;
		$action->setDispatcher($this);
		foreach ($this->callables as $callable) {
			call_user_func_array($callable, [$action]);
		}
		$this->dispatching = false;
	}
	
	/**
	 * @return array - the list of registered callables
	 */ 
	public function getRegisteredCallables () {
		return $this->callables;
	}
	
	public function isDispatching () {
		return $this->dispatching;
	}
}