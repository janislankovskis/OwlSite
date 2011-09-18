<?php 

class Email extends ObjectModule
{
	public $tableName = 'sentEmail';

	public $nameFrom, $emailFrom, $subject, $body; 
	
	public $presave = 'calc';
	
	public $fields = array(
	
		'nameFrom' => array(
			'type' => 'text',
			'required' => true,
		),
		'emailFrom' => array(
			'type' => 'text',
			'required' => true,
		),
		'subject' => array(
			'type' => 'text',
			'required' => true,
		),
		'body' => array(
			'type' => 'richtext',
		),
		
		'allData' => array(
			'type'	=> 'hidden',
			'serialize'	=> true,
		),
		 
		'date'	=> array(
			'type'	=> 'autodatetime'
		),
		'ip'	=> array(
			'type'	=> 'autoipaddr'
		),

		
	);
	
	
	public function calc($data)
	{
		$data['allData'] = $GLOBALS;
		
		debug($data);
		return $data;
	}
	
	
	
	public static function send($data, $save=true)
	{
		$mail = new Email();
		$mail->save($data);
		$mail->loadValues();
		debug($mail);
		
		
	}
	
	
	
	
}


?>