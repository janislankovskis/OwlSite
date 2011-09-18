<h1>{$module->getModuleName()}</h1>
<div class="wrap form">
<div class="tree roundCorners left">
	{if isset($tree)}
	<div class="hidden data-urlBase">{url remove="id,pid,load,unload" add="mode=ajaxload"}&id=</div>
	<ul class="treeGroup rootGroup">
	{defun name="rc" list=$tree}
		{foreach from=$list item=item} 
            <li class="treeItem item{$item->id}">
			<div class="opener left">
			<div class="buttons{$item->id}Wrap">
			<div class="hidden data-currentId">{$item->id}</div>
			<div class="hidden data-ajaxLoadUrl">{url add="mode=ajaxload,id=`$item->id`"}</div>
			{if $item->hasChildren}
				{if sizeof($item->children)}
				    {assign var="expanderClass" value=" hidden"}
				    {assign var="collapserClass" value=""}
				{else}	
				    {assign var="expanderClass" value=""}
				    {assign var="collapserClass" value=" hidden"}
				{/if}
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
			<div class="clear"></div>
			 <div class="children{$item->id}Wrap">
   			{if sizeof($item->children)}
   				<ul class="treeGroup">{fun name="rc" list=$item->children}</ul> 
  	 		{/if}
  	 		</div>
  	 		</div>
  	 		<div class="clear"></div>
		</li>
		{/foreach}	 
	{/defun}	
	</ul>
	{/if}
	
	<div class="addZeroElement"><a href="{url add="mode=edit,id=0,pid=0"}" title="Add root element">Add root object</a></div>
</div>
{if isset($form)}

<div class="left objectOptions">
    
    {if !$form}
    
    <div class="defaultError alert roundCorners shadow">
        Object doesn't exists! <br />
        
        <a href="{url remove="id,pid,load,unload" add="pid=0,mode=edit,id=0"}">Create one!</a>
        
    </div>
   
    {else}
    
	<form class="defaultForm" action="{url add="mode=save"}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="return" value="{url add="mode=edit"}" />
		{if isset($smarty.get.parent)}
			<input type="hidden" name="parent" value="{$smarty.get.parent}" />
		{else}
			<input type="hidden" name="parent" value="{$openedObject->parent}" />
		{/if}
		
		{foreach from=$form item=item}
			
			{assign var=name value=$item.name}
			<div class="formItem{if isset($error.$name)} error{/if}">
				{if is_array($item.field)}
					<fieldset>
						<legend>Object custom data</legend>
						{foreach from = $item.field item=field}
							<div>{$field.field}</div>
						{/foreach}
					</fieldset>
				{else}
				{$item.field}
				{/if}
			</div>
		{/foreach}
		<div class="smallinfo">Content url: {$openedObject->getUrl()}</div>
		<div class="oneField submit">
			<button type="submit" class="saveBtn">Save{if isset($smarty.get.id)} changes{/if}</button> <a class="fromCancelLink" href="{url remove="id,mode"}">Cancel</a>
		</div>
		
	</form>
{if sizeof($module->assign.openedObject->id)}
<div class="deleteObject roundCorners">
	Delete this Content tree.
	<form action="{url add="mode=delete"}" method="post" onsubmit="return confirm('Sure to delete this Content tree?');">
		<input type="hidden" name="return" value="{url remove="id,mode"}" />
		<input type="hidden" name="id" value="{$module->assign.openedObject->id}" />
		<button type="submit" class="deleteBtn">Delete</button>
	</form>
</div>
{/if}
</div>
{/if}
{literal}
<script type="text/javascript">
    jQuery(document).ready(function(){
        expand({/literal}{$module->assign.openedObject->parent}{literal})      
    });
</script>
{/literal}

{/if}
<div class="clear"></div>
</div>