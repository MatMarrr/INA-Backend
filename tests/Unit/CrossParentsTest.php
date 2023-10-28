<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\GeneticAlgorithmService;
class CrossParentsTest extends TestCase
{
    /** @test */
    public function it_crosses_parents_correctly()
    {
        $service = new GeneticAlgorithmService();

        $parents = [
            'pc' => 2,
            'parent_1' => ['value' => '111000', 'index' => 1],
            'parent_2' => ['value' => '010101', 'index' => 2]
        ];

        $result = $service->crossParents($parents);

        $expectedResult = [
            "d1" => "111101",
            "d1Index" => 1,
            "d2" => "010000",
            "d2Index" => 2,
        ];

        $this->assertEquals($expectedResult, $result);
    }
}
