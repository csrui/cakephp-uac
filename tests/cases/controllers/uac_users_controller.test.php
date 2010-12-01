<?php
/* UacUsers Test cases generated on: 2010-09-13 00:09:41 : 1284335201*/
App::import('Controller', 'uac.UacUsers');

class TestUacUsersController extends UacUsersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class UacUsersControllerTestCase extends CakeTestCase {
	function startTest() {
		$this->UacUsers =& new TestUacUsersController();
		$this->UacUsers->constructClasses();
	}

	function endTest() {
		unset($this->UacUsers);
		ClassRegistry::flush();
	}

}
?>