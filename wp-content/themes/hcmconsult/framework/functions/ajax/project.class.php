<?php

namespace PS\Functions\Ajax;

/**
 * Class Project
 * @package     PS\Functions\Ajax
 * @since       1.0.0
 */
class Project {

    public function __construct() {
        // ajax-loading
        add_action( 'wp_ajax_loading_of_projects', array( $this, 'loading_of_projects' ) );
        add_action( 'wp_ajax_nopriv_loading_of_projects', array( $this, 'loading_of_projects' ) );
    }

    // ajax-loading
    public function loading_of_projects() {
        ob_start();

        // vars
        global $wp_query;
        $args = unserialize( stripslashes( $_POST['query'] ) );
        $args['paged']  = (int)$_POST['page'] + 1;
        $args['post_status'] = 'publish';

        // query
        query_posts( $args );
        $custom_query = $wp_query;
        if ( $custom_query->have_posts() ):
            while( $custom_query->have_posts() ): $custom_query->the_post();
                get_template_part('parts/elements/project');
            endwhile;
        else:
            wp_send_json_error(); // {"success":false}
        endif;
        wp_reset_query();

        // return
        $posts = ob_get_clean();
        echo json_encode(
            array(
                'success' => true,
                'posts'   => $posts
            ),
            JSON_UNESCAPED_UNICODE
        );
        exit();
    }

}