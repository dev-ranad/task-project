<?php

namespace App\Http\Traits;

use App\Http\Services\AuthTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use \Firebase\JWT\JWT;

trait ApiResponserTrait
{
    public function responseMessage(string $module, string $action)
    {
        $messages = [
            'index' => ' list getting successfully!',
            'show' => ' showing successfully!',
            'edit' => ' edited data getting successfully!',
            'store' => ' stored successfully!',
            'update' => ' updated successfully!',
            'delete' => ' deleted successfully!',
            'destroy' => ' deleted permanently successfully!',
            'restore' => "'s data restored successfully!",
            'empty' => "'s data deleted permanently successfully!",
            'trash' => ' trash getting successfully!',
            'status' => ' status updated successfully!',
            'import' => ' imported successfully!',
            'export' => ' exported successfully!',
            'default' => ' make defaulted successfully!',
            'validation' => "'s validation response!",
            'recharge' => " are recharged successfully!",
            'pc-check' => " are Checked!",
            'coupon-alert' => " are finished for this code!",
            'gift-alert' => " is already used!",



            'authentication' => " Attempted request are Authenticated",
            'unauthentication' => " Attempted request are Unauthenticated",
            'revoke' => " Logged out successfully!",
            'closeCash' => " are closed!",
            'lessCash' => "'s balance are lower then expense amount!",
            'empty-stock' => "'s are Empty!",
            'invalid-warehouse' => "Some of the product are not exist in the selected warehouse",
            'invalid' => " have invalid value!",
            'notFound' => "'s data not found!",
            'dataNotFound' => 'Data not found!',
        ];
        return preg_replace('/(?<!^)(?=[A-Z])/', ' ', $module) . ($messages[$action] ?? 'Response value showing');
    }

    public function successResponse(string $message, $data, int $httpResponseCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => $httpResponseCode,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], $httpResponseCode);
    }

    public function errorResponse(string $message, ?array $errors = [], int $httpResponseCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => $httpResponseCode,
            'message' => $message ?? null,
            'data' => null,
            'errors' => $errors,
        ], $httpResponseCode);
    }
}
