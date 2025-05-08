<?php
/**
 * The template for displaying the one-page scrolling front page.
 *
 * @package EricPortfolioTheme
 */

get_header();
?>

<div id="frontpage-container">
    <aside id="page-nav" class="frontpage-sidebar">
        <ul>
            <li><a href="#home-section" class="active-section">Home</a></li>
            <li><a href="#about-section">About</a></li>
            <li><a href="#projects-section">Projects</a></li>
            <li><a href="#contact-section">Contact</a></li>
        </ul>
    </aside>

    <main id="frontpage-main-content">
        <section id="home-section" class="full-page-section">
            <div class="section-content hero-content">
                <h1><?php echo esc_html(get_theme_mod('ep_hero_heading', '[Your Name]')); ?></h1>
                <p class="subheading"><?php echo wp_kses_post(get_theme_mod('ep_hero_subheading', '[Your 2-line Subheading/Description]')); ?></p>
                <?php
                $cv_text = get_theme_mod('ep_hero_cv_button_text', 'View My CV');
                $cv_url = get_theme_mod('ep_hero_cv_button_url', '#');
                if ($cv_url && $cv_text) : ?>
                    <a href="<?php echo esc_url($cv_url); ?>" class="cv-button"><?php echo esc_html($cv_text); ?></a>
                <?php endif; ?>
            </div>
            <button class="next-section-button" data-target="#about-section">↓</button>
        </section>

        <section id="about-section" class="full-page-section">
            <div class="section-content about-content">
                <h2><?php echo esc_html( get_theme_mod('ep_about_title', 'About Me') ); ?></h2>
                <?php 
                $about_image_id = get_theme_mod('ep_about_image');
                if ( $about_image_id ) :
                    echo wp_get_attachment_image( $about_image_id, 'medium', false, array('class'=>'about-image') );
                endif; 
                ?>
                <div class="about-content">
                    <?php echo wp_kses_post(get_theme_mod('ep_about_content', 'A little bit about myself...')); ?>
                </div>
            </div>
            <button class="next-section-button" data-target="#projects-section">↓</button>
        </section>

        <section id="projects-section" class="full-page-section">
            <div class="section-content projects-content">
                <h2><?php echo esc_html( get_theme_mod('ep_projects_title', 'My Projects') ); ?></h2>
                <div class="swiper project-carousel">
                    <div class="swiper-wrapper">
                        <?php
                        $project_args = array(
                            'post_type' => 'project',
                            'posts_per_page' => -1,
                        );
                        $projects_query = new WP_Query( $project_args );
                        
                        if ( $projects_query->have_posts() ) :
                            while ( $projects_query->have_posts() ) : $projects_query->the_post();
                                $github_url = get_post_meta( get_the_ID(), '_epp_github_url', true );
                                $demo_url = get_post_meta( get_the_ID(), '_epp_demo_url', true );
                                $short_desc = get_post_meta( get_the_ID(), '_epp_short_description', true);
                                $project_main_image_url = '';
                                if (has_post_thumbnail()) {
                                    $project_main_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                    error_log('Project image URL: ' . $project_main_image_url);
                                } else {
                                    $content = get_the_content();
                                    preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
                                    if (!empty($matches[1][0])) {
                                        $project_main_image_url = $matches[1][0];
                                    } else {
                                        $project_main_image_url = get_template_directory_uri() . '/assets/images/placeholder.png';
                                    }
                                }
                                ?>
                                <div class="swiper-slide">
                                    <article class="project-slide-item">
                                        <div class="project-image-container">
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                                <img src="<?php echo esc_url($project_main_image_url); ?>" 
                                                     alt="<?php the_title_attribute(); ?>" 
                                                     class="project-carousel-image">
                                            </a>
                                        </div>
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <?php if($short_desc): ?>
                                            <p class="project-short-desc"><?php echo esc_html($short_desc); ?></p>
                                        <?php endif; ?>
                                        <div class="project-tech-stack">
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
                                            <?php if ( $github_url ) : ?>
                                                <a href="<?php echo esc_url( $github_url ); ?>" class="project-button github-button" target="_blank" rel="noopener noreferrer">GitHub</a>
                                            <?php endif; ?>
                                            <?php if ( $demo_url ) : ?>
                                                <a href="<?php echo esc_url( $demo_url ); ?>" class="project-button demo-button" target="_blank" rel="noopener noreferrer">Demo</a>
                                            <?php endif; ?>
                                        </div>
                                    </article>
                                </div>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<p>No projects yet!</p>';
                        endif;
                        ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
            <button class="next-section-button" data-target="#contact-section">↓</button>
        </section>

        <section id="contact-section" class="full-page-section">
            <div class="section-content contact-content">
                <h2><?php echo esc_html( get_theme_mod('ep_contact_title', 'Get In Touch') ); ?></h2>
                <div><?php echo wpautop( wp_kses_post( get_theme_mod('ep_contact_content', 'Feel free to reach out...') ) ); ?></div>
            </div>
            <button class="next-section-button" data-target="#home-section">↑</button>
        </section>
    </main>
</div>

<?php get_footer(); ?>
