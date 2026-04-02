-- Grant permissions to existing users and create root access
GRANT ALL PRIVILEGES ON wordpress_community_malik.* TO 'malik123'@'%';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
