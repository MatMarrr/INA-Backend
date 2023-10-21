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
        $requestData = $service->extractCommonParameters($request);

        return response()->json([
           'conversionsTable' => $service->getAllConversionsTable($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n'])
        ]);
    }
    public function fXTable(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);

        return response()->json([
            'fxTable' => $service->getFxTable($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n'])
        ]);
    }

    public function fXGxTable(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);

        $direction = (string)$request->get('direction');

        $fXTable = $service->getFxTable($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n']);

        return response()->json([
            'fxGxTable' => $service->getFxGxTable($fXTable, $requestData['d'], $direction),
        ]);
    }

    public function fXGxPiTable(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);
        $direction = (string)$request->get('direction');

        $fXTable = $service->getFxTable($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n']);
        $fXGxTable = $service->getFxGxTable($fXTable, $requestData['d'], $direction);

        return response()->json([
            'fxGxPiTable' => $service->getFxGxPiTable($fXGxTable),
        ]);
    }

    public function fxGxPiQiTable(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);
        $direction = (string)$request->get('direction');

        $fXTable = $service->getFxTable($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n']);
        $fXGxTable = $service->getFxGxTable($fXTable, $requestData['d'], $direction);
        $fXGxPiTable = $service->getFxGxPiTable($fXGxTable);
        return response()->json([
            'fxGxPiQiTable' => $service->getFxGxPiQiTable($fXGxPiTable),
        ]);
    }

    public function fxGxPiQiRTable(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);
        $direction = (string)$request->get('direction');

        $fXTable = $service->getFxTable($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n']);
        $fXGxTable = $service->getFxGxTable($fXTable, $requestData['d'], $direction);
        $fXGxPiTable = $service->getFxGxPiTable($fXGxTable);
        $fXGxPiQiTable = $service->getFxGxPiQiTable($fXGxPiTable);
        return response()->json([
            'fxGxPiQiRTable' => $service->getFxGxPiQiRTable($fXGxPiQiTable, $request['d']),
        ]);
    }

    public function fxGxPiQiRXTable(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);
        $direction = (string)$request->get('direction');

        $fXTable = $service->getFxTable($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n']);
        $fXGxTable = $service->getFxGxTable($fXTable, $requestData['d'], $direction);
        $fXGxPiTable = $service->getFxGxPiTable($fXGxTable);
        $fXGxPiQiTable = $service->getFxGxPiQiTable($fXGxPiTable);
        $fxGxPiQiRTable = $service->getFxGxPiQiRTable($fXGxPiQiTable, $request['d']);

        return response()->json([
            'fxGxPiQiRFxTable' => $service->getFxGxPiQiRXTable($fxGxPiQiRTable),
        ]);
    }
}
