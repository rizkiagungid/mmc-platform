<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ImportAlumniMembers extends BaseCommand
{
    protected $group       = 'Users';
    protected $name        = 'users:import-alumni';
    protected $description = 'Import list of alumni members (Angkatan 1 & 2) into users database';

    public function run(array $params)
    {
        CLI::write("=== MMC Alumni Database Importer ===", 'yellow');

        $db = \Config\Database::connect();

        // Ensure Alumni role exists
        $alumniRole = $db->table('roles')->where('slug', 'alumni')->get()->getRowArray();
        if (!$alumniRole) {
            $db->table('roles')->insert([
                'name'        => 'Alumni',
                'slug'        => 'alumni',
                'description' => 'Alumni klub: akses portal alumni, riwayat kegiatan, dan komunitas ekskul.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $alumniRole = $db->table('roles')->where('slug', 'alumni')->get()->getRowArray();
        }

        $roleId = (int)$alumniRole['id'];
        CLI::write("Using Role ID: {$roleId} (Alumni)", 'green');

        $rawNames = [
            // Angkatan 1
            'athaya saputra',
            'farrel Noka radhitya',
            'Rani Nurhasanah',
            'Gayus Dementrius Simanjuntak',
            'Thania Aprillia',
            'Delia anggraeni',
            'Dias siska damayanti',
            'M.rayhan iryansyah',
            'silva dwi yanti',
            'Bagus andre w',
            'Miftah ziadatur rizki',
            'nisrina salma',
            'nadilla balqista',
            'siti nurhalisya',
            'Denisa eka',
            'Anisa nurmayanti',
            'Kevin satria sitanggang',
            'isa romansah',
            'khaerul anwar',
            'mita siti patimah',
            'fadia sahara',
            'husna alna',
            'syifa dinah rafifah',
            'septia lestari',
            'annisa nurul adelia',
            'zenobia khadijah',
            'salwa zalfadira',
            'akar aripan',
            'Dwi rizky',
            'alif solehudin',
            'qonita sumayya',
            'ilham rizki',
            'moch. Rizky agustian',
            'latifah rahmawati',
            'anisa nurbaiti',
            'nurayuni',
            'm.fahmi hidayat',
            'm.hafis putra',
            'm.syawaluddin',
            'firyal balqis',
            'muhammad rizky',
            'czar daffa alfarizi',
            'putri aisyah',
            'shaida azzahra',
            'Yudis maulana',
            'alhalim ali',
            'putri widiasari',
            'reka bayu',
            'rembulan nauli s',
            'zahwa luna',
            'ahmad dhani',
            'jason rafael setia j',
            'm andreansyah',
            'm hendryawan',
            'm sandy',
            'vidya rahmawati',
            'Muhammad zidan',

            // Angkatan 2
            'Lukman nul hakim',
            'sochio athallah rinaldi',
            'Febby ryan A',
            'muhammad adnan',
            'khairunnisa',
            'ikhwan ahdilah',
            'sri rahma hania hidayat',
            'bella desvita aulia',
            'cindy herniwan',
            'putri aprilia',
            'muhammad rafli firdaus',
            'muhammad arya nurhakim',
            'muhammad ikbal riyadi',
            'nurul nasywa anjani',
            'ihsan abdillah',
            'raihan fauzan satura',
            'moh. Firman ardiansyah ajhafi',
            'muhammad andriansyah',
            'adinda lestari',
            'alif haikal fadilah',
            'farida widiasiti',
            'muhammad bintang raja s',
            'aulia azahra rasadi',
            'noviya rahma',
            'rieke artanty',
            'rafa hermawan suryana',
            'zahra nur azizah',
            'kery irawan',
            'muhammad fadhil . Fs',
            'wahyu ferdiana',
            'Avisa Nurmaulidina',
            'detria rahmawati',
            'meisya maulida uswatun hasanah',
            'astri vidila sari',
            'ratu keisha L.R.',
            'siti rofiah salsabilah',
            'exzal nurul suradey',
            'wanda abida s.',
            'bayu maulana',
            'agis haryanto',
            'salwa haura s.',
            'muhammad andriansyah',
            'muhammad dendra s.',
            'muhammad rifky alan',
            'dimas marentino',
            'afta alfika sakila',
            'yumna hanifah',
            'adry nov prayoga',
            'gusti nurseptiansyah',
            'dewi ayu ratnadia',
            'mutika',
            'muhammad isal',
            'fadli rizaldi',
            'muhammad ridwan nurfadilah',
            'lalu ferdiansyah hidayat',
            'fillfani febriyolla',
            'afriyan',
            'rama wijaya',
            'pasha udia aripah',
            'citra lestari'
        ];

        $defaultPasswordHash = password_hash('mmc12345', PASSWORD_BCRYPT);
        $now = date('Y-m-d H:i:s');
        $insertedCount = 0;
        $skippedCount  = 0;

        foreach ($rawNames as $rawName) {
            $cleanName = trim(preg_replace('/\s+/', ' ', $rawName));
            if (empty($cleanName)) continue;

            // Properly format capitalization (e.g. "athaya saputra" -> "Athaya Saputra")
            $formattedName = ucwords(strtolower($cleanName));
            // Preserve acronyms like M. or L.R.
            $formattedName = preg_replace_callback('/\b([a-z])\./i', function ($m) {
                return strtoupper($m[1]) . '.';
            }, $formattedName);

            // Generate clean base username
            $cleanForUser = strtolower($cleanName);
            $cleanForUser = preg_replace('/[^a-z0-9]/', '.', $cleanForUser);
            $cleanForUser = trim(preg_replace('/\.+/', '.', $cleanForUser), '.');
            if (empty($cleanForUser)) {
                $cleanForUser = 'alumni.' . substr(md5($cleanName), 0, 6);
            }

            // Ensure username uniqueness
            $username = $cleanForUser;
            $counter = 1;
            while ($db->table('users')->where('username', $username)->countAllResults() > 0) {
                $counter++;
                $username = $cleanForUser . $counter;
            }

            // Ensure email uniqueness
            $email = $username . '@alumni.mmc';
            $emailCounter = 1;
            while ($db->table('users')->where('email', $email)->countAllResults() > 0) {
                $emailCounter++;
                $email = $username . $emailCounter . '@alumni.mmc';
            }

            // Check if user with exact same full_name and alumni role already exists
            $existingUser = $db->table('users')
                               ->where('full_name', $formattedName)
                               ->where('role_id', $roleId)
                               ->get()
                               ->getRowArray();

            if ($existingUser) {
                CLI::write("Skipped (already exists): {$formattedName} (@{$existingUser['username']})", 'light_gray');
                $skippedCount++;
                continue;
            }

            // Generate UUID
            $memberUuid = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );

            $insertData = [
                'member_uuid'   => $memberUuid,
                'role_id'       => $roleId,
                'username'      => $username,
                'email'         => $email,
                'password_hash' => $defaultPasswordHash,
                'full_name'     => $formattedName,
                'nis_nip'       => null,
                'class_dept'    => 'Alumni',
                'phone'         => null,
                'avatar'        => null,
                'qr_version'    => 1,
                'qr_updated_at' => $now,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];

            $db->table('users')->insert($insertData);
            $insertedCount++;
            CLI::write("[+] Added: {$formattedName} -> @{$username} ({$email})", 'green');
        }

        CLI::write("\n==========================================", 'yellow');
        CLI::write("Total Alumni Inserted: {$insertedCount}", 'green');
        CLI::write("Total Skipped: {$skippedCount}", 'yellow');
        CLI::write("Default Password for all: mmc12345", 'cyan');
        CLI::write("==========================================", 'yellow');
    }
}
