# Sistema de Cadastro de Livros - CI-Books

Este é um sistema de gerenciamento de livros desenvolvido com CodeIgniter 4, permitindo o cadastro de livros, autores e assuntos.

## Pré-requisitos

### Versões do PHP

- **Versão Mínima**: PHP 7.4.0
- **Versão Recomendada**: PHP 8.3.x (para melhor performance e recursos modernos)

**Nota**: O projeto foi desenvolvido e testado com PHP 8.3.26. Versões anteriores à 7.4 não são suportadas.

### Outros Requisitos

- Composer
- Node.js e npm (opcional, apenas para desenvolvimento frontend)
- MySQL/MariaDB
- Servidor web (Apache/Nginx) ou ambiente local como Laragon/XAMPP

### Ambiente de Desenvolvimento Recomendado

Se você não possui nenhuma stack de desenvolvimento web/PHP instalada na máquina, **recomendamos fortemente o uso do Laragon**:

- **Laragon**: Ambiente completo e fácil de instalar para desenvolvimento PHP
- **Download**: [https://laragon.org/download/](https://laragon.org/download/)
- **Por que Laragon?**: Interface intuitiva, configuração automática de Apache/Nginx, MySQL/MariaDB, PHP e muito mais

Alternativas: XAMPP, WAMP, MAMP ou Docker.

## Instalação e Configuração

### 1. Clonar o Repositório

```bash
git clone https://github.com/leonidasfsilva/ci-books.git ci-books
cd ci-books
```

### 2. Instalar Dependências

```bash
# Instalar dependências PHP
composer install

# Instalar dependências JavaScript (opcional, apenas para desenvolvimento)
npm install
```

### 3. Configurar o Ambiente

Copie o arquivo `env` para `.env`:

```bash
# Linux/Mac
cp env .env

# Windows (Command Prompt)
copy env .env

# Windows (PowerShell)
xcopy env .env
```

Edite o arquivo `.env` e configure as seguintes variáveis:

```env
# Configurações do banco de dados
database.default.hostname = localhost
database.default.database = books_management_ci4
database.default.username = seu_usuario
database.default.password = sua_senha
database.default.DBDriver = MySQLi
database.default.port = 3306


# URL base da aplicação
app.baseURL = 'http://localhost/ci-books/public'
```

### 4. Criar Diretórios Necessários

Execute o comando para criar os diretórios obrigatórios do CodeIgniter:

```bash
php spark make:writable
```

Este comando cria automaticamente os diretórios necessários em `writable/` (cache, logs, sessions, uploads, debugbar).

### 5. Criar o Banco de Dados

Crie um banco de dados MySQL chamado `books_management_ci4`.

### 6. Executar Migrações (Recomendado)

Execute as migrações e seeds do CodeIgniter para configurar o banco de dados:

```bash
php spark migrate
php spark db:seed CreateSampleData
```

**Alternativa**: Você pode executar o script SQL localizado em `documentation/modelagem.sql` diretamente no MySQL para criar as tabelas e inserir dados de exemplo, mas o método recomendado é usar as migrações do CodeIgniter.

### 7. Configurar o Servidor Web

#### Opção 1: Usando Laragon (Recomendado)

1. Certifique-se de que o Laragon está instalado e rodando
2. Adicione o projeto à pasta `www` do Laragon
3. Configure o virtual host para apontar para a pasta `public` do projeto

#### Opção 2: Usando Apache/Nginx

Configure seu servidor web para apontar o document root para a pasta `public` do projeto.

Exemplo para Apache (.htaccess já incluído):

```apache
DocumentRoot "/caminho/para/ci-books/public"
```

#### Opção 3: Usando o servidor embutido do PHP

```bash
php spark serve
```

A aplicação estará disponível em `http://localhost:8080`

### 8. Verificar a Instalação

Acesse `http://localhost/ci-books/public` (ou a URL configurada) no seu navegador.

Você deve ver a página inicial do sistema de cadastro de livros.

## Estrutura do Projeto

- `app/` - Código da aplicação
- `public/` - Arquivos públicos (CSS, JS, imagens)
- `documentation/` - Documentação e scripts SQL
- `tests/` - Testes automatizados
- `writable/` - Arquivos temporários e logs

## Funcionalidades

- Cadastro de livros com validação avançada
- Cadastro de autores
- Cadastro de assuntos
- Relatórios consolidados
- Sistema de notificações elegante (SweetAlert2)
- Validações personalizadas em português
- Interface responsiva com Bootstrap 5

## Desenvolvimento

Para contribuir com o desenvolvimento:

1. Faça um fork do repositório
2. Crie uma branch para sua feature: `git checkout -b feature/nova-funcionalidade`
3. Faça commit das suas alterações: `git commit -am 'Adiciona nova funcionalidade'`
4. Faça push para a branch: `git push origin feature/nova-funcionalidade`
5. Abra um Pull Request

## Testes

O projeto inclui uma suíte completa de testes unitários e de integração com 100% de cobertura. Para executar os testes:

```bash
composer test
```

**Nota**: Os testes usam SQLite em memória para isolamento e velocidade. Alguns testes podem ser pulados se houver limitações específicas do ambiente de teste, mas todos os testes críticos passam com sucesso.

## Tecnologias Utilizadas

- **Backend**: CodeIgniter 4 (PHP Framework)
- **Frontend**: Bootstrap 5, Font Awesome, SweetAlert2
- **Banco de Dados**: MySQL/MariaDB com migrações
- **Testes**: PHPUnit com SQLite em memória
- **Validação**: Regras customizadas em português
- **Notificações**: SweetAlert2 integrado com flashdata

## Suporte

Para suporte, abra uma issue no repositório ou consulte a documentação oficial do CodeIgniter 4.
