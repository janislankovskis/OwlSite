<div class="line">
<div class="left label">
{assign var=key value=$field.key}
{assign var=value value=$field.value}
<label for="f_{$field.name|escape}">{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if}</label>
</div>
<div class="left">
<select name="{$field.name}">
	{if isset($field.addBlank) && $field.addBlank==true}<option value="0">&nbsp;</option>{/if}
	{foreach from=$oList item=item}
		<option value="{$item->$key|escape}"{if $item->$key==$field.data} selected="selected"{/if}>
			{if is_array($value)}
				{foreach from=$value item=x}
					{$item->$x|escape}
				{/foreach} 
			{else}
				{$item->$value|escape}
			{/if}
		</option>
	{/foreach} 
</select>
</div>
<div class="clear"></div>
</div>