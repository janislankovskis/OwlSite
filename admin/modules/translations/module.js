function saveGroup()
{
	var name = jQuery("#newGroup").val();
	if(name == '')
	{
		return;
	}
	
	
	var url = jQuery("#addGroupForm").attr('action') + '&ajax=1';
	
	jQuery.ajax({
		type: "POST",
		data: "newGroup="+name,
		url: url, 
		success: function(out){
			jQuery(out).insertBefore('.bottomGroup');
			jQuery("#newGroup").val("");
		}
	});
	
	return false;
	
}


function deleteGroup(id, name)
{
	if(!confirm('Sure to delete group '+name+'?'))
	{
		return false;
	}
	
	var url = jQuery(".deleteGroup").attr('action') + '&ajax=1';
	
	jQuery.ajax({
		type: "POST",
		data: "id="+id,
		url: url, 
		success: function(out){
			jQuery('.group'+id).slideUp();
		}
	});
	
	
	
	return false;

}


function save(form)
{
	var url = jQuery(".addForm").attr('action') + '&ajax=1';
	var ident = jQuery(form).find('input[name=ident]').val();
	var group = jQuery(form).find('input[name=group]').val();
	var id = jQuery(form).find('input[name=id]').val();
	var values = '';
	jQuery("input[name=values[]]", form).each(function(){
		values = values+'&values[]='+jQuery(this).val();
	});
	
	var data = 'group='+group+'&ident='+ident+values;
	
	if(id)
	{
		data = data + '&id='+id;
	}
	
	jQuery.ajax({
		type: 'POST',
		data: data,
		url: url,
		success: function(out){
			console.log(out);
			jQuery(out).insertBefore('.bottomTranslation');
			
			if(!id)
			{
				jQuery("input[type=text]", form).each(function(){
					jQuery(this).val("");
				});
			}
			
		}
	});
	
	
	return false;
}



function deleteTranslation(form, name)
{
	if(!confirm('Sure to delete translation Ident: '+name+'?'))
	{
		return false;
	}
	
	var id = jQuery(form).find('input[name=id]').val();
	
	var url = jQuery(form).attr('action') + '&ajax=1';
	
	jQuery.ajax({
		type: "POST",
		data: "id="+id,
		url: url, 
		success: function(out){
			jQuery('.trid'+id).slideUp();
		}
	});
	return false;

}

