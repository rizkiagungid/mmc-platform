<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    private function generateUuid()
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

    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        // 1. Sample Meetings
        $meetings = [
            [
                'id'                => 1,
                'uuid'              => 'm001-meeting-uuid-active-1122334455',
                'title'             => 'Workshop Videography & Color Grading Masterclass',
                'description'       => 'Pelatihan dasar teknik pengambilan gambar sinematik, komposisi kamera, dan pengenalan DaVinci Resolve.',
                'learning_material' => 'https://drive.google.com/drive/folders/sample-videography-materials',
                'mentor'            => 'Muhammad Rizky Pratama & Tim Pembina',
                'location'          => 'Laboratorium Komputer 2 SMAN 1 Tamansari',
                'meeting_date'      => $today,
                'start_time'        => '14:00:00',
                'end_time'          => '16:30:00',
                'qr_token'          => 'MEET-QR-VIDEO-2026-ACTIVE-XYZ',
                'pin_code'          => '8899',
                'status'            => 'active',
                'created_by'        => 3, // BPH
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'id'                => 2,
                'uuid'              => $this->generateUuid(),
                'title'             => 'Kumpul Rutin & Evaluasi Project Aftermovie MPLS',
                'description'       => 'Evaluasi progress tugas aftermovie MPLS, pembagian revisi video, dan penyelarasan audio backsound.',
                'learning_material' => 'https://notion.so/mpls-aftermovie-breakdown',
                'mentor'            => 'Dra. Endang Setyowati',
                'location'          => 'Ruang Multimedia Utama',
                'meeting_date'      => date('Y-m-d', strtotime('+3 days')),
                'start_time'        => '15:00:00',
                'end_time'          => '17:00:00',
                'qr_token'          => 'MEET-QR-MPLS-EVAL-2026',
                'pin_code'          => '1234',
                'status'            => 'draft',
                'created_by'        => 2, // Pembina
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ];
        $this->db->table('meetings')->ignore(true)->insertBatch($meetings);

        // 2. Sample Tasks
        $tasks = [
            [
                'id'          => 1,
                'uuid'        => 't001-task-uuid-mpls-aftermovie-99',
                'title'       => 'Aftermovie MPLS SMAN 1 Tamansari 2026',
                'description' => 'Membuat video aftermovie durasi 3-5 menit untuk dokumentasi kegiatan MPLS 2026. Termasuk shooting, color grading, dan backsound license.',
                'priority_id' => 4, // Urgent
                'status_id'   => 2, // In Progress
                'deadline'    => date('Y-m-d H:i:s', strtotime('+7 days')),
                'created_by'  => 3, // BPH
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 2,
                'uuid'        => $this->generateUuid(),
                'title'       => 'Desain Poster Open Recruitment Anggota Baru',
                'description' => 'Desain poster Instagram Feed (1:1) dan Story (9:16) untuk promosi Pendaftaran Anggota Multimedia Club 2026/2027.',
                'priority_id' => 3, // High
                'status_id'   => 1, // Todo
                'deadline'    => date('Y-m-d H:i:s', strtotime('+5 days')),
                'created_by'  => 2, // Pembina
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];
        $this->db->table('tasks')->ignore(true)->insertBatch($tasks);

        // 3. Task Assignees
        $taskAssignees = [
            ['id' => 1, 'task_id' => 1, 'user_id' => 4, 'assigned_at' => $now], // Rizki
            ['id' => 2, 'task_id' => 1, 'user_id' => 5, 'assigned_at' => $now], // Adit
            ['id' => 3, 'task_id' => 1, 'user_id' => 6, 'assigned_at' => $now], // Fajar
            ['id' => 4, 'task_id' => 2, 'user_id' => 6, 'assigned_at' => $now], // Fajar
        ];
        $this->db->table('task_assignees')->ignore(true)->insertBatch($taskAssignees);

        // 4. Task Labels
        $taskLabels = [
            ['task_id' => 1, 'label_id' => 2], // Videography
            ['task_id' => 1, 'label_id' => 1], // Photography
            ['task_id' => 2, 'label_id' => 3], // Poster & Graphic Design
        ];
        $this->db->table('task_labels')->ignore(true)->insertBatch($taskLabels);

        // 5. Sample Task Submission
        $submissions = [
            [
                'id'              => 1,
                'task_id'         => 1,
                'user_id'         => 4, // Rizki
                'submission_text' => 'Sudah mengupload draft kasar Aftermovie MPLS (Cut 1) di Google Drive. Silakan di-review kak BPH.',
                'attachment_url'  => 'https://drive.google.com/file/d/sample-aftermovie-cut1',
                'status_id'       => 3, // Review
                'feedback'        => 'Transitions sudah bagus, tapi perjelas audio sambutan Kepala Sekolah.',
                'grade'           => 85,
                'evaluated_by'    => 3,
                'submitted_at'    => $now,
                'updated_at'      => $now,
            ],
        ];
        $this->db->table('task_submissions')->ignore(true)->insertBatch($submissions);

        // 6. Task Activities (ClickUp timeline)
        $activities = [
            [
                'task_id'     => 1,
                'user_id'     => 3,
                'action'      => 'Task Created',
                'description' => 'BPH (Muhammad Rizky Pratama) membuat tugas "Aftermovie MPLS SMAN 1 Tamansari 2026"',
                'created_at'  => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'task_id'     => 1,
                'user_id'     => 3,
                'action'      => 'Members Assigned',
                'description' => 'Ditugaskan kepada Rizki Agung Febrian, Aditya Kurniawan, dan Fajar Nugraha.',
                'created_at'  => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'task_id'     => 1,
                'user_id'     => 4,
                'action'      => 'Submission Uploaded',
                'description' => 'Rizki Agung Febrian mengirimkan tautan draft video Cut 1.',
                'created_at'  => $now,
            ],
        ];
        $this->db->table('task_activities')->ignore(true)->insertBatch($activities);

        // 7. Sample Attendance Record
        $attendances = [
            [
                'id'                  => 1,
                'meeting_id'          => 1,
                'user_id'             => 4, // Rizki
                'method'              => 'meeting_qr',
                'scanned_by_admin_id' => null,
                'scan_time'           => $now,
                'status'              => 'present',
                'notes'               => 'Presensi sukses via Meeting QR Code.',
                'device'              => 'Mozilla/5.0 (Android Mobile)',
                'ip_address'          => '127.0.0.1',
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
        ];
        $this->db->table('attendances')->ignore(true)->insertBatch($attendances);
    }
}
