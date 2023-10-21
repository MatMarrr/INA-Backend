<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeneticAlgorithmController extends Controller
{
    public function forceDecimalPlaces(Request $request): JsonResponse
    {
        $number = $request->get('number');
        $decimalPlaces = $request->get('decimalPlaces');

        return response()->json([
            'number' => $this->strictDecimalPlaces($number, $decimalPlaces)
        ]) ;
    }
    public function generateRandomFloat(Request $request): JsonResponse
    {
        $a = (int)$request->get('a');
        $b = (int)$request->get('b');
        $decimalPlaces = (int)$request->get('decimalPlaces');

        return response()->json([
            'randomFloat' => $this->generateRandomNumber($a, $b, $decimalPlaces)
        ]);
    }

    public function countL(Request $request): JsonResponse
    {
        $a = (int)$request->get('a');
        $b = (int)$request->get('b');
        $d = (float)$request->get('d');

        return response()->json([
            'l' => $this->getL($a, $b, $d)
        ]);
    }

    public function countFunctionValue(Request $request): JsonResponse
    {
        $realNumber = (float)$request->get('realNumber');

        return response()->json([
            'functionValue' => $this->functionValue($realNumber)
        ]);
    }

    public function countDecimalPlaces(Request $request): JsonResponse
    {
        return response()->json([
            'decimalPlaces' => $this->calculateDecimalPlaces((float)$request->get('d'))
        ]);
    }

    public function realToInt(Request $request): JsonResponse
    {
        return response()->json([
            'intNumber' => $this->calculateRealToInt(
                (float)$request->get('realNumber'),
                (int)$request->get('a'),
                (int)$request->get('b'),
                (int)$request->get('l')
            )
        ]);
    }

    public function intToBin(Request $request): JsonResponse
    {
        return response()->json([
            'binNumber' => $this->convertIntToBinary(
                (int)$request->get('intNumber'),
                (int)$request->get('l')
            )
        ]);
    }

    public function binToInt(Request $request): JsonResponse
    {
        return response()->json([
            'intNumber' => $this->convertBinToInt($request->get('binNumber'))
        ]);
    }

    public function intToReal(Request $request): JsonResponse
    {
        return response()->json([
            'realNumber' => $this->calculateIntToReal(
                (int)$request->get('intNumber'),
                (int)$request->get('a'),
                (int)$request->get('b'),
                (int)$request->get('l'),
                (int)$request->get('decimalPlaces')
            )
        ]);
    }

    public function allConversionsTable(Request $request): JsonResponse
    {
        $a = (int)$request->get('a');
        $b = (int)$request->get('b');
        $d = (float)$request->get('d');
        $n = (int)$request->get('n');

        return response()->json([
           'conversionsTable' => $this->getAllConversionsTable($a, $b, $d, $n)
        ]);
    }
    private function strictDecimalPlaces($number, $decimalPlaces): string
    {
        return sprintf("%." . $decimalPlaces . "f", $number);
    }
    private function functionValue(float $realNumber): float
    {
        $fractionalPart = (float)($realNumber - intval($realNumber));
        $cosPart = cos(20 * pi() * $realNumber);
        $sinPart = sin($realNumber);

        return $fractionalPart * ($cosPart - $sinPart);
    }

    private function calculateDecimalPlaces(float $d): int
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

    private function generateRandomNumber(int $a, int $b, int $decimalPlaces): float
    {
        $randomFloat = $a + lcg_value() * ($b - $a);
        return round($randomFloat, $decimalPlaces);
    }

    private function getL(int $a, int $b, float $d): int
    {
        return (int) ceil(log((($b - $a) / $d) + 1, 2));
    }

    private function calculateRealToInt(float $realNumber, int $a, int $b, int $l): int
    {
        return (int) floor((1 / (float)($b - $a)) * ($realNumber - $a) * (pow(2, $l) - 1));
    }

    private function convertIntToBinary(int $intNumber, int $l): string
    {
        return str_pad(decbin($intNumber), $l, '0', STR_PAD_LEFT);
    }

    private function convertBinToInt(string $binaryNumber): int
    {
        return bindec($binaryNumber);
    }

    private function calculateIntToReal(int $intNumber, int $a, int $b, int $l, int $decimalPlaces): float
    {
        return round(((float)$intNumber * (float)($b - $a)) / (pow(2, $l) - 1) + $a, $decimalPlaces);
    }

    private function getAllConversionsTable(int $a, int $b, float $d, int $n): array
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

}
