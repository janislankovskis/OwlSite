<form action="{url}" method="get" class="searchForm">
	{if isset($smarty.get.module)}
	<input type="hidden" name="module" value="{$smarty.get.module}" />
	{/if}
	<input type="text" name="_search" value="{if isset($smarty.get._search)}{$smarty.get._search|escape}{/if}" /><button type="submit">Search</button>
</form>