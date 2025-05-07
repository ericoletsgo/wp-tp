<?php get_header(); ?>

<main id="primary" class="site-main single-project-main">
    <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                <?php 
                $subheader = get_post_meta( get_the_ID(), '_epp_subheader', true );
                if ( $subheader ) : ?>
                    <p class="project-subheader"><?php echo esc_html( $subheader ); ?></p>
                <?php endif; ?>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="project-featured-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <div class="project-gallery">
                <?php 
                for ($i = 1; $i <= 4; $i++) :
                    $image_url = get_post_meta( get_the_ID(), '_epp_image_' . $i . '_url', true );
                    if ( $image_url ) : ?>
                        <img src="<?php echo esc_url( $image_url ); ?>" 
                             alt="<?php the_title_attribute(); ?> - Image <?php echo $i; ?>" 
                             class="project-gallery-image">
                    <?php endif;
                endfor; ?>
            </div>

            <footer class="entry-footer project-meta">
                <div class="project-tech-stack">
                    <strong>Technologies:</strong>
                    <?php
                    $technologies = get_the_terms( get_the_ID(), 'technology' );
                    if ( $technologies && ! is_wp_error( $technologies ) ) :
                        foreach ( $technologies as $tech ) : ?>
                            <span class="tech-button"><?php echo esc_html( $tech->name ); ?></span>
                        <?php endforeach;
                    endif;
                    ?>
                </div>
                <div class="project-links">
                    <?php
                    $github_url = get_post_meta( get_the_ID(), '_epp_github_url', true );
                    $demo_url = get_post_meta( get_the_ID(), '_epp_demo_url', true );
                    if ( $github_url ) : ?>
                        <a href="<?php echo esc_url( $github_url ); ?>" 
                           class="project-button github-button" 
                           target="_blank" 
                           rel="noopener noreferrer">View on GitHub</a>
                    <?php endif; 
                    if ( $demo_url ) : ?>
                        <a href="<?php echo esc_url( $demo_url ); ?>" 
                           class="project-button demo-button" 
                           target="_blank" 
                           rel="noopener noreferrer">View Demo</a>
                    <?php endif; ?>
                </div>
            </footer>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
