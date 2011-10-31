<?php

class CurrentModuleObject extends DefaultAdminModule
{
	
	public $objectName = 'Object';
	
	public $moduleName = 'Pages';	
	
	public $modes = array('edit', 'add', 'delete', 'save', 'ajaxload');
	
	public function __construct()
	{
		//debug('dsdfd');
		
	}


}

?>