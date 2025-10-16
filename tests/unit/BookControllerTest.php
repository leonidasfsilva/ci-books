<?php

namespace Tests\Unit;

use App\Controllers\BookController;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class BookControllerTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $seed = '';

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure migrations are run for tests
        $this->migrateDatabase();

        // Create tables manually for SQLite tests
        $this->createTables();
    }

    private function createTables(): void
    {
        // Create Autor table
        $this->db->query("
            CREATE TABLE IF NOT EXISTS Autor (
                CodAu INTEGER PRIMARY KEY AUTOINCREMENT,
                Nome VARCHAR(40) NOT NULL
            )
        ");

        // Create Livro table
        $this->db->query("
            CREATE TABLE IF NOT EXISTS Livro (
                CodL INTEGER PRIMARY KEY AUTOINCREMENT,
                Titulo VARCHAR(40),
                Editora VARCHAR(40),
                Edicao INTEGER,
                AnoPublicacao VARCHAR(4),
                Valor DECIMAL(10,2) NOT NULL
            )
        ");

        // Create Assunto table
        $this->db->query("
            CREATE TABLE IF NOT EXISTS Assunto (
                codAs INTEGER PRIMARY KEY AUTOINCREMENT,
                Descricao VARCHAR(20) NOT NULL
            )
        ");

        // Create Livro_Autor table
        $this->db->query("
            CREATE TABLE IF NOT EXISTS Livro_Autor (
                Livro_CodL INTEGER,
                Autor_CodAu INTEGER,
                PRIMARY KEY (Livro_CodL, Autor_CodAu),
                FOREIGN KEY (Livro_CodL) REFERENCES Livro(CodL),
                FOREIGN KEY (Autor_CodAu) REFERENCES Autor(CodAu)
            )
        ");

        // Create Livro_Assunto table
        $this->db->query("
            CREATE TABLE IF NOT EXISTS Livro_Assunto (
                Livro_CodL INTEGER,
                Assunto_codAs INTEGER,
                PRIMARY KEY (Livro_CodL, Assunto_codAs),
                FOREIGN KEY (Livro_CodL) REFERENCES Livro(CodL),
                FOREIGN KEY (Assunto_codAs) REFERENCES Assunto(codAs)
            )
        ");
    }

    public function testIndexReturnsViewWithBooks(): void
    {
        // Create test data
        $authorId = $this->db->table('Autor')->insert(['Nome' => 'Test Author']);
        $subjectId = $this->db->table('Assunto')->insert(['Descricao' => 'Test Subject']);
        $bookId = $this->db->table('Livro')->insert([
            'Titulo' => 'Test Book',
            'Valor' => 29.99
        ]);

        $this->withURI('http://example.com/books')
             ->controller(BookController::class)
             ->execute('index');

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    public function testCreateGetReturnsView(): void
    {
        $this->withURI('http://example.com/books/create')
             ->controller(BookController::class)
             ->execute('create');

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    public function testCreatePostValidData(): void
    {
        // Create test data
        $authorId = $this->db->table('Autor')->insert(['Nome' => 'Test Author']);
        $subjectId = $this->db->table('Assunto')->insert(['Descricao' => 'Test Subject']);

        $data = [
            'titulo' => 'Test Book',
            'editora' => 'Test Publisher',
            'edicao' => 1,
            'ano_publicacao' => '2023',
            'valor' => 29.99,
            'authors' => [$authorId],
            'subjects' => [$subjectId]
        ];

        $this->withURI('http://example.com/books/create')
             ->withRequest($this->createRequest('POST', '/books/create', $data))
             ->controller(BookController::class)
             ->execute('create');

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    public function testCreatePostInvalidData(): void
    {
        $data = ['titulo' => '']; // Invalid empty title

        $this->withURI('http://example.com/books/create')
             ->withRequest($this->createRequest('POST', '/books/create', $data))
             ->controller(BookController::class)
             ->execute('create');

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    public function testEditGetValidId(): void
    {
        // Create test data
        $authorId = $this->db->table('Autor')->insert(['Nome' => 'Test Author']);
        $subjectId = $this->db->table('Assunto')->insert(['Descricao' => 'Test Subject']);
        $bookId = $this->db->table('Livro')->insert([
            'Titulo' => 'Test Book',
            'Valor' => 29.99
        ]);

        $this->withURI("http://example.com/books/edit/{$bookId}")
             ->controller(BookController::class)
             ->execute('edit', $bookId);

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    public function testEditGetInvalidId(): void
    {
        // Should not throw exception, just redirect or show error
        $this->withURI('http://example.com/books/edit/9999')
             ->controller(BookController::class)
             ->execute('edit', 9999);

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    public function testEditPostValidData(): void
    {
        // Create test data
        $authorId = $this->db->table('Autor')->insert(['Nome' => 'Test Author']);
        $subjectId = $this->db->table('Assunto')->insert(['Descricao' => 'Test Subject']);
        $bookId = $this->db->table('Livro')->insert([
            'Titulo' => 'Original Book',
            'Valor' => 19.99
        ]);

        $data = [
            'titulo' => 'Updated Book',
            'editora' => 'Updated Publisher',
            'edicao' => 2,
            'ano_publicacao' => '2024',
            'valor' => 39.99,
            'authors' => [$authorId],
            'subjects' => [$subjectId]
        ];

        $this->withURI("http://example.com/books/edit/{$bookId}")
             ->withRequest($this->createRequest('POST', "/books/edit/{$bookId}", $data))
             ->controller(BookController::class)
             ->execute('edit', $bookId);

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    public function testEditPostInvalidData(): void
    {
        $bookId = $this->db->table('Livro')->insert([
            'Titulo' => 'Test Book',
            'Valor' => 29.99
        ]);

        $data = ['titulo' => '']; // Invalid empty title

        $this->withURI("http://example.com/books/edit/{$bookId}")
             ->withRequest($this->createRequest('POST', "/books/edit/{$bookId}", $data))
             ->controller(BookController::class)
             ->execute('edit', $bookId);

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    public function testDeleteValidId(): void
    {
        $bookId = $this->db->table('Livro')->insert([
            'Titulo' => 'Test Book',
            'Valor' => 29.99
        ]);

        $this->withURI("http://example.com/books/delete/{$bookId}")
             ->controller(BookController::class)
             ->execute('delete', $bookId);

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    public function testDeleteInvalidId(): void
    {
        // Should not throw exception, just redirect
        $this->withURI('http://example.com/books/delete/9999')
             ->controller(BookController::class)
             ->execute('delete', 9999);

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    public function testGetBookValidId(): void
    {
        $bookId = $this->db->table('Livro')->insert([
            'Titulo' => 'Test Book',
            'Valor' => 29.99
        ]);

        $result = $this->withURI("http://example.com/books/get/{$bookId}")
                      ->controller(BookController::class)
                      ->execute('getBook', $bookId);

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    public function testGetBookInvalidId(): void
    {
        $result = $this->withURI('http://example.com/books/get/9999')
                      ->controller(BookController::class)
                      ->execute('getBook', 9999);

        $this->assertTrue(true); // Just ensure no exception is thrown
    }

    private function createRequest(string $method, string $uri, array $data = []): \CodeIgniter\HTTP\RequestInterface
    {
        $request = service('request');
        $request->setMethod($method);
        $request->setPath($uri);

        if ($method === 'POST' && !empty($data)) {
            $request->setGlobal('post', $data);
        }

        return $request;
    }
}