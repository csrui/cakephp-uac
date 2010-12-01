<?php
class UacImage extends UacAppModel {
	
	var $name = 'UacImage';
	
	var $displayField = 'filename';

	var $belongsTo = array(
		'UacProfile' => array(
			'className' => 'UacProfile',
			'foreignKey' => 'uac_profile_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $actsAs = array(
		'Containable',
        'MeioUpload.MeioUpload' => array(
        	'filename' => array(
	            'dir' => 'profiles',
	            'createDirectory' => true,
				'uploadName' => null,
				'allowedMime' => array('image/jpeg', 'image/pjpeg', 'image/png', 'image/gif', 'image/bmp', 'image/x-icon', 'image/vnd.microsoft.icon'),
				'allowedExt' => array('.jpg', '.jpeg', '.png', '.gif', '.bmp', '.ico'),
				'maxSize' => 2097152, // 2MB
				'thumbnailQuality' => 100,
				'useImageMagick' => false,
				'thumbnails' => true,
				'zoomCrop' => true,
	            'thumbsizes' => array(
	                'big' => array('width'=> 750, 'height'=> 750),
					'medium' => array('width'=> 450, 'height'=> 450),
					'small' => array('width'=> 250, 'height'=> 250),
					'tiny' => array('width'=> 34, 'height'=> 34),
	            ),
	            'default' => 'default.jpg',
	        )				 
		)
    );	
	
    
    function setAvatar(&$data) {
    	
		# GET SAVED FILENAME
		$conditions = array('UacImage.id' => $this->id);
		
		$avatar_filename = $this->field('filename', $conditions);

    	# SET THE AVATAR ON THE USERS PROFILE
    	$this->UacProfile->id = $data['UacImage']['uac_profile_id'];
		if ($this->UacProfile->saveField('avatar_filename', $avatar_filename)) {
			return true;
		}
		
		return false;
					
    }
	
}
?>