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
    public function tableLpToFx(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);

        return response()->json([
            'tableLpToFx' => $service->getTableLpToFx($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n'])
        ]);
    }

    public function tableLpToGx(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);

        $direction = (string)$request->get('direction');

        $tableLpToFx = $service->getTableLpToFx($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n']);

        return response()->json([
            'tableLpToGx' => $service->getTableLpToGx($tableLpToFx, $requestData['d'], $direction),
        ]);
    }

    public function tableLpToPi(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);
        $direction = (string)$request->get('direction');

        $tableLpToFx = $service->getTableLpToFx($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n']);
        $tableLpToGx = $service->getTableLpToGx($tableLpToFx, $requestData['d'], $direction);

        return response()->json([
            'tableLpToPi' => $service->getTableLpToPi($tableLpToGx),
        ]);
    }

    public function tableLpToQi(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);
        $direction = (string)$request->get('direction');

        $tableLpToFx = $service->getTableLpToFx($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n']);
        $tableLpToGx = $service->getTableLpToGx($tableLpToFx, $requestData['d'], $direction);
        $tableLpToPi = $service->getTableLpToPi($tableLpToGx);

        return response()->json([
            'tableLpToQi' => $service->getTableLpToQi($tableLpToPi),
        ]);
    }

    public function tableLpToR(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);
        $direction = (string)$request->get('direction');

        $tableLpToFx = $service->getTableLpToFx($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n']);
        $tableLpToGx = $service->getTableLpToGx($tableLpToFx, $requestData['d'], $direction);
        $tableLpToPi = $service->getTableLpToPi($tableLpToGx);
        $tableLpToQi = $service->getTableLpToQi($tableLpToPi);

        return response()->json([
            'tableLpToR' => $service->getTableLpToR($tableLpToQi, $request['d']),
        ]);
    }

    public function tableLpToXreal(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);
        $direction = (string)$request->get('direction');

        $tableLpToFx = $service->getTableLpToFx($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n']);
        $tableLpToGx = $service->getTableLpToGx($tableLpToFx, $requestData['d'], $direction);
        $tableLpToPi = $service->getTableLpToPi($tableLpToGx);
        $tableLpToQi = $service->getTableLpToQi($tableLpToPi);
        $tableLpToR = $service->getTableLpToR($tableLpToQi, $request['d']);

        return response()->json([
            'tableLpToXreal' => $service->getTableLpToX($tableLpToR),
        ]);
    }

    public function tableLpToXbin(Request $request, GeneticAlgorithmService $service): JsonResponse
    {
        $requestData = $service->extractCommonParameters($request);

        $direction = (string)$request->get('direction');
        $l = $service->getL($requestData['a'], $requestData['b'], $requestData['d']);

        $tableLpToFx = $service->getTableLpToFx($requestData['a'], $requestData['b'], $requestData['d'], $requestData['n']);
        $tableLpToGx = $service->getTableLpToGx($tableLpToFx, $requestData['d'], $direction);
        $tableLpToPi = $service->getTableLpToPi($tableLpToGx);
        $tableLpToQi = $service->getTableLpToQi($tableLpToPi);
        $tableLpToR = $service->getTableLpToR($tableLpToQi, $request['d']);
        $tableLpToXreal = $service->getTableLpToX($tableLpToR);
        return response()->json([
            'tableLpToXbin' => $service->getTableLpToXbin($tableLpToXreal, $requestData['a'], $requestData['b'],  $l),
        ]);
    }
}
