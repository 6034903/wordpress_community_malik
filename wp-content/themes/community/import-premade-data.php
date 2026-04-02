<?php
/**
 * Premade Data Import Script
 * Run this script once to populate your WordPress site with sample data
 */

// Make sure we're running in WordPress context
require_once('../../../wp-config.php');
require_once('../../../wp-load.php');

// Start session if not already started
if (!session_id()) {
    session_start();
}

// Check if user is admin (simple protection)
if (!current_user_can('manage_options')) {
    die('You must be an administrator to run this script.');
}

// Sample data arrays (6 items each)
$sample_projects = [
    [
        'title' => 'Community Website Redesign',
        'project_github_repo' => 'https://github.com/example/community-redesign',
        'project_tech_stack' => 'WordPress, React, Tailwind CSS, Node.js',
        'project_about' => 'A complete redesign of the community website with modern UI/UX principles and responsive design. Features include dark mode, improved navigation, and mobile-first approach. The project involved creating custom WordPress themes, implementing React components for dynamic content, and optimizing performance for better user experience.',
        'project_contribution' => 'Led the frontend development team, designed the UI/UX system architecture, implemented responsive design patterns, optimized website performance, and integrated React components with WordPress REST API for seamless content management.'
    ],
    [
        'title' => 'Mobile App for Event Management',
        'project_github_repo' => 'https://github.com/example/event-app',
        'project_tech_stack' => 'React Native, Firebase, Node.js, Redux',
        'project_about' => 'Native mobile application for managing community events, registrations, and notifications. Includes real-time updates, social features, push notifications, and offline capabilities. The app supports event creation, ticket sales, attendee management, and live streaming integration.',
        'project_contribution' => 'Developed the mobile application architecture using React Native, implemented Firebase real-time database for live updates, created Redux state management for complex data flow, designed intuitive user interfaces, and integrated payment gateway for ticket sales.'
    ],
    [
        'title' => 'E-commerce Platform Integration',
        'project_github_repo' => 'https://github.com/example/ecommerce-integration',
        'project_tech_stack' => 'PHP, Stripe API, WooCommerce, REST API',
        'project_about' => 'Integration of third-party payment systems and inventory management for the community shop. Supports multiple payment gateways, real-time inventory tracking, automated order processing, and advanced analytics dashboard with comprehensive reporting features.',
        'project_contribution' => 'Designed and implemented API integrations for multiple payment processors, developed custom inventory management system, created automated order processing workflows, built analytics dashboard for sales reporting, and ensured PCI compliance for payment security.'
    ],
    [
        'title' => 'Community Forum System',
        'project_github_repo' => 'https://github.com/example/community-forum',
        'project_tech_stack' => 'PHP, MySQL, JavaScript, Bootstrap',
        'project_about' => 'Custom forum system with advanced features like threaded discussions, user profiles, moderation tools, rich text editor, file attachments, search functionality, and real-time notifications. Includes spam protection, user reputation system, and comprehensive admin tools.',
        'project_contribution' => 'Architected the database schema for complex forum relationships, implemented threaded discussion algorithms, created moderation tools with automated spam detection, developed user authentication and profile systems, and built responsive frontend with Bootstrap.'
    ],
    [
        'title' => 'AI-Powered Content Recommendation',
        'project_github_repo' => 'https://github.com/example/ai-recommendation',
        'project_tech_stack' => 'Python, TensorFlow, WordPress API, React',
        'project_about' => 'Machine learning system that recommends relevant content to users based on their preferences and behavior. Uses collaborative filtering, natural language processing, and user engagement analytics to provide personalized content suggestions across multiple content types.',
        'project_contribution' => 'Developed machine learning models using TensorFlow for content classification, implemented collaborative filtering algorithms, created Python APIs for WordPress integration, built React dashboard for analytics, and designed user feedback system for model improvement.'
    ],
    [
        'title' => 'Real-time Collaboration Tools',
        'project_github_repo' => 'https://github.com/example/collaboration-tools',
        'project_tech_stack' => 'WebRTC, Socket.io, Node.js, Vue.js',
        'project_about' => 'Suite of real-time collaboration tools including document editing, video conferencing, project management, and team communication. Features include screen sharing, file collaboration, task tracking, and integration with popular productivity tools like Slack and Trello.',
        'project_contribution' => 'Implemented WebRTC for peer-to-peer video conferencing, created Socket.io server for real-time communication, developed Vue.js frontend with reactive components, integrated third-party APIs for productivity tools, and optimized performance for low-latency collaboration.'
    ]
];

$sample_tutorials = [
    [
        'title' => 'Getting Started with WordPress Development',
        'content' => 'Learn the basics of WordPress theme and plugin development. This tutorial covers custom post types, meta boxes, and theme customization.',
        'tutorial_content' => 'Complete guide to WordPress development fundamentals including hooks, filters, and custom functionality.',
        'tutorial_difficulty' => 'beginner',
        'tutorial_estimated_time' => '2 hours',
        'tutorial_prerequisites' => 'Basic HTML, CSS, and PHP knowledge'
    ],
    [
        'title' => 'Advanced Custom Post Types and Taxonomies',
        'content' => 'Deep dive into creating complex custom post types with custom taxonomies, meta fields, and archive templates.',
        'tutorial_content' => 'Advanced techniques for custom post types with complex relationships and custom queries.',
        'tutorial_difficulty' => 'advanced',
        'tutorial_estimated_time' => '4 hours',
        'tutorial_prerequisites' => 'WordPress development experience, PHP knowledge'
    ],
    [
        'title' => 'Building Responsive Themes with Tailwind CSS',
        'content' => 'Learn how to integrate Tailwind CSS into WordPress themes for modern, responsive design.',
        'tutorial_content' => 'Complete guide to Tailwind CSS integration with WordPress theme development.',
        'tutorial_difficulty' => 'intermediate',
        'tutorial_estimated_time' => '3 hours',
        'tutorial_prerequisites' => 'CSS knowledge, basic WordPress theme development'
    ],
    [
        'title' => 'WordPress Performance Optimization',
        'content' => 'Techniques for optimizing WordPress site performance including caching, database optimization, and asset management.',
        'tutorial_content' => 'Comprehensive performance optimization strategies for WordPress websites.',
        'tutorial_difficulty' => 'intermediate',
        'tutorial_estimated_time' => '3 hours',
        'tutorial_prerequisites' => 'WordPress administration, basic web performance concepts'
    ],
    [
        'title' => 'Creating Custom WordPress Plugins',
        'content' => 'Step-by-step guide to creating WordPress plugins from scratch with best practices and security considerations.',
        'tutorial_content' => 'Complete plugin development guide with hooks, security, and deployment.',
        'tutorial_difficulty' => 'intermediate',
        'tutorial_estimated_time' => '5 hours',
        'tutorial_prerequisites' => 'PHP knowledge, WordPress development basics'
    ],
    [
        'title' => 'WordPress REST API Development',
        'content' => 'Learn to build and consume WordPress REST APIs for headless WordPress applications.',
        'tutorial_content' => 'REST API development for headless WordPress and mobile applications.',
        'tutorial_difficulty' => 'advanced',
        'tutorial_estimated_time' => '4 hours',
        'tutorial_prerequisites' => 'JavaScript, REST API concepts, WordPress development'
    ]
];

$sample_events = [
    [
        'title' => 'Community Hackathon 2024',
        'content' => 'Annual hackathon event where community members collaborate on innovative projects. Prizes, food, and fun!',
        'event_type' => 'hackathon',
        'event_date' => '2024-06-15',
        'event_location' => 'Community Center, Amsterdam',
        'event_status' => 'upcoming',
        'event_description' => '48-hour hackathon with prizes, mentorship, and networking opportunities.',
        'event_duration' => '48 hours',
        'event_prizes' => '€5000 in prizes, mentorship sessions, tech goodies'
    ],
    [
        'title' => 'WordPress Workshop: Plugin Development',
        'content' => 'Hands-on workshop for learning WordPress plugin development from scratch.',
        'event_type' => 'workshop',
        'event_date' => '2024-05-20',
        'event_location' => 'Tech Hub, Rotterdam',
        'event_status' => 'upcoming',
        'event_description' => 'Intensive workshop on WordPress plugin development best practices.',
        'event_duration' => '6 hours',
        'event_prizes' => 'Certificate of completion, plugin starter templates'
    ],
    [
        'title' => 'Community Meetup & Networking',
        'content' => 'Monthly meetup for community members to share ideas, showcase projects, and network.',
        'event_type' => 'meetup',
        'event_date' => '2024-04-25',
        'event_location' => 'Co-working Space, Utrecht',
        'event_status' => 'upcoming',
        'event_description' => 'Informal networking event with project showcases and discussions.',
        'event_duration' => '3 hours',
        'event_prizes' => 'Best project showcase award, community recognition'
    ],
    [
        'title' => 'Frontend Development Bootcamp',
        'content' => 'Intensive bootcamp covering modern frontend technologies and best practices.',
        'event_type' => 'workshop',
        'event_date' => '2024-07-10',
        'event_location' => 'Digital Campus, The Hague',
        'event_status' => 'upcoming',
        'event_description' => 'Weekend bootcamp focusing on React, Vue, and modern CSS.',
        'event_duration' => '2 days',
        'event_prizes' => 'Certificate, job placement assistance, portfolio review'
    ],
    [
        'title' => 'Open Source Contribution Day',
        'content' => 'Dedicated day for contributing to open source projects with guidance from experienced maintainers.',
        'event_type' => 'workshop',
        'event_date' => '2024-08-05',
        'event_location' => 'Online + Various Locations',
        'event_status' => 'upcoming',
        'event_description' => 'Learn to contribute to open source projects with mentorship.',
        'event_duration' => '8 hours',
        'event_prizes' => 'Swag pack, contributor recognition, GitHub badges'
    ],
    [
        'title' => 'Annual Community Conference',
        'content' => 'Large-scale conference with speakers, workshops, and networking opportunities.',
        'event_type' => 'conference',
        'event_date' => '2024-09-20',
        'event_location' => 'Convention Center, Amsterdam',
        'event_status' => 'upcoming',
        'event_description' => 'Full-day conference with international speakers and workshops.',
        'event_duration' => '1 day',
        'event_prizes' => 'Conference certificate, networking opportunities, premium swag'
    ]
];

$sample_team = [
    [
        'title' => 'John Doe',
        'content' => 'Lead developer with 10+ years of experience in WordPress and full-stack development. Passionate about open source and community building.',
        'team_role' => 'Lead Developer',
        'team_email' => 'john.doe@example.com',
        'team_twitter' => '@johndoe',
        'team_linkedin' => 'linkedin.com/in/johndoe',
        'team_github' => 'github.com/johndoe',
        'team_bio' => 'Lead developer with 10+ years of experience in WordPress and full-stack development.'
    ],
    [
        'title' => 'Jane Smith',
        'content' => 'UX/UI designer specializing in user-centered design and accessibility. Creates beautiful and functional interfaces.',
        'team_role' => 'UX/UI Designer',
        'team_email' => 'jane.smith@example.com',
        'team_twitter' => '@janesmith',
        'team_linkedin' => 'linkedin.com/in/janesmith',
        'team_github' => 'github.com/janesmith',
        'team_bio' => 'UX/UI designer specializing in user-centered design and accessibility.'
    ],
    [
        'title' => 'Mike Johnson',
        'content' => 'Project manager and community organizer. Ensures projects run smoothly and community events are successful.',
        'team_role' => 'Project Manager',
        'team_email' => 'mike.johnson@example.com',
        'team_twitter' => '@mikejohnson',
        'team_linkedin' => 'linkedin.com/in/mikejohnson',
        'team_github' => 'github.com/mikejohnson',
        'team_bio' => 'Project manager and community organizer with 8 years of experience.'
    ],
    [
        'title' => 'Sarah Williams',
        'content' => 'Backend developer specializing in API development and database optimization. Expert in PHP and Node.js.',
        'team_role' => 'Backend Developer',
        'team_email' => 'sarah.williams@example.com',
        'team_twitter' => '@sarahwilliams',
        'team_linkedin' => 'linkedin.com/in/sarahwilliams',
        'team_github' => 'github.com/sarahwilliams',
        'team_bio' => 'Backend developer specializing in API development and database optimization.'
    ],
    [
        'title' => 'David Chen',
        'content' => 'DevOps engineer with expertise in cloud infrastructure and automation. Ensures smooth deployment and scaling.',
        'team_role' => 'DevOps Engineer',
        'team_email' => 'david.chen@example.com',
        'team_twitter' => '@davidchen',
        'team_linkedin' => 'linkedin.com/in/davidchen',
        'team_github' => 'github.com/davidchen',
        'team_bio' => 'DevOps engineer with expertise in cloud infrastructure and automation.'
    ],
    [
        'title' => 'Emily Brown',
        'content' => 'Content strategist and technical writer. Creates documentation, tutorials, and community content.',
        'team_role' => 'Content Strategist',
        'team_email' => 'emily.brown@example.com',
        'team_twitter' => '@emilybrown',
        'team_linkedin' => 'linkedin.com/in/emilybrown',
        'team_github' => 'github.com/emilybrown',
        'team_bio' => 'Content strategist and technical writer specializing in developer documentation.'
    ]
];

$sample_products = [
    [
        'title' => 'Community T-Shirt',
        'content' => 'High-quality cotton t-shirt with community logo. Available in multiple sizes and colors.',
        'product_price' => 25.00,
        'product_sku' => 'TSHIRT-001',
        'product_stock_status' => 'instock',
        'product_sizes' => 'S,M,L,XL,XXL',
        'product_colors' => 'Black,White,Navy Blue,Gray',
        'product_description' => 'Premium quality cotton t-shirt with embroidered community logo.'
    ],
    [
        'title' => 'Community Hoodie',
        'content' => 'Comfortable hoodie with embroidered community logo. Perfect for cooler weather.',
        'product_price' => 45.00,
        'product_sku' => 'HOODIE-001',
        'product_stock_status' => 'instock',
        'product_sizes' => 'S,M,L,XL,XXL',
        'product_colors' => 'Black,Navy Blue,Gray',
        'product_description' => 'Comfortable hoodie with embroidered community logo and kangaroo pocket.'
    ],
    [
        'title' => 'Community Mug',
        'content' => 'Ceramic mug with community logo. Microwave and dishwasher safe.',
        'product_price' => 12.00,
        'product_sku' => 'MUG-001',
        'product_stock_status' => 'instock',
        'product_sizes' => '',
        'product_colors' => 'Black,White,Blue',
        'product_description' => 'High-quality ceramic mug, microwave and dishwasher safe.'
    ],
    [
        'title' => 'Community Sticker Pack',
        'content' => 'Set of 5 vinyl stickers with various community designs and logos.',
        'product_price' => 8.00,
        'product_sku' => 'STICKER-001',
        'product_stock_status' => 'instock',
        'product_sizes' => '',
        'product_colors' => '',
        'product_description' => 'Durable vinyl stickers, waterproof and UV resistant.'
    ],
    [
        'title' => 'Community Notebook',
        'content' => 'A5 notebook with community logo. 200 pages, lined paper.',
        'product_price' => 15.00,
        'product_sku' => 'NOTEBOOK-001',
        'product_stock_status' => 'instock',
        'product_sizes' => '',
        'product_colors' => 'Black,Blue,Red',
        'product_description' => 'Premium A5 notebook with 200 lined pages and elastic closure.'
    ],
    [
        'title' => 'Community Tote Bag',
        'content' => 'Reusable canvas tote bag with community logo. Eco-friendly and durable.',
        'product_price' => 18.00,
        'product_sku' => 'TOTE-001',
        'product_stock_status' => 'instock',
        'product_sizes' => '',
        'product_colors' => 'Natural,Black,Navy',
        'product_description' => 'Heavy-duty canvas tote bag, 15" x 16" with reinforced handles.'
    ]
];

// Function to create posts
function create_sample_posts($post_type, $sample_data, $meta_fields, $force_import = false) {
    $created = 0;
    $errors = [];
    
    foreach ($sample_data as $data) {
        // Debug: Show what we're checking for
        echo "<p style='color:blue;'>Checking for: '{$data['title']}' in post type '{$post_type}'</p>";
        
        // Check if post already exists (only if not forcing)
        if (!$force_import) {
            $existing = get_posts([
                'post_type' => $post_type,
                'post_title' => $data['title'],
                'posts_per_page' => 1,
                'post_status' => 'any' // Check all statuses
            ]);
            
            if (!empty($existing)) {
                echo "<p style='color:orange;'>Skipping '{$data['title']}' - already exists (ID: {$existing[0]->ID})</p>";
                continue; // Skip if already exists
            }
        }
        
        // Create post
        $post_data = [
            'post_title' => $data['title'],
            'post_content' => $data['project_about'] ?? $data['content'] ?? '',
            'post_status' => 'publish',
            'post_type' => $post_type,
            'post_author' => get_current_user_id()
        ];
        
        $post_id = wp_insert_post($post_data);
        
        if ($post_id && !is_wp_error($post_id)) {
            echo "<p style='color:green;'>Created '{$data['title']}' (ID: {$post_id})</p>";
            
            // Add meta fields
            foreach ($meta_fields as $field) {
                if (isset($data[$field])) {
                    update_post_meta($post_id, '_' . $field, $data[$field]);
                }
            }
            $created++;
        } else {
            $error_msg = "Failed to create: " . $data['title'];
            if (is_wp_error($post_id)) {
                $error_msg .= " - " . $post_id->get_error_message();
            }
            $errors[] = $error_msg;
            echo "<p style='color:red;'>{$error_msg}</p>";
        }
    }
    
    return ['created' => $created, 'errors' => $errors];
}

// Import data
echo "<h1>Premade Data Import</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;}.success{color:green;}.error{color:red;}</style>";

// Debug: Check current user
echo "<p>Current user: " . get_current_user_id() . " - " . wp_get_current_user()->user_login . "</p>";

// Debug: Check if post types exist
$post_types = ['project', 'tutorial', 'event', 'team_member', 'product'];
foreach ($post_types as $pt) {
    if (post_type_exists($pt)) {
        echo "<p style='color:green;'>Post type '{$pt}' exists</p>";
    } else {
        echo "<p style='color:red;'>Post type '{$pt}' NOT FOUND</p>";
    }
}

echo "<hr>";

// Import Projects (FORCE IMPORT)
echo "<h2>Projects (Force Import)</h2>";
$result = create_sample_posts('project', $sample_projects, ['project_github_repo', 'project_tech_stack', 'project_about', 'project_contribution'], true);
echo "<p class='success'>Created: {$result['created']}</p>";
if (!empty($result['errors'])) {
    echo "<p class='error'>Errors: " . implode(', ', $result['errors']) . "</p>";
}

// Import Tutorials (FORCE IMPORT)
echo "<h2>Tutorials (Force Import)</h2>";
$result = create_sample_posts('tutorial', $sample_tutorials, ['tutorial_content', 'tutorial_difficulty', 'tutorial_estimated_time', 'tutorial_prerequisites'], true);
echo "<p class='success'>Created: {$result['created']}</p>";
if (!empty($result['errors'])) {
    echo "<p class='error'>Errors: " . implode(', ', $result['errors']) . "</p>";
}

// Import Events (FORCE IMPORT)
echo "<h2>Events (Force Import)</h2>";
$result = create_sample_posts('event', $sample_events, ['event_type', 'event_date', 'event_location', 'event_status', 'event_description', 'event_duration', 'event_prizes'], true);
echo "<p class='success'>Created: {$result['created']}</p>";
if (!empty($result['errors'])) {
    echo "<p class='error'>Errors: " . implode(', ', $result['errors']) . "</p>";
}

// Import Team Members (FORCE IMPORT)
echo "<h2>Team Members (Force Import)</h2>";
echo "<p>Attempting to import " . count($sample_team) . " team members...</p>";
$result = create_sample_posts('team_member', $sample_team, ['team_role', 'team_email', 'team_twitter', 'team_linkedin', 'team_github', 'team_bio'], true);
echo "<p class='success'>Created: {$result['created']}</p>";
if (!empty($result['errors'])) {
    echo "<p class='error'>Errors: " . implode(', ', $result['errors']) . "</p>";
}

// Debug: Check existing team posts
echo "<h3>Debug - Existing Team Posts:</h3>";
$existing_team = get_posts(['post_type' => 'team_member', 'posts_per_page' => -1, 'post_status' => 'any']);
if (empty($existing_team)) {
    echo "<p style='color:red;'>No team posts found</p>";
} else {
    echo "<p style='color:green;'>Found " . count($existing_team) . " team posts:</p>";
    foreach ($existing_team as $post) {
        echo "<p>- {$post->post_title} (ID: {$post->ID}, Status: {$post->post_status})</p>";
    }
}

// Import Products (FORCE IMPORT)
echo "<h2>Products (Force Import)</h2>";
$result = create_sample_posts('product', $sample_products, ['product_price', 'product_sku', 'product_stock_status', 'product_sizes', 'product_colors', 'product_description'], true);
echo "<p class='success'>Created: {$result['created']}</p>";
if (!empty($result['errors'])) {
    echo "<p class='error'>Errors: " . implode(', ', $result['errors']) . "</p>";
}

echo "<h2>Import Complete!</h2>";
echo "<p><a href='" . admin_url() . "'>Go to WordPress Admin</a></p>";
?>
