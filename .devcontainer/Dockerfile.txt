FROM mcr.microsoft.com/devcontainers/php:1-8.2-bullseye

ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=America/Argentina/San_Juan

# Configurar timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Configurar locales
RUN apt-get update && \
    apt-get install -y locales && \
    locale-gen es_ES.UTF-8 && \
    update-locale LANG=es_ES.UTF-8

# Establecer variables de entorno de locale
ENV LANG=es_ES.UTF-8
ENV LC_ALL=es_ES.UTF-8

# Instalar extensiones PHP adicionales necesarias para Laravel
RUN apt-get update && apt-get install -y \
    php8.2-sqlite3 \
    php8.2-gd \
    php8.2-intl \
    php8.2-redis \
    php8.2-imagick \
    sqlite3 \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer global (última versión)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /workspaces/teassist

# Crear usuario vscode si no existe
RUN if ! id -u vscode > /dev/null 2>&1; then \
    useradd -m -s /bin/bash vscode; \
    fi

USER vscode
