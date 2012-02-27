{if !$mceLoaded}
	<script type="text/javascript" src="{$path_tiny_mce}"></script>
{literal}
<script type="text/javascript">

	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",

		editor_selector : "richtext",
		
		plugins : "safari,style,table,paste,fullscreen,nonbreaking,owlmedia",
		// Theme options
		theme_advanced_buttons1 : "bold,italic,strikethrough,|,justifyleft,justifycenter,justifyright,|,styleselect,|,paste,pasteword,|,removeformat,cleanup,nonbreaking,code,|,fullscreen",
		theme_advanced_buttons2 : "tablecontrols,|,bullist,numlist,|,link,unlink,anchor,|,owlmedia",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		relative_urls : false ,
        remove_script_host : false,
		 
        content_css : "{/literal}{$smarty.const.BASE}project/templates/textstyles.css{literal}",

        extended_valid_elements: "iframe[align<bottom?left?middle?right?top|class|frameborder|height|id|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style|title|width]"
         
});
	
</script>
{/literal}
{/if}
<div class="line">
	<div class="left label">
		<label for="f_{$field.name|escape}">{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if}</label>
	</div>
	<div class="left">
		<textarea class="richtext" name="{$field.name|escape}" id="f_{$field.name|escape}" rows="10" cols="50">
			{$field.data}
		</textarea>
	</div>
	<div class="clear"></div>
</div>