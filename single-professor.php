<?php

    get_header();

    while(have_posts()){
        the_post(); 
        pageBanner();
        ?>

        <div class="container container--narrow page-section">

            <div class="generic-content">
                <div class="row group">
                    <div class="one-third">
                        <?php the_post_thumbnail('professorPortrait'); ?>
                    </div>
                    <div class="two-thirds">
                        <?php the_content(); ?>
                    </div>
                    <?php 
                        //print_r($pageBannerImage); 
                    ?>
                </div>
            </div>
            
            <?php 
                //Below will return a wordpress post object
                $relatedPrograms = get_field('related_programs');
                //print_r($relatedPrograms);

                if($relatedPrograms){
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
                    echo '<ul class="link-list min-list">';
                    foreach($relatedPrograms as $program){ ?>
                    <?php //because we're not in a main loop, you cannot only use the_title(). You need to use the get_the_title() and pass in a wordpress object ?>
                        <li class="xxx"><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>
                    <?php }
                    echo '</ul>';
                }
                
            ?>

        </div> 
        
    <?php }

    get_footer();
?>