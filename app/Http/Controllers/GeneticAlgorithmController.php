<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeneticAlgorithmController extends Controller
{
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
}
