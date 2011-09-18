<div class="item trid{$item.id}">
<div class="left">
<form action="{url add="mode=save"}" method="post" onsubmit="save(this); return false;" class="addForm">
		<input type="hidden" name="id" value="{$item.id}" />
		<input type="hidden" name="group" value="{$item.group}"/>
			<div class="field left">Ident: <input type="text" name="ident" value="{$item.ident|escape}" /></div>
			{foreach from=$languages item=x}
				<div class="field left">{$x}: <input type="text" name="values[]" value="{$item.values.$x|escape}" /></div>
			{/foreach}
			<div class="left"><button type="submit">Save</button></div>
			<div class="clear"></div>
	</form>
</div>
<div class="left">
	<form action="{url add="mode=delete"}" method="post" onsubmit="deleteTranslation(this, '{$item.ident|escape}'); return false;">
		<input type="hidden" name="id" value="{$item.id}" />
		<button type="submit">Delete</button>
	</form>
</div>
<div class="clear"></div>

</div>