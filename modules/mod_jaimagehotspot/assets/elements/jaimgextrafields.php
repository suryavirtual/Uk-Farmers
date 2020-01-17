<?php
/**
 * ------------------------------------------------------------------------
 * JA Image Hotspot Module for Joomla 2.5 & 3.4
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.form.formfield');

require_once(dirname(__FILE__).'/../behavior.php');

class JFormFieldJaimgextrafields extends JFormField {
	
    protected $type = 'Jaimgextrafields';

	public function getControlGroup()
	{
		if ($this->hidden) {
			return $this->getInput();
		}

		return
			'<div class="control-group control-xfgroup span3">'
			. '<div class="controls">' . $this->getInput() . '</div>'
			. '</div>';
	}
    
	protected function getInput() {
		$description = json_decode($this->value);
		
		/*
		 * Include js and css 
		 * Url define in xml file
		 * */
		$extpath = $this->element['extpath'];
		$doc = JFactory::getDocument();
		$doc->addStyleSheet(JURI::root().$extpath.'/imgextrafields.css');
		
    	/*
    	 * Get extra fields of map from xml file
    	 * Return string
    	 * */
		$html = array();
		
		$html[] = '<div id="extrafieldimg" class="extrafieldimg">';	
		$html[] = '<div id="extrafield-action"><input class="btn btn-primary" type="button" name="'.JText::_("JAI_ADD").'" value="'.JText::_("JAI_ADD").'" id="jai_add" />
				<input type="button" class="btn btn-danger" name="remove" value="'.JText::_("JAI_REMOVE").'" id="jai_remove" style="display:none;" /></div>';

		$htmlpoint = '';
		$maxid = 0;
		if(count($description)>0) {
	    	foreach ($description as $des) {
				$maxid = max($maxid, $des->imgid);
				$css = 'point';
				if(isset($des->ptype) && !empty($des->ptype)) $css .= ' ja-marker-'.$des->ptype;
				if($des->offsety > 100) $des->offsety = 95;
				if($des->offsetx > 100) $des->offsetx = 95;

	    		$htmlpoint .= '<span class="'.$css.'" style="left:'.$des->offsetx.'%;top:'.$des->offsety.'%;" id="ja-marker-'.$des->imgid.'">';
				$htmlpoint .= '<span class="hide">Point</span>';
				$htmlpoint .= '</span>';
	    		//$html[] = $this->getExtrafield($des);
	    	}
		}
    	$script = "
			var desc = jQuery.parseJSON('".addslashes($this->value)."');
			var originalSubmit = Joomla.submitbutton;
    		(function($){
				$(document).ready(function(){
					if(!desc){ desc = [];}
					if($('#jform_params_imgpath_preview_img').css('display') != 'none'){
						$('".$htmlpoint."').appendTo(\"#jform_params_imgpath_preview_img\");
					}else{
						$('#jai_add').hide();
					}
					$('#jai_add').prop('data-count', ".$maxid.");
					
					Joomla.submitbutton = function(task) {
						var imgid = $('#extrafieldimg .adminformlist input[name=\"imgid\"]').val();
						if(imgid) {
							//save pointer setting
							jaiupdate('update', imgid);
						}
						originalSubmit(task);
					}
					
					addpoint = function(){
						if($(\"#jform_params_imgpath_preview_img\").find(\"span.point\").length >0){
							$(\"#jform_params_imgpath_preview_img span.point\").each(function(){
								$(this).bind('click',function(){
									var imgid = $('#extrafieldimg .adminformlist input[name=\"imgid\"]').val();
									if(imgid) {
										//save current setting before load others
										jaiupdate('update', imgid);
									}
									$('#jform_params_imgpath_preview_img span.point').removeClass('active');
									$(this).addClass('active');
									var count = ($(this).data('click_count') || 0) + 1;
									$(this).data('click_count', count);

									for(var i=0;i<desc.length;i++){
										if(desc[i]['imgid'] == $(this).attr('id').replace('ja-marker-','')){
											$('#extrafieldimg .adminformlist :input').each( function(j, field){
												var field_name = field.name.replace('jaform[params][','').replace(']','');
												if(field_name == 'offsetx' || field_name == 'offsety'){
													$(this).prop('autocomplete','off');
													if(count == 1){
														var input_value = desc[i][field_name] >= 100 ? 95 : desc[i][field_name];
														$(this).val(input_value);
													}
												}else{
													var input_type = $(this).prop('type');
													switch(input_type){
														case 'radio':
														case 'checkbox':
															if(desc[i][field_name] == field.value ){
																$(this).attr('checked', true);
															}
															break;
														case 'select-one':
															$(this).val(desc[i][field_name]).trigger('liszt:updated');
															break;
														case 'textarea':
															$(this).val(desc[i][field_name]);
															break;
														case 'button':
															break;
														default:
															$(this).val(desc[i][field_name]);
															if($(this).hasClass('minicolors-input')){
																$(this).minicolors('value',desc[i][field_name]);
															}
															break;
													}
												}
											});
										}
									}

									var pointer_classes = $('#jaform_params_ptype').find('option');
									for(var i=0; i<pointer_classes.length; i++) {
										$(this).removeClass('ja-marker-'+pointer_classes[i].value);
									}
									if($('#jaform_params_ptype').val()) {
										$(this).addClass('ja-marker-'+$('#jaform_params_ptype').val());
									}

									if(count>1){
										$('#jaform_params_offsetx').val(parseFloat($(this).css('left'))*100/parseFloat($('#jform_params_imgpath_preview_img').width()));
										$('#jaform_params_offsety').val(parseFloat($(this).css('top'))*100/parseFloat($('#jform_params_imgpath_preview_img').height()));
									}

									if($('#extrafieldimg .adminformlist #jai_cancel').length >0){
										$('#extrafieldimg .adminformlist #jai_cancel').remove();
									}

									if($('#jform_params_imgpath_preview_img .point.point-add').length >0){
										$('#jform_params_imgpath_preview_img .point.point-add').remove();
									}

									$('#extrafieldimg #jai_remove').show();

									$('#extrafieldimg .adminformlist').removeClass('deactive').addClass('active');


									$(this).draggable({
										cursor: 'move',
										containment: 'parent',
										stop: function() {
											$('#jaform_params_offsetx').val(parseFloat($(this).css('left'))*100/parseFloat($('#jform_params_imgpath_preview').width()));
											$('#jaform_params_offsety').val(parseFloat($(this).css('top'))*100/parseFloat($('#jform_params_imgpath_preview').height()));
											jaiupdate('update',$('#extrafieldimg .adminformlist input[name=\"imgid\"]').val());
										}
									}).addClass('active');;
								});

							});
						}
					};


					addpoint();


					jareset = function(){
						$('#extrafieldimg .adminformlist :input').each( function(j, field){
							var field_name = field.name.replace('jaform[params][','').replace(']','');
							if($(this).prop('id') == 'jaform_params_offsetx' || $(this).prop('id') == 'jaform_params_offsety'){
								$(this).prop('autocomplete','off');
								$(this).val(desc_default[field_name]);
							}else{
								input_type = $(this).prop('type');
								switch(input_type){
									case 'radio':
									case 'checkbox':
										if(desc_default[field_name] == field.value ){
											$(this).prop('checked', true);
										}
										break;
									case 'textarea':
										$(this).val(desc_default[field_name]);
										break;
									case 'button':
										break;
									default:
										$(this).val(desc_default[field_name]);
										break;
								}
						  }
						});
					};

					jaiupdate = function(task,id){
						switch(task){
							case 'add':
								desc_add = new Object();
								$('#extrafieldimg .adminformlist :input').each( function(j, field){
									var input_type = $(this).prop('type');
									var field_name = field.name.replace('jaform[params][','').replace(']','');
									switch(input_type){
										case 'radio':
										case 'checkbox':
											if($(this).prop('checked')){
												desc_add[field_name] = field.value;
											}
											break;
										case 'button':
											break;
										default:
											desc_add[field_name] = field.value;
											break;
									}

								});
								desc.push(desc_add);
								break;
							case 'remove':
								desc = jQuery.grep(desc, function(n, i){
								  return (n.imgid != id);
								});
								break;
							case 'update':
								for(var i=0;i<desc.length;i++){
									if(desc[i]['imgid'] == id){
										$('#extrafieldimg .adminformlist :input').each( function(j, field){
											var input_type = $(this).prop('type');
											var field_name = field.name.replace('jaform[params][','').replace(']','');
											switch(input_type){
												case 'radio':
												case 'checkbox':
													if($(this).prop('checked')){
														desc[i][field_name] = field.value;
													}
													break;
												case 'button':
													break;
												default:
													desc[i][field_name] = field.value;
													break;
											}

										});
									}
								}
								break;
							default:
								break;
						}
						$('#jform_params_description').val(JSON.stringify(desc));
					};

					jaremove = function(){
						$('#extrafieldimg #extrafield-action #jai_remove').click(function(){
							var id = $('#extrafieldimg .adminformlist').find('input[name=\"imgid\"]').val();
							$('#ja-marker-'+id).remove();
							jaiupdate('remove',id);
							jareset();
							$('#extrafieldimg .adminformlist').removeClass('active').addClass('deactive');
							$('#extrafieldimg #jai_remove').hide();
						});
					};
					jaremove();
					$('#jai_add').click(function(){
						if($('#jform_params_imgpath_preview_img').css('display') != 'none'){
							$('#jform_params_imgpath_preview_img span.point').removeClass('active');
							jareset();
							var pointid = $(this).prop('data-count') + 1;
							$(this).prop('data-count', pointid);
							var randTop = 50 + (Math.floor(Math.random() * 90) - 45);
							var randLeft = 50 + (Math.floor(Math.random() * 90) - 45);

							$('#jaform_params_offsetx').val(randLeft);
							$('#jaform_params_offsety').val(randTop);

							$('<span class=\"point\" id=\"adminformlist\" style=\"left:'+randLeft+'%;top:'+randTop+'%;\"><span class=\"hide\">Point</span></span>')
								.appendTo(\"#jform_params_imgpath_preview_img\")
								.addClass('active')
								.attr('id','ja-marker-'+pointid);
							$('#ja-marker-'+pointid).draggable({
								cursor: 'move',
								containment: 'parent',
								stop: function() {
									$('#jaform_params_offsetx').val(parseFloat($(this).css('left'))*100/parseFloat($('#jform_params_imgpath_preview').width()));
									$('#jaform_params_offsety').val(parseFloat($(this).css('top'))*100/parseFloat($('#jform_params_imgpath_preview').height()));
									jaiupdate('update',$('#extrafieldimg .adminformlist input[name=\"imgid\"]').val());
								}
							});


							$('#extrafieldimg .adminformlist input[name=\"imgid\"]').val(pointid);

							$('#extrafieldimg .adminformlist').removeClass('deactive').addClass('active');
							jaiupdate('add',$('#extrafieldimg .adminformlist input[name=\"imgid\"]').val());
							addpoint();
							$('#extrafieldimg #jai_remove').show();
						}
					});
					
					$('#extrafieldimg .adminformlist :input').each( function(j, field){
						if($(this).prop('name') != 'imgid'){
							$(this).change(function(){
								maxwidth = (parseFloat($('#jform_params_imgpath_preview').width()) - parseFloat($('#jform_params_imgpath_preview_img span.point').width()))*100/parseFloat($('#jform_params_imgpath_preview').width());
								maxheight = (parseFloat($('#jform_params_imgpath_preview').height()) - parseFloat($('#jform_params_imgpath_preview_img span.point').height()))*100/parseFloat($('#jform_params_imgpath_preview').height());
								maxwidth = Math.floor(maxwidth);
								maxheight = Math.floor(maxheight);

								imgid = $('#extrafieldimg .adminformlist input[name=\"imgid\"]').val();
								if($(this).prop('id') == 'jaform_params_ptype'){
									var active_pointer = $('#jform_params_imgpath_preview_img span.active');
									if(active_pointer.length) {
										var pointer_classes = $(this).find('option');
										for(var i=0; i<pointer_classes.length; i++) {
											active_pointer.removeClass('ja-marker-'+pointer_classes[i].value);
										}
										if($(this).val()) {
											active_pointer.addClass('ja-marker-'+$(this).val());
										}
									}
								}
								if($(this).prop('id') == 'jaform_params_offsetx'){

									if(isNaN($(this).val())){
										alert('".JText::_('JAI_INSERT_NUMBERIC', true)."');
										return;
									}
									if($(this).val() > maxwidth){
										$(this).val(maxwidth);
										alert('".JText::_('JAI_INSERT_NUMBERIC_LESS_THAN', true)."'+maxwidth);
									}
									if($(this).val() < 0){
										$(this).val(0);
										alert('".JText::_('JAI_INSERT_NUMBERIC_GREATER_THAN', true)."');
									}
									if($(this).parent().parent().find('input[name=\"imgid\"]').length > 0){
										imgidchange = $(this).parent().parent().find('input[name=\"imgid\"]').val();
										if($('#ja-marker-'+imgidchange)){
											$('#ja-marker-'+imgidchange).css('left',$(this).val()+'%');
										}
									}
								}
								if($(this).prop('id') == 'jaform_params_offsety'){
									if(isNaN($(this).val())){
										alert('".JText::_('JAI_INSERT_NUMBERIC', true)."');
									}
									if($(this).val() > maxheight){
										$(this).val(maxheight);
										alert('".JText::_('JAI_INSERT_NUMBERIC_LESS_THAN', true)."'+maxheight);
									}
									if($(this).val() < 0){
										$(this).val(0);
										alert('".JText::_('JAI_INSERT_NUMBERIC_GREATER_THAN', true)."');
									}
									if($(this).parent().parent().find('input[name=\"imgid\"]').length > 0){
										imgidchange = $(this).parent().parent().find('input[name=\"imgid\"]').val();
										if($('#ja-marker-'+imgidchange)){
											$('#ja-marker-'+imgidchange).css('top',$(this).val()+'%');
										}
									}
								}
								jaiupdate('update',imgid);
							});
						}
					});


					$('#jaform_params_offsetx').on('keydown', function (event) {
						maxwidth = (parseFloat($('#jform_params_imgpath_preview').width()) - parseFloat($('#jform_params_imgpath_preview_img span.point').width()))*100/parseFloat($('#jform_params_imgpath_preview').width());
						maxwidth = Math.floor(maxwidth);

						if (event.which == 38 || event.which == 104) {
							if((parseFloat($(this).val())+1) > maxwidth){
								$(this).val(maxwidth - 1);
								alert('".JText::_('JAI_INSERT_NUMBERIC_LESS_THAN', true)."'+maxwidth);
							}

							$('#jaform_params_offsetx').val((parseInt($('#jaform_params_offsetx').val()) + 1));

							imgidchange = $(this).parent().parent().find('input[name=\"imgid\"]').val();
							if($('#ja-marker-'+imgidchange)){
								$('#ja-marker-'+imgidchange).css('left',$(this).val()+'%');
							}

							jaiupdate('update',$('#extrafieldimg .adminformlist input[name=\"imgid\"]').val());

						} else if (event.which == 40 || event.which == 98) {

							if((parseFloat($(this).val())-1) < 0){
								$(this).val(0);
								alert('".JText::_('JAI_INSERT_NUMBERIC_GREATER_THAN', true)."');
							}

							$('#jaform_params_offsetx').val((parseInt($('#jaform_params_offsetx').val()) - 1));
							imgidchange = $(this).parent().parent().find('input[name=\"imgid\"]').val();
							if($('#ja-marker-'+imgidchange).length >0){
								$('#ja-marker-'+imgidchange).css('left',$(this).val()+'%');
							}

							jaiupdate('update',$('#extrafieldimg .adminformlist input[name=\"imgid\"]').val());
						}
					});

					$('#jaform_params_offsety').on('keydown', function (event) {
						maxheight = (parseFloat($('#jform_params_imgpath_preview').height()) - parseFloat($('#jform_params_imgpath_preview_img span.point').height()))*100/parseFloat($('#jform_params_imgpath_preview').height());
						maxheight = Math.floor(maxheight);

						if (event.which == 38 || event.which == 104) {
							if((parseFloat($(this).val())+1) > maxheight){
								$(this).val(maxheight - 1);
								alert('".JText::_('JAI_INSERT_NUMBERIC_LESS_THAN', true)."'+maxheight);
							}

							$('#jaform_params_offsety').val((parseInt($('#jaform_params_offsety').val()) + 1));

							imgidchange = $(this).parent().parent().find('input[name=\"imgid\"]').val();

							if($('#ja-marker-'+imgidchange)){
								$('#ja-marker-'+imgidchange).css('top',$(this).val()+'%');
							}

							jaiupdate('update',$('#extrafieldimg .adminformlist input[name=\"imgid\"]').val());

						} else if (event.which == 40 || event.which == 98) {
							if((parseFloat($(this).val())-1) < 0){
								$(this).val(0);
								alert('".JText::_('JAI_INSERT_NUMBERIC_GREATER_THAN', true)."');
							}

							$('#jaform_params_offsety').val((parseInt($('#jaform_params_offsety').val()) - 1));


							imgidchange = $(this).parent().parent().find('input[name=\"imgid\"]').val();

							if($('#ja-marker-'+imgidchange)){
								$('#ja-marker-'+imgidchange).css('top',$(this).val()+'%');
							}
							jaiupdate('update',$('#extrafieldimg .adminformlist input[name=\"imgid\"]').val());
						}
					});
					$('#jform_params_imgpath_preview').click(function(){
						$('#jform_params_imgpath_preview_img span.point').removeClass('active');
						jareset();
						$('#extrafieldimg .adminformlist').removeClass('active').addClass('deactive');
						$('#extrafieldimg #jai_remove').hide();
					});
				});
			 })(jQuery);";
    	 	
    	$doc->addScriptDeclaration($script);
    	
    	$html[] = $this->getExtrafield();
    	
    	$html[] = '</div>';
		/*
		 * Show input add position
		 * */
    	
    	
    	$html[] = '<textarea style="display: none;" rows="6" cols="60" name="' . $this->name . '" id="' . $this->id . '" >'. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') .'</textarea>';
		
		//$script = implode('', $script);
    	
    	return implode("\n", $html);
    }
    public function getExtrafield($obj = NULL){
  		/*
  		 * Check case null
  		 * */
    	$checkobj = true;
    	if(!isset($obj)){
    		$checkobj = false;
    		$obj = new stdClass();
    		$obj->id = '';
    	} 	
    	
    	/*
    	 * Get extra fields of map from xml file
    	 * Return string
    	 * */
		$html = array();
		$html[] = '<ul class="adminformlist'.$obj->id.' deactive">';		
    	$extraXml = dirname(__FILE__) . '/jaimgextrafields/imgextrafields.xml';
    	if(file_exists($extraXml)){   	
    		$options = array('control' => 'jaform');
			$paramsForm = JForm::getInstance('jform', $extraXml, $options);		
			$fieldSets = $paramsForm->getFieldsets('params');
			foreach ($fieldSets as $name => $fieldSet) :
				if (isset($fieldSet->description) && trim($fieldSet->description)){
					$html[] = '<p class="tip">'.JText::_($fieldSet->description).'</p>';
				}				
				$hidden_fields = '';
				$desdefault = array();
				foreach ($paramsForm->getFieldset($name) as $field) :
					$fieldname = $field->fieldname;
					$desdefault[$field->fieldname] = $field->value;
					if(!$checkobj){
			    		$obj->$fieldname = $field->value?$field->value:'';
			    	} 	
					if (!$field->hidden):
						$html[] = '<li>';
						$html[] = $paramsForm->getLabel($field->fieldname,$field->group);
						$html[] = $paramsForm->getInput($field->fieldname,$field->group,$obj->$fieldname); 
						$html[] = '</li>';
					else : 
						$hidden_fields .= $paramsForm->getInput($field->fieldname,$field->group,$obj->$fieldname);	
					endif;
				endforeach;
				$html[] = $hidden_fields; 
			endforeach;			
    	} 
    	$desdefault = json_encode($desdefault);
    	if(!$checkobj){
    		$html[] = '<li>'.
    				  '<input type="hidden" name="imgid" value=""></li>';
    	}else {
    		$html[] = '<li><input type="button" class="btn btn-mini btn-danger" name="remove" value="'.JText::_('JAI_REMOVE', true).'"></li>';
    	}	
    	$html[] = '</ul>';
    	$html[] = '<script type="text/javascript">'
    			  .'var desc_default= jQuery.parseJSON("'.addslashes($desdefault).'");'
    			  .'</script>';
    	return implode("\n", $html);
    }
	
}