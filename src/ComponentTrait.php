<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */ 


namespace Plux;

/**
 * A Component listens to Store events and update their internal data, in order
 * to perform a "render" action.
 */ 
trait ComponentTrait {
	
	
	abstract public function render ();
	
}