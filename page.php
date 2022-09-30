<?php

    get_header();

    while(have_posts()){
        the_post(); 
        pageBanner(array(
            'title' => 'Hello there, this is the title',
            'subtitle' => 'Hi this is the subtitle',
            'photo' => 'https://img.freepik.com/free-photo/african-american-student-walking-street-talking-phone_1303-12696.jpg?w=740&t=st=1664539376~exp=1664539976~hmac=511c2787bc88a84188d1f5141c2a5f68672044a42fcc9dac597de37dc6c2804d'
        ));
        ?>

        <div class="container container--narrow page-section">

            <?php
                $theParent = wp_get_post_parent_id(get_the_ID());
                if($theParent){ ?>
                    <div class="metabox metabox--position-up metabox--with-home-link">
                        <p>
                            <a class="metabox__blog-home-link" href="<?php echo get_the_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
                        </p>
                    </div>
                <?php }
            ?>
            

            <?php 
                //if the page has a parent or if it is a child page
                $testArray = get_pages(array(
                    'child_of' => get_the_ID()
                ));

                if ($theParent or $testArray ){ ?>
                    <div class="page-links">
                        <h2 class="page-links__title"><a href="<?php echo get_the_permalink($theParent);?>"><?php echo get_the_title($theParent); ?></a></h2>
                        <ul class="min-list">
                            <?php 
                            
                            if($theParent){
                                $findChildrenOf = $theParent;
                            } else {
                                $findChildrenOf = get_the_ID();
                            }
                            //if its a parent page, we want to display all child pages of the current page
                            //if it's a child page, we dont
                                wp_list_pages(array(
                                    'title_li'=> NULL,
                                    'child_of' => $findChildrenOf,
                                    'sort_column' => 'menu_order'
                                ));
                            ?>
                        </ul>
                    </div>
                <?php }
            ?>

            <div class="generic-content">
                <?php the_content(); ?>
            </div>
        </div>
        
    <?php }

    get_footer();
?>
