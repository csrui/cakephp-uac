<?php
/**
 * undocumented class
 *
 * @package Uac
 * @author Rui Cruz
 */
class UacImagesController extends UacAppController {
	
	
	function beforeFilter() {
		
		$this->set('title_for_layout', __('Profile photos', true));
		
	}	
	
	
	function index() {
		
		$this->UacImage->recursive = -1;
		$conditions = array('UacImage.uac_profile_id' => $this->Account->id());
		$this->set('uac_images', $this->paginate($conditions));
		
	}
	
	function add() {
		
		if (!empty($this->data)) {
			$this->UacImage->create();
			$this->data['UacImage']['uac_profile_id'] = $this->Account->id();
			if ($this->UacImage->save($this->data)) {
				
				if ($this->data['UacImage']['avatar'] == 1) {
					$this->UacImage->setAvatar($this->data);					
				}
				
				$this->Session->setFlash(__('The image has been saved', true));
				$this->redirect(array('controller' => 'uac_profiles', 'action' => 'edit',));

			} else {
				$this->Session->setFlash(__('The image could not be saved. Please, try again.', true));
			}
		}
		
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for image', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->UacImage->delete($id)) {
			$this->Session->setFlash(__('Image deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Image was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}	
	
}