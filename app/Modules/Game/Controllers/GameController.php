<?php

namespace App\Modules\Game\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class GameController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Mini Game Hub / Catalog
     */
    public function index()
    {
        $games = [
            [
                'id'          => 'exposure-triangle',
                'title'       => 'Exposure Triangle Simulator',
                'subtitle'    => '"The Holy Trinity": Segitiga Exposure',
                'category'    => 'Fotografi & Videografi',
                'badge'       => 'Simulator Kamera',
                'badge_color' => 'danger',
                'icon'        => 'fa-camera-retro',
                'image'       => 'assets/img/game-exposure-thumb.jpg',
                'difficulty'  => 'Pemula - Mahir',
                'status'      => 'active',
                'play_url'    => base_url('mini-game/exposure-triangle'),
                'description' => 'Eksperimen interaktif memahami kombinasi tiga pilar kamera: Aperture (Bukaan), Shutter Speed (Kecepatan Rana), dan ISO dalam mengatur pencahayaan, bokeh, motion blur, dan grain/noise.',
                'features'    => [
                    'Live Viewfinder Visualizer',
                    'Interactive Bokeh & Motion Blur',
                    'Digital Sensor Noise Emulation',
                    '5 Tantangan Fotografer Mode',
                    'Kamera Shutter Snap & EXIF Card'
                ]
            ],
            [
                'id'          => 'ide-simulator',
                'title'       => 'IDE Simulator & Code Lab',
                'subtitle'    => 'Multi-Language Compiler, Live Web & Quests',
                'category'    => 'Divisi Programming & Web',
                'badge'       => 'New Game #2',
                'badge_color' => 'primary',
                'icon'        => 'fa-code',
                'image'       => 'assets/img/game-ide-thumb.jpg',
                'difficulty'  => 'Pemula - Menengah',
                'status'      => 'active',
                'play_url'    => base_url('mini-game/ide-simulator'),
                'description' => 'Coding langsung berbagai bahasa (HTML/CSS/JS, Python, PHP, Java, C++, SQL), eksekusi live output, coba template struktur data web, dan selesaikan tantangan coding interaktif step-by-step!',
                'features'    => [
                    'Multi-Language Live Runner',
                    'Live Web Sandbox (HTML+CSS+JS)',
                    'Template Struktur & Tipe Data',
                    '5 Quest Coding dengan Auto-Test',
                    'Panduan Step-by-Step Interaktif'
                ]
            ],
            [
                'id'          => 'tapnich',
                'title'       => 'Tapnich: Drone Aerial',
                'subtitle'    => 'Flappy-Style Drone Side-Scroller & Footage Hunter',
                'category'    => 'Videografi & Drone Pilot',
                'badge'       => 'New Game #3',
                'badge_color' => 'warning',
                'icon'        => 'fa-helicopter',
                'image'       => 'assets/img/game-tapnich-thumb.jpg',
                'difficulty'  => 'Casual - Hardcore',
                'status'      => 'active',
                'play_url'    => base_url('mini-game/tapnich'),
                'description' => 'Kendalikan drone multimedia melintasi rintangan studio broadcast! Kumpulkan rol film footage berharga, atur tingkat kesulitan, sesuaikan kecepatan, dan pilih karakter drone favoritmu!',
                'features'    => [
                    'Responsive Vertical & Horizontal Canvas',
                    'Kustomisasi Karakter Drone & Nama',
                    'Pilihan Difficulty & Kecepatan',
                    'Collectible Rol Film (Footage Score)',
                    'Audio SFX Cerdas & No Alert Game Over'
                ]
            ],
            [
                'id'          => 'quiz',
                'title'       => 'Quiz MMC',
                'subtitle'    => 'Single Player & 2-Player Duel Receh MMC',
                'category'    => 'Trivia, Humor & Sejarah MMC',
                'badge'       => 'New Game #4',
                'badge_color' => 'purple',
                'icon'        => 'fa-brain',
                'image'       => 'assets/img/game-quiz-thumb.jpg',
                'difficulty'  => 'Mudah - WNI (100 Soal)',
                'status'      => 'active',
                'play_url'    => base_url('mini-game/quiz'),
                'description' => 'Adu wawasan kuis receh, satir kasus Indonesia, istilah Gen-Z, IT/kamera, dan sejarah Multimedia Club SMAN 1 Tamansari. Main solo atau duel multiplayer ganti-gantian!',
                'features'    => [
                    'Single Player & 2-Player Pass & Play',
                    '4 Level: Mudah (10) s/d WNI (100 Soal)',
                    'Sistem Nyawa / Heal (3x s/d 10x)',
                    'Fitur Bantuan 50:50 Lifeline (3x)',
                    'Rolling Acak Soal & Pilihan Jawaban'
                ]
            ],
            [
                'id'          => 'pelari-kalcer',
                'title'       => 'Pelari Kalcer: SMANIT Skena Runner',
                'subtitle'    => 'Endless Side-Scroller Skena & Lorong Multimedia',
                'category'    => 'Arcade Runner & Skena SMANIT',
                'badge'       => 'New Game #5',
                'badge_color' => 'success',
                'icon'        => 'fa-person-running',
                'image'       => 'assets/img/game-runner-thumb.jpg',
                'difficulty'  => 'Casual - Hardcore',
                'status'      => 'active',
                'play_url'    => base_url('mini-game/pelari-kalcer'),
                'description' => 'Game pelari tanpa akhir (endless runner) khas anak Multimedia SMAN 1 Tamansari! Lompat melewati tripod, kabel kusut, dan kucing oren, nunduk menghindari drone & boom mic, serta kumpulkan SD Card & kopi kalcer!',
                'features'    => [
                    'Lompat, Double Jump & Slide / Nunduk',
                    '5 Karakter Kalcer Multimedia Unik',
                    'Dynamic Sky: Pagi, Sunset & Malam Neon',
                    'Power-Up Kopi Skena & Baterai Shield',
                    'Mobile Touch Controls & Retro SFX'
                ]
            ],
            [
                'id'          => 'menggambar',
                'title'       => 'Studio Gambar & Tebak Gambar MMC',
                'subtitle'    => 'Single Player Studio & 2-Player Duel Tebak Gambar',
                'category'    => 'Seni Digital & Pictionary Duel',
                'badge'       => 'New Game #6',
                'badge_color' => 'pink',
                'icon'        => 'fa-palette',
                'image'       => 'assets/img/game-draw-thumb.jpg',
                'difficulty'  => 'Semua Umur / Kreatif',
                'status'      => 'active',
                'play_url'    => base_url('mini-game/menggambar'),
                'description' => 'Mini game kanvas interaktif dengan 2 mode seru: Single Player (menggambar bebas, mewarnai sketsa, stempel clipart & ekspor PNG) dan 2-Player Multiplayer (Tebak Gambar bergantian dengan timer & petunjuk kata)!',
                'features'    => [
                    'Mode Single (Kanvas Bebas & Buku Mewarnai)',
                    'Mode Multiplayer (Tebak Gambar 2-Player)',
                    'Sistem Giliran, Timer & Bank Kata Luas',
                    'Kuas Halus, Neon Glow, Spray & Flood Fill',
                    'Ekspor PNG High-Res & Poin Ranking (+5 pts)'
                ]
            ],
            [
                'id'          => 'aku-hacker',
                'title'       => 'Aku Hacker: Cyber Simulator',
                'subtitle'    => 'Retro Terminal, Password Cracker, Decryptor & Network Infiltrator',
                'category'    => 'Cybersecurity & Logic Simulator',
                'badge'       => 'New Game #7',
                'badge_color' => 'success',
                'icon'        => 'fa-user-secret',
                'image'       => 'assets/img/game-hacker-thumb.jpg',
                'difficulty'  => 'Level 1 - 10 (Cyber Elite)',
                'status'      => 'active',
                'play_url'    => base_url('mini-game/aku-hacker'),
                'description' => 'Simulasi peretasan & cybersecurity interaktif bertema retro terminal neon. Pecahkan hash password, dekripsi sandi rahasia, jalankan perintah terminal Linux, dan susupi peta jaringan server!',
                'features'    => [
                    'Password Cracker (MD5, SHA1, SHA256)',
                    'Encryption & Decrypt Tools (Base64, AES, Caesar)',
                    'Interactive Linux Hacker Terminal CLI',
                    'Interactive Network Visualizer Map',
                    'Misi Bertingkat & Poin Ranking (+5 pts)'
                ]
            ]
        ];

        return view('App\Modules\Game\Views\index', [
            'title' => 'Mini Games & Lab Interaktif Multimedia Club',
            'games' => $games,
        ]);
    }

    /**
     * Mini Game #1: Exposure Triangle Simulator
     */
    public function exposureTriangle()
    {
        return view('App\Modules\Game\Views\exposure_triangle', [
            'title'       => 'Exposure Triangle Simulator - The Holy Trinity Kamera | MMC',
            'pageTitle'   => 'Exposure Triangle Simulator',
            'description' => 'Simulasi interaktif memahami Segitiga Exposure: Aperture, Shutter Speed, dan ISO dalam fotografi dan videografi.',
        ]);
    }

    /**
     * Mini Game #2: IDE Simulator & Code Lab
     */
    public function ideSimulator()
    {
        return view('App\Modules\Game\Views\ide_simulator', [
            'title'       => 'IDE Simulator & Live Code Runner - Divisi Programming | MMC',
            'pageTitle'   => 'IDE Simulator & Code Lab',
            'description' => 'Mini game simulator coding interaktif untuk divisi programming. Tulis kode multi-bahasa, eksekusi live output, coba template web & tipe data, serta selesaikan quest coding step-by-step.',
        ]);
    }

    /**
     * Mini Game #3: Tapnich (Drone Aerial)
     */
    public function tapnich()
    {
        return view('App\Modules\Game\Views\tapnich', [
            'title'       => 'Tapnich: Drone Aerial - Mini Game Side-Scroller | MMC',
            'pageTitle'   => 'Tapnich Drone Aerial',
            'description' => 'Game side-scroller drone multimedia interaktif. Kendalikan drone, kumpulkan footage rol film, atur tingkat kesulitan dan kecepatan, serta capai skor tertinggi!',
        ]);
    }

    /**
     * Mini Game #4: Quiz MMC (Single & 2-Player Duel)
     */
    public function quiz()
    {
        return view('App\Modules\Game\Views\quiz', [
            'title'       => 'Quiz MMC - Single & 2-Player Duel | MMC',
            'pageTitle'   => 'Quiz MMC',
            'description' => 'Game kuis interaktif seru & receh dengan mode Single Player dan 2-Player Duel. Uji pengetahuanmu seputar WNI satir, Gen Z, teknologi kamera/komputer, dan sejarah ekstrakurikuler Multimedia Club SMAN 1 Tamansari!',
        ]);
    }

    /**
     * Mini Game #5: Pelari Kalcer (Endless Skena Runner)
     */
    public function pelariKalcer()
    {
        return view('App\Modules\Game\Views\pelari_kalcer', [
            'title'       => 'Pelari Kalcer - Endless Skena Runner | Multimedia Club SMANIT',
            'pageTitle'   => 'Pelari Kalcer',
            'description' => 'Game pelari tanpa akhir (endless runner) khas anak Multimedia SMAN 1 Tamansari! Lompat melewati rintangan, nunduk menghindari drone, dan kumpulkan SD Card serta kopi kalcer sebanyak mungkin!',
        ]);
    }

    /**
     * Mini Game #6: Studio Gambar & Mewarnai MMC
     */
    public function menggambar()
    {
        return view('App\Modules\Game\Views\menggambar', [
            'title'       => 'Studio Gambar & Mewarnai - Mini Game Kreatif | Multimedia Club',
            'pageTitle'   => 'Studio Gambar & Mewarnai MMC',
            'description' => 'Kanvas lukis digital interaktif untuk menggambar bebas, mewarnai sketsa multimedia, stempel clipart, cat tumpah flood fill, kuas neon, dan unduh hasil karyamu!',
        ]);
    }

    /**
     * Mini Game #7: Aku Hacker (Cyber Hacker Simulator)
     */
    public function akuHacker()
    {
        return view('App\Modules\Game\Views\aku_hacker', [
            'title'       => 'Aku Hacker: Cyber Hacker Simulator | Multimedia Club',
            'pageTitle'   => 'Cyber Hacker Simulator',
            'description' => 'Simulasi cybersecurity interaktif bergaya retro green terminal. Pecahkan hash password, dekripsi ciphertext rahasia, eksekusi perintah terminal shell, dan tembus node jaringan server!',
        ]);
    }

    /**
     * AJAX Record Challenge Score & Ranking Points (+5 pts, max 25 pts/day)
     */
    public function recordScore(): ResponseInterface
    {
        if (!session()->get('is_logged_in')) {
            return $this->response->setJSON([
                'status'  => 'guest',
                'message' => 'Skor tersimpan di sesi lokal perangkat Anda. Silakan login untuk menyimpan di profil akun!',
            ]);
        }

        $userId = (int) session()->get('user_id');
        $gameId = (string) ($this->request->getPost('game_id') ?: 'exposure-triangle');
        $level  = (int) ($this->request->getPost('level') ?: 1);
        $score  = (int) ($this->request->getPost('score') ?: 100);
        $stars  = (int) ($this->request->getPost('stars') ?: 3);

        $today = date('Y-m-d');
        $todayCount = $this->db->table('mini_game_logs')
            ->where('user_id', $userId)
            ->where("DATE(created_at)", $today)
            ->countAllResults();

        // 5 pts per game, max 25 pts per day (5 games)
        $pointsAwarded = ($todayCount < 5) ? 5 : 0;

        $this->db->table('mini_game_logs')->insert([
            'user_id'        => $userId,
            'game_id'        => $gameId,
            'level'          => $level,
            'score'          => $score,
            'stars'          => $stars,
            'points_awarded' => $pointsAwarded,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        $todayTotalPoints = min(25, ($todayCount + 1) * 5);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => $pointsAwarded > 0 
                ? "Selamat! Anda mendapatkan +{$pointsAwarded} Poin Ranking MM ({$todayTotalPoints}/25 Poin hari ini)!" 
                : "Skor tantangan tersimpan! (Batas harian 25 Poin mini games hari ini telah tercapai).",
            'data'    => [
                'user_id'              => $userId,
                'game_id'              => $gameId,
                'level'                => $level,
                'score'                => $score,
                'stars'                => $stars,
                'points_awarded'       => $pointsAwarded,
                'today_points'         => $todayTotalPoints,
                'daily_limit_reached'  => ($todayCount >= 5),
            ]
        ]);
    }
}
