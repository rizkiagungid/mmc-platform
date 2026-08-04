<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table            = 'attendances';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'meeting_id',
        'user_id',
        'method',
        'scanned_by_admin_id',
        'scan_time',
        'status',
        'notes',
        'device',
        'ip_address',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getAttendancesByMeeting(int $meetingId)
    {
        return $this->select('attendances.*, users.full_name, users.nis_nip, users.class_dept, users.username, roles.name as role_name, roles.slug as role_slug, admin.full_name as admin_name, meetings.title as meeting_title, meetings.meeting_date')
                    ->join('users', 'users.id = attendances.user_id')
                    ->join('roles', 'roles.id = users.role_id', 'left')
                    ->join('meetings', 'meetings.id = attendances.meeting_id')
                    ->join('users as admin', 'admin.id = attendances.scanned_by_admin_id', 'left')
                    ->where('attendances.meeting_id', $meetingId)
                    ->where('meetings.deleted_at IS NULL')
                    ->where('roles.slug !=', 'superadmin')
                    ->orderBy('attendances.scan_time', 'DESC')
                    ->findAll();
    }

    public function getAttendancesByUser(int $userId)
    {
        return $this->select('attendances.*, meetings.title as meeting_title, meetings.meeting_date, meetings.start_time, meetings.location')
                    ->join('meetings', 'meetings.id = attendances.meeting_id')
                    ->where('attendances.user_id', $userId)
                    ->where('meetings.deleted_at IS NULL')
                    ->orderBy('meetings.meeting_date', 'DESC')
                    ->findAll();
    }

    public function getAllAttendances(?int $meetingId = null, ?int $userId = null)
    {
        $builder = $this->select('attendances.*, users.full_name, users.nis_nip, users.class_dept, users.username, roles.name as role_name, roles.slug as role_slug, meetings.title as meeting_title, meetings.meeting_date, meetings.location, admin.full_name as admin_name')
                        ->join('users', 'users.id = attendances.user_id')
                        ->join('roles', 'roles.id = users.role_id', 'left')
                        ->join('meetings', 'meetings.id = attendances.meeting_id')
                        ->join('users as admin', 'admin.id = attendances.scanned_by_admin_id', 'left')
                        ->where('meetings.deleted_at IS NULL')
                        ->where('roles.slug !=', 'superadmin');

        if ($meetingId && $meetingId > 0) {
            $builder->where('attendances.meeting_id', $meetingId);
        }

        if ($userId && $userId > 0) {
            $builder->where('attendances.user_id', $userId);
        }

        return $builder->orderBy('attendances.scan_time', 'DESC')->findAll();
    }

    public function getFilteredAttendances(array $filters = [])
    {
        $builder = $this->select('attendances.*, users.full_name, users.nis_nip, users.class_dept, users.username, roles.name as role_name, roles.slug as role_slug, meetings.title as meeting_title, meetings.meeting_date, meetings.location, admin.full_name as admin_name')
                        ->join('users', 'users.id = attendances.user_id')
                        ->join('roles', 'roles.id = users.role_id', 'left')
                        ->join('meetings', 'meetings.id = attendances.meeting_id')
                        ->join('users as admin', 'admin.id = attendances.scanned_by_admin_id', 'left')
                        ->where('meetings.deleted_at IS NULL')
                        ->where('roles.slug !=', 'superadmin');

        if (!empty($filters['meeting_id']) && $filters['meeting_id'] !== 'all') {
            $builder->where('attendances.meeting_id', (int)$filters['meeting_id']);
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $builder->where('attendances.status', $filters['status']);
        }

        if (!empty($filters['method']) && $filters['method'] !== 'all') {
            $builder->where('attendances.method', $filters['method']);
        }

        if (!empty($filters['start_date'])) {
            $builder->where('DATE(attendances.scan_time) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $builder->where('DATE(attendances.scan_time) <=', $filters['end_date']);
        }

        return $builder->orderBy('attendances.scan_time', 'DESC')->findAll();
    }

    public function getUserHistory(int $userId)
    {
        return $this->getAttendancesByUser($userId);
    }

    public function checkAlreadyAttended(int $meetingId, int $userId)
    {
        return $this->where('meeting_id', $meetingId)
                    ->where('user_id', $userId)
                    ->first();
    }

    public function isAlreadyAttended(int $meetingId, int $userId): bool
    {
        return $this->checkAlreadyAttended($meetingId, $userId) !== null;
    }
}
