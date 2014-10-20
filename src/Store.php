<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */ 


namespace Plux;

abstract class Store {
	
	/**
	 * The registration id given by the dispatcher
	 * @var string
	 */ 
	private $registration_id;
	
	/**
	 * @param Dispatcher $dispatcher - the Dispatcher we will be registered to
	 */ 
	public function __construct (Dispatcher $dispatcher) {
		$this->registration_id = $dispatcher->register (
			[$this, 'handle']
		);
	}
	
	public function getRegistrationId () {
		return $this->registration_id;
	}
	
	/**
	 * Must handle every given Action. Called by the dispatcher the store is
	 * registered to.
	 * @param Action $action
	 */ 
	public abstract function handle (Action $action);
	
}