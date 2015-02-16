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
	 * @var boolean[] - $handled[id] == true if 'id' has already been handled
	 */ 
	private $handled = [];
	
	/**
	 * @var boolean[] -$handled[id] == true if 'id' is beeing handled
	 */ 
	private $pending = [];
	
	/**
	 * @var Action - the currently handled action
	 */ 
	private $currentAction;
	
	
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
		$this->currentAction = $action;
		
		foreach ($this->callables as $id => $callable) {
			if (!empty($this->pending[$id])) {
				continue;
			}
			$this->call($id);
		}
		
		$this->dispatching = false;
		$this->currentAction = null;
		$this->pending = [];
		$this->handled = [];
	}
	
	/**
	 * Waits for the callbacks specified to be invoked before continuing execution
	 * of the current callback.
	 * 
	 * @param array $ids - the ids to wait for
	 * @throws Exception
	 */ 
	public function waitFor (array $ids) {
		
		if (!$this->dispatching) {
			throw new Exception('Not dispatching');
		}
		
		foreach ($ids as $id) {
			$this->waitForOne($id);
		}
	}
	
	/**
	 * @param string $id
	 */ 
	private function waitForOne ($id) {
		if (!empty($this->pending[$id])) {
			throw new Exception('Circular dependency detected while'
				. 'waiting for ' . $id);
		}
		
		if (empty($this->callables[$id])) {
			throw new Exception($id . ' does not match any registered'
				. 'callbacks');
		}
		
		$this->call($id);
	}

	private function call ($id) {
		$this->pending[$id] = true;
			
		call_user_func_array($this->callables[$id], [$this->currentAction]);
		
		$this->handled[$id] = true;
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