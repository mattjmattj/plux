<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */ 


namespace Plux;

/**
 * A Store performs two things:
 *  - handle Actions dispatched by the Dispatcher
 *  - emit events so that Components can update themselves
 */ 
trait StoreTrait {
	
	use \Evenement\EventEmitterTrait;
	
	/**
	 * The registration id given by the dispatcher
	 * @var string
	 */ 
	private $registration_id;
	
	/**
	 * @param Dispatcher $dispatcher - the Dispatcher we will be registered to
	 */ 
	public function register (Dispatcher $dispatcher) {
		$this->registration_id = $dispatcher->register (
			[$this, 'handle']
		);
	}
	
	/**
	 * @return string - the registration id of store to the Dispatcher
	 */ 
	public function getRegistrationId () {
		return $this->registration_id;
	}
}