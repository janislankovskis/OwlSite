<?php 

class Object extends ObjectModule
{
	
	public $tableName = 'objects';
	
	public $sessionName = 'owl_tree'; 
	
	public $parent, $name, $rewrite, $ordering, $template, $data, $dateSaved, $active, $showonmenu, $ip_addr;
	
	public $alt_title, $alt_description;
	
	public $fullUrl, $opened=false, $children = array();
	 
	public $presave = 'calculateData';
	
	public $postsave = 'postSave';
	
	public $fields  = array(
		'name'	=> array(
			'type' => 'text',
			'required'	=> true,
		),	
		
		'template'	=> array(
			'type' => 'custom',
			'method' => 'getTemplates',
			'default' => 'page',   	
		),
		
		'parent' => array(
			'type' => 'hidden',
		),
		
		'ordering' => array(
			'type' => 'custom',
			'method' => 'loadOrdering',
		),
		
		
		'data' => array(
			'type' => 'custom',
			'method' => 'getTemplateFields',
			'serialized' => true,
		),
		
		'alt_title' => array(
			'type' => 'text'
		),
		
		'alt_description' => array(
			'type' => 'textarea'
		),
		
		'active' => array (
			'type' => 'checkbox',
		),
		
		'showonmenu' => array (
			'type' => 'checkbox',
		),
		
		'rewrite' => array(
			'type' => 'rewrite',
			'required'	=> true,
			'source' => 'name',
		),
		
		'dateSaved' => array (
			'type' => 'autodatetime',
			'updateonsave' => true,
		),
		
		'ip_addr' => array(
			'type' => 'autoipaddr',
			'updateonsave'	=> true,
		),
		
		
	);
	
	private function returnChildren($id, $tree)
	{
		$out = array();
		foreach($tree as $item)
		{
			if($item->parent == $id)
			{
				$item->children = $this->returnChildren($item->id, $tree);
				
				if(sizeof($item->children))
				{
					$item->hasChildren = true;
				}
				else
				{
					$item->hasChildren = false;
				}
				
				$out[] = $item;
			}
		}
		
		return $out;
	}
	
	public function getAllDescendants($id = false, $out=array())
	{
		if(!$id)
		{
			return false;
		}
		
		$list = $this->getChildren($id);
		foreach($list as $x)
		{
			$out[] = $x->id;
			$out = array_merge($out, $this->getAllDescendants($x->id, $out));
		}
		return $out;
	}
	
	public function loadTreeChidren($parent = false)
	{
		if(!$parent)
		{
			return false;
		}
		
		$params = array('parent' => $parent);
		$list = $this->getList($params); 
		
		if(!$list)
		{
			return false;
		}
		
		foreach($list as $key=>$item)
		{
		      $list[$key]->children = $item->getChildren($item->id);
		      if(sizeof($list[$key]->children))
		      {
		          $list[$key]->hasChildren = true;
		      }
        }
		
		if(!isset($_SESSION[$this->sessionName]['opened']))
		{
			$_SESSION[$this->sessionName]['opened'][] = $parent;	
		}
		else
		{
			array_push($_SESSION[$this->sessionName]['opened'], $parent);
		}
		return $list;
	}
	
	public function unLoadTreeChidren($parent = false)
	{
		if(!$parent)
		{
			return false;
		}
		
		if(isset($_SESSION[$this->sessionName]['opened']))
		{
			foreach($_SESSION[$this->sessionName]['opened'] as $key=>$item)
			{
				if($item == $parent)
				{
					unset($_SESSION[$this->sessionName]['opened'][$key]);	
				}
			}
		}
	}
	
	
	public function getTree()
	{
		$ids = array(0); //open root
		
		//load from session opened ids..
		if(isset($_SESSION[$this->sessionName]['opened']) && is_array($_SESSION[$this->sessionName]['opened']))
		{
			$ids = array_merge($ids, $_SESSION[$this->sessionName]['opened']);	
		}
		
		//little cleanup
		$ids = array_unique($ids);
		
		//get all list
		$params = array(/* 'parent' => $ids  */);
		$list = $this->getList($params); 
		$out = array();
		
		foreach($list as $item)
		{
			if($item->parent == 0)
			{
				$item->children = $this->returnChildren($item->id, $list);
				
				if(sizeof($item->children))
				{
					$item->hasChildren = true;
				}
				else
				{
					$item->hasChildren = false;
				}
				
				$out[] = $item;	
			}
		}
		
		$out = $this->removeNodes($out, $ids);
		
		return $out;
		
	}
	
	
	private function removeNodes($tree, $opened)
	{
		foreach($tree as $key=>$val)
		{
			if(!in_array($val->parent, $opened))
			{
				unset($tree[$key]);		
			}
			elseif(sizeof($val->children))
			{
				$tree[$key]->children = $this->removeNodes($val->children, $opened);
			}
			
		}
		return $tree;
	}
	
	public function getList($params = array(), $getParts=false)
	{	
		$q = array();
		$q['select'][] = 'o.*';
		$q['from'] = $this->tableName . ' AS o';

		if(isset($params['parent']))
		{
			if(is_array($params['parent']))
			{
				$q['where'][] = 'o.parent IN (' . implode(',', $params['parent']) . ')';
			}
			else
			{
				$q['where'][] = 'o.parent = ' . $params['parent'];
			}
		}
		
		if(isset($params['id']))
		{
			$q['where'][] = 'o.id = ' . $params['id'];
		}
		
		if(isset($params['active']))
		{
			$q['where'][] = 'o.active = 1';
		}
		
		if(isset($params['showonmenu']))
		{
			$q['where'][] = 'o.showonmenu = 1';
		}
		
		if(isset($params['rewrite']))
		{
			$q['where'][] = 'o.rewrite = "' . mysql_real_escape_string($params['rewrite']) . '"';
		}
		
		if(isset($params['template']))
		{
			$q['where'][] = 'o.template = "' . mysql_real_escape_string($params['template']) . '"';
		}
		
		
		if(isset($params['limit']))
		{
			$q['limit'] = $params['limit'];
		}
		
		if(isset($params['where']))
		{
			if(isset($q['where']))
			{
				$q['where'] = array_merge($q['where'], $params['where']);
			}
			else
			{
				$q['where'] = $params['where'];
			}
		}
		
		
		$q['order'] = 'o.ordering';
		
		return dbExecute($q, __CLASS__);
		
	}
	
	public function loadOrdering()
	{
		$parent = $this->parent;
		if(!empty($_GET['parent']) && empty($this->id)) // creating new
		{
			$parent = $_GET['parent'];
		}
		$list = $this->getChildren($parent);
		$select = array();
		$select[0] = 'start';
		$i = 1;
		foreach ($list as $key=>$item)
		{
			if($this->id != $item->id)
			{
				$select[$i] = $item->name;
			}	
			$i++;
		}
		
		$out = array (
			'type'       => 'select',
			'list'       => $select,
			'name' 	     => 'ordering',
			'data'       => $this->ordering-1,
			'prefer'    => 'first', 
		);
		
				
		return $out;
		
	}
	
	public function getTemplates()
	{
		//readdir 
		$dir = PATH . 'project/templates/';
		$fp = opendir($dir);
		$templates = array();
		while($file = readdir($fp))
		{
			if(substr($file, -4) == '.xml')
			{
				$filexml = @simplexml_load_file($dir . $file);
				if(is_object($filexml))
				{
					$name = $this->xml_attribute($filexml, 'name');
					$templates[substr($file, 0, -4)] = $name;
				}
			}
		}
		
		asort($templates);
		
		if(sizeof($_POST))
		{
			$this->template = $_POST['template'];
		}
		
		elseif($this->template == '')
		{
			$this->template = $this->fields['template']['default'];
		}
		
		$out = array (
			'type' => 'select',
			'list'	=> $templates,
			'name' 	=> 'template',
			'data'	=> $this->template,
			'onchangesubmit' => true,
		);
		
		return $out;
	}
	
	private function xml_attribute($object, $attribute)
	{
		 if(isset($object[$attribute]))
        	return (string) $object[$attribute];
	}
	
	
	public function calculateData($data = array())
	{
		//rewrite //TODO: add ru chars.. and other...
		$wrong = array('   ', '  ', ' ', 'ā', 'č', 'ē', 'ģ', 'ī', 'ķ', 'ļ', 'ņ', 'š', 'ū', 'ž');
		$right = array('-', '-', '-', 'a', 'c', 'e', 'g', 'i', 'k', 'l', 'n', 's', 'u', 'z');
		
		$data['rewrite'] = mb_convert_case($data['rewrite'], MB_CASE_LOWER, "UTF-8");
		$data['rewrite'] = str_replace($wrong, $right, $data['rewrite']);
		$allow = '-_-1234567890qwertyuiopasdfghjklzxcvbnm--';
		
		$new = '';
		for($i = 0; $i <= strlen($data['rewrite'])-1; $i++)
		{
			if(strpos($allow, substr($data['rewrite'], $i, 1))!==false)
			{
				
				$new .= substr($data['rewrite'], $i, 1);
			}
		}
		
		$data['rewrite'] = $new; 
		if(!isset($data['data']))
		{
			$data['data'] = array();
		}
		//object custom data
		$data['data'] = serialize($data['data']);
		
		
		//ordering
		$data['ordering']++;
		
		return $data;
	}
	
	
	public function getTemplateFields()
	{	
		$file = PATH . 'project/templates/' . $this->template . '.xml';
		$xml = simplexml_load_file($file);
		$fields = array();
		foreach($xml->field as $x)
		{
			$field = array(
				'type' => $this->xml_attribute($x, 'type'),
			);
			$fields[$this->xml_attribute($x, 'name')] = $field;
		}
		
		/*
if(!sizeof($_POST) || is_string($this->data))
		{
			//$this->data = unserialize($this->data);
		}
		else
		
*/
/*
		if(isset($_POST['data']))
		{
			$this->data = $_POST['data'];
		}
		else
		{
		  $this->data = '';
		}
*/		
		$out = array(
			'type' => 'array',
			'name' => 'data', 
			'fields' => $fields,
			'data' => $this->data,
			'repeat'	=> false,
			'fetchOnlyFirst'  => true,
		);
		
		return $out;
		
	}
	
	
	public function fetchData()
	{
		if(!is_array($this->data))
		{
			return;		
		}
		
		$out = array();
		//dealing with 0 
		foreach($this->data as $k=>$x)
		{
			$out[$k] = $x[0];
		}
		
		return $out;
		
	}
	
	public function getByRewrite($rewrite = false, $parent = 0, $active = true)
	{
		if(!$rewrite)
		{
			return false;
		}
		
		$q = array();
		$q['select'][] = 'o.*';
		$q['from'] = $this->tableName . ' AS o';
		$q['where'][] = 'o.rewrite = "' . mysql_real_escape_string($rewrite) . '"';
		$q['where'][] = 'o.parent = ' . $parent;
		
		if($active)
		{
			$q['where'][] = 'o.active = 1';
		}
		
		$q['limit'] = 1;
		
		$result = dbExecute($q, __CLASS__);
		
		if(!sizeof($result))
		{
			return false; 
		}
		
		return $result[0];
	}
	
	
	public function getUrl($force=false)
	{
		//get parent
		if($force)
		{
			$parts = array();
			$parts[] = $this->rewrite; 
			$parent = $this->parent;
			while($parent != 0)
			{
				$obj = $this->getParent($parent);
				if($obj)
				{
					$parent = $obj->parent;
					$parts[] = $obj->rewrite;
				}
				else
				{
					$parent = 0;
				}
			}
			
			$parts = array_reverse($parts);
			$url = implode('/', $parts) . '/';
			$this->fullUrl = $url;
			$q = 'UPDATE ' . $this->tableName . ' SET fullUrl = "' . $url . '" WHERE id = ' . $this->id;
			dbExecute($q);
		}
		
		return  BASE . $this->fullUrl;
		
	}
	
	
	
	public function getParent($parent = null)
	{
		if($parent == null) 
		{
			$parent = $this->parent;
		}
		
		$params = array(
			'limit' => 1,
			'id' => $parent,
		);
		
		$x = $this->getList($params);
		if(!$x)
		{
			return false;
		}
		
		return $x[0];
		
	}
	
	
	public function postSave()
	{
//	       debug($this);
	   
		$this->getUrl(true);
		$this->updateChildrenUrls($this->id);
		
		$this->updateOrdering($this->id);
		
	} 
	
	
	
	/*
	 *  updates ordering .. just sets ordering values up from 0	   
     */
	public function updateOrdering($id)
	{
		$obj = new Object($id);
		$obj->loadValues();		
		$list = $this->getChildren($obj->parent);
		$next = 1;
		foreach($list as $item)
		{
				$q = 'UPDATE objects SET ordering = ' . $next . ' WHERE id = ' . $item->id;
				dbExecute($q);
				$next++;
		}
        
	}
	
	public function updateChildrenUrls($id)
	{
		$list = $this->getChildren($id);
		foreach($list as $obj)
		{
			$obj->getUrl(true);
			$this->updateChildrenUrls($obj->id);
		}
	} 
	
	public function getChildren($id=null, $params2 = array())
	{
		if($id == null)
		{
			$id = $this->id;
		}
		$params = array('parent' => $id);
		$params = array_merge($params, $params2);
		return $this->getList($params);
	}
		
		
	

	
	
	
}



?>