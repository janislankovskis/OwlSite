<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="style/style.css" type="text/css" />
<link rel="stylesheet" href="style/login.css" type="text/css" />
<link rel="stylesheet" href="style/forms.css" type="text/css" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<title>OwlSite Log-in</title>
{foreach from=$module->assign.css item=item}
<link rel="stylesheet" href="{$item}" type="text/css" />
{/foreach}
</head>
<body>

<div class="loginBox">

{if $module->loginAttempt == 'fail'} 
	<div class="error">
		The e-mail or password you entered was incorrect. <br />Please try again.
	</div>
{/if}

<form action="{url}" method="post">
	
	<input type="hidden" name="return" value="{url}" />
	
	<div class="oneLine labels">
		<div class="left first">
			<label for="email">Username / E-mail:</label>		
		</div>
		<div class="left">
			<label for="password">Password:</label>		
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="oneLine fields">
		<div class="left first">
			<input type="text" id="email" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email|escape}{/if}" />		
		</div>
		<div class="left">
			<input type="password" id="password" name="password" />
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="button">
		<button type="submit">Log-in</button>
	</div>
	
	
</form>
<div class="note">
	If you found yourself here by mistake close this page or visit our <a href="{$module->getSiteUrl()}">SITE</a>.</div>
</div>

{foreach from=$module->assign.js item=item}
<script src="{$item}" type="text/javascript"></script>
{/foreach}
<script type="text/javascript" src="{$smarty.const.WWW}/library/3rdpart/jquery/jquery-1.4.2.min.js"></script>
{literal}
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#email').focus();
	});
</script> 
{/literal}
</body>
</html>