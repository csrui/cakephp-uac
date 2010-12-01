<?php
/* UacProfiles Test cases generated on: 2010-09-13 00:09:53 : 1284335213*/
App::import('Controller', 'uac.UacProfiles');

class TestUacProfilesController extends UacProfilesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class UacProfilesControllerTestCase extends CakeTestCase {
	function startTest() {
		$this->UacProfiles =& new TestUacProfilesController();
		$this->UacProfiles->constructClasses();
	}

	function endTest() {
		unset($this->UacProfiles);
		ClassRegistry::flush();
	}

}
?>