<?php
/**
 * Plugin Name:       Eric's Portfolio Projects
 * Description:       Registers "Project" CPT and "Technologies" taxonomy for Eric's Portfolio.
 * Version:           1.0.0
 * Author:            Eric Nie
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       eric-portfolio-projects
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function epp_register_project_cpt() {
    $labels = [
        'name'               => _x( 'Projects', 'post type general name', 'eric-portfolio-projects' ),
        'singular_name'      => _x( 'Project', 'post type singular name', 'eric-portfolio-projects' ),
        'menu_name'          => _x( 'Projects', 'admin menu', 'eric-portfolio-projects' ),
        'name_admin_bar'     => _x( 'Project', 'add new on admin bar', 'eric-portfolio-projects' ),
        'add_new'            => _x( 'Add New', 'project', 'eric-portfolio-projects' ),
        'add_new_item'       => __( 'Add New Project', 'eric-portfolio-projects' ),
        'new_item'           => __( 'New Project', 'eric-portfolio-projects' ),
        'edit_item'          => __( 'Edit Project', 'eric-portfolio-projects' ),
        'view_item'          => __( 'View Project', 'eric-portfolio-projects' ),
        'all_items'          => __( 'All Projects', 'eric-portfolio-projects' ),
    ];
    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'project', 'with_front' => true],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-portfolio',
    ];
    
    error_log('Registering Project CPT with args: ' . print_r($args, true));
    
    register_post_type( 'project', $args );
}
add_action( 'init', 'epp_register_project_cpt' );

function epp_register_technology_taxonomy() {
    $labels = [
        'name'              => _x( 'Technologies', 'taxonomy general name', 'eric-portfolio-projects' ),
        'singular_name'     => _x( 'Technology', 'taxonomy singular name', 'eric-portfolio-projects' ),
        'search_items'      => __( 'Search Technologies', 'eric-portfolio-projects' ),
        'all_items'         => __( 'All Technologies', 'eric-portfolio-projects' ),
        'parent_item'       => __( 'Parent Technology', 'eric-portfolio-projects' ),
        'parent_item_colon' => __( 'Parent Technology:', 'eric-portfolio-projects' ),
        'edit_item'         => __( 'Edit Technology', 'eric-portfolio-projects' ),
        'update_item'       => __( 'Update Technology', 'eric-portfolio-projects' ),
        'add_new_item'      => __( 'Add New Technology', 'eric-portfolio-projects' ),
        'new_item_name'     => __( 'New Technology Name', 'eric-portfolio-projects' ),
        'menu_name'         => __( 'Technologies', 'eric-portfolio-projects' ),
    ];
    $args = [
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'tech'],
        'show_in_rest'      => true,
    ];
    register_taxonomy( 'technology', ['project'], $args );
}
add_action( 'init', 'epp_register_technology_taxonomy' );

function epp_project_meta_boxes() {
    add_meta_box(
        'epp_project_details',
        __( 'Project Details', 'eric-portfolio-projects' ),
        'epp_render_project_details_mb',
        'project',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'epp_project_meta_boxes' );

function epp_render_project_details_mb( $post ) {
    wp_nonce_field( 'epp_save_project_details', 'epp_project_details_nonce' );

    $subheader = get_post_meta( $post->ID, '_epp_subheader', true );
    $github_url = get_post_meta( $post->ID, '_epp_github_url', true );
    $demo_url = get_post_meta( $post->ID, '_epp_demo_url', true );
    $short_desc = get_post_meta( $post->ID, '_epp_short_description', true );

    // Add debug output
    error_log('Current meta values:');
    error_log('Subheader: ' . $subheader);
    error_log('Short description: ' . $short_desc);
    error_log('GitHub URL: ' . $github_url);
    error_log('Demo URL: ' . $demo_url);

    echo '<p><label for="epp_subheader">' . __( 'Subheader:', 'eric-portfolio-projects' ) . '</label><br>';
    echo '<input type="text" id="epp_subheader" name="epp_subheader" value="' . esc_attr( $subheader ) . '" style="width:100%;"></p>';

    echo '<p><label for="epp_short_description">' . __( 'Short Description (for front page carousel):', 'eric-portfolio-projects' ) . '</label><br>';
    echo '<textarea id="epp_short_description" name="epp_short_description" rows="3" style="width:100%;">' . esc_textarea( $short_desc ) . '</textarea></p>';

    echo '<p><label for="epp_github_url">' . __( 'GitHub URL:', 'eric-portfolio-projects' ) . '</label><br>';
    echo '<input type="url" id="epp_github_url" name="epp_github_url" value="' . esc_url( $github_url ) . '" style="width:100%;"></p>';

    echo '<p><label for="epp_demo_url">' . __( 'Demo URL (e.g., YouTube):', 'eric-portfolio-projects' ) . '</label><br>';
    echo '<input type="url" id="epp_demo_url" name="epp_demo_url" value="' . esc_url( $demo_url ) . '" style="width:100%;"></p>';

    for ($i = 1; $i <= 4; $i++) {
        $image_url = get_post_meta( $post->ID, '_epp_image_' . $i . '_url', true );
        echo '<p><label for="epp_image_' . $i . '_url">' . sprintf(__( 'Image %s URL:', 'eric-portfolio-projects' ), $i) . '</label><br>';
        echo '<input type="url" id="epp_image_' . $i . '_url" name="epp_image_' . $i . '_url" value="' . esc_url( $image_url ) . '" style="width:80%;">';
        echo '<button type="button" class="button upload_image_button">' . __('Upload Image', 'eric-portfolio-projects') . '</button></p>';
        if ($image_url) {
            echo '<img src="'.esc_url($image_url).'" style="max-width:200px;height:auto;display:block;margin-top:5px;">';
        }
    }
}

function epp_save_project_details( $post_id ) {
    if ( ! isset( $_POST['epp_project_details_nonce'] ) || ! wp_verify_nonce( $_POST['epp_project_details_nonce'], 'epp_save_project_details' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['post_type'] ) && 'project' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Add error logging
    error_log('Saving project details for post ID: ' . $post_id);
    error_log('POST data: ' . print_r($_POST, true));

    if ( isset( $_POST['epp_subheader'] ) ) {
        update_post_meta( $post_id, '_epp_subheader', sanitize_text_field( $_POST['epp_subheader'] ) );
        error_log('Saved subheader: ' . $_POST['epp_subheader']);
    }
    if ( isset( $_POST['epp_short_description'] ) ) {
        update_post_meta( $post_id, '_epp_short_description', sanitize_textarea_field( $_POST['epp_short_description'] ) );
        error_log('Saved short description');
    }
    if ( isset( $_POST['epp_github_url'] ) ) {
        update_post_meta( $post_id, '_epp_github_url', esc_url_raw( $_POST['epp_github_url'] ) );
        error_log('Saved GitHub URL: ' . $_POST['epp_github_url']);
    }
    if ( isset( $_POST['epp_demo_url'] ) ) {
        update_post_meta( $post_id, '_epp_demo_url', esc_url_raw( $_POST['epp_demo_url'] ) );
        error_log('Saved demo URL');
    }
    for ($i = 1; $i <= 4; $i++) {
        if ( isset( $_POST['epp_image_' . $i . '_url'] ) ) {
            update_post_meta( $post_id, '_epp_image_' . $i . '_url', esc_url_raw( $_POST['epp_image_' . $i . '_url'] ) );
            error_log('Saved image ' . $i . ' URL: ' . $_POST['epp_image_' . $i . '_url']);
        }
    }
}
add_action( 'save_post', 'epp_save_project_details' );

function epp_plugin_activate() {
    epp_register_project_cpt();
    epp_register_technology_taxonomy();
    
    flush_rewrite_rules();
    
    error_log('Plugin activated - rewrite rules flushed');
}
register_activation_hook( __FILE__, 'epp_plugin_activate' );

function epp_plugin_deactivate() {
    flush_rewrite_rules();
    error_log('Plugin deactivated - rewrite rules flushed');
}
register_deactivation_hook( __FILE__, 'epp_plugin_deactivate' );

function epp_flush_rewrite_on_project_save($post_id) {
    if (get_post_type($post_id) === 'project') {
        flush_rewrite_rules();
        error_log('Project saved - rewrite rules flushed');
    }
}
add_action('save_post', 'epp_flush_rewrite_on_project_save');

function epp_enqueue_admin_scripts() {
    wp_enqueue_media();
    wp_enqueue_script(
        'epp-admin-script',
        plugins_url( 'js/admin.js', __FILE__ ),
        array( 'jquery' ),
        '1.0.0',
        true
    );
}
add_action( 'admin_enqueue_scripts', 'epp_enqueue_admin_scripts' );
