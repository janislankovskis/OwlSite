<div class="line tagTool">
    <script type="text/javascript" src="{$smarty.const.BASE}library/3rdpart/autoSuggestv14/jquery.autoSuggest.minified.js"></script>
    <div class="hidden findedResult"></div>
	<div class="left label">
		<label for="f_{$field.name|escape}">{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if}</label>	
	</div>
	<div class="left relative">
		<input id="f_{$field.name|escape}" type="text" name="{$field.name|escape}" value="{$field.data|escape}"{if isset($field.readonly) && $field.readonly == true} readonly="readonly"{/if} />{if $field.required}*{/if}
		<div class="hidden findedResult"></div>
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript">
{literal}
    jQuery(document).ready(function(){
    
    yepnope(['{/literal}{$smarty.const.BASE}{literal}library/3rdpart/autoSuggestv14/autoSuggest.modif.css']);
    
        
      var data = {items: [
      {/literal}
            {foreach from=$tags item=item}
                {literal}{{/literal}value: "{$item.tagname}", name: "{$item.tagname}"{literal}}{/literal},
            {/foreach}
       {literal} 
        ]};
        jQuery("{/literal}#f_{$field.name|escape}{literal}").autoSuggest(data.items, {selectedItemProp: "name", searchObjProps: "name", startText: "", asHtmlID: "{/literal}{$field.name|escape}{literal}", preFill: {/literal}{$field._tags_field_value}{literal}
        });
    });
{/literal}
</script>