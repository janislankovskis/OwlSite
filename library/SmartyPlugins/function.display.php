<?php 

function smarty_function_display($params, &$smarty) 
{

	extract($params);
	//file, width, height, from, urlonly, alt, ar, crop
	if(!isset($file) || $file=='')
	{
		return;
	}
	
	if(!isset($alt))
	{
		$alt = '';
	}
	
	$ext = strtolower(substr($file, strrpos($file,'.')));
	$images = array('.jpg', '.jpeg', '.gif', '.bmp', '.png'); 
	$flash = array('.swf');
	
	if( in_array(strtolower($ext), $flash) )
	{
		return displayFlash($params);
	}	
	elseif(in_array(strtolower($ext), $images) )
	{
    	return O_Image::LoadImage($params);
	}
}

function displayFlash($params)
{
	if(isset($params['fileUrl']))
	{
		$file = $params['fileUrl'];
		$width = $params['width'];
		$height = $params['height'];
	}
	else
	{
		$file = BASE . 'content/' . $params['from'] . '/' . $params['file'];
		list($width, $height) = getimagesize(PATH . 'content/' . $params['from'] . '/' . $params['file']);
	}
	
	$flash = array(
		'file' 		=> $file,
		'width'		=> $width,
		'height'	=> $height,
	);
	
	global $smarty;
	$smarty->assign('flash', $flash);
	$html = $smarty->fetch(PATH . 'library/templates/fields/flash.tpl');
	return $html;
}

?>