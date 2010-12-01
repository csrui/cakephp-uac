<?php
/* Item Test cases generated on: 2010-09-13 00:09:28 : 1284335068*/
App::import('Model', 'uac.Item');

class ItemTestCase extends CakeTestCase {
	function startTest() {
		$this->Item =& ClassRegistry::init('Item');
	}

	function endTest() {
		unset($this->Item);
		ClassRegistry::flush();
	}

}
?>