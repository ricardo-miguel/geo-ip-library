(function($) {

    var fade = 250;
    var process = 400;
    var step = 0;
    var steps = [
        { 'action': 'geo_ip_get_library', 'class': '.downloading' },
        { 'action': 'geo_ip_extract_library', 'class': '.decompressing' },
        { 'action': 'geo_ip_update_lastest', 'class': '.done', 'finish': true }
    ];

    $(document).ready(function() {
        $('.time-ago').timeago();
        $('.update').on('click', function() {
            return update_library();
        });
        $('.gil-close').on('click', function() {
            $('.gil-update-wrapper').fadeOut(fade, function() {
                reset_steps();
            });
            return false;
        });
    });

    function update_library() {
        var s = steps[step];
        if(step == 0)
            $('.gil-update-wrapper').fadeIn(fade).css('display', 'table');
        if(s) {
            ajax_call(s);
            show_step(s);
            step++;
        }
    }

    function ajax_call(s) {
        if(s && s.action) {
            var request = { 'action': s.action };
            if(s.parameters && typeof s.parameters === 'object')
                request.push(s.parameters);
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: request,
                success: function(response) {
                    console.log(response);
                    update_library();
                    if(response.date && response.string_date)
                        $('.time-ago').data('timeago', null).attr('datetime', response.date).text(response.string_date).timeago();
                    if(response.size)
                        $('.gil-size').text(response.size);
                }
            });
        }
        return false;
    }

    function show_step(s) {
        if($(s.class).siblings(':visible').length > 0) {
            $(s.class).siblings(':visible').css('transform', 'translate3d(0, 50px, 0)').fadeOut(process, function() {
                if(s.finish)
                    $('.gil-update-wrapper .animation').fadeOut(process/2).css('transform', 'translate3d(0, -50px, 0)');
                $(s.class).fadeIn(process).css('transform', 'translate3d(0, 0px, 0)');
            });
        } else {
            if(s.finish)
                $('.gil-update-wrapper .animation').fadeOut(process/2).css('transform', 'translate3d(0, -50px, 0)');
            $(s.class).fadeIn(process).css('transform', 'translate3d(0, 0px, 0)');
        }
    }

    function reset_steps() {
        step = 0;
        $('.gil-update-wrapper .animation').removeAttr('style'); 
        $('.gil-update-wrapper .process > div').removeAttr('style');
    }
})(jQuery);