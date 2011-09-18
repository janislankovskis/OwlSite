<ul class="menu">
{defun name=menu menu=$mainMenu}
{foreach from=$menu item=item name=menu}
	<li class="menuItem{if $item->opened} active{/if}{if $smarty.foreach.menu.last} last{/if}"><a href="{$item->getUrl()}">{$item->name}</a>
	{if $item->children}
		<ul class="submenu">{fun name=menu menu=$item->children}</ul>
	{/if}
	</li>
{/foreach}
{/defun}
	<li class="clear">&nbsp;</li>
</ul>