<?php

namespace Tests\Unit;

use App\Models\AuthorModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class AuthorModelTest extends CIUnitTestCase
{
    private AuthorModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new AuthorModel();
    }

    public function testModelCanBeInstantiated(): void
    {
        $this->assertInstanceOf(AuthorModel::class, $this->model);
    }

    public function testModelHasCorrectTableName(): void
    {
        $this->assertEquals('Autor', $this->model->getTable());
    }

    public function testModelHasCorrectPrimaryKey(): void
    {
        $this->assertEquals('CodAu', $this->model->primaryKey);
    }

    public function testModelHasCorrectReturnType(): void
    {
        $this->assertEquals('array', $this->model->returnType);
    }

    public function testModelHasValidationRules(): void
    {
        $validationRules = $this->model->validationRules;
        $this->assertIsArray($validationRules);
        $this->assertArrayHasKey('Nome', $validationRules);
    }

    public function testModelValidationRulesAreConfigured(): void
    {
        $validationRules = $this->model->getValidationRules();
        $this->assertNotEmpty($validationRules);
    }

    public function testModelValidationMessagesAreConfigured(): void
    {
        $validationMessages = $this->model->getValidationMessages();
        $this->assertIsArray($validationMessages);
    }

    public function testModelHasAllowedFields(): void
    {
        $allowedFields = $this->model->allowedFields;
        $this->assertIsArray($allowedFields);
        $this->assertContains('Nome', $allowedFields);
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