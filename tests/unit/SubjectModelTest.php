<?php

namespace Tests\Unit;

use App\Models\SubjectModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class SubjectModelTest extends CIUnitTestCase
{
    private SubjectModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new SubjectModel();
    }

    public function testModelCanBeInstantiated(): void
    {
        $this->assertInstanceOf(SubjectModel::class, $this->model);
    }

    public function testModelHasCorrectTableName(): void
    {
        $this->assertEquals('Assunto', $this->model->getTable());
    }

    public function testModelHasCorrectPrimaryKey(): void
    {
        $this->assertEquals('codAs', $this->model->primaryKey);
    }

    public function testModelHasCorrectReturnType(): void
    {
        $this->assertEquals('array', $this->model->returnType);
    }

    public function testModelHasValidationRules(): void
    {
        $validationRules = $this->model->validationRules;
        $this->assertIsArray($validationRules);
        $this->assertArrayHasKey('Descricao', $validationRules);
    }

    public function testModelHasAllowedFields(): void
    {
        $allowedFields = $this->model->allowedFields;
        $this->assertIsArray($allowedFields);
        $this->assertContains('Descricao', $allowedFields);
    }

    public function testModelHasUseTimestamps(): void
    {
        $this->assertFalse($this->model->useTimestamps);
    }

    public function testModelHasCorrectDateFormat(): void
    {
        $this->assertEquals('datetime', $this->model->dateFormat);
    }
}