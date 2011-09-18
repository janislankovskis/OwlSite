<?php 

class Cache
{
	
	protected $CACHE = array();
	public $DBCache = array();
	
	public function set($variable, $value)
	{
		if($variable == NULL)
		{
			return false;
		}
		
			//TODO: lai toch nekad neatkartojas
			$key = $variable;
			
			if(isset($this->CACHE[$key]))
			{
				$uniqueKeyName = false;
			}	
			else
			{
				$uniqueKeyName = true;
			}
			
			while($uniqueKeyName == false)
			{
				$key = $variable . '_' . substr(sha1(rand()), 0, 5);	
				if(!isset($this->CACHE[$key]))
				{
					 $uniqueKeyName = true;
				}	
			}
			
			$this->CACHE[$key] = $value;
	}
	
	public function get($variable)
	{
		if(!isset($this->CACHE[$variable]))
		{
			return false;
		}
		
		return $this->CACHE[$variable];
	}
	
	
	public function dump($DB=false)
	{
		if($DB)
		{
			debug($this->DBCache, false);
		}
		
		debug($this->CACHE);
	}
	
	
}




?>