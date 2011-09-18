<div class="line rewriteTool">
    <div class="hidden source">{$field.source}</div>
	<div class="left label">
		<label for="f_{$field.name|escape}">{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if}</label>	
	</div>
	<div class="left">
		<input id="f_{$field.name|escape}" type="text" name="{$field.name|escape}" value="{$field.data|escape}"{if isset($field.readonly) && $field.readonly == true} readonly="readonly"{/if} /><a href="javascript:;"><img src="images/suggest.png" alt="suggest" /></a> {if $field.required}*{/if}
	</div>
	<div class="clear"></div>
</div>