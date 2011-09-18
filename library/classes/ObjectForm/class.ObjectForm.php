<?php

class ObjectForm
{


	public static function createHtmlForm($object, $values=array())
	{
		if(!is_object($object))
		{
			return;
		}
		$outHtml = array();
        
        if(empty($object->fields))
        {
            return false;
        }
        
		foreach($object->fields as $key=>$item)
		{
			if(!sizeof($values))
			{
				$value = $object->$key;
			}
			elseif(isset($values[$key]))
			{
				$value = $values[$key];
			}
			else
			{
				$value = '';	
			}
			
			$item['name'] = $key;
			$out = '';
			
			if($item['type'] == 'array')
			{
				$item['name'] = $key;
			}
			
			$out = ObjectFields::GetField($item, $value, $object);
			
			if($out!='')
			{
				$oneField = array();
				$oneField['name'] = $key;
				$oneField['field'] = $out;
				$oneField['label'] = $key;
				$outHtml[$key] = $oneField;
			}
			
		}
		
		return $outHtml;
		
	}
	
}

?>