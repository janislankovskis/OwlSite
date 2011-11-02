<header>
<h1>{$module->getModuleName()}</h1>
	<div>
		<a class="add" href="{url add="mode=edit,id=0"}" title="Add new">Add new</a>
		{if $module->view == 'form'}
		<a class="all" href="{url remove="id,mode"}" title="view all">View all</a>
		{/if}
	</div>
</header>
<div class="moduleContent form">
{if $module->view == 'form'}
	<h2>{if $module->openedObject->id>0}Edit{else}Create new{/if} page</h2>
	{assign var=fields value=$module->GetForm()}
	<form action="{url add="mode=save"}" method="post" enctype="multipart/form-data" class="clearfix">
		<input type="hidden" name="return" value="{url add="mode=edit"}" />
		<div class="left mainFields">
			<div class="oneLine">
				{$fields.name.field}
			</div>
			{if sizeof($fields.data.field)}
				{$fields.data.field}
			{/if}
		</div>
		<div class="right sysFields">
			<div class="block">
				<h2>Actions</h2>
				<div class="clearfix">
				<button type="submit" class="left">Save{if $module->openedObject->id>0} changes{/if}</button>
				{if $module->openedObject->id>0}
					<a class="left" href="{$module->openedObject->getUrl()}" target="_blank" title="view">View</a>
					<a href="javascript:;" class="deleteLink right">Delete</a>
				{/if}
				</div>
			</div>
			<div class="block rondedCorners">
				<h2>Parameters</h2>
					{$fields.parent.field}
					{$fields.template.field}
					{$fields.ordering.field}
					{$fields.rewrite.field}
					{$fields.showonmenu.field}
					{$fields.active.field}
			</div>
			<div class="block">
				<h2>Meta data</h2>
					{$fields.alt_title.field}
					{$fields.alt_description.field}
			</div>
		</div>
	</form>
	<form action="{url add="id=`$module->openedObject->id`,mode=delete"}" method="post" class="inline deleteForm"><input type="hidden" name="id" value="{$module->openedObject->id}" /><input type="hidden" name="return" value="{url remove="mode,id"}" /></form>
	{literal}				
	<script>
		jQuery(document).ready(function(){
			
			jQuery('.deleteLink').click(function(){
				if(confirm('Sure to delete: {/literal}{$module->openedObject->name|escape}{literal}?'))
				{
					jQuery('.deleteForm').submit();				
				}
				return false;
			})
		});
	</script>
	{/literal}
{else}
	<div class="headTab clearfix">
		<div class="ch left">{*<input type="checkbox" class="masteCh" />*}</div>
		<div class="name left">Page name</div>
		<div class="actions right">actions</div>
		<div class="date right">last saved date</div>
	</div>
	<ul class="list clearfix">
	{defun name=list menu=`$module->assign.list`}
		{foreach from=$menu item=item name=menu}
			<li class="clearfix item{if $item->opened} opened{/if}{if $smarty.foreach.menu.last} last{/if}{if $item->active} active{/if}">
				<div class="clearfix wrap pod{cycle values="1,2"}">
					<div class="ch left">{*<input type="checkbox" name="d[]" value="{$item->id}" class="delCh" />*}</div>
					<a href="{url add="mode=edit,id=`$item->id`"}" class="name">{section name=l loop=$x}<span class="spacer">-</span>{/section}{$item->name}</a>
					<div class="right actions">
						<a href="{url add="mode=edit,id=`$item->id`"}">Edit</a>
						<form action="{url add="id=`$item->id`,mode=delete"}" method="post" class="inline" onsubmit="return confirm('Sure to delete: {$item->name|escape} ?');">
							<input type="hidden" name="id" value="{$item->id}" />
							<input type="hidden" name="return" value="{url remove="mode,id"}" />
							<button type="submit">Delete</button>
						</form>
					</div>
					<div class="right date">{$item->dateSaved|date_format:"%d/%m/%Y %H:%M"}</div>
				</div>
				{if $item->children}
					{assign var=x value=$x+1}
					<ul class="submenu">{fun name=list menu=$item->children}</ul>
					{assign var=x value=$x-1}
				{/if}
			</li>
		{/foreach}
	{/defun}
	</ul>
	{*
	<div class="">With selectd: <a href="dlink">Delete</a></div>
	{literal}
		
	{/}
	*}

{/if}
</div>