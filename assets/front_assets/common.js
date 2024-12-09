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
            if (options.progressBar) {
                toastr.options.progressBar = options.progressBar;
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
        }
    };

}();

var AjaxRequest = function () {

    return {
        init: function () {
            return true;
        },
        load: function (url, data, target_obj) {
            response = this.fire(url, data);
            if (response.status == 1) {
                target_obj.html(response.txt);
            }
        },
        fire: function (url, data) {
            var to_return = "";

            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: false,
                dataType: "json",
                success: function (response) {
                    if (response.status == 0) {
                        AdminToastr.error(response.txt, 'Error');
                    } else if (response.status == 1) {
                        AdminToastr.success(response.txt, 'Success');
                    }
                    to_return = response;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function () {
                    Loader.show();
                },
                complete: function() {
                    Loader.hide();
                }
            });
            return to_return;
        },
        formrequest: function (url, data, async = false) {
            var to_return = "";

            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: async,
                dataType: "json",
                success: function (response) {
                    to_return = response;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                complete: function (jqXHR, textStatus) {
                    Loader.hide();
                    if (textStatus > 300) {
                        console.log(textStatus + ": " + jqXHR.status);
                    }
                },
                beforeSend: function () {
                    Loader.show();
                },
            });

            return to_return;
        },
        asyncRequest: function (url, data, showLoader = true, buttonElement = '', buttonTextBeforeSend = '', buttonTextAfterSend = '') {
            return new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    async: true,
                    dataType: "json",
                    success: function (response) {
                        resolve(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function () {
                        if(buttonElement) {
                            $(buttonElement).attr('disabled', true)
                            $(buttonElement).html(buttonTextBeforeSend)
                        } else {
                            if(showLoader) {
                                Loader.show();
                            }
                        }
                    },
                    complete: function() {
                        if(buttonElement) {
                            $(buttonElement).attr('disabled', false)
                            $(buttonElement).html(buttonTextAfterSend)
                        } else {
                            if(showLoader) {
                                Loader.hide();
                            }
                        }
                    }
                });
            });
        },
        fileAsyncRequest: function (url, data, showLoader = true, buttonElement = '', buttonTextBeforeSend = '', buttonTextAfterSend = '') {
            return new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    enctype: 'multipart/form-data',
                    async: true,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (response) {
                        resolve(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function () {
                        if(buttonElement) {
                            $(buttonElement).attr('disabled', true)
                            $(buttonElement).html(buttonTextBeforeSend)
                        } else {
                            if(showLoader) {
                                Loader.show();
                            }
                        }
                    },
                    complete: function() {
                        if(buttonElement) {
                            $(buttonElement).attr('disabled', false)
                            $(buttonElement).html(buttonTextAfterSend)
                        } else {
                            if(showLoader) {
                                Loader.hide();
                            }
                        }
                    }
                });
            });
        },
    };
}(); // End of AjaxRequest

var Loader = function () {
    return {
        show: function () {
            jQuery("#preloader").show();
        },
        hide: function () {
            jQuery("#preloader").hide();
        }
    };
}();

function showLoader() {
    jQuery("#preloader").show();
}
function hideLoader() {
    jQuery("#preloader").hide();
}