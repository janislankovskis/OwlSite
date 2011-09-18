<?php 

class CurrentModuleObject extends DefaultAdminModule 
{

	public $modes = array('edit', 'save');
	public $objectName = '_siteConfig';
	
	public $moduleName = 'Configuration';
	
	//public $itemsPerPage = 2;
	
	public function __construct()
	{	
		$this->initModule();
		
		$_GET['id'] = 1;
		$this->_add_css( 'style/forms.css');
	
	
	}
	
	
}

?>