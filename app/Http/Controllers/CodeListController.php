<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenErrorException;
use App\Exceptions\InputDataBadFormatErrorException;
use App\Http\Resources\SalesmanCollection;
use App\Services\CodeListService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class CodeListController
 * @package App\Http\Controllers
 */
class CodeListController extends Controller
{
    /**
     * @param CodeListService $codeListService
     */
    public function __construct(private readonly CodeListService $codeListService) {}

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json($this->codeListService->list(), Response::HTTP_OK);
        } catch (ForbiddenErrorException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
