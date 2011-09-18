<div class="line">
<div class="left label">
<label for="f_{$field.name|escape}">{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if}</label>
</div>
<div class="left">
<input id="f_{$field.name|escape}" type="password" name="{$field.name|escape}" value="" />{if $field.required}*{/if}
</div>
<div class="clear"></div>
</div>
<div class="line">
<div class="left label">
<label for="f_{$field.name|escape}_repeat">{if isset($field.label)}{$field.label|escape}{else}{$field.name|escape}{/if} repeat</label>
</div>
<div class="left">
<input id="f_{$field.name|escape}_repeat" type="password" name="{$field.name|escape}_repeat" value="" />{if $field.required}*{/if}
</div>
<div class="clear"></div>
</div>