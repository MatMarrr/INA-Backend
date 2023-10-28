<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\GeneticAlgorithmService;

class MutateBinTest extends TestCase
{
    /** @test */
    public function it_mutates_bin_correctly_when_r_is_less_than_one_and_pm_is_one()
    {
        $service = $this->getMockBuilder(GeneticAlgorithmService::class)
            ->onlyMethods(['generateR'])
            ->getMock();

        $service->method('generateR')
            ->willReturn(0.5); // zwraca wartość mniejszą od 1

        $xBin = "010101";
        $pm = 1;

        $result = $service->mutateBin($xBin, $pm);

        $expectedResult = [
            "xBin" => "101010",
            "mutatedBits" => "0,1,2,3,4,5",
        ];

        $this->assertEquals($expectedResult, $result);
    }
}
