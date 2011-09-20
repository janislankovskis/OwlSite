<?php

/*
 * TODO: REPLACE, UPDATE 
 */

function  dbExecute($query=null, $class='')
{
    global $conf;
    global $cache;

    if(is_array($query))
    {
        $query = getQuery($query);
    }
    /*
    if($conf['CACHEQUERIES'])
    { 
        if(!isset($cache->readFromCache)) { $cache->readFromCache = 0; } 
        
        //is in cache?
        if(isset($cache->executedQueries[sha1($query)]['result']))
        {   
            $cache->readFromCache++;
            return $cache->executedQueries[sha1($query)]['result'];
            
        }
        
        $time1 = microtime(true); 
            if(!isset($cache->totTime)) { $cache->totTime = 0; } 
            if(!isset($cache->queriesCount)) { $cache->queriesCount = 0; } 
    } 
    */
    $result = mysql_query($query);
    
    $list = GetListFromResource($result, $class);
    
    if($conf['CACHEQUERIES'])
    {
                
    	$time2 = microtime(true);
    	$cache->executedQueries[sha1($query)] = array(
    		'query' => $query,
    		'time' => $time2-$time1,
    		'result' => $list,
    	);
    	
    	$cache->totTime  = $cache->totTime + ($time2-$time1);
    	$cache->queriesCount++;
    }
    
    
    if($error = mysql_error())
    {
    	if(DEV)
    	{
    		echo '<pre>' . $error . '</pre>';
    		echo '<pre>' . $query . '</pre>';
    	}
  
		return;   	
    }
    
    return $list;
      
}


function GetListFromResource($result, $class='')
{
    if(gettype($result) == 'resource')
    {
        $list = array();
        if($class!='')
        {
        	while($f = mysql_fetch_object($result, $class))
        	{
                $list[] = stripslashesObject($f, $class);
        	}
        }
        else
        {
        	while($f = mysql_fetch_assoc($result))
        	{
            	$list[] = $f; 
        	}
        }
        return $list;        
    }
    
    else
    {
        return $result;
    }
    
}


function stripslashesObject($row, $class)
{
	foreach(get_class_vars($class) as $key=>$val)
	{
		if(isset($row->$key))
		{
			if(is_string($row->$key))
			{
				$row->$key = stripslashes($row->$key);
			}
			elseif(is_array($row->$key))
			{
				foreach($row->$key as $x=>$y)
				{
					if(is_string($row->$key))
					{
						$row->$key[$x] = stripslashes($y);
					}
				}
			}
			
		}
	}
	
	return $row;
	
}



function getQuery($parts)
{
    $string = '';
    //select
    if(isset($parts['select']))
    {   
        $string .= 'SELECT ';
    	if(is_array($parts['select']))
        {
            $string .= implode(',', $parts['select']);
        }
        else
        {
            $string .= $parts['select'];
        }
    }
    
    //from
    if(isset($parts['from']))
    {
        if(is_array($parts['from']))
        {
            //recursion !
            $string .= ' FROM (' . getQuery($parts['from']) .  ') as q';
        }
        else
        {
            $string .= ' FROM ' . $parts['from'];
        }
    }
    
    //use index
    if(isset($parts['useIndex']))
    {
        $string .= ' USE INDEX ( ' . $parts['useIndex'] . ' )';
    }
    
    
    //leftjoin
    if(isset($parts['leftJoin']))
    {
        if(is_array($parts['leftJoin']))
        {
            $left = '';
            foreach($parts['leftJoin'] as $item)
            {
                $left .= ' LEFT JOIN ' . $item;
            }
        }
        else
        {
            $left = 'LEFT JOIN ' . $parts['leftJoin'];
        }
        
        $string .= ' ' . $left;
    }
    
    
    
    //where
    if(isset($parts['where']))
    {
        $string .= ' WHERE';
        if(is_array($parts['where']))
        {
            $where = ' ';
            foreach($parts['where'] as $item)
            {
                $where .= '(' . $item . ') AND ';
            }
            
            $where = substr($where, 0, -5);
        }
        else
        {
            $where = $parts['where'];
        }
        
        $string .= ' ' . $where;
    }
    
    //group 
    if(isset($parts['group']))
    {
        
        $string .= ' GROUP BY ' . $parts['group'];
        
    }
    
    
    //order
    if(isset($parts['order']))
    {
        if(is_array($parts['order']))
        {
            $order = ' ';
            foreach($parts['order'] as $item)
            {
                $order .= $item . ',';
            }
            $order = substr($order, 0, -1);
        }
        else
        {
            $order = $parts['order'];
        }
        
        $string .= ' ORDER BY ' . $order;
    }
    
    //limit
    
    if(isset($parts['limit']))
    {
        $string .= ' LIMIT ' . $parts['limit'];
    }
    
    
    return $string;

}



function returnFirstRow($query)
{
    $result = dbExecute($query);
    if(sizeof($result))
    {
        return $result[0];
    }
    else
    {
        return false;
    }
}

function dbReplace($data, $table)
{
	return dbInsert($data, $table, true);
}


function dbInsert($data, $table, $replace=false)
{
    
    if(!$replace)
    {
   	 	$q = 'INSERT INTO ' . $table . ' (';
    }
    
    else
    {
    	$q = 'REPLACE INTO ' . $table . ' (';
    }
    
    //add fields
    $fields = '';
    foreach($data as $key=>$val)
    {
        $fields .= '`' . $key .'`, '; 
    }
    
    $fields = substr($fields, 0, -2);
    
    $q .= $fields . ') VALUES (';
    
    $values = '';
    foreach($data as $key=>$val)
    {
        if(is_numeric($val))
        {
            $values .= $val . ', ';
        }
        elseif($val == '__NOW()__')
        {
             $values .= 'NOW(), ';
        }
        elseif(is_string($val))
        {
            $values .= '"' . mysql_real_escape_string($val) .'", '; 
        }
        
        
    }
    
    $values = substr($values, 0, -2);
    
    $q .= $values . ')';

    dbExecute($q);
    
    return true;
}


function dbDelete($key, $table)
{

	if(!is_array($key) || sizeof($key)!=2)
	{
		return;
	}
	
	if(!is_numeric($key[1]))
	{
		$key[1] = '"' . $key[1] . '"';  
	}
	
	$q = 'DELETE FROM ' . $table . ' WHERE ' . $key[0] .' = ' . $key[1] . ' LIMIT 1';
	dbExecute($q);
	return;
}


function dbGetOneObject($class, $object)
{
	if(!is_numeric($object->id))
	{
		debug('fail');
		return;
	}
	
	$q = array(
		
		'select' 	=> 'c.*',
		'from'		=> $object->tableName . ' c',
		'where'		=> 'c.id = ' . $object->id 
	
	);	
	
	$list = dbGetObjectList($class, $q);
	
	if(!$list)
	{
		return false;
	}
	
	foreach($list as $obj)
	{
		//return first
		return $obj;
	}
	
	
}


function dbGetObjectList($class, $query)
{
	global $conf;
    global $cache;
    
    if(is_array($query))
    {
        $query = getQuery($query);
    }
    
    if($conf['CACHEQUERIES']){ $time1 = microtime(true); }
    
    $result = mysql_query($query);
    
    if($conf['CACHEQUERIES'])
    {
    	$time2 = microtime(true);
    	$cache->DBCache[] = array(
    		'query' => $query,
    		'time' => $time2-$time1,
    	);
    }
    
    
    if($error = mysql_error())
    {
    	if(DEV)
    	{
    		debug ($error, false);
    		debug ($query, false);
    	}
    	
    	//TODO: if possible write error to DB
		return;   	
    }
    
    if(gettype($result) == 'resource')
    {
        $list = array();
        while($f = mysql_fetch_object($result, $class))
        {
            $list[] = $f;
        }
        return $list;
        
    }
    
    else
    {
        return $result;
    }
}


function dbGetId($table)
{
	
	$q = 'SELECT MAX(id) as id FROM ' . $table;
	$r = returnFirstRow($q);
	if(isset($r['id']))
	{
		return $r['id'];
	}
	
	return false;
	
}


function setCachingOff()
{
    global $conf;
    $conf['CACHEQUERIES'] = false;
    return;
}

function setCachingOn()
{
    global $conf;
    $conf['CACHEQUERIES'] = true;
    return;
}


?>al $conf;
    $conf['CACHEQUERIES'] = false;
    return;
}

function setCachingOn()
{
    global $conf;
    $conf['CACHEQUERIES'] = true;
    return;
}


?>