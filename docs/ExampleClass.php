<?php

namespace Mogic\Example;

/**
 * This class gives an example how the code should look like.
 *
 * @author Christian Weiske <weiske@mogic.com>
 */
class ExampleClass
{
    /**
     * List of keys
     *
     * @var array
     */
    protected $keys;

    /**
     * Nobody may access those secrets.
     *
     * @var string
     */
    private $secret;

    /**
     * Set the parent
     *
     * @param object $parent Optional parent object
     */
    public function __construct(ExampleClass $parent = null)
    {
        parent::__construct();
    }

    /**
     * Does something with the input variables
     *
     * @param int $numberOne First number
     * @param int $numberTwo Second number
     *
     * @return int Sum of numbers
     */
    public function doSomething($numberOne, $numberTwo)
    {
        if (!is_int($numberOne)) {
            throw new \Exception('Number one is not an integer');
        }

        switch (gettype($numberTwo)) {
            case 'object':
                throw new \Exception('Number two is an object');
            default:
                //all ok
        }

        return $numberOne + $numberTwo;
    }

    /**
     * Both styles are allowed
     */
    public function lineBreaks(): void
    {
        $this->object->thisIsAMethodWithAVeryLongNameThatMakesUsRequireMultipleLines(
            $param, 23, false
        );
        $this->object->thisIsAMethodWithAVeryLongNameThatMakesUsRequireMultipleLines(
            $param,
            23,
            false
        );
    }
}
