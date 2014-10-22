<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */ 


namespace Plux;

/**
 * An Action is what flows through the bus via the Dispatcher
 */ 
class Action {
	
	/**
	 * The Action type, better if actually unique in your application
	 * @var string
	 */
	private $type;
	
	/** 
	 * The Action data, if any
	 * @var array
	 */
	private $data;
	
	/**
	 * @param string $type
	 * @param array $data
	 */
	public function __construct ($type, array $data = []) {
		$this->type = $type;
		$this->data = $data;
	}
	
	/**
	 * @return string $type
	 */
	public function getType () {
		return $this->type;
	}
	
	/**
	 * @return array $data
	 */
	public function getData () {
		return $this->data;
	}
}