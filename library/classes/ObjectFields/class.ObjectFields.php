<?php

class ObjectFields
{
	
	public $allowedTypes = array('text', 'array', 'checkbox', 'textarea', 'objectList', 'file', 'custom', 'richtext', 'select', 'password', 'readonly', 'autodatetime', 'autoipaddr', 'date', 'rewrite', 'tags', 'datetime', 'objectRead', 'hidden', 'ip_addr' );
	
	public static function GetTypes()
	{
		$obj = new ObjectFields;
		return $obj->allowedTypes;
	}
	
	public static function GetField($item, $data, $object = null)
	{	
		$item['data'] = $data;
		$item['object'] = $object;
		if(!isset($item['required']))
		{
		  $item['required'] = false;
		}
		$fakeField = $item['name'] . '_VALUE';
		if(isset($object->$fakeField))
		{
			$item['value_value'] = $object->$fakeField;
		}
		
		if(!in_array($item['type'], self::getTypes()))
		{
			return;
		}
		
		switch($item['type'])
		{
			case 'text':
				$out = self::GetText($item);
			break;
			
			case 'checkbox':
				$out = self::GetCheckbox($item);
			break;
			
			case 'textarea':
				$out = self::GetTextarea($item);
			break;
			
			case 'array':
				$out = self::GetArrayFields($item, $data);
			break;
			
			case 'objectList':
				$out = self::GetObjectList($item);
			break;
			
			case 'file':
				$out = self::GetFile($item);
			break;
			
			case 'richtext':
				$out = self::GetRichtext($item);
			break;
			
			case 'select':
				$out = self::getSelectField($item);
			break;
			
			case 'password':
				$out = self::getPasswordField($item);
			break;
			
			case 'autodatetime':
				$out = self::getAutodatetimeField($item);
			break;
			
			case 'autoipaddr':
				$out = self::getAutoipaddr($item);
			break;
			
			case 'readonly':
				$out = self::getReadonly($item);
			break;
			
			case 'hidden':
				$out = self::getReadonly($item);
			break;
			
			case 'ip_addr':
				$out = self::getReadonly($item);
			break;
			
			
			case 'custom':
				$itemData = call_user_func(array($object, $item['method']));
				$out = self::GetField($itemData, $itemData['data'], $object);
			break;
			
			case 'date':
				$out = self::GetDateField($item);
			break;
			
			case 'rewrite':
				$out = self::GetRewriteField($item);
			break;
		  
            case 'tags':
				$out = self::GetTagsField($item);
			break;
		  	
		  	case 'datetime':
				$out = self::GetDatetimeField($item);
			break;

            case 'objectRead':
                $out = self::GetObjectReadField($item);
            break;
            
		}
		
		
	
		
		if(!isset($out))
		{
			return false;
		}
		
		return $out;
		
	}
	
	
	public static function GetText($item)
	{
		global $smarty;
		$smarty->assign('field', $item);
		$out = $smarty->fetch(PATH . 'library/templates/fields/text.tpl');
		return $out;
	}

	public static function GetPasswordField($item)
	{
		global $smarty;
		$smarty->assign('field', $item);
		$out = $smarty->fetch(PATH . 'library/templates/fields/password.tpl');
		return $out;
	}
	
	public static function GetCheckbox($item)
	{
		global $smarty;
		$smarty->assign('field', $item);
		$out = $smarty->fetch(PATH . 'library/templates/fields/checkbox.tpl');
		return $out;
	}

	public static function GetTextarea($item)
	{
		global $smarty;
		$smarty->assign('field', $item);
		$out = $smarty->fetch(PATH . 'library/templates/fields/textarea.tpl');
		return $out;
	}
	
	public static function getSelectField($item)
	{
		global $smarty;
		$smarty->assign('field', $item);
		$out = $smarty->fetch(PATH . 'library/templates/fields/select.tpl');
		return $out;
	} 
	
	public static function getReadonly($item)
	{	
		global $smarty;
		$smarty->assign('field', $item);
		$out = $smarty->fetch(PATH . 'library/templates/fields/readonly.tpl');
		return $out;
	}
	
	public static function getAutodatetimeField($item)
	{	
		global $smarty;
		$smarty->assign('field', $item);
		$out = $smarty->fetch(PATH . 'library/templates/fields/readonly.tpl');
		return $out;
	}
	
	public static function getAutoipaddr($item)
	{	
		global $smarty;
		$smarty->assign('field', $item);
		$out = $smarty->fetch(PATH . 'library/templates/fields/readonly.tpl');
		return $out;
	}
	
	public static function getArrayFields($item, $data)
	{
	
	    if(!isset($item['repeat']))
		{
			$item['repeat'] = true;
		}
		
		if(!isset($item['fields']) || !is_array($item['fields']))
		{
			return false;
		}
		
		$dataArray = array();
		if(is_array($data))
		{
			$dataArray = $data;
		}
		
		$outHtml = array();
		//get y from values
		$keys = array_keys($item['fields']);
		if(!$keys)
		{
			return array();
		}
		$y = 1;
		if(isset($item['data'][$keys[0]]) && !isset($item['fetchOnlyFirst']) &&  $item['fetchOnlyFirst']!= true) //for accidentally creating doubled values
		{
			$y = count($item['data'][$keys[0]]);
		}
		$x = 0;
		for($x=0; $x<=$y;$x++)
		{
			foreach($item['fields'] as $key=>$field)
			{
					
				$dataA = '';
				if(isset($dataArray[$key][$x]))
				{
					$dataA = $dataArray[$key][$x];
				}
				
				$field['name'] = $item['name'].'['.$key.']['.$x.']';
				$field['file_field_name'] = $item['name'].'['.$key.'_file]['.$x.']';
				$field['label'] = $key;
				$out = self::GetField($field, $dataA, $item['object']);
				//$x++;
			
				if($out!='')
				{			
					$oneField = array();
					$oneField['name'] = $key.'['.$x.']';
					$oneField['field'] = $out;
					$outHtml[$x][] = $oneField;
				}	
		
			}
		
		}
		global $smarty;
		$smarty->assign('fields', $outHtml);
		$smarty->assign('fieldx', $item);  
		return $smarty->fetch(PATH . 'library/templates/fields/array.tpl');	
	}
	
	
	public static function GetObjectList($item)
	{
		global $smarty;
		$obj = new $item['objectName'];
		if(is_array($item['value']))
		{
			$params = array('sort' => $item['value'][0]); //take first
		}
		else
		{
			$params = array('sort' => $item['value']);
		}
		
		$list = $obj->getList($params);
		$smarty->assign('oList', $list);
		$smarty->assign('field', $item);
		$out = $smarty->fetch(PATH . 'library/templates/fields/objectList.tpl');
		return $out;
	}
	
	public static function GetFile($item)
	{
		global $smarty;
		$item['key'] = substr(sha1($item['name']),0, 6);
		if(!isset($item['file_field_name']))
		{
			$item['file_field_name'] = $item['name'] . '_file';
		}
		$smarty->assign('field', $item);
		$smarty->assign('object', $item['object']);
		$out = $smarty->fetch(PATH . 'library/templates/fields/file.tpl');
		return $out;
	}
	
	public static function GetRichtext($item)
	{
		global $smarty;
		global $mceLoaded;
		
		$smarty->assign('mceLoaded', $mceLoaded);
		$smarty->assign('field', $item);
		
		$smarty->assign('path_tiny_mce', BASE . 'library/3rdpart/tiny_mce/tiny_mce.js');
		
		if(!$mceLoaded)
		{
			$mceLoaded = true;
		}
		
		$out = $smarty->fetch(PATH . 'library/templates/fields/richtext.tpl');
		return $out;
		
	}
	
	public static function GetDateField($item)
	{
		global $smarty;
		global $uiDatePicker;
		$smarty->assign('uiDatePicker', $uiDatePicker);
		$smarty->assign('field', $item);
		
		$smarty->assign('path_jquery_ui', BASE . 'library/3rdpart/jquery/ui/js/jquery.ui.core.min.js');
		$smarty->assign('path_jquery_datepicker', BASE . 'library/3rdpart/jquery/ui/js/jquery.ui.datepicker.js');
		
		if(!$uiDatePicker)
		{
			$uiDatePicker = true;
		}
		
		$out = $smarty->fetch(PATH . 'library/templates/fields/date.tpl');
		return $out;
		
	}
	
	public static function GetRewriteField($item)
	{
		global $smarty;
		$smarty->assign('field', $item);
		$out = $smarty->fetch(PATH . 'library/templates/fields/rewrite.tpl');
		return $out;
	}
	
	public static function GetTagsField($item)
	{
		global $smarty;
		$value = '[';
		if($item['data']!='')
		{
    		$expl = explode(',', $item['data']);
    		$vallues = array();
    		foreach($expl as $k=>$v)
    		{
    		  if(trim($v)!='')
    		  {
                $values[] = '{value: "'.$v.'", name: "'.$v.'"}';            
    		  }
    		}
    		if(sizeof($values))
    		{
    		  $value .= implode(',', $values);
		      }
		}
		$value .= ']'; 
		$item['_tags_field_value'] = $value;
		$smarty->assign('field', $item);
		//debug($item);
		$smarty->assign('tags', self::GetObjectTags($item['object'], $item['name']));
		
 
		$out = $smarty->fetch(PATH . 'library/templates/fields/tags.tpl');
		return $out;
	}
	
	public static function GetDatetimeField($item)
	{
		global $smarty;
		$smarty->assign('field', $item);
		$out = $smarty->fetch(PATH . 'library/templates/fields/datetime.tpl');
		return $out;
	}
	
	public static function GetObjectTags($object, $field)
	{   
	    $table = TABLEPREFIX . $object->fields[$field]['table'];
	   
        $q = array();
        $q['select'] = 'tagname';
        $q['from'] = $table;
        $q['order'] = 'tagname';
        
        return dbExecute($q);
        
    }
    
    public static function GetObjectReadField($object)
    {   
        //debug($object);
//        $obj  = $object['objectName']::LoadObject($object['data']. 'Id');
  //      debug($obj);
        //if($obj)
        //{
            global $smarty;
            
            $smarty->assign('fieldName', $object['name'] );    
            $smarty->assign('fieldValue', $object['data']);
            
            return $smarty->fetch(PATH . 'library/templates/fields/readonlyfield.tpl');
        //}
        //return '';
    }
	
	public static function SaveObjectTags($object, $tagsString, $field)
	{
	   
	   if($tagsString == '')
	   {
	       return;
	   }
	   
	   $exists = self::GetObjectTags($object, $field);
	   $names = array();
	   foreach($exists as $x)
	   {
	       $names[] = $x['tagname'];
	   }
	   
	   $expl = explode(',', $tagsString);
	   $table = $object->fields[$field]['table'];
        foreach($expl as $item) 
        {
            if(trim($item)!='' && !in_array($item, $names) )
            {
                dbInsert(array('tagname'=>$item), $table);
            }
            
        }
	   
	   return;
	   
	   
	
	}
	
	
		
	
	
	

}


?>