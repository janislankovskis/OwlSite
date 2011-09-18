<div class="f_{$field.key} line">
<div class="left label">
<label for="f_{$field.name|escape}">
	{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if}
</label>
</div>
<div class="left">
<div class="hidden tmp{$field.key}"></div>
<input type="hidden" name="{$field.name|escape}" class="{$field.key}" value="{$field.data}" />
<input type="file" name="{$field.file_field_name}" id="f_{$field.file_field_name}" class="imageInputField{if $field.value!=''} hidden{/if}" />
{assign var = file value=$object->getAttachment($field.data)}
{if $file}
	<span class="imagePlace"><a href="{$file.fullUrl}">{$file.name|escape}</a></span> 
	<a href="javascript:;" onclick="deletepic('{$field.key}')" class="deliter">Delete</a>
	<a href="javascript:;" onclick="undeletepic('{$field.key}')" class="deliter hidden">Undelete</a>
	<div class="imagePlace">
	{display file=$file.name from=$file.dir width=100 height=100}
	</div>
{/if}
</div>
<div class="clear"></div>
</div>