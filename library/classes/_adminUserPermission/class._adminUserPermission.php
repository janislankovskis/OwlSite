<?php 

class _adminUserPermission extends O_Model 
{
	public $group, $module, $action, $active;
	
	public $tableName = 'usersPermissions';
	
	public $fields = array(
	
		'group' => array(
			'type'			=> 'objectList',
			'objectName'	=> '_adminUserGroup',
			'value'			=> 'name',
			'key'			=> 'id',	
		),
		'module' => array(
			'type'   => 'custom',
			'method' => 'getModulesList',
		),
		'action' => array(
			'type'	=> 'select',
			'list' => array(
				'read'	=> 'read',
				'write'	=> 'write',
			),
			
		),
		'active' => array(
			'type'	=> 'checkbox',
		),
		
	);
	
	
	public function getModulesList()
	{
		$x = new _adminModule(true);
		$menuArray = $x->GetMenu();
		
		$out = array();
		foreach($menuArray['library'] as $item)
		{
			$out[$item['name']] = $item['name'];
		}
		
		if(!isset($menuArray['project']))
		{
			return $out;
		}
		
		foreach($menuArray['project'] as $item)
		{
			if(sizeof($item['sub']))
			{
				foreach($item['sub'] as $i)
				{
					$out[$i['name']] = $i['name'];
				}
			}
			else
			{
				$out[$item['name']] = $item['name'];
			}
		}
		
		ksort($out);
		
		$field = array(
			'type'	=> 'select',
			'name'	=> 'module',
			'list'	=> $out,
			'data'	=> $this->module,
		);
		
		return $field;
		
	}
	
	
	public static function GetPermissions() 
	{
		$user = _adminUser::GetUser(); 
		if(!$user)
		{
			return;
		}
		
		$o = new _adminUserPermission;
		
		$params = array(
			'where' => array(
				's.group = ' . $user->group
			),
		);
		
		$list = $o->GetList($params);
		$permissions = array();
		foreach($list as $perm)
		{
			$permissions[$perm->module] = $perm->action;
		}
		
		return $permissions;
		
	}
}


?>