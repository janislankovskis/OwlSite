<h1>{$module->getModuleName()}</h1>

<div  class="left groups">
	
	<div class="group">
		<h2>Groups</h2>
		{foreach from=$groups item=item}
			{include file="groupField.tpl"}
		{/foreach}
		<div class="bottomGroup"></div>
	</div>
	
	<div class="group">
		<form action="{url add="mode=saveGroup" escaped=false}" method="post" onsubmit="saveGroup(); return false;" id="addGroupForm">
			<fieldset>
				<legend>Add New Group</legend>
				<div class="field left"><input type="text" name="name" id="newGroup" /></div>
				<div class="left"><button type="submit">Save</button></div>
				<div class="clear"></div>
			</fieldset>
		</form>
	</div>
	
</div>

<div class="left translations">
	<h2>{$currentgroup.name}</h2>
	{foreach from=$translations item=item}
		{include file="translationField.tpl"}
	{/foreach}
	<div class="bottomTranslation"></div>
	<form action="{url add="mode=save"}" method="post" onsubmit="save(this); return false;" class="addForm">
		<input type="hidden" name="group" value="{$currentgroup.id}"/>
		<fieldset>
			<legend>Add New Translation</legend>
			<div class="field left">Ident: <input type="text" name="ident" id="newGroup" /></div>
			{foreach from=$languages item=x}
				<div class="field left">{$x}: <input type="text" name="values[]" /></div>
			{/foreach}
			<div class="left"><button type="submit">Save</button></div>
			<div class="clear"></div>
		</fieldset>
	</form>
</div>

<div class="clear"></div> 