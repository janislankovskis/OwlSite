<?php 

class _adminUser extends O_Model
{

	public $group, $email, $password, $active, $module, $sum;
	
	public $tableName = 'users';
	
	public $sessionName;
	
	public $fields = array(

		'group'	=>array(
			'type' => 'objectList',
			'objectName' => '_adminUserGroup',
			'value' => 'name', // var buut arii array //TODO: implement
			'key'	=> 'id',
			'addBlank' => false,	
		),
		'email' => array(
			'type' => 'text',
			'required' => true,
		),
		
		'password' => array(
			'type' => 'password',
			'required' => true,
		),
		
		'module'	=> array(
			'type'   => 'custom',
			'method' => 'getModulesList',
		),
		'active' => array(
			'type' => 'checkbox',
		),
		
	);
	
	public static function GetUser($reload = true)
	{
		if(isset($_SESSION[self::getSessionName()]))
		{
			if(!$reload)
			{
				return $_SESSION[self::getSessionName()];
			}
			$ID = $_SESSION[self::getSessionName()]->id;
			$user = _adminUser::LoadObject($ID);
			return $user;
		}
		return false; //new _adminUser();
	}
	
	
	public static function getSessionName()
	{
		global $conf;
		return $conf['cms_session_name'];
	}
	
	
	public static function catchLogin()
	{
		if(sizeof($_POST))
		{
			if( $_POST['email'] == '' || $_POST['password'] == '')
			{
				return 'fail';
			}
			
			$q = 'SELECT * FROM users WHERE active=1 AND email = "' . mysql_real_escape_string($_POST['email']) . '" AND password = "' . sha1($_POST['password']) . '"';
			$r = dbExecute($q, __CLASS__);
			
			if(sizeof($r))
			{
			    global $project;
				//set cookie
				setcookie(self::getSessionName(), $r[0]->sum, strtotime('1 Day'), '/' . $project['root']);
				$_SESSION[self::getSessionName()] = $r[0];
				redirect($_POST['return']);
			}
			
			return 'fail';
		
		}
		
	}
	
	public static function catchLogout()
	{
		
		if(isset($_GET['logout']))
		{
		    global $project;
			unset($_SESSION[self::getSessionName()]);
			//unset cookie
			setcookie(self::getSessionName(), '', time()-strtotime('1 Day'), '/' . $project['root']);
			redirect($_GET['return']);
		}
		
	}
	
	public static function getByCookie()
	{
		
		if(!isset($_COOKIE[self::getSessionName()]) && !isset($_GET['_session'])) //TODO: tmp solution
		{
			return false;
		}
		
		if(isset($_COOKIE[self::getSessionName()]))
		{
			$key = $_COOKIE[self::getSessionName()];
		}
		else
		{
			$key = $_GET['_session'];
		}
		
		$q = 'SELECT * FROM users WHERE active=1 AND sum  = "' . mysql_real_escape_string($key) .'"';
		$r = dbExecute($q, __CLASS__);
		if(!$r)
		{
			return false;
		}
		
		$_SESSION[self::getSessionName()] = $r[0];
		return true;
		
	}
	
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
	
	
	
}


?>