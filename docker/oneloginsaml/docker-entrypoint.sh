#!/bin/bash
# docker-entrypoint.sh

# Check if the project has been properly set up
echo "Checking if project is set up correctly..."

# If any setup or migration is required, add it here
# For example, you can run Composer to install dependencies
echo "Running Composer install..."
composer install --no-dev --optimize-autoloader

# You could also add other steps such as database migrations if required

# Start Apache server
echo "Starting Apache server..."
exec apache2-foreground
