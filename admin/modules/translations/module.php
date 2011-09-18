<?php 

class CurrentModuleObject extends DefaultAdminModule 
{

	public $modes = array('list', 'save', 'savegroup', 'delete', 'deletegroup');
	public $objectName = '_translations';
	public $moduleName = 'Trasnslations';	
	
	public function __construct()
	{	
	
		$this->initModule();
		$this->_add_css( 'style/forms.css');
		$this->_add_css( 'modules/translations/module.css');
		$this->_add_js( WWW . 'library/3rdpart/jquery/jquery.js');
		$this->_add_js( 'modules/translations/module.js');
	}
	
	
	public function GetList()
	{
		$groups = _translation::getGroups(); 
		$this->assign['groups'] = $groups;
		if(sizeof($groups))
		{
			$id = $groups[0]['id'];
			if(isset($_GET['group']))
			{
				$id = $_GET['group'];
			}
			
			$config = _siteConfig::getConfig();
			$this->assign['languages'] = $config['languages'];
			$currentgroup = $groups[0]; 
			$this->assign['translations'] = _translation::getTranslations(array('group' => $id));
			
			if(isset($_GET['group']) && is_numeric($_GET['group']))
			{
				$currentgroup = $_GET['group']; 
			}
			
			$group = null;
			foreach($groups as $item)
			{
				if($item['id'] == $currentgroup)
				{
					$group = $item;
				}
			}

			$this->assign['currentgroup'] = $group;
			
		}
		
		
	}
	
	
	public function saveGroup()
	{
		
		if(empty($_POST['newGroup']))
		{
			return false;
		}
		
		$q = 'INSERT INTO translationsGroups(name) VALUES ("'.mysql_real_escape_string($_POST['newGroup']).'")';
		dbExecute($q);
		if(!isset($_GET['ajax']))
		{
			return;
		}
		
		$last = _translation::getLastGroup();
		if($last)
		{
			global $smarty;
			$smarty->assign('item', $last);
			$outHtml = $smarty->fetch( ADMIN_PATH . 'modules/translations/groupField.tpl' );
			die($outHtml);
		}
		
		
	}
	
	
	public function deleteGroup()
	{
		_translation::deleteGroup($_POST['id']);
		if(!isset($_GET['ajax']))
		{
			return;
		}
		
		die('ok');
	}
	
	public function save()
	{
		$config = _siteConfig::getConfig();
		$i = 0;
		$values = array();
		foreach($config['languages'] as $x)
		{
			$values[$x] = $_POST['values'][$i];  
			$i++;
		}
		
		$data = array(
			'ident' => $_POST['ident'],
			'values' => serialize($values),
			'group' => $_POST['group'],
		);
		
		//add id
		if(isset($_POST['id']))
		{
			$data['id'] = $_POST['id'];
		}
		
		dbReplace($data, 'translations');
		
		if(isset($_POST['id']))
		{
			die();
		}
		
		$params = array('limit'=>1);
		
		if(isset($_POST['id']))
		{
			$params['id'] = $_POST['id'];
		}
		
		$params['order'] = 'id DESC';
		
		
		$list = _translation::getTranslations($params);
		if(!sizeof($list))
		{
			die();
		}
		
		global $smarty;
		$smarty->assign('languages',  $config['languages']);
		$smarty->assign('item', $list[0]);
		$outHtml = $smarty->fetch( ADMIN_PATH . 'modules/translations/translationField.tpl' );
		die($outHtml);
	}
	
	
	public function delete()
	{
		//debug('asdas');
		$tr = new _translation($_POST['id']);
		//debug($tr);
		$tr->Delete();
		
		if(!isset($_GET['ajax']))
		{
			return;
		}
		
		die('ok');
	}
	
	
	
}

?>