<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

// no direct access
defined('_JEXEC') or die;

//get params
$model = $this->getModel('project');
$extra_fields = json_decode($this->item->extra_fields);
$extra_fields_title = $this->params->get('single_project_extra_fields_title', 1);




if(	!empty($extra_fields->extra_fielda) ||
	!empty($extra_fields->extra_fieldb) ||
	!empty($extra_fields->extra_fieldc) ||
	!empty($extra_fields->extra_fieldd) ||
    !empty($extra_fields->extra_fielde) ||
    !empty($extra_fields->extra_fieldf) ||
    !empty($extra_fields->extra_fieldg) ||
    !empty($extra_fields->extra_fieldh) ||
    !empty($extra_fields->extra_fieldi) ||
    !empty($extra_fields->extra_fieldj)){

?>

<div class="mb2-portfolio-extrafields">
	<?php echo $extra_fields_title == 1 ? '<h3>'. JText::_('COM_MB2PORTFOLIO_EXTRA_FIELDS_TITLE') .'</h3>' : ''; ?>
	<ul class="mb2-portfolio-extrafields-list">   
		<?php          
                                                      
            $extra_fields_array = array(
                array($extra_fields->extra_fielda, $extra_fields->extra_field_valuea, 'a'),
                array($extra_fields->extra_fieldb, $extra_fields->extra_field_valueb, 'b'),
                array($extra_fields->extra_fieldc, $extra_fields->extra_field_valuec, 'c'),
                array($extra_fields->extra_fieldd, $extra_fields->extra_field_valued, 'd'),
                array($extra_fields->extra_fielde, $extra_fields->extra_field_valuee, 'e'),
                array($extra_fields->extra_fieldf, $extra_fields->extra_field_valuef, 'f'),
                array($extra_fields->extra_fieldg, $extra_fields->extra_field_valueg, 'g'),
                array($extra_fields->extra_fieldh, $extra_fields->extra_field_valueh, 'h'),
                array($extra_fields->extra_fieldi, $extra_fields->extra_field_valuei, 'i'),
                array($extra_fields->extra_fieldj, $extra_fields->extra_field_valuej, 'j')
            );
                            
                            
                            
                            
            foreach($extra_fields_array as $extra_field){ 
                                    
                $field = $extra_field[0];
                $value = $extra_field[1];													
                if(!$field){
                    continue;
                }
                                                                
                $model_extra_field = $model->get_extra_field($extra_field[0]);
                                                                
                if($model_extra_field){?>                                                              
              		<li class="mb2-portfolio-extrafield <?php echo $model_extra_field->alias; ?>">
          				<span><?php echo $model_extra_field->title;?>:</span> <?php echo $extra_field[1];?>
                  	</li>                                                              
                <?php														
                }
                
            }//end foreach 
        ?>
    </ul>
</div><!-- end. mb2-portfolio-extrafields -->
<?php
}
?>