<?php

   /* * * * * * * * * * * * * * * * * * * * * * * *
    *            < GeoIPLibraryAdmin >            *
    *                                             *
    * This is the admin core. All needed actions  *
    * filters for management are settled here.    *
    *                                             *
    * * * * * * * * * * * * * * * * * * * * * * * *
    * This is a built-in script, please do not    *
    * modify if is not really necessary.          *
    * * * * * * * * * * * * * * * * * * * * * * * */

    class GeoIPLibraryAdmin {

        /**
         * Initializes administration related actions
         * @return void
         */
        function run() {
            add_action('current_screen', array($this, 'assets'));
            add_action('admin_menu', array($this, 'menu'));            
        }

        /**
         * Load front-end assets (like stylesheets, scripts, etc.)
         * @return void
         */
        function assets() {
            $current_screen = get_current_screen();
            if($current_screen->id == "tools_page_geo-ip-library-settings") {
                wp_enqueue_style('gil-admin-book-style', GILURL . 'assets/css/book.min.css');
                wp_enqueue_style('gil-admin-style', GILURL . 'assets/css/admin.min.css');
                wp_enqueue_script('gil-timeago-script', GILURL . 'assets/js/jquery.timeago.js', 'jquery');
                wp_enqueue_script('gil-admin-script', GILURL . 'assets/js/admin.min.js', 'jquery');
            }
        }

        /**
         * Defines how admin is shown at dashboard
         * @return void
         */
        function menu() {
            add_submenu_page(
                'tools.php',
                'Geo IP Library',
                'Geo IP Library',
                'manage_options',
                'geo-ip-library-settings',
                array($this, 'settings')
            );
        }

        /**
         * Settings page (mostly info)
         * @return void
         */
        function settings() {
            $latest_update         = (get_option('geo_ip_library_latest_update')) ? get_option('geo_ip_library_latest_update') : null;
            $string_latest_update  = (is_null($latest_update)) ? 'Never' : strtolower(date('\o\n l, jS F \a\t H:i:s', strtotime($latest_update))) . ' (GMT+0)';
            $file_size             = round(filesize(GILPATH . 'lib/geoiploc.php') / 1000) . ' KB';
            // HTML begins ?>

            <div class="gil-wrapper">
                <div class="gil-block gil-center">
                    <h1>Geo IP Library</h1>
                </div>
                <p class="gil-by">source by <a href="//chir.ag/projects/geoiploc/">Chirag Mehta</a> | scrapped by <a href="//ricardomiguel.cl">Ricardo Miguel</a></p>
                <div class="gil-block">
                    <h2>Source</h2>
                    <table>
                        <tr>
                            <td>Latest update</td><td><time class="time-ago" datetime="<?=$latest_update?>"><?=$string_latest_update?></time> <a class="update" href="#">[ <span class="dashicons dashicons-update" style="margin-top: -0.1em"></span> UPDATE NOW ]</a></td>
                        </tr>
                        <tr>
                            <td>Size</td><td class="gil-size"><?=$file_size?></td>
                        </tr>
                        <tr>
                            <td>Required PHP version</td><td>>= 5.3</td>
                        </tr>
                        <tr>
                            <td>Server PHP version</td><td><?=PHP_VERSION?></td>
                        </tr>
                    </table>
                </div>
                <div class="gil-block">
                    <h2>Coding</h2>
                    <p>The following static functions can be used anywhere within GeoIPLibrary class:</p>
                    <table class="gil-function">
                        <tr>
                            <td class="name"><span style="color: #369">get_client_address()</span></td><td class="return">string</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="description">Returns the current client's IP address. It bypasses proxies, forwarding and network masks.</td>
                        </tr>
                    </table>
                    <table class="gil-function">
                        <tr>
                            <td class="name"><span style="color: #369">get_client_country_code</span>(<span style="color: #693">string</span> <span style="color: #369">$ip</span> <span style="color: #936">= ''</span>)</td><td class="return">string</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="description">Returns the current client's <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country code or the specified in <span style="color: #369">$ip</span> (inherits <span style="color: #369">get_client_address()</span> algorithm in that case).</td>
                        </tr>
                    </table>
                    <table class="gil-function">
                        <tr>
                            <td class="name"><span style="color: #369">get_client_country_name</span>(<span style="color: #693">string</span> <span style="color: #369">$ip</span> <span style="color: #936">= ''</span>)</td><td class="return">string</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="description">Returns the current client's country name or the specified in <span style="color: #369">$ip</span> (inherits <span style="color: #369">get_client_address()</span> algorithm in that case).</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="gil-update-wrapper">
                <div class="gil-update-content">
                    <div class="animation">
                        <div class="bookshelf_wrapper">
                            <ul class="books_list">
                                <li class="book_item first"></li>
                                <li class="book_item second"></li>
                                <li class="book_item third"></li>
                                <li class="book_item fourth"></li>
                                <li class="book_item fifth"></li>
                                <li class="book_item sixth"></li>
                            </ul>
                            <div class="shelf"></div>
                            </div>
                        </div>
                    <div class="process">
                        <div class="downloading">Downloading library package <span class="ellipsis-anim"><span>.</span><span>.</span><span>.</span></span></div>
                        <div class="decompressing">Decompressing library file <span class="ellipsis-anim"><span>.</span><span>.</span><span>.</span></span></div>
                        <div class="done">Everything works like a charm!<br /><a class="gil-button gil-close" href="#">OK, you're the best!</a></div>
                        <div class="failure"><div class="exception"></div><br /><a class="gil-button gil-close" href="#">For sure I will</a></div>
                    </div>
                </div>
            </div>

            <?php // HTML ends
        }

    }

?>