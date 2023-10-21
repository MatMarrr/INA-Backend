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
    public function generateRandomNumber(int $a, int $b, int $decimalPlaces): float
    {
        $randomFloat = $a + lcg_value() * ($b - $a);
        return round($randomFloat, $decimalPlaces);
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
    public function getFxTable(int $a, int $b, float $d, int $n): array
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
    public function getFxGxTable(array $fXTable, float $d, string $direction): array
    {
        $maxVal = -PHP_FLOAT_MAX;
        $minVal = PHP_FLOAT_MAX;

        foreach ($fXTable as $row) {
            $fx = $row[2];

            if ($fx > $maxVal) {
                $maxVal = $fx;
            }

            if ($fx < $minVal) {
                $minVal = $fx;
            }
        }

        foreach ($fXTable as &$row) {
            if ($direction === "max") {
                $row[] = ((float)$row[1] - $minVal) + $d;
            } else if ($direction === "min") {
                $row[] = -1 * ((float)$row[1] - $maxVal) + $d;
            }
        }

        return $fXTable;
    }

    public function getFxGxPiTable($fXGxTable): array
    {
        $sumGi = 0;

        foreach ($fXGxTable as $row) {
            $sumGi += $row[3];
        }

        foreach ($fXGxTable as &$row) {
            $row[] = $row[3] / $sumGi;
        }

        return $fXGxTable;
    }

}
