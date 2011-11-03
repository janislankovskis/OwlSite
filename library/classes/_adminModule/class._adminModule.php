<?php 

class _adminModule
{

	public $template, $module, $menu, $loggedUser, $language, $loginAttempt, $assign;
	
	
	public function __construct($pass=false)
	{
		if($pass)
		{
			return;
		}
		$template = 'login';
		if($this->IsLoggedIn() || _adminUser::getByCookie())
		{
			$template = 'index';
		}
		else
		{
			$this->loginAttempt = _adminUser::catchLogin();
		}
		
		
		$this->getTranslations();
		
		$this->SetTemplate($template);
		
		$this->menu = $this->GetMenu();
		$this->module = $this->GetModule();
		$this->moduleContent = $this->GetModuleContent();

		return;
	}
	
	/* PUBLIC METHODS */
	public function IsLoggedIn()
	{
		$user = _adminUser::GetUser();
		if($user)
		{
			$this->loggedUser = $user;
			_adminUser::catchLogout();
			return true;
		}
		
		return false;
	
	}
	
	private function getTranslations()
	{
		//language
		global $project;
		$list = _translation::getTranslations(array('loadgroup'=>true));
		
		global $TRA;
		foreach($list as $item)
		{
			if(isset($item['values'][$project['admin_language']]))
			{
				$TRA[$item['groupName']][$item['ident']]  = $item['values'][$project['admin_language']];
			}	
		}
		
	}
	
	
	public function GetMenu()
	{
		$menuItems = array();
		$notFit = array('.', '..','.DS_Store');
		
		$modulesProperties = array();
		
		//get user permissions
		$permissions = _adminUserPermission::GetPermissions();
		
		//library modules
		$fs = opendir( ADMIN_PATH . 'modules/');
		while (false !== ($file = readdir($fs))) 
		{
	    	if(!in_array($file, $notFit) && (!isset($permissions[$file]) || ($permissions[$file]!='read')) )
	    	{	
	    		$x = array(
	    			'name' => $file,
	    			'dir' => 'admin/modules/' . $file,
	    		);
	    		
	    		$modulesProperties[$file]['dir'] = ADMIN_BASE . '/modules/' . $file . '/';
	    		if(is_dir(PATH . 'admin/modules/' . $file))
	    		{
	    			$chlds = array();
	    			$fs2 = opendir( PATH . 'admin/modules/' . $file);
	    			while(false !== ($fil2 = readdir($fs2)))
	    			{
	    				if(!in_array($fil2, $notFit)
	    					&&	
	    					is_dir(PATH . 'admin/modules/' . $file . '/' . $fil2)
	    					)
	    				{
	    					$y = array('name' => $fil2, 'dir' => 'admin/modules/' . $file . '/' . $fil2);
	    					$chlds[] = $y;
	    					$modulesProperties[$fil2]['dir'] = 'admin/modules/' . $file . '/' . $fil2 . '/';
	    				}
	    			}
	    			$x['sub'] = $chlds;
	    		}
	    		$menuItems['library'][] = $x;
	    				
	    	}
	    }
		
	    //project modules
		$fs = opendir( PATH . 'project/adminModules/');
		
		while (false !== ($file = readdir($fs))) 
		{
	    	if(!in_array($file, $notFit))
	    	{	
	    		$x = array(
	    			'name' => $file,
	    			'dir' => 'project/adminModules/' . $file,
	    		);
	    		$modulesProperties[$file]['dir'] = 'project/adminModules/' . $file .'/';  
	    		if(is_dir(PATH . 'project/adminModules/' . $file))
	    		{
	    			$chlds = array();
	    			$fs2 = opendir( PATH . 'project/adminModules/' . $file);
	    			while(false !== ($fil2 = readdir($fs2)))
	    			{
	    				if(!in_array($fil2, $notFit)
	    					&&	
	    					is_dir(PATH . 'project/adminModules/' . $file . '/' . $fil2)
	    					)
	    				{
	    					$y = array('name' => $fil2, 'dir' => 'project/adminModules/' . $file . '/' . $fil2);
	    					$chlds[] = $y;
	    					$modulesProperties[$fil2]['dir'] = 'project/adminModules/' . $file . '/' . $fil2 . '/';
	    				}
	    			}
	    			
	    			$x['sub'] = $chlds;
	    			
	    		}
	    		
	    		$menuItems['project'][] = $x;	
	    	}
	    }
	    
	    $this->modulesProperties = $modulesProperties;
	    return $menuItems;
	   
	}
	
	public function GetModuleContent()
	{
		
		if(!$this->module)
		{
			return;
		}
		
		$prop = $this->getProperties($this->module);
		
		$templatePath = PATH . $prop['dir'];
		$template = $templatePath . 'index.tpl';
		
		global $smarty;
		
		if(!file_exists($template))
		{
			return $this->GetInnerErrorTemplate('no template index file in <b>' . $this->module . '</b> module');	
		}
		
		
		
		if(file_exists($templatePath . 'module.php'))
		{
			$smarty->template_dir[] = $templatePath;
			include_once($templatePath . 'module.php');
			$moduleObject = new CurrentModuleObject($this);
			$assign = $moduleObject->assign;
			$smarty->assign('module', $moduleObject);
			$smarty->assign($assign);
			
			$this->assign = $assign;
		}
		
		
		
		return $smarty->fetch($template);
			
	}
	
	
	
	/* PRIVATE METHODS */
	
	private function SetTemplate($template)
	{
		$this->template = $template;	
	}
	
	private function GetInnerErrorTemplate($message = 'this is blank error message')
	{
		$template = ADMIN_PATH . 'templates/blocks/error.tpl';
		global $smarty;
		$smarty->assign('errorMessage', $message);
		return $smarty->fetch($template);
	}
	
	private function GetModule()
	{
		$params = $this->ReadParams();
		
		if($this->template == 'login')
		{
			return false;
		}
		
		if(isset($params['module']) && $this->moduleExists($params['module']))
		{
			$module = $params['module'];
			//$module = strtolower($params['module']);			
		}
		else
		{
			$user = _adminUser::getUser();
			$module = $user->module;
		}
		
		return $module;
		
	}

	private function moduleExists($module)
	{
		//TODO: implement
		return true;
		
	} 
	
	
	public function getProperties($module)
	{
		if(!key_exists($module, $this->modulesProperties))
		{
			return false;
		}
		
		return $this->modulesProperties[$module];
		
	}
	
	
	//TODO: moduleProperties
	
	private function ReadParams()
	{
		//$params = array_merge($_GET, $_POST);
		//return $params;
		return $_GET;
	}
	
	
	public function getSiteUrl()
	{
		return WWW;
	}

	public function getRelUrl()
	{
		return BASE . $this->modulesProperties[$this->module]['dir'];
	}


}

?>
