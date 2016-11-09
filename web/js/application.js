/**
 * 设为首页
 */
function setHomepage(obj, vrl){
    try{
        obj.style.behavior='url(#default#homepage)';
        obj.setHomePage(vrl);
        NavClickStat(1);
    }
    catch(e){
        if(window.netscape) {
            try {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
            } catch (e) { 
                alert("抱歉！您的浏览器不支持直接设为首页。请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为“true”，点击“加入收藏”后忽略安全提示，即可设置成功。");  
            }
            var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
            prefs.setCharPref('browser.startup.homepage', vrl);
        }
    }
}

/**
 * 加入收藏
 */
function addFavorite(sURL, sTitle) {
    try {
        window.external.addFavorite(sURL, sTitle);
    }　catch(e) {
        try {
            window.sidebar.addPanel(sTitle, sURL, "");
        } catch(e) {
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}

/*
 * Lazy Load - jQuery plugin for lazy loading images
 *
 * Copyright (c) 2007-2009 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/lazyload
 *
 * Version:  1.5.0
 *
 */
(function(a){a.fn.lazyload=function(g){var c={threshold:0,failurelimit:0,event:"scroll",effect:"show",container:window};if(g){a.extend(c,g)}var f=this;if("scroll"==c.event){a(c.container).bind("scroll",function(b){var d=0;f.each(function(){if(a.abovethetop(this,c)||a.leftofbegin(this,c)){}else if(!a.belowthefold(this,c)&&!a.rightoffold(this,c)){a(this).trigger("appear")}else{if(d++>c.failurelimit){return false}}});var h=a.grep(f,function(e){return!e.loaded});f=a(h)})}this.each(function(){var b=this;if(undefined==a(b).attr("original")){a(b).attr("original",a(b).attr("src"))}if("scroll"!=c.event||undefined==a(b).attr("src")||c.placeholder==a(b).attr("src")||(a.abovethetop(b,c)||a.leftofbegin(b,c)||a.belowthefold(b,c)||a.rightoffold(b,c))){if(c.placeholder){a(b).attr("src",c.placeholder)}else{a(b).removeAttr("src")}b.loaded=false}else{b.loaded=true}a(b).one("appear",function(){if(!this.loaded){a("<img />").bind("load",function(){a(b).hide().attr("src",a(b).attr("original"))[c.effect](c.effectspeed);b.loaded=true}).attr("src",a(b).attr("original"))}});if("scroll"!=c.event){a(b).bind(c.event,function(e){if(!b.loaded){a(b).trigger("appear")}})}});a(c.container).trigger(c.event);return this};a.belowthefold=function(e,b){if(b.container===undefined||b.container===window){var d=a(window).height()+a(window).scrollTop()}else{var d=a(b.container).offset().top+a(b.container).height()}return d<=a(e).offset().top-b.threshold};a.rightoffold=function(e,b){if(b.container===undefined||b.container===window){var d=a(window).width()+a(window).scrollLeft()}else{var d=a(b.container).offset().left+a(b.container).width()}return d<=a(e).offset().left-b.threshold};a.abovethetop=function(e,b){if(b.container===undefined||b.container===window){var d=a(window).scrollTop()}else{var d=a(b.container).offset().top}return d>=a(e).offset().top+b.threshold+a(e).height()};a.leftofbegin=function(e,b){if(b.container===undefined||b.container===window){var d=a(window).scrollLeft()}else{var d=a(b.container).offset().left}return d>=a(e).offset().left+b.threshold+a(e).width()};a.extend(a.expr[':'],{"below-the-fold":"$.belowthefold(a, {threshold : 0, container: window})","above-the-fold":"!$.belowthefold(a, {threshold : 0, container: window})","right-of-fold":"$.rightoffold(a, {threshold : 0, container: window})","left-of-fold":"!$.rightoffold(a, {threshold : 0, container: window})"})})(jQuery);

var Mai = Mai || {};
Mai.urls = Mai.urls || {};
Mai.urls = {
    baseUrl: undefined,
    item: {
        list: undefined,
        view: undefined
    },
    shoppingCart: {
        index: undefined,
        add: undefined,
        'delete': undefined,
        changeQuantity: undefined
    }
};
Mai.product = Mai.product || {};
Mai.product.item = Mai.product.item || {};
Mai.product.item.activeId = Mai.product.item.activeId || undefined;
Mai.product.items = Mai.product.items || {};
Mai.product.ItemSpecifications = Mai.product.ItemSpecifications || {};

var vm = avalon.define({
    logined: false,
    $id: "product",
    product: {
        id: undefined,
        name: undefined,
        picture: undefined,
        description: undefined
    }, // 当前所查看的商品
    metaValues: [], // 当前商品的自定义属性
    items: {}, // 所有的单品
    item: {
        id: 0,
        name: 'Product name',
        brandName: 'Brand name',
        picture: '/images/product-detault-photo.png',
        price: 0,
        productNumber: '1908785',
        batchNumber: '20015157',
        description: 'This is product description'
    }, // 当前所选择的单品
    specifications: [],
    specificationValues: [],
    searchResults: [],
    shoppingCart: {
        items: [],
        amount: 0
    },
    activeSpecificationsValueIds: {}, // 选择商品属性的 id 集合
    choiceSpecification: function (specificationId, specificationValueId, execute) {
        console.info(specificationId, specificationValueId, execute);
        if (!execute) {
            return; // 没有关联的商品，点击无效
        }
        this.activeSpecificationsValueIds['spec' + specificationId] = specificationValueId;
        var specifications = this.specifications;
        for (var i = 0, l = specifications.length; i < l; i++) {
            if (specifications[i].id === specificationId) {
                for (var ii = 0, ll = specifications[i].items.length; ii < ll; ii++) {
                    this.specifications[i].items[ii].selected = specifications[i].items[ii].id == specificationValueId ? true : false;
                }
            }
        }
        var valueIds = _.values(this.activeSpecificationsValueIds), itemId = null;
        for (var key in this.specificationValues) {
            if (this.specificationValues[key].length === valueIds.length && _.difference(this.specificationValues[key], valueIds).length === 0) {
                itemId = key;
                break;
            }
        }
        console.info("itemId: " + itemId);
        if (itemId !== null) {
            for (var i = 0, l = this.items.length; i < l; i++) {
                if (this.items[i].id == itemId) {
                    this.item = this.items[i]; // 设置 item.id
                }
            }
        } else {
            this.item = {};
        }
    },
    // 查看商品详情
    showItemDetail: function (url) {
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            beforeSend: function (xhr) {
            },
            success: function (response) {
                vm.product = {
                    id: response.id,
                    name: response.name,
                    picture: response.picture,
                    description: response.description
                };
                vm.metaValues = response.metaValues;
                vm.items = response.items;
                vm.item = {};
                for (var i = 0, l = response.items.length; i < l; i++) {
                    if (response.items[i].default) {
                        vm.item = {
                            id: response.items[i].id,
                            name: response.items[i].name,
                            brandName: response.items[i].brandName,
                            price: response.items[i].price,
                            picture: response.items[i].picture
                        };
                    }
                }
                vm.activeSpecificationsValueIds = {};
                if (response.specifications !== undefined) {
                    var specifications = response.specifications;
                    vm.specifications = specifications;
                    vm.specificationValues = response.specificationValues;
                    for (var i = 0, l = specifications.length; i < l; i++) {
                        for (var ii = 0, ll = specifications[i].items.length; ii < ll; ii++) {
                            if (specifications[i].items[ii].selected) {
                                vm.activeSpecificationsValueIds['spec' + specifications[i].id] = specifications[i].items[ii].id;
                            }
                        }
                    }
                }
            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText, {icon: -1});
            }
        });
        
        return false;
    },
    // 删除订单列表中的某个商品
    removeOrderItem: function (id) {
        layer.confirm('您是否确定删除该商品？', {
          btn: ['确定','取消'] //按钮
        }, function(){
            var items = vm.shoppingCart.items;
            for (var i = 0, l = items.length; i < l; i++) {
                if (items[i].id === id) {
                    items.splice(i, 1);
                    $.ajax({
                        type: 'POST',
                        url: Mai.urls.shoppingCart['delete'].replace('_itemId', id),
                        data: {itemId: id},
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                vm.shoppingCart.items = items;
                            } else {
                                layer.alert(response.error.message);
                            }
                        }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                            layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText, {icon: -1});
                        }
                    });

                    break;
                }
            }
            layer.closeAll();
        }, function(){
           layer.closeAll();
        });
       
    },
    increaseItemOrderQuantity: function (id) {
        var items = this.shoppingCart.items, index = null;
        for (var i = 0, l = items.length; i < l; i++) {
            if (items[i].id === id) {
                var item = items[i];
                item.quantity = parseInt(item.quantity) + 1;
                index = i;
                break;
            }
        }
        
        if (index !== null) {
            this.shoppingCart.items.splice(index, 1, item);
            $.ajax({
                type: 'POST',
                url: Mai.urls.shoppingCart.changeQuantity.replace('_itemId', item.id),
                data: {quantity: 1},
                dataType: 'json',
                success: function (response) {
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
                }
            });
        }
    },
    decreaseItemOrderQuantity: function (id) {
        var items = this.shoppingCart.items, index = null;
        for (var i = 0, l = items.length; i < l; i++) {
            if (items[i].id === id && items[i].quantity >= 2) {
                var item = items[i];
                item.quantity = parseInt(item.quantity) - 1;
                index = i;
                break;
            }
        }
        if (index !== null) {
            this.shoppingCart.items.splice(index, 1, item);
            $.ajax({
                type: 'POST',
                url: Mai.urls.shoppingCart.changeQuantity.replace('_itemId', item.id),
                data: {quantity: -1},
                dataType: 'json',
                success: function (response) {
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
                }
            });
        }
    }
});

vm.$watch("shoppingCart.items", function() {
    var amount = 0, items = vm.shoppingCart.items;
    for (var i = 0, l = items.length; i < l; i++) {
        amount += (parseFloat(items[i].price) * parseInt(items[i].quantity));
    }
    vm.shoppingCart.amount = amount;
});

vm.$watch('onReady', function () {
    if (vm.logined) {
        $.ajax({
            type: 'GET',
            url: Mai.urls.shoppingCart.index,
            dataType: 'json',
            success: function (response) {
                vm.shoppingCart.items = response;
            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
            }
        });
    }
});

$(function () {

    /**
     * 关闭弹出窗口
     */
    $('.widget-item-search-results .hd a').on('click', function () {
        $(this).parent().parent().hide();
        
        return false;
    });
    
    /**
     * 根据品牌或者分类搜索相关商品
     */
    $('#page-product-brands li a, #page-product-categories li a').mouseenter('mouseenter', function () {
        var $t = $(this),
            $widget = $('.widget-item-search-results');
            id = $t.parent().parent().parent().parent().attr('id');
        $.ajax({
            type: 'GET',
            url: $t.attr('data-url'),
            dataType: 'json',
            beforeSend: function (xhr) {
                $widget.hide();
            },
            success: function (response) {
                vm.searchResults = response;
                var $li = $t.parent(),
                    widgetCss = {
                        top: $li.offset().top,
                        left: $li.offset().left + 133
                    };
                if (id === 'page-product-brands') {
                    $widget.removeClass('widget-item-search-results-categories').addClass('widget-item-search-results-brands');
                } else {
                    $widget.removeClass('widget-item-search-results-brands').addClass('widget-item-search-results-categories');
                    widgetCss.left = $li.offset().left - 249;
                }
                $widget.css(widgetCss).show();
            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText, {icon: -1});
            }
        });
    });
    
    $('.widget-item-search-results').mouseleave(function () {
        $(this).hide();
    });
    
        // 修改商品订购数量
    $('.btn-increase, .btn-decrease').on('click', function () {
        var $t = $(this),
            $quantityInput,
            quantity;
        if ($t.hasClass('btn-increase')) {
            $quantityInput = $t.next();
            quantity = parseInt($quantityInput.val()) + 1;
            $quantityInput.val(quantity);
            $t.parent().find('.btn-decrease').removeAttr('disabled');
        } else {
            $quantityInput = $t.prev();
            var value = parseInt($quantityInput.val());
            if (value <= 1) {
                $t.attr('disabled', 'disabled');
            }
            quantity = value - 1;
            $quantityInput.val(quantity);
        }
        
        return false;
    });
    
    // 订购（加入购物车）
    $('#buy').click(function() {
        var quantity = parseInt($('#buy-quantity').val());
        if (quantity <= 0) {
            layer.tips('请输入正确的订购数量。', '#buy-quantity', {
                tips: 1
            });
        } else {
            var item = vm.item,
                shoppingCartItems = vm.shoppingCart.items,
                index = null;
            for (var i = 0, l = shoppingCartItems.length; i < l; i++) {
                if (shoppingCartItems[i].id === item.id) {
                    item = shoppingCartItems[i];
                    item.quantity = parseInt(item.quantity) + quantity;
                    index = i;
                    break;
                }
            }
            if (index === null) {
                var cartItem = {
                    id: item.id,
                    name: item.name,
                    brandName: item.brandName,
                    modelName: item.modelName, // 型号
                    specificationName: item.specificationName, // 规格
                    modelSpecification: null, // 型号、规格
                    price: item.price,
                    quantity: quantity
                }, modelSpecification = [];
                for (var i = 0, l = vm.specificationValues[item.id].length; i < l; i++) {
                    for (var ii = 0, ll = vm.specifications.length; ii < ll; ii++) {
                        for (var iii = 0, lll = vm.specifications[ii].items.length; iii < lll; iii++) {
                            if (vm.specifications[ii].items[iii].id == vm.specificationValues[item.id][i]) {
                                modelSpecification.push(vm.specifications[ii].items[iii].text);
                            }
                        }
                    }
                }
                cartItem.modelSpecification = modelSpecification.join('、');
                vm.shoppingCart.items.push(cartItem);
                
            } else {
                vm.shoppingCart.items.splice(index, 1, item);
            }
            $.ajax({
                type: 'POST',
                url: Mai.urls.shoppingCart.add.replace('_itemId', item.id),
                data: {quantity: quantity},
                dataType: 'json',
                success: function (response) {
                    !response.success && layer.alert(response.error.message);
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText, {icon: -1});
                }
            });
        }
        
        return false;
    });
    
    // 商品详情查看更多效果
    $('.intro span.show-more-intro').click(function () {
        var $this = $(this);
        if ($this.hasClass('show-more-close')) {
            $('.intro .intro-body').animate({
                height: '96px'
            });
            $this.removeClass('show-more-close').addClass('show-more-open');
        } else {
            $('.intro .intro-body').animate({
                height: '100%'
            });
            $this.removeClass('show-more-open').addClass('show-more-close');
        }

        return false;
    });
});