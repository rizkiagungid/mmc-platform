<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ChatSeeder extends Seeder
{
    public function run()
    {
        $users = $this->db->table('users')->select('id, full_name')->get()->getResultArray();
        if (count($users) < 2) return;

        $user1 = $users[0]['id'];
        $user2 = $users[1]['id'];
        $user3 = isset($users[2]) ? $users[2]['id'] : $user1;

        $now = date('Y-m-d H:i:s');

        // 1. Create a Group Chat
        $groupConvId = $this->db->table('chat_conversations')->insert([
            'uuid'        => sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)),
            'type'        => 'group',
            'name'        => 'Grup Multimedia Club SMAN 1',
            'description' => 'Grup resmi koordinasi kegiatan dan divisi Multimedia Club SMAN 1 Tamansari.',
            'created_by'  => $user1,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);
        $groupConvId = $this->db->insertID();

        foreach ($users as $u) {
            $this->db->table('chat_participants')->insert([
                'conversation_id' => $groupConvId,
                'user_id'         => $u['id'],
                'role'            => ($u['id'] === $user1) ? 'admin' : 'member',
                'joined_at'       => $now,
            ]);
        }

        $this->db->table('chat_messages')->insertBatch([
            [
                'conversation_id' => $groupConvId,
                'sender_id'       => $user1,
                'message'         => 'Halo semuanya! Selamat datang di Grup Resmi Multimedia Club SMAN 1 Tamansari 👋',
                'is_read'         => 1,
                'created_at'      => date('Y-m-d H:i:s', strtotime('-1 hour')),
            ],
            [
                'conversation_id' => $groupConvId,
                'sender_id'       => $user2,
                'message'         => 'Halo kak! Siap untuk diskusi proyek dan tugas multimedia klub berikutnya 🚀',
                'is_read'         => 0,
                'created_at'      => date('Y-m-d H:i:s', strtotime('-30 minutes')),
            ],
        ]);

        // 2. Create a Direct Chat
        $directConvId = $this->db->table('chat_conversations')->insert([
            'uuid'       => sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)),
            'type'       => 'direct',
            'name'       => null,
            'created_by' => $user1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $directConvId = $this->db->insertID();

        $this->db->table('chat_participants')->insertBatch([
            ['conversation_id' => $directConvId, 'user_id' => $user1, 'role' => 'admin', 'joined_at' => $now],
            ['conversation_id' => $directConvId, 'user_id' => $user2, 'role' => 'member', 'joined_at' => $now],
        ]);

        $this->db->table('chat_messages')->insertBatch([
            [
                'conversation_id' => $directConvId,
                'sender_id'       => $user1,
                'message'         => 'Permisi kak, mau tanya terkait hasil evaluasi tugas penugasan kemarin.',
                'is_read'         => 1,
                'created_at'      => date('Y-m-d H:i:s', strtotime('-2 hours')),
            ],
            [
                'conversation_id' => $directConvId,
                'sender_id'       => $user2,
                'message'         => 'Tentu, hasil revisi dan catatannya sudah diupdate ya!',
                'is_read'         => 0,
                'created_at'      => date('Y-m-d H:i:s', strtotime('-10 minutes')),
            ],
        ]);
    }
}
