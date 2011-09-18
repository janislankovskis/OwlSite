<?php 

class CurrentModuleObject extends DefaultAdminModule
{

	public $objectName = 'Object';
	
	public $moduleName = 'Content';	
	
	public $modes = array('edit', 'add', 'delete', 'save', 'ajaxload');
	
	public function __construct()
	{
	   
	    $this->initModule();
		
		$this->getView();
		
		$this->loadTree();
		
		$this->_add_css( 'style/forms.css');
		$this->_add_css('modules/content/module.css');
		$this->_add_js('modules/content/module.js');
		$this->_add_js('js/formhelpers.js');
	}
	
	
	
	private function getView()
	{
		$allow = array('edit', 'add', 'delete', 'save');
		if(isset($_GET['mode']))
		{
			switch($_GET['mode'])
			{
				case'add':
					$this->assign['form'] = $this->getForm();
				break;
				case'edit':
					$this->assign['form'] = $this->getForm();
				break;
				case'delete':
					$this->delete();
				break;
				case'save':
					$this->assign['form'] = $this->getForm();
				break;
			}
		}
		
	}
	
	public function loadTree()
	{
		//all tree functions
		$obj = new $this->objectName;
		if(isset($_GET['load']))
		{
			$obj->loadTreeChidren($_GET['load']);	
		}
		
		if(isset($_GET['unload']))
		{
			$obj->unLoadTreeChidren($_GET['unload']);	
		}
		
		$this->assign['tree'] = $obj->getTree();
	}
	
	
	
	public function Delete()
	{
		if(!sizeof($_POST))
		{
			return false;
		}
		
		$obj = new $this->objectName;
		$obj->loadValues();
		$tdl = $obj;
		$list = $obj->getAllDescendants($_POST['id']);
		$list[] = $_POST['id'];
		$list = array_unique($list);
		
		foreach($list as $id)
		{
			$obj = new $this->objectName($id);
			$obj->Delete();
		}
		
		$tdl->updateOrdering($tdl->id);
		
		redirect($_POST['return']);
		
	}
	
	public function ajaxLoad()
	{
        $ob = $this->objectName;
        $obj = $ob::LoadObject($_GET['id']);
        
        
        $list = $obj->loadTreeChidren($obj->id);
        
        if(!$list)
        {
            _core_exit('false');
        }

        $html = '<ul class="treeGroup">';
        global $smarty;
        
        foreach($list as $item)
        {
            $smarty->assign('item', $item);
            $html .= $smarty->fetch(ADMIN_PATH  . 'modules/content/treeItem.tpl');
        }
        
        $html .= '</ul>';
        
        _core_exit($html);
        
	}
	
	
	
	
	
}


?>