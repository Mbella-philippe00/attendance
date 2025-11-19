<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    protected function ok($data = null, string $message = 'OK', int $code = 200): JsonResponse
    {
        return response()->json(['message'=>$message,'data'=>$data], $code);
    }

    protected function paginated($paginator, $resource = null): JsonResponse
    {
        $items = $resource ? $resource::collection($paginator->items()) : $paginator->items();

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }
}
