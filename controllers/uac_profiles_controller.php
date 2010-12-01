<?php
class UacProfilesController extends UacAppController {

	var $name = 'UacProfiles';
	
	
	function edit() {
		
		if (!empty($this->data)) {
			
			if ($this->UacProfile->save($this->data)) {
				$this->Session->setFlash(__('Your profile was saved', true));
				$this->redirect('/');
			} else {
				$this->Session->setFlash(__('There is an error on your profile', true));
			}
			
		} else {
			
			$this->data = $this->UacProfile->read(null, $this->Auth->user('id'));
			
		}
		
	}
	
	function view($id = null) {
		
		$user_id = empty($id) ? $this->Auth->user('id') : $id;
		
		$this->UacProfile->recursive = -1;
		$this->set('profile', $this->UacProfile->read(null, $user_id));
		
	}

}
?>