(function (window, $, layer) {
    window.layer_from_index = 0;
    window.close_layer_from = function () {
        layer.close(layer_from_index);
    }
    $(function () {
        let icon = $('<div id="float_icon">+</div>');
        let event_user=$('body').data('event_user');
        let now_user=String($('body').data('now_user'));
        console.log(event_user);
        console.log(now_user);
        if(event_user.length && event_user.indexOf(now_user)!=-1){
            icon.appendTo('body');
            icon.bind('click', function () {
                window.layer_from_index = layer.open({
                    type: 2,
                    title: '<h4>添加事件</h4>',
                    shadeClose: true,
                    scrollbar: false,
                    maxmin: true,
                    shade: 0.8,
                    area: ['700px', '500px'],
                    content: [
                        "/event/event/create"
                    ],end: function () {
                        let url=window.location.href;
                        console.log('url:'+url);
                        if(url.indexOf('event/event/history')!=-1){
                            window.location.reload();
                        }
                    }
                });
            });
        }
    });

})(window, $, layer);