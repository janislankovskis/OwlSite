<div class="line">
	<div class="left label">
		<label for="f_{$field.name|escape}">{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if}</label>		
	</div>
	<div class="left">
		<input id="f_{$field.name|escape}" type="text" name="{$field.name|escape}" value="{if $field.data=='' && isset($field.default) && is_null($field.object->id)}{$field.default}{else}{$field.data|escape}{/if}"{if isset($field.readonly) && $field.readonly == true} readonly="readonly"{/if}{if isset($field.maxlength)} maxlength="{$field.maxlength}"{/if} />{if $field.required}*{/if}
	</div>
	<div class="clear"></div>
</div>