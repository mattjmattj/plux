<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */ 


namespace Plux\Test;


class DispatcherTest extends \PHPUnit_Framework_TestCase {

	private $dispatcher;

	public function setup () {
		$this->dispatcher = new \Plux\Dispatcher();
	}
	
	public function testRegisterUnregister () {
		
		$callable1 = function () {};
		$callable2 = function () {};
		$callable3 = function () {};
		
		$c1id = $this->dispatcher->register ($callable1);
		$c2id = $this->dispatcher->register ($callable2);
		$c3id = $this->dispatcher->register ($callable3);
		
		$this->dispatcher->unregister($c2id);
		
		$this->assertEquals([ 
				$c1id => $callable1, 
				$c3id => $callable3
			], $this->dispatcher->getRegisteredCallables());
			
		$this->dispatcher->unregister($c1id);
		
		$this->assertEquals([ 
				$c3id => $callable3
			], $this->dispatcher->getRegisteredCallables());
			
		$this->dispatcher->unregister($c1id);
		
		$this->assertEquals([ 
				$c3id => $callable3
			], $this->dispatcher->getRegisteredCallables());
			
		$this->dispatcher->unregister($c3id);
		
		$this->assertEquals([], $this->dispatcher->getRegisteredCallables());
	}
	
	public function testDispatch () {
		
		$data = ['foo' => 'bar'];
		$actualdata = null;
		
		$c1count = $c2count = 0;
		
		$callable1 = function (\Plux\Action $action) use (&$c1count, &$actualdata) {
			if ($action->getType() == 'foobar') {
				$c1count++;
			}
			$actualdata = $action->getData();
		};
		
		$callable2 = function (\Plux\Action $action) use (&$c2count) {
			if ($action->getType() == 'foobar') {
				$c2count++;
			}
		};
		
		$c1id = $this->dispatcher->register ($callable1);
		$c2id = $this->dispatcher->register ($callable2);
		
		$this->dispatcher->dispatch (new \Plux\Action ('foobar',$data));
		
		$this->assertEquals(1, $c1count);
		$this->assertEquals(1, $c2count);
		$this->assertEquals($data, $actualdata);
		
		$this->dispatcher->unregister($c1id);
		
		$this->dispatcher->dispatch (new \Plux\Action ('foobar',[$data]));
		$this->assertEquals(1, $c1count);
		$this->assertEquals(2, $c2count);
		
		$this->dispatcher->unregister($c2id);
		
		$this->dispatcher->dispatch (new \Plux\Action ('foobar',[$data]));
		$this->assertEquals(1, $c1count);
		$this->assertEquals(2, $c2count);
	}
	
}