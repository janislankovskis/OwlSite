<?php 

class CurrentModuleObject extends DefaultAdminModule 
{
	public $moduleName = 'Users';
	public $objectName = '_adminUser';
	//public $itemsPerPage = 2;
	
	public function __construct()
	{	
		$this->initModule();
		$this->_add_css( 'style/forms.css');
	}
	
	
}

?>