<?php

namespace Tests\Unit;

use App\Controllers\ReportController;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ReportControllerTest extends CIUnitTestCase
{
    private ReportController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ReportController();
        $this->controller->initController(
            service('request'),
            service('response'),
            service('logger')
        );
    }

    public function testControllerCanBeInstantiated(): void
    {
        $this->assertInstanceOf(ReportController::class, $this->controller);
    }

    public function testIndexMethodExists(): void
    {
        $this->assertTrue(method_exists($this->controller, 'index'));
    }

    public function testGetConsolidatedReportMethodExists(): void
    {
        $this->assertTrue(method_exists($this->controller, 'getConsolidatedReport'));
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