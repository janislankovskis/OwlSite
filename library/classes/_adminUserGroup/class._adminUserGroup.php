<?php 

class _adminUserGroup extends ObjectModule //TODO:  extends 
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