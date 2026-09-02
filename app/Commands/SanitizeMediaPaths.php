<?php

namespace App\Commands;

use App\Libraries\MediaManager;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SanitizeMediaPaths extends BaseCommand
{
    protected $group       = 'Media';
    protected $name        = 'media:clean-paths';
    protected $description = 'Sanitize all media path records in database to clean relative paths (uploads/...)';

    public function run(array $params)
    {
        CLI::write("=== Universal Media Database Path Sanitizer ===", 'yellow');

        $db = \Config\Database::connect();
        $totalCleaned = 0;

        // Target tables and their media columns
        $targets = [
            'task_submissions' => ['attachment_url'],
            'media_library'    => ['file_path'],
            'users'            => ['avatar'],
            'portfolios'       => ['image_url', 'file_url', 'thumbnail_url'],
            'learning_materials' => ['file_path', 'cover_image'],
            'feed_posts'       => ['media_url'],
            'site_settings'    => ['value'],
            'divisions'        => ['icon', 'image'],
            'achievements'     => ['image_url'],
        ];

        foreach ($targets as $table => $columns) {
            if (!$db->tableExists($table)) {
                continue;
            }

            CLI::write("Checking table: {$table}...", 'white');
            $rows = $db->table($table)->get()->getResultArray();
            $tableCleaned = 0;

            foreach ($rows as $row) {
                $primaryKey = $row['id'] ?? null;
                if (!$primaryKey) {
                    continue;
                }

                $updates = [];
                foreach ($columns as $column) {
                    if (!isset($row[$column]) || empty($row[$column])) {
                        continue;
                    }

                    $raw = $row[$column];
                    // Only process if it contains uploads/ and has http/https or is not clean
                    if (strpos($raw, 'uploads/') !== false && (strpos($raw, 'http://') !== false || strpos($raw, 'https://') !== false || strpos($raw, '\\') !== false)) {
                        $clean = MediaManager::cleanPath($raw);
                        if ($clean !== $raw) {
                            $updates[$column] = $clean;
                        }
                    }
                }

                if (!empty($updates)) {
                    $db->table($table)->where('id', $primaryKey)->update($updates);
                    $tableCleaned++;
                    $totalCleaned++;
                }
            }

            CLI::write("  -> {$tableCleaned} records sanitized in {$table}.", 'green');
        }

        CLI::write("=== Done! Total {$totalCleaned} media paths sanitized to relative paths ===", 'yellow');
    }
}
