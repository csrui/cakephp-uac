<?php
/* UacProfile Fixture generated on: 2010-09-13 00:09:28 : 1284335068 */
class UacProfileFixture extends CakeTestFixture {
	var $name = 'UacProfile';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'primary'),
		'uac_user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'unique'),
		'screen_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45),
		'avatar_filename' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 75),
		'about' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'uac_user_id_UNIQUE' => array('column' => 'uac_user_id', 'unique' => 1), 'fk_profiles_uac_users1' => array('column' => 'uac_user_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'uac_user_id' => 1,
			'screen_name' => 'Lorem ipsum dolor sit amet',
			'avatar_filename' => 'Lorem ipsum dolor sit amet',
			'about' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
		),
	);
}
?>