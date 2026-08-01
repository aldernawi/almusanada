<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-Key') ?: $request->query('api_key');

        if (!$key) {
            return response()->json([
                'success' => false,
                'message' => 'مفتاح الـ API مفقود (يجب توفير X-API-Key في الـ Headers)',
            ], 401);
        }

        $apiKey = ApiKey::where('key', $key)->first();

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'مفتاح الـ API غير صالح',
            ], 401);
        }

        // Log the usage
        $apiKey->update(['last_used_at' => now()]);

        // Log in the user for this request
        auth()->login($apiKey->user);

        return $next($request);
    }
}
