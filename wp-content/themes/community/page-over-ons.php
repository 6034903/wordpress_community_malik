<?php
/**
 * Template Name: Over Ons
 * Description: Custom About Us page template with editable content via Customizer
 */

get_header();

// Get customizer settings
$about_title = get_theme_mod('about_title', 'Over Ons');
$about_subtitle = get_theme_mod('about_subtitle', 'Welkom bij The Software Syndicate, waar innovatie en samenwerking samenkomen. Wij zijn een community van ontwikkelaars, denkers en makers die geloven in de kracht van collectieve intelligentie.');

$manifest_title = get_theme_mod('manifest_title', 'Ons Manifest');

$feature1_title = get_theme_mod('feature1_title', 'Samenwerking');
$feature1_description = get_theme_mod('feature1_description', 'Wij geloven dat de grootste doorbraken ontstaan wanneer we samenwerken. Kennis delen en elkaar ondersteunen is onze kern.');

$feature2_title = get_theme_mod('feature2_title', 'Continue Groei');
$feature2_description = get_theme_mod('feature2_description', 'De wereld van software staat nooit stil, en wij ook niet. We omarmen levenslang leren en experimenteren.');

$feature3_title = get_theme_mod('feature3_title', 'Innovatie');
$feature3_description = get_theme_mod('feature3_description', 'Constante innovatie is de drijvende kracht achter onze community. We zoeken altijd naar nieuwe en betere manieren.');

$feature4_title = get_theme_mod('feature4_title', 'Impact Maken');
$feature4_description = get_theme_mod('feature4_description', 'Onze projecten zijn meer dan alleen code; ze zijn bedoeld om een positieve impact te hebben op de wereld.');

$vision_title = get_theme_mod('vision_title', 'Onze Visie');
$vision_description = get_theme_mod('vision_description', 'Wij streven ernaar een wereld te creëren waarin elke ontwikkelaar de middelen en de gemeenschap heeft om hun ideeën tot leven te brengen en de toekomst van technologie vorm te geven.');

// Get images
$about_hero_image = get_theme_mod('about_hero_image', '');
$about_vision_image = get_theme_mod('about_vision_image', '');
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="about-hero-section">
        <div class="container">
            <h1 class="about-title"><?php echo esc_html($about_title); ?></h1>
            <p class="about-subtitle"><?php echo esc_html($about_subtitle); ?></p>
        </div>
    </section>

    <!-- Hero Image Section -->
    <?php if (!empty($about_hero_image)) : ?>
    <section class="about-hero-image-section">
        <div class="container">
            <div class="hero-image-wrapper">
                <img src="<?php echo esc_url($about_hero_image); ?>" alt="<?php echo esc_attr($about_title); ?>" class="hero-image">
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Manifest Section -->
    <section class="manifest-section">
        <div class="container">
            <h2 class="manifest-title"><?php echo esc_html($manifest_title); ?></h2>

            <div class="features-grid">
                <!-- Feature 1: Samenwerking -->
                <div class="feature-item">
                    <div class="feature-icon">
                        <span class="dashicons dashicons-groups"></span>
                    </div>
                    <h3 class="feature-title"><?php echo esc_html($feature1_title); ?></h3>
                    <p class="feature-description"><?php echo esc_html($feature1_description); ?></p>
                </div>

                <!-- Feature 2: Continue Groei -->
                <div class="feature-item">
                    <div class="feature-icon">
                        <span class="dashicons dashicons-chart-line"></span>
                    </div>
                    <h3 class="feature-title"><?php echo esc_html($feature2_title); ?></h3>
                    <p class="feature-description"><?php echo esc_html($feature2_description); ?></p>
                </div>

                <!-- Feature 3: Innovatie -->
                <div class="feature-item">
                    <div class="feature-icon">
                        <span class="dashicons dashicons-lightbulb"></span>
                    </div>
                    <h3 class="feature-title"><?php echo esc_html($feature3_title); ?></h3>
                    <p class="feature-description"><?php echo esc_html($feature3_description); ?></p>
                </div>

                <!-- Feature 4: Impact Maken -->
                <div class="feature-item">
                    <div class="feature-icon">
                        <span class="dashicons dashicons-admin-site"></span>
                    </div>
                    <h3 class="feature-title"><?php echo esc_html($feature4_title); ?></h3>
                    <p class="feature-description"><?php echo esc_html($feature4_description); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Image Section -->
    <?php if (!empty($about_vision_image)) : ?>
    <section class="about-vision-image-section">
        <div class="container">
            <div class="vision-image-wrapper">
                <img src="<?php echo esc_url($about_vision_image); ?>" alt="<?php echo esc_attr($vision_title); ?>" class="vision-image">
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Vision Section -->
    <section class="vision-section">
        <div class="container">
            <h2 class="vision-title"><?php echo esc_html($vision_title); ?></h2>
            <p class="vision-description"><?php echo esc_html($vision_description); ?></p>
        </div>
    </section>
</main>

<?php get_footer(); ?>
