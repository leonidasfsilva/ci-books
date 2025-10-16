<?php

namespace Tests\Unit;

use App\Controllers\BookController;
use App\Models\BookModel;
use App\Models\AuthorModel;
use App\Models\SubjectModel;
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
    protected $seed = 'CreateSampleData';

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new BookController();
        $this->bookModel = new BookModel();
        $this->authorModel = new AuthorModel();
        $this->subjectModel = new SubjectModel();
    }

    public function testIndexReturnsViewWithBooks(): void
    {
        $result = $this->controller->index();

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $result);
    }

    public function testCreatePostValidData(): void
    {
        // Create test author and subject
        $authorId = $this->authorModel->insert(['name' => 'Test Author']);
        $subjectId = $this->subjectModel->insert(['name' => 'Test Subject']);

        $data = [
            'title' => 'New Book',
            'description' => 'Book Description',
            'value' => '29.99',
            'publication_date' => '2023-01-01',
            'authors' => [$authorId],
            'subjects' => [$subjectId],
        ];

        $this->withRequest($this->createRequest('POST', '/books/create', $data))
             ->controller(BookController::class)
             ->execute('create');

        $book = $this->bookModel->where('title', 'New Book')->first();
        $this->assertNotNull($book);
    }

    public function testCreatePostInvalidData(): void
    {
        $data = [
            'title' => '', // Invalid empty title
            'description' => 'Book Description',
            'value' => '29.99',
        ];

        $response = $this->withRequest($this->createRequest('POST', '/books/create', $data))
                        ->controller(BookController::class)
                        ->execute('create');

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $response);
    }

    public function testCreatePostWithoutAuthors(): void
    {
        $data = [
            'title' => 'Book Without Authors',
            'description' => 'Book Description',
            'value' => '29.99',
            'authors' => [], // No authors
        ];

        $response = $this->withRequest($this->createRequest('POST', '/books/create', $data))
                        ->controller(BookController::class)
                        ->execute('create');

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $response);
    }

    public function testEditGetValidId(): void
    {
        $bookId = $this->bookModel->insert([
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => 29.99,
        ]);

        $result = $this->controller->edit($bookId);

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $result);
    }

    public function testEditGetInvalidId(): void
    {
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);

        $this->controller->edit(9999);
    }

    public function testEditPostValidData(): void
    {
        // Create test data
        $bookId = $this->bookModel->insert([
            'title' => 'Original Book',
            'description' => 'Original Description',
            'value' => 29.99,
        ]);
        $authorId = $this->authorModel->insert(['name' => 'Test Author']);
        $subjectId = $this->subjectModel->insert(['name' => 'Test Subject']);

        $data = [
            'title' => 'Updated Book',
            'description' => 'Updated Description',
            'value' => '39.99',
            'publication_date' => '2023-02-01',
            'authors' => [$authorId],
            'subjects' => [$subjectId],
        ];

        $this->withRequest($this->createRequest('POST', "/books/edit/{$bookId}", $data))
             ->controller(BookController::class)
             ->execute('edit', $bookId);

        $updatedBook = $this->bookModel->find($bookId);
        $this->assertEquals('Updated Book', $updatedBook['title']);
    }

    public function testEditPostInvalidData(): void
    {
        $bookId = $this->bookModel->insert([
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => 29.99,
        ]);

        $data = [
            'title' => '', // Invalid empty title
            'description' => 'Updated Description',
            'value' => '39.99',
        ];

        $response = $this->withRequest($this->createRequest('POST', "/books/edit/{$bookId}", $data))
                        ->controller(BookController::class)
                        ->execute('edit', $bookId);

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $response);
    }

    public function testDeleteValidId(): void
    {
        $bookId = $this->bookModel->insert([
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => 29.99,
        ]);

        $this->controller->delete($bookId);

        $deletedBook = $this->bookModel->find($bookId);
        $this->assertNull($deletedBook);
    }

    public function testDeleteInvalidId(): void
    {
        // Should not throw exception, just redirect
        $result = $this->controller->delete(9999);

        $this->assertNull($result); // delete method returns null
    }

    public function testGetBookValidId(): void
    {
        $bookId = $this->bookModel->insert([
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => 29.99,
        ]);

        $response = $this->controller->getBook($bookId);

        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetBookInvalidId(): void
    {
        $response = $this->controller->getBook(9999);

        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
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