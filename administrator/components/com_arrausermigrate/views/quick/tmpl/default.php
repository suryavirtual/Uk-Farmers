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
	
	$separator = JFActory::getApplication()->input->get("separator", ",", "raw");
?>

<script type="text/javascript" language="javascript">
	function createPageContent(){
		step = document.getElementById("step").value;
		separator = document.getElementById("separator").value;
		file_name = document.getElementById("file_name").value;

		if(step == 2){
			$.ajax({url: "index.php?option=com_arrausermigrate&controller=quick&task=step2&tmpl=component&format=raw&start=1&separator="+separator+"&file_name="+file_name, success: function(result){
				$("#step_page2").html(result);
			}});
		}
		else if(step == 3){
			start = document.getElementById("start").value;
			username = document.getElementById("username-column").value;
			email = document.getElementById("email-column").value;
			password = document.getElementById("password-column").value;
			params = document.getElementById("params-column").value;
			groups = document.getElementById("groups").value;
			overwrite = 0;
			encript = 1;
			
			if(document.getElementById("overwrite").checked){
				overwrite = 1;
			}
			
			if(document.getElementById("encript2").checked){
				encript = 2;
			}
			
			nr_name = document.getElementById("nr-name").value;
			and = "";
			
			for(i=1; i<=nr_name; i++){
				and += "&name[]=" + document.getElementById("name"+i+"-column").value;
			}
			
			$.ajax({url: "index.php?option=com_arrausermigrate&controller=quick&task=step3&tmpl=component&format=raw&start="+start+"&separator=<?php echo $separator; ?>&file_name="+file_name+"&username="+username+"&email="+email+"&password="+password+"&params="+params+"&groups="+groups+"&separator="+separator+"&encript="+encript+"&overwrite="+overwrite+and, success: function(result){
				$("#step_page3").html(result);
				
				$.ajax({url: "index.php?option=com_arrausermigrate&controller=quick&task=step3_import&tmpl=component&format=raw&start="+start+"&separator=<?php echo $separator; ?>&file_name="+file_name+"&username="+username+"&email="+email+"&password="+password+"&params="+params+"&groups="+groups+"&separator="+separator+"&encript="+encript+"&overwrite="+overwrite+and, success: function(result){
					document.getElementById("steps").style.display = "none";
					$("#step_page3").html(result);
				}});
				
			}});
		}
	}
	
	function changePage(type){
		step = document.getElementById("step").value;
		
		if(step == 2 && type == 'increase'){
			result = validateStep2();
			if(!result){
				return false;
			}
		}
		
		if(type == 'increase'){
			step++;
		}
		else if(type == 'decrease'){
			step--;
		}
		document.getElementById("step").value = step;
		
		createPageContent();
		
		for(i=1; i<=3; i++){
			if(i == step){
				document.getElementById("step_page"+i).style.display = "block";
			}
			else{
				document.getElementById("step_page"+i).style.display = "none";
			}
		}
		
		if(step == 1 || step == 3){
			document.getElementById("back-button").style.display = "none";
			
			if(step == 3){
				document.getElementById("next-button").style.display = "none";
			}
		}
		else if(step == 2){
			document.getElementById("back-button").style.display = "";
		}
		
		if(step == 1){
			document.getElementById("quick-progress-1").style.width = "33.33%";
			document.getElementById("quick-progress-2").style.width = "0%";
			document.getElementById("quick-progress-3").style.width = "0%";
		}
		else if(step == 2){
			document.getElementById("quick-progress-1").style.width = "33.33%";
			document.getElementById("quick-progress-2").style.width = "33.33%";
			document.getElementById("quick-progress-3").style.width = "0%";
		}
		else if(step == 3){
			document.getElementById("quick-progress-1").style.width = "33.33%";
			document.getElementById("quick-progress-2").style.width = "33.33%";
			document.getElementById("quick-progress-3").style.width = "33.33%";
		}
	}
</script>

<div class="quick-content">
    <div class="row-fluid">
        <div class="span12 text-center">
            <div id="steps">
                <div class="progress progress-striped active">
                    <div class="bar bar-info" id="quick-progress-1" style="width: 33.33%;"></div>
                    <div class="bar bar-warning" id="quick-progress-2" style="width: 0%;"></div>
                    <div class="bar bar-success" id="quick-progress-3" style="width: 0%;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row-fluid">
        <div class="span12 text-center">
            <div id="pages">
                <div id="step_page1" style="display:block;">
                    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
                    <script src="http://malsup.github.com/jquery.form.js"></script>
                    
                    <?php
                        echo JText::_("ARRA_SEPARATOR").": &nbsp;";
                    ?>
                    <input type="text" value="," class="input-mini" style="font-size: 20px; padding: 10px;" id="separator" name="separator" />
                    
                    <form id="myForm" action="index.php?option=com_arrausermigrate&controller=quick&task=upload&tmpl=component&format=raw" method="post" enctype="multipart/form-data">
                        <input type="file" size="60" name="csvfile">
                        <input type="submit" class="btn btn-success" value="Start Upload">
                    </form>
                    
                    <div id="progress">
                        <div id="bar"></div>
                        <div id="percent">0%</div >
                    </div>
                    
                    <div id="message"></div>
                    
                    <script>
                        $(document).ready(function()
                            {
                                var options = { 
                                beforeSend: function() 
                                {
                                    $("#progress").show();
                                    //clear everything
                                    $("#bar").width('0%');
                                    $("#message").html("");
                                    $("#percent").html("0%");
                                },
                                uploadProgress: function(event, position, total, percentComplete) 
                                {
                                    $("#bar").width(percentComplete+'%');
                                    $("#percent").html(percentComplete+'%');
                                },
                                success: function() 
                                {
                                    $("#bar").width('100%');
                                    $("#percent").html('100%');
                                    document.getElementById("next-button").style.display = "";
                                },
                                complete: function(response) 
                                {
                                    $("#message").html("<font color='green'>Uploaded File: "+response.responseText+"</font>");
                                    $("#file_name").val(response.responseText);
                                },
                                error: function()
                                {
                                    $("#message").html("<font color='red'> ERROR: unable to upload files</font>");
                                }   
                            };
                            $("#myForm").ajaxForm(options);
                        });
                    </script>
                </div>
                
                <div id="step_page2" style="display:none;">
                </div>
                
                <div id="step_page3" style="display:none;">
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="row-fluid">
        <div class="span12 text-center">
            <div id="bar_buttons">
                <input type="button" class="btn btn-inverse" id="back-button" style="display:none;" onclick="javascript:changePage('decrease');" value="<< Back" />
                <input type="button" class="btn btn-inverse" id="next-button" style="display:none;" onclick="javascript:changePage('increase');" value="Next >>" />
                
                <input type="hidden" id="step" name="step" value="1" />
                <input type="hidden" id="file_name" name="file_name" value="" />
            </div>
        </div>
    </div>
</div>