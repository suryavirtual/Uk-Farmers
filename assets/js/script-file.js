var abc = 0; //Declaring and defining global increement variable
jQuery(document).ready(function() {
	//To add new input file field dynamically, on click of "Add More Files" button below function will be executed
    jQuery('#add_more').click(function() {
        jQuery(this).before(jQuery("<div/>", {id: 'filediv'}).fadeIn('slow').append(
			jQuery("<input/>", {name: 'supplier_files[]', type: 'file', id: 'supplier_files'})
		));
    });
	
	//following function will executes on change event of file input to select different file	
	jQuery('body').on('change', '#supplier_files', function(){
		if (this.files && this.files[0]) {
			abc += 1; //increementing global variable by 1
			
			var z = abc - 1;
            var x = jQuery(this).parent().find('#previewimg' + z).remove();
			jQuery(this).before("<div id='abcd"+ abc +"' class='abcd'><span id='previewimg" + abc + "' >"+this.files[0]['name']+"</span></div>");
			
			var reader = new FileReader();
			reader.onload = imageIsLoaded;
			reader.readAsDataURL(this.files[0]);
			
			jQuery(this).hide();
			jQuery("#abcd"+ abc).append(jQuery("<img/>", {id: 'img', src: 'assets/js/x.png', alt: 'delete'}).click(function() {
				//alert("hiii");
				jQuery(this).parent().remove();
				jQuery("#supplier_files").val('');
				jQuery("#supplier_files").show();
				//location.reload();

			}));
		}
	});
	
	//To preview image     
	function imageIsLoaded(e) {
        jQuery('#previewimg' + abc).attr('src', e.target.result);
    };

    jQuery('#upload').click(function(e) {
        var name = jQuery(":supplier_files").val();
        if (!name)
        {
            alert("First File Must Be Selected");
            e.preventDefault();
        }
    });
});