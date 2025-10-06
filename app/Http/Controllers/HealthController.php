<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class HealthController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json([
            'status'  => 'ok',
            'app'     => Config::get('app.name'),
            'env'     => App::environment(),
            'version' => App::version(),
            'time'    => now()->toIso8601String(),
        ], 200);
    }
}
