<?php

namespace Symfony\Component\Validator\Tests\Constraints;

use Symfony\Component\Validator\Constraints\Doi;
use Symfony\Component\Validator\Constraints\DoiValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @see https://en.wikipedia.org/wiki/Digital_object_identifier
 */
class DoiValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
    {
        return new DoiValidator();
    }

    public function getValidDoi()
    {
        return array(
            array('10.1000/182'),
            array('10.1088/0004-637X/815/2/137'),
            array('doi:10.1088/0004-6256/140/6/2070'),
            array('http://dx.doi.org/10.1515/zna-2006-1213'),
            array('10.1557/PROC-122-135'),
            array('10.4028/www.scientific.net/AMR.415-417.237'),
            array('10.1016/j.ejphar.2010.10.078'),
            array('10.2139/ssrn.1910534'),
        );
    }

    public function getInvalidDoi()
    {
        return array(
            array('1234/abcde', Doi::DOI_REGISTRY_REQUIRED_ERROR),
            array('10.1234abcde', Doi::SLASH_REQUIRED_ERROR),
            array('10.1234/', Doi::TOO_SHORT_ERROR),
            array('10./5678', Doi::TOO_SHORT_ERROR),
        );
    }

    public function testNullIsValid()
    {
        $constraint = new Doi();

        $this->validator->validate(null, $constraint);

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $constraint = new Doi();

        $this->validator->validate('', $constraint);

        $this->assertNoViolation();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testExpectsStringCompatibleType()
    {
        $constraint = new Doi();
        $this->validator->validate(new \stdClass(), $constraint);
    }

    /**
     * @dataProvider getValidDoi
     */
    public function testValidDoi($doi)
    {
        $constraint = new Doi();

        $this->validator->validate($doi, $constraint);

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getInvalidDoi
     */
    public function testInvalidDoi($doi, $code)
    {
        $constraint = new Doi(array(
            'message' => 'myMessage',
        ));

        $this->validator->validate($doi, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$doi.'"')
            ->setCode($code)
            ->assertRaised();
    }
}
