<?php

namespace Tests\Unit;

use App\Models\BookModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class BookModelTest extends CIUnitTestCase
{
    private BookModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new BookModel();
    }

    public function testModelCanBeInstantiated(): void
    {
        $this->assertInstanceOf(BookModel::class, $this->model);
    }

    public function testModelHasCorrectTableName(): void
    {
        $this->assertEquals('Livro', $this->model->getTable());
    }

    public function testModelHasCorrectPrimaryKey(): void
    {
        $this->assertEquals('CodL', $this->model->primaryKey);
    }

    public function testModelHasCorrectReturnType(): void
    {
        $this->assertEquals('array', $this->model->returnType);
    }

    public function testModelHasValidationRules(): void
    {
        $validationRules = $this->model->validationRules;
        $this->assertIsArray($validationRules);
        $this->assertArrayHasKey('Titulo', $validationRules);
    }

    public function testModelHasAllowedFields(): void
    {
        $allowedFields = $this->model->allowedFields;
        $this->assertIsArray($allowedFields);
        $this->assertContains('Titulo', $allowedFields);
        $this->assertContains('Valor', $allowedFields);
    }

    public function testModelHasUseTimestamps(): void
    {
        $this->assertFalse($this->model->useTimestamps);
    }

    public function testModelHasCorrectDateFormat(): void
    {
        $this->assertEquals('datetime', $this->model->dateFormat);
    }

    public function testGetBooksWithRelationsMethodExists(): void
    {
        $this->assertTrue(method_exists($this->model, 'getBooksWithRelations'));
    }

    public function testGetBookWithRelationsMethodExists(): void
    {
        $this->assertTrue(method_exists($this->model, 'getBookWithRelations'));
    }
}