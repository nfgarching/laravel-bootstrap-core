# Component Package Tests

This directory contains tests for the Nfgarching Core package, focusing on authentication functionality.

## Test Structure

### Feature Tests (`tests/Feature/`)

Feature tests verify that the authentication system works correctly from a user's perspective:

- **AuthenticationTest.php** - Tests login, logout, and remember me functionality
- **RegistrationTest.php** - Tests user registration with validation
- **DashboardTest.php** - Tests dashboard access and display
- **MiddlewareTest.php** - Tests auth and guest middleware protection
- **RouteTest.php** - Tests that all routes exist and work correctly
- **ViewTest.php** - Tests view rendering and component namespaces

### Unit Tests (`tests/Unit/`)

Unit tests verify individual components work correctly in isolation:

- **AuthControllerTest.php** - Tests authentication controllers

## Running Tests

### Run all tests
```bash
php artisan test
```

### Run specific test file
```bash
php artisan test tests/Feature/AuthenticationTest.php
```

### Run tests with specific filter
```bash
php artisan test --filter=test_users_can_authenticate
```

### Run tests from the package directory
```bash
cd packages/nfgarching/core
php artisan test
```

## Test Coverage

### Authentication Tests
- ✅ Login screen rendering
- ✅ User authentication with valid credentials
- ✅ Failed authentication with invalid credentials
- ✅ Logout functionality
- ✅ Remember me functionality
- ✅ Guest middleware (preventing authenticated users from accessing login/register)

### Registration Tests
- ✅ Registration screen rendering
- ✅ User registration with valid data
- ✅ Validation for all required fields
- ✅ Email uniqueness validation
- ✅ Password confirmation validation
- ✅ Email conversion to lowercase

### Dashboard Tests
- ✅ Dashboard rendering for authenticated users
- ✅ User information display
- ✅ Logout button presence
- ✅ Guest redirection to login

### Middleware Tests
- ✅ Auth middleware protection on protected routes
- ✅ Guest middleware redirection for authenticated users
- ✅ Public route accessibility

### Route Tests
- ✅ All route existence
- ✅ Named route availability
- ✅ Route protection verification

### View Tests
- ✅ View namespace registration
- ✅ Layout usage (guest-layout, main-layout)
- ✅ View component rendering
- ✅ Error message display

## Database

Tests use an in-memory SQLite database (configured in `phpunit.xml`) and the `RefreshDatabase` trait to ensure a clean state for each test.

## Writing New Tests

When adding new features, ensure to:

1. Add feature tests for user-facing functionality
2. Add unit tests for individual components
3. Test both success and failure scenarios
4. Test validation rules
5. Test middleware protection
6. Run all tests before committing

## Test Database

The test suite uses Laravel's `RefreshDatabase` trait, which:
- Migrates the database before each test
- Rolls back changes after each test
- Ensures test isolation

## Continuous Integration

These tests should be run:
- Before committing code
- In CI/CD pipelines
- Before deploying to production
