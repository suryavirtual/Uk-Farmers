<?php
	$slider = new RevSlider();
	$arrSliders = $slider->getArrSliders();
	$arrSlidersTemplates = $slider->getArrSliders(true);
	
	$addNewLink = self::getViewUrl(RevSliderAdmin::VIEW_SLIDER);
	$addNewTemplateLink = self::getViewUrl(RevSliderAdmin::VIEW_SLIDER_TEMPLATE);

	$permission = RevOperations::getPermission(GlobalsRevSlider::PERMISSION_SLIDER_OPERATIONS);
	$permission_setting = RevOperations::getPermission(GlobalsRevSlider::PERMISSION_SLIDER_SETTINGS);
	
	require self::getPathTemplate("sliders");
?>


	