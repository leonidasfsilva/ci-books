<?php

namespace Tests\Unit;

use App\Controllers\BookController;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class BookControllerTest extends CIUnitTestCase
{
    private BookController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new BookController();
        $this->controller->initController(
            service('request'),
            service('response'),
            service('logger')
        );
    }

    public function testControllerCanBeInstantiated(): void
    {
        $this->assertInstanceOf(BookController::class, $this->controller);
    }

    public function testIndexMethodExists(): void
    {
        $this->assertTrue(method_exists($this->controller, 'index'));
    }

    public function testCreateMethodExists(): void
    {
        $this->assertTrue(method_exists($this->controller, 'create'));
    }

    public function testEditMethodExists(): void
    {
        $this->assertTrue(method_exists($this->controller, 'edit'));
    }

    public function testDeleteMethodExists(): void
    {
        $this->assertTrue(method_exists($this->controller, 'delete'));
    }

    public function testGetBookMethodExists(): void
    {
        $this->assertTrue(method_exists($this->controller, 'getBook'));
    }

    public function testControllerHasCorrectNamespace(): void
    {
        $this->assertEquals('App\Controllers', (new \ReflectionClass($this->controller))->getNamespaceName());
    }

    public function testControllerExtendsBaseController(): void
    {
        $this->assertInstanceOf(\App\Controllers\BaseController::class, $this->controller);
    }
}