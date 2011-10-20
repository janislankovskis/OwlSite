<?php
/**
* @category		OwlSite
* @package 		OwlSite Library
*
* @version 1.1
*/


class O_Image
{
	/**
	* @category		OwlSite
	* @package 		OwlSite Library
	* @subpackage	O_Image
	* 
	*
	* Used to resize, crop, cache, dispaly images on the fly
	* Main functionality: O_Image::LoadImage($params);
	*/
	
		
	public static function LoadImage($params=array())
	{
		/**
		* @category		OwlSite
		* @package 		OwlSite Library
		* @subpackage	O_Image
		* 
		* @returns string OR fasle
		* 
		* Used for returning formated img tag ex <img src="path/to/cached/image.jpg" alt="image alt text" widht="int" height="int" /> 
		*	or just url. 
		*
		* $params = array(
		*   'file' 		=> string,
		* 	'from'		=> string,  (folder name in ROOT /content/[folder_name] )
		*	'width'		=> int, [optional] (target width)
		*	'height'	=> int, [optional] (target height)
		*	'crop'		=> bool, [optional] (default false, if true, resizes and crops image to desired width and height dimensions)
		*	'alt'		=> string, [optional] (default is empty)
		*   'urlonly'	=> bool, [optional] (default false, if true, returns only image url ex. "path/to/your/image.jpg" )
		*	'ar'		=> bool, [optional] (default true, if false, aspect ratio of image is ignored )
		*	'quality'	=> int (1-100), [optional] (default 80, sets image quality while generating one)
	 	* );
	 	*
		*/

	
		global $project;
		$params['quality'] = 80;
		extract($params);
		if(!isset($file) || $file=='')
		{
			return false;
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
			return false;
		}
		
		$images = array('.jpg', '.jpeg', '.gif', '.bmp', '.png'); 

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
	    	
			
	    	$geo = getimagesize($cache_path . $fileName);
	    	
			if(!isset($addClass))
			{
				$addClass = '';
			}
			
			$tag = '<img class="' . $addClass . '" src="' . $imageurl . '" alt="'.$alt.'" width="'.$geo[0].'" height="'.$geo[1].'" />';
			return $tag;
		}
		else
		{
			return false;
		}
		
	}
	
	
	
	public static function GenerateThumbnail($params=array())
	{
		/**
		* @category		OwlSite
		* @package 		OwlSite Library
		* @subpackage	O_Image
		* 
		*
		* Used to resize, crop images
		* Main functionality: O_Image::LoadImage($params);
		* @returns bool
		*
		*/
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
		
		if(!extension_loaded("imagick"))
		{
			//fallback to phpThumb
			$params['target'] = $cache_path . $params['fileName'];		
			return self::GeneratePhpThumb($params);
		}
		
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
			//scale
			if(!isset($params['ar']))
			{	
				$params['ar'] = 1;
			}
			
			$img->scaleImage($params['width'], $params['height'], $params['ar']);
			
		}
		elseif(isset($params['crop']) && $params['crop'] == true && ($params['width']!=0 || $params['height']!=0) )
		{
			//crop
			$img->cropThumbnailImage($params['width'], $params['height']);
		}
			
		$img->writeImage($cache_path . $fileName);
		
		return true;
	
	}
	
	public static function GeneratePhpThumb($params)
	{
		/** 
		* @category 	OwlSite
		* @package 		OwlSite Library
		* @subpackage 	O_Image
		* 
		*
		* Used for Imagick fallback
		* uses phpThumb - http://phpthumb.sourceforge.net/
		*/
		require_once( PATH . 'library/3rdpart/phpThumb/phpthumb.class.php');
		
		$phpThumb = new phpThumb();
		$phpThumb->setSourceFilename(PATH . '/content/' . $params['from'] . '/' . $params['file']);
		$phpThumb->setParameter('q', $params['quality']);
		$phpThumb->setParameter('w', $params['width']);
		$phpThumb->setParameter('h', $params['height']);
		if(isset($params['ar']) && $params['ar'] == false){
			$phpThumb->setParameter('iar', '1');
		}
		if(isset($params['crop']) && $params['crop']){
			$phpThumb->setParameter('zc', '1');
		}
		
		if($phpThumb->GenerateThumbnail()){
			if ($phpThumb->RenderToFile($params['target'])) {
				return true;				
			}
		}
		return false;		
	}
	
	
}


?>