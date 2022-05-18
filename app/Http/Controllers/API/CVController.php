<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CV\StoreCVRequest;
use App\Http\Resources\CV\CVResource;
use App\Services\CV\CVService;
use App\Traits\ApiResponse;
use App\Traits\CurrentUser;
use Illuminate\Http\JsonResponse;

class CVController extends Controller
{
    use ApiResponse, CurrentUser;

    private $cvService, $blogService;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(CVService $cvService)
    {
        $this->cvService = $cvService;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCVRequest $request
     * @return JsonResponse
     */
    public function store(StoreCVRequest $request): JsonResponse
    {
        $dataCV = $request->all();
        $user = $this->user();

        $cv = $this->cvService->store($user, $dataCV);

        if ($cv) {
            return $this->successfulResultWithoutAuth('Store CV successfully!!!', new CVResource($cv));
        }
        return $this->failedResult('Failed store CV!!!', 500);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $id = request('id');
        
        $user = $this->user();
        $cv = $this->cvService->findById($user, $id);

        if ($cv) {
            return $this->successfulResultWithoutAuth('Get CV successfully!!!', new CVResource($cv));
        }
        return $this->failedResult('Failed get CV!!!', 500);
    }
    
}
