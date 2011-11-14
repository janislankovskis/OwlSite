<div class="form">
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
</div>
{$pages}