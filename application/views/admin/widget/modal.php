<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confirm Delete?</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this <?=ucfirst(str_replace("_", "",$this->router->class));?>?
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">No</button>
                <button id="yes" class="btn btn-danger" type="button">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Success!</h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button onclick="javascript:$('#success').modal('hide');"  class="btn btn-success" type="button">Ok</button>
            </div>
        </div>
    </div>
</div>
  <div class="modal fade" id="error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Error!</h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-danger" type="button">Ok</button>
            </div>
        </div>
    </div>         
  </div>
  <div class="modal fade" id="msg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Message!</h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-danger" type="button">Ok</button>
            </div>
        </div>
    </div>         
  </div>                                

  <div class="modal fade" id="img_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Image Preview!</h4>
            </div>
            <div class="modal-body">
                <img src=""/>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-danger" type="button">Ok</button>
            </div>
        </div>
    </div>         
  </div>