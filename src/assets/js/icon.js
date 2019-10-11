(function (window, $, layer) {
    window.top.layer_from_index = 0;
    window.top.close_layer_from = function () {
        window.top.layer.close(window.top.layer_from_index);
    }
    $(function () {
        let icon = $('<div id="float_icon">+</div>');
        let event_user = $('body').data('event_user');
        let now_user = String($('body').data('now_user'));
        if (event_user.length && event_user.indexOf(now_user) != -1) {
            icon.appendTo('body');
            icon.bind('click', function () {
                window.top.layer_from_index = layer.open({
                    type: 2,
                    title: '<h4>添加事件</h4>',
                    shadeClose: false,
                    scrollbar: false,
                    maxmin: true,
                    shade: 0.8,
                    area: ['1000px', '750px'],
                    content: [
                        "/event/event/create"
                    ], end: function () {
                        let url = window.location.href;

                        try {
                            window.iframeRunFunction('/event/event/history', function (siWindow) {
                                siWindow.location.reload();
                            });
                            window.iframeRunFunction('/event/event/index', function (siWindow) {
                                siWindow.location.reload();
                            });
                        } catch (e) {
                            if (url.indexOf('event/event') != -1) {
                                window.location.reload();
                            }
                        }

                    }
                });
                window.top.layer.full(window.top.layer_from_index);
            });


        }
    });

})(window, $, layer);