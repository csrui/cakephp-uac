<?php
/* InventoryItem Test cases generated on: 2010-09-13 00:09:28 : 1284335068*/
App::import('Model', 'uac.InventoryItem');

class InventoryItemTestCase extends CakeTestCase {
	function startTest() {
		$this->InventoryItem =& ClassRegistry::init('InventoryItem');
	}

	function endTest() {
		unset($this->InventoryItem);
		ClassRegistry::flush();
	}

}
?>