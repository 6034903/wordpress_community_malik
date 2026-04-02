# WordPress Community - Docker Setup

## Overview
This Docker environment contains the WordPress Community application with custom theme "The Software Syndicate".

## Prerequisites
- Docker Desktop installed and running
- Docker Compose installed

## Quick Start

1. **Start the containers:**
   ```bash
   docker-compose up -d
   ```

2. **Access the application:**
   - WordPress site: http://localhost:8080
   - phpMyAdmin: http://localhost:8081

3. **Stop the containers:**
   ```bash
   docker-compose down
   ```

## Services

### WordPress (Port 8080)
- Custom theme: "The Software Syndicate"
- Custom plugins: Akismet, Contact Submissions
- Database: MySQL 8.0

### Database (Port 3306)
- MySQL 8.0
- Database: wordpress_community_malik
- User: malik123
- Password: 123

### phpMyAdmin (Port 8081)
- Web-based database administration
- Host: db
- User: malik123
- Password: 123

## Volumes
- `db_data`: Persistent MySQL data storage
- `./wp-content`: Custom themes and plugins (synced)
- `./wp-config.php`: WordPress configuration (synced)

## Development Workflow

1. Make changes to theme files in `wp-content/themes/community/`
2. Changes are automatically reflected in the container
3. Use phpMyAdmin to manage database if needed

## Custom Features

### Theme: The Software Syndicate
- Modern dark theme design
- Custom pages: Cart, Checkout, Contact, Events, Projects, Shop, Team, Tutorials
- E-commerce functionality
- Community features

### Custom Pages
- page-cart.php - Shopping cart functionality
- page-checkout.php - Checkout process
- page-contact.php - Contact form
- page-events-hackathon.php - Events and hackathons
- page-join-community.php - Community registration
- page-projecten.php - Projects showcase
- page-shop.php - Shop functionality
- page-team.php - Team information
- page-tutorials.php - Tutorials section

## Troubleshooting

### Database Connection Issues
- Ensure MySQL container is running: `docker-compose ps`
- Check database credentials in docker-compose.yml

### Permission Issues
- WordPress files are owned by www-data:www-data
- Directories: 755, Files: 644

### Port Conflicts
- Change ports in docker-compose.yml if 8080/8081/3306 are in use

## Production Deployment

For production deployment, consider:
1. Using environment variables for sensitive data
2. Adding SSL certificates
3. Implementing backup strategies
4. Using production-grade database configuration
5. Adding monitoring and logging
