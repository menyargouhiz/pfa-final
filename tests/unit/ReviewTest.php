<?php
require_once __DIR__ . '/../MockDatabaseTestCase.php';
require_once __DIR__ . '/../../model/review.php';

class ReviewTest extends MockDatabaseTestCase
{
    public function testReviewInstantiation()
    {
        $review = new Review(1, "Author Name", 5, "Great food", "2023-10-01", 10, 5, 4, 5, 4, "FACT123");
        
        $this->assertEquals(1, $review->restaurant_id);
        $this->assertEquals("Author Name", $review->author);
        $this->assertEquals(5, $review->rating);
        $this->assertEquals("Great food", $review->text);
        $this->assertEquals("FACT123", $review->facture_code);
    }

    public function testReviewCreate()
    {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = Review::create(1, "Author", 5, "Text", "FACT123", 5, 5, 5, 5, 10);
        $this->assertTrue($result);
    }

    public function testFindByRestaurant()
    {
        $mockReviews = [
            new Review(1, "Author 1", 5, "Text 1"),
            new Review(1, "Author 2", 4, "Text 2")
        ];

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($mockReviews);

        $results = Review::findByRestaurant(1);
        $this->assertCount(2, $results);
        $this->assertEquals("Author 1", $results[0]->author);
    }

    public function testFindById()
    {
        $mockReview = new Review(1, "Author", 5, "Text");

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([1]);
            
        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->willReturn($mockReview);

        $result = Review::findById(1);
        $this->assertEquals("Author", $result->author);
    }

    public function testUpdate()
    {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([4, "Updated text", 1])
            ->willReturn(true);

        $result = Review::update(1, 4, "Updated text");
        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([1])
            ->willReturn(true);

        $result = Review::delete(1);
        $this->assertTrue($result);
    }
}
