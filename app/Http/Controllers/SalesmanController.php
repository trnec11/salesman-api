<?php

namespace App\Http\Controllers;

use App\Exceptions\AlreadyExistsException;
use App\Exceptions\ForbiddenErrorException;
use App\Exceptions\InputDataBadFormatErrorException;
use App\Exceptions\InputDataOutOfRangeErrorException;
use App\Exceptions\NotFoundException;
use App\Http\Resources\Salesman;
use App\Http\Resources\SalesmanCollection;
use App\Service\SalesmanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SalesmanController extends Controller
{
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
     * @param string $uuid
     * @param Request $request
     * @return JsonResponse
     */
    public function update(string $uuid, Request $request): JsonResponse
    {
        try {
            $salesmanId = $this->salesmanService->update($uuid, $request->all());
            return response()->json(new Salesman(Salesman::query()->where(['id' => $salesmanId])), Response::HTTP_OK);
        } catch (ForbiddenErrorException|InputDataBadFormatErrorException|InputDataOutOfRangeErrorException|NotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function destroy(string $uuid)
    {
        try {
            $this->salesmanService->delete($uuid);
            return response()->json(['message' => 'Salesman deleted successfully.'], Response::HTTP_NO_CONTENT);
        } catch (ForbiddenErrorException|NotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
