<?php
use PHPUnit\Framework\TestCase;

class MockDatabaseTestCase extends TestCase
{
    protected $mockPdo;
    protected $mockStatement;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create mock for PDOStatement
        $this->mockStatement = $this->createMock(PDOStatement::class);
        
        // Create mock for PDO
        $this->mockPdo = $this->createMock(PDO::class);
        
        // By default, prepare() returns our mock statement
        $this->mockPdo->method('prepare')->willReturn($this->mockStatement);
        
        // By default, query() returns our mock statement
        $this->mockPdo->method('query')->willReturn($this->mockStatement);

        // Override the global database connection variable
        global $cnx;
        $cnx = $this->mockPdo;
    }
    
    protected function tearDown(): void
    {
        global $cnx;
        $cnx = null;
        parent::tearDown();
    }
}
