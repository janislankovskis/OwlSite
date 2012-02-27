<?php

//ini_set('display_errors', 1);
include "../../../../../project/config.php";
include "../../../../../library/config.php";
/*
if(!_adminUser::GetUser(false))
{
	redirect(WWW . ADMIN_BASE);
}
*/
?>
<!DOCTYPE html>
<html>
<head>
	<title>Insert images or embed code</title>
	<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script type="text/javascript" src="js/dialog.js"></script>
	<link rel="stylesheet" href="<?php echo WWW; ?>library/3rdpart/html5boilerplate/style.css" type="text/css" /> 
	<style>
	.tabs
	{
		background-color: #e3e3e3;
		margin-bottom: 20px;
		padding: 20px 0 0 20px;
	}
	.tabs a
	{
		float: left;
		padding: 3px 15px;
		
	}
	.wrap
	{
		margin: 0 20px;
	}
	
	.tabs .active
	{
		background-color: #FFF;
	}
	
	.oneLine { margin-bottom: 25px; }
	.oneLine label { width: 80px; display: inline-block; }	
	
	input, textarea { width: 300px; padding: 3px; font-size: 14px; }
	input[type=file] { width: auto; }
	
	</style>
</head>
<body>
<?php

if(isset($_GET['mode']))
{
	$mode = $_GET['mode'];
}
else
{
	$mode = 'image';
}

if(!empty($_POST) && isset($_FILES['image']['error']) && $_FILES['image']['error'] == '0' )
{
	//global $CFG;
	//upload image
	$path = PATH . 'content/uploads/';
	$allow = array('.jpg', '.jpeg');
	
	if(in_array(getSufix($_FILES['image']['name']), $allow))
	{
		
		$newName = generateFileName($_FILES['image']['name']);
	
	
		if(copy($_FILES['image']['tmp_name'], $path . $newName))
		{
			//resize 
			resize($path . $newName);
			$out = WWW . 'content/uploads/' . $newName;		
			//print_r($out);
		}
	
	}

	
}

// function generateFileName($name)
// {
//     $sufix = getSufix($name);
//     $newname = time() . '-' . rand(1000, 9999) . $sufix;
//     return $newname;
// }
    
    
function getSufix($name)
{
   return strtolower(substr($name, strrpos($name,'.')));
}


function resize($filePath)
{
	
	$img_thumb_width = 450; // 
	$quality = 90;
	
	$limitedext = array(".gif",".jpg",".jpeg",".bmp");		
	
	$ext = strrchr($filePath,'.');
	$ext = strtolower($ext);
	
	if(!in_array($ext, $limitedext))
	{
		return;
	}
	
	$info = getimagesize($filePath);
	
	// Get new dimensions
	list($width, $height) = getimagesize($filePath);
	$new_width = 450;
	$new_height = round(450 * $height / $width);
	
	//debug($new_height);
	
	// Resample
	$image_p = imagecreatetruecolor($new_width, $new_height);
	/*
	 * image/gif
	 * image/jpeg
	 * image/png
	 * 
	 * */
	
	switch($info['mime'])
	{
		
		case 'image/jpeg':
			$image = imagecreatefromjpeg($filePath);
		break;

		case 'image/gif':
			$image = imagecreatefromgif($filePath);
		break;
		
		case 'image/png':
	//		$image = imagecreatefrompng($filePath);
		break;
		
		
	}
	
	
	if(!$image) { return; }
	
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	// Save
	imagejpeg($image_p, $filePath, $quality);
	
	return;
}


?> 

<div class="tabs clearfix">
	<a href="?mode=image"<?php if ($mode=='image'){ echo ' class="active"';}?>>Image</a>
	<a href="?mode=embed"<?php if ($mode=='embed'){ echo ' class="active"';}?>>Embed</a>
</div>
<div class="wrap">
<div class="modul"></div>

<?php

if(isset($out) && $out!='')
{

?>


<form onsubmit="ExampleDialog.insert();return false;" action="#">	
	<?php 
		$string = "";
		if(isset($out))
		{
			echo 'ADD image: <br /><img src="' . $out . '" alt="" />';
			$string = '<figure><img src="' . $out . '" ALT="" />'; 
				if (trim($_POST['title'])!='') {
					$string .= '<figcaption>'.strip_tags($_POST['title']).'</figcaption>';
				}
			$string .= '</figure>'; 
		}
	
			
	?>

	<!-- <input id="someval" name="someval" type="textarea" value='' class="text" /> -->
	<div class="oneLine">
	<textarea id="someval2" name="someval2" class="hide"><?php echo $string; ?></textarea>
	<input id="somearg" name="somearg" type="hidden" class="text" />
	<div style="clear:both;"></div>	
	</div>
	<div class="mceActionPanel">
		<button type="submit" id="insert" name="insert" onclick="ExampleDialog.insert();">Insert image</button>
		or <a href="?here">dismiss and upload another image</a>
		<!-- 
		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
		</div>
		-->
	</div>
</form>	

<script type="text/javascript">

		el = document.getElementById('someval2');
		if(el)
		{
			val  = el.value;
			console.log(val);
			console.log(document.getElementById('someval').value);
			document.getElementById('someval').value = val;
			
		}

</script>
	
<!-- </form> -->
<?php
}
else
{

	if($mode == 'image')
	{

		?>



<form action="?here" method="post" enctype="multipart/form-data">
	<input type="hidden" name="upload" value="1" />
	<div class="oneLine">
		<label for="title">Title</label>	
		<input type="text" id="title" name="title" />
	</div>
	
	<div class="oneLine">
		<label for="image">Image</label>	
		<input id="image" type="file" name="image" /> 
	</div>
	
	<button type="submit">Upload image</button>
	
</form>
	


<?php
	}
	elseif($mode == 'embed')
	{
?>		
		<div class="oneLine">
			<textarea name="someval2" id="someval2" cols="40" rows="10"></textarea>
		</div> 
		<button type="submit" onclick="ExampleDialog.insert();">Insert Embed code</button>
		
<?php 		
	}
}
?>

</div>

</body>
</html>