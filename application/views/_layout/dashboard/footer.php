<script>
    // topbar profile search
    $(document).ready(function() {

        $('body').on('keyup', 'input[name=searchDataKeyword]', function() {
            let search = $(this).val();
            let output_div = ".search_result";
            $.ajax({
                url: base_url + 'dashboard/custom/getSearchData',
                data: {
                    '_token': $('meta[name=csrf-token]').attr("content"),
                    'search': search
                },
                dataType: "html",
                type: "POST",
                success: function(response) {
                    $(output_div).empty().html(response);
                }
            });
        });

        // $(".autoComplete").autocomplete({
        //    source: base_url + 'dashboard/custom/profile_search',
        //    select: function(event, ui) {
        //       event.preventDefault();
        //       $(".autoComplete").attr('data-value', ui.item.id);
        //       $(".autoComplete").val(ui.item.value);
        //    }
        // });

        // $('.search').on('submit', function() {
        //    window.location.href = base_url + 'dashboard/custom/search-redirect/' + convertToSlug($('input[name=search]').data('value'));
        // })

        function convertToSlug(Text) {
            return Text.toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, '');
        }
    })
</script>

<script>
    // ============================================
    // As of Chart.js v2.5.0
    // http://www.chartjs.org/docs
    // ============================================
    // NOT USED
    try {
        var chart = document.getElementById('chart').getContext('2d'),
            gradient = chart.createLinearGradient(0, 0, 0, 450);

        gradient.addColorStop(0, '#faad3ecc');
        gradient.addColorStop(0.5, '#faad3e70');
        gradient.addColorStop(1, '#faad3e00');


        var data = {
            labels: ['January', 'February', 'March', 'April', 'May'],
            datasets: [{
                label: 'Value',
                backgroundColor: gradient,
                pointBackgroundColor: 'white',
                borderWidth: 3,
                borderColor: '#faad3e',
                data: [40, 60, 45, 50, 63]
            }]
        };


        var options = {
            responsive: true,
            maintainAspectRatio: true,
            animation: {
                easing: 'easeInOutQuad',
                duration: 520
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: '#fff',
                        lineWidth: 1
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: '#ddd',
                        lineWidth: 1
                    }
                }]
            },
            elements: {
                line: {
                    tension: 0.4
                }
            },
            legend: {
                display: false
            },
            point: {
                backgroundColor: 'white'
            },
            tooltips: {
                titleFontFamily: 'montserrat',
                backgroundColor: '#faad3e',
                titleFontColor: '#fff',
                caretSize: 5,
                cornerRadius: 2,
                xPadding: 10,
                yPadding: 10
            }
        };


        var chartInstance = new Chart(chart, {
            type: 'line',
            data: data,
            options: options
        });
    } catch (Exception) {}
</script>
<script>
    // ============================================
    // As of Chart.js v2.5.0
    // http://www.chartjs.org/docs
    // ============================================
    // NOT USED
    try {
        var chart = document.getElementById('chart2').getContext('2d'),
            gradient = chart.createLinearGradient(0, 0, 0, 450);

        gradient.addColorStop(0, '#e46567db');
        gradient.addColorStop(0.5, '#e4656770');
        gradient.addColorStop(1, '#e4656700');


        var data = {
            labels: ['January', 'February', 'March', 'April', 'May'],
            datasets: [{
                label: 'Value',
                backgroundColor: gradient,
                pointBackgroundColor: 'white',
                borderWidth: 3,
                borderColor: '#e46567',
                data: [40, 50, 45, 60, 63]
            }]
        };


        var options = {
            responsive: true,
            maintainAspectRatio: true,
            animation: {
                easing: 'easeInOutQuad',
                duration: 520
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: '#fff',
                        lineWidth: 1
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: '#ddd',
                        lineWidth: 1
                    }
                }]
            },
            elements: {
                line: {
                    tension: 0.4
                }
            },
            legend: {
                display: false
            },
            point: {
                backgroundColor: 'white'
            },
            tooltips: {
                titleFontFamily: 'montserrat',
                backgroundColor: '#e46567',
                titleFontColor: '#fff',
                caretSize: 5,
                cornerRadius: 2,
                xPadding: 10,
                yPadding: 10
            }
        };


        var chartInstance = new Chart(chart, {
            type: 'line',
            data: data,
            options: options
        });
    } catch (Exception) {}
</script>

<script>
    // ==== //
    // USED //
    // ==== //
    $(document).ready(function() {
        try {
            if (document.getElementById("barChart")) {
                var ctx = document.getElementById("barChart").getContext('2d');
                var barChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Monday'],
                        datasets: [{
                            label: '',
                            fill: false,
                            backgroundColor: "#d2d6db",
                            borderColor: "#d2d6db",
                            data: [0.7],
                        }, {
                            label: '',
                            fill: false,
                            backgroundColor: "#58595b",
                            borderColor: "#58595b",
                            data: [3],
                        }, {
                            label: '',
                            fill: false,
                            backgroundColor: "#3a3a3b",
                            borderColor: "#3a3a3b",
                            data: [5],
                        }, {
                            label: '',
                            fill: false,
                            backgroundColor: "#290038",
                            borderColor: "#290038",
                            data: [7],
                        }, {
                            label: '',
                            fill: false,
                            backgroundColor: "#8204aa",
                            borderColor: "#8204aa",
                            data: [9],
                        }],
                    }
                });
            }
        } catch (Exception) {
            console.log(Exception.message)
        }
    })
</script>

<script>
    function readURL(input, selector) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                selector.css('background-image', 'url(' + e.target.result + ')');
                selector.hide();
                selector.fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    // company profile upload
    $("#profileUpload").change(function() {
        var file_obj = $(this);
        var ext = file_obj.val().split('.').pop().toLowerCase();
        $('input[name=signup_company_image]').val(file_obj.val())
        if (ext != '') {
            if ($.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) {
                file_obj.val('');
                AdminToastr.error('Extension not allowed');
            } else {
                readURL(this, $('#imagePreview'));
                var data = new FormData(document.getElementById('form-profile-image'));
                updateCompanyImageAjax(data, '<?= g('base_url') ?>dashboard/company/update_image').then(
                    function(response) {
                        if (response.status == 0) {
                            AdminToastr.error(response.txt, 'Error');
                        } else if (response.status == 1) {
                            AdminToastr.success(response.txt, 'Success');
                        }
                    }
                )
            }
        }
    });

    // profile upload
    $("#imageUpload").on('change', function() {

        var file_obj = $(this);
        var ext = file_obj.val().split('.').pop().toLowerCase();

        $('input[name=signup_logo_image]').val(file_obj.val())
        if (ext != '') {
            if ($.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) {
                file_obj.val('');
                AdminToastr.error('Extension Not allowed');
            } else {
                readURL(this, $('#imagePreview'));
                var data = new FormData(document.getElementById('form-profile-image'));
                updateProfileImageAjax(data, '<?= g('base_url') ?>dashboard/profile/update_image').then(
                    function(response) {
                        if (response.status == 0) {
                            AdminToastr.error(response.txt, 'Error');
                        } else if (response.status == 1) {
                            AdminToastr.success(response.txt, 'Success');
                        }
                    }
                )
            }
        }
    });

    function makeBold() {
        document.execCommand("bold");
        if (document.getElementById("bold").isToggled) {
            // document.getElementById("bold").style.backgroundColor = "#00cc55";
            document.getElementById("bold").isToggled = false;
        } else {
            // document.getElementById("bold").style.backgroundColor = "#008833";
            document.getElementById("bold").isToggled = true;
        }
    }

    function makeItalic() {
        document.execCommand("italic");
        if (document.getElementById("italic").isToggled) {
            // document.getElementById("italic").style.backgroundColor = "#00cc55";
            document.getElementById("italic").isToggled = false;
        } else {
            // document.getElementById("italic").style.backgroundColor = "#008833";
            document.getElementById("italic").isToggled = true;
        }
    }

    function doUnderline() {
        document.execCommand("underline");
        if (document.getElementById("underline").isToggled) {
            // document.getElementById("underline").style.backgroundColor = "#00cc55";
            document.getElementById("underline").isToggled = false;
        } else {
            // document.getElementById("underline").style.backgroundColor = "#008833";
            document.getElementById("underline").isToggled = true;
        }
    }

    function doAddImage() {
        var image_url = prompt("Image URL:");
        if (image_url != "") {
            document.execCommand("insertImage", false, image_url);
        } else {
            alert("You must set a URL!");
        }
    }

    function justifyLeft() {
        document.execCommand("justifyLeft");
    }

    function justifyCenter() {
        document.execCommand("justifyCenter");
    }

    function justifyRight() {
        document.execCommand("justifyRight");
    }

    function doSetTextColor() {
        var text_color = prompt("CSS Color:");
        if (text_color != "") {
            document.execCommand("foreColor", false, text_color);
        } else {
            alert("You must set a Color!");
        }
    }
    $('div#toolbar>button').click(function() {
        $('div#toolbar>button').removeClass('active');
        $(this).addClass('active');
    })

    function updateProfileImageAjax(data, url) {

        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                dataType: 'json',
                async: true,
                success: function(response) {
                    resolve(response)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function() {
                    Loader.show();
                },
                complete: function() {
                    Loader.hide();
                    
                    //
                    if (data instanceof FormData) {
                        $('.trash_profile_img').removeClass('d-none')
                    } else if (JSON.stringify(data) === '{}') {
                        $('#imagePreview').css('background-image', 'url(<?= g('dashboard_images_root') ?>upload-img.jpg)')
                        $('.trash_profile_img').addClass('d-none')
                    } else {
                        $('.trash_profile_img').removeClass('d-none')
                    }
                }
            });
        })
    }

    function updateCompanyImageAjax(data, url) {
        
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                dataType: 'json',
                async: true,
                success: function(response) {
                    resolve(response)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function() {
                    Loader.show();
                },
                complete: function() {
                    Loader.hide();
                    if (data instanceof FormData) {
                        $('.trash_company_img').removeClass('d-none')
                    } else if (JSON.stringify(data) === '{}') {
                        $('#imagePreview').css('background-image', 'url(<?= g('dashboard_images_root') ?>upload-img.jpg)')
                        $('.trash_company_img').addClass('d-none')
                    } else {
                        $('.trash_company_img').removeClass('d-none')
                    }
                }
            });
        })
    }
</script>

<script src="<?= g('js_root') ?>acctoolbar.min.js"></script>
<script>
    window.onload = function() {
        window.micAccessTool = new MicAccessTool({
            link: 'http://your-awesome-website.com/your-accessibility-declaration.pdf',
            contact: 'mailto:admin@contractorslicense.com',
            buttonPosition: 'left',
            forceLang: 'en-IL'
        });
    }
</script>

<!-- FOLLOW ACTION -->

<script>
    $('body').on('click', '.followBtn', function() {

        var thisBtn = this

        var data = {
            reference_id: $(this).data('reference_id')
        }
        if ($(this).data('reference') != undefined) {
            data.reference = $(this).data('reference')
        }
        var url = base_url + 'dashboard/custom/signupFollow'
        
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: true,
                dataType: "json",
                success: function(response) {
                    resolve(response)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                complete: function(jqXHR, textStatus) {
                },
                beforeSend: function() {
                    $(thisBtn).html('<span class="loading-ellipsis"></span>')
                }
            });
        }).then(
            function(response) {
                if (response.status) {
                    $(".followCountArea").load(location.href + " .followCountArea>*", function() {
                        $('[data-toggle="tooltip"]').tooltip({
                            html: true,
                        })
                        if ($(".dropdown input[type=checkbox]").length) {
                            $(".dropdown input[type=checkbox]").prop("checked", true).trigger("change");
                        }
                    });
                } else {
                    $.dialog({
                        backgroundDismiss: true,
                        title: '<?= __("Error!") ?>',
                        content: response.txt,
                    });
                }
            }
        )
    })
</script>

<!-- FOLLOW ACTION -->

<!-- CART ACTIONS -->

<script>
    async function updateCart() {
        $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"));
        var data = $('.update_cart_form').serialize()
        var url = base_url + 'dashboard/product/updateCartItems'
        //
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                dataType: 'json',
                async: true,
                success: function(response) {
                    resolve(response)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function() {
                    $('#updateCartBtn').attr('disabled', true)
                    $('#updateCartBtn').html('Updating ...')
                },
                complete: function() {
                    $('#updateCartBtn').attr('disabled', false)
                    $('#updateCartBtn').html('Update Cart')
                }
            });
        })
    }

    $(document).ready(function() {
        $('body').on('click', '.add_to_cart', function() {
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("Add this to your cart?") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Yes") ?>'],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    var data = {
                        'product_id': $(this).data('id'),
                        'product_quantity': $(this).data('quantity') != undefined ? $(this).data('quantity') : 1,
                        '_token': $('meta[name=csrf-token]').attr("content")
                    }
                    var url = base_url + 'dashboard/product/addCart'
                    new Promise((resolve, reject) => {
                        jQuery.ajax({
                            url: url,
                            type: "POST",
                            data: data,
                            async: true,
                            dataType: "json",
                            success: function(response) {
                                resolve(response)
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                            },
                            beforeSend: function() {
                                showLoader()
                            },
                            complete: function() {
                                hideLoader()
                            }
                        })
        			}).then(
        			    function(response) {
                            if (response.status) {
                                if (response.redirect) {
                                    swal({
                                        title: "Success",
                                        text: response.txt,
                                        icon: "success",
                                    }).then(() => {
                                        location.href = base_url + 'dashboard/order/cart'
                                    })
                                } else {
                                    swal("Success", response.txt, "success");
                                    $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                                        $('[data-toggle="tooltip"]').tooltip({
                                            html: true,
                                        })
                                    });
                                }
                            } else {
                                swal("Error", response.txt, "error");
                            }
        			    }
    			    )
                } else {
                    swal("Cancelled", "Action aborted", "error");
                }
            })
        })

        $('body').on('click', '.delete_cart_item', function() {
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("You are about to remove this product from your cart!") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Yes") ?>'],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    var data = {
                        'rowid': $(this).data('id'),
                        '_token': $('meta[name=csrf-token]').attr("content")
                    }
                    var url = base_url + 'dashboard/product/deleteCartItem'

                    new Promise((resolve, reject) => {
                        jQuery.ajax({
                            url: url,
                            type: "POST",
                            data: data,
                            async: true,
                            dataType: "json",
                            success: function(response) {
                                resolve(response)
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                            },
                            beforeSend: function() {
                                showLoader()
                            },
                            complete: function() {
                                hideLoader()
                            }
                        })
        			}).then(
        			    function(response) {
        			        if (response.status) {
                                swal("Success", response.txt, "success");
                                $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                                    $('[data-toggle="tooltip"]').tooltip({
                                        html: true,
                                    })
                                });
                            } else {
                                swal("Error", response.txt, "error");
                            }
        			    }
    			    )
                } else {
                    swal("Cancelled", "Action aborted", "error");
                }
            })
        })

        $('body').on('submit', '.update_cart_form', function(event) {
            event.preventDefault()
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("Update content of the cart?") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Yes") ?>'],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    updateCart().then(
                        function(response) {
                            if (response.status) {
                                swal("Success", response.txt, "success");
                                $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                                    $('[data-toggle="tooltip"]').tooltip({
                                        html: true,
                                    })
                                });
                            } else {
                                swal("Error", response.txt, "error");
                            }
                        }
                    )
                } else {
                    swal("Cancelled", "Action aborted", "error");
                }
            })
        });
    })
</script>

<!-- CART ACTIONS -->

<script>
    function pushNotify(data) {
        if (!("Notification" in window)) {
            // checking if the user's browser supports web push Notification
            console.log("Web browser does not support desktop notification");
        }
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        } else {
            notification = '';
            for (i = 0; i < data.length; i++) {
                notification = createNotification(data[i].title, data[i].icon, data[i].body, data[i].url);
            }
            // closes the web browser notification automatically after 5 secs
            setTimeout(function() {
                notification.close();
            }, 5000);
        }
    };

    function createNotification(title, icon, body, url) {
        var notification = new Notification(title, {
            icon: icon,
            body: body,
        });
        // url that needs to be opened on clicking the notification
        // finally everything boils down to click and visits right
        notification.onclick = function() {
            window.open(url);
        };
        return notification;
    }

    /**
     * Method refreshCountAjax
     *
     * @return void
     */
    async function refreshCountAjax() {
        var data = {
            '_token': $('meta[name=csrf-token]').attr("content"),
            'seen_notifications': $('input[name=seen_notifications]').val(),
            'seen_chat': $('input[name=seen_chat]').val(),
        }
        var url = base_url + 'dashboard/custom/refreshCount'
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: true,
                dataType: "json",
                success: function(response) {
                    resolve(response)
                }
            })
        })
    }

    async function refreshCount(response) {
        if (response.notification_status) {
            $("#top-notification").load(location.href + " #top-notification>*", function() {
                $('[data-toggle="tooltip"]').tooltip({
                    html: true,
                })
                pushNotify(response.notification_data)
            });
        }
        if (response.chat_status) {
            $("#top-message").load(location.href + " #top-message>*", function() {
                $('[data-toggle="tooltip"]').tooltip({
                    html: true,
                })
            });
        }
        setTimeout(function() {
            refreshCountAjax().then(
                function(response) {
                    refreshCount(response)
                }
            )
        }, 5000)
    }

    $(document).ready(function() {
        refreshCountAjax().then(
            function(response) {
                refreshCount(response)
            }
        )
        // pushNotify()
    })
</script>

<!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 38 38">
    <defs>
        <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">
            <stop stop-color="#000" stop-opacity="0" offset="0%" />
            <stop stop-color="#000" stop-opacity=".631" offset="63.146%" />
            <stop stop-color="#000" offset="100%" />
        </linearGradient>
    </defs>
    <g fill="none" fill-rule="evenodd">
        <g transform="translate(1 1)">
            <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2">
                <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite" />
            </path>
            <circle fill="#000" cx="36" cy="18" r="1">
                <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite" />
            </circle>
        </g>
    </g>
</svg> -->