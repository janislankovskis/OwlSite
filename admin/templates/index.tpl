<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="style/style.css" type="text/css" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<title>OWLSite - {tra item=siteTitle group=site}</title>
{foreach from=$module->assign.css item=item}
<link rel="stylesheet" href="{$item}" type="text/css" />
{/foreach}
<script type="text/javascript" src="{$smarty.const.WWW}library/3rdpart/yepnope/yepnope.1.0.1-min.js"></script>
<script type="text/javascript" src="{$smarty.const.WWW}library/3rdpart/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$smarty.const.WWW}library/3rdpart/jquery/ui/jquery-ui-1.8.6.custom.min.js"></script>
<script type="text/javascript" src="{$smarty.const.WWW}library/3rdpart/swfobject/swfobject.js"></script>
</head>
<body>
<div class="mainWrap">
<div class="upperContainerLine">
	<div class="left">
		<a href="{$smarty.const.ADMIN_WWW}" title="{$smarty.const.ADMIN_WWW}" ><img src="images/owlsite-logo-small.png" alt="home" /></a>
	</div>
	<div class="right">
		<div>Logged is as <b>{$module->loggedUser->email}</b> | <a href="{$module->getSiteUrl()}" target="_blank">View Site</a> | <a href="?logout&amp;return={url}" onclick="return confirm('Sure You want to logout?');">Logout</a></div>
	</div>
	<div class="clear"></div>
	
</div>

<div class="innerWrap">
<div class="menuContainer left">
	<ul id="menu" class="menuBlock">
		{foreach from=$module->menu.library item=item}
			<li class="menuItem{if $item.name==$module->module} active{/if}"><a href="{$smarty.const.ADMIN_WWW}?module={$item.name}">{$item.name}</a></li>
		{/foreach}
	</ul>
	
	<ul id="menu2">
		{foreach from=$module->menu.project item=item}
			<li class="menuItem{if $item.name==$module->module} active{/if}">
				{if !empty($item.sub)}
					<div class="menutitle">{$item.name|strtoupper}</div>
					<ul class="sub">
						{foreach from=$item.sub item=sub}
							<li class="menuItem{if $sub.name==$module->module} active{/if}"><a href="{$smarty.const.ADMIN_WWW}?module={$sub.name}">{$sub.name}</a></li>
						{/foreach}
					</ul>
				{else}
					<a href="{$smarty.const.ADMIN_WWW}?module={$item.name}">{$item.name}</a>
				{/if}
			</li>
		{/foreach}
	</ul>
</div>
<div class="moduleContent left">
{$module->moduleContent}
</div>
<div class="clear"></div>
</div>
</div>
{foreach from=$module->assign.js item=item}
<script src="{$item}" type="text/javascript"></script>
{/foreach}
<script src="{$smarty.const.ADMIN_WWW}js/maintain.js" type="text/javascript"></script>
</body>
</html>