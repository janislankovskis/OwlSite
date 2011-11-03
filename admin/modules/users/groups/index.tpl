<h1>{$module->getModuleName()}</h1>
<div class="toolbar">
	<a href="{url add="mode=edit" remove="sort,direction,id"}">Add</a>
	<a href="{url add="mode=list" remove="sort,direction,id"}">List</a>
</div>
<div class="form">
{* -edit- *}
{assign var = direction value='0'}
{if isset($smarty.get.direction) && $smarty.get.direction == '0'}
	{assign var = direction value='1'}
{/if}

{if $module->view == 'form'}
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
{/if}

{* list *}

{if $module->view == 'list'}
	<table border="0" class="objectTable" cellpadding="3" cellspacing="0">
		<thead>
			<tr>
				<td></td>
				{foreach from=$object->getListFields() item=field}
					<td><a href="{url add="sort=`$field`,direction=`$direction`"}">{$field}</a></td>
				{/foreach}
			</tr>
		</thead>
		<tbody>
		{foreach from=$list item=row}
			<tr class="zebra{cycle values="1,0"}">
				<td>
					<a href="{url remove="direction,page,sort" add="mode=edit,id=`$row->id`"}">Edit</a>
					<form class="inline" method="post" action="{url add="mode=delete"}" onsubmit="return confirm('sure?')" >
						<input type="hidden" name="id" value="{$row->id}" />
						<input type="hidden" name="return" value="{url add="mode=list"}" />
						<button type="submit" class="deleteBtn">Delete</button>
					</form>
				</td>
				
				{foreach from=$object->getListFields() item=field}
						<td>
							{$row->getFieldValue($field)}
							{*
							{assign var=value value="`$field`_VALUE"}
							{if isset($row->$value)}
								{$row->$value|stripslashes}
							{elseif $row->$field!='0'}
								{$row->$field|stripslashes}
							{/if}
							*}
						</td>
				{/foreach}
			</tr>
		{/foreach}
		</tbody>
	</table>

{if $pages.total>1}
	{section name=pages loop=$pages.total}
		{assign var=iteration value=$smarty.section.pages.index+1}
		{if $iteration == $pages.current}
			{$iteration}
		{else} 
			<a href="{url add="page=$iteration"}" title="page {$iteration}">{$iteration}</a>
		{/if}
	{/section}
{/if}

{/if}
</div>