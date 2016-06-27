<?php

namespace Symfony\Component\Validator\Tests\Constraints;

use Symfony\Component\Validator\Constraints\Bibcode;
use Symfony\Component\Validator\Constraints\BibcodeValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @see https://en.wikipedia.org/wiki/Bibcode
 */
class BibcodeValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
    {
        return new BibcodeValidator();
    }

    public function getValidBibcode()
    {
        return array(
            array('1974AJ.....79..819H'),
            array('1924MNRAS..84..308E'),
            array('1970ApJ...161L..77K'),
            array('2004PhRvL..93o0801M'),
            array('2016ZAGeo.tmp...36.'),
            array('2016SoPh..tmp..106M'),
            array('1996ZVer..121..577B'),
            array('2009arXiv0901.0115G'),
        );
    }

    public function getInvalidBibcode()
    {
        return array(
            array(0, Bibcode::TOO_SHORT_ERROR),
            array('970ApJ...161L..77K', Bibcode::TOO_SHORT_ERROR),
            array('A970ApJ...161L..77K', Bibcode::INVALID_CHARACTERS_ERROR),
            array('197012J...161L..77K', Bibcode::INVALID_CHARACTERS_ERROR),
            array('1970ApJ...161L..777', Bibcode::INVALID_CHARACTERS_ERROR),
            array('1970ApJ...fooL..77K', Bibcode::INVALID_CHARACTERS_ERROR)
        );
    }

    public function testNullIsValid()
    {
        $constraint = new Bibcode();

        $this->validator->validate(null, $constraint);

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $constraint = new Bibcode();

        $this->validator->validate('', $constraint);

        $this->assertNoViolation();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testExpectsStringCompatibleType()
    {
        $constraint = new Bibcode();
        $this->validator->validate(new \stdClass(), $constraint);
    }

    /**
     * @dataProvider getValidBibcode
     */
    public function testValidBibcode($bibcode)
    {
        $constraint = new Bibcode();

        $this->validator->validate($bibcode, $constraint);

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getInvalidBibcode
     */
    public function testInvalidBibcode($bibcode, $code)
    {
        $constraint = new Bibcode(array(
            'message' => 'myMessage',
        ));

        $this->validator->validate($bibcode, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$bibcode.'"')
            ->setCode($code)
            ->assertRaised();
    }
}
