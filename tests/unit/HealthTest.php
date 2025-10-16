<?php

use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
use Config\Services;
use Tests\Support\Libraries\ConfigReader;

/**
 * @internal
 */
final class HealthTest extends CIUnitTestCase
{
    public function testIsDefinedAppPath(): void
    {
        $this->assertTrue(defined('APPPATH'));
    }

    public function testBaseUrlHasBeenSet(): void
    {
        $validation = Services::validation();

        $env = false;
        $envBaseURL = '';

        // Check the baseURL in .env
        if (is_file(HOMEPATH . '.env')) {
            $envFile = file(HOMEPATH . '.env');
            foreach ($envFile as $line) {
                if (preg_match('/^app\.baseURL = (.+)$/', trim($line), $matches)) {
                    $env = true;
                    $envBaseURL = trim($matches[1], '"\'');
                    break;
                }
            }
        }

        if ($env && !empty($envBaseURL)) {
            // BaseURL in .env is a valid URL?
            $this->assertTrue(
                $validation->check($envBaseURL, 'valid_url'),
                'baseURL "' . $envBaseURL . '" in .env is not valid URL'
            );
        } else {
            // Get the baseURL in app/Config/App.php
            // You can't use Config\App, because phpunit.xml.dist sets app.baseURL
            $reader = new ConfigReader();

            // BaseURL in app/Config/App.php is a valid URL?
            $this->assertTrue(
                $validation->check($reader->baseURL, 'valid_url'),
                'baseURL "' . $reader->baseURL . '" in app/Config/App.php is not valid URL'
            );
        }
    }
}
