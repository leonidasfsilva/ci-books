#!/bin/bash

echo "Configurando permissoes para o projeto CodeIgniter..."

# Criar diretorios writable se nao existirem
mkdir -p writable/cache
mkdir -p writable/debugbar
mkdir -p writable/logs
mkdir -p writable/session
mkdir -p writable/uploads

# Ajustar permissoes (assumindo que o usuario do servidor web e www-data)
chown -R www-data:www-data writable
chmod -R 755 writable

echo "Configuracao concluida!"