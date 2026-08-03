<?php

namespace App\Models;

use CodeIgniter\Model;

class MeetingModel extends Model
{
    protected $table            = 'meetings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'uuid',
        'title',
        'description',
        'learning_material',
        'mentor',
        'location',
        'meeting_date',
        'start_time',
        'end_time',
        'qr_token',
        'pin_code',
        'status',
        'created_by',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    public function generateUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function generateQrToken()
    {
        return 'MEET-QR-' . strtoupper(bin2hex(random_bytes(12)));
    }

    public function generatePinCode()
    {
        return str_pad((string) mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function getActiveMeeting()
    {
        return $this->select('meetings.*, users.full_name as creator_name')
                    ->join('users', 'users.id = meetings.created_by', 'left')
                    ->where('meetings.status', 'active')
                    ->orderBy('meetings.meeting_date', 'DESC')
                    ->first();
    }

    public function activateMeeting(int $id)
    {
        // Deactivate all existing active meetings first if any
        $this->where('status', 'active')->set(['status' => 'completed'])->update();

        $meeting = $this->find($id);
        if (!$meeting) return false;

        $qrToken = $meeting['qr_token'] ?? $this->generateQrToken();
        $pinCode = $meeting['pin_code'] ?? $this->generatePinCode();

        return $this->update($id, [
            'status'   => 'active',
            'qr_token' => $qrToken,
            'pin_code' => $pinCode,
        ]);
    }
}
