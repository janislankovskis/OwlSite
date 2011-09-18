{*  

    Paginated navigation
    
    First ... X X X current X X X ... Last  
    
    Required input = array('total' => 123, 'current' => 3 );

*}
{if $pages.total>1}
    <div class="paginatorContainer">
    {if $pages.current!='1'}
		  <a href="{url add="page=`$pages.current-1`"}" title="page {$pages.current-1}">&laquo;</a>
    {/if}
    {assign var=skip_start value=false}
    {assign var=can_skip value=true}
    {assign var=skip_end value=false}
	{section name=pages loop=$pages.total}
		{assign var=iteration value=$smarty.section.pages.index+1}
		{if $iteration == $pages.current}
			{$iteration}
            {assign var=can_skip value=true}
		{elseif   $iteration == '1'|| 
		          $iteration==$pages.total || 
		          (($iteration-3 < $pages.current) && $iteration+3 > $pages.current)} 
			<a href="{url add="page=$iteration"}" title="page {$iteration}">{$iteration}</a>
        {elseif !$skip_start}
            ... 
            {assign var=skip_start value=true}
            {assign var=can_skip value=false}
        {elseif !$skip_end && $can_skip}
            ...
            {assign var=skip_end value=true}
            {assign var=can_skip value=false}        
		{/if}
		
	{/section}
	{if $pages.current!=$pages.total}
		  <a href="{url add="page=`$pages.current+1`"}" title="page {$pages.current+1}">&raquo;</a>
    {/if}
    </div>
{/if}