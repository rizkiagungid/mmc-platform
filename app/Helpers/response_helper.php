<?php

if (!function_exists('json_response')) {
    /**
     * Helper to return standardized JSON response in CodeIgniter Controllers
     */
    function json_response(string $status, string $message, $data = null, int $statusCode = 200)
    {
        $response = \Config\Services::response();
        return $response->setStatusCode($statusCode)->setJSON([
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ]);
    }
}
