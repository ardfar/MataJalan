# Testing Methodology

## Overview
MATAJALAN_OS uses **Pest PHP**, an elegant testing framework built on top of PHPUnit, to ensure system stability and reliability.

## Test Suites

### 1. Feature Tests (`tests/Feature`)
These tests simulate HTTP requests to the application and verify the response, database state, and side effects.

- **Authentication**: Verifies login, registration, and password reset flows.
- **Vehicle Rating**: Tests the end-to-end flow of submitting a rating.
- **Profile Management**: Checks user profile updates.

### 2. Unit Tests (`tests/Unit`)
These tests focus on individual methods and classes in isolation (e.g., helper functions, complex calculations).

## Running Tests

To run the full test suite:
```bash
php artisan test
```

To run a specific test file:
```bash
php artisan test tests/Feature/VehicleRatingTest.php
```

To run tests with a coverage report (requires Xdebug or PCOV):
```bash
php artisan test --coverage
```

## Writing New Tests
When adding a new feature, always include a corresponding test.

**Example Feature Test (Pest):**
```php
it('allows authenticated user to rate a vehicle', function () {
    $user = User::factory()->create();
    $vehicle = Vehicle::factory()->create(['plate_number' => 'B1234XYZ']);

    $response = $this->actingAs($user)
        ->post('/vehicle/B1234XYZ/rate', [
            'rating' => 5,
            'comment' => 'Great driver!',
            'tags' => 'safe,polite'
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('ratings', [
        'vehicle_id' => $vehicle->id,
        'rating' => 5
    ]);
});
```
