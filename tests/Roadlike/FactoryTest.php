<?php

namespace App\Roadlike;

use App\Roadlike\Factory;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;

/**
 * Testsuit for Factory
 */
final class FactoryTest extends TestCase
{
    /**
     * Tests building a manager
     */
    public function testBuildManager(): void
    {
        $factory = new Factory();
        $challenger = $this->createStub("App\Roadlike\Challenger");
        $manager = $factory->buildManager($challenger);

        $this->assertEquals($challenger, $manager->getChallenger());
    }

    // /**
    //  * Test randomshape static method
    //  */
    // public function testRandomRoadShape(): void
    // {
    //     $shape = Factory::randomRoadShape();
    //     $this->assertContainsOnly("int", $shape);
    //     $this->assertArrayHasKey("length", $shape);
    //     $this->assertArrayHasKey("obstacles", $shape);
    //     $this->assertIsInt($shape["length"]);
    //     $this->assertIsInt($shape["obstacles"]);
    // }
}