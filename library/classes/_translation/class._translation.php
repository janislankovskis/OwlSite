<?php 

class _translation extends O_Model
{
	
	public $tableName = 'translations';
	
	public $group, $translation, $ident;
	
	public $fields = array(
		'group' => array(
			'type' => 'hidden',
		),
	);
	
	
	public static function getGroups($params = array())
	{
		$q = array();
		$q['select'][] = 'o.*';
		$q['from'] = 'translationsGroups As o';
		$q['order'] = 'o.name';
		
		if(isset($params['order']))
		{
			$q['order'] = $params['order'];
		}
		
		if(isset($params['limit']))
		{
			$q['limit'] = $params['limit'];
		}
		
		
		return dbExecute($q);
		
	} 
	
	public static function getLastGroup()
	{
		$params = array('limit' => 1, 'order' => 'o.id DESC');
		$list = self::getGroups($params);
		
		if(isset($list[0]))
		{
			return $list[0]; 
		}
		
		return false; 
		
	}
	
	
	public static function deleteGroup($id)
	{

		if(empty($id) || !is_numeric($id))
		{
			return false;
		}
		
		$q = 'DELETE FROM translations WHERE `group`  = ' . $id;
		//debug($q);
		dbExecute($q);
		
		$q = 'DELETE FROM translationsGroups WHERE id = ' . $id;
		dbExecute($q);
		
		return true;
		
	}
	
	public static function getTranslations($params = array())
	{
		$q = array();
		$q['select'][] = 'x.*';
		$q['from'] = 'translations As x'; 
		
		if(isset($params['group']))
		{
			$q['where'][] = 'x.group = ' . $params['group'];
		}
		
		if(isset($params['id']))
		{
			$q['where'][] = 'x.id = ' . $params['id'];
		}
		
		if(isset($params['limit']))
		{
			$q['limit'] = $params['limit'];
		}
		
		if(isset($params['order']))
		{
			$q['order'] = $params['order'];
		}
		
		if(isset($params['loadgroup']))
		{
			$q['select'][] = 'g.name as groupName';
			$q['leftJoin'][] =  'translationsGroups as g ON g.id = x.group';
		}
		
		$list = dbExecute($q);
		
		foreach($list as $key=>$val)
		{
			$list[$key]['values'] = unserialize($val['values']);	
		}
		
		return $list;
	}
	
	
	
	
	
	
	
}

?>