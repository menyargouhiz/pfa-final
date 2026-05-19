<?php
require_once __DIR__ . '/../MockDatabaseTestCase.php';
require_once __DIR__ . '/../../model/comment.php';

class CommentTest extends MockDatabaseTestCase
{
    public function testCommentInstantiation()
    {
        $comment = new Comment(1, 10, "Commenter", "Nice review", "2023-10-01 12:00:00");
        
        $this->assertEquals(1, $comment->review_id);
        $this->assertEquals(10, $comment->user_id);
        $this->assertEquals("Commenter", $comment->author);
        $this->assertEquals("Nice review", $comment->text);
    }

    public function testCommentCreate()
    {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = Comment::create(1, 10, "Commenter", "Nice review");
        $this->assertTrue($result);
    }

    public function testFindByReview()
    {
        $mockComments = [
            new Comment(1, 10, "User 1", "Comment 1"),
            new Comment(1, 11, "User 2", "Comment 2")
        ];

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($mockComments);

        $results = Comment::findByReview(1);
        $this->assertCount(2, $results);
        $this->assertEquals("User 1", $results[0]->author);
    }

    public function testDelete()
    {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([1])
            ->willReturn(true);

        $result = Comment::delete(1);
        $this->assertTrue($result);
    }
}
