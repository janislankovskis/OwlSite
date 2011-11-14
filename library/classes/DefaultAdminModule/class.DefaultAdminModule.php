<?php

class DefaultAdminModule
{

	public $assign = array('js'=>array());

	public $view, $js, $caller;
	
	public $objectName = '';
	
	public $modes = array('edit', 'list', 'save', 'delete'); //define default actions
	
	public $openedObject, $relativeUrl;
	
	public $itemsPerPage = 25;	
	
	
	public function initModule($admin=null)
	{
	   
	   if(isset($_GET['ajax']) && isset($_GET['tag']))
	   {
	       $this->GetSearchTagsResult($_GET['tag']);
	   }
		
		//TODO: fix this / re-think
		
		$mode = 'List';
		if(isset($_GET['mode']))
		{	
			$mode = ucwords($_GET['mode']);
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

		//load openedObject
		if(isset($_GET['id']) && isPositiveInt($_GET['id']))
		{
			$name = $this->objectName;
			$this->openedObject = $name::LoadObject($_GET['id']);
		}

		//add module.css if $admin interface loaded and file exists
		if($admin!=null && file_exists(PATH . $admin->modulesProperties[$admin->module]['dir'] . 'module.css'))
		{
			$this->_add_css( $admin->getRelUrl() . 'module.css' );
		}
		


	}
	
	public function GetForm()
	{
		$id = null;
		if(isset($_GET['id']) && is_numeric($_GET['id']))
		{
			$id = $_GET['id'];
		}

		$obName = $this->objectName;
		$this->assign['openedObject']  = $obName::LoadObject($id); 
		$this->openedObject = $this->assign['openedObject'];
		
		$values = array();
		if(sizeof($_POST))
		{
			$values = $_POST;
		}
		
		return ObjectForm::createHtmlForm($this->assign['openedObject'], $values);
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

		if(isset($_GET['_search'])) // admin specific
		{
			$params['_search'] = $_GET['_search'];
		}
		
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

	public function getRelUrl()
	{
		if($this->relativeUrl!='')
		{
			return $this->relativeUrl;
		}

		//$ad = new _adminModule;

		debug($this);

		//return get_included_files();
	}

	public function getSearch()
	{
		global $smarty;
		return $smarty->fetch( PATH . 'library/templates/admin/search.tpl');
	}





}

?>