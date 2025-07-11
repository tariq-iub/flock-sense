# FlockSense API Test Suite

This directory contains comprehensive tests for the FlockSense API, covering both feature and unit tests for all endpoints and related functionality.

## Test Structure

### Feature Tests (`tests/Feature/`)

Feature tests test the complete API endpoints and their interactions with the database and external services.

#### API Controllers (`tests/Feature/Api/V1/`)

1. **UserControllerTest.php** - Tests user management endpoints
   - User listing with query builder features
   - User creation, updates, and deletion
   - Authentication and authorization
   - Data validation and error handling

2. **FarmControllerTest.php** - Tests farm management endpoints
   - Farm CRUD operations
   - User ownership validation
   - Geographic data handling
   - Relationship management

3. **ShedControllerTest.php** - Tests shed management endpoints
   - Shed CRUD operations
   - Farm relationship validation
   - Capacity and type validation
   - Device and flock relationships

4. **DeviceControllerTest.php** - Tests device management endpoints
   - Device registration and management
   - Serial number validation
   - Capabilities and firmware handling
   - Shed associations

5. **DeviceApplianceControllerTest.php** - Tests device appliance endpoints
   - Appliance CRUD operations
   - IoT status updates (unauthenticated)
   - Real-time status management
   - Batch operations

6. **FlockControllerTest.php** - Tests flock management endpoints
   - Flock lifecycle management
   - Breed associations
   - Chicken count tracking
   - Date range validation

7. **BreedControllerTest.php** - Tests breed management endpoints
   - Breed CRUD operations
   - Category validation
   - Flock associations
   - Statistics calculation

8. **SensorDataControllerTest.php** - Tests sensor data endpoints
   - Data storage and retrieval
   - Time range queries
   - DynamoDB integration
   - Multi-device data aggregation

### Unit Tests (`tests/Unit/`)

Unit tests focus on individual components and their logic.

#### Models (`tests/Unit/Models/`)

1. **UserTest.php** - Tests User model functionality
   - Relationships (farms, media)
   - Soft delete operations
   - Attribute casting
   - Role management

2. **FarmTest.php** - Tests Farm model functionality
   - Owner relationships
   - Shed associations
   - Geographic data handling
   - Statistics calculation

3. **DeviceTest.php** - Tests Device model functionality
   - Appliance relationships
   - Shed associations
   - Capability management
   - Serial number uniqueness

#### Services (`tests/Unit/Services/`)

1. **DynamoDbServiceTest.php** - Tests DynamoDB service functionality
   - Data formatting and validation
   - Time range calculations
   - Statistics computation
   - Error handling

#### Resources (`tests/Unit/Resources/`)

1. **UserResourceTest.php** - Tests UserResource transformation
   - JSON:API structure compliance
   - Conditional relationship inclusion
   - Date formatting
   - Collection handling

## Test Coverage

### API Endpoints Covered

#### User Management
- `GET /api/v1/users` - List users with filtering, sorting, includes
- `GET /api/v1/users/{id}` - Get user details with relationships
- `POST /api/v1/users` - Create new user
- `PUT /api/v1/users/{id}` - Update user
- `DELETE /api/v1/users/{id}` - Delete user

#### Farm Management
- `GET /api/v1/farms` - List farms with user scoping
- `GET /api/v1/farms/{id}` - Get farm details
- `POST /api/v1/farms` - Create new farm
- `PUT /api/v1/farms/{id}` - Update farm
- `DELETE /api/v1/farms/{id}` - Delete farm

#### Shed Management
- `GET /api/v1/sheds` - List sheds with farm scoping
- `GET /api/v1/sheds/{id}` - Get shed details with relationships
- `POST /api/v1/sheds` - Create new shed
- `PUT /api/v1/sheds/{id}` - Update shed
- `DELETE /api/v1/sheds/{id}` - Delete shed

#### Device Management
- `GET /api/v1/devices` - List devices
- `GET /api/v1/devices/{id}` - Get device details
- `POST /api/v1/devices` - Register new device
- `PUT /api/v1/devices/{id}` - Update device
- `DELETE /api/v1/devices/{id}` - Delete device

#### Device Appliance Management
- `GET /api/v1/device-appliances` - List appliances
- `GET /api/v1/device-appliances/{id}` - Get appliance details
- `POST /api/v1/device-appliances` - Create appliance
- `PUT /api/v1/device-appliances/{id}` - Update appliance
- `DELETE /api/v1/device-appliances/{id}` - Delete appliance

#### IoT Endpoints (Unauthenticated)
- `PUT /api/v1/device-appliances/{id}/status` - Update appliance status
- `PUT /api/v1/device-appliances/statuses/update` - Batch status updates
- `GET /api/v1/device-appliances/{id}/status` - Get appliance status
- `GET /api/v1/device-appliances/statuses` - Get all statuses
- `GET /api/v1/device/{serial}/appliances` - Get device appliances
- `GET /api/v1/device/{serial}/appliance-ids` - Get appliance IDs
- `GET /api/v1/shed/{id}/appliances` - Get shed appliances

#### Flock Management
- `GET /api/v1/flocks` - List flocks with shed scoping
- `GET /api/v1/flocks/{id}` - Get flock details
- `POST /api/v1/flocks` - Create new flock
- `PUT /api/v1/flocks/{id}` - Update flock
- `DELETE /api/v1/flocks/{id}` - Delete flock

#### Breed Management
- `GET /api/v1/breeds` - List breeds
- `GET /api/v1/breeds/{id}` - Get breed details
- `POST /api/v1/breeds` - Create new breed
- `PUT /api/v1/breeds/{id}` - Update breed
- `DELETE /api/v1/breeds/{id}` - Delete breed

#### Sensor Data Management
- `POST /api/v1/sensor-data` - Store sensor data
- `GET /api/v1/sensor-data/shed/{id}` - Get shed sensor data
- `GET /api/v1/sensor-data/farm/{id}` - Get farm sensor data

### Test Scenarios Covered

#### Authentication & Authorization
- ✅ User authentication required for protected endpoints
- ✅ User can only access their own data
- ✅ IoT endpoints allow unauthenticated access
- ✅ Role-based access control

#### Data Validation
- ✅ Required field validation
- ✅ Data type validation
- ✅ Range validation (coordinates, counts, etc.)
- ✅ Unique constraint validation
- ✅ Enum value validation

#### Query Builder Features
- ✅ Filtering by various fields
- ✅ Sorting by multiple fields
- ✅ Eager loading relationships
- ✅ Pagination support
- ✅ Search functionality

#### Error Handling
- ✅ 404 for non-existent resources
- ✅ 422 for validation errors
- ✅ 401 for authentication failures
- ✅ 500 for server errors
- ✅ Graceful DynamoDB error handling

#### Business Logic
- ✅ User ownership validation
- ✅ Relationship integrity
- ✅ Data consistency
- ✅ Real-time status updates
- ✅ Statistics calculation

## Running Tests

### Prerequisites

1. Ensure all dependencies are installed:
   ```bash
   composer install
   ```

2. Set up the test database:
   ```bash
   php artisan migrate --env=testing
   ```

3. Configure test environment in `.env.testing`:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=:memory:
   ```

### Running All Tests

```bash
# Run all tests
php artisan test

# Run with verbose output
php artisan test --verbose

# Run with coverage report
php artisan test --coverage --min=80
```

### Running Specific Test Categories

```bash
# Run only feature tests
php artisan test tests/Feature/

# Run only unit tests
php artisan test tests/Unit/

# Run specific test file
php artisan test tests/Feature/Api/V1/UserControllerTest.php
```

### Running Tests with Filters

```bash
# Run tests containing "User" in the name
php artisan test --filter=User

# Run tests containing "Farm" in the name
php artisan test --filter=Farm

# Run tests containing "Device" in the name
php artisan test --filter=Device
```

### Using the Test Runner

```php
// Run all tests
Tests\TestRunner::runAllTests();

// Run only feature tests
Tests\TestRunner::runFeatureTests();

// Run only unit tests
Tests\TestRunner::runUnitTests();

// Generate coverage report
Tests\TestRunner::generateCoverageReport();

// Run filtered tests
Tests\TestRunner::runFilteredTests('UserController');
```

## Test Data

### Factories

The tests use Laravel factories to generate test data:

- `UserFactory` - Creates users with realistic data
- `FarmFactory` - Creates farms with coordinates
- `ShedFactory` - Creates sheds with capacity and type
- `DeviceFactory` - Creates devices with capabilities
- `DeviceApplianceFactory` - Creates appliances with status
- `FlockFactory` - Creates flocks with dates and counts
- `BreedFactory` - Creates breeds with categories

### Database Seeding

Tests use `RefreshDatabase` trait to ensure clean state between tests.

## Mocking

### External Services

- **DynamoDB Service**: Mocked for sensor data operations
- **AWS SDK**: Mocked for cloud service interactions
- **File Storage**: Mocked for media uploads

### Authentication

- **Sanctum**: Used for API authentication testing
- **Guest Access**: Tested for IoT endpoints

## Continuous Integration

### GitHub Actions

The test suite is configured to run automatically on:

- Pull requests
- Push to main branch
- Scheduled runs

### Test Matrix

Tests run against:
- PHP 8.2+
- Laravel 12.x
- SQLite (testing)
- MySQL (staging)
- PostgreSQL (production)

## Coverage Goals

- **Feature Tests**: 95%+ coverage of API endpoints
- **Unit Tests**: 90%+ coverage of business logic
- **Integration Tests**: 85%+ coverage of external services

## Best Practices

### Test Organization

1. **Arrange-Act-Assert**: Clear test structure
2. **Descriptive Names**: Tests describe expected behavior
3. **Single Responsibility**: Each test focuses on one scenario
4. **Data Isolation**: Tests don't depend on each other

### Performance

1. **Database Transactions**: Fast rollback between tests
2. **Factory Optimization**: Minimal data creation
3. **Mocking**: External services mocked for speed
4. **Parallel Execution**: Tests can run in parallel

### Maintenance

1. **Regular Updates**: Tests updated with code changes
2. **Documentation**: Clear test descriptions
3. **Refactoring**: Tests refactored for clarity
4. **Coverage Monitoring**: Regular coverage reports

## Troubleshooting

### Common Issues

1. **Database Connection**: Ensure test database is configured
2. **Factory Issues**: Check factory definitions
3. **Mock Failures**: Verify mock expectations
4. **Environment Variables**: Check test environment config

### Debugging

```bash
# Run single test with debug output
php artisan test --filter="test_name" --verbose

# Run with stop on failure
php artisan test --stop-on-failure

# Run with coverage for specific file
php artisan test --coverage --filter="UserController"
```

## Contributing

When adding new features:

1. Write tests first (TDD approach)
2. Ensure all tests pass
3. Maintain coverage goals
4. Update documentation
5. Add integration tests for external services

## Support

For test-related issues:

1. Check the test documentation
2. Review existing test patterns
3. Consult the Laravel testing guide
4. Create an issue with test details 