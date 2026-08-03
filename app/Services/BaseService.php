<?php

namespace App\Services;

use Config\Database;

abstract class BaseService
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Start a database transaction
     */
    protected function beginTransaction(): void
    {
        $this->db->transStart();
    }

    /**
     * Complete a database transaction and return status
     */
    protected function commitTransaction(): bool
    {
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    /**
     * Standardized API/AJAX response shape generator
     */
    protected function respond(string $status, string $message, $data = null, int $statusCode = 200): array
    {
        return [
            'status_code' => $statusCode,
            'body'        => [
                'status'  => $status,
                'message' => $message,
                'data'    => $data,
            ],
        ];
    }

    protected function success(string $message = 'Operation successful', $data = null): array
    {
        return $this->respond('success', $message, $data, 200);
    }

    protected function error(string $message = 'Operation failed', $data = null, int $code = 400): array
    {
        return $this->respond('error', $message, $data, $code);
    }

    protected function warning(string $message, $data = null): array
    {
        return $this->respond('warning', $message, $data, 200);
    }
}
