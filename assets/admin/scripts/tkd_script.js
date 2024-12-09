// Admin Script Contains Helpers
var AdminScript = function () {

    return {

        //main function to initiate the module
        init: function () {
            // Right now, nothing to autoload
            //initPickers();
        },

        trim: function (str, chr) {
            var rgxtrim = (!chr) ? new RegExp('^\\s+|\\s+$', 'g') : new RegExp('^' + chr + '+|' + chr + '+$', 'g');
            return str.replace(rgxtrim, '');
        },

        rTrim: function (str, chr) {
            var rgxtrim = (!chr) ? new RegExp('\\s+$') : new RegExp(chr + '+$');
            return str.replace(rgxtrim, '');
        },

        lTrim: function (str, chr) {
            var rgxtrim = (!chr) ? new RegExp('^\\s+') : new RegExp('^' + chr + '+');
            return str.replace(rgxtrim, '');
        },

        moveToTop: function () {
            $(".icon-arrow-up").trigger("click");
        },

    }; // End of class return

}(); // End of AdminScript

var AdminAlert = function () {
    return {

        init: function () {
            return true;
        },

        show: function (message, containerObj, type) {
            var iconType = Array();
            iconType['success'] = "check";
            iconType['warning'] = "warning";
            iconType['danger'] = "warning";
            iconType['info'] = "warning";
            Metronic.alert({
                type: type,
                icon: iconType[type],
                message: message,
                container: containerObj,
                closeInSeconds: 5,
                place: 'prepend'
            });
        },

        success: function (message, containerObj) {
            this.show(message, containerObj, "success");
        },

        failure: function (message, containerObj) {
            this.show(message, containerObj, "danger");
        },

        warning: function (message, containerObj) {
            this.show(message, containerObj, "warning");
        },

        info: function (message, containerObj) {
            this.show(message, containerObj, "info");
        },

    };
}();

var AdminToastr = function () {

    return {

        success: function (msg, title, options) {
            this.show(msg, title, "success", options);
        },

        info: function (msg, title, options) {
            this.show(msg, title, "info", options);
        },

        warning: function (msg, title, options) {
            this.show(msg, title, "warning", options);
        },

        error: function (msg, title, options) {
            this.show(msg, title, "error", options);
        },

        show: function (msg, title, type, options) {

            if (!options) {
                var options = { 'showDuration': 500, "progressBar": true, "preventDuplicates": true, "showEasing": "swing" };
            }

            toastr.options.positionClass = options.positionClass || "toast-bottom-right";

            if (options.showDuration) {
                toastr.options.showDuration = options.showDuration;
            }

            if (options.hideDuration) {
                toastr.options.hideDuration = options.hideDuration;
            }

            if (options.timeOut) {
                toastr.options.timeOut = options.timeOut;
            }

            if (options.extendedTimeOut) {
                toastr.options.extendedTimeOut = options.extendedTimeOut;
            }

            if (options.showEasing) {
                toastr.options.showEasing = options.showEasing;
            }

            if (options.hideEasing) {
                toastr.options.hideEasing = options.hideEasing;
            }

            if (options.showMethod) {
                toastr.options.showMethod = options.showMethod;
            }

            if (options.hideMethod) {
                toastr.options.hideMethod = options.hideMethod;
            }

            var $toast = toastr[type](msg, title); // Wire up an event handler to a button in the toast, if it exists
            $toast.options = options;
            $toastlast = $toast;

            // body...
        },
    };

}();

// Admin Script Contains Helpers
var AjaxRequest = function () {

    var ajaxParams = {};

    return {

        //main function to initiate the module
        init: function () {
            // Right now, nothing to autoload
            return true;
            //initPickers();
        },

        setAjaxParam: function (name, value) {
            ajaxParams[name] = value;
        },

        load: function (url, data, target_obj) {
            response = this.fire(url, data);
            if (response.status == 1) {
                target_obj.html(response.txt);
            }
        },

        fire: function (url, data) {
            var to_return = "";
            reqObj = this;
            Metronic.blockUI("body");

            $.ajax({
                url: url,
                type: "POST",
                data: data,
                async: false,  // Has to be false to be able to return response
                dataType: "json",  // Has to be false to be able to return response
                success: function (response) {
                    to_return = response;
                },
                complete: function (response) {
                    Metronic.unblockUI("body");
                }
            });  // JQUERY Native Ajax End

            this.response = to_return;
            return to_return;

        }, // End of ajaxRequest

    }; // End of class return

}(); // End of AdminScript

// Admin Script Contains Helpers
var FormScript = function () {

    var ajaxParams = {};

    var ajaxPopulate = function (obj) {

        var targets = $("#" + obj.attr("data-target"));
        targets.each(function () {
            var target_obj = $(this);
            console.log(target_obj);
            var params = {};
            params.search_model = target_obj.attr("data-search_model");
            params.search_model_relation = target_obj.attr("data-search_model_relation");
            params.search_key = target_obj.attr("data-search_key");
            params.dd_key = dd_key = target_obj.attr("data-dd_key");
            params.dd_value = dd_value = target_obj.attr("data-dd_value");
            params.search_val = obj.find("option:selected").val();

            var previous_val = target_obj.attr("data-selected");
            var req_uri = target_obj.attr("data-uri") ? target_obj.attr("data-uri") : "ajax";
            var populate_uri = target_obj.attr("data-populate-uri") ? target_obj.attr("data-populate-uri") : "populate";
            var url = $js_config.base_url + "admin/" + req_uri + "/" + populate_uri;

            response = AjaxRequest.fire(url, params);
            target_obj.find("option:gt(0)").remove();
            $(response).each(function (i, arr) {
                option = $("<option>", { "value": arr[dd_key] }).text(arr[dd_value]);
                target_obj.append(option);
                target_obj.removeAttr("data-selected");
            });

            target_obj.select2("val", previous_val);
        });

    }

    var handleMultiSelect = function () {
        if ($('.dd-multiselect').length > 0)
            $('.dd-multiselect').multiSelect({
                selectableOptgroup: true
            });
    }

    var handleColorPicker = function () {
        if (!jQuery().colorpicker) {
            return;
        }
        $('.colorpicker-default').colorpicker({
            format: 'hex'
        });
        $('.colorpicker-rgba').colorpicker();
    }

    // var handleDateTimePicker = function () {
    //     if($(".ddefault-date-picker").length > 0)
    //         $(".ddefault-date-picker").datepicker({format: 'yyyy-mm-dd hh:ii'});
    //     if($(".default-datetime-picker").length > 0)
    //         $(".default-datetime-picker").datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});
    // }

    var handleDateTimePicker = function () {
        if ($(".ddefault-date-picker").length > 0)
            $(".ddefault-date-picker").datepicker({ format: 'yyyy-mm-dd hh:ii' });
        if ($(".default-datetime-picker").length > 0)
            $(".default-datetime-picker").datetimepicker({ format: 'yyyy-mm-dd hh:ii:ss' });

        if ($(".default-date-picker1").length > 0)
            $(".default-date-picker1").datetimepicker({
                format: 'Y-m-d',
                timepicker: false,
            });

        if ($(".default-datetime-picker1").length > 0)
            $(".default-datetime-picker1").datetimepicker({ format: 'Y-m-d H:i' });
    }

    var toggleElement = function () {
        var tglr = $(".toggleElement");
        if (tglr.length > 0) {
            tglr.each(function () {
                var t = $(this);
                toggleTarget = $(t.attr("data-toggle")).closest(".form-group");
                if (!t.bootstrapSwitch('state'))
                    toggleTarget.hide();
            });
            tglr.on('switchChange.bootstrapSwitch', function (event, state) {
                toggleTarget = $($(this).attr("data-toggle")).closest(".form-group");
                toggleTarget.fadeToggle(state);
            });
        }

    }

    var slugify = function () {
        $("body").on("change keyup keydown focus", "[slugify]", function () {
            var value = $(this).val();
            var slug_ele = $($(this).attr("slugify"));
            value = value.replace(/([^a-zA-Z0-9\-_\s]+)/gi, "");
            value = value.replace(/\s+/g, '-');
            slug_ele.val(value);
        });
    }

    return {

        //main function to initiate the module
        init: function () {
            handleMultiSelect();
            handleColorPicker();
            toggleElement();
            slugify();
            handleDateTimePicker();
            var populate_objects = $('.ajax-populate');
            if (populate_objects.length > 0) {
                populate_objects.change(function () {
                    ajaxPopulate($(this));
                });
                populate_objects.each(function () {
                    ajaxPopulate($(this));
                });
            }

        },

        // Validate Form and send data via ajax
        validateAndAjax: function (form, url) {
            var response = {};
            var options = {
                errorClass: "has-error help-block",
                errorElement: "span",
                submitHandler: function (form) {
                    // Submit Handler
                },
            };
            form.validate(options);
            response.valid = form.valid();
            if (response.valid) {
                console.log(form);
                var data = form.serialize();
                console.log(data);
                response.result = AjaxRequest.fire(url, data);
            }
            return response;
        },

        prepareOptions: function (data, key, val) {
            if (data.length) {
                var options = $();
                var i = 0;
                $.each(data, function (ind, dt) {
                    var option = $("<option>");
                    if (!key)
                        key = ind;
                    if (!val)
                        var val = dt;
                    else
                        var val = dt[val];
                    options.push(option.val(key).html(val));
                    i++;
                });
                return options;
            }
        }

    }; // End of class return

}(); // End of AdminScript
var FormFileUpload = function () {


    return {
        //main function to initiate the module
        init: function () {

            // Initialize the jQuery File Upload widget:
            $('#fileupload').fileupload({
                disableImageResize: false,
                autoUpload: false,
                disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
                maxFileSize: 52428800,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png|mp4|mp3|mov)$/i,
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
            });

            // Enable iframe cross-domain access via redirect option:
            $('#fileupload').fileupload(
                'option',
                'redirect',
                window.location.href.replace(
                    /\/[^\/]*$/,
                    '/cors/result.html?%s'
                )
            );

            // Upload server status check for browsers with CORS support:
            if ($.support.cors) {
                $.ajax({
                    type: 'HEAD'
                }).fail(function () {
                    $('<div class="alert alert-danger"/>')
                        .text('Upload server currently unavailable - ' +
                            new Date())
                        .appendTo('#fileupload');
                });
            }

            // Load & display existing files:
            $('#fileupload').addClass('fileupload-processing');
            $.ajax({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url: $('#fileupload').attr("action"),
                dataType: 'json',
                context: $('#fileupload')[0]
            }).always(function () {
                $(this).removeClass('fileupload-processing');
            }).done(function (result) {
                $(this).fileupload('option', 'done')
                    .call(this, $.Event('done'), { result: result });
            });
        }

    };

}();
$(window).on('load', function () {
    hide_preload();
});

$(document).ready(function () {

    // Load Admin Script
    AdminScript.init();
    FormScript.init();
    Layout.init(); // init current layout

});
function hide_preload() {
    $('#preloader').fadeOut(1000, function () {
        // page loaded
    });

}
function show_preload() {
    $('#preloader').fadeIn(100);
}
