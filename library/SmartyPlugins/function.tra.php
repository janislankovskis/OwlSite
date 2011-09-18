<?php 

	function smarty_function_tra($params, &$smarty) 
	{
		global $TRA;

		if(!isset($params['item']) || !isset($params['group']))
		{
			return;
		}
		
		
		if(isset($TRA[$params['group']][$params['item']]) && $TRA[$params['group']][$params['item']]!='')
		{
			return stripslashes($TRA[$params['group']][$params['item']]);
		}
		return 'tra:' . $params['group'] . '/' . $params['item'];
	}

?>