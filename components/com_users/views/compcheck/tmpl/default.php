<?php
/**
* @version 1.0
* @package SalesHistory
* @copyright (C) 2008 Matt Hooker
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
$database = JFactory::getDbo();
$user = JFactory::getUser();
$usr_id = (int)$user->get('id','0');
$comp_id=explode(',',$user->comp_list);
$totalcount=count($comp_id);
if($totalcount>1){
	$com_ids=implode("','",$comp_id);
	$query = "SELECT j1.link_id,j1.link_name,j2.link_id,j2.value from jos_mt_links j1,jos_mt_cfvalues j2 where j1.link_id in('$com_ids') and j1.link_id = j2.link_id and (j2.value = 'Member' || j2.value = 'Supplier') and j1.link_published='1' order by j1.link_name";
    $database->setQuery( $query );
    $rows_existing_comp = $database->loadObjectList();	
}
?>
<div id="supplier_send_terms">
		<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.multiplecomp'); ?>" method="post" name="share" id="share">
		<label>select the company :</label>
		<select name="comp_ids">
		<optgroup label="Members">
		<?php foreach($rows_existing_comp as $comp) : 
				if($comp->value == "Member"){?>
					<option value='<?php echo $comp->link_id;?>'><?php echo $comp->link_name;?></option>
			<?php } endforeach; ?>
		<optgroup label="Suppliers">
		<?php foreach($rows_existing_comp as $comp) : 
				if($comp->value == "Supplier"){?>
					<option value='<?php echo $comp->link_id;?>'><?php echo $comp->link_name;?></option>
			<?php } endforeach; ?>
		</select>
		<br/>
		<input type="submit" name="submit" value="ok">
		</form>
		</div>
			

