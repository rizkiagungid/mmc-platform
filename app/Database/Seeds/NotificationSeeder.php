<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // 1. Update any legacy types to 'task'
        $this->db->table('notifications')->whereIn('type', ['task_eval', 'mention'])->update(['type' => 'task']);

        // 2. Fetch all tasks to match titles with task notifications
        $tasks = $this->db->table('tasks')->select('id, title')->get()->getResultArray();
        
        foreach ($tasks as $t) {
            $taskId    = $t['id'];
            $taskTitle = $t['title'];

            // Match notifications containing this task's title
            $this->db->table('notifications')
                     ->where('type', 'task')
                     ->like('title', $taskTitle)
                     ->update(['link' => 'member/tasks/submit/' . $taskId]);
        }

        // 3. Fallback for any remaining task notifications without explicit link
        $firstTask = reset($tasks);
        $defaultTaskId = $firstTask ? $firstTask['id'] : 4;
        $this->db->table('notifications')
                 ->where('type', 'task')
                 ->groupStart()
                     ->where('link IS NULL')
                     ->orWhere('link', 'member/tasks')
                 ->groupEnd()
                 ->update(['link' => 'member/tasks/submit/' . $defaultTaskId]);
    }
}
