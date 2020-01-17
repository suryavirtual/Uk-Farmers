<?php 
/**
 * ARRA User Export Import component for Joomla! 1.6
 * @version 1.6.0
 * @author ARRA (joomlarra@gmail.com)
 * @link http://www.joomlarra.com
 * @Copyright (C) 2010 - 2011 joomlarra.com. All Rights Reserved.
 * @license GNU General Public License version 2, see LICENSE.txt or http://www.gnu.org/licenses/gpl-2.0.html
 * PHP code files are distributed under the GPL license. All icons, images, and JavaScript code are NOT GPL (unless specified), and are released under the joomlarra Proprietary License, http://www.joomlarra.com/licenses.html
 *
 * file: default.php
 *
 **** class     
 **** functions
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

    JHtml::_('behavior.tooltip');
	JHTML::_('behavior.modal');
	JHtml::_('behavior.formvalidation');
	JHtml::_('bootstrap.tooltip');
	JHtml::_('behavior.multiselect');
	JHtml::_('dropdown.init');
	JHtml::_('formbehavior.chosen', 'select');
	JHtml::_('bootstrap.framework');

    $document = JFactory::getDocument();
    $document->addStyleSheet("components/com_arrausermigrate/css/arra_admin_layout.css");
	$document->addStyleSheet("components/com_arrausermigrate/css/arra_import_layout.css");
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/validations.js");
	
	$groups = $this->groups;
	$rows = $this->rows;
	$columns = 0;
	$letters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "I", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
	$start = JFactory::getApplication()->input->get("start", "1", "raw");
	
	if(isset($rows) && count($rows) > 0){
		foreach($rows as $key=>$line){
			if(count($line) > $columns){
				$columns = count($line);
			}
		}
	}
	
	$separator = JFactory::getApplication()->input->get("separator", ",", "raw");
	$file_name = JFactory::getApplication()->input->get("file_name", "", "raw");
	$start = JFactory::getApplication()->input->get("start", "1", "raw");
?>

<script type="text/javascript" language="javascript">
	function validateStep2(){
		groups = document.getElementById("groups").value;
		if(groups == 0){
			alert("<?php echo JText::_("ARRA_COMPLETE_MARKED"); ?>");
			return false;
		}
		
		username = document.getElementById("username-column").value;
		if(username == 0){
			alert("<?php echo JText::_("ARRA_COMPLETE_MARKED"); ?>");
			return false;
		}
		
		password = document.getElementById("password-column").value;
		if(password == 0){
			alert("<?php echo JText::_("ARRA_COMPLETE_MARKED"); ?>");
			return false;
		}
		return true;
	}
	
	function changeLine(value){
		start = <?php echo intval($start); ?>;
		end = <?php echo intval($start) + 4; ?>;
		
		for(i=start; i<=end; i++){
			if(i == value){
				document.getElementById("row-"+i).className = "active-row";
			}
			else{
				document.getElementById("row-"+i).className = "";
			}
		}
		
		if((value > end) || (value < start)){
			$.ajax({url: "index.php?option=com_arrausermigrate&controller=quick&task=step2&tmpl=component&format=raw&start="+value+"&separator=<?php echo $separator; ?>&file_name=<?php echo $file_name; ?>", success: function(result){
				$("#step_page2").html(result);
			}});
		}
	}
	
	function addNewName(){
		nr_name = document.getElementById("nr-name").value;
		
		select_input = '<div id="div-name'+(parseInt(nr_name) + 1)+'" style="margin-top:5px;"><select class="pull-left" id="name'+(parseInt(nr_name) + 1)+'-column" name="name'+(parseInt(nr_name) + 1)+'-column"><option value="0"> <?php echo JText::_("ARRA_SELECT_COLUMN"); ?> </option><?php for($k=0; $k<$columns; $k++){ echo '<option value="'.$letters[$k].'"> '.$letters[$k].' </option>'; }?></select> <input id="button'+(parseInt(nr_name) + 1)+'-column" type="button" class="btn btn-danger" value="-" onclick="javascript:deleteName(\''+(parseInt(nr_name) + 1)+'\');" /></div>';
		
		document.getElementById("name-zone").innerHTML += select_input;
		document.getElementById("nr-name").value = parseInt(nr_name) + 1;
	}
	
	function deleteName(value){
		element = document.getElementById("div-name"+value);
		element.parentNode.removeChild(element);
		
		nr_name = document.getElementById("nr-name").value;
		document.getElementById("nr-name").value = parseInt(nr_name) - 1;
	}
</script>

<div style="float:left; text-align:left; width:100%;">
    <table style="width:100%;">
    	<tr>
        	<td nowrap="nowrap">
            	<?php echo JText::_("ARRA_USERS_START"); ?>
                <input type="number" id="start" name="start" value="<?php echo $start; ?>" min="1" class="input-mini" onchange="javasript:changeLine(this.value);" />
            </td>
            <td nowrap="nowrap">
            	<?php echo JText::_("ARRA_USER_TYPE"); ?>
                <span style="color:#FF0000; display:inline;">*</span>
                <select id="groups" name="groups">
                	<option value="0"><?php echo JText::_("ARRA_SELECT_ORDER"); ?></option>
                    <?php
                    	if(isset($groups) && count($groups) > 0){
							foreach($groups as $key=>$value){
					?>
                    			<option value="<?php echo $value["id"]; ?>"> <?php echo $value["title"]; ?> </option>
                    <?php
							}
						}
					?>
                </select>
            </td>
            <td nowrap="nowrap">
            	<input type="checkbox" value="1" id="overwrite" name="overwrite" />&nbsp;
            	<?php echo JText::_("ARRA_OVERWRITE_USERS"); ?>
            </td>
        </tr>
    </table>
    
    <br />
    
    <div class="file-content-area">
        <table style="width:100%;" class="preview-table">
            <tr>
                <td nowrap="nowrap">
                    
                </td>
                <?php
                	for($i=0; $i<$columns; $i++){
						echo '<td class="table-value table-value-center"> '.$letters[$i].' </td>';
					}
				?>
            </tr>
            <?php
				$current = $start;
            	foreach($rows as $key=>$row){
					$class = "";
					if($current == $start){
						$class = 'class="active-row"';
					}
			?>
            		<tr id="row-<?php echo $start; ?>" <?php echo $class; ?> >
                    	<td nowrap="nowrap" class="table-value-center"><?php echo $start++; ?></td>
            <?php
					for($k=0; $k<$columns; $k++){
			?>
                        <td class="table-value"><?php if(isset($row[$k])){ echo trim($row[$k]); }; ?></td>
			<?php
					}
			?>
            		</tr>
            <?php
				}
			?>
        </table>
    </div>
    
    <br />
    
    <table style="width:100%;">
		<tr>
        	<td width="20%" nowrap="nowrap" style="padding:2px 5px;" valign="top">
            	<?php echo JText::_("ARRA_NAME"); ?>
                <br />
                <select id="name1-column" name="name1-column" class="pull-left">
                    <option value="0"> <?php echo JText::_("ARRA_SELECT_COLUMN"); ?> </option>
                    <?php
                        for($k=0; $k<$columns; $k++){
                            echo '<option value="'.$letters[$k].'"> '.$letters[$k].' </option>';
                        }
                    ?>
                </select>
                <input type="button" class="btn btn-primary" value="+" onclick="javascript:addNewName();" />
                <div id="name-zone">
                </div>
                <input type="hidden" value="1" id="nr-name" name="nr_name" />
            </td>
            
            <td width="20%" nowrap="nowrap" style="padding:2px 5px;" valign="top">
            	<?php echo JText::_("ARRA_USERNAME"); ?>
                <span style="color:#FF0000; display:inline;">*</span>
                <br />
                <select id="username-column" name="username-column">
                	<option value="0"> <?php echo JText::_("ARRA_SELECT_COLUMN"); ?> </option>
                    <?php
                    	for($k=0; $k<$columns; $k++){
							echo '<option value="'.$letters[$k].'"> '.$letters[$k].' </option>';
						}
					?>
                </select>
            </td>
            
            <td width="20%" nowrap="nowrap" style="padding:2px 5px;" valign="top">
            	<?php echo JText::_("ARRA_EMAIL"); ?>
                <br />
                <select id="email-column" name="email-column">
                	<option value="0"> <?php echo JText::_("ARRA_SELECT_COLUMN"); ?> </option>
                    <?php
                    	for($k=0; $k<$columns; $k++){
							echo '<option value="'.$letters[$k].'"> '.$letters[$k].' </option>';
						}
					?>
                </select>
            </td>
            
            <td width="20%" nowrap="nowrap" style="padding:2px 5px;" valign="top">
            	<?php echo JText::_("ARRA_PASSWORD"); ?>
                <span style="color:#FF0000; display:inline;">*</span>
                <br />
                <select id="password-column" name="password-column">
                	<option value="0"> <?php echo JText::_("ARRA_SELECT_COLUMN"); ?> </option>
                    <?php
                    	for($k=0; $k<$columns; $k++){
							echo '<option value="'.$letters[$k].'"> '.$letters[$k].' </option>';
						}
					?>
                </select>
                <br />
                <input type="radio" value="1" checked="checked" id="encript1" name="encript"> &nbsp; <?php echo JText::_("ARRA_PLAIN"); ?>
                <br />
                <input type="radio" value="2" id="encript2" name="encript"> &nbsp; <?php echo JText::_("ARRA_JOOMLA_ENCRIPTED"); ?>
            </td>
            
            <td width="20%" nowrap="nowrap" style="padding:2px 5px;" valign="top">
            	<?php echo JText::_("ARRA_PARAMS"); ?>
                <br />
                <select id="params-column" name="params-column">
                	<option value="0"> <?php echo JText::_("ARRA_SELECT_COLUMN"); ?> </option>
                    <?php
                    	for($k=0; $k<$columns; $k++){
							echo '<option value="'.$letters[$k].'"> '.$letters[$k].' </option>';
						}
					?>
                </select>
            </td>
    	</tr>
    </table>
</div>
