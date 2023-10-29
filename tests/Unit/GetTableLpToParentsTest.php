<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\GeneticAlgorithmService;

class GetTableLpToParentsTest extends TestCase
{
    /** @test */
    public function is_draws_parents_when_two_or_more_parents_will_be_drawn()
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

    /** @test */
    public function is_draws_parents_when_no_parents_will_be_drawn()
    {
        $service = $this->getMockBuilder(GeneticAlgorithmService::class)
            ->onlyMethods(['generateR'])
            ->getMock();

        $service->method('generateR')->willReturn(0.6);

        $tableLpToXbin = [
            [0, 0, 0, 0, 0, 0, 0, 0, '00010000101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011000101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011100101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011110101000'],
        ];

        $pk = 0.5;

        $result = $service->getTableLpToParents($tableLpToXbin, $pk);

        $expectedResult = [
            [0, 0, 0, 0, 0, 0, 0, 0, '00010000101000', null],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011000101000', null],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011100101000', null],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011110101000', null],
        ];

        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function is_draws_parents_when_one_parent_is_drawn_and_second_is_random()
    {
        $service = $this->getMockBuilder(GeneticAlgorithmService::class)
            ->onlyMethods(['generateR', 'getRandomIndex'])
            ->getMock();

        $service->method('generateR')->willReturnOnConsecutiveCalls(0.55, 0.6, 0.4, 0.8, 0.9);
        $service->method('getRandomIndex')->willReturn(1);

        $tableLpToXbin = [
            [0, 0, 0, 0, 0, 0, 0, 0, '00010000101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011000101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011100101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011110101000'],
        ];

        $pk = 0.5;

        $result = $service->getTableLpToParents($tableLpToXbin, $pk);

        $expectedResult = [
            [0, 0, 0, 0, 0, 0, 0, 0, '00010000101000', null],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011000101000', '00011000101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011100101000', '00011100101000'],
            [0, 0, 0, 0, 0, 0, 0, 0, '00011110101000', null],
        ];

        $this->assertEquals($expectedResult, $result);
    }
}
