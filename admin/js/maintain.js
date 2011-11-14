jQuery(window).load(function(){
	/*
	var v1  = jQuery('.menuContainer').width();
	var v2  = jQuery('.moduleContent').width();
    console.log(v1);
    console.log(v2);
    rez = 33;
    var sum = rez + v1 + v2;
    
    console.log(sum);
    
	
	var normal = jQuery('.upperContainerLine').width();
	
	if(sum < normal)
	{
		sum = normal;
	}
	
	jQuery('.innerWrap').width(sum);
    */
    
    resizecont();

	
});


jQuery(window).resize(resizecont);


function resizecont()
{
    
    wh = jQuery(window).width() - jQuery('.menuContainer').width() - 50; //50 = paddings, borders	
    jQuery('.moduleContent, .paginatorContainer').width(wh);
    

    mh = jQuery('.menuContainer').height();
    ch = jQuery('.moduleContent').height();
    
    if(ch > mh)
    {
        jQuery('.menuContainer').css('height', ch+'px');
    }
    
    wh = (jQuery(window).height() - jQuery('.upperContainerLine').height() - 40);
        mh = jQuery('.menuContainer').height();
    if(mh < wh)
    {
        jQuery('.menuContainer').css('height', wh+'px');
    }
    if(ch < wh)
    {
        jQuery('.moduleContent').css('height', wh+'px');
    }
    

}

(function(){
            
            jQuery('.menutitle').click(function(){
                toggleItem(jQuery('.data', jQuery(this)).text());
            });

            function toggleItem(id)
            {
                var el = jQuery('#g_'+id);
                if(!el) { return; }
                if(jQuery(el).css('display') == 'none')
                {
                    jQuery(el).css('display', 'block'); 
                    addKey(id);
                }
                else
                {
                    jQuery(el).css('display', 'none');
                    delKey(id);
                }
                loadKeys();
            }

            function addKey(id)
            {
                var arr = new Array();
                if(localStorage.openedStr)
                {
                    arr  = localStorage.openedStr.split(',');
                }
                arr.push(id);
                localStorage.openedStr = arr.join(',');
            }
                
            function delKey(id)
            {
                var arr  = localStorage.openedStr.split(',');
                var save = new Array();
                jQuery(arr).each(function(item){
                    if(arr[item]!=id)
                    {
                        save.push(arr[item]);
                    }
                });
                localStorage.openedStr = save.join(',');
            }
                
            function loadKeys()
            {
                if(!localStorage.openedStr) { return; }
                var arr = localStorage.openedStr.split(',');
                jQuery(arr).each(function(item){
                    var el = jQuery('#g_'+arr[item]);
                    if(el) { jQuery(el).css('display', 'block'); }
                });
            }
            loadKeys();

        })();