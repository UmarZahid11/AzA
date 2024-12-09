<?global $config;?>
<section class="wrapper">
              <!-- page start-->
              <div class="row">
                  <div class="col-lg-12">
                      <section class="panel">
                          <div class="panel-body">
	        <form class="cmxform form-horizontal tasi-form" id="<?=$form_id ?>" method="POST" action="" enctype="multipart/form-data">
	          <div class="form-body">
							            

				<div class="form-group <?=$field_error?>">
						<label class="control-label col-md-2 <?=$label_class ?>" for="<?=$id ?>"> 
								Excel :
						</label>
	              	<div class="<?=$wrap_class?>">
                    	<div class="">
							<div class="uploadfile uploadfile-new" data-provides="uploadfile">
								<div>
									<span class="btn btn-file blue">
										<span class="uploadfile-new"><i class="fa fa-paper-clip"></i> Select File</span>
										<span class="uploadfile-exists"><i class="fa fa-undo"></i> Change</span>
										<input type="file" class="default <?=$field_class?>" name="product_excel" />
									</span>
										<a href="#" class="btn btn-danger uploadfile-exists" data-dismiss="uploadfile"><i class="fa fa-trash"></i> Remove</a>
								</div>
							</div>
						</div>
                    </div>
                </div>
							          			
  				<div class="form-actions">
		            <div class="row">
		              <div class="col-md-offset-3 col-md-9">
		                <button type="submit" class="btn green">Submit</button>
		                <button type="button" class="btn default">Cancel</button>
		              </div>
		            </div>
	          	</div>
	        </form>

	        </div>
          </section>
      </div>
  </div>
  <!-- page end-->
</section>
<script></script>