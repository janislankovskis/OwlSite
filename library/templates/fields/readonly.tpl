<div class="line">
	<div class="left label">
		<label>{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if}</label>		
	</div>
 	<div class="left readonly">
		<input type="hidden" name="{$field.name|escape}" value="{$field.data|escape}" />
		{$field.data|escape}
	</div>
	<div class="clear"></div>
</div>