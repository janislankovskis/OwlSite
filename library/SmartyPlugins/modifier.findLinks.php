<?php

function smarty_modifier_findLinks($str)
{
   	
		if(!is_string($str) || strlen($str)==0)
		{
			return false;
		}
		
		$expr = '%(http://www.|https://www.|http://|https://|www.)([a-zA-Z0-9\-\.]+\.\w{2,4})/?([0-9a-zA-Z?=#;!&+/_\.\%-]*)%';
		
		preg_match_all($expr, $str, $match);
		
		if(empty($match)||!isset($match[0])||empty($match[0]))
		{
			return $str;
		}		
		
		//getting keys..
		$urls = array();
		$url_html = array();
		$i =0;
		foreach($match[0] as $found)
		{
			$found_re = $found;
			if(substr($found_re, 0, 4)!='http')
			{
				$found_re = 'http://' . $found_re;
			}
			$name = $match[2][$i];
			if(strlen($match[3][$i]) > 0)
			{
				$name .= '...';
			}
			$urls[] = $found;
			$url_html[] = '<a title="'.$found.'" href="'.$found_re.'">'.$name.'</a>';
			$i++;
		}
		
		return str_replace($urls, $url_html, $str);   	

}


?>