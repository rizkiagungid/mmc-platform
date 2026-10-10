<?php

namespace App\Modules\Tools\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class ToolsController extends BaseController
{
    protected array $tools = [];

    public function __construct()
    {
        // Enforce strict login check (guest/public access denied)
        if (!session()->get('is_logged_in')) {
            header('Location: ' . base_url('login'));
            exit;
        }

        $this->tools = [
            'compress' => [
                'id'          => 'compress',
                'name'        => 'Compress IMAGE',
                'title'       => 'Kompres Gambar (JPG, PNG, WEBP)',
                'category'    => 'optimize',
                'category_name' => 'Optimasi',
                'badge'       => null,
                'icon'        => 'fa-solid fa-compress',
                'icon_color'  => '#10b981',
                'description' => 'Kompres file JPG, PNG, WEBP, dan GIF hemat ruang penyimpanan tanpa mengurangi ketajaman visual gambar.',
                'accept'      => 'image/jpeg,image/png,image/webp,image/gif',
                'formats'     => 'JPG, PNG, WEBP, GIF',
            ],
            'crop' => [
                'id'          => 'crop',
                'name'        => 'Crop IMAGE',
                'title'       => 'Potong / Crop Gambar',
                'category'    => 'edit',
                'category_name' => 'Edit',
                'badge'       => null,
                'icon'        => 'fa-solid fa-crop-simple',
                'icon_color'  => '#06b6d4',
                'description' => 'Potong area foto dengan presisi pixel tinggi atau pilihan rasio standar 1:1, 16:9, 9:16 (Story/Reels), dan 4:3.',
                'accept'      => 'image/*',
                'formats'     => 'JPG, PNG, WEBP, GIF',
            ],
            'resize' => [
                'id'          => 'resize',
                'name'        => 'Resize IMAGE',
                'title'       => 'Ubah Dimensi / Ukuran Gambar',
                'category'    => 'edit',
                'category_name' => 'Edit',
                'badge'       => null,
                'icon'        => 'fa-solid fa-up-right-and-down-left-from-center',
                'icon_color'  => '#3b82f6',
                'description' => 'Ubah resolusi lebar dan tinggi gambar berdasarkan pixel atau persentase skala, dengan pengunci rasio aspek otomatis.',
                'accept'      => 'image/*',
                'formats'     => 'JPG, PNG, WEBP, GIF',
            ],
            'convert-to-jpg' => [
                'id'          => 'convert-to-jpg',
                'name'        => 'Convert to JPG',
                'title'       => 'Konversi Gambar ke JPG',
                'category'    => 'convert',
                'category_name' => 'Konversi',
                'badge'       => null,
                'icon'        => 'fa-solid fa-file-image',
                'icon_color'  => '#f59e0b',
                'description' => 'Ubah gambar format PNG, WEBP, GIF, BMP, atau SVG ke format standar JPG beresolusi tinggi dalam hitungan detik.',
                'accept'      => 'image/*',
                'formats'     => 'PNG, WEBP, GIF, SVG, BMP → JPG',
            ],
            'convert-from-jpg' => [
                'id'          => 'convert-from-jpg',
                'name'        => 'Convert from JPG',
                'title'       => 'Konversi JPG ke Format Lain',
                'category'    => 'convert',
                'category_name' => 'Konversi',
                'badge'       => null,
                'icon'        => 'fa-solid fa-arrow-right-arrow-left',
                'icon_color'  => '#eab308',
                'description' => 'Ubah gambar JPG Anda ke format PNG transparan, format modern WEBP hemat kuota, atau GIF.',
                'accept'      => 'image/jpeg,image/jpg',
                'formats'     => 'JPG → PNG, WEBP, GIF',
            ],
            'rotate' => [
                'id'          => 'rotate',
                'name'        => 'Rotate IMAGE',
                'title'       => 'Putar & Cermin Gambar',
                'category'    => 'edit',
                'category_name' => 'Edit',
                'badge'       => null,
                'icon'        => 'fa-solid fa-rotate',
                'icon_color'  => '#38bdf8',
                'description' => 'Putar orientasi foto 90°, 180°, 270°, atau cerminkan secara horizontal/vertikal (mirror) langsung dari browser.',
                'accept'      => 'image/*',
                'formats'     => 'JPG, PNG, WEBP, GIF',
            ],
            'upscale' => [
                'id'          => 'upscale',
                'name'        => 'Upscale Image',
                'title'       => 'Perbesar Resolusi Gambar (Super Res)',
                'category'    => 'optimize',
                'category_name' => 'Optimasi',
                'badge'       => 'New!',
                'badge_type'  => 'new',
                'icon'        => 'fa-solid fa-wand-magic-sparkles',
                'icon_color'  => '#ef4444',
                'description' => 'Perbesar resolusi gambar 2x hingga 4x dengan interpolasi super-resolusi & penajaman detail tepi bebas blur.',
                'accept'      => 'image/*',
                'formats'     => 'JPG, PNG, WEBP',
            ],
            'remove-bg' => [
                'id'          => 'remove-bg',
                'name'        => 'Remove Background',
                'title'       => 'Hapus Latar Belakang (Transparan)',
                'category'    => 'edit',
                'category_name' => 'Edit',
                'badge'       => 'New!',
                'badge_type'  => 'new',
                'icon'        => 'fa-solid fa-eraser',
                'icon_color'  => '#22c55e',
                'description' => 'Hapus background latar foto objek secara otomatis atau selektif, hasilkan file PNG transparan siap ditempel.',
                'accept'      => 'image/png,image/jpeg,image/webp',
                'formats'     => 'PNG, JPG, WEBP → PNG Transparan',
            ],
            'pdf-to-jpg' => [
                'id'          => 'pdf-to-jpg',
                'name'        => 'PDF to JPG',
                'title'       => 'Konversi Dokumen PDF ke Gambar JPG',
                'category'    => 'convert',
                'category_name' => 'Konversi',
                'badge'       => 'New!',
                'badge_type'  => 'new',
                'icon'        => 'fa-solid fa-file-pdf',
                'icon_color'  => '#f43f5e',
                'description' => 'Ekstrak dan konversi setiap halaman dokumen PDF Anda menjadi file gambar JPG berkualitas tajam dan siap cetak.',
                'accept'      => 'application/pdf',
                'formats'     => 'PDF → JPG Berkualitas Tinggi',
            ],
            'watermark' => [
                'id'          => 'watermark',
                'name'        => 'Watermark IMAGE',
                'title'       => 'Bubuhi Watermark Teks & Logo',
                'category'    => 'create',
                'category_name' => 'Kreatif',
                'badge'       => null,
                'icon'        => 'fa-solid fa-stamp',
                'icon_color'  => '#a855f7',
                'description' => 'Tempelkan tanda air teks identitas atau stempel logo MMC pada foto dengan transparansi dan penempatan fleksibel.',
                'accept'      => 'image/*',
                'formats'     => 'JPG, PNG, WEBP',
            ],
            'meme' => [
                'id'          => 'meme',
                'name'        => 'Meme Generator',
                'title'       => 'Meme Generator Komunitas',
                'category'    => 'create',
                'category_name' => 'Kreatif',
                'badge'       => null,
                'icon'        => 'fa-solid fa-face-grin-squint-tears',
                'icon_color'  => '#ec4899',
                'description' => 'Bikin meme seru dan lucu dengan tipografi Impact klasik atas & bawah, simpan dan bagikan karya Anda dengan mudah.',
                'accept'      => 'image/*',
                'formats'     => 'JPG, PNG, WEBP',
            ],
        ];
    }

    /**
     * Tools Editor Hub Catalog
     */
    public function index()
    {
        return view('App\Modules\Tools\Views\index', [
            'title' => 'Tools Editor - Studio Manipulasi Gambar & Dokumen MMC',
            'tools' => $this->tools,
        ]);
    }

    /**
     * Interactive Studio Workspace for a specific tool
     */
    public function tool(string $slug)
    {
        if (!isset($this->tools[$slug])) {
            return redirect()->to(base_url('tools-editor'))->with('error', 'Tool yang diminta tidak ditemukan.');
        }

        return view('App\Modules\Tools\Views\studio', [
            'title'       => $this->tools[$slug]['name'] . ' - Tools Editor MMC',
            'activeTool'  => $this->tools[$slug],
            'allTools'    => $this->tools,
        ]);
    }
}
