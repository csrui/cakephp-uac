<?php
/* UacUser Fixture generated on: 2010-09-13 00:09:28 : 1284335068 */
class UacUserFixture extends CakeTestFixture {
	var $name = 'UacUser';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'primary'),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 75, 'key' => 'unique'),
		'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45),
		'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'invite_code' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'email_UNIQUE' => array('column' => 'email', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'email' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'last_login' => '2010-09-13 00:44:28',
			'invite_code' => 'Lorem ipsum dolor sit amet'
		),
	);
}
?>