<?php

class CurrentModuleObject extends DefaultAdminModule
{
	
		
	public $objectName = 'Object';	
	public $moduleName = 'Pages';
		
	public function __construct($admin)
	{
		$this->initModule($admin);
		$this->_add_css( 'style/forms.css');
	}

	public function GetList()
	{
		$ob = new Object;		
		$this->assign['list'] = $ob->loadTreeChidren(0);
	}


}

?>