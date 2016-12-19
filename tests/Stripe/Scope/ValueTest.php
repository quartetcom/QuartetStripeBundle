<?php


namespace Quartet\Stripe\Scope;


use Quartet\Stripe\Scope;

class ValueTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $value = new Value\Failure(new Scope(), new \Exception('foo bar'));

        $value = $value
            ->recover(function (\Exception $exception) {
                return $exception->getMessage().' recovered';
            })
            ->map(function ($message) {
                return $message.' mapped';
            });

        $this->assertEquals('foo bar recovered mapped', $value->get());
    }
}
