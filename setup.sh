#!/bin/bash

echo "Configurando permissoes para o projeto CodeIgniter..."

# Criar diretorios writable se nao existirem
mkdir -p writable/cache
mkdir -p writable/debugbar
mkdir -p writable/logs
mkdir -p writable/session
mkdir -p writable/uploads

# Ajustar permissoes (assumindo que o usuario do servidor web e www-data ou apache)
# Tentar www-data primeiro (Ubuntu/Debian)
if id -u www-data > /dev/null 2>&1; then
    chown -R www-data:www-data writable
    echo "Permissoes ajustadas para www-data (Ubuntu/Debian)"
# Se nao existir, tentar apache (CentOS/RHEL)
elif id -u apache > /dev/null 2>&1; then
    chown -R apache:apache writable
    echo "Permissoes ajustadas para apache (CentOS/RHEL)"
else
    echo "Usuario do servidor web nao encontrado. Ajustando permissoes para o usuario atual."
    chmod -R 755 writable
fi

echo "Configuracao concluida!"