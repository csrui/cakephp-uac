<?php
/* UacUser Test cases generated on: 2010-09-13 00:09:28 : 1284335068*/
App::import('Model', 'uac.UacUser');

class UacUserTestCase extends CakeTestCase {
	function startTest() {
		$this->UacUser =& ClassRegistry::init('UacUser');
	}

	function endTest() {
		unset($this->UacUser);
		ClassRegistry::flush();
	}

}
?>