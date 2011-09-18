<?php 

class _siteConfig extends ObjectModule
{
	
	public $tableName = 'configuration';

	public $languages;
	
	public $fields = array(
		'languages' => array(
			'type' => 'text',
		),
	);
	
	
	
	public static function getConfig()
	{
		
		$obj = new _siteConfig;
		$list = $obj->GetList();
		
		if(!$list)
		{
			return false;
		}
		
		$conf = array();
		
		$conf['languages'] = explode(',', $list[0]->languages);
		
		return $conf;
		
		
	}

}


?>