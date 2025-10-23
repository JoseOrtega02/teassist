#!/usr/bin/env bash
set -euo pipefail

# Script used by devcontainer postCreateCommand
echo "Running devcontainer init script..."
cd /workspaces/teassist/src

echo "Installing composer dependencies..."
composer install --no-interaction --prefer-dist

echo "Copying .env if missing..."
cp .env.example .env || true

echo "Generating app key..."
php artisan key:generate --force

echo "Running migrations and seeders..."
php artisan migrate --seed --force || true

echo "Creating storage symlink..."
php artisan storage:link || true

echo "Fixing permissions..."
chmod -R 775 storage bootstrap/cache || true

echo "Devcontainer init script finished."
