<?php

namespace App\Http\Controllers;

use App\Rules\DomainRule;
use App\Services\WhoIsService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    /**
     * Create a new instance of a controller
     *
     * @param WhoIsService $whoIsService
     */
    public function __construct(
        protected WhoIsService $whoIsService
    )
    {
        //
    }

    /**
     * Fetch the domain info
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ConnectionException
     */
    public function fetchDomain(Request $request): JsonResponse
    {
        // Validate the request
        $validated = $request->validate([
            'domain' => ['required', new DomainRule],
            'type' => ['required', 'string'],
        ]);

        // Get the domain info from a service
        $data = $this->whoIsService->getDomainInfo(
            data_get($validated, 'domain')
        );

        // Return the info as JSON
        return response()->json(data_get($data, 'WhoisRecord', []));
    }
}
