<?php

/*
	
	collection of functions used to resize, scale, crop, cache images

*/

class O_Image
{
	
	public static function LoadImage($params=array())
	{
		global $project;
		$params['quality'] = 80;
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
		
		$path = PATH . 'content/' . $from . '/';
		$cache_path = PATH . 'cache/images/';
		
		$name = substr($file, 0, strrpos($file,'.'));
		$ext = strtolower(substr($file, strrpos($file,'.')));
		
		if(!file_exists($path. $file))
		{
			return;
		}
		
		$images = array('.jpg', '.jpeg', '.gif', '.bmp', '.png'); 
		//$flash = array('.swf');
		
		//if( in_array(strtolower($ext), $flash) )
		//{
		//	return displayFlash($params);
		//}	
		if(in_array(strtolower($ext), $images) )
		{
	    	if(isset($ar))
	    	{
	    		$strech = 1;
	    	}
	    	else
	    	{
	    		$strech = 0;		
	    	}
	    	
	    	if(!isset($width))
	    	{
	    		$width = '';
	    	}
	    	
	    	if(!isset($height))
	    	{
	    		$height = '';
	    	}
	    	
	    	if(isset($crop)&&$crop==true)
	    	{
	    		$crop = 'crop';
	    	}
	    	else
	    	{
	    		$crop = 'nocrop';
	    	}
	    	
	    	$fileName = $from . '_' . sha1($name . $width . $height . $strech . $crop . $quality) . $ext;
	    	
	    	/* got static server? */
	    	if(isset($project['static_content_server']) && $project['static_content_server']!='')
	    	{
	    	   $imageurl = $project['static_content_server'] . 'images/' . $fileName;
	    	}
	    	else
	    	{
	    	   $imageurl = WWW . 'cache/images/' . $fileName;
	        }
	    	 
	    	if(!file_exists($cache_path . $fileName))
	    	{
	    		$params['fileName'] = $fileName;
	    		self::GenerateThumbnail($params); 
	    	}
	    	
	    	if(isset($params['urlonly']) && $params['urlonly'] == true)
	    	{
	    		return $imageurl;
	    	}
	    	if(!file_exists($cache_path . $fileName))
	    	{
	    		return false;
	    	}
	    	
			$img = new Imagick($cache_path . $fileName);
			$tag = '<img src="' . $imageurl . '" alt="'.$alt.'" width="'.$geo['width'].'" height="'.$geo['height'].'" />';
			return $tag;
		}
		else
		{
			return false;
		}
		
	}
	
	
	
	public static function GenerateThumbnail($params=array())
	{
	
		$path = PATH . '/content/' . $params['from'] . '/';
		$cache_path = PATH . 'cache/images/';
	 
		$limitedext = array(".gif",".jpg",".jpeg",".bmp");		
	
		$ext = strrchr($params['file'],'.');
		$ext = strtolower($ext);
		
		$fileName = $params['fileName'];
		
		if(!in_array($ext, $limitedext))
		{
			return;
		}
		
		$filePath = $path . $params['file'];
		
		
		$img = new Imagick($filePath);
		if(!$img) { return false; }
		$img->setImageCompressionQuality($params['quality']);
		
		
		if(!isset($params['width']))
		{
			$params['width'] = 0;
		}
		if(!isset($params['height']))
		{
			$params['height'] = 0;
		}
		
		if($params['width']!=0 && $params['height']!=0 && !(isset($params['crop']) && $params['crop']==true))
		{
			//scale image
			if(!isset($params['ar']))
			{	
				$params['ar'] = 1;
			}
			
			$img->scaleImage($params['width'], $params['height'], $params['ar']);
			
		}
		elseif(isset($params['crop']) && $params['crop'] == true && ($params['width']!=0 || $params['height']!=0) )
		{
			//crop image
			$img->cropThumbnailImage($params['width'], $params['height']);
		}
			
		$img->writeImage($cache_path . $fileName);
		
		return true;
	
	}
	
	


}


?>