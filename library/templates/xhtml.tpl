<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
{if FAVICON}
<link rel="shortcut icon" type="image/x-icon" href="{$smarty.const.BASE}favicon.ico">
{/if}
<meta http-equiv="Content-Script-Type" content="type" /> 
{if $module->metaDescription!=''}
<meta name="description" content="{$module->metaDescription|escape}" />
{/if}
<title>{tra item=siteTitle group=site}: {foreach from=$module->getTitle() name=title item=item}{$item}{if !$smarty.foreach.title.last} < {/if}{/foreach} {if DEV} [DEV]{/if}</title>
{foreach from=$module->css item=css}
<link rel="stylesheet" href="{$css}" type="text/css" />
{/foreach}
</head>
<body>
{$module->html}
{foreach from=$module->js item=js}
<script type="text/javascript" src="{$js}"></script>
{/foreach}

{if isset($module->project.GA) && $module->project.GA!='' && !DEV}
{literal}
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '{/literal}{$module->project.GA}{literal}']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
{/literal}
{/if}
</body>
</html>