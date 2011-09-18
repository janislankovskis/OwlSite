<script type="text/javascript" src="{$smarty.const.BASE}library/3rdpart/jquery/jquery.ui.datetime/jquery.ui.datetime.min.js"></script>
{literal}
<script type="text/javascript">
    jQuery(function() {
        
        yepnope(['{/literal}{$smarty.const.BASE}{literal}library/3rdpart/jquery/ui/css/flick/jquery-ui-1.7.2.custom.css',
                '{/literal}{$smarty.const.BASE}{literal}library/3rdpart/jquery/jquery.ui.datetime/jquery.ui.datetime.css'
                ]);
        
		jQuery("#f_{/literal}{$field.name}{literal}").datetime({
            value: '{/literal}{$field.data|escape}{literal}'
        });
	});
</script>
{/literal}

<div class="line">
	<div class="left label">
		<label for="f_{$field.name|escape}">{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if}</label>		
	</div>
	<div class="left">
		<input id="f_{$field.name|escape}" type="text" name="{$field.name|escape}" value="{$field.data|escape}" />{if $field.required}*{/if}
	</div>
	<div class="clear"></div>
</div>