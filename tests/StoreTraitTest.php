<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */ 


namespace Plux\Test;


class StoreTraitTest extends \PHPUnit_Framework_TestCase {

	private $dispatcher;

	public function setup () {
		$this->dispatcher = new \Plux\Dispatcher();
	}
	
	public function testRegistration () {
		$store = new TestableStore ();
		$store->register($this->dispatcher);
		
		$registered_callables = $this->dispatcher->getRegisteredCallables();
		$this->assertTrue(isset($registered_callables[$store->getRegistrationId()]));
		
		$this->dispatcher->unregister($store->getRegistrationId());
	}
	
	public function testHandle () {
		$store = new TestableStore ();
		$store->register($this->dispatcher);
		
		$action = new \Plux\Action ('foobar');
		
		$this->dispatcher->dispatch ($action);
		
		$this->assertEquals(1, $store->call_count);
		$this->assertEquals($action, $store->last_action);
	}
	
	public function testEvents () {
		$store = new TestableStore ();
		$store->register($this->dispatcher);
		$actual_message = null;
		
		$store->on('handled', function ($message) use (&$actual_message) {
			$actual_message = $message;
		});
		
		$action = new \Plux\Action ('foobar');
		
		$this->dispatcher->dispatch ($action);
		
		$this->assertEquals ($action->getType(), $actual_message);
	}
	
}

class TestableStore implements \Plux\Store {
	
	use \Plux\StoreTrait;
	
	public $call_count = 0;
	public $last_action = null;
	
	public function handle (\Plux\Action $action) {
		$this->last_action = $action;
		$this->call_count++;
		$this->emit ('handled', [$action->getType()]);
	}
}