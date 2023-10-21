<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\GeneticAlgorithmService;

class GeneticAlgorithmController extends Controller
{
    public function forceDecimalPlaces(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $number = $request->get('number');
        $decimalPlaces = $request->get('decimalPlaces');

        return response()->json([
            'number' => $service->strictDecimalPlaces($number, $decimalPlaces)
        ]) ;
    }
    public function generateRandomFloat(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $a = (int)$request->get('a');
        $b = (int)$request->get('b');
        $decimalPlaces = (int)$request->get('decimalPlaces');

        return response()->json([
            'randomFloat' => $service->generateRandomNumber($a, $b, $decimalPlaces)
        ]);
    }

    public function countL(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $a = (int)$request->get('a');
        $b = (int)$request->get('b');
        $d = (float)$request->get('d');

        return response()->json([
            'l' => $service->getL($a, $b, $d)
        ]);
    }

    public function countFunctionValue(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $realNumber = (float)$request->get('realNumber');

        return response()->json([
            'functionValue' => $service->functionValue($realNumber)
        ]);
    }

    public function countDecimalPlaces(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        return response()->json([
            'decimalPlaces' => $service->calculateDecimalPlaces((float)$request->get('d'))
        ]);
    }

    public function realToInt(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        return response()->json([
            'intNumber' => $service->calculateRealToInt(
                (float)$request->get('realNumber'),
                (int)$request->get('a'),
                (int)$request->get('b'),
                (int)$request->get('l')
            )
        ]);
    }

    public function intToBin(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        return response()->json([
            'binNumber' => $service->convertIntToBinary(
                (int)$request->get('intNumber'),
                (int)$request->get('l')
            )
        ]);
    }

    public function binToInt(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        return response()->json([
            'intNumber' => $service->convertBinToInt($request->get('binNumber'))
        ]);
    }

    public function intToReal(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        return response()->json([
            'realNumber' => $service->calculateIntToReal(
                (int)$request->get('intNumber'),
                (int)$request->get('a'),
                (int)$request->get('b'),
                (int)$request->get('l'),
                (int)$request->get('decimalPlaces')
            )
        ]);
    }

    public function allConversionsTable(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $a = (int)$request->get('a');
        $b = (int)$request->get('b');
        $d = (float)$request->get('d');
        $n = (int)$request->get('n');

        return response()->json([
           'conversionsTable' => $service->getAllConversionsTable($a, $b, $d, $n)
        ]);
    }
    public function fXTable(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $a = (int)$request->get('a');
        $b = (int)$request->get('b');
        $d = (float)$request->get('d');
        $n = (int)$request->get('n');

        return response()->json([
            'fxTable' => $service->getFxTable($a, $b, $d, $n)
        ]);
    }

    public function fXWithGxTable(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $a = (int)$request->get('a');
        $b = (int)$request->get('b');
        $d = (float)$request->get('d');
        $n = (int)$request->get('n');
        $direction = (string)$request->get('direction');

        $fXTable = $service->getFxTable($a, $b, $d, $n);

        return response()->json([
            'fxWithGxTable' => $service->getFxWithGxTable($fXTable,$d, $direction),
        ]);
    }

}
