<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */ 


namespace Plux\Test;

use Plux\Plux;

class PluxTest extends \PHPUnit_Framework_TestCase {

	public function testDispatcherCycleLife () {
		Plux::initialize();
		
		$dispatcher = Plux::getDispatcher();
		$this->assertInstanceOf('\Plux\Dispatcher', $dispatcher);
		
		$dispatcher2 = Plux::getDispatcher();
		$this->assertEquals ($dispatcher, $dispatcher2);
		
		
		Plux::initialize();
		$dispatcher3 = Plux::getDispatcher();
		$this->assertTrue ($dispatcher !== $dispatcher3);
	}
	
	public function testStores () {
		Plux::initialize();
		
		Plux::addStore('Store1', new TestStore('store1'));
		Plux::addStore('Store2', new TestStore('store2'));
		
		$this->assertEquals ('store1', Plux::getStore('Store1')->name);
		$this->assertEquals ('store2', Plux::getStore('Store2')->name);
		$this->assertNull ( Plux::getStore('Foo'));
	}
}

class TestStore implements \Plux\Store{
	
	use \Plux\StoreTrait;

	public $name;
	
	public function __construct($name) {
		$this->name = $name;
	}
	
	public function handle (\Plux\Action $action) {}
}