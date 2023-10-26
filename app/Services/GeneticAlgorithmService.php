<?php

namespace App\Services;

use Illuminate\Http\Request;

class GeneticAlgorithmService
{
    public function extractCommonParameters(Request $request): array
    {
        return [
            'a' => (int)$request->get('a'),
            'b' => (int)$request->get('b'),
            'd' => (float)$request->get('d'),
            'n' => (int)$request->get('n'),
        ];
    }

    public function strictDecimalPlaces($number, $decimalPlaces): string
    {
        return sprintf("%." . $decimalPlaces . "f", $number);
    }

    public function functionValue(float $realNumber): float
    {
        $fractionalPart = (float)($realNumber - intval($realNumber));
        $cosPart = cos(20 * pi() * $realNumber);
        $sinPart = sin($realNumber);

        return $fractionalPart * ($cosPart - $sinPart);
    }

    public function calculateDecimalPlaces(float $d): int
    {
        $decimalAsString = (string)$d;
        return match ($decimalAsString) {
            "0.1" => 1,
            "0.01" => 2,
            "0.001" => 3,
            "0.0001" => 4,
            default => 0,
        };
    }

    public function generateRandomNumber(float $a, float $b, int $decimalPlaces): float
    {
        $randomInt = mt_rand(0, pow(10, $decimalPlaces));
        $scaledValue = $randomInt / pow(10, $decimalPlaces);
        $randomFloat = $a + $scaledValue * ($b - $a);

        return round($randomFloat, $decimalPlaces);
    }

    public function generateR(): float
    {
        return mt_rand(0, mt_getrandmax()) / mt_getrandmax();
    }

    public function getL(int $a, int $b, float $d): int
    {
        return (int)ceil(log((($b - $a) / $d) + 1, 2));
    }

    public function calculateRealToInt(float $realNumber, int $a, int $b, int $l): int
    {
        return (int)floor((1 / (float)($b - $a)) * ($realNumber - $a) * (pow(2, $l) - 1));
    }

    public function convertIntToBinary(int $intNumber, int $l): string
    {
        return str_pad(decbin($intNumber), $l, '0', STR_PAD_LEFT);
    }

    public function convertBinToInt(string $binaryNumber): int
    {
        return bindec($binaryNumber);
    }

    public function calculateIntToReal(int $intNumber, int $a, int $b, int $l, int $decimalPlaces): float
    {
        return round(((float)$intNumber * (float)($b - $a)) / (pow(2, $l) - 1) + $a, $decimalPlaces);
    }

    public function getAllConversionsTable(int $a, int $b, float $d, int $n): array
    {
        $resultArray = array();

        $l = $this->getL($a, $b, $d);
        $decimalPlaces = $this->calculateDecimalPlaces($d);

        for ($i = 1; $i <= $n; $i++) {
            $firstX = $this->generateRandomNumber($a, $b, $decimalPlaces);
            $secondX = $this->calculateRealToInt($firstX, $a, $b, $l);
            $thirdX = $this->convertIntToBinary($secondX, $l);
            $fourthX = $this->convertBinToInt($thirdX);
            $fifthX = $this->calculateIntToReal($fourthX, $a, $b, $l, $decimalPlaces);
            $functionX = $this->functionValue($fifthX);
            $resultArray[] = [$i, $this->strictDecimalPlaces($firstX, $decimalPlaces), $secondX, $thirdX, $fourthX, $this->strictDecimalPlaces($fifthX, $decimalPlaces), $functionX];
        }

        return $resultArray;
    }

    public function getTableLpToFx(int $a, int $b, float $d, int $n): array
    {
        $resultArray = array();
        $decimalPlaces = $this->calculateDecimalPlaces($d);

        for ($i = 1; $i <= $n; $i++) {
            $xReal = $this->generateRandomNumber($a, $b, $decimalPlaces);
            $functionX = $this->functionValue($xReal);
            $resultArray[] = [$i, $this->strictDecimalPlaces($xReal, $decimalPlaces), $functionX];
        }

        return $resultArray;
    }

    public function getTableLpToGx(array $tableLpToFx, float $d, string $direction): array
    {
        $maxVal = -PHP_FLOAT_MAX;
        $minVal = PHP_FLOAT_MAX;

        foreach ($tableLpToFx as $row) {
            $fx = (float)$row[2];

            if ($fx > $maxVal) {
                $maxVal = $fx;
            }

            if ($fx < $minVal) {
                $minVal = $fx;
            }
        }

        foreach ($tableLpToFx as &$row) {
            if ($direction === "max") {
                $row[] = ((float)$row[2] - $minVal) + $d;
            } else if ($direction === "min") {
                $row[] = (-1 * ((float)$row[2] - $maxVal)) + $d;
            }
        }

        return $tableLpToFx;
    }

    public function getTableLpToPi(array $tableLpToGx): array
    {
        $sumGi = 0;

        foreach ($tableLpToGx as $row) {
            $sumGi += (float)$row[3];
        }

        foreach ($tableLpToGx as &$row) {
            $row[] = (float)$row[3] / $sumGi;
        }

        return $tableLpToGx;
    }

    public function getTableLpToQi(array $tableLpToPi): array
    {
        $qiSum = 0;

        foreach ($tableLpToPi as &$row) {
            (float)$pi = $row[4];

            $qiSum += $pi;
            $row[] = (float)$qiSum;
        }

        return $tableLpToPi;
    }

    public function getTableLpToR(array $tableLpToQi): array
    {
        foreach ($tableLpToQi as &$row) {
            $r = $this->generateR();
            $row[] = $r;
        }
        return $tableLpToQi;
    }

    public function getTableLpToX(array $tableLpToR): array
    {

        foreach ($tableLpToR as &$row) {
            $r = $row[6];
            $chosenX = false;
            for ($i = 0; $i < count($tableLpToR); $i++) {

                $xReal = $tableLpToR[$i][1];
                $q = $tableLpToR[$i][5];

                if ($i == 0) {
                    $prevQ = 0;
                } else {
                    $prevQ = $tableLpToR[$i - 1][5];
                }

                if ($prevQ < $r && $r <= $q) {
                    $row[] = $xReal;
                    $chosenX = true;
                }
            }
            if (!$chosenX) {
                $row[] = null;
            }

        }

        return $tableLpToR;
    }

    public function getTableLpToXbin(array $tableLpToXreal, $a, $b, $l): array
    {
        foreach ($tableLpToXreal as &$row) {

            $xReal = (float)$row[7];

            if ($xReal == null) {
                $row[] = null;
            } else {
                $xInt = $this->calculateRealToInt($xReal, $a, $b, $l);
                $row[] = $this->convertIntToBinary($xInt, $l);
            }
        }
        return $tableLpToXreal;
    }

    public function getTableLpToParents(array $tableLpToXreal, $pk): array
    {
        $parentsIndexes = array();
        $emptyPatentIndexes = array();

        foreach ($tableLpToXreal as $index => &$row) {

            $r = $row[6];
            $xBin = $row[8];

            if ($pk <= $r && !in_array($index, $parentsIndexes)) {
                $row[] = $xBin;
                $parentsIndexes[] = $index;


            } else {
                $row[] = null;
                $emptyPatentIndexes[] = $index;
            }
        }

        if (count($parentsIndexes) == 1) {
            $randomParentTabIndex = array_rand($emptyPatentIndexes);
            $randomParentTabIndexValue = $emptyPatentIndexes[$randomParentTabIndex];
            $tableLpToXreal[$randomParentTabIndexValue][9] = $tableLpToXreal[$randomParentTabIndexValue][8];
        }

        return $tableLpToXreal;
    }

    public function getTableLpToPc(array $tableLpToParents, int $l): array
    {

        $parentsColumn = array_column($tableLpToParents, 9);

        $parents = array_filter($parentsColumn, function ($value) {
            return !is_null($value);
        });

        if (empty($parents)) {
            return array(
                'tableLpToPk' => $tableLpToParents,
            );
        }

        $parentsData = array();
        $parentsKeys = array_keys($parents);

        for ($i = 0; $i < count($parentsKeys); $i += 2) {
            $indexParent1 = $parentsKeys[$i];
            $indexParent2 = isset($parentsKeys[$i + 1]) ? $parentsKeys[$i + 1] : $parentsKeys[0];

            $parent1 = $parents[$indexParent1];
            $parent2 = $parents[$indexParent2];
            $pk = rand(0, $l - 2);

            $parentsData[] = array(
                'parent_1' => array('index' => $indexParent1, 'value' => $parent1),
                'parent_2' => array('index' => $indexParent2, 'value' => $parent2),
                'pk' => $pk,
            );
        }

        foreach ($parentsData as &$parentData) {
            $pk = $parentData['pk'];
            if (!isset($tableLpToParents[$parentData['parent_1']['index']][10])) {
                $tableLpToParents[$parentData['parent_1']['index']][] = $pk;
            }

            if (!isset($tableLpToParents[$parentData['parent_2']['index']][10])) {
                $tableLpToParents[$parentData['parent_2']['index']][] = $pk;
            }
        }

        foreach ($tableLpToParents as &$row) {
            if (!isset($row[10])) {
                $row[] = null;
            }
        }

        return array(
            'tableLpToPk' => $tableLpToParents,
            'pairs' => $parentsData,
        );
    }

    public function crossParents(array $parents): array
    {
        $pk = $parents['pk'];
        $firstParentHead = substr($parents['parent_1']['value'], 0, $pk + 1);
        $firstParentTail = substr($parents['parent_1']['value'], $pk + 1);

        $secondParentHead = substr($parents['parent_2']['value'], 0, $pk + 1);
        $secondParentTail = substr($parents['parent_2']['value'], $pk + 1);

        $d1 = $firstParentHead . $secondParentTail;
        $d2 = $secondParentHead . $firstParentTail;

        return array(
            "d1" => $d1,
            "d1Index" => $parents['parent_1']['index'],
            "d2" => $d2,
            "d2Index" => $parents['parent_2']['index'],
        );
    }

    public function getTableLpToCross(array $tableLpToPk, array $pairs): array
    {
        foreach ($pairs as $pair) {
            $crossedData = $this->crossParents($pair);

            if (!isset($tableLpToPk[$crossedData['d1Index']][11])) {
                $tableLpToPk[$crossedData['d1Index']][] = $crossedData['d1'];
            }

            if (!isset($tableLpToPk[$crossedData['d2Index']][11])) {
                $tableLpToPk[$crossedData['d2Index']][] = $crossedData['d2'];
            }
        }

        foreach ($tableLpToPk as &$row) {
            if (!isset($row[11])) {
                $row[] = $row[8];
            }
        }

        return $tableLpToPk;
    }
}
