$('.widget-homepage-common .tabs li a').live('click', function () {
    var $t = $(this),
        $container = $t.parent().parent().parent().parent(),
        $changeWidget = $container.find('div.widget-simple'),
        $changeWidgetBody = $changeWidget.find('.bd');

    $changeWidget.find('.hd').text($t.text());
    $.ajax({
        type: 'GET',
        url: $t.attr('href'),
        dataType: 'json',
        beforeSend: function (xhr) {
            $changeWidgetBody.addClass('loading');
        },
        success: function (data, textStatus, jqXHR) {
            $changeWidgetBody.removeClass('loading');
            var tmpl = document.getElementById('tmpl-homepage-news-widget').innerHTML,
                doTtmpl = doT.template(tmpl);
            $changeWidgetBody.find('ul').html(doTtmpl(data));
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(textStatus.text);
            $changeWidgetBody.removeClass('loading');
        }
    });
    return false;
});