<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="{$module->language}"> <!--<![endif]-->
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>{tra item=siteTitle group=site}{if sizeof($module->getTitle())}: {foreach from=$module->getTitle() name=title item=item}{$item}{if !$smarty.foreach.title.last} < {/if}{/foreach}{/if} {if DEV} [DEV]{/if}</title>
{if $module->metaDescription!=''}
<meta name="description" content="{$module->metaDescription|escape}" />
{/if}
<meta name="author" content="OWLSite">
{*
<meta name="viewport" content="width=device-width">
<meta name="viewport" content="initial-scale=1.0">
*}
<meta name="viewport" content="width=640"> {* force width to be 640px *}
{if FAVICON}
<link rel="shortcut icon" type="image/x-icon" href="{$smarty.const.BASE}favicon.ico">
{/if}
{foreach from=$module->GetCss() item=css}
<link rel="stylesheet" href="{$css}" />
{/foreach}
<script src="{$smarty.const.BASE}library/3rdpart/modernizr/modernizr.custom.05612.js"></script>
</head>
<body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.js"></script>
<script>window.jQuery || document.write(unescape('%3Cscript src="{$smarty.const.BASE}library/3rdpart/jquery/jquery-1.1.2.min.js"%3E%3C/script%3E'))</script>
{if isset($module->project.loadswfobject) && $module->project.loadswfobject==true}
<script src="{$smarty.const.BASE}library/3rdpart/swfobject/swfobject.js"></script>
{/if}
{$module->html}
<script src="{$smarty.const.BASE}library/3rdpart/html5boilerplate/script.js"></script>
{foreach from=$module->GetJs() item=js}
<script src="{$js}"></script>
{/foreach}
<!--[if lt IE 7 ]>
<script src="{$smarty.const.BASE}library/3rdpart/DD_belatedPNG/DD_belatedPNG_0.0.8a-min.js"></script>
<script>
  DD_belatedPNG.fix('img, .png_bg');
</script>
<![endif]--> 
{if !DEV && isset($module->project.GA) && $module->project.GA!=''}
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