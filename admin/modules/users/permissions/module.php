<?php 

class CurrentModuleObject extends DefaultAdminModule 
{
	public $moduleName = 'User permissions';
	public $objectName = '_adminUserPermission';
	//public $itemsPerPage = 2;
	
	public function __construct()
	{	
		$this->initModule();
		$this->_add_css( 'style/forms.css');
	}
	
	
}

?>