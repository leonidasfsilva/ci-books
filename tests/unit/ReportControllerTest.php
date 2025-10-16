<?php

namespace Tests\Unit;

use App\Controllers\ReportController;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class ReportControllerTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $seed = 'CreateSampleData';

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ReportController();
    }

    public function testIndexReturnsViewWithReportData(): void
    {
        $result = $this->controller->index();

        $this->assertInstanceOf(\CodeIgniter\View\View::class, $result);
    }

    public function testGetConsolidatedReportReturnsArray(): void
    {
        $reportData = $this->invokeMethod($this->controller, 'getConsolidatedReport');

        $this->assertIsArray($reportData);
    }

    public function testGetConsolidatedReportStructure(): void
    {
        $reportData = $this->invokeMethod($this->controller, 'getConsolidatedReport');

        if (!empty($reportData)) {
            $firstItem = $reportData[0];
            $this->assertArrayHasKey('author_id', $firstItem);
            $this->assertArrayHasKey('author_name', $firstItem);
            $this->assertArrayHasKey('book_id', $firstItem);
            $this->assertArrayHasKey('book_title', $firstItem);
            $this->assertArrayHasKey('book_description', $firstItem);
            $this->assertArrayHasKey('book_value', $firstItem);
            $this->assertArrayHasKey('book_publication_date', $firstItem);
            $this->assertArrayHasKey('subjects', $firstItem);
        }
    }

    /**
     * Helper method to invoke private methods
     */
    private function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}