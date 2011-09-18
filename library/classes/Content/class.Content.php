<?php 

class Content
{
	
	public function getTitle()
	{
		return "my title";
	}
	
	
	public function output()
	{
		$template = new Smarty;
		//return $template->fetch('xxxx.tpl');
		return "this is output";
	} 
	
	
	public function readRewrite($rewrite)
	{
		
	
	
	}
	
	
	
	
	
	
	
}


?>