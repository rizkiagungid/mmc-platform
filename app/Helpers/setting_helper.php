<?php

if (!function_exists('get_setting')) {
    /**
     * Get system setting value by key with memory static caching
     */
    function get_setting(string $key, $default = null)
    {
        static $settingsCache = null;
        if ($settingsCache === null) {
            try {
                $db = \Config\Database::connect();
                $rows = $db->table('settings')->get()->getResultArray();
                $settingsCache = [];
                foreach ($rows as $r) {
                    $settingsCache[$r['setting_key']] = $r['setting_value'];
                }
            } catch (\Throwable $e) {
                $settingsCache = [];
            }
        }
        return array_key_exists($key, $settingsCache) && $settingsCache[$key] !== '' ? $settingsCache[$key] : $default;
    }
}
