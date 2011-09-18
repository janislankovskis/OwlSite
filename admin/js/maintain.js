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
    console.log(wh);
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