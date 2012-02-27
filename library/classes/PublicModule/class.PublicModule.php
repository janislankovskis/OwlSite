<?php 

class PublicModule
{
	
	public $openedObject, $urlParts = array(), $objects = array(), $title = array(), $languages = false, $metaDescription;
	
	public $template, $language, $name;
	
	public $contentLoaded = false;
	
	public $html;
	private $css = array(), $js = array(), $cssString, $cssStringName, $jsString, $jsStringName;
	
	/* magic functions */
	
	public function __construct()
	{
		global $project;
		$this->GetTemplate();
        $this->content();
		$this->title = array_reverse($this->title);
		$this->project = $project;
	}
	
	
	public function __get($name)
	{
		
		return $this->$name;
		
		/* cache all css */
		if($name == 'css')
		{
			$outer = array();
			$fName = '';
			foreach($this->css as $file)
			{
				if(substr($file, 0, 4)!='http')
				{
					$fName .= $file;
				}
				else
				{
					$outer[] = $file;
				}
			}
			
			global $conf;
			$fName = sha1($fName) . '.css';
			
			if(!file_exists( $conf['CACHE_PATH'] . 'css/' . $fName ))
			{
				foreach($this->css as $file)
				{
				 	if(substr($file, 0, 4)!='http')
				 	{
				 		$strWrite .= file_get_contents(PATH . substr($file, 0, strpos($file,'?')));
				 	}
				}	
				
				file_put_contents ( $conf['CACHE_PATH'] . 'css/' . $fName , $strWrite);
			}
			
			$outer[] = WWW . 'cache/css/' . $fName;
			
			return $outer;
			
		}
		
		return $this->$name;
	}
	
	/* static functions */
	
	public function GetTemplate()
	{
		$params = array('active' => 1, 'limit' => 1, 'parent'=>0);
		$obj = new Object;

		if(empty($_GET['rewrite'])) //get first object
		{
			$list = $obj->getList($params);
			if(sizeof($list))
			{
				$this->openedObject = $list[0];
				$this->title[] = $this->openedObject->name;
				$this->objects[] = $list[0];
				$this->metaDescription = $this->openedObject->alt_description;
				if($this->openedObject->template == 'language')
				{
					$this->language = strtolower($this->openedObject->name);
				}
			}
			else
			{
				$this->template = '404';
			}
		}
		else
		{	
			$this->urlParts =  explode('/', $_GET['rewrite']);
			$pid = 0;
			foreach($this->urlParts as $key=>$rewrite)
			{
				if($rewrite == '' || is_null($rewrite))
				{
					unset($this->urlParts[$key]);
					continue;
				}
				
				$object = $obj->getByRewrite($rewrite, $pid);
				if($object)
				{
					$pid = $object->id;
					$this->objects[] = $object;
					unset($this->urlParts[$key]);
					$this->openedObject = $object;
					
					if($object->template == 'language')
					{
						$this->language = strtolower($object->name);
					}
					else
					{
						$this->title[] = $object->name;
					}
					
					if(!empty($object->alt_description))
					{
						$this->metaDescription = $object->alt_description;
					}
				}
				
			}

			if(!$this->openedObject)
			{
    			//try first
    			$list = $obj->getList($params);
    			if(sizeof($list))
    			{
    				$this->openedObject = $list[0];
    			//	$this->title[] = $this->openedObject->name;
    				$this->content();
    				$this->objects[] = $list[0];
    				$this->metaDescription = $this->openedObject->alt_description;
    			}
			}
            elseif(sizeof($this->urlParts))
            {
                //$this->title[] = $this->openedObject->name;
                $this->content(); // <----------------------------------------------------------------------------bookmark
                //debug($this);
            }
            
            
			if(sizeof($this->urlParts) || !$this->openedObject)
			{
				$this->template = '404';

			}
		}

		if($this->openedObject)
		{
			$this->openedObject->loadValues();
		}
		
		$this->getLanguages(true);
	}
	
	public function getTitle()
	{
		if($this->openedObject && !empty($this->openedObject->alt_title))
		{
			$this->title = array();
			$this->title[] = $this->openedObject->alt_title;
		}
		return array_reverse($this->title);
	}

	public function content()
	{
		
		global $smarty;

		if($this->contentLoaded)
		{
		   return;
		}
		
		$this->getTranslations();
		
		$smarty->template_dir[] = PATH . 'project/templates';
		
		$path = PATH . 'project/templates/';
		
		
		
		if($this->openedObject && $this->template!='404')
		{
				
			$smarty->assign('languages', $this->getLanguages() );//<0000000000000000000--------------------------
			$smarty->assign('mainMenu', $this->getMenu());
			$smarty->assign('opened', $this->openedObject);
			
			$smarty->assign($this->openedObject->fetchData());

			if(file_exists($path . $this->openedObject->template . '.php'))
			{
				include_once($path . $this->openedObject->template . '.php');
				$module = new Module($this);
				if(isset($module->pageTitle) && is_array($module->pageTitle))
				{
	                $this->title = array_merge($this->title, $module->pageTitle);
				}
				$this->title = array_reverse($this->title);
			}
	        
		//project data
		if(file_exists($path . '_project.php'))
		{	
			include_once($path . '_project.php');
			if(!isset($module))
			{
				$module = null;
			}
			new Frame($module, $this);
			$smarty->assign($module->assign);
		}
		
		
		//css
		global $project;
			if(isset($project['css']) && is_array($project['css']))
			{
				foreach($project['css'] as $css)
				{
					$this->_add_css(BASE . $css);	
				}
			}

			if(file_exists($path . $this->openedObject->template . '.css'))
			{
				$this->_add_css( BASE . 'project/templates/' . $this->openedObject->template . '.css');
			}
			
			if(file_exists($path . $this->openedObject->template . '.js'))
			{
				$this->_add_js( BASE . 'project/templates/' . $this->openedObject->template . '.js');
			}
		
		}
		
		if($this->template == '404' || ( sizeof($this->urlParts) && $this->openedObject) )
		{
		    $this->title = array();
			unset($this->js, $this->css);
			if(file_exists( PATH . 'project/templates/404.css' ))
			{
			     $this->_add_css( BASE . 'project/templates/404.css');
			}
			header("HTTP/1.0 404 Not Found");
			$template = '404.tpl';
		}
		else
		{
			$template =  $this->openedObject->template . '.tpl';
		}

		//debug($this);
		
		//$smarty->assign('languages', array());
		//

		$this->html = $smarty->fetch($path . $template);
		$this->contentLoaded = true;

	}
	
	public function getLanguages($active=false)
	{
		$ids = $this->getOpenedIds();
		$obj = new Object;
		$params = array('parent' => 0, 'template' => 'language');
		if($active)
		{
			$params['where'][] = 'active=1';
			$params['where'][] = 'showonmenu=1';
		}
		
		$list = $obj->getList($params);
		
		if(!$list)
		{
			return false;
		}
		
		foreach($list as $key=>$item)
		{
			if(in_array($item->id, $ids))
			{
				$list[$key]->opened=true;
				if(!defined('CURRENT_LANGUAGE')){
					define('CURRENT_LANGUAGE', strtolower($list[$key]->name));
				}
			}
		}
		


		$this->languages = $list;
		return $list;

	}
	
	public function getMenu($root=0)
	{
		//calculate root id
		if($root==0 && !empty($this->languages))
		{
			foreach($this->languages as $lang)
			{
				if($lang->opened!=false)
				{
					$root = $lang->id;
				}
			}			
		}
		
		$obj = new Object;
		$params = array('parent' => $root, 'active' => 1, 'showonmenu'=>1);
		$list = $obj->getList($params);
		
		foreach($list as $key=>$item)
		{
			if(in_array($item->id, $this->getOpenedIds()))
			{
				$list[$key]->opened = true;
				$list[$key]->children = $this->getMenu($item->id);
			}
		}
		
		return $list;
		
	}
	
	
	public function getOpenedIds()
	{
		$ids = array();
		foreach($this->objects as $obj)
		{
			$ids[] = $obj->id;
		}
		
		return $ids;
	}
	
	public function _add_css($file)
	{
		
		if(substr($file, 0, 4)!='http' && file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
		{
			$this->cssString .= file_get_contents($_SERVER['DOCUMENT_ROOT'] . $file);
			$file .= $file . '?' . md5_file($_SERVER['DOCUMENT_ROOT'] . $file);
			$this->cssStringName .= $file;
		}
		else
		{
			$this->css[] = $file;
		}	
		
	}
	
	public function _add_js($file)
	{
		$this->js[] = $file;
	}
	
	public function GetCss()
	{
		$name = sha1($this->cssStringName);
		$full_path = CACHE_PATH . 'css/' . $name . '.css';
		if(!file_exists($full_path))
		{
			/* remove comments */
		    $this->cssString = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $this->cssString);
    		/* remove tabs, spaces, newlines, etc. */
    		$this->cssString = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $this->cssString);
			file_put_contents($full_path, $this->cssString);
		}
		if($this->css == null) /*TODO: why this happens on 404 pages? */
		{
			$this->css = array(WWW . 'cache/css/' .$name . '.css');	
		}
		else
		{
			$this->css[] = WWW . 'cache/css/' .$name . '.css';
		}
		return $this->css;
	}


	public function GetJs()
	{
		return $this->js;
		
		$name = sha1($this->jsStringName);
		$full_path = CACHE_PATH . '/js/' . $name . '.js';
		if(!file_exists($full_path))
		{
		
		  //  $this->cssString = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $this->jsString);
    
    	  // $this->cssString = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $this->jsString);
		
			file_put_contents($full_path, $this->cssString);
		}
		$this->js[] = WWW . 'cache/js/' .$name . '.js';
		
		return $this->js;
	}

	
	
	public function getTranslations()
	{
        if(empty($this->language))
		{
			$config = _siteConfig::getConfig();
			if(isset($config['languages'][0]))
			{
				$this->language = $config['languages'][0];
			}
		}
		
		global $TRA;
		$TRA = array('site'=>array('siteTitle' => '404 - Not Found'));
		
		
		if(empty($this->language))
		{
			return array();
		}

		$tra = _translation::getTranslations(array('loadgroup'=>true));
		
		
		foreach($tra as $item)
		{    
		      if(isset($item['values'][$this->language]))
		      {
			       $TRA[$item['groupName']][$item['ident']] = $item['values'][$this->language];
			  }
		}
		
		return true;
	}


}


?>