<?php 

$content = new Content;

$assign = array(
	'content' => $content,
	'output' => $content->output(),
);

$template->assign($assign);

?>