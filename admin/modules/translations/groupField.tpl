<div class="item group{$item.id}">
<form class="deleteGroup" onsubmit="deleteGroup({$item.id}, '{$item.name|escape}'); return false;" action="{url add="mode=deleteGroup"}">
	<div class="left name"><a href="{url add="group=`$item.id`" remove="mode"}">{$item.name|escape}</a></div>
	<div class="delete left"><button type="submit">Delete</button></div>
	<div class="clear"></div>
</form>
</div>