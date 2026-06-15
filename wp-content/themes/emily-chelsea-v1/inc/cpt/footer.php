<?php

/**
 * Register Project Post Type
 *
 * @category   CPT
 * @author     TTG
 * @link       https://technologytherapy.com/
 */


function register_footer_cpt()
{

    $singular = 'Footer';
    $plural   = $singular . 's';

    $labels = array(
        'name'               => $plural,
        'singular_name'      => $singular,
        'menu_name'          => $plural,
        'name_admin_bar'     => $singular,
        'add_new'            => 'Add New ' . $singular,
        'add_new_item'       => 'Add New ' . $singular,
        'new_item'           => 'New ' . $singular,
        'edit_item'          => 'Edit ' . $singular,
        'view_item'          => 'View ' . $singular,
        'all_items'          => 'All ' . $plural,
        'search_items'       => 'Search ' . $plural,
        'parent_item_colon'  => 'Parent ' . $plural,
        'not_found'          => 'No ' . $plural . ' found',
        'not_found_in_trash' => 'No ' . $plural . ' found in trash',
    );

    $args = array(
        'labels'             => $labels,
        'description'        => '',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'footer'),
        'capability_type'    => 'page',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title')
    );

    register_post_type('footer', $args);
}

add_action('init', 'register_footer_cpt', 0);
