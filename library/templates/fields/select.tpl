<div class="line">
	<div class="left label">
		<label for="f_{$field.name|escape}">{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if}</label>		
	</div>
	<div class="left">
		<select id="f_{$field.name|escape}" name="{$field.name|escape}"{if $field.onchangesubmit} onchange="form.submit()"{/if}>
		{if isset($field.addBlank) && $field.addBlank==true}<option value="0">&nbsp;</option>{/if}
		{foreach from=$field.list item=item key=key name=selector}<option value="{$key}"{if 
            ($key == $field.data) ||
            (isset($field.prefer) && $field.prefer == 'last' && $smarty.foreach.selector.last) ||
            (isset($field.prefer) && $field.prefer == 'first' && $smarty.foreach.selector.first)
            } selected="selected"{/if}>{$item}</option>{/foreach}
		</select>
	</div>
	<div class="clear"></div>
</div>