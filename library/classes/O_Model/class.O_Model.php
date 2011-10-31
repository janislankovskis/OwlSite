<?php 
/**
* @category		OwlSite
* @package 		OwlSite Library
*
* @version 1.1
*/

class O_Model 
{

	/**
	* @category		OwlSite
	* @package 		OwlSite Library
	* @subpackage	O_Model
	* 
	* help on field types and options -> /library/helper.txt
	* 
	* ///////////// Class YourModel extends O_Model //////////////////////
	* 
	* $ob = YourModel::LoadObject($ID);  	//returns object
	* $ob = YourModel::LoadObject(); 	 	//returns empty object
	* $ob->saveObject();
	* $ob->delete();
	*
	* $list = YourModel::GetObjectList($params = array()) 	//returns array of objects
	* 
	*/
	
	public $error = array();
	
	public $id;
	
	public $tableName;
	
	public $allowedParams = array('sort', 'direction', 'limit', 'id', '_search', 'where', 'order', 'leftJoin', 'select');
	
	public $presave, $postsave; //pre & podt save actions

	public $presaveO, $postsaveO; //pre & podt save actions	 V2

	public $cleanup; 
	
	protected $joinLetters = array('q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p');
	
	/* MAGIC FUNCTIONS */
	public function __construct($id = null)
	{
		if(!$id)
		{
			return;
		}

		$this->id = $id;
		
		//cleanup function
		if($this->cleanup != NULL)
		{
			$variables = call_user_func(array($this, $this->presave), $variables);
		}	
	}
	
	public function __get($key)
	{
		if(!empty($this->$key) && !is_null($this->$key))
		{
			return $this->$key;
		}
			
		//get related property name 
		if(substr($key, -6) == 'Object')
		{
			$name = substr($key, 0, -6);
			
			if(!isset($this->fields[$name]))
			{
				return false;
			}
			if($this->fields[$name]['type'] == 'objectList' || $this->fields[$name]['type'] == 'objectRead')
			{	
				$obname = $this->fields[$name]['objectName'];
				return $obname::LoadObject($this->$name);
			}		
			
		}
		
		return false;
	}
	
	
	/* PUBLIC METHODS */
	public function Save($variables)
	{
		
		$this->loadValues(); //aaaaaaaa		

		if($this->presave != NULL)
		{
			$variables = call_user_func(array($this, $this->presave), $variables);
		}
		
		$variables = $this->validate($variables);
		
		//debug($variables);
			
		if(isset($this->id) && $this->id > 0)
		{
			$variables['id'] = $this->id;
		}
		
		$saveData = $this->removeFieldTypeFields($variables);
		
		if(empty($this->error))
		{
			$upload = $this->upload($_FILES);
			foreach($upload as $item)
			{
				if(is_array($item['key']))
				{
					$keys = $item['key'];
					//TODO: refactor
					//pagaidaam ir viens variants  -> 3 
					if(!is_array($saveData[$keys[0]]))
					{
						$saveData[$keys[0]] = unserialize($saveData[$keys[0]]);
					}
					
					$saveData[$keys[0]][$keys[1]][$keys[2]] = $item['id'];
					$saveData[$keys[0]] = serialize($saveData[$keys[0]]);	
				}
				else
				{
					$saveData[$item['key']] = $item['id'];
				} 
			}
			$tableName = TABLEPREFIX . $this->tableName;
			dbReplace($saveData, $tableName);
			
			$this->loadUpdated();
			
			if($this->postsave != NULL)
			{
				$variables = call_user_func(array($this, $this->postsave));
			}
			
		}
		
		return;
	}
	
	public function upload($data)
	{

		$folder = strtolower(get_class($this));
		$savePath = PATH . 'content/' . $folder . '/';
		if(!file_exists($savePath))
		{
			mkdir($savePath);
		}
		
		$out = array();		
		foreach($data as $k=>$item)
		{
		
			if(isset($item['name']) && isset($item['type']) && !is_array($item['name']) && $item['error'] == 0)
			{
				$newName = $this->generateFileName($item['name']);
				//upload & save & retrun key
				if(copy($item['tmp_name'], $savePath . $newName))
				{
					$dataIn = array (
						'date_time' => '__NOW()__',
						'ip_addr' 	=> $_SERVER['REMOTE_ADDR'],
						'data'		=> serialize(array('originalFileName' => $item['name'])),
						'file' 		=> $newName,
						'object'	=> $folder,
					);
					dbInsert($dataIn, 'attachments');
					$r = returnFirstRow('SELECT id FROM attachments ORDER BY id DESC LIMIT 1');
					$ID = $r['id'];
					
					$out[] = array('key' => str_replace('_file', '', $k), 'id' => $ID);
				}
				
			}
			elseif(isset($item['name']) && isset($item['type']) && is_array($item['name']))
			{
				$i = 0;
				$is = false;
				foreach($item['name'] as $ke => $x)
				{
					$key = $ke;
					$maxKey = count($x)-1;	 
				}
				for($i=0; $i<=$maxKey; $i++)
				{
					$tmp = $item['tmp_name'][$key][$i];
					$name = $item['name'][$key][$i];
					$err =  $item['error'][$key][$i];
					
					$newName = $this->generateFileName($name);
										
					if($err == 0 && copy($tmp, $savePath . $newName))
					{
						$dataIn = array (
						'date_time' => '__NOW()__',
						'ip_addr' 	=> $_SERVER['REMOTE_ADDR'],
						'data'		=> serialize(array('originalFileName' => $item['name'])),
						'file' 		=> $newName,
						'object'	=> $folder,
						);
						
						dbInsert($dataIn, 'attachments');
						$r = returnFirstRow('SELECT id FROM attachments ORDER BY id DESC LIMIT 1');
						$ID = $r['id'];
						
						$full_key = array (  $k, str_replace('_file', '', $key),$i); 
						
						$out[$i] = array('key' => $full_key, 'id' => $ID);
					}
					
				}
				
			}
						
		}
		
		return $out;
		
	}
	
	private function cleanupAfterDelete($data)
	{
		
	}
	
	public function uploadFile($fileData)
	{
		
		$folder = strtolower(get_class($this));
		$savePath = PATH . 'content/' . $folder . '/';
		if(!file_exists($savePath))
		{
			mkdir($savePath);
		}
		
		$newName = $this->generateFileName($fileData['name']);
		
		if(copy($fileData['tmp_name'], $savePath . $newName))
		{
			$dataArray = array('originalFileName' => $fileData['name'] ); 
			$dIn = array(
				'file' 		=> $newName,
				'object'	=> $folder,
				'object_id' => $this->id,
				'date_time' => '__NOW()__',
				'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
				'data' 		=> serialize($dataArray),
			);
			
			dbInsert($dIn, 'attachments');
			
			$q = 'SELECT id FROM attachments order by id DESC limit 1';
			$r = returnFirstRow($q);
			$id = $r['id'];
			
			return $id;
			
		}
		
		return false;
	
	}
	
	
	public function removeFieldTypeFields($variables)
	{
		
		foreach($this->fields as $key=>$val)
		{
			if($val['type'] == 'file' && is_array($variables[$key]) && $variables[$key]['error'] == '0')
			{
				$variables[$key] = 0;
			}
			elseif($val['type'] == 'file' && is_array($variables[$key]) && $variables[$key]['error'] == '4') // - no change
			{
				$variables[$key] = $this->$key;
			}
		}
		
		return $variables;
		
	}
	
	function generateFileName($name)
	{
    	$sufix = $this->getSufix($name);
    	$newname = date('YmdHis') . '-' . rand(1000, 9999) . $sufix;
    	return $newname;
	}
    
	function getSufix($name)
	{
   		return strtolower(substr($name, strrpos($name,'.')));
	}
	
	
	public function validate($variables)
	{
		$allow = true;		
		$data = array();
		foreach($this->fields as $key => $field)
		{
			
			if(!isset($variables[$key]) && isset($field['required']) && $field['required']==true)
			{
				//error
				$this->error[$key] = $key;
			}
			
			else
			{
				//validate //TODO additional checks & custom fields
				$value = '';
				if(isset($variables[$key]))
				{
					$value = $variables[$key]; 
				}
				
				if(isset($field['required']) && $field['required']==1 && !$this->validateItem($field['type'], $value))
				{
					//maker error
					$this->error[$key] = $key;
				}
				else
				{
					//pass 
					//serialize array
					if($field['type'] == 'array' || (isset($field['serialize']) && $field['serialize']==true)) 
					{
						$value = serialize($value);
					}
					elseif($field['type'] == 'tags')
					{ 
					    if(isset($variables['as_values_' . $key]))
					    {
					       $value = $variables['as_values_' . $key];
                            //parse all tags and save unlisted
                            ObjectFields::SaveObjectTags($this, $value, $key);
                        }   
					}
					
					$data[$key] = $value;
				}
			}
			
			//password field
			if($field['type'] == 'password' && $this->id != 0 && $variables[$key]=='')
			{
				$this->loadValues();
				unset($this->error[$key]);
				$data[$key] = $this->$key;
			}
			elseif
			(
				($field['type'] == 'password' && $this->id != 0 && $variables[$key]!='') ||
				($field['type'] == 'password' && $this->id == 0)
			)
			{
				if($variables[$key] != $variables[$key . '_repeat'])
				{
					$this->error[$key] = $key;
				}
				else
				{
					$data[$key] = sha1($variables[$key]);
					//unset($variables[$key . '_repeat']);
				}
			}
			
			//file field for save / delete 
			if(isset($variables[$key.'_delete']))
			{
				$data[$key.'_delete'] = $variables[$key.'_delete'];
			}
			
			
			//autodatetime
			if($field['type'] == 'autodatetime')
			{
				if(($this->id > 0 && isset($field['updateonsave']) && $field['updateonsave']==true)
					||
					($this->id == 0)
				)
				{
					$data[$key] = '__NOW()__';
				}
				else
				{
					$data[$key] = $this->$key;
				}
			}
			
			//autoipddr
			if($field['type'] == 'autoipaddr')
			{
				if(($this->id > 0 && isset($field['updateonsave']) && $field['updateonsave']==true)
					||
					($this->id == 0)
				)
				{
					$data[$key] = $_SERVER['REMOTE_ADDR'];
				}
				else
				{
					$data[$key] = $this->$key;
				}
			}
			
		}
				
		return $data;
	
	}
	
	public static function getObjectList($params = array())
	{
		//get object
		$className = get_called_class(); 
		$obj = new $className;

		$q = array();
		$q['select'][] = 's.*';
		$q['from'] = $obj->tableName . ' As s';
		
		if(isset($params['sort']) && in_array($params['sort'], $this->getListFields()))
		{
			$direction = 'ASC';
			if(isset($params['direction']) && $params['direction'] == '1')
			{
				$direction = 'DESC';	
			}
			$q['order'] = $params['sort'] . ' ' . $direction;
		}
		
		if(isset($params['limit']))
		{
			$q['limit'] = $params['limit'];
		}
		
		if(!isset($q['order']))
		{
			$q['order'] = 'id DESC';
		}
		if(isset($params['order']))
		{
		      $q['order'] = $params['order'];
		}
		
		if(isset($params['id']))
		{
			$q['where'][] = 's.id = ' . $params['id']; 
		}
				
		//get auto joins
		$joinLetters = array('q', 'w', 'e', 'r', 't', 'y');
		$ind = 0; 
		foreach($obj->fields as $key=>$item)
		{
			//objectList
			if($item['type'] == 'objectList')
			{
				$obj = new $item['objectName'];
				$q['leftJoin'][] = $obj->tableName . ' AS ' . $joinLetters[$ind] . ' ON (s.' . $key . ' = ' . $joinLetters[$ind] . '.' . $item['key'] . ')';
				$q['select'][] = $joinLetters[$ind] . '.' . $item['value'] . ' as ' . $key . '_VALUE'; 
				$ind++;
			}
			
			//file
			if($item['type'] == 'file')
			{
				$q['leftJoin'][] = 'attachments AS ' . $joinLetters[$ind] . ' ON(s.' . $key . ' = ' . $joinLetters[$ind] . '.id)';
				$q['select'][] = $joinLetters[$ind] . '.file as ' . $key . '_VALUE';  
 				$ind++;
			}
			
		}
		
		if(isset($params['where']))
		{
			if(!isset($q['where']))
			{
				$q['where'] = $params['where'];
			}
			else
			{
				$q['where'] = array_merge($q['where'], $params['where']);
			}
		
		}
		
		return dbExecute($q, get_called_class());	
	}
	
	public static function loadObject($id=0)
	{
		if($id==0)
		{
			$className = get_called_class();
			return new $className;
		}
		
		$params = array();
		$params['limit'] = 1;
		$params['where'][] =  's.id=' . $id;
		
		$list = self::getObjectList($params);
		if(sizeof($list))
		{
			$list[0]->loader();
			return $list[0];	
		}
		
		return false;
		
		
		
	}
	
	
	public function getList($params = array(), $getParts=false)
	{
		//old non-static method
		
		
		$params = $this->cleanupParams($params);
		//debug($params,0);
		
		
		$q = array();
		$q['select'][] = 's.*';
		$q['from'] = $this->tableName . ' As s';
		
		if(isset($params['sort']) && in_array($params['sort'], $this->getListFields()))
		{
			$direction = 'ASC';
			if(isset($params['direction']) && $params['direction'] == '1')
			{
				$direction = 'DESC';	
			}
			$q['order'] = $params['sort'] . ' ' . $direction;
		}
		
		if(isset($params['limit']))
		{
			$q['limit'] = $params['limit'];
		}
		
		if(isset($params['order']))
		{
			$q['order'] = $params['order'];
		}
		
		if(!isset($q['order']))
		{
			$q['order'] = 's.id DESC';
		}
		
		
		if(isset($params['id']))
		{
			$q['where'][] = 's.id = ' . $params['id']; 
		}
		
		if(isset($params['where']))
		{
			if(!isset($q['where']))
			{
				$q['where'] = $params['where'];
			}
			else
			{
				$q['where'] = array_merge($q['where'], $params['where']);
			}
		
		}
		
		
		//get auto joins
		$joinLetters = $this->joinLetters;
		$ind = 0; 
		foreach($this->fields as $key=>$item)
		{
			//objectList
			if($item['type'] == 'objectList')
			{
				$obj = new $item['objectName'];
				$q['leftJoin'][] = $obj->tableName . ' AS ' . $joinLetters[$ind] . ' ON (s.' . $key . ' = ' . $joinLetters[$ind] . '.' . $item['key'] . ')';
				$q['select'][] = $joinLetters[$ind] . '.' . $item['value'] . ' as ' . $key . '_VALUE'; 
				$ind++;
			}
			
			//file
			if($item['type'] == 'file')
			{
				$q['leftJoin'][] = 'attachments AS ' . $joinLetters[$ind] . ' ON(s.' . $key . ' = ' . $joinLetters[$ind] . '.id)';
				$q['select'][] = $joinLetters[$ind] . '.file as ' . $key . '_VALUE';  
 				$ind++;
			}
			
			//objectRead
			if($item['type'] == 'objectRead')
			{
			    $obj = new $item['objectName'];
                $q['leftJoin'][] = $obj->tableName . ' AS ' . $joinLetters[$ind] . ' ON(s.' . $key . ' = ' . $joinLetters[$ind] . '.'.$item['key'].')';
                $q['select'][] = $joinLetters[$ind] . '.' . $item['value'].' As ' . $key;  
                $q['select'][] = 's.'.$key.' As ' . $key.'Id';  
                $ind++;
			}
			
		}
		
		//triger serach 
		if(isset($params['_search']) && $params['_search']!='')
		{
			$params['_search'] = mysql_real_escape_string($params['_search']); 
			//get search fields
			$q['where'][] = implode(' LIKE "%'.$params['_search'].'%" OR ', $this->getSerachFields()) . ' LIKE "%'.$params['_search'].'%"';
		}
		
		if(isset($params['select']))
		{
			$q['select'] = array_merge($q['select'], $params['select']);
		}
		
		if(isset($params['leftJoin']))
		{
			if(isset($q['leftJoin']))
			{
				$q['leftJoin'] = array_merge($q['leftJoin'], $params['leftJoin']);
			}
			else
			{
				$q['leftJoin']  = $params['leftJoin'];				
			}
		}
		if($getParts)
		{
		  return $q;
		}
		
		return dbExecute($q, get_class($this));
		
	}
	
	public function getCount($params)
    {

        //return false;
        $parts = $this->getList($params, true);  
        if(isset($parts['limit']))
        {
            unset($parts['limit']);
        }
        
        $parts['select'] = 'count(*) AS count';
        unset($parts['leftJoin'], $parts['where']); 
        //debug($parts);
        
       $result = returnFirstRow($parts);
	   if($result)
	   {
	       return $result['count'];
	   }
	   
	   return false;
	}
	
	
	private function getSerachFields()
	{
		$out = array();
		$joinLetters = $this->joinLetters;
		$ind = 0;
		foreach($this->fields as $key=>$field)
		{	
			if($field['type']=='objectList')
			{
				$out[] = $joinLetters[$ind] . '.' . $field['value'];
				$ind++;
			}
			else
			{
				$out[] = 's.' . $key;	
			}
		}
		
		return $out; 
	}
	
	
	public function validateItem($type, $data)
	{
		//TODO create class.Validate.php -> Validate::Text($value);
		switch ($type)
		{
			case'array':
				return true;
			break;
			default:
				//text
				if($data!='')
				{
					return true;
				}
					
			break;
		} 
		
		
		return false;
		
	}

	private function loader()
	{
		//solution for serialized fields
		foreach($this->fields as $name=>$field)
		{
			if( ( isset($field['serialized']) && $field['serialized']) || $field['type'] == 'array')
			{
				$this->$name = 	unserialize($this->$name);
			}	
		}
	}
	
	private function loadUpdated()
	{
		
		if($this->id > 0)
		{
			$id = $this->id;
		}
		else
		{
			$q = array();
			$q['select'][] = 'id';
			$q['from'] = $this->tableName;
			$q['limit'] = 1;
			$q['order'] = 'id DESC';
			
			$r = returnFirstRow($q);
			$id = $r['id'];
		}
		
		$this->id = $id;
		$this->loadValues();
		
	}
	
	
	public function loadValues()
	{
		$class = get_class($this);

		// return $class::LoadObject($this->id);
		
		if($this->id!=null)
		{
			$params = array('id' => $this->id);
			$list = $this->getList($params);
			$values = $list[0];
		}
		else
		{
			$values = $this;
		}
		
		
		//TODO: refactor
		$props = get_class_vars(get_class($this));
		foreach($props as $prop => $value)
		{
			//$value = _utility::stripslashes_deep($value);
			$value = $values->$prop;
			if( 
				((isset($values->fields[$prop]['type']) && $values->fields[$prop]['type'] == 'array') ) 
				||
				(isset($values->fields[$prop]['serialized']) && is_string($value))
			) 
			{
				$value = _utility::mb_unserialize($value);
				$value = _utility::stripslashes_deep($value);
			}
			
			$fakeField = $prop.'_VALUE';
			
			if(isset($values->$fakeField))
			{
				$this->$fakeField = $values->$fakeField;
			}			
			$this->$prop = $value;	
		}
		
		
	}
	
	public function getListFields()
	{
		if(!is_array($this->fields))
		{
			return;
		}
		$forbiddenFields = array('textarea', 'richtext', 'array', 'file', 'password');
		$out = array();
 		foreach($this->fields as $key => $item)
		{
			if(!in_array($item['type'], $forbiddenFields) && (!isset($item['notInList']) || $item['notInList'] != true ) )
			{
				$out[] = $key;
			}
		}
		
		return $out;
	
	}
	
	
	public function Delete()
	{
		
		foreach($this->fields as $key=>$val)
		{
			if($val['type']=='file' && $this->$key != 0)
			{
				$this->deleteFile($this->$key);
			}
		}
		dbDelete(array('id', $this->id), $this->tableName);
		return;
	}
	
	
	private function cleanupParams($params)
	{
		
		foreach($params as $key=>$item)
		{
			if(!in_array($key, $this->allowedParams))
			{
				unset($params[$key]);
			}
		}
		
		return $params;
		
	}
	
	public function getFieldValue($field)
	{
		$f = $this->fields[$field];
		
		if($f['type']=='checkbox')
		{
			if($this->$field == 1)
			{
				return "*";
			}
			else
			{
				return '';
			}
		}
		
		if($f['type'] == 'objectList')
		{
			$key = $field . '_VALUE';
			 
			return $this->$key;
		}
		
		if($f['type'] == 'tags')
		{
		
            if(substr($this->$field, 0, 1) == ',')
            {
                $this->$field = substr($this->$field, 1);
            }
            
            if(substr($this->$field, -1) == ',')
            {
                $this->$field = substr($this->$field, 0, -1);
            }
        
		}
		
		if($f['type'] == 'date' && $this->$field == '0000-00-00')
		{
			return '';	
		}
		
		return $this->$field;
		
	}
	
	public function getAttachmentsPath()
	{
		$path = PATH . 'content/' . strtolower(get_class($this)) . '/';
		return $path;
	} 
	
	public function getAttachmentsUrl()
	{
		$url = WWW . 'content/' . strtolower(get_class($this)) . '/';
		return $url;
	} 
	
	public function deleteFile($id)
	{
		if($id==null)
		{
			return;
		}
		
		$q = array();
		$q['select'][] = 'a.*';
		$q['from'] = 'attachments AS a';
		$q['where'][] = 'a.id=' . $id;
		
		$r = returnFirstRow($q);
		
		$file = $this->getAttachmentsPath() . $r['file'];
		if(file_exists($file))
		{
			unlink($file);
		}

		dbExecute('DELETE FROM attachments WHERE id= ' . $id);
		
		return true;
		
	}
	
	
	public function getAttachment($id = null)
	{
		if(!is_numeric($id) && $id<=0)
		{
			return false;
		}
		
		$out = array();
		$q = 'SELECT * FROM attachments WHERE id = ' . $id . ' LIMIT 1';
		$r = returnFirstRow($q);

		if(!$r)
		{
			return false;
		}
		
		$out['name'] = $r['file'];
		$out['url'] = BASE . 'content/' . strtolower(get_class($this)) . '/' . $r['file'];
		$out['fullUrl'] = WWW . 'content/' . strtolower(get_class($this)) . '/' . $r['file'];
		$out['dir'] = strtolower(get_class($this));   
		
		return $out;	
	}
	
	
	public function SaveObject()
	{
		$fields = get_class_vars(get_class($this));
		$fields = $fields['fields'];
		$data = array();
		
		if(isPositiveInt($this->id))
		{
			$data['id'] = $this->id;
		}
		
		//check presave
		
		if(isset($this->presaveO))
		{
			call_user_func(array($this, $this->presaveO));
		}
		
		foreach($fields as $key => $val)
		{
			//check data types etc
			switch($val['type'])
			{
				case 'autodatetime';
					$data[$key] = "__NOW()__";
				break;
				
				case 'ip_addr';
					$data[$key] = $_SERVER['REMOTE_ADDR'];
				break;
				
				default:
					$data[$key] = $this->$key;
				break;
			}
			
		}
		
		
		dbReplace($data, $this->tableName);
		
	}
	
	
	
		
}




?>