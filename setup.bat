@echo off
echo Configurando permissoes para o projeto CodeIgniter...

REM Criar diretorios writable se nao existirem
if not exist "writable" mkdir writable
if not exist "writable\cache" mkdir writable\cache
if not exist "writable\debugbar" mkdir writable\debugbar
if not exist "writable\logs" mkdir writable\logs
if not exist "writable\session" mkdir writable\session
if not exist "writable\uploads" mkdir writable\uploads

REM Ajustar permissoes para IIS_IUSRS
icacls writable /grant "IIS_IUSRS:(OI)(CI)F" /T

echo Configuracao concluida!
pause