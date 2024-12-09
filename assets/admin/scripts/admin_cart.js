/*
*   @author: Muhammad Uzair Khan (Muhammad.uzair@tradekey.com)
*   @package: TKD - Helper
*   @class: Cart - Simply, update , insert, delete and destroy Cart from the face of the earth...
*/ 

var Cart = function () {

    var urls = {
        product :  $js_config.base_url + "product/" , 
        get_item_set :  $js_config.base_url + "cart/get_available_item_set_color/" , 
        add_cart :  $js_config.base_url + "cart/add_to_cart/" , 
        update_cart :  $js_config.base_url + "cart/update_cart/" , 
        update_qty :  $js_config.base_url + "cart/update_qty/" , 
        remove_cart :  $js_config.base_url + "cart/remove/" , 
        destroy_cart :  $js_config.base_url + "cart/destroy_cart/" , 
        get_cart :  $js_config.base_url + "cart/get_cart/" , 
        get_basket :  $js_config.base_url + "cart/get_basket/" , 
    };

    var itemSetSelector = function () {
        $("[name='cart[size]']").change(function () {
            
            /*var product_id = $(".add_cart").attr("data-id");
            var url = urls.get_item_set + product_id ;
            var params = {} ;
            params.where = {} ;
            params.where.size_id = $("[name='cart[size]']").val();
            response = AjaxRequest.fire(url, params);
            $("[name='cart[color]'] option:gt(0)").remove();
            $(response).each(function (index,data) {
                var option = $("<option>").val(data.pis_id).html(data.color_name);
                $("[name='cart[color]']").append(option);
            });*/

        });
        params = {} ;
    };

    return {
        
        //main function to initiate the module
        init: function () {

            // Initialize Add to cart Functionality
            this._addToCart();

            // Initialize Remove
            this._removeFromCart();

            // Auto Update Cart when Qty increased
            this._updateQty();

            // Load Cart into the body.
            this.loadCart();

            // Private methods
            itemSetSelector();

        },
        addCart : function(cartparams){
            response = AjaxRequest.fire(urls.add_cart, cartparams);   
            
            if( response.success )  
            {
                this.loadCart();
            }       
            else
                alert(response.msg) ;
        },

        loadCart : function(cart_body){
            if(!cart_body)
                cart_body = ".cart_body";   
            // If cart_body exists, load
            if($(cart_body).length > 0)   
            {
                var content = this.getCart();
                $(".cart_body").html(content.cart_body);
                $("#cart_total_items").html(content.total_items);
                $("#cart_total_weight").html(content.total_weight);
                $("#cart_total").html(content.total);
                // Reinitialize foolish Core Class.
                window.globalCore.events.quantity();
            }      
        },

        getCart : function(cartparams){
            return AjaxRequest.fire(urls.get_cart, cartparams);            
        },

        _addToCart: function () {
            
            var that = this;
            $("body").on("click",".load_cart", function() {
                that.loadCart();
            });

            $("body").on("click",".add_cart", function() {
                var cart_container = $(this).closest(".cart_container");
                var cartparams = {} ; 
                var qty_container = cart_container.find($(this).attr("data-qty-container"));
                var ele_item_set = cart_container.find("[name='oitem[oitem_itemset_id]']") ;
                var ele_addons = cart_container.find("[name='oitem[addons][]'] option:checked");
                if(ele_item_set.find('option').length > 0 && !ele_item_set.val() )
                {
                    alert("Please select Size.");
                    return false;
                }

                cartparams.id = $("#oitem_product_id").val();

                // Can pick up QTY from another dom element incase data-qty-container exists.
                if(qty_container.length > 0)
                    cartparams.qty = qty_container.val();
                if(cartparams.qty == 0)
                    cartparams.qty = $(this).attr("data-qty");

                // For itemset, find if the button has wrapper of ITEMSET
                if(ele_item_set.length>0)
                    cartparams.item_set = ele_item_set.val();

                cartparams.addons = Array();
                // Additional Products
                if(ele_addons.length>0)
                {
                    ele_addons.each(function (i) {
                        var addon_id = $(this).val();
                        cartparams.addons[i] = addon_id;
                    });
                }
                that.addCart(cartparams);
            });

        },

        _removeFromCart: function () {
            
            that = this;
            $("body").on("click",".cart_remove",function() {
                var rowid = $(this).attr("data-rowid");
                if(rowid)
                {
                    var url = urls.remove_cart + rowid;
                    response = AjaxRequest.fire(url, {});
                    if(response.success)
                    {
                        that.loadCart();
                    }
                }
            });

        },

        _updateQty: function () {

            that = this;
            $("body").on("change",".cart_qty",function() {
                var data = {};
                data.rowid = $(this).attr("data-rowid");
                data.qty = $(this).val();
                if(data.rowid)
                {
                    var url = urls.update_qty ;
                    response = AjaxRequest.fire( url, data );

                    if(!response.success && response.msg)
                        alert(response.msg);

                    that.loadCart();
                }
            });

        },

    }; // End of class return

}(); // End of Script

$(document).ready(function () {
    Cart.init();
});