<?php

/**
 * Test Runner for FlockSense API Tests
 * 
 * This script provides a comprehensive test suite for all API endpoints
 * and related functionality in the FlockSense application.
 */

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class TestRunner
{
    /**
     * Run all feature tests
     */
    public static function runFeatureTests()
    {
        echo "Running Feature Tests...\n";
        echo "========================\n\n";

        // User API Tests
        echo "1. User API Tests\n";
        self::runTest('Feature/Api/V1/UserControllerTest.php');
        
        // Farm API Tests
        echo "2. Farm API Tests\n";
        self::runTest('Feature/Api/V1/FarmControllerTest.php');
        
        // Shed API Tests
        echo "3. Shed API Tests\n";
        self::runTest('Feature/Api/V1/ShedControllerTest.php');
        
        // Device API Tests
        echo "4. Device API Tests\n";
        self::runTest('Feature/Api/V1/DeviceControllerTest.php');
        
        // DeviceAppliance API Tests
        echo "5. DeviceAppliance API Tests\n";
        self::runTest('Feature/Api/V1/DeviceApplianceControllerTest.php');
        
        // Flock API Tests
        echo "6. Flock API Tests\n";
        self::runTest('Feature/Api/V1/FlockControllerTest.php');
        
        // Breed API Tests
        echo "7. Breed API Tests\n";
        self::runTest('Feature/Api/V1/BreedControllerTest.php');
        
        // SensorData API Tests
        echo "8. SensorData API Tests\n";
        self::runTest('Feature/Api/V1/SensorDataControllerTest.php');
    }

    /**
     * Run all unit tests
     */
    public static function runUnitTests()
    {
        echo "Running Unit Tests...\n";
        echo "====================\n\n";

        // Model Tests
        echo "1. Model Tests\n";
        self::runTest('Unit/Models/UserTest.php');
        self::runTest('Unit/Models/FarmTest.php');
        self::runTest('Unit/Models/DeviceTest.php');
        
        // Service Tests
        echo "2. Service Tests\n";
        self::runTest('Unit/Services/DynamoDbServiceTest.php');
        
        // Resource Tests
        echo "3. Resource Tests\n";
        self::runTest('Unit/Resources/UserResourceTest.php');
    }

    /**
     * Run all tests
     */
    public static function runAllTests()
    {
        echo "FlockSense API Test Suite\n";
        echo "=========================\n\n";

        self::runFeatureTests();
        echo "\n";
        self::runUnitTests();
        
        echo "\nTest Suite Complete!\n";
        echo "===================\n";
    }

    /**
     * Run a specific test file
     */
    private static function runTest($testFile)
    {
        $command = "php artisan test tests/{$testFile} --verbose";
        echo "Running: {$command}\n";
        
        // Execute the test command
        $output = shell_exec($command);
        echo $output;
        
        echo "\n";
    }

    /**
     * Generate test coverage report
     */
    public static function generateCoverageReport()
    {
        echo "Generating Coverage Report...\n";
        echo "============================\n\n";

        $command = "php artisan test --coverage --min=80";
        echo "Running: {$command}\n";
        
        $output = shell_exec($command);
        echo $output;
    }

    /**
     * Run tests with specific filters
     */
    public static function runFilteredTests($filter)
    {
        echo "Running Filtered Tests: {$filter}\n";
        echo "===============================\n\n";

        $command = "php artisan test --filter={$filter}";
        echo "Running: {$command}\n";
        
        $output = shell_exec($command);
        echo $output;
    }
}

// Usage examples:
// TestRunner::runAllTests();
// TestRunner::runFeatureTests();
// TestRunner::runUnitTests();
// TestRunner::generateCoverageReport();
// TestRunner::runFilteredTests('UserController'); 