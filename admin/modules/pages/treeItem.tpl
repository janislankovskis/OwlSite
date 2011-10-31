<li class="treeItem item{$item->id}">
			<div class="opener left">
			<div class="buttons{$item->id}Wrap">
			<div class="hidden data-currentId">{$item->id}</div>
			<div class="hidden data-ajaxLoadUrl">{url add="mode=ajaxload,id=`$item->id`"}</div>
			
				{if $item->hasChildren}
    				{assign var="expanderClass" value=""}
					{assign var="collapserClass" value=" hidden"}
                {else}
                    {assign var="expanderClass" value=" hidden"}
				    {assign var="collapserClass" value=" hidden"}
				    &nbsp;
			 	{/if}
				    <a class="collapser toggler{$collapserClass}" title="Collapse '{$item->name}'" href="{url add="unload=`$item->id`" remove="load"}">
						<img src="images/nolines_minus.gif" alt="-"/>
					</a>
				    
				    <a class="expander toggler{$expanderClass}" href="{url add="load=`$item->id`" remove="unload"}" title="Expand '{$item->name}'">
						<img src="images/nolines_plus.gif" alt="Expand {$item->name}"/>
					</a>
			</div>
			</div>
			<div class="left item"> 
			<a href="{url remove="parent" add="mode=edit,id=`$item->id`"}" title="Edit content '{$item->name|escape}'" class="left itemName{if $item->active} active{/if}">{$item->name|truncate:20:"...":true}</a>
			<a class="add left" title="Create new content under '{$item->name|escape}'" href="{url add="mode=add,parent=`$item->id`" remove="id"}">&nbsp;</a>
			{* <div class="clear"></div> *}
			<div class="children{$item->id}Wrap"></div>
  	 		</div>
  	 		{* <div class="clear"></div> *}
		</li>