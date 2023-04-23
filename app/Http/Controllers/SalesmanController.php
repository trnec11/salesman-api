<?php

namespace App\Http\Controllers;

use App\Exceptions\AlreadyExistsException;
use App\Exceptions\ForbiddenErrorException;
use App\Exceptions\InputDataBadFormatErrorException;
use App\Exceptions\InputDataOutOfRangeErrorException;
use App\Exceptions\NotFoundException;
use App\Http\Resources\Salesman;
use App\Http\Resources\SalesmanCollection;
use App\Services\SalesmanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class SalesmanController
 * @package App\Http\Controllers
 */
class SalesmanController extends Controller
{
    /**
     * @param SalesmanService $salesmanService
     */
    public function __construct(private readonly SalesmanService $salesmanService) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $salesman = $this->salesmanService->list($request->all());
            return response()->json(new SalesmanCollection($salesman), Response::HTTP_OK);
        } catch (ForbiddenErrorException|InputDataBadFormatErrorException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $salesman = $this->salesmanService->create($request->all());
            return response()->json(new Salesman($salesman), Response::HTTP_CREATED);
        } catch (AlreadyExistsException|ForbiddenErrorException|InputDataBadFormatErrorException|InputDataOutOfRangeErrorException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $salesman = $this->salesmanService->update($request->all());
            return response()->json(new Salesman($salesman), Response::HTTP_OK);
        } catch (ForbiddenErrorException|InputDataBadFormatErrorException|InputDataOutOfRangeErrorException|NotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $this->salesmanService->delete($request->all());
            return response()->json(['message' => 'Salesman deleted successfully.'], Response::HTTP_NO_CONTENT);
        } catch (ForbiddenErrorException|NotFoundException|InputDataBadFormatErrorException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
