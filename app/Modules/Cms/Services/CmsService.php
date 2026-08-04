<?php

namespace App\Modules\Cms\Services;

use App\Services\BaseService;
use App\Models\AuditLogModel;

class CmsService extends BaseService
{
    protected $auditLogModel;

    public function __construct()
    {
        parent::__construct();
        $this->auditLogModel = new AuditLogModel();
    }

    public function getHomepageSections(): array
    {
        return $this->db->table('homepage_sections')
                        ->orderBy('sort_order', 'ASC')
                        ->get()->getResultArray();
    }

    public function updateHomepageSections(array $sections, int $actorId): array
    {
        $this->beginTransaction();
        try {
            foreach ($sections as $sec) {
                $id = (int)($sec['id'] ?? 0);
                if ($id > 0) {
                    $this->db->table('homepage_sections')->where('id', $id)->update([
                        'name'           => trim($sec['name'] ?? ''),
                        'bg_color'       => trim($sec['bg_color'] ?? 'transparent'),
                        'bg_image'       => trim($sec['bg_image'] ?? ''),
                        'container_type' => trim($sec['container_type'] ?? 'container'),
                        'padding_top'    => trim($sec['padding_top'] ?? 'py-5'),
                        'padding_bottom' => trim($sec['padding_bottom'] ?? 'py-5'),
                        'sort_order'     => (int)($sec['sort_order'] ?? 0),
                        'is_active'      => isset($sec['is_active']) ? 1 : 0,
                        'updated_at'     => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            $this->auditLogModel->recordLog($actorId, 'CMS_HOMEPAGE_UPDATE', 'Memperbarui susunan seksi Halaman Utama.');
            $this->commitTransaction();
            return $this->success('Penataan Seksi Halaman Utama berhasil diperbarui.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memperbarui susunan seksi: ' . $e->getMessage());
        }
    }

    public function getHeroSection(): ?array
    {
        return $this->db->table('hero_sections')->orderBy('id', 'DESC')->get()->getRowArray();
    }

    public function updateHeroSection(array $data, int $actorId): array
    {
        $this->beginTransaction();
        try {
            $existing = $this->getHeroSection();
            $payload = [
                'title'              => trim($data['title'] ?? ''),
                'subtitle'           => trim($data['subtitle'] ?? ''),
                'description'        => trim($data['description'] ?? ''),
                'hero_bg'            => trim($data['hero_bg'] ?? ''),
                'hero_image'         => trim($data['hero_image'] ?? ''),
                'primary_btn_text'   => trim($data['primary_btn_text'] ?? ''),
                'primary_btn_url'    => trim($data['primary_btn_url'] ?? ''),
                'secondary_btn_text' => trim($data['secondary_btn_text'] ?? ''),
                'secondary_btn_url'  => trim($data['secondary_btn_url'] ?? ''),
                'updated_at'         => date('Y-m-d H:i:s'),
            ];

            if ($existing) {
                $this->db->table('hero_sections')->where('id', $existing['id'])->update($payload);
            } else {
                $payload['created_at'] = date('Y-m-d H:i:s');
                $this->db->table('hero_sections')->insert($payload);
            }

            $this->auditLogModel->recordLog($actorId, 'CMS_HERO_UPDATE', 'Memperbarui konten Hero Section.');
            $this->commitTransaction();
            return $this->success('Hero Section berhasil diperbarui.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memperbarui Hero Section: ' . $e->getMessage());
        }
    }

    private function ensureStatsColumnsExist()
    {
        if (!$this->db->fieldExists('is_auto', 'homepage_stats')) {
            $forge = \Config\Database::forge();
            $forge->addColumn('homepage_stats', [
                'is_auto'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'auto_source' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            ]);
        }
    }

    public function getHomepageStats(): array
    {
        $this->ensureStatsColumnsExist();
        return $this->db->table('homepage_stats')
                        ->orderBy('sort_order', 'ASC')
                        ->get()->getResultArray();
    }

    public function saveHomepageStat(array $data, int $actorId): array
    {
        $this->ensureStatsColumnsExist();
        $this->beginTransaction();
        try {
            $id = (int)($data['id'] ?? 0);
            $label = trim($data['label'] ?? '');

            // Auto detect auto_source based on label if not explicitly set
            $autoSource = trim($data['auto_source'] ?? '');
            if (empty($autoSource)) {
                if (stripos($label, 'Anggota') !== false) {
                    $autoSource = 'total_members';
                } elseif (stripos($label, 'Juara') !== false || stripos($label, 'Penghargaan') !== false || stripos($label, 'Prestasi') !== false) {
                    $autoSource = 'total_achievements';
                } elseif (stripos($label, 'Proyek') !== false || stripos($label, 'Karya') !== false || stripos($label, 'Portofolio') !== false) {
                    $autoSource = 'total_portfolios';
                }
            }

            $payload = [
                'label'       => $label,
                'value'       => trim($data['value'] ?? ''),
                'icon'        => trim($data['icon'] ?? 'fa-chart-line'),
                'prefix'      => trim($data['prefix'] ?? ''),
                'suffix'      => trim($data['suffix'] ?? ''),
                'sort_order'  => (int)($data['sort_order'] ?? 0),
                'is_active'   => isset($data['is_active']) ? 1 : 0,
                'is_auto'     => isset($data['is_auto']) ? 1 : 0,
                'auto_source' => $autoSource,
                'updated_at'  => date('Y-m-d H:i:s'),
            ];

            if ($id > 0) {
                $this->db->table('homepage_stats')->where('id', $id)->update($payload);
            } else {
                $payload['created_at'] = date('Y-m-d H:i:s');
                $this->db->table('homepage_stats')->insert($payload);
            }

            $this->auditLogModel->recordLog($actorId, 'CMS_STATS_UPDATE', 'Memperbarui data statistik homepage.');
            $this->commitTransaction();
            return $this->success('Data Statistik berhasil disimpan.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menyimpan data statistik: ' . $e->getMessage());
        }
    }

    public function deleteHomepageStat(int $id, int $actorId): array
    {
        $this->db->table('homepage_stats')->where('id', $id)->delete();
        $this->auditLogModel->recordLog($actorId, 'CMS_STATS_DELETE', "Menghapus data statistik ID {$id}");
        return $this->success('Data statistik dihapus.');
    }

    public function saveContactMessage(array $data): array
    {
        $name    = trim($data['sender_name'] ?? '');
        $email   = trim($data['sender_email'] ?? '');
        $subject = trim($data['subject'] ?? '');
        $message = trim($data['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            return $this->error('Harap lengkapi Nama, Email, dan Pesan Anda.');
        }

        $this->db->table('contact_messages')->insert([
            'sender_name'  => $name,
            'sender_email' => $email,
            'phone'        => trim($data['phone'] ?? ''),
            'subject'      => $subject ?: 'Pesan dari Website',
            'message'      => $message,
            'status'       => 'unread',
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return $this->success('Pesan Anda telah terkirim ke Pengurus Multimedia Club. Terima kasih!');
    }

    public function saveFeedbackMessage(array $data): array
    {
        $subject  = trim($data['subject'] ?? 'Kritik & Saran');
        $category = trim($data['category'] ?? 'Kritik & Saran');
        $message  = trim($data['message'] ?? '');

        if (empty($message)) {
            return $this->error('Isi kritik dan saran tidak boleh kosong.');
        }

        $this->db->table('contact_messages')->insert([
            'sender_name'  => 'Anonim',
            'sender_email' => 'anonymous@multimediaclub.org',
            'subject'      => $subject ?: 'Kritik & Saran',
            'category'     => $category ?: 'Kritik & Saran',
            'message'      => $message,
            'is_anonymous' => 1,
            'status'       => 'unread',
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return $this->success('Kritik & Saran Anda berhasil dikirim secara anonim. Terima kasih atas masukannya!');
    }

    public function getContactMessages(): array
    {
        return $this->db->table('contact_messages')
                        ->orderBy('created_at', 'DESC')
                        ->get()->getResultArray();
    }
}
