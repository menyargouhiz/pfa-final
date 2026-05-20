# Performance Tests

This folder contains guidance for the repository's performance-oriented regression tests.

## What is included

- `tests/performance/PerformanceTest.php` - PHPUnit performance tests that exercise key API controller flows and ensure response times remain within a reasonable threshold.

## Running performance tests

From the repository root, run:

```bash
composer install
vendor/bin/phpunit tests/performance/PerformanceTest.php
```

If you want to run all tests, use:

```bash
vendor/bin/phpunit
```

## Notes

- These tests are not a full load-test suite, but they provide automated performance regression coverage for critical controller paths.
- The performance thresholds are intentionally moderate so the tests catch major regressions without failing on normal developer machines.
