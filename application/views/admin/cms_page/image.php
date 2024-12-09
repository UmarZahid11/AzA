<?global $config;
// If Stitched, use qty, else use price
if($form_data[ 'product' ][ 'product_stitched' ] ){
	$qty_price = "qty" ;
}else{
	$qty_price = "price" ;

}
?>

<form class="cmxform horizontal-form tasi-form" 
	id="uploadCmsimage" 
	method="POST" 
	action="<?=$config['base_url']?>admin/cms_page/upload_image" 
>
		<div class="form-body">
            
            <div class="alert alert-danger display-hide">
              <button class="close" data-close="alert"></button>
              You have some form errors. Please check below.
            </div>
            <div class="alert alert-success display-hide">
              <button class="close" data-close="alert"></button>
              Your form validation is successful!
            </div>

        	<input type = "hidden" value="<?=$form_data['cms_page']['cms_page_id']?>" name = "cms_page[cms_page_id]" />

            <div class="row item_set">
	            							        
	            <div class="col-md-2"></div>
        <div class="col-md-4">
            <div class="form-group ">
            <div class="">
            <div data-provides="uploadfile" class="uploadfile uploadfile-new">
            <div style="max-width: 200px; max-height: 150px;" class="uploadfile-new thumbnail">
            <?php
            if(!empty($form_data['cms_page']['cms_page_image'])){
            ?>
            	<img alt="" src="<?=g('base_url')?>assets/uploads/cms_page/<?=$form_data['cms_page']['cms_page_image']?>">
            <?php
            }
            else{
            ?>
            	<img alt="" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image">
            <?php
            }
            ?>
            
            </div>
            <div style="max-width: 200px; max-height: 150px; line-height: 20px;" class="uploadfile-preview uploadfile-exists thumbnail">
            </div>
            <div>
            <span class="btn btn-file blue">
            <span class="uploadfile-new"><i class="fa fa-paper-clip"></i> Select image</span>
            <span class="uploadfile-exists"><i class="fa fa-undo"></i> Change</span>
            <input type="file" name="cms_page[cms_page_image]" class="default">
            </span>
            <a data-dismiss="uploadfile" class="btn btn-danger uploadfile-exists" href="#"><i class="fa fa-trash"></i> Remove</a>
            </div>
            </div>
            </div>
            </div>
        </div>
	           

	        </div>

		</div>

	<div class="form-actions">
	    <div class="row">
	      <div class="col-md-offset-3 col-md-9">
	        <button type="button" class="btn sub-btn green" id="saveImageCms">Save</button>	        
	      </div>
	    </div>
  	</div>
</form>


<script>
	$(document).ready(function() {
			
		$("#saveImageCms").click(function(){

	        var data = new FormData(document.getElementById("uploadCmsimage"));
	        var url = "<?=$config['base_url']?>admin/cms_page/upload_images/";
	        
	        $.ajax({
	            url: url,
	            type: "POST",
	            data: data,
	            dataType: "json",  // Has to be false to be able to return response
	            processData: false,
	            contentType: false,
	            success: function(response) 
	            {
	                if(response.status == 1){
	                   AdminToastr.success( response.message , "Success" );
	                }
	                else{
	                   AdminToastr.error( response.message , "Error" );
	                }
	            }
	        });  // JQUERY Native Ajax End*/
	        return false;
	    });	
		
	});

	
</script>
