# 🚀 GitHub Codespaces Setup

Este proyecto está configurado para funcionar automáticamente en GitHub Codespaces.

## Configuración automática

Cuando abres el proyecto en Codespaces, se ejecutan automáticamente:

1. **Instalación de dependencias**:
   - Composer (PHP)
   - NPM (Node.js)

2. **Configuración de Laravel**:
   - Copia de `.env.example` a `.env`
   - Generación de `APP_KEY`
   - Creación de base de datos SQLite

3. **Base de datos**:
   - Ejecución de migraciones
   - Población con seeders

4. **Assets**:
   - Compilación de Vite
   - Enlace de storage

5. **Servidor**:
   - Laravel se ejecuta automáticamente en el puerto 8000

## Acceso a la aplicación

Una vez configurado, la aplicación estará disponible en:
- **Laravel**: `http://localhost:8000`
- **Vite**: `http://localhost:5173` (dev mode)

## Credenciales por defecto

### Administrador Root
- Email: `admin@admin.com`
- Password: `CHANGE_ME`

### Pacientes
- Ingresar con el código desde la tabla `patients`

## Comandos útiles

```bash
# Recompilar assets
cd src && npm run dev

# Ejecutar tests
cd src && php artisan test

# Limpiar caché
cd src && php artisan optimize:clear

# Ver logs en tiempo real
tail -f /tmp/laravel.log
```

## Estructura de archivos

```
.devcontainer/
├── devcontainer.json    # Configuración principal
├── Dockerfile           # Imagen Docker con PHP 8.2
└── init.sh              # Script de inicialización
```

## Troubleshooting

### El servidor no arranca
```bash
cd src && php artisan serve --host=0.0.0.0 --port=8000
```

### Problemas con permisos
```bash
cd src && chmod -R 775 storage bootstrap/cache
```

### Regenerar base de datos
```bash
cd src && php artisan migrate:fresh --seed
```

## Extensiones instaladas

- PHP Intelephense (autocompletado PHP)
- Laravel Blade (sintaxis)
- ESLint (linting JavaScript)
- Tailwind CSS IntelliSense
- Prettier (formato de código)
