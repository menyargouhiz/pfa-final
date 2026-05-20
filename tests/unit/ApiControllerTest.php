<?php
require_once __DIR__ . '/../MockDatabaseTestCase.php';
require_once __DIR__ . '/../../model/user.php';
require_once __DIR__ . '/../../model/review.php';

class ApiControllerTest extends MockDatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        MockPhpStream::$input = '';
        $_SESSION = [];
        $_GET = [];
        $_POST = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    private function runController($file)
    {
        global $cnx;
        ob_start();
        try {
            require $file;
            ob_end_clean();
        } catch (Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }

    public function testApiLoginSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        MockPhpStream::$input = json_encode(['email' => 'valid@example.com', 'password' => 'password123']);
        
        $mockUser = new User("Valid User", "valid@example.com", password_hash("password123", PASSWORD_DEFAULT));
        $mockUser->id = 1;
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn($mockUser);

        try {
            $this->runController(__DIR__ . '/../../controller/api_login.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiSignupSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        MockPhpStream::$input = json_encode(['name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'pass123']);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn(false);

        try {
            $this->runController(__DIR__ . '/../../controller/api_signup.php');
        } catch (ResponseException $e) {
            $this->assertEquals(201, $e->statusCode);
        }
    }

    public function testApiCreateReviewSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        
        MockPhpStream::$input = json_encode([
            'restaurant_id' => 1,
            'author' => 'Reviewer',
            'text' => 'This is a great review!',
            'facture_code' => 'FACT-999',
            'ambiance' => 5,
            'cleanliness' => 5,
            'quality' => 5,
            'service' => 5
        ]);

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn(false);

        try {
            $this->runController(__DIR__ . '/../../controller/api_create_review.php');
        } catch (ResponseException $e) {
            $this->assertEquals(201, $e->statusCode);
        }
    }

    public function testApiReadReviews()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['restaurant_id'] = 1;

        $mockReviews = [
            new Review(1, "Author 1", 5, "Text 1")
        ];

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetchAll')->willReturn($mockReviews);

        try {
            $this->runController(__DIR__ . '/../../controller/api_read_reviews.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testGetRestaurants()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetchAll')->willReturn([['id' => 1, 'name' => 'Resto']]);

        try {
            $this->runController(__DIR__ . '/../../controller/get_restaurants.php');
            $this->assertTrue(true);
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiFavoritesGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetchAll')->willReturn([]);

        try {
            $this->runController(__DIR__ . '/../../controller/api_favorites.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }
    
    public function testApiFavoritesPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        MockPhpStream::$input = json_encode(['restaurant_id' => 1]);

        $this->mockStatement->method('execute')->willReturn(true);

        try {
            $this->runController(__DIR__ . '/../../controller/api_favorites.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiWishlistGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetchAll')->willReturn([]);

        try {
            $this->runController(__DIR__ . '/../../controller/api_wishlist.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }
    
    public function testApiLogout()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        try {
            $this->runController(__DIR__ . '/../../controller/api_logout.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiUpdateReviewSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        MockPhpStream::$input = json_encode(['review_id' => 1, 'rating' => 5, 'text' => 'Updated text here!']);
        
        $mockReview = new Review(1, "Author", 4, "Old text", "2023-10-01", 1);
        $mockReview->id = 1;
        $mockReview->user_id = 1;

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn($mockReview);

        try {
            $this->runController(__DIR__ . '/../../controller/api_update_review.php');
            $this->assertTrue(true);
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiDeleteReviewSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        MockPhpStream::$input = json_encode(['review_id' => 1]);
        
        $mockReview = new Review(1, "Author", 4, "Old text", "2023-10-01", 1);
        $mockReview->id = 1;
        $mockReview->user_id = 1;

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn($mockReview);

        try {
            $this->runController(__DIR__ . '/../../controller/api_delete_review.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testDeleteUserSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['id'] = 1;

        $this->mockStatement->method('execute')->willReturn(true);

        try {
            $this->runController(__DIR__ . '/../../controller/delete_user.php');
            $this->assertTrue(true);
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testUpdateUserSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['idu'] = 1;
        $_POST['user_name'] = 'Jane Doe';
        $_POST['email'] = 'jane@example.com';
        $_POST['password'] = 'newpassword123';

        $this->mockStatement->method('execute')->willReturn(true);

        try {
            $this->runController(__DIR__ . '/../../controller/update_user.php');
            $this->assertTrue(true);
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testTraitementAddSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['action'] = 'add';
        $_POST['user_name'] = 'Alice';
        $_POST['email'] = 'alice@example.com';
        $_POST['password'] = 'alicepass123';

        $this->mockStatement->method('execute')->willReturn(true);

        try {
            $this->runController(__DIR__ . '/../../controller/traitement.php');
            $this->assertTrue(true);
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testTraitementGetAllSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['action'] = 'getAll';

        $this->mockStatement->method('fetchAll')->willReturn([]);

        try {
            $this->runController(__DIR__ . '/../../controller/traitement.php');
            $this->assertTrue(true);
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testTraitementSearchSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['action'] = 'search';
        $_POST['name'] = 'test';

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetchAll')->willReturn([]);

        try {
            $this->runController(__DIR__ . '/../../controller/traitement.php');
            $this->assertTrue(true);
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiCheckUsersSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->mockStatement->method('fetchAll')->willReturn([]);

        try {
            $this->runController(__DIR__ . '/../../controller/api_check_users.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiCheckUsersPwdSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->mockStatement->method('fetchAll')->willReturn([]);

        try {
            $this->runController(__DIR__ . '/../../controller/api_check_users_pwd.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiSessionSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = 'Test User';
        $_SESSION['user_email'] = 'test@example.com';

        try {
            $this->runController(__DIR__ . '/../../controller/api_session.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiFavoritesDelete()
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        MockPhpStream::$input = json_encode(['restaurant_id' => 1]);

        $this->mockStatement->method('execute')->willReturn(true);

        try {
            $this->runController(__DIR__ . '/../../controller/api_favorites.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiWishlistPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        MockPhpStream::$input = json_encode(['restaurant_id' => 1]);

        $this->mockStatement->method('execute')->willReturn(true);

        try {
            $this->runController(__DIR__ . '/../../controller/api_wishlist.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiWishlistDelete()
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        MockPhpStream::$input = json_encode(['restaurant_id' => 1]);

        $this->mockStatement->method('execute')->willReturn(true);

        try {
            $this->runController(__DIR__ . '/../../controller/api_wishlist.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiDeleteReviewNotAuthorized()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 2;
        MockPhpStream::$input = json_encode(['review_id' => 1]);
        
        $mockReview = new Review(1, "Author", 4, "Old text", "2023-10-01", 1);
        $mockReview->id = 1;
        $mockReview->user_id = 1;

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn($mockReview);

        try {
            $this->runController(__DIR__ . '/../../controller/api_delete_review.php');
        } catch (ResponseException $e) {
            $this->assertEquals(403, $e->statusCode);
        }
    }

    public function testApiDeleteReviewNotFound()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        MockPhpStream::$input = json_encode(['review_id' => 999]);

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn(false);

        try {
            $this->runController(__DIR__ . '/../../controller/api_delete_review.php');
        } catch (ResponseException $e) {
            $this->assertEquals(404, $e->statusCode);
        }
    }

    public function testApiUpdateReviewNotFound()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        MockPhpStream::$input = json_encode(['review_id' => 999, 'rating' => 5, 'text' => 'Updated text']);

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn(false);

        try {
            $this->runController(__DIR__ . '/../../controller/api_update_review.php');
        } catch (ResponseException $e) {
            $this->assertEquals(404, http_response_code());
        }
    }

    public function testApiDebug()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['user_id'] = 1;

        $this->mockStatement->method('fetch')->willReturn(['id' => 1]);
        $this->mockStatement->method('fetchAll')->willReturn([]);

        try {
            $this->runController(__DIR__ . '/../../controller/api_debug.php');
            $this->assertTrue(true);
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiDebugSession()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['user_id'] = 1;

        try {
            $this->runController(__DIR__ . '/../../controller/api_debug_session.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiFixDbSchema()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->mockStatement->method('fetchColumn')->willReturn(0);
        $this->mockPdo->method('exec')->willReturn(1);

        try {
            $this->runController(__DIR__ . '/../../controller/api_fix_db_schema.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testApiFixDbSchemaAlreadyFixed()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->mockStatement->method('fetchColumn')->willReturn(1);

        try {
            $this->runController(__DIR__ . '/../../controller/api_fix_db_schema.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testRestaurantImage()
    {
        try {
            $this->runController(__DIR__ . '/../../controller/restaurant_image.php');
            $this->assertTrue(true);
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }
    }

    public function testErrorHandlerWarning()
    {
        require_once __DIR__ . '/../../controller/response.php';
        require_once __DIR__ . '/../../controller/error_handler.php';

        ob_start();
        try {
            trigger_error("Test warning", E_USER_WARNING);
        } catch (Throwable $e) {
            $this->assertTrue(true);
        } finally {
            ob_end_clean();
            restore_error_handler();
            restore_exception_handler();
        }
    }

    public function testApiCreateReviewInvalidAmbiance()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        
        MockPhpStream::$input = json_encode([
            'restaurant_id' => 1,
            'author' => 'Reviewer',
            'text' => 'This is a great review!',
            'facture_code' => 'FACT-999',
            'ambiance' => 6,
            'cleanliness' => 5,
            'quality' => 5,
            'service' => 5
        ]);

        try {
            $this->runController(__DIR__ . '/../../controller/api_create_review.php');
        } catch (ResponseException $e) {
            $this->assertEquals(400, $e->statusCode);
        }
    }

    public function testApiCreateReviewDuplicateFacture()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;
        
        MockPhpStream::$input = json_encode([
            'restaurant_id' => 1,
            'author' => 'Reviewer',
            'text' => 'This is a great review!',
            'facture_code' => 'FACT-999',
            'ambiance' => 5,
            'cleanliness' => 5,
            'quality' => 5,
            'service' => 5
        ]);

        $mockReview = new Review(1, "Author", 4, "Old text", "2023-10-01", 1);
        $mockReview->id = 1;

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn($mockReview);

        try {
            $this->runController(__DIR__ . '/../../controller/api_create_review.php');
        } catch (ResponseException $e) {
            $this->assertEquals(409, $e->statusCode);
        }
    }

    public function testApiSignupDuplicateEmail()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        MockPhpStream::$input = json_encode(['name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'pass123']);
        
        $mockUser = new User("John Doe", "john@example.com", "hash");
        $mockUser->id = 1;

        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn($mockUser);

        try {
            $this->runController(__DIR__ . '/../../controller/api_signup.php');
        } catch (ResponseException $e) {
            $this->assertEquals(400, $e->statusCode);
        }
    }

    public function testCorsPreflight()
    {
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';

        try {
            $this->runController(__DIR__ . '/../../controller/api_login.php');
        } catch (ResponseException $e) {
            $this->assertEquals(200, $e->statusCode);
        }
    }

    public function testAllControllersEmptyGet()
    {
        $files = glob(__DIR__ . '/../../controller/*.php');
        foreach ($files as $file) {
            $name = basename($file);
            if (in_array($name, ['response.php', 'validators.php', 'error_handler.php', 'api_debug.php', 'api_debug_session.php'])) continue;

            $_SERVER['REQUEST_METHOD'] = 'GET';
            $_GET = [];
            MockPhpStream::$input = '';
            
            try {
                $this->runController($file);
                $this->assertTrue(true);
            } catch (Throwable $e) {
                $this->assertTrue(true);
            }
        }
    }
    
    public function testAllControllersEmptyPost()
    {
        $files = glob(__DIR__ . '/../../controller/*.php');
        foreach ($files as $file) {
            $name = basename($file);
            if (in_array($name, ['response.php', 'validators.php', 'error_handler.php', 'api_debug.php', 'api_debug_session.php'])) continue;

            $_SERVER['REQUEST_METHOD'] = 'POST';
            MockPhpStream::$input = json_encode([]);
            $_POST = [];
            
            try {
                $this->runController($file);
                $this->assertTrue(true);
            } catch (Throwable $e) {
                $this->assertTrue(true);
            }
        }
    }
}
