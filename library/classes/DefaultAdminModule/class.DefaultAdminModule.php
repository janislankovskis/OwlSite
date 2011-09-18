<?php

class DefaultAdminModule
{

	public $assign = array('js'=>array());
	
	public $view, $js;
	
	public $objectName = '';
	
	public $modes = array('edit', 'list', 'save', 'delete'); //define default actions
	
	public $openedObject;
	
	public $itemsPerPage = 25;	
	
	public function initModule()
	{
	   
	   if(isset($_GET['ajax']) && isset($_GET['tag']))
	   {
	       $this->GetSearchTagsResult($_GET['tag']);
	   }
	   		   	
		
		//TODO: fix this! 
		
		$mode = 'List';
		if(isset($_GET['mode']))
		{	
			$mode = ucwords($_GET['mode']);
		//	$mode = $_GET['mode'];
		}
		
		
		
		if(!in_array(strtolower($mode), $this->modes))
		{
			return;
		}
		
		
		if($mode == 'List')
		{
			$mode = 'Get' . $mode; 
		}
		
        if(method_exists($this, $mode))
        {
		  
		  call_user_func(array($this, $mode));
		
		}
		
		$this->assign['mode'] = $mode;
	}
	
	public function GetForm()
	{
		$id = null;
		if(isset($_GET['id']) && is_numeric($_GET['id']))
		{
			$id = $_GET['id'];
		}
		
		$object = new $this->objectName($id);		
		$object->loadValues();
		$this->assign['openedObject']  = $object;
		$this->openedObject = $object;
		$values = array();
		
		if(sizeof($_POST))
		{
			$values = $_POST;
		}
		
		return ObjectForm::createHtmlForm($object, $values);
	}
	
	
	public function GetList()
	{
		$obj = new $this->objectName;
		$this->view = 'list';
		$params = $_GET;
		$page = 1;
		if(isset($_GET['page']))
		{
			$page = $_GET['page'];
		}
		
		$params['limit'] = ($page-1)*$this->itemsPerPage .  ', ' . $this->itemsPerPage;
		$this->assign['list'] = $obj->getList($params);
		
//		unset($params['limit']);
		$pages = array(
			'total' => ceil($obj->getCount($params)/$this->itemsPerPage),
			'current' => $page, 
		);
		
		$this->assign['pages'] = $this->getPagedNavigationHtml($pages);
		$this->assign['object'] = $obj; //blank object
	}
	
	public function Edit()
	{	
		$this->_add_js(ADMIN_WWW . 'js/formhelpers.js');
		$this->view = 'form';
		$this->assign['object'] = new $this->objectName; //blank object
	}
	
	public function Save()
	{
		$id = null;
		if(isset($_GET['id']) && is_numeric($_GET['id']))
		{
			$id = $_GET['id'];
		}
		
		$obj = new $this->objectName($id);
		
		$data =  $_POST;
		
		$obj->Save($data);
		
		if(empty($obj->error))
		{
			if(isset($_POST['editOptionsReturn']))
			{
				//return to list
				redirect($_POST['editOptionsReturn']);
			}
			elseif(is_numeric($obj->id))
			{
				//return to self edit if new
				$add = array('id' => $obj->id, 'mode' => 'edit');
				redirect(url($add));
			}
			else
			{
				//return to default
				redirect($_POST['return']);
			}
		}
		
		else
		{
			//assign data to form
			$this->Edit();
			$this->assign['error'] = $obj->error;
		}
		
		return $this->assign;	
	}
	
	public function Delete()
	{
		
		if(!isset($_POST['id']))
		{
			if(isset($_POST['return']))
			{
				redirect($_POST['return']);
			}
			
			return;
		}
		
		//delete
		$obj = new $this->objectName($_POST['id']);
		if($obj)
		{
			$obj->delete();
		}
		
		if(isset($_POST['return']))
		{
			redirect($_POST['return']);
		}
		
		return;
		
	}
	
	public function _add_css($file)
	{
			$this->assign['css'][] = $file;
	}
	
	public function _add_js($file)
	{
			$this->assign['js'][] = $file;
	}
	
	public function getModuleUrl()
	{
		return ADMIN_WWW . 'modules/' . strtolower($this->objectName) . '/';
	}
	
	public function doNeedUpperSaveButton()
	{
		$obj = new $this->objectName;
		if(count($obj->fields) > 5)
		{
			return true;
		}
		return false;
	}
	
	public function getModuleName()
	{
		
		if(empty($this->moduleName))
		{
			return $this->objectName; 
		}
		
		return $this->moduleName;
	}
	
	public function getPagedNavigationHtml($pages)
	{
	   global $smarty;
	   $smarty->assign('pages', $pages);
	   return $smarty->fetch( PATH . 'library/templates/blocks/paginator.tpl' );
	}


}

?>