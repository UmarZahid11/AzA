    <?global $config;?>
<form class="cmxform form-horizontal tasi-form" id="fileupload" method="POST" action="<?=$config['base_url']."admin/"."partner_image/upload_images/".$id;?>" enctype="multipart/form-data">
    <div class="form-body">

        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            You have some form errors. Please check below.
        </div>
        <div class="alert alert-success display-hide">
            <button class="close" data-close="alert"></button>
            Your form validation is successful!
        </div>

        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
				<span class="btn green fileinput-button">
				<i class="fa fa-plus"></i>
				<span>
				Add files... </span>
                <input type="hidden" name="partner_image[partner_image_partner_id]" value="<?=$id?>" multiple="">
				<input type="file" name="partner_image[partner_image_name]" multiple="" accept="image/*" />
				</span>
                <button type="submit" class="btn blue start">
                    <i class="fa fa-upload"></i>
				<span>
				Start upload </span>
                </button>
                <button type="reset" class="btn warning cancel">
                    <i class="fa fa-ban-circle"></i>
				<span>
				Cancel upload </span>
                </button>
                <button type="button" class="btn red delete">
                    <i class="fa fa-trash"></i>
				<span>
				Delete </span>
                </button>
                <input type="checkbox" class="toggle">
                <!-- The global file processing state -->
				<span class="fileupload-process">
				</span>
            </div>
            <!-- The global progress information -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;">
                    </div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">
                    &nbsp;
                </div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped clearfix">
            <tbody class="files">
            </tbody>
        </table>

    </div>
</form>


<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger label label-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn blue start" disabled>
                    <i class="fa fa-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn red cancel">
                    <i class="fa fa-ban"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-download fade">
                <td>
                    <span class="preview">
                        {% if (file.thumbnailUrl) { %}
                          <!--  <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery>

                            </a>-->
                            {% if (file.name.substring(file.name.indexOf('.') + 1) == "mp4") { %}
                                   <img src="<?=g('base_url')?>assets/front_assets/images/videoimg.png">
                            {% } else{ %}
                                <img src="{%=file.thumbnailUrl%}">
                            {% } %}
                        {% } %}
                    </span>
                </td>
                <td>
                    <p class="name">
                        {% if (file.url) { %}
                            <a target="_blank" href="{%=file.url%}" title="{%=file.name%}"  {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                        {% } else { %}
                            <span>{%=file.name%}</span>
                        {% } %}
                    </p>
                    {% if (file.error) { %}
                        <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                    {% } %}
                </td>
                <td>
                    <span class="size">{%=o.formatFileSize(file.size)%}</span>
                </td>
                <td>
                    {% if (file.deleteUrl) { %}
                        <button class="btn red delete btn-sm" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                            <i class="fa fa-trash-o"></i>
                            <span>Delete</span>
                        </button>
                        <!--<input type="checkbox" name="delete" value="1" class="toggle">-->
                    {% } else { %}
                        <button class="btn yellow cancel btn-sm">
                            <i class="fa fa-ban"></i>
                            <span>Cancel</span>
                        </button>
                    {% } %}
                    {% if (file.featuredUrl) { %}
                        <!--<button class="btn yellow delete btn-sm" data-type="GET" data-url="{%=file.featuredUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                            <i class="fa fa-pencil"></i>
                            <span>Set As Cover</span>
                        </button>-->
                    {% } else { %}
                        <!--<a href="javascript:void(0)" class="btn green btn-sm">
                            <i class="fa fa-check"></i>
                            <span>Marked As Cover</span>
                        </a>-->
                    {% } %}
                </td>
            </tr>
        {% } %}
    </script>
<script type="text/javascript">
    $(document).ready(function (argument) {
        $('#fileupload').bind('fileuploaddestroy', function (e, data) {
            console.log(e);
            console.log(data);
            location.reload();
        });
    })
</script>