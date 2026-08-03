<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TaskMasterSeeder extends Seeder
{
    public function run()
    {
        // 1. Task Statuses
        $statuses = [
            ['id' => 1, 'name' => 'Todo', 'color' => '#6c757d', 'sort_order' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'In Progress', 'color' => '#0ea5e9', 'sort_order' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Review', 'color' => '#f59e0b', 'sort_order' => 3, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'Revision', 'color' => '#ec4899', 'sort_order' => 4, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 5, 'name' => 'Done', 'color' => '#10b981', 'sort_order' => 5, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('task_statuses')->ignore(true)->insertBatch($statuses);

        // 2. Task Priorities
        $priorities = [
            ['id' => 1, 'name' => 'Low', 'color' => '#10b981', 'sort_order' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Medium', 'color' => '#3b82f6', 'sort_order' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'High', 'color' => '#f59e0b', 'sort_order' => 3, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'Urgent', 'color' => '#ef4444', 'sort_order' => 4, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('task_priorities')->ignore(true)->insertBatch($priorities);

        // 3. Labels
        $labels = [
            ['id' => 1, 'name' => 'Photography', 'color' => '#ef4444', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Videography', 'color' => '#8b5cf6', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Poster & Graphic Design', 'color' => '#ec4899', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'Programming & Web', 'color' => '#3b82f6', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 5, 'name' => 'Broadcast', 'color' => '#10b981', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 6, 'name' => 'Podcast', 'color' => '#f59e0b', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 7, 'name' => 'Website Content', 'color' => '#06b6d4', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('labels')->ignore(true)->insertBatch($labels);
    }
}
