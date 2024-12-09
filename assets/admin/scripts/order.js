// Order Details And Update Section
var Order = function () {
    
    var urls = {
        "get_address" : $js_config.base_url + "admin/order/get_address/" ,
        "save_address" : $js_config.base_url + "admin/order/save_address/" ,
        "save_discount" : $js_config.base_url + "admin/order/save_discount/" ,
    } ;


    return {

        "update_address" : function () {
            // Update address form
            $("body").on("click", '.updt-address' ,function () {
                var that = $(this) ;
                var url = urls.get_address + that.attr("data-id") ;
                var modal = $(".address_update_modal");
                modal.modal();
                AjaxRequest.load( url , {} , modal.find(".modal-body") );
            });
            // Update address Submit
            $(".address_update_form ").on("click", '.submitter' ,function () {
                response = FormScript.validateAndAjax($(".address_update_form"), urls.save_address );
                if(response.valid && response.result.success)
                {
                    AdminToastr.success("Address Updated" , "Address");
                    location.reload();
                }

            });
        },

        // Discount update functionality
        "update_discount" : function () {
            $("body").on("click" , ".discount_update" , function (){
                var order_total = parseFloat($(".order_price").val());
                var data = {} ;
                data.order_id = $(this).attr("data-id");
                data.order_discount = $("[name='order[order_discount]']").val();
                data.order_discount_type = $("[name='order[order_discount_type]']").val();
                if(!data.order_discount_type)
                {
                    AdminToastr.error("Percentage select Type","Invalid");
                    return false;
                }
                if(data.order_discount_type == "percent" && parseInt(data.order_discount) > 99 )
                {
                    AdminToastr.error("Percentage cannot exceed 99","Invalid");
                    return false;
                }
                if(data.order_discount >= order_total)
                {
                    AdminToastr.error("Discount cannot exceed total","Invalid");
                    return false;
                }

                response = AjaxRequest.fire( urls.save_discount , {"order":data} );
                if(response.success)
                {
                    AdminToastr.success("Discount Updated Successfully" ,"Discount Update");
                    location.reload();
                }   
            });
        },

        "init" : function () {

            this.update_address();
            this.update_discount();

        }
    };

}();

// Add Order Section
var AddOrder = function () {
    
    var row_mask = "";
    var payment_field_mask = "" ;
    var urls = {
        "get_addons" : $js_config.base_url + "admin/order/get_product_addons/" ,
        "get_itemset" : $js_config.base_url + "admin/order/get_itemset/" ,
        "get_payment_fields" : $js_config.base_url + "admin/order/get_payment_fields/" ,
        "save_discount" : $js_config.base_url + "admin/order/save_discount/" ,
    } ;


    return {

        // Product selection functionality
        "change_product" : function (product) {

            var product_id = product.val();
            
            var url = urls.get_addons + product_id ;
            var addons = AjaxRequest.fire(url , {});
            var addons_ele = product.closest("tr").find(".oitem-addons");
            addons_ele.find('option').remove();
            $.each(addons,function (i,dt) {
                addons_ele.append(
                    $("<option>").val(dt.addon_id).html(dt.addon_name).attr({"data-price":dt.addon_price})
                );
            });
            addons_ele.multiSelect('refresh');

            
            var url = urls.get_itemset + product_id ;
            var itemsets = AjaxRequest.fire(url , {});
            var item_set_element = $("[name='oitem[oitem_itemset_id]']") ;
            item_set_element.find('option').remove();
            $.each(itemsets,function (i,dt) {
                console.log(dt);
                item_set_element.append(
                    $("<option>").val(dt.pis_id).html(dt.size_name).attr({"data-price":dt.pis_price})
                );
            });

        },

        // Payment Method Selection Functionality
        "change_payment" : function (obj) {

            var payment_method_id = obj.val();
            
            var url = urls.get_payment_fields + payment_method_id ;
            var fields = AjaxRequest.fire(url , {});
            var container = $(".pay_cont_main");
            container.html("");
            $.each(fields , function (i , dt) {
                var mask = payment_field_mask.clone();
                mask.find(".pay_label").html(dt.pmf_title);
                mask.find(".pay_field").attr({
                    "name" : "transfer_detail[field][" + dt.pmf_id + "]" ,
                });
                container.append(mask);
            });


        },

        "init" : function () {
			var that = this ;            
            row_mask = $(".product.template").clone() ;
            payment_field_mask = $(".payment_field_cont").clone();
            $(".payment_field_cont").remove();

            $("body").on("change","[name='oitem[oitem_product_id]']" , function(){
                that.change_product($(this));
            });

            $("body").on("change","[name='order[order_payment_method_id]']" , function(){
                that.change_payment($(this));
            });

        }
    };

}();

// Order Messages Section

var Message = function () {
    var urls = {
        "add" : $js_config.base_url + "admin/order/add_message/" ,
        "mark_read" : $js_config.base_url + "admin/order/mark_message_asread/" ,
        "get" : $js_config.base_url + "admin/order/get_order_message/" ,
    } ;

    return {
        "init" : function () {
            var that = this ;
            that.reloadMessage();   
            $("body").on("click",".updt-message", function () {
                that.updateMessage( $(this) );
            });
            $("body").on("click",".mark_asread", function () {
                that.markRead( $(this) );
            });


        },

        "markRead" : function (obj) {
            var message_id = obj.attr("data-msg-id");
            var url = urls.mark_read + message_id ;
            var response = AjaxRequest.fire(url, {});
            if(response.success)
            {
                this.reloadMessage();
                AdminToastr.success("Order message Marked as read!" , "Successful");
            }
            else
                AdminToastr.warning("Could not mark the message!" , "Warning");
        },

        "updateMessage" : function (obj) {
            var order_id = obj.attr("data-id");
            var url = urls.add + order_id ;
            var data = { 
                "message" : $('[ name="message[message]"]').val() ,
                "is_private" : $('[ name="message[is_private]"]:checked').val() ,
            } ;
            if( data.message.length < 4 )
            {
                AdminToastr.error("Message Length must be atleast 4 characters long.");
                return false;
            }
            var response = AjaxRequest.fire(url, data);
            if(response.success)
            {
                this.reloadMessage();
                AdminToastr.success("Order message Updated!" , "Successful");
            }
            else
                AdminToastr.warning("No change in Order message!" , "Warning"); 
        },

        "reloadMessage" : function () {
            var order_id = $(".updt-message").attr("data-id") ;
            var url = urls.get + order_id ;
            var response = AjaxRequest.fire(url , {});
            var container = $(".messages tbody");
            container.html('') ;

            container.html('') ;
            
            if(response.length == 0)
                container.html('<tr><td colspan="100%">No messages available</td></tr>') ;

            $.each(response, function (ind , dt) {

                var type = dt.order_message_is_private == 1 ? "Private" : "Public" ;
                var row = $("<tr>")
                                .append( $("<td>").html(dt.user_username) )
                                .append( $("<td>").html(type) )
                                .append( $("<td>").html(dt.order_message_ondate) )
                                .append( $("<td>").html(dt.order_message_text) )
                                .append( $("<td>") );
                var show_tick = ( dt.by_me == 0 && dt.order_message_is_read == 0 ) ? 1 : 0 ;

                if( show_tick )
                    row.find("td:last").html('<i data-msg-id="' + dt.order_message_id + '" class="fa fa-check mark_asread btn green"></i>') ;

                container.append(row) ;
            });
        },
    };

}();

$(document).ready(function () {
    Order.init();
    Message.init();
    AddOrder.init();
});