<?php
require_once dirname(__DIR__, 1) . '/bootstrap.php';
require_once dirname(__DIR__, 1) . '/MockDatabaseTestCase.php';
require_once dirname(__DIR__, 1) . '/../model/user.php';
require_once dirname(__DIR__, 1) . '/../model/review.php';

class PerformanceTest extends MockDatabaseTestCase
{

    private int $iterations = 10;
    private float $maxElapsedSeconds = 1.0;

    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        $_GET = [];
        $_POST = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
        MockPhpStream::$input = '';
    }

    private function runController(string $file): void
    {
        ob_start();
        try {
            require $file;
            ob_end_clean();
        } catch (ResponseException $e) {
            ob_end_clean();
        } catch (Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }

    private function measureController(string $file, callable $prepare): float
    {
        $start = microtime(true);

        for ($i = 0; $i < $this->iterations; $i++) {
            $_SESSION = [];
            $_GET = [];
            $_POST = [];
            $_SERVER['REQUEST_METHOD'] = 'GET';
            MockPhpStream::$input = '';

            $prepare();
            $this->runController($file);
        }

        return microtime(true) - $start;
    }

    public function testApiLoginPerformance()
    {
        $this->mockStatement->method('execute')->willReturn(true);

        $duration = $this->measureController(__DIR__ . '/../../controller/api_login.php', function () {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            MockPhpStream::$input = json_encode([
                'email' => 'valid@example.com',
                'password' => 'password123'
            ]);

            $mockUser = new User('Valid User', 'valid@example.com', password_hash('password123', PASSWORD_DEFAULT));
            $mockUser->id = 1;
            $this->mockStatement->method('fetch')->willReturn($mockUser);
        });

        $this->assertLessThan(
            $this->maxElapsedSeconds,
            $duration,
            "api_login.php should complete {$this->iterations} iterations within {$this->maxElapsedSeconds} seconds"
        );
    }

    public function testApiReadReviewsPerformance()
    {
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetchAll')->willReturn([
            new Review(1, 'Author 1', 5, 'Text 1')
        ]);

        $duration = $this->measureController(__DIR__ . '/../../controller/api_read_reviews.php', function () {
            $_SERVER['REQUEST_METHOD'] = 'GET';
            $_GET['restaurant_id'] = 1;
        });

        $this->assertLessThan(
            $this->maxElapsedSeconds,
            $duration,
            "api_read_reviews.php should complete {$this->iterations} iterations within {$this->maxElapsedSeconds} seconds"
        );
    }

    public function testGetRestaurantsPerformance()
    {
        $this->mockPdo->method('query')->willReturn($this->mockStatement);
        $this->mockStatement->method('fetchAll')->willReturnOnConsecutiveCalls(
            [
                ['id' => 1, 'name' => 'Resto', 'city' => 'Tunis', 'lat' => '36.8', 'lng' => '10.18', 'tags' => 'local,cozy'],
            ],
            [],
            []
        );

        $duration = $this->measureController(__DIR__ . '/../../controller/get_restaurants.php', function () {
            $_SERVER['REQUEST_METHOD'] = 'GET';
        });

        $this->assertLessThan(
            $this->maxElapsedSeconds,
            $duration,
            "get_restaurants.php should complete {$this->iterations} iterations within {$this->maxElapsedSeconds} seconds"
        );
    }
}
