<?php

namespace Tests\Unit;

use App\Models\SubjectModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class SubjectModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $seed = 'CreateSampleData';

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new SubjectModel();
    }

    public function testModelCanInsertSubject(): void
    {
        $data = [
            'name' => 'Test Subject',
        ];

        $result = $this->model->insert($data);

        $this->assertIsInt($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testModelCanFindSubject(): void
    {
        $data = [
            'name' => 'Test Subject',
        ];

        $id = $this->model->insert($data);
        $subject = $this->model->find($id);

        $this->assertIsArray($subject);
        $this->assertEquals('Test Subject', $subject['name']);
    }

    public function testModelCanUpdateSubject(): void
    {
        $data = [
            'name' => 'Test Subject',
        ];

        $id = $this->model->insert($data);
        $updateData = [
            'name' => 'Updated Subject',
        ];

        $result = $this->model->update($id, $updateData);
        $this->assertTrue($result);

        $updatedSubject = $this->model->find($id);
        $this->assertEquals('Updated Subject', $updatedSubject['name']);
    }

    public function testModelCanDeleteSubject(): void
    {
        $data = [
            'name' => 'Test Subject',
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
            'name' => 'Test Subject',
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
            'name' => 'Test@Subject!',
        ];

        $result = $this->model->insert($data);
        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('name', $errors);
    }

    public function testModelValidationPassesWithValidName(): void
    {
        $data = [
            'name' => 'Matemática Aplicada',
        ];

        $result = $this->model->insert($data);
        $this->assertIsInt($result);
    }

    public function testModelCanOrderByName(): void
    {
        $this->model->insert(['name' => 'Z Subject']);
        $this->model->insert(['name' => 'A Subject']);

        $subjects = $this->model->orderBy('name')->findAll();

        $this->assertEquals('A Subject', $subjects[0]['name']);
        $this->assertEquals('Z Subject', $subjects[1]['name']);
    }
}