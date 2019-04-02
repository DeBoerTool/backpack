<?php

namespace Dbt\Backpack\Tests;

use Dbt\Backpack\Exceptions\MissingKey;
use Dbt\Backpack\Exceptions\UndefinedProperty;
use Dbt\Backpack\Exceptions\WrongType;
use Dbt\Backpack\Tests\Fixtures\Fixture;
use Dbt\Backpack\Types;
use stdClass;

class BackpackTest extends TestCase
{
    /** @test */
    public function getting_non_backpack_properties ()
    {
        $fixture = new Fixture();

        $this->assertSame('test property', $fixture->prop);
    }

    /** @test */
    public function setting_non_backpack_properties ()
    {
        $fixture = new Fixture();

        $fixture->prop = 'it is set';

        $this->assertSame('it is set', $fixture->prop);
    }

    /** @test */
    public function setting_and_getting ()
    {
        $string = 'some string';
        $array = ['test array'];
        $fixture = new Fixture();

        $fixture->test1 = $string;
        $fixture->test2 = $array;

        $this->assertSame($string, $fixture->test1);
        $this->assertSame($array, $fixture->test2);
    }

    /** @test */
    public function failing_to_get ()
    {
        $this->expectException(UndefinedProperty::class);
        $fixture = new Fixture();

        $fixture->thisShouldFail;
    }

    /**
     * @test
     * @dataProvider passingHydrationData
     */
    public function hydrating ($payload, $test1, $test2)
    {
        $fixture = new Fixture();

        $fixture->hydrate($payload);

        $this->assertSame($test1, $fixture->test1);
        $this->assertSame($test2, $fixture->test2);
    }

    /**
     * @test
     * @dataProvider failingHydrationData
     */
    public function failing_to_hydrate ($exception, $payload)
    {
        $this->expectException($exception);

        $fixture = new Fixture();
        $fixture->hydrate($payload);
    }

    public function passingHydrationData (): array
    {
        $string = 'some string';
        $array = ['test array'];

        return [
            'payload is associative' => [
                ['test1' => $string, 'test2' => $array],
                $string,
                $array,
            ],
            'payload is non-associative' => [
                [$string, $array],
                $string,
                $array,
            ],
            'payload has extra values' => [
                [$string, $array, 'this is an extra value'],
                $string,
                $array,
            ],
        ];
    }

    public function failingHydrationData (): array
    {
        $string = 'some string';
        $array = ['test array'];
        $class = new stdClass();

        return [
            'has the wrong types (associative)' => [
                WrongType::class,
                ['test1' => $class, 'test2' => $array],
            ],
            'has the wrong types (non-associative)' => [
                WrongType::class,
                [$string, $class],
            ],
            'is missing an value (associative)' => [
                MissingKey::class,
                ['test1' => $string],
            ],
            'is missing a value (non-associative)' => [
                MissingKey::class,
                [$string],
            ],
        ];
    }


}
