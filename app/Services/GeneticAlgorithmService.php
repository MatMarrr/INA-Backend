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
        $decimalAsString = (string) $d;
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
        return (int) ceil(log((($b - $a) / $d) + 1, 2));
    }
    public function calculateRealToInt(float $realNumber, int $a, int $b, int $l): int
    {
        return (int) floor((1 / (float)($b - $a)) * ($realNumber - $a) * (pow(2, $l) - 1));
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

        for($i = 1; $i <= $n; $i++){
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
                $row[] = (-1 * ((float)$row[2] - $maxVal) )+ $d;
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
        foreach($tableLpToQi as &$row)
        {
            $r = $this->generateR();
            $row[] = $r;
        }
        return $tableLpToQi;
    }

    public function getTableLpToX(array $tableLpToR): array
    {

        foreach($tableLpToR as &$row)
        {
            $r = $row[6];
            $chosenX = false;
            for($i = 0; $i < count($tableLpToR); $i++){

                $xReal = $tableLpToR[$i][1];
                $q = $tableLpToR[$i][5];

                if($i == 0){
                    $prevQ = 0;
                }else{
                    $prevQ = $tableLpToR[$i-1][5];
                }

                if($prevQ < $r && $r <= $q){
                    $row[] = $xReal;
                    $chosenX = true;
                }
            }
            if(!$chosenX){
                $row[] = null;
            }

        }

        return $tableLpToR;
    }

    public function getTableLpToXbin(array $tableLpToXreal, $a, $b, $l): array
    {
        foreach ($tableLpToXreal as &$row){

            $xReal = (float)$row[7];

            if($xReal == null){
                $row[] = null;
            }else{
                $xInt = $this->calculateRealToInt($xReal, $a, $b, $l);
                $row[] = $this->convertIntToBinary($xInt, $l);
            }
        }
        return $tableLpToXreal;
    }

    public function getTableLpToParents(array $tableLpToXreal, $pk): array
    {
        $parentsIndexes = array();
        do {

            foreach ($tableLpToXreal as $index => &$row) {

                $r = $row[6];
                $xBin = $row[8];

                if ($pk <= $r && !in_array($index, $parentsIndexes)) {
                    $row[] = $xBin;
                    $parentsIndexes[] = $index;
                } else {
                    $row[] = null;
                }
            }

        }while(count($parentsIndexes) == 1);
            return $tableLpToXreal;
    }

    public function getTableLpToPc(array $tableLpToParents, int $l): array
    {
        foreach($tableLpToParents as &$row){

            $pk = rand(0, $l-2);
            $row[] = $pk;
        }

        return $tableLpToParents;
    }
}
