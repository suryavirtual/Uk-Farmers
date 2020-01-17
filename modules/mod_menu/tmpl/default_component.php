<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
  $db = JFactory::getDBO();
   $user = JFactory::getUser();
   $usr_id = $user->id;
?>

<?php

// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? 'class="' . $item->anchor_css . '" ' : '';
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';
//echo $item->menu_image;
//echo $item->link;

if ($item->menu_image)
{
	$item->params->get('menu_text', 1) ?
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' . $item->title . '</span> ' :
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
}
else
{
	$linktype = $item->title;
} 
switch ($item->browserNav)
{
	default:
	case 0:
	//if($item->menutype =="member-confidential"  && $item->title=="Member News")
        if(($item->title=="Member News") || ($item->title=="UF News"))
{
	$published_count=array();
	$checked_count=array();
    $exploded_url = explode( "id=", $item->link ); 
    $category_id=$exploded_url[1];

    
  if($usr_id!="")
  {
	$queryTab = "SELECT * FROM #__content where catid='$category_id' and state='1'";
	$db->setQuery( $queryTab );
	$dtls = $db->loadObjectList();
	$db->query();
	foreach ($dtls as  $blog)
	{

		$published_date=$blog->publish_up;
		$jdate= JFactory::getDate();
		if (($jdate->toUnix()-strtotime($published_date)) < (14*86400) ) 
		{
			/*first insert the data into new table */
		$at_id=$blog->id;
		$query1="select * from #__member_news_view where user_id='$usr_id' and at_id='$at_id'";
        $db->setQuery($query1);
        $rows_existing = $db->loadObject();
        $db->query();
        if(empty($rows_existing))
        {
		$query = "insert into #__member_news_view values('','$usr_id','$at_id','0',now(),'')";
		$db->setQuery( $query );
		$db->query();
	    }
			/* insertion code ends here */
		$published_count[]=$blog->id;
		}
    }
   /* checking updated value from jos_member_news_view table */

    foreach ($published_count as $val) 
    {
    	$art_id= $val;
    	$query2="select * from #__member_news_view where user_id='$usr_id' and at_id='$art_id' and status='0'";
        $db->setQuery($query2);
        $rowval=$db->loadObject();
        $attr_id=$rowval->at_id;
        if(!empty($attr_id))
        {
        	$rows_checking[]=$attr_id;
        }
          

    }
  
   ?>
 <?php if(!empty($rows_checking)):?>
    	   
<a id="test"<?php echo $class; ?> data-user="<?php echo $usr_id;  ?>" href="<?php echo $item->flink; ?>" <?php echo $title; ?>><?php echo $linktype.'<sup class="new">new</sup> '; ?></a>

<?php else: ?>
<a <?php echo $class; ?> href="<?php echo $item->flink; ?>" <?php echo $title; ?>><?php echo $linktype; ?></a>	
<?php endif;?>

<?php
	
}
}
else
{
?>
<a <?php echo $class; ?> href="<?php echo $item->flink; ?>" <?php echo $title; ?>><?php echo $linktype; ?></a>
<?php	
}
?>

<?php
		break;
	case 1:
		// _blank
?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" target="_blank" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
		break;
	case 2:
	// Use JavaScript "window.open"
?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;" <?php echo $title; ?>><?php echo $linktype; ?></a>
<?php
		break;
}
