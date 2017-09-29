(function($) {

    /**
     * Interval (in hours) to allow library update from latest update date
     * 
     * @since   0.7
     * @type    {int}
     */
    var update_interval = 72;

    /**
     * Fade transition time (in milliseconds) for update wrapper
     * 
     * @since   0.7
     * @type    {int}
     */
    var fade = 250;

    /**
     * Transition time of each step (in milliseconds) for update process animation
     * 
     * @since   0.7
     * @type    {int}
     */
    var process = 400;

    /**
     * Offset distance of each process step animation
     * 
     * @since   0.7
     * @type    {string}
     */
    var offset = '40px';

    /**
     * Step counter
     * 
     * @since   0.5.1
     * @type    {int}
     */
    var step = 0;
    
    /**
     * Steps object where every single step is settled including other related parameters
     * 
     * @since   0.5.1
     * @type    {object}
     */
    var steps = [
        { 
            'action' : 'geo_ip_get_library', 
            'class'  : '.downloading' 
        },
        { 
            'action' : 'geo_ip_extract_library', 
            'class'  : '.decompressing' 
        },
        { 
            'action' : 'geo_ip_update_latest', 
            'class'  : '.done', 
            'finish' : true 
        }
    ];

    /**
     * Exceptions object where all possible errors are defined to show info about
     * 
     * @since   0.5
     * @type    {object}
     */
    var exceptions = {
        "FILE_DOWNLOAD"     : i10n_geo_ip.EX_FILE_EXTRACTION,
        "FILE_SIZE"         : i10n_geo_ip.EX_FILE_SIZE,
        "FILE_EXTRACTION"   : i10n_geo_ip.EX_FILE_EXTRACTION,
        "FILE_NOT_FOUND"    : i10n_geo_ip.EX_FILE_NOT_FOUND
    };

    /**
     * Main events settled when document is ready
     * 
     * @since   0.5
     * @return  {void}
     */
    $(document).ready(function() {
        update_availability();
        if($('.time-ago').text() != i10n_geo_ip.NEVER)
            $('.time-ago').timeago();
        $('.geo-ip-close').on('click', function() {
            $('.geo-ip-update-wrapper').fadeOut(fade, function() {
                reset_steps();
            });
            return false;
        });
    });

    /**
     * Update library process
     * 
     * @since   0.5
     * @return  {void}
     */
    function update_library() {
        var s = steps[step];
        if(step == 0)
            $('.geo-ip-update-wrapper').fadeIn(fade).css('display', 'table');
        if(s) {
            ajax_call(s);
            show_step(s);
            step++;
        }
    }

    /**
     * AJAX caller to handle process steps
     * 
     * @since   0.5
     * @param   {object}    s   Step process object
     * @return  {void|bool}
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
                            $('.geo-ip-size').text(response.size);
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
     * 
     * @since   0.7
     * @param   {object}   s   Step process object
     * @return  {void}
     */
    function show_step(s) {
        if($(s.class).siblings(':visible').length > 0) {
            $(s.class).siblings(':visible').css('transform', 'translate3d(0, ' + offset + ', 0)').fadeOut(process, function() {
                if(s.finish)
                    $('.geo-ip-update-wrapper .animation').fadeOut(process/2).css('transform', 'translate3d(0, -' + offset + ', 0)');
                $(s.class).fadeIn(process).css('transform', 'translate3d(0, 0px, 0)');
            });
        } else {
            if(s.finish)
                $('.geo-ip-update-wrapper .animation').fadeOut(process/2).css('transform', 'translate3d(0, -' + offset + ', 0)');
            $(s.class).fadeIn(process).css('transform', 'translate3d(0, 0px, 0)');
        }
    }

    /**
     * Reset DOM elements related to step animations
     * 
     * @since   0.7.1
     * @return  {void}
     */
    function reset_steps() {
        step = 0;
        $('.geo-ip-update-wrapper .animation, .geo-ip-update-wrapper .process > div, .bookshelf_wrapper').removeAttr('style');
        $('.bookshelf_wrapper').removeClass('paused');
    }

    /**
     * Handles unexpected error animation when a failure is triggered in any step process response
     * 
     * @since   0.7.2
     * @param   {object}   ex   Exception object
     * @return  {void}
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
     * 
     * @since   0.8
     * @return  {void}
     */
    function update_availability() {
        var update_diff = $('.update').attr('diff');
        var never       = ($('.time-ago').text() == i10n_geo_ip.NEVER);  
        if(update_diff == '' || update_diff >= update_interval || never) {
            $('.update').on('click', function() {
                return update_library();
            });
        } else {
            var remain       = update_interval - update_diff;
            var hours        = (remain == 1) ? i10n_geo_ip.HOUR : i10n_geo_ip.HOURS;
            var available_in = i10n_geo_ip.UPDATE_AVAILABLE_IN.replace('%d', remain).replace('%s', hours);
            $('.update').after('<span class="remain">[ ' + available_in + ' ]</span>').remove();
        }
    }

})(jQuery);