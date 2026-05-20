<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controller/validators.php';

class ValidatorsTest extends TestCase
{
    public function testValidateEmail()
    {
        $this->assertTrue(validateEmail('test@example.com')['valid']);
        $this->assertFalse(validateEmail('invalid_email')['valid']);
        $this->assertFalse(validateEmail('')['valid']);
        $this->assertFalse(validateEmail(str_repeat('a', 250) . '@example.com')['valid']);
    }

    public function testValidatePassword()
    {
        $this->assertTrue(validatePassword('password123')['valid']);
        $this->assertFalse(validatePassword('short')['valid']); // < 6 chars
        $this->assertFalse(validatePassword('nouppercaseornumber')['valid']); // missing number
        $this->assertFalse(validatePassword('')['valid']);
    }

    public function testValidateName()
    {
        $this->assertTrue(validateName('Valid Name')['valid']);
        $this->assertFalse(validateName('A')['valid']); // too short
        $this->assertFalse(validateName('<script>')['valid']); // invalid chars
        $this->assertFalse(validateName('')['valid']);
    }

    public function testValidateRating()
    {
        $this->assertTrue(validateRating(5)['valid']);
        $this->assertTrue(validateRating(1)['valid']);
        $this->assertFalse(validateRating(0)['valid']);
        $this->assertFalse(validateRating(6)['valid']);
    }

    public function testValidateDimensionRating()
    {
        $this->assertTrue(validateDimensionRating(4, 'Ambiance')['valid']);
        $this->assertFalse(validateDimensionRating(6, 'Service')['valid']);
    }

    public function testValidateReviewText()
    {
        $this->assertTrue(validateReviewText('Great place!')['valid']);
        $this->assertFalse(validateReviewText('Bad')['valid']); // too short
        $this->assertFalse(validateReviewText('')['valid']);
    }

    public function testValidateFactureCode()
    {
        $this->assertTrue(validateFactureCode('FACT-1234')['valid']);
        $this->assertFalse(validateFactureCode('F12')['valid']); // too short
        $this->assertFalse(validateFactureCode('FACT<123')['valid']); // invalid chars
        $this->assertFalse(validateFactureCode('')['valid']);
    }

    public function testSanitizeString()
    {
        $this->assertEquals('&lt;b&gt;bold&lt;/b&gt;', sanitizeString('<b>bold</b>'));
    }
}
