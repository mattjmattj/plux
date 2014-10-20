<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */ 


namespace Plux;

class Action {
	
	/**
	 * The Action id, better if actually unique in your application
	 * @var string
	 */
	private $id;
	
	/** 
	 * The Action data, if any
	 * @var array
	 */
	private $data;
	
	/**
	 * @param string $id
	 * @param array $data
	 */
	public function __construct ($id, array $data = []) {
		$this->id = $id;
		$this->data = $data;
	}
	
	/**
	 * @return string $id
	 */
	public function getId () {
		return $this->id;
	}
	
	/**
	 * @return array $data
	 */
	public function getData () {
		return $this->data;
	}
}