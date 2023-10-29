<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\GeneticAlgorithmService;

class GetTableLpToPcTest extends TestCase
{
    /** @test */
    public function it_adds_tenth_column_and_generates_pairs_correctly_when_even_number_of_parents()
    {
        $service = $this->getMockBuilder(GeneticAlgorithmService::class)
            ->onlyMethods(['generatePc'])
            ->getMock();

        $service->method('generatePc')
            ->willReturnOnConsecutiveCalls(1, 2);

        $tableLpToParents = [
            [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000001'],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000011'],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, null],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000111'],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, '0001111'],
        ];

        $l = 7;

        $result = $service->getTableLpToPc($tableLpToParents, $l);

        $expectedResult = [
            'tableLpToPk' => [
                [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000001', 1],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000011', 1],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, null, null],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000111', 2],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, '0001111', 2],
            ],
            'pairs' => [
                [
                    'parent_1' => ['index' => 0, 'value' => '0000001'],
                    'parent_2' => ['index' => 1, 'value' => '0000011'],
                    'pc' => 1,
                ],
                [
                    'parent_1' => ['index' => 3, 'value' => '0000111'],
                    'parent_2' => ['index' => 4, 'value' => '0001111'],
                    'pc' => 2,
                ]
            ],
        ];

        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function it_adds_tenth_column_and_generates_pairs_correctly_when_odd_number_of_parents()
    {
        $service = $this->getMockBuilder(GeneticAlgorithmService::class)
            ->onlyMethods(['generatePc'])
            ->getMock();

        $service->method('generatePc')
            ->willReturnOnConsecutiveCalls(1, 2, 3);

        $tableLpToParents = [
            [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000001'],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000011'],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, null],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000111'],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, '0001111'],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, '0011111'],
        ];

        $l = 7;

        $result = $service->getTableLpToPc($tableLpToParents, $l);

        $expectedResult = [
            'tableLpToPk' => [
                [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000001', 1],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000011', 1],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, null, null],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, '0000111', 2],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, '0001111', 2],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, '0011111', 3],
            ],
            'pairs' => [
                [
                    'parent_1' => ['index' => 0, 'value' => '0000001'],
                    'parent_2' => ['index' => 1, 'value' => '0000011'],
                    'pc' => 1,
                ],
                [
                    'parent_1' => ['index' => 3, 'value' => '0000111'],
                    'parent_2' => ['index' => 4, 'value' => '0001111'],
                    'pc' => 2,
                ],
                [
                    'parent_1' => ['index' => 5, 'value' => '0011111'],
                    'parent_2' => ['index' => 0, 'value' => '0000001'],
                    'pc' => 3,
                ]
            ],
        ];

        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function it_adds_tenth_column_and_generates_pairs_correctly_when_no_parents()
    {
        $service = $this->getMockBuilder(GeneticAlgorithmService::class)
            ->onlyMethods(['generatePc'])
            ->getMock();

        $service->method('generatePc')
            ->willReturnOnConsecutiveCalls(1, 2, 3);

        $tableLpToParents = [
            [0, 0, 0, 0, 0, 0, 0, 0, 0, null],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, null],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, null],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, null],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, null],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, null],
        ];

        $l = 7;

        $result = $service->getTableLpToPc($tableLpToParents, $l);

        $expectedResult = [
            'tableLpToPk' => [
                [0, 0, 0, 0, 0, 0, 0, 0, 0, null, null],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, null, null],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, null, null],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, null, null],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, null, null],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, null, null],
            ],
            'pairs' => [],
        ];

        $this->assertEquals($expectedResult, $result);
    }
}
