(function($) {

    /**
     * Interval (in hours) to allow library update from latest update date
     */
    var update_interval = 72;

    /**
     * Fade transition time (in milliseconds) for update wrapper
     */
    var fade = 250;

    /**
     * Transition time of each step (in milliseconds) for update process animation
     */
    var process = 400;

    /**
     * Offset distance of each process step animation
     */
    var offset = '40px';

    /**
     * Step counter
     */
    var step = 0;
    
    /**
     * Steps object where every single step is settled including other related parameters
     */
    var steps = [
        { 'action': 'geo_ip_get_library', 'class': '.downloading' },
        { 'action': 'geo_ip_extract_library', 'class': '.decompressing' },
        { 'action': 'geo_ip_update_latest', 'class': '.done', 'finish': true }
    ];

    /**
     * Exceptions object where all possible errors are defined to show info about
     */
    var exceptions = {
        "FILE_DOWNLOAD": "Something went wrong while attempting to download from source. Try again later.",
        "FILE_SIZE": "It seems that source library is updating itself, give it a try later.",
        "FILE_EXTRACTION": "There was something weird while trying to unzip the source file. Try again in a few minutes.",
        "FILE_NOT_FOUND": "Huh! The library source was suppose to be found, but is not! Check for writing and reading permissions and try it again."
    };

    /**
     * Main events settled when document is ready
     */
    $(document).ready(function() {
        update_availability();
        if($('.time-ago').text() != 'Never')
            $('.time-ago').timeago();
        $('.gil-close').on('click', function() {
            $('.gil-update-wrapper').fadeOut(fade, function() {
                reset_steps();
            });
            return false;
        });
    });

    /**
     * Update library process
     */
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

    /**
     * AJAX caller to handle process steps
     * @param {object} s Step process
     */
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
                    if(response.success) {
                        update_library();
                        if(response.date && response.string_date) {
                            $('.time-ago').data('timeago', null).attr('datetime', response.date).text(response.string_date).timeago();
                            $('.update').attr('diff', 0);
                            update_availability();
                        }
                        if(response.size)
                            $('.gil-size').text(response.size);
                    } else {
                        update_error(response.exception);
                    }
                }
            });
        }
        return false;
    }

    /**
     * Handles step animation
     * @param {object} s Step process 
     */
    function show_step(s) {
        if($(s.class).siblings(':visible').length > 0) {
            $(s.class).siblings(':visible').css('transform', 'translate3d(0, ' + offset + ', 0)').fadeOut(process, function() {
                if(s.finish)
                    $('.gil-update-wrapper .animation').fadeOut(process/2).css('transform', 'translate3d(0, -' + offset + ', 0)');
                $(s.class).fadeIn(process).css('transform', 'translate3d(0, 0px, 0)');
            });
        } else {
            if(s.finish)
                $('.gil-update-wrapper .animation').fadeOut(process/2).css('transform', 'translate3d(0, -' + offset + ', 0)');
            $(s.class).fadeIn(process).css('transform', 'translate3d(0, 0px, 0)');
        }
    }

    /**
     * Reset DOM elements related to step animations
     */
    function reset_steps() {
        step = 0;
        $('.gil-update-wrapper .animation, .gil-update-wrapper .process > div, .bookshelf_wrapper').removeAttr('style');
        $('.bookshelf_wrapper').removeClass('paused');
    }

    /**
     * Handles unexpected error animation when a failure is triggered in any step process response
     * @param {object} ex Exception 
     */
    function update_error(ex) {
        $('.bookshelf_wrapper').addClass('paused');
        $('.process .failure').siblings(':visible').css('transform', 'translate3d(0, ' + offset + ', 0)').fadeOut(process, function() {
            console.log(exceptions[ex]);
            $('.process .failure .exception').text(exceptions[ex]);
            $('.process .failure').fadeIn(process).css('transform', 'translate3d(0, 0px, 0)');
        });
    }

    /**
     * Check for update availability due to interval restriction
     */
    function update_availability() {
        var update_diff = $('.update').attr('diff');
        if(update_diff == '' || update_diff >= update_interval) {
            $('.update').on('click', function() {
                return update_library();
            });
        } else {
            var remain = update_interval - update_diff;
            var hours  = (remain == 1) ? 'HOUR' : 'HOURS';
            $('.update').after('<span class="remain">[ UPDATE AVAILABLE IN ' + remain + ' ' + hours + ' ]</span>').remove();
        }
    }

})(jQuery);