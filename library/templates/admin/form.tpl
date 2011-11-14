<div class="form">
<form class="defaultForm" action="{url add="mode=save"}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="return" value="{url add="mode=edit"}" />
	{if $module->doNeedUpperSaveButton()}
		<div class="oneField submit">
			<button type="submit">Save{if isset($smarty.get.id)} changes{/if}</button> <a class="fromCancelLink" href="{url remove="id,mode"}">Cancel</a>
		</div>
	{/if}	
		{foreach from=$module->getForm() name="fields" item=field}
			{assign var=name value=$field.name}
			{if is_array($field.field)}
				<fieldset>
				<legend>{$field.name|escape}</legend>
				<div class="arrayField">
					{foreach from=$field.field item=item}
						<div class="oneLine{if isset($error.$name)} error{/if}">
							{$item.field}
						</div>
					{/foreach}
				</div>
				</fieldset>
			{else}
			<div class="oneLine{if isset($error.$name)} error{/if}">
				{$field.field}
			</div>
			{/if}
		{/foreach}
		
		<div class="oneLine">
			<input class="auto" type="checkbox" id="editOptionsReturn" name="editOptionsReturn" value="{url add="mode=list" remove="id"}"{if isset($smarty.post.editOptionsReturn)} checked="checked"{/if} /> <label for="editOptionsReturn">return to list</label>
		</div>
		
		<div class="oneField submit">
			<button type="submit" class="saveBtn">Save{if isset($smarty.get.id)} changes{/if}</button> <a class="fromCancelLink" href="{url remove="id,mode"}">Cancel</a>
		</div>
	</form>
</div>