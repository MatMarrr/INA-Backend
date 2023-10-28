<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\GeneticAlgorithmService;

class GetTableLpToParentsTest extends TestCase
{
    /** @test */
    public function it_adds_tenth_column_correctly_based_on_arguments()
    {
        $service = $this->getMockBuilder(GeneticAlgorithmService::class)
            ->onlyMethods(['generateR'])
            ->getMock();

        $service->method('generateR')->willReturn(0.4);

        $tableLpToXbin = [
            [0, 0, 0, 0, 0, 0, 0, 0, '00010000101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011000101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011100101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011110101000'],
        ];

        $pk = 0.5;

        $result = $service->getTableLpToParents($tableLpToXbin, $pk);

        $expectedResult = [
            [0, 0, 0, 0, 0, 0, 0, 0, '00010000101000', '00010000101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011000101000', '00011000101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011100101000', '00011100101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011110101000', '00011110101000'],
        ];

        $this->assertEquals($expectedResult, $result);
    }
}
