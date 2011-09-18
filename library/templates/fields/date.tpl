{if !$uiDatePicker}
	<script type="text/javascript" src="{$path_jquery_ui}"></script>
	<script type="text/javascript" src="{$path_jquery_datepicker}"></script>
{/if}	
{literal}
<script type="text/javascript">
	jQuery(function() {
	
	    yepnope(['{/literal}{$smarty.const.BASE}{literal}library/3rdpart/jquery/ui/css/flick/jquery-ui-1.7.2.custom.css'
                ]);
	
		jQuery("#f_{/literal}{$field.name}{literal}").datepicker({
			'autosize': true,
			'dateFormat': "yy-mm-dd",
			'firstDay': 1
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