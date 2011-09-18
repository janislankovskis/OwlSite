<?php 

class ObjectTree extends ObjectModule
{

	public $name, $parentId, $object;
	
	public $tableName = 'objects';
	
	public $dateCreated, $dateModified, $userCreated, $userModified, $remoteAddr;
	
	
	public $fields = array('name', 'parentId', 'object', 'rewrite', 'dateSaved');
	
	
	/* STATIC METHODS */
	
	public static  function FetchChildren($id=0)
	{
		$parts = array();
		$parts['select'][] = 'o.*';
		$parts['from'] = TABLEPREFIX . self::TABLENAME;		
		
		
		
	}
	
	
	//TODO: load fields from xml
	
	
	
	
}



?>