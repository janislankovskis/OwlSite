<?php 

class _adminUserGroup extends O_Model
{
	public $name;
	
	public $tableName = 'usersGroups';
	
	public $fields = array(
		'name' => array(
			'type' => 'text',
			'required' => true,
		),
	);
	
}


?>