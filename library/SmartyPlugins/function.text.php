<?php
/**
 {text text="hello, world!" font=style}
*/

function smarty_function_text($params, &$smarty)
{
	global $project;
	$style = $project['fonts'][$params['font']];
	
	//text 
	if(isset($style['text-transform']) && $style['text-transform']=='uppercase')
	{
		$params['text'] = mb_convert_case($params['text'], MB_CASE_UPPER, "UTF-8");
	}
	
	if(isset($style['text-transform']) && $style['text-transform']=='lowercase')
	{
		$params['text'] = mb_convert_case($params['text'], MB_CASE_LOWER, "UTF-8");
	}

	//images cache path
	$imagesCache =  PATH . 'cache/images/';
	
	$key = sha1($params['text'] . $style['font']. $style['size']. $style['color']);
	$filename = 'text_' . $key . '.png';

	//debug($imagesCache . $filename);	
	
	if(!file_exists($imagesCache . $filename))
	{
		generate($params, $key);
	}
	
	return '<img src="' . WWW . 'cache/images/' . $filename . '" alt="' . $key . '" />';		
    
}

function generate($params, $key)
{
	global $project;
	$style = $project['fonts'][$params['font']];
	//fontpath 
	$fontPath = PATH. 'project/fonts/';
	$font = $fontPath. $style['font'];
	$filename = PATH . 'cache/images/text_' . $key . '.png';
	
	//get color
	$colorRGB = hex2rgb($style['color']);
	
	//generate	
	// create a bounding box for the text
	$dims = imagettfbbox($style['size'], 0, $font, $params['text']);
	
	// make some easy to handle dimension vars from the results of imagettfbbox
	// since positions aren't measures in 1 to whatever, we need to
	// do some math to find out the actual width and height
	$width = (round($dims[4] - $dims[6])); // upper-right x minus upper-left x 
	$height = $dims[3] - $dims[5] + 6; // lower-right y minus upper-right y
	
	//create Image
	$bg = imagecreatetruecolor($width, $height);

	//This will make it transparent
	imagesavealpha($bg, true);

	$trans_colour = imagecolorallocatealpha($bg, 0, 0, 0, 127);
	imagefill($bg, 0, 0, $trans_colour);

	$color = imagecolorallocate($bg, $colorRGB[0], $colorRGB[1], $colorRGB[2]);
	imagettftext($bg, $style['size'], 0, 0, $style['size'] + 2, $color, $font, $params['text']);

	//Create image
	imagepng($bg, $filename);
	imagedestroy($bg);	
	return;
}


function hex2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}


?>
