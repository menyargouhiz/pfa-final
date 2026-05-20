<?php
require_once __DIR__ . '/../MockDatabaseTestCase.php';
require_once __DIR__ . '/../../model/user.php';

class UserTest extends MockDatabaseTestCase
{
    public function testUserCreationLogic()
    {
        $user = new User("Test User", "test@example.com", "password123");
        $this->assertEquals("Test User", $user->nom);
        $this->assertEquals("test@example.com", $user->email);
        $this->assertEquals("password123", $user->password);
    }

    public function testCreateUserWithMockedDb()
    {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = User::create("Test User", "test@example.com", "password123");
        $this->assertTrue($result);
    }

    public function testFindByEmail()
    {
        $mockUser = new User("Found User", "found@example.com", "hashedpassword");
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with(['found@example.com']);
            
        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->willReturn($mockUser);

        $result = User::findByEmail("found@example.com");
        $this->assertEquals("Found User", $result->nom);
    }

    public function testReadAll()
    {
        $mockUsers = [
            new User("User 1", "u1@example.com", "hash1"),
            new User("User 2", "u2@example.com", "hash2")
        ];

        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($mockUsers);

        $results = User::readAll();
        $this->assertCount(2, $results);
        $this->assertEquals("User 1", $results[0]->nom);
    }

    public function testFindById()
    {
        $mockUser = new User("User 1", "u1@example.com", "hash1");

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([1]);
            
        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->willReturn($mockUser);

        $result = User::findById(1);
        $this->assertEquals("User 1", $result->nom);
    }

    public function testUpdate()
    {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with(["Updated Name", "updated@example.com", 1])
            ->willReturn(true);

        $result = User::update(1, "Updated Name", "updated@example.com");
        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([1])
            ->willReturn(true);

        $result = User::delete(1);
        $this->assertTrue($result);
    }
}
