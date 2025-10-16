<?php

namespace Tests\Unit;

use App\Controllers\AuthorController;
use App\Models\AuthorModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class AuthorControllerTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $seed = 'CreateSampleData';

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AuthorController();
        $this->model = new AuthorModel();
    }

    public function testIndexReturnsViewWithAuthors(): void
    {
        $result = $this->controller->index();

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $result);
    }

    public function testCreatePostValidData(): void
    {
        $data = [
            'name' => 'New Author',
        ];

        $this->withRequest($this->createRequest('POST', '/authors/create', $data))
             ->controller(AuthorController::class)
             ->execute('create');

        $author = $this->model->where('name', 'New Author')->first();
        $this->assertNotNull($author);
    }

    public function testCreatePostInvalidData(): void
    {
        $data = [
            'name' => '', // Invalid empty name
        ];

        $response = $this->withRequest($this->createRequest('POST', '/authors/create', $data))
                        ->controller(AuthorController::class)
                        ->execute('create');

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $response);
    }

    public function testCreatePostDuplicateName(): void
    {
        // Create first author
        $this->model->insert(['name' => 'Existing Author']);

        $data = [
            'name' => 'Existing Author', // Duplicate name
        ];

        $response = $this->withRequest($this->createRequest('POST', '/authors/create', $data))
                        ->controller(AuthorController::class)
                        ->execute('create');

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $response);
    }

    public function testEditGetValidId(): void
    {
        $authorId = $this->model->insert(['name' => 'Test Author']);

        $result = $this->controller->edit($authorId);

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $result);
    }

    public function testEditGetInvalidId(): void
    {
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);

        $this->controller->edit(9999);
    }

    public function testEditPostValidData(): void
    {
        $authorId = $this->model->insert(['name' => 'Original Author']);

        $data = [
            'name' => 'Updated Author',
        ];

        $this->withRequest($this->createRequest('POST', "/authors/edit/{$authorId}", $data))
             ->controller(AuthorController::class)
             ->execute('edit', $authorId);

        $updatedAuthor = $this->model->find($authorId);
        $this->assertEquals('Updated Author', $updatedAuthor['name']);
    }

    public function testEditPostInvalidData(): void
    {
        $authorId = $this->model->insert(['name' => 'Test Author']);

        $data = [
            'name' => '', // Invalid empty name
        ];

        $response = $this->withRequest($this->createRequest('POST', "/authors/edit/{$authorId}", $data))
                        ->controller(AuthorController::class)
                        ->execute('edit', $authorId);

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $response);
    }

    public function testDeleteValidId(): void
    {
        $authorId = $this->model->insert(['name' => 'Test Author']);

        $this->controller->delete($authorId);

        $deletedAuthor = $this->model->find($authorId);
        $this->assertNull($deletedAuthor);
    }

    public function testDeleteInvalidId(): void
    {
        // Should not throw exception, just redirect
        $result = $this->controller->delete(9999);

        $this->assertNull($result); // delete method returns null
    }

    private function createRequest(string $method, string $uri, array $data = []): RequestInterface
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