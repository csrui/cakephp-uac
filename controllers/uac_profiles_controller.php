<?php
class UacProfilesController extends UacAppController {

	var $name = 'UacProfiles';
	
	function beforeFilter() {
		
		parent::beforeFilter();
		
		$this->set('title_for_layout', __('Profile', true));
		
	}
	
	/**
	 * Change users profile
	 *
	 * @return void
	 * @author Rui Cruz
	 */
	function edit() {
		
		$this->set('genders', Configure::read('User.profile.genders'));
		
		if (!empty($this->data)) {
			
			if ($this->Account->updateProfile($this->data)) {
			
				$this->Session->setFlash(__('Your profile was saved', true));
				$this->redirect(Configure::read('User.edit.redirect'));
				
			} else {
				
				$this->Session->setFlash(__('There is an error on your profile', true));
				
			}
			
		} else {
			
			$this->data = $this->Account->user();
			$this->data = Set::merge($this->data, $this->UacProfile->UacProfileMeta->findByUacProfileId($this->Auth->user('id')));

		}
		
	}
	
	function view($id = null) {
		
		$user_id = empty($id) ? $this->Auth->user('id') : $id;
		
		$this->UacProfile->Contain('UacProfileMeta');
		$this->set('profile', $this->UacProfile->read(null, $user_id));
		
	}

}
?>