<?php
namespace PhotoOrganize\Domain;


use Assert\Assertion;

class Path
{
    /**
     * @var string
     */
    private $value;

    /**
     * Path constructor.
     * @param string $value
     */
    public function __construct($value)
    {
        Assertion::notEmpty($value);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}