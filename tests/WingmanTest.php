<?php

namespace Mleko\Wingman;

use PHPUnit\Framework\TestCase;

class WingmanTest extends TestCase
{
    public function testFormat()
    {
        $fifer = new Wingman();
        $this->assertEquals(
            [
                "name" => "mleko/wingman",
                "license" => "MIT"
            ],
            $fifer->format([
                "license" => "MIT",
                "name" => "mleko/wingman",
            ])
        );
    }
}
