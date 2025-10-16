<?php

namespace Tests\Unit;

use App\Controllers\SubjectController;
use App\Models\SubjectModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class SubjectControllerTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $seed = 'CreateSampleData';

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new SubjectController();
        $this->model = new SubjectModel();
    }

    public function testIndexReturnsViewWithSubjects(): void
    {
        $result = $this->controller->index();

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $result);
    }

    public function testCreatePostValidData(): void
    {
        $data = [
            'name' => 'New Subject',
        ];

        $this->withRequest($this->createRequest('POST', '/subjects/create', $data))
             ->controller(SubjectController::class)
             ->execute('create');

        $subject = $this->model->where('name', 'New Subject')->first();
        $this->assertNotNull($subject);
    }

    public function testCreatePostInvalidData(): void
    {
        $data = [
            'name' => '', // Invalid empty name
        ];

        $response = $this->withRequest($this->createRequest('POST', '/subjects/create', $data))
                        ->controller(SubjectController::class)
                        ->execute('create');

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $response);
    }

    public function testCreatePostDuplicateName(): void
    {
        // Create first subject
        $this->model->insert(['name' => 'Existing Subject']);

        $data = [
            'name' => 'Existing Subject', // Duplicate name
        ];

        $response = $this->withRequest($this->createRequest('POST', '/subjects/create', $data))
                        ->controller(SubjectController::class)
                        ->execute('create');

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $response);
    }

    public function testEditGetValidId(): void
    {
        $subjectId = $this->model->insert(['name' => 'Test Subject']);

        $result = $this->controller->edit($subjectId);

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $result);
    }

    public function testEditGetInvalidId(): void
    {
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);

        $this->controller->edit(9999);
    }

    public function testEditPostValidData(): void
    {
        $subjectId = $this->model->insert(['name' => 'Original Subject']);

        $data = [
            'name' => 'Updated Subject',
        ];

        $this->withRequest($this->createRequest('POST', "/subjects/edit/{$subjectId}", $data))
             ->controller(SubjectController::class)
             ->execute('edit', $subjectId);

        $updatedSubject = $this->model->find($subjectId);
        $this->assertEquals('Updated Subject', $updatedSubject['name']);
    }

    public function testEditPostInvalidData(): void
    {
        $subjectId = $this->model->insert(['name' => 'Test Subject']);

        $data = [
            'name' => '', // Invalid empty name
        ];

        $response = $this->withRequest($this->createRequest('POST', "/subjects/edit/{$subjectId}", $data))
                        ->controller(SubjectController::class)
                        ->execute('edit', $subjectId);

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $response);
    }

    public function testDeleteValidId(): void
    {
        $subjectId = $this->model->insert(['name' => 'Test Subject']);

        $this->controller->delete($subjectId);

        $deletedSubject = $this->model->find($subjectId);
        $this->assertNull($deletedSubject);
    }

    public function testDeleteInvalidId(): void
    {
        // Should not throw exception, just redirect
        $result = $this->controller->delete(9999);

        $this->assertNull($result); // delete method returns null
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