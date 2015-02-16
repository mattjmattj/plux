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
			$this->assertTrue($this->dispatcher->isDispatching());
			$this->assertEquals($this->dispatcher, $action->getDispatcher());
		};
		
		$callable2 = function (\Plux\Action $action) use (&$c2count) {
			if ($action->getType() == 'foobar') {
				$c2count++;
			}
			$this->assertTrue($this->dispatcher->isDispatching());
			$this->assertEquals($this->dispatcher, $action->getDispatcher());
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
	
	
	public function testWaitFor () {
		
		$log = [];
		$ids = [];
		
		$ids[] = $this->dispatcher->register(function ($action) use (&$log, &$ids) {
			$log[] = 1;
			$action->getDispatcher()->waitFor([$ids[1], $ids[2]]);
			$log[] = 4;
		});
		
		$ids[] = $this->dispatcher->register(function () use (&$log) {
			$log[] = 2;
		});
		
		$ids[] = $this->dispatcher->register(function () use (&$log) {
			$log[] = 3;
		});
		
		$ids[] = $this->dispatcher->register(function () use (&$log) {
			$log[] = 5;
		});
		
		$this->dispatcher->dispatch(new \Plux\Action('testWaitFor'));
		
		$this->assertEquals([1,2,3,4,5], $log);
	}
	
	/**
	 * @expectedException \Plux\Exception
	 */ 
	public function testWaitForCircularDependency () {
		
		$ids = [];
		
		$ids[] = $this->dispatcher->register(function ($action) use (&$ids) {
			$action->getDispatcher()->waitFor([$ids[1]]);
		});
		
		$ids[] = $this->dispatcher->register(function ($action) use (&$ids) {
			$action->getDispatcher()->waitFor([$ids[0]]);
		});
		
		$this->dispatcher->dispatch(new \Plux\Action('testWaitForCircularDependency'));
	}
	
	
	/**
	 * @expectedException \Plux\Exception
	 */ 
	public function testUnknownWaitFor () {
		
		$this->dispatcher->register(function ($action) {
			$action->getDispatcher()->waitFor(['not-existing-id']);
		});
		
		
		$this->dispatcher->dispatch(new \Plux\Action('testUnknownWaitFor'));
	}
}