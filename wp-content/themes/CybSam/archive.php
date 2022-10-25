<!-- archive -->

<?php get_header(); ?>
<h5><?php single_cat_title(); ?></h5>

            <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
                <h4><?php the_title() ?></h4>

                    <?php the_excerpt(); ?>
                    <a href="<?php the_permalink();  ?>">Read More</a>
                
                <?php endwhile; endif; ?>
                



<?php get_footer(); ?>

