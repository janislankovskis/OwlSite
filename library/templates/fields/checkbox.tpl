<div class="line checkbox">
	<div class="left label">
		<label for="f_{$field.name|escape}">{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if} </label>
	</div>
	<div class="left">
		<input class="auto" id="f_{$field.name|escape}" type="checkbox" value="{if isset($field.value)}{$field.value|escape}{else}1{/if}" name="{$field.name|escape}"{if $field.data!='' && $field.data!='0'} checked="checked"{/if} />
	</div>
	<div class="clear"></div>	
</div>