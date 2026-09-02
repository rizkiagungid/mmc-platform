<?php

namespace App\Commands;

use App\Libraries\MediaManager;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SyncMediaOnline extends BaseCommand
{
    protected $group       = 'Media';
    protected $name        = 'media:sync-online';
    protected $description = 'Download and synchronize all physical media assets from production URL into local storage';

    public function run(array $params)
    {
        CLI::write("=== Universal Media Online Synchronizer ===", 'yellow');

        $prodUrl = rtrim(env('media.prodFallbackURL', 'https://multimediasmanit.my.id'), '/');
        CLI::write("Production Source: {$prodUrl}", 'cyan');

        $db = \Config\Database::connect();
        $downloaded = 0;
        $skipped = 0;
        $failed = 0;

        $targets = [
            'users'              => ['avatar'],
            'posts'              => ['media_url'],
            'task_submissions'   => ['attachment_url'],
            'media_library'      => ['file_path'],
            'portfolios'         => ['image_url', 'file_url', 'thumbnail_url'],
            'learning_materials' => ['file_path', 'thumbnail', 'banner'],
            'divisions'          => ['icon', 'image'],
            'achievements'       => ['image_url'],
        ];

        foreach ($targets as $table => $columns) {
            if (!$db->tableExists($table)) {
                continue;
            }

            $rows = $db->table($table)->get()->getResultArray();
            foreach ($rows as $row) {
                foreach ($columns as $column) {
                    if (empty($row[$column])) {
                        continue;
                    }

                    $cleanPath = MediaManager::cleanPath($row[$column]);
                    if (empty($cleanPath) || MediaManager::isExternalUrl($cleanPath) || strpos($cleanPath, 'uploads/') !== 0) {
                        continue;
                    }

                    $localFile = FCPATH . ltrim($cleanPath, '/');

                    if (file_exists($localFile) && is_file($localFile)) {
                        $skipped++;
                        continue;
                    }

                    $remoteUrl = $prodUrl . '/' . ltrim($cleanPath, '/');
                    CLI::write("Downloading: {$cleanPath} ...", 'white');

                    $ch = curl_init($remoteUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                    $content = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($httpCode === 200 && !empty($content)) {
                        $dir = dirname($localFile);
                        if (!is_dir($dir)) {
                            @mkdir($dir, 0755, true);
                        }
                        @file_put_contents($localFile, $content);
                        $downloaded++;
                        CLI::write("  [OK] Saved to: {$cleanPath} (" . strlen($content) . " bytes)", 'green');
                    } else {
                        $failed++;
                        CLI::write("  [FAILED] HTTP {$httpCode} for: {$remoteUrl}", 'red');
                    }
                }
            }
        }

        CLI::write("================================================", 'yellow');
        CLI::write("Sync Complete!", 'green');
        CLI::write("Downloaded : {$downloaded} files", 'green');
        CLI::write("Skipped    : {$skipped} files (already exist)", 'white');
        CLI::write("Failed     : {$failed} files", $failed > 0 ? 'red' : 'white');
    }
}
