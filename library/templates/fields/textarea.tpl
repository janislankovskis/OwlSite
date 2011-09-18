<div class="line">
<div class="left label">
<label for="f_{$field.name|escape}">
{assign var=key value=$field.name}
{if isset($field.label)}
	{assign var=key value=$field.label}
{/if}
{$key}
</label>
</div>
<div class="left">
<textarea name="{$field.name|escape}" id="f_{$field.name|escape}" rows="10" cols="60">{$field.data|escape}</textarea>
{if $field.required}*{/if}
</div>
<div class="clear"></div>
</div>