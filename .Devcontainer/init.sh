#!/bin/bash
set -e

echo "🚀 Iniciando configuración de TeAssist..."

# Navegar al directorio del proyecto Laravel
cd /workspaces/teassist/src

# Copiar .env si no existe
if [ ! -f .env ]; then
    echo "📝 Copiando archivo .env..."
    cp .env.example .env
fi

# Instalar dependencias de Composer
echo "📦 Instalando dependencias PHP..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Instalar dependencias de NPM
echo "📦 Instalando dependencias Node.js..."
npm install

# Generar clave de aplicación si no existe
if grep -q "APP_KEY=$" .env || ! grep -q "APP_KEY=" .env; then
    echo "🔑 Generando APP_KEY..."
    php artisan key:generate --ansi
fi

# Crear base de datos SQLite si no existe
if [ ! -f database/database.sqlite ]; then
    echo "💾 Creando base de datos SQLite..."
    touch database/database.sqlite
fi

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders
echo "🌱 Ejecutando seeders..."
php artisan db:seed --force

# Crear enlace simbólico de storage
echo "🔗 Creando enlace de storage..."
php artisan storage:link

# Limpiar y cachear configuración
echo "🧹 Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cachear para producción
echo "⚡ Optimizando aplicación..."
php artisan config:cache
php artisan route:cache

# Compilar assets con Vite
echo "🎨 Compilando assets..."
npm run build

# Dar permisos correctos
echo "🔐 Configurando permisos..."
chmod -R 775 storage bootstrap/cache
chmod -R 664 database/database.sqlite 2>/dev/null || true

echo "✅ Configuración completada!"
echo ""
echo "🌐 La aplicación estará disponible en:"
echo "   http://localhost:8000"
echo ""
echo "📚 Credenciales por defecto:"
echo "   Root: admin@admin.com / CHANGE_ME"
echo "   Pacientes: Usa el código de la tabla patients"
echo ""
