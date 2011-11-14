{* HEADER *}
<div class="heading clearfix">
	<h1 class="left">{$module->getModuleName()}</h1>
	<div class="toolbar left">
		<a class="addButton" href="{url add="mode=edit" remove="sort,direction,id,_search,page"}">Add new</a>
		{if $module->view != 'list' || isset($smarty.get._search)}
		<a class="listButton" href="{url add="mode=list" remove="sort,direction,id,_search,page"}">View all</a>
		{/if}
	</div>
	<div class="right">{$module->getSearch()}</div>
</div>