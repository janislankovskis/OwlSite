//load session storage
if(sessionStorage.treeState)
{    
    if(sessionStorage.treeState!='false')
    {
        //jQuery('.treeGroup').html(sessionStorage.treeState);
    }
}

init();

function expand(id)
{
    if(id!=0)
    {
        el = jQuery('.item'+id);
        url = jQuery('.data-ajaxLoadUrl', el).first().text();
        jQuery.get(
            url, function(data){
                
                if(data == 'false') {
                    return false;
                }
                
                jQuery('.children'+id+'Wrap').html(data);                           
                    saveState();
                    init();
        });
    }
    else
    {
        url = jQuery('.data-urlBase').text() + '0';
        jQuery.get(
            url, function(data){
                if(data == 'false') {
                    return false;
                }
                    jQuery('.rootGroup').html(data);                           
                    saveState();
                    init();
        });
    }

    return false;
    
}

function saveState()
{    
    sessionStorage.setItem('treeState', jQuery('.treeGroup').html());
    return false;
}
var x = 1;


function init()
{

    jQuery('.collapser').unbind('click');
    jQuery('.expander').unbind('click');
    
    
    jQuery('.collapser').click(function(){
        
        el = jQuery(this).parent().parent();
        id = jQuery('.data-currentId', el).text();
        if(!id)
        {
            return false;
        }
          
        //clean
        jQuery('.children'+id+'Wrap').html('');
        
        //toggle buttons
        jQuery('.buttons'+id+'Wrap .toggler').toggle();
        
        saveState();
        init();
            
        return false;
    });


    jQuery('.expander').click(function(){
        
        el = jQuery(this).parent().parent();
        url = jQuery('.data-ajaxLoadUrl', el).text();
        id = jQuery('.data-currentId', el).text();
           
        if(url==='')
        {
            return false;
        }
        
        jQuery.get(
            url, function(data){
                if(data == 'false') {
                    return false;
                }
                jQuery('.children'+id+'Wrap').html(data);                
                    //toggle buttons
                    jQuery('.buttons'+id+'Wrap .toggler').toggle();
                    saveState();
                    init();
            }
        );
        
        return false;
    });


}
