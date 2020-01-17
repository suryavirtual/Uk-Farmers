<?php
$filepaths = $_REQUEST['path'];
$requestpath=base64_decode($filepaths);
define( '_JEXEC', 1 );
$path = getcwd();
define('JPATH_BASE', $path);
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

/* Create the Application */
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
$session = JFactory::getSession();
$user = JFactory::getUser();
$user = JFactory::getUser();
$usr_id = (int)$user->get('id','0');
if($usr_id)
{
$file = JPATH_BASE.DS.$requestpath;
if (file_exists($file)) 
{
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}
else
{
	  $referer = $_SERVER['HTTP_REFERER'];
		$app =& JFactory::getApplication();
	$app->redirect($referer, JFactory::getApplication()->enqueueMessage('File path not found', 'error'));
}


}
else
{  
JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_users&view=login', "You must be logged in to view this content"));
}
?>