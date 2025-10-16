<?php

namespace Tests\Unit;

use App\Models\AuthorModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class AuthorModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $seed = 'CreateSampleData';

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new AuthorModel();
    }

    public function testModelCanInsertAuthor(): void
    {
        $data = [
            'name' => 'Test Author',
        ];

        $result = $this->model->insert($data);

        $this->assertIsInt($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testModelCanFindAuthor(): void
    {
        $data = [
            'name' => 'Test Author',
        ];

        $id = $this->model->insert($data);
        $author = $this->model->find($id);

        $this->assertIsArray($author);
        $this->assertEquals('Test Author', $author['name']);
    }

    public function testModelCanUpdateAuthor(): void
    {
        $data = [
            'name' => 'Test Author',
        ];

        $id = $this->model->insert($data);
        $updateData = [
            'name' => 'Updated Author',
        ];

        $result = $this->model->update($id, $updateData);
        $this->assertTrue($result);

        $updatedAuthor = $this->model->find($id);
        $this->assertEquals('Updated Author', $updatedAuthor['name']);
    }

    public function testModelCanDeleteAuthor(): void
    {
        $data = [
            'name' => 'Test Author',
        ];

        $id = $this->model->insert($data);
        $result = $this->model->delete($id);

        $this->assertTrue($result);
        $this->assertNull($this->model->find($id));
    }

    public function testModelValidationFailsWithEmptyName(): void
    {
        $data = [
            'name' => '',
        ];

        $result = $this->model->insert($data);
        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('name', $errors);
    }

    public function testModelValidationFailsWithDuplicateName(): void
    {
        $data = [
            'name' => 'Test Author',
        ];

        $this->model->insert($data);
        $result = $this->model->insert($data);

        $this->assertFalse($result);
        $errors = $this->model->errors();
        $this->assertArrayHasKey('name', $errors);
    }

    public function testModelValidationFailsWithInvalidCharacters(): void
    {
        $data = [
            'name' => 'Test@Author!',
        ];

        $result = $this->model->insert($data);
        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('name', $errors);
    }

    public function testModelValidationPassesWithValidName(): void
    {
        $data = [
            'name' => 'João Silva',
        ];

        $result = $this->model->insert($data);
        $this->assertIsInt($result);
    }

    public function testModelCanOrderByName(): void
    {
        $this->model->insert(['name' => 'Z Author']);
        $this->model->insert(['name' => 'A Author']);

        $authors = $this->model->orderBy('name')->findAll();

        $this->assertEquals('A Author', $authors[0]['name']);
        $this->assertEquals('Z Author', $authors[1]['name']);
    }
}