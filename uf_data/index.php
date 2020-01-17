<?php
define( '_JEXEC', 1 );
//echo dirname(__FILE__);
//echo dirname();
 

define('JPATH_BASE', 'C:\xampp\htdocs\uk_farmers');
//echo JPATH_BASE;

//C:\xampp\htdocs\uk_farmers\uf_data
//C:\xampp\htdocs\uk_farmers\includes
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

/* Create the Application */
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
$session = JFactory::getSession();
$user = JFactory::getUser();
$user = JFactory::getUser();
echo "user id".$usr_id = (int)$user->get('id','0');
print_r($user);
if($usr_id)
{
	echo "<br>";
	echo "user is now logged in ";
	echo "<br>";
}
else
{   echo "<br>";
	echo "user is now logged out in";
	echo "<br>";
	return false;
}
/* Make sure we are logged in at all. */
//if (JFactory::getUser()->id == 0)
//echo JPATH_BASE;

//require_once ( JPATH_BASE. '/includes/defines.php' );
//require_once ( JPATH_BASE. '/includes/framework.php' );
//$mainframe = JFactory::getApplication('site');
//$mainframe->initialise();
//$session = JFactory::getSession();
//print_r($session);
?>