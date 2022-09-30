<?php 

    function pageBanner($args = NULL){
        if(!$args['title']){
            $args['title'] = get_the_title();
        }

        if(!$args['subtitle']){
            $args['subtitle'] = get_field('page_banner_subtitle');
        }

        if(!$args['photo']){
            if(get_field('page_banner_background_image')) {
                $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
            } else {
                $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
            }
        }

        ?>
        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'] ?>)"></div>
                <div class="page-banner__content container container--narrow">
                    <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
                    <div class="page-banner__intro">
                    <?php echo $args['subtitle']; ?>
                </div>
            </div>
        </div>
        <?php
    }

    function university_files(){
        wp_enqueue_script('mainuniversityjs', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i, 700, 700i');
        wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
        wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
    }
    add_action('wp_enqueue_scripts', 'university_files');

    function university_features (){
        //register_nav_menu('headerMenuLocation','Header Menu Location');
        //register_nav_menu('footerLocationOne','Footer Location One');
        //register_nav_menu('footerLocationTwo','Footer Location Two');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        
        //below registers custom image sizes.. the true value speaks to whether you want to crop
        add_image_size('professorLandscape', 400, 260, true);
        add_image_size('professorPortrait', 480, 650, true);
        add_image_size('pageBanner', 1500, 350, true);
    }
    add_action('after_setup_theme', 'university_features');

    //Registering a new Events Post Type
    //These should ideally live in the wp-content directory in a folder called mu-plugins, in a file name of your choosing
    //Im keeping this code here in order to remain a part of the git repo for the theme
        function university_post_types(){
            //Event Post Type
            register_post_type('event', array(
                'show_in_rest' => true,
                'supports' => array('title','editor','excerpt'),
                'rewrite' => array('slug' => 'events'),
                'has_archive' => true,
                'public' => true,
                'labels' => array(
                    'name' => 'Events',
                    'add_new_item' => 'Add New Event',
                    'edit_item' => 'Edit Event',
                    'all_items' => 'All Events',
                    'singular_name' => 'Event'
                ),
                'menu_icon' => 'dashicons-calendar'
            ));

            //Program Post Type
            register_post_type('program', array(
                'show_in_rest' => true,
                'supports' => array('title','editor'),
                'rewrite' => array('slug' => 'programs'),
                'has_archive' => true,
                'public' => true,
                'labels' => array(
                    'name' => 'Programs',
                    'add_new_item' => 'Add New Program',
                    'edit_item' => 'Edit Program',
                    'all_items' => 'All Programs',
                    'singular_name' => 'Program'
                ),
                'menu_icon' => 'dashicons-awards'
            ));

            //Professor Post Type
            register_post_type('professor', array(
                'show_in_rest' => true,
                'supports' => array('title','editor', 'thumbnail'),
                //'rewrite' => array('slug' => 'professors'),
                //'has_archive' => true,
                'public' => true,
                'labels' => array(
                    'name' => 'Professors',
                    'add_new_item' => 'Add New Professor',
                    'edit_item' => 'Edit Professor',
                    'all_items' => 'All Professors',
                    'singular_name' => 'Professor'
                ),
                'menu_icon' => 'dashicons-welcome-learn-more'
            ));

        }
        add_action('init', 'university_post_types');

        //Modifying a default query (ie, getting events query on an events archive URL)
        function university_adjust_queries ($query){
            if (!is_admin() AND is_post_type_archive('program') AND is_main_query() ){
                $query->set('orderby', 'title');
                $query->set('order','ASC');
                $query->set('posts_per_page','-1');
            }
            
            if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query() ){
                $today = date('Ymd');
                $query->set('meta_key', 'event_date');
                $query->set('orderby','meta_value_num');
                $query->set('order','ASC');
                $query->set('meta_query', array(
                    array(
                      'key' => 'event_date',
                      'compare' => '>=',
                      'value' => $today,
                      'type' => 'numeric'
                    )
                  ));
            }
        }
        add_action('pre_get_posts', 'university_adjust_queries');

