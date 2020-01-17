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
$project_links = json_decode($this->item->links);

if(	!empty($project_links->linka) ||
	!empty($project_links->linkb) ||
	!empty($project_links->linkc) ||
	!empty($project_links->linkd) ||
	!empty($project_links->linke)){	
?>

<div class="mb2-portfolio-links">  
		<?php											
        	                                           
            $project_links_array = array(
                array($project_links->linka, $project_links->link_valuea, $project_links->link_targeta, $project_links->link_icona, $project_links->link_classa, 'a'),
				array($project_links->linkb, $project_links->link_valueb, $project_links->link_targetb, $project_links->link_iconb, $project_links->link_classb, 'b'),
				array($project_links->linkc, $project_links->link_valuec, $project_links->link_targetc, $project_links->link_iconc, $project_links->link_classc, 'c'),
				array($project_links->linkd, $project_links->link_valued, $project_links->link_targetd, $project_links->link_icond, $project_links->link_classd, 'd'),
				array($project_links->linke, $project_links->link_valuee, $project_links->link_targete, $project_links->link_icone, $project_links->link_classe, 'e')
            );
                            
                            
                            
                            
          foreach($project_links_array as $project_link){ 
                                    
              $url = $project_link[0];
              $text = $project_link[1];
              $target = $project_link[2];
              $icon = $project_link[3];	
			  $class = $project_link[4];
				
																
              if(!$url){
                  continue;
              }
				
				
              ?>                                                             
              		
               	<a class="<?php echo $project_link[4]; ?>" href="<?php echo $project_link[0]; ?>" target="<?php echo $project_link[2]; ?>">
					<?php if($project_link[3] != ''){?>
                      <i class="mb2portfolio-fa <?php echo $project_link[3]; ?>"></i>
                     <?php
                    }
					echo $project_link[1]; ?>
               </a>
                                                                               
                <?php             
        }//end foreach	   
        ?>
    
</div><!-- end. mb2-portfolio-links -->
<?php
 }
?>