# Sistema de Cadastro de Livros - CI-Books

Este é um sistema de gerenciamento de livros desenvolvido com CodeIgniter 4, permitindo o cadastro de livros, autores e assuntos.

## Pré-requisitos

- PHP 7.4 ou superior
- Composer
- Node.js e npm (opcional, apenas para desenvolvimento frontend)
- MySQL/MariaDB
- Servidor web (Apache/Nginx) ou ambiente local como Laragon/XAMPP

## Instalação e Configuração

### 1. Clonar o Repositório

```bash
git clone git@github.com:leonidasfsilva/ci-books.git
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
cp env .env
```

Edite o arquivo `.env` e configure as seguintes variáveis:

```env
# Configurações do banco de dados
database.default.hostname = localhost
database.default.database = books_management_ci4
database.default.username = seu_usuario
database.default.password = sua_senha
database.default.DBDriver = MySQLi

# URL base da aplicação
app.baseURL = 'http://localhost/ci-books/public'
```

### 4. Criar o Banco de Dados

Crie um banco de dados MySQL chamado `books_management_ci4`.

### 5. Executar Migrações (Recomendado)

Execute as migrações e seeds do CodeIgniter para configurar o banco de dados:

```bash
php spark migrate
php spark db:seed CreateSampleData
```

**Alternativa**: Você pode executar o script SQL localizado em `documentation/modelagem.sql` diretamente no MySQL para criar as tabelas e inserir dados de exemplo, mas o método recomendado é usar as migrações do CodeIgniter.

### 6. Configurar o Servidor Web

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

### 7. Verificar a Instalação

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

### Endpoints de Teste

O projeto inclui endpoints especiais para testar validações:

- `/books/errorMsg` - Testa mensagens de erro de validação
- `/books/successMsg` - Testa mensagens de sucesso

Ambos suportam GET (visualização) e POST (simulação de operações).

## Tecnologias Utilizadas

- **Backend**: CodeIgniter 4 (PHP Framework)
- **Frontend**: Bootstrap 5, Font Awesome, SweetAlert2
- **Banco de Dados**: MySQL/MariaDB com migrações
- **Testes**: PHPUnit com SQLite em memória
- **Validação**: Regras customizadas em português
- **Notificações**: SweetAlert2 integrado com flashdata

## Suporte

Para suporte, abra uma issue no repositório ou consulte a documentação oficial do CodeIgniter 4.
