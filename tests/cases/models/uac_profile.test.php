<?php
/* UacProfile Test cases generated on: 2010-09-13 00:09:28 : 1284335068*/
App::import('Model', 'uac.UacProfile');

class UacProfileTestCase extends CakeTestCase {
	function startTest() {
		$this->UacProfile =& ClassRegistry::init('UacProfile');
	}

	function endTest() {
		unset($this->UacProfile);
		ClassRegistry::flush();
	}

}
?>