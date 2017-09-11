<?php

    class GeoIPLibraryAdmin {

        function __construct() {

        }

        function run() {
            add_action('current_screen', array($this, 'assets') );
            add_action('admin_menu', array($this, 'menu'));
        }

        function assets() {
            $current_screen = get_current_screen();
            wp_enqueue_style('gil-admin-style', GILURL . 'assets/css/admin.min.css');
        }

        function menu() {
            add_submenu_page(
                'options-general.php',
                'Geo IP Library',
                'Geo IP Library',
                'manage_options',
                'geo-ip-library-settings',
                array($this, 'settings')
            );
        }

        function settings() {
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
                            <td>Lastest update</td><td>Never <a class="update" href="#">[ <span class="dashicons dashicons-update" style="margin-top: -0.1em"></span> UPDATE NOW ]</a></td>
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
                    <h2>Functions</h2>
                    <p>The following static functions can be used anywhere within wordpress core:</p>
                </div>
            </div>

            <?php // HTML ends
        }

    }

?>