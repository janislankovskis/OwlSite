function deletepic(name)
{
	var id = jQuery('input.'+name).val();
	jQuery('.tmp'+name).html(id);
	jQuery('input.'+name).val('0');
	jQuery('.f_'+name+' .imagePlace, .f_'+name+' .imageInputField, .f_'+name+' .deliter').toggleClass('hidden');
}

function undeletepic(name)
{
	var id = jQuery('.tmp'+name).html();
	jQuery('input.'+name).val(id);
	jQuery('.f_'+name+' .imagePlace, .f_'+name+' .imageInputField, .f_'+name+' .deliter').toggleClass('hidden');
}


function addGroup(group)
{
	var el = jQuery('#block_'+group+ ' ._blankGroup').html();
	var string = '<div class="arrayfields">' + el + '</div>';
	jQuery(string).insertBefore('#block_'+group+ ' ._blankGroup');
	refreshFields();
}

function refreshFields()
{
	jQuery('.removeField').click(function deleteField(){
		var el = jQuery(this).parent().parent().parent();
		el.remove();
		return false;
	});
	
	//add sort feature
	jQuery('.sortable').sortable();
	
	//rewrite tool
	jQuery('.rewriteTool a').click(function(){
	   
	   //get source name
	   var parent = jQuery(this).parent().parent();
	   var sourceFieldName = jQuery('.source', jQuery(parent)).html();
	   var value = jQuery('#f_'+sourceFieldName).val();
	   //do rewrite
	   var replace = rewriteMaker(value);
	   jQuery('input[type=text]', jQuery(parent)).val(replace);
	   
	});

	if(jQuery('.rewriteTool'))
	{
	   var sourceFieldName = jQuery('.rewriteTool .source').html();
       jQuery('#f_'+sourceFieldName).keyup(function(){
            value = jQuery(this).val();
            var replace = rewriteMaker(value);
	        jQuery('input[type=text]', jQuery('.rewriteTool')).val(replace);
       });
	}
	
	
}

jQuery(document).ready(function(){
	refreshFields(); 
	jQuery('.defaultForm').submit(function(){
		jQuery('._blankGroup').remove();
		return true;
	});
});



function rewriteMaker(value)
{
        value = value
        .replace(/ /g, '-')
        .replace(/ /g, '-')
        .replace(/ /g, '-')
        .replace(/ /g, '-')
        .replace(/a/g, 'a')
        .replace(/c/g, 'c')
        .replace(/e/g, 'e')
        .replace(/u/g, 'u')
        .replace(/l/g, 'l')
        .replace(/g/g, 'g')
        .replace(/š/g, 's')
        .replace(/c/g, 'c')
        .replace(/n/g, 'n')
        .replace(/i/g, 'i')
        .replace(/ž/g, 'z')
        .replace(/k/g, 'k')
        .replace(/ā/g, 'a')
        .replace(/č/g, 'c')
        .replace(/ē/g, 'e')
        .replace(/ģ/g, 'g')
        .replace(/ī/g, 'i')
        .replace(/ķ/g, 'k')
        .replace(/ļ/g, 'l')
        .replace(/ņ/g, 'n')
        .replace(/š/g, 's')
        .replace(/ū/g, 'u')
        .replace(/ž/g, 'z')
        .replace(/_/g, '-')
        .replace(/\!/g, '')
        .replace(/\?/g, '')
        .replace(/\'/g, '')
        .replace(/\"/g, '')
        .replace(/\:/g, '-')
        .replace(/_/g, '-')
        .replace(/«/g, '')
        .replace(/»/g, '')
        .replace(/—/g, '-')
        .replace(/„/g, '')
        .replace(/”/g, '')
        .replace(/"/g, '')
        .replace(/'/g, '')
        .replace(/,/g, '')
        .replace(/\./g, '')
        .replace(/А/g, 'a')
        .replace(/а/g, 'a')
        .replace(/Б/g, 'b')
        .replace(/б/g, 'b')
        .replace(/в/g, 'v')
        .replace(/В/g, 'v')
        .replace(/Г/g, 'g')
        .replace(/г/g, 'g')
        .replace(/Д/g, 'd')
        .replace(/д/g, 'd')
        .replace(/Е/g, 'e')
        .replace(/е/g, 'e')
        .replace(/Ё/g, 'jo')
        .replace(/ё/g, 'jo')
        .replace(/Ж/g, 'zh')
        .replace(/ж/g, 'zh')
        .replace(/З/g, 'z')
        .replace(/з/g, 'z')
        .replace(/И/g, 'i')
        .replace(/и/g, 'i')
        .replace(/Й/g, 'j')
        .replace(/й/g, 'j')
        .replace(/К/g, 'k')
        .replace(/к/g, 'k')
        .replace(/Л/g, 'l')
        .replace(/л/g, 'l')
        .replace(/М/g, 'm')
        .replace(/м/g, 'm')
        .replace(/Н/g, 'n')
        .replace(/н/g, 'n')
        .replace(/О/g, 'o')
        .replace(/о/g, 'o')
        .replace(/П/g, 'p')
        .replace(/п/g, 'p')
        .replace(/Р/g, 'r')
        .replace(/р/g, 'r')
        .replace(/С/g, 's')
        .replace(/с/g, 's')
        .replace(/Т/g, 't')
        .replace(/т/g, 't')
        .replace(/У/g, 'u')
        .replace(/у/g, 'u')
        .replace(/Ф/g, 'f')
        .replace(/ф/g, 'f')
        .replace(/Х/g, 'h')
        .replace(/х/g, 'h')
        .replace(/Ц/g, 'c')
        .replace(/ц/g, 'c')
        .replace(/Ч/g, 'ch')
        .replace(/ч/g, 'ch')
        .replace(/Ш/g, 'sh')
        .replace(/ш/g, 'sh')
        .replace(/Щ/g, 'sch')
        .replace(/щ/g, 'sch')
        .replace(/Ъ/g, 'j')
        .replace(/ъ/g, 'j')
        .replace(/Ы/g, 'y')
        .replace(/ы/g, 'y')
        .replace(/Ь/g, 'j')
        .replace(/ь/g, 'j')
        .replace(/Э/g, 'e')
        .replace(/э/g, 'e')
        .replace(/Ю/g, 'ju')
        .replace(/ю/g, 'ju')
        .replace(/Я/g, 'ja')
        .replace(/я/g, 'ja')
        .replace(/---/g, '-')
        .replace(/--/g, '-')
        .replace(/\;/g, '')
        .replace(/\)/g, '')
        .replace(/\[/g, '')
        .replace(/\]/g, '')
        .replace(/\(/g, ''); 
        return value;
}