<fieldset class="arrayFieldBlock">
<legend>{$fieldx.name}</legend>
<div{if $filedx.repeat} class="sortable"{/if} id="block_{$fieldx.name}">
{foreach from=$fields item=group name=group}
<div class="arrayfields{if $smarty.foreach.group.last} _blankGroup{/if}">
{if $fieldx.repeat}
<div class="moveDelete">
	<div class="left move">Move</div>
	<div class="right remove"><a href="javascript:;" class="removeField">Remove</a></div>
	<div class="clear"></div>
</div>
{/if}
	{foreach from=$group item=fld}
		{$fld.field}
	{/foreach}
</div>
{/foreach}
<div class="bottom_{$fieldx.name}"></div>
</div>
{if $fieldx.repeat}
<a href="javascript:;" onclick="addGroup('{$fieldx.name}')"  class="addField">+ Add</a>
{/if}
</fieldset>