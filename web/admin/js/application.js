Date.prototype.fromUnixTimestamp = function (value) {
    return new Date(parseFloat(value) * 1000);
};
Date.prototype.format = function (format) {
    var o = {
        "M+": this.getMonth() + 1, //month 
        "d+": this.getDate(), //day 
        "h+": this.getHours(), //hour 
        "m+": this.getMinutes(), //minute 
        "s+": this.getSeconds(), //second 
        "q+": Math.floor((this.getMonth() + 3) / 3), //quarter 
        "S": this.getMilliseconds() //millisecond 
    };
    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }
    for (var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length === 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
};

/**
 * Lock UI
 */
(function ($) {
    $.fn.lock = function () {
        this.unlock();
//            if ($.css(this, 'position') === 'static')
//                this.style.position = 'relative';
//            if ($.browser.msie)
//                this.style.zoom = 1;
        $('body').append('<div id="widget-lock-ui" class="lock-ui" style="position:absolute;width:100%;height:100%;top:0;left:0;z-index:1000;background-color:#000;cursor:wait;opacity:.7;filter: alpha(opacity=70);"><div>');
    };
    $.fn.unlock = function () {
        $('#widget-lock-ui').remove();
    };
})(jQuery);

$(function () {
    $(document).on('click', '#header-account-manage li.change-tenant a:first', function () {
        $(this).parent().find('ul').show();
    });
});
// Art dialog default settings
(function (artDialog) {
    artDialog['okValue'] = '确定';
    artDialog['cancelValue'] = '取消';
    artDialog['title'] = '提示信息';
})($.dialog.defaults);

var yadjet = yadjet || {};
yadjet.icons = yadjet.icon || {};
yadjet.icons.boolean = [
    '/images/no.png',
    '/images/yes.png'
];
yadjet.actions = yadjet.actions || {
    toggle: function (selector, url) {
        var dataExt = arguments[2] ? arguments[2] : {};
        var trData = arguments[3] ? arguments[3] : [];
        $(selector).on('click', function (event) {
            event.stopPropagation();
            var $this = $(this);
            var $tr = $this.parent().parent();
            var data = {
                id: $tr.attr('data-key')
            };
            for (var key in dataExt) {
                data[key] = dataExt[key];
            }
            console.info(trData);
            for (var key in trData) {
                // `data-key` To `dataKey`
                var t = trData[key].toLowerCase();
                t = t.replace(/\b\w+\b/g, function (word) {
                    return word.substring(0, 1).toUpperCase() + word.substring(1);
                });
                t = t.replace('-', '');
                t = t.substring(0, 1).toLowerCase() + t.substring(1);
                data[t] = $tr.attr('data-' + trData[key]);
            }
            console.info(data);
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                beforeSend: function (xhr) {
                    $this.hide().parent().addClass('running-c-c');
                }, success: function (response) {
                    if (response.success) {
                        var data = response.data;
                        $this.attr('src', yadjet.icons.boolean[data.value ? 1 : 0]);
                        if (data.updatedAt) {
                            $tr.find('td.rb-updated-at').html(data.updatedAt);
                        }
                        if (data.updatedBy) {
                            $tr.find('td.rb-updated-by').html(data.updatedBy);
                        }
                    } else {
                        $.alert(response.error.message);
                    }
                    $this.show().parent().removeClass('running-c-c');
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
                    $this.show().parent().removeClass('running-c-c');
                }
            });

            return false;
        });
    },
    gridColumnConfig: function () {
        jQuery(document).on('click', '#menu-buttons li a.grid-column-config', function () {
            var $this = $(this);
            $.ajax({
                type: 'GET',
                url: $this.attr('href'),
                beforeSend: function (xhr) {
                    $.fn.lock();
                }, success: function (response) {
                    $.dialog({
                        title: '表格栏位设定',
                        content: response,
                        lock: true,
                        padding: '10px'
                    }, function () {
                        $.pjax.reload({container: '#' + $this.attr('data-reload-object')});
                    });
                    $.fn.unlock();
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
                    $.fn.unlock();
                }
            });

            return false;
        });
    }
};

yadjet.actions.gridColumnConfig();

jQuery(document).on('click', '.tabs-common li a', function () {
    var $this = $(this);
    $('.panels .panel').hide();
    $('#' + $this.attr('data-key')).show();
    $this.parent().parent().find('li').removeClass('active').end().end().addClass('active');
});

// 数据表单 tabs
jQuery(document).on('click', '#entity-form-tabs li a', function () {
    var $this = $(this);
    $('.form .panel').hide();
    $('#' + $this.attr('data-key')).show();
    $this.parent().parent().find('li').removeClass('active').end().end().addClass('active');
});

// 图片裁剪对话框
jQuery(document).on('click', '.open-image-cropper-dialog', function () {
    $.ajax({
        type: 'GET',
        url: $(this).attr('href'),
        beforeSend: function(xhr) {
            $.fn.lock();
        }, success: function(response) {
            $.dialog({
                title: 'Image Cropper',
                content: response,
                lock: true,
                padding: '10px'
            });
            $.fn.unlock();
        }, error: function(XMLHttpRequest, textStatus, errorThrown) {
            $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
            $.fn.unlock();
        }
    });
    
    return false;
});

jQuery(document).on('click', '#logout', function () {
    return confirm('你是否确定退出本系统？');
});
