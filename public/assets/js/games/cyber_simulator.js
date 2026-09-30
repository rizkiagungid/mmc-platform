/**
 * CYBER HACKER SIMULATOR - 20 MISSIONS, DEFACING STUDIO & ASMR SOUND ENGINE
 */
const HackerApp = (function() {
    // -------------------------------------------------------------
    // Audio Synthesis (Web Audio API) with Rich ASMR Cyber SFX
    // -------------------------------------------------------------
    let audioCtx = null;
    let soundEnabled = true;

    function initAudio() {
        try {
            if (!audioCtx) {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (AudioContext) audioCtx = new AudioContext();
            }
            if (audioCtx && audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
        } catch (e) {
            console.warn('AudioContext init error:', e);
        }
    }

    function playTone(freq, type = 'sine', duration = 0.08, gainVal = 0.05) {
        if (!soundEnabled) return;
        try {
            initAudio();
            if (!audioCtx) return;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = type;
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            gain.gain.setValueAtTime(gainVal, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + duration);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        } catch (e) {}
    }

    function sfxClick() { 
        playTone(950 + Math.random() * 250, 'triangle', 0.04, 0.07); 
    }
    function sfxTab() {
        playTone(1250, 'sine', 0.05, 0.08);
        setTimeout(() => playTone(1650, 'sine', 0.06, 0.06), 40);
    }
    function sfxRadar() {
        if (!soundEnabled) return;
        playTone(1800, 'sine', 0.15, 0.07);
        setTimeout(() => playTone(1200, 'sine', 0.25, 0.05), 80);
    }
    function sfxModem() {
        if (!soundEnabled) return;
        playTone(550, 'sawtooth', 0.06, 0.05);
        setTimeout(() => playTone(880, 'square', 0.07, 0.04), 70);
        setTimeout(() => playTone(1420, 'sine', 0.12, 0.06), 150);
    }
    function sfxLaser() {
        if (!soundEnabled) return;
        try {
            initAudio();
            if (!audioCtx) return;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(1400, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(200, audioCtx.currentTime + 0.16);
            gain.gain.setValueAtTime(0.09, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.18);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.18);
        } catch (e) {}
    }
    function sfxDecrypted() {
        if (!soundEnabled) return;
        try {
            initAudio();
            if (!audioCtx) return;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(320, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(1750, audioCtx.currentTime + 0.22);
            gain.gain.setValueAtTime(0.09, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.25);
        } catch (e) {}
    }
    function sfxSuccess() {
        sfxDecrypted();
    }
    function sfxMatrixRattle() {
        if (!soundEnabled) return;
        for (let i = 0; i < 4; i++) {
            setTimeout(() => { playTone(1100 + Math.random() * 600, 'square', 0.02, 0.03); }, i * 35);
        }
    }
    function sfxCrackHit() { playTone(1350 + Math.random() * 450, 'square', 0.025, 0.035); }
    function sfxRootDrop() {
        if (!soundEnabled) return;
        try {
            initAudio();
            if (!audioCtx) return;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(180, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(45, audioCtx.currentTime + 0.35);
            gain.gain.setValueAtTime(0.18, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.38);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.38);
        } catch (e) {}
    }
    function sfxVictoryFanfare() {
        if (!soundEnabled) return;
        playTone(523.25, 'sine', 0.12, 0.08);
        setTimeout(() => playTone(659.25, 'sine', 0.12, 0.08), 110);
        setTimeout(() => playTone(783.99, 'sine', 0.15, 0.09), 220);
        setTimeout(() => playTone(1046.50, 'triangle', 0.28, 0.12), 330);
        setTimeout(() => playTone(1318.51, 'sine', 0.38, 0.10), 450);
    }
    function sfxAlert() {
        playTone(350, 'sawtooth', 0.1, 0.08);
        setTimeout(() => playTone(280, 'sawtooth', 0.15, 0.08), 100);
    }

    // -------------------------------------------------------------
    // COMPLETE 20 DIVERSE MISSIONS (RED TEAM & WHITE HAT)
    // -------------------------------------------------------------
    const MISSIONS = [
        // --- 10 RED TEAM MISSIONS ---
        {
            id: 1,
            role: "red",
            title: "Operation Backdoor (WiFi SMANIT)",
            category: "WiFi Infiltration",
            targetIP: "192.168.1.1",
            secLevel: "LEVEL 1",
            reward: 50,
            status: "Ready",
            cipherText: "VGFyZ2V0IEhhc2g6IDVmNGRjYzNiNWFhNzY1ZDYxZDgzMjdkZWI4ODJjZjk5",
            cipherMethod: "Base64",
            cipherKey: "",
            targetHash: "5f4dcc3b5aa765d61d8327deb882cf99",
            hashType: "MD5",
            attackMethod: "Dictionary",
            crackedPlain: "password",
            desc: "Pihak router sekolah menyimpan otentikasi di token Base64. Lakukan 8 tahap infiltrasi untuk mendapatkan akses administrator penuh!",
            hint: "1. Recon &rarr; 2. WAF Bypass &rarr; 3. Dump Vault &rarr; 4. Decrypt Base64 &rarr; 5. Profiling MD5 &rarr; 6. Crack &rarr; 7. Root Shell &rarr; 8. Clear",
            edu: "Jangan gunakan enkripsi usang atau password default pada router. Di dunia nyata selalu ganti password default admin dan aktifkan WPA3."
        },
        {
            id: 2,
            role: "red",
            title: "Web Defacement Demo (Portal Sekolah)",
            category: "Web Defacing",
            targetIP: "192.168.1.50",
            secLevel: "LEVEL 2",
            reward: 65,
            status: "Ready",
            cipherText: "VGFyZ2V0IEhhc2g6IDBkMTAyNmU5ZjAyNGZhZGFhNmM5NjBhOTRkZDYxNDhh",
            cipherMethod: "Base64",
            cipherKey: "",
            targetHash: "0d1026e9f024fadaa6c960a94dd6148a",
            hashType: "MD5",
            attackMethod: "Dictionary",
            crackedPlain: "smanit_hebat",
            desc: "Uji penetrasi celah upload web shell pada portal sekolah. Dapatkan akses untuk mengganti index tampilan web!",
            hint: "1. Scan Port 80 &rarr; 2. Injeksi Web Shell &rarr; 3. Decrypt Base64 &rarr; 4. Crack MD5 &rarr; 5. Buka Deface Studio!",
            edu: "Web Defacing terjadi karena celah File Upload tak tervalidasi atau SQL Injection. Selalu sanitasi ekstensi file & simpan file di luar root web."
        },
        {
            id: 3,
            role: "red",
            title: "Data Exfiltration (Database Nilai Siswa)",
            category: "Database Breach",
            targetIP: "151.154.59.54",
            secLevel: "LEVEL 2",
            reward: 75,
            status: "Ready",
            cipherText: "Whghu Wdujhw: ghfc3f6280456c60def94e2264c1264c",
            cipherMethod: "Caesar",
            cipherKey: "3",
            targetHash: "dead0c3250123f30abc61b9931f8931f",
            hashType: "MD5",
            attackMethod: "Rainbow Table",
            crackedPlain: "admin_mmc",
            desc: "Paket rahasia disadap dari server nilai raport terenkripsi Caesar Cipher Shift 3. Eksekusi rantai serangan siber untuk membongkar shadow database!",
            hint: "1. Recon &rarr; 2. WAF Bypass &rarr; 3. Dump Vault &rarr; 4. Decrypt Caesar (3) &rarr; 5. MD5 Profile &rarr; 6. Rainbow Table &rarr; 7. Root",
            edu: "Database kredensial wajib di-hash menggunakan algoritma modern (Bcrypt/Argon2id) ditambah unique Salt, bukan MD5 polos yang rentan Rainbow Table."
        },
        {
            id: 4,
            role: "red",
            title: "CCTV Studio Broadcast Hijack",
            category: "IoT / Surveillance",
            targetIP: "47.247.233.138",
            secLevel: "LEVEL 3",
            reward: 100,
            status: "Ready",
            cipherText: "e2E1ZmY0NTQ3MGExOTc4N2ExYTY4OTM1OTM4MTgyYmQ4ZWIyODhiNH0=",
            cipherMethod: "Base64",
            cipherKey: "",
            targetHash: "a5ff45470a19787a1a68935938182bd8eb288b4",
            hashType: "SHA1",
            attackMethod: "Brute Force",
            crackedPlain: "cyber2026",
            desc: "Kamera studio streaming terkunci dengan hashing SHA1. Lakukan rantai serangan siber lengkap untuk membajak feed broadcast live!",
            hint: "1. Recon &rarr; 2. Proxy Pivot &rarr; 3. RTSP Injection &rarr; 4. Base64 &rarr; 5. SHA1 &rarr; 6. Brute Force &rarr; 7. Root",
            edu: "Perangkat IoT kamera sering kali tidak dipisah dari jaringan publik. Selalu isolasi perangkat IoT pada VLAN terpisah dengan firewall ketat."
        },
        {
            id: 5,
            role: "red",
            title: "Server Ujian Online CBT Firewall Breach",
            category: "Enterprise CBT",
            targetIP: "10.0.4.88",
            secLevel: "LEVEL 4",
            reward: 125,
            status: "Ready",
            cipherText: "ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f",
            cipherMethod: "AES",
            cipherKey: "smanit_cbt",
            targetHash: "ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f",
            hashType: "SHA256",
            attackMethod: "Dictionary",
            crackedPlain: "zero_day_root",
            desc: "Firewall ujian CBT dilindungi enkripsi AES (Key: smanit_cbt) dan hashing SHA256. Pecahkan hash untuk bypass firewall pengawas ujian!",
            hint: "1. Recon &rarr; 2. WAF Bypass &rarr; 3. SQL Injection &rarr; 4. AES (smanit_cbt) &rarr; 5. SHA256 &rarr; 6. Dictionary &rarr; 7. Root",
            edu: "Enkripsi AES memerlukan manajemen secret key yang aman. Jangan pernah menaruh kunci rahasia di dalam kode frontend/JavaScript!"
        },
        {
            id: 6,
            role: "red",
            title: "Smart Gate & RFID Access Controller",
            category: "Physical Security",
            targetIP: "192.168.10.15",
            secLevel: "LEVEL 4",
            reward: 135,
            status: "Ready",
            cipherText: "Wnfljy Mfxm: h7787321e053a8f595df876b6e4e7e6f",
            cipherMethod: "Caesar",
            cipherKey: "5",
            targetHash: "c7787321e053a8f595df876b6e4e7e6f",
            hashType: "MD5",
            attackMethod: "Rainbow Table",
            crackedPlain: "gate_unlock_2026",
            desc: "Gerbang otomatis sekolah dikontrol oleh modul mikroprosesor RFID. Dekripsikan Caesar Shift 5 untuk membuka akses palang pintu!",
            hint: "1. Caesar Shift: 5 &rarr; 2. Decrypt &rarr; 3. MD5 Rainbow Table &rarr; 4. Root Gerbang",
            edu: "Sistem akses fisik seperti RFID controller harus diautentikasi dua arah (*mutual authentication*) untuk mencegah replikasi sinyal."
        },
        {
            id: 7,
            role: "red",
            title: "Social Engineering Phishing Leak",
            category: "Phishing Probe",
            targetIP: "103.84.200.12",
            secLevel: "LEVEL 5",
            reward: 150,
            status: "Ready",
            cipherText: "VGFyZ2V0IEhhc2g6IDhlYzJjZTFhNGI4NWNhMmU4ODg0YmNmODgyMDVlMWNk",
            cipherMethod: "Base64",
            cipherKey: "",
            targetHash: "8ec2ce1a4b85ca2e8884bcf88205e1cd",
            hashType: "MD5",
            attackMethod: "Rainbow Table",
            crackedPlain: "mmc_godmode",
            desc: "Dokumen bocor dari domain phishing gelap. Dekripsikan data target untuk menemukan hash admin phisher dan matikan server jahat mereka!",
            hint: "1. Recon &rarr; 2. WAF &rarr; 3. Memory Dump &rarr; 4. Base64 &rarr; 5. MD5 &rarr; 6. Rainbow &rarr; 7. Root Escalation",
            edu: "Phishing menargetkan faktor manusia. Selalu periksa URL domain pengirim email dan jangan klik tautan mencurigakan."
        },
        {
            id: 8,
            role: "red",
            title: "Darkweb MMC Secret Vault Archives",
            category: "Darkweb Node",
            targetIP: "198.51.100.77",
            secLevel: "LEVEL 6",
            reward: 180,
            status: "Ready",
            cipherText: "Gnetrg Unfu: c8538740c06a86c67ef991c01e38c47b59e5ee50e2060126a27e7f12e9b8971f",
            cipherMethod: "Caesar",
            cipherKey: "13",
            targetHash: "c8538740c06a86c67ef991c01e38c47b59e5ee50e2060126a27e7f12e9b8971f",
            hashType: "SHA256",
            attackMethod: "Brute Force",
            crackedPlain: "shadow_infiltrator",
            desc: "Vault tersembunyi berformat Caesar ROT-13 yang menyimpan file raw master dokumentasi MMC. Bongkar SHA256 untuk membuka arsip terlarang!",
            hint: "1. Recon &rarr; 2. Relay &rarr; 3. Zero-Day &rarr; 4. Caesar (13) &rarr; 5. SHA256 &rarr; 6. Brute Force &rarr; 7. Root",
            edu: "Arsip penting wajib dienkripsi di level filesystem (*at-rest encryption*) dengan proteksi access control berbasis peran (RBAC)."
        },
        {
            id: 9,
            role: "red",
            title: "Judol Banner Injection Simulation",
            category: "Injection Audit",
            targetIP: "182.253.110.88",
            secLevel: "LEVEL 7",
            reward: 200,
            status: "Ready",
            cipherText: "69d30da4b85ca2e8884bcf88205e1cd",
            cipherMethod: "AES",
            cipherKey: "slot_gacor_bypass",
            targetHash: "69d30da4b85ca2e8884bcf88205e1cd",
            hashType: "MD5",
            attackMethod: "Rainbow Table",
            crackedPlain: "slot_injected_root",
            desc: "Simulasi audit peretasan website instansi yang disusupi iklan judol slot gacor. Dapatkan akses shell untuk mengaudit injeksi script liar.",
            hint: "1. AES Key: slot_gacor_bypass &rarr; 2. Decrypt &rarr; 3. MD5 Rainbow Table &rarr; 4. Audit Shell",
            edu: "Injeksi judol pada situs pemerintah/sekolah biasanya terjadi karena CMS usang (Wordpress tanpa update plugin). Rutin update CMS & audit database."
        },
        {
            id: 10,
            role: "red",
            title: "Root Master Cyber Fortress (Satir Kominfo)",
            category: "Top Tier Boss",
            targetIP: "root@cyber.fortress.id",
            secLevel: "LEVEL 8 (MASTER)",
            reward: 300,
            status: "Ready",
            cipherText: "3b719468910e53a2963665ccda2c8b746a51270dc5824c9eb9236d9361ad22f0",
            cipherMethod: "AES",
            cipherKey: "password_terkuat_123",
            targetHash: "3b719468910e53a2963665ccda2c8b746a51270dc5824c9eb9236d9361ad22f0",
            hashType: "SHA256",
            attackMethod: "Rainbow Table",
            crackedPlain: "satir_keamanan_100persen",
            desc: "Target puncak cyber fortress dengan password legendaris 'password_terkuat_123'. Ambil alih seluruh sistem dan jadilah Legenda Hacker MMC!",
            hint: "1. Recon &rarr; 2. WAF &rarr; 3. Buffer Overflow &rarr; 4. AES &rarr; 5. SHA256 &rarr; 6. Rainbow &rarr; 7. Root Master",
            edu: "Keamanan siber bukan jaminan 100% instan, melainkan proses berkelanjutan (*Defense in Depth*) yang melibatkan enkripsi, WAF, audit, dan SDM."
        },

        // --- 10 WHITE HAT (DEFENSIVE / RECOVERY) MISSIONS ---
        {
            id: 11,
            role: "white",
            title: "Web Deface Emergency Recovery (Portal OSIS)",
            category: "Incident Response",
            targetIP: "192.168.1.100",
            secLevel: "DEFENSE 1",
            reward: 80,
            status: "Ready",
            cipherText: "QmFja3VwIEhhc2g6IDIzZDEwMjZlOWYwMjRmYWRhYTZjOTYwYTk0ZGQ2MTRh",
            cipherMethod: "Base64",
            cipherKey: "",
            targetHash: "23d1026e9f024fadaa6c960a94dd614a",
            hashType: "MD5",
            attackMethod: "Dictionary",
            crackedPlain: "restore_clean_2026",
            desc: "Website OSIS SMANIT terkena aksi defacing! Bersihkan web shell, dekripsi master backup key, dan pulihkan tampilan portal ke versi bersih.",
            hint: "1. Buka Deface Studio &rarr; Pilih Mode Recovery &rarr; Bersihkan Shell &rarr; Restore index.html &rarr; Patch WAF!",
            edu: "Saat terjadi insiden defacing, segera isolasi server, analisa akses log untuk menemukan celah masuk, lalu pulihkan file dari repositori Git bersih."
        },
        {
            id: 12,
            role: "white",
            title: "Crypto Ransomware Key Recovery (Lab MM)",
            category: "Malware Forensics",
            targetIP: "172.16.254.19",
            secLevel: "DEFENSE 2",
            reward: 110,
            status: "Ready",
            cipherText: "U0hBMSBLRVk6IDcwZGFkYmYyYjRjYWY3ZDM4NzNhOGM3YzNhZDI4YWY2OWI4MWM3OGU=",
            cipherMethod: "Base64",
            cipherKey: "",
            targetHash: "70dadbf2b4caf7d3873a8c7c3ad28af69b81c78e",
            hashType: "SHA1",
            attackMethod: "Rainbow Table",
            crackedPlain: "decrypt_key_99",
            desc: "Laboratorium komputer terkena ransomware simulasi. Dekripsikan master payload Base64 dan bongkar kunci privat SHA1 untuk memulihkan seluruh PC!",
            hint: "1. Recon &rarr; 2. Tunnel &rarr; 3. Payload Leak &rarr; 4. Base64 &rarr; 5. SHA1 &rarr; 6. Rainbow Table &rarr; 7. Root",
            edu: "Menerapkan backup 3-2-1 (3 salinan data, 2 media berbeda, 1 lokasi off-site/cloud terisolasi) adalah pertahanan terbaik melawan ransomware."
        },
        {
            id: 13,
            role: "white",
            title: "Judol Slot Script Cleanup & DB Sanitizing",
            category: "Database Defense",
            targetIP: "103.11.200.45",
            secLevel: "DEFENSE 3",
            reward: 120,
            status: "Ready",
            cipherText: "VGFyZ2V0IEhhc2g6IGFmM2MyNDY1ZDYxZDgzMjdkZWI4ODJjZjk5MTI4ZGMz",
            cipherMethod: "Base64",
            cipherKey: "",
            targetHash: "af3c2465d61d8327deb882cf99128dc3",
            hashType: "MD5",
            attackMethod: "Dictionary",
            crackedPlain: "clean_db_admin",
            desc: "Bersihkan ribuan tautan tersembunyi judol dari tabel database sekolah dan tambal celah SQL Injection agar tidak disusupi kembali.",
            hint: "1. Base64 Decrypt &rarr; 2. MD5 Dictionary &rarr; 3. Jalankan Terminal SQL Sanitizer &rarr; 4. Selesai",
            edu: "Gunakan parameterized queries (PDO prepared statement) dan nonaktifkan eksekusi PHP pada direktori upload untuk mencegah backdoor."
        },
        {
            id: 14,
            role: "white",
            title: "DDoS Traffic Mitigation & Rate Limiting",
            category: "Traffic Shield",
            targetIP: "104.21.45.10",
            secLevel: "DEFENSE 4",
            reward: 140,
            status: "Ready",
            cipherText: "Wnfljy: d8898321e053a8f595df876b6e4e7e6f",
            cipherMethod: "Caesar",
            cipherKey: "5",
            targetHash: "d8898321e053a8f595df876b6e4e7e6f",
            hashType: "MD5",
            attackMethod: "Rainbow Table",
            crackedPlain: "rate_limit_active",
            desc: "Server pendaftaran PPDB dibanjiri jutaan request botnet DDoS. Terapkan Cloudflare rate-limiting rules dan blokir bot IP liar!",
            hint: "1. Caesar Shift: 5 &rarr; 2. Decrypt &rarr; 3. MD5 Rainbow Table &rarr; 4. Aktifkan WAF Rate Limiting",
            edu: "DDoS mitigation membutuhkan Anycast network dan web application firewall (WAF) yang mampu memfilter SYN flood & HTTP flood."
        },
        {
            id: 15,
            role: "white",
            title: "Phishing Fake Domain Takedown",
            category: "Anti-Fraud",
            targetIP: "45.33.32.156",
            secLevel: "DEFENSE 5",
            reward: 160,
            status: "Ready",
            cipherText: "ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f",
            cipherMethod: "AES",
            cipherKey: "phish_takedown_key",
            targetHash: "ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f",
            hashType: "SHA256",
            attackMethod: "Dictionary",
            crackedPlain: "domain_suspended",
            desc: "Ditemukan domain tiruan fake-sman1tamansari.com yang mencuri akun siswa. Lacak server C2 dan jalankan perintah takedown registrar!",
            hint: "1. AES Key: phish_takedown_key &rarr; 2. Decrypt &rarr; 3. SHA256 Dictionary &rarr; 4. Takedown Domain",
            edu: "Lindungi reputasi domain dengan mengaktifkan DMARC, SPF, dan DKIM pada server email agar tidak bisa di-spoofing oleh penipu."
        },
        {
            id: 16,
            role: "white",
            title: "Rogue AP / Evil Twin WiFi Defense",
            category: "Wireless Defense",
            targetIP: "192.168.1.254",
            secLevel: "DEFENSE 6",
            reward: 175,
            status: "Ready",
            cipherText: "V0hJVEUgSEFUIFdJRkk6IDcwZGFkYmYyYjRjYWY3ZDM4NzNhOGM3YzNhZDI4YWY2OWI4MWM3OGU=",
            cipherMethod: "Base64",
            cipherKey: "",
            targetHash: "70dadbf2b4caf7d3873a8c7c3ad28af69b81c78e",
            hashType: "SHA1",
            attackMethod: "Rainbow Table",
            crackedPlain: "wpa3_enterprise_ok",
            desc: "Terdeteksi access point palsu bernama 'WiFi-SMANIT-Gratis' yang menyadap lalu lintas login siswa. Putus sinyal dan amankan SSID resmi!",
            hint: "1. Base64 &rarr; 2. SHA1 Rainbow &rarr; 3. Aktifkan WIDS (Wireless Intrusion Detection)",
            edu: "Jangan gunakan WiFi publik terbuka tanpa proteksi VPN. Jaringan resmi perusahaan/sekolah harus menerapkan 802.1X autentikasi per user."
        },
        {
            id: 17,
            role: "white",
            title: "Malware Memory Forensics & C2 Extraction",
            category: "DFIR Forensics",
            targetIP: "10.10.10.45",
            secLevel: "DEFENSE 7",
            reward: 190,
            status: "Ready",
            cipherText: "Gnetrg: c8538740c06a86c67ef991c01e38c47b59e5ee50e2060126a27e7f12e9b8971f",
            cipherMethod: "Caesar",
            cipherKey: "13",
            targetHash: "c8538740c06a86c67ef991c01e38c47b59e5ee50e2060126a27e7f12e9b8971f",
            hashType: "SHA256",
            attackMethod: "Brute Force",
            crackedPlain: "c2_server_blocked",
            desc: "Lakukan reverse engineering pada memory dump laptop guru yang terinfeksi trojan. Ekstrak IP Command & Control untuk diblokir permanen!",
            hint: "1. Caesar Shift: 13 &rarr; 2. SHA256 Brute Force &rarr; 3. Blokir C2 Domain",
            edu: "Digital Forensics & Incident Response (DFIR) memanfaatkan memory analysis untuk menemukan malware fileless yang bersembunyi di RAM."
        },
        {
            id: 18,
            role: "white",
            title: "Zero-Day Kernel Patching & Hardening",
            category: "Kernel Hardening",
            targetIP: "172.20.10.1",
            secLevel: "DEFENSE 8",
            reward: 210,
            status: "Ready",
            cipherText: "3b719468910e53a2963665ccda2c8b746a51270dc5824c9eb9236d9361ad22f0",
            cipherMethod: "AES",
            cipherKey: "kernel_patch_2026",
            targetHash: "3b719468910e53a2963665ccda2c8b746a51270dc5824c9eb9236d9361ad22f0",
            hashType: "SHA256",
            attackMethod: "Rainbow Table",
            crackedPlain: "kernel_hardened_ok",
            desc: "Audit celah Buffer Overflow CVE-2024 pada Linux kernel server sekolah. Terapkan kernel livepatch tanpa perlu mematikan layanan!",
            hint: "1. AES Key: kernel_patch_2026 &rarr; 2. SHA256 Rainbow &rarr; 3. Terapkan Kernel Patch",
            edu: "Terapkan prinsip least privilege (prinsip hak akses minimal) dan aktifkan SELinux/AppArmor untuk membatasi dampak exploit kernel."
        },
        {
            id: 19,
            role: "white",
            title: "API Credential Leakage Containment",
            category: "Cloud Security",
            targetIP: "10.0.50.88",
            secLevel: "DEFENSE 9",
            reward: 240,
            status: "Ready",
            cipherText: "V0hJVEUgSEFUIEFQSTogOGVjMmNlMWE0Yjg1Y2EyZTg4ODRiY2Y4ODIwNWUxY2Q=",
            cipherMethod: "Base64",
            cipherKey: "",
            targetHash: "8ec2ce1a4b85ca2e8884bcf88205e1cd",
            hashType: "MD5",
            attackMethod: "Rainbow Table",
            crackedPlain: "jwt_token_rotated",
            desc: "Kunci API cloud multimedia tidak sengaja terunggah ke repositori publik GitHub. Segera cabut token bocor dan rotasi secret JWT!",
            hint: "1. Base64 &rarr; 2. MD5 Rainbow Table &rarr; 3. Revoke Leaked API Key",
            edu: "Gunakan secret scanner (seperti GitGuardian / TruffleHog) pada pipeline CI/CD agar API token tidak pernah ter-commit ke public repository."
        },
        {
            id: 20,
            role: "white",
            title: "National CSIRT Cyber Defense Commander",
            category: "Top Tier Guardian",
            targetIP: "csirt.national.defense.id",
            secLevel: "DEFENSE 10 (COMMANDER)",
            reward: 350,
            status: "Ready",
            cipherText: "5f4dcc3b5aa765d61d8327deb882cf99a5ff45470a19787a1a68935938182bd8",
            cipherMethod: "AES",
            cipherKey: "indonesia_cyber_shield",
            targetHash: "5f4dcc3b5aa765d61d8327deb882cf99a5ff45470a19787a1a68935938182bd8",
            hashType: "SHA256",
            attackMethod: "Rainbow Table",
            crackedPlain: "garuda_cyber_defense_master",
            desc: "Puncak operasi pertahanan siber! Pimpin tim CSIRT nasional memitigasi gelombang serangan APT canggih dan amankan infrastruktur digital!",
            hint: "1. Recon &rarr; 2. WAF &rarr; 3. Dump &rarr; 4. AES (indonesia_cyber_shield) &rarr; 5. SHA256 &rarr; 6. Rainbow &rarr; 7. CSIRT Master",
            edu: "Selamat! Anda memahami esensi sejati keamanan siber: kolaborasi, integritas etika, dan dedikasi menjaga kedaulatan ruang digital bangsa."
        }
    ];

    let currentMissionIdx = 0;
    let playerXP = 0;
    let isCracking = false;
    let crackInterval = null;
    let currentHashType = 'MD5';
    let currentAttackMethod = 'Dictionary';
    let currentCipherMethod = 'Base64';
    let activeFilter = 'all';

    // 8-Stage Pipeline Tracker (0..7)
    let currentStage = 0;

    // Deface Studio State
    let studioMode = 'deface';
    let recoveryState = { shell: false, git: false, waf: false };

    const STAGE_LABELS = [
        "1. Jalankan Recon & Nmap",
        "2. Bypass Firewall & WAF",
        "3. Injeksi CVE & Dump Vault",
        "4. Dekripsi Kunci Sandi",
        "5. Analisis & Profiling Hash",
        "6. Jalankan Password Cracker",
        "7. Eskalasi Root Privilege",
        "8. Exfiltrasi Data & Klaim XP"
    ];

    // -------------------------------------------------------------
    // Helper & Cryptographic Utilities
    // -------------------------------------------------------------
    function rotCipher(str, shift, decrypt = false) {
        let s = parseInt(shift) || 3;
        if (decrypt) s = (26 - (s % 26)) % 26;
        return str.replace(/[a-zA-Z]/g, function(c) {
            const base = c <= 'Z' ? 65 : 97;
            return String.fromCharCode(((c.charCodeAt(0) - base + s) % 26) + base);
        });
    }

    function fakeAesTransform(str, key, decrypt = false) {
        if (!key) key = "KEY";
        let res = "";
        for (let i = 0; i < str.length; i++) {
            const charCode = str.charCodeAt(i);
            const keyChar = key.charCodeAt(i % key.length);
            res += String.fromCharCode(charCode ^ (keyChar % 5));
        }
        return res;
    }

    // -------------------------------------------------------------
    // Initialization
    // -------------------------------------------------------------
    function init() {
        setupGlobalAudioUnlock();
        renderMissions();
        updateHUD();
        setupEvents();
        initNetworkCanvas();
        startStatsFluctuation();
        updateStageUI();
    }

    function setupGlobalAudioUnlock() {
        // Unlock audio on any user interaction anywhere
        ['click', 'pointerdown', 'touchstart', 'keydown'].forEach(evt => {
            window.addEventListener(evt, () => {
                initAudio();
            }, { passive: true, once: false });
        });

        // Global capture listener to play click SFX on all buttons/tabs/cards
        document.addEventListener('click', (e) => {
            initAudio();
            const clickable = e.target.closest('button, .cyber-nav-btn, .btn-cyber, .btn-cyber-red, .btn-cyber-blue, .btn-cyber-amber, .quick-chip, .mission-card, .stage-step-item, a');
            if (clickable) {
                sfxClick();
            }
        }, { capture: true, passive: true });
    }

    function setupEvents() {
        // Tab switching event delegation
        document.addEventListener('click', (e) => {
            const navBtn = e.target.closest('#moduleNavTabs .cyber-nav-btn');
            if (navBtn) {
                const targetTab = navBtn.getAttribute('data-tab');
                if (targetTab) {
                    switchTab(targetTab);
                }
            }
        });

        const btnAudio = document.getElementById('btnAudioToggle');
        if (btnAudio) {
            btnAudio.addEventListener('click', function(e) {
                e.stopPropagation();
                soundEnabled = !soundEnabled;
                const icon = document.getElementById('audioIcon');
                if (soundEnabled) {
                    if (icon) icon.className = 'fa-solid fa-volume-high';
                    this.classList.remove('btn-cyber-red');
                    playTone(440, 'sine', 0.1);
                } else {
                    if (icon) icon.className = 'fa-solid fa-volume-xmark';
                    this.classList.add('btn-cyber-red');
                }
            });
        }

        const shellIn = document.getElementById('shellInput');
        if (shellIn) {
            shellIn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    execShellCommand();
                }
            });
        }
    }

    function openTutorialModal() {
        sfxClick();
        const el = document.getElementById('modalTutorial');
        if (el && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getOrCreateInstance(el);
            modal.show();
        }
    }

    function switchTab(tabId) {
        document.querySelectorAll('#moduleNavTabs .cyber-nav-btn').forEach(b => {
            b.classList.toggle('active', b.getAttribute('data-tab') === tabId);
        });

        document.querySelectorAll('.tab-pane-content').forEach(pane => {
            pane.classList.add('d-none');
        });

        const targetPane = document.getElementById('tab-' + tabId);
        if (targetPane) {
            targetPane.classList.remove('d-none');
        }

        sfxTab();
        logGlobal(`[VIEW SWITCH] Modul diaktifkan: ${tabId.toUpperCase()}`);

        if (tabId === 'network') {
            setTimeout(() => {
                resizeNetworkCanvas();
            }, 80);
        }
    }

    // -------------------------------------------------------------
    // FILTER & RENDER MISSIONS (20 MISSIONS)
    // -------------------------------------------------------------
    function filterMissions(filter) {
        activeFilter = filter;
        document.querySelectorAll('[data-filter]').forEach(b => {
            b.classList.toggle('active', b.getAttribute('data-filter') === filter);
        });
        renderMissions();
        sfxClick();
    }

    function renderMissions() {
        const container = document.getElementById('missionsContainer');
        if (!container) return;
        container.innerHTML = '';

        MISSIONS.forEach((m, idx) => {
            if (activeFilter === 'red' && m.role !== 'red') return;
            if (activeFilter === 'white' && m.role !== 'white') return;

            const isCurrent = idx === currentMissionIdx;
            const isCompleted = m.status === 'Completed';

            let roleBadge = m.role === 'red' 
                ? '<span class="badge bg-danger bg-opacity-25 text-danger font-monospace style-tiny me-1">🔴 RED</span>' 
                : '<span class="badge bg-info bg-opacity-25 text-info font-monospace style-tiny me-1">🛡️ WHITE</span>';

            let statusClass = isCompleted ? 'text-success' : 'text-warning';
            let statusIcon = isCompleted ? 'fa-check-double' : 'fa-play';

            const card = document.createElement('div');
            card.className = `mission-card ${isCurrent ? 'active' : ''}`;
            card.setAttribute('data-mission-idx', idx);
            card.onclick = () => selectMission(idx);

            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="text-white small">${roleBadge} #${m.id} ${m.title}</strong>
                    <span class="style-tiny font-monospace ${statusClass}">
                        <i class="fa-solid ${statusIcon} me-1"></i>${m.status}
                    </span>
                </div>
                <div class="d-flex justify-content-between small text-secondary style-tiny">
                    <span>IP: ${m.targetIP}</span>
                    <span class="text-success">${m.secLevel}</span>
                </div>
            `;
            container.appendChild(card);
        });

        const cur = MISSIONS[currentMissionIdx];
        if (!cur) return;

        const badgeEl = document.getElementById('missionProgressBadge');
        if (badgeEl) badgeEl.textContent = `Misi: ${currentMissionIdx + 1}/20`;
        
        const tagEl = document.getElementById('currentMissionTag');
        if (tagEl) tagEl.textContent = `MISSION #${cur.id}`;
        
        const catEl = document.getElementById('currentMissionCategory');
        if (catEl) catEl.textContent = cur.category;
        
        const titleEl = document.getElementById('currentMissionTitle');
        if (titleEl) titleEl.textContent = cur.title;
        
        const descEl = document.getElementById('currentMissionDesc');
        if (descEl) descEl.textContent = cur.desc;
        
        const rewardEl = document.getElementById('currentMissionReward');
        if (rewardEl) rewardEl.innerHTML = `<i class="fa-solid fa-coins"></i> +${cur.reward} XP`;
        
        const hintEl = document.getElementById('missionLiveHint');
        if (hintEl) hintEl.innerHTML = cur.hint;
        
        const eduEl = document.getElementById('currentMissionEdu');
        if (eduEl) eduEl.textContent = cur.edu;

        const roleEl = document.getElementById('currentMissionRole');
        if (roleEl) {
            if (cur.role === 'red') {
                roleEl.className = 'badge bg-danger text-white font-monospace ms-1';
                roleEl.textContent = '🔴 RED TEAM OFFENSIVE';
            } else {
                roleEl.className = 'badge bg-info text-dark font-monospace ms-1';
                roleEl.textContent = '🛡️ WHITE HAT DEFENSIVE';
            }
        }

        // Toggle Deface & Recovery Auto-Solve Shortcuts
        const defaceShortcut = document.getElementById('btnMissionDefaceShortcut');
        const recoveryShortcut = document.getElementById('btnMissionRecoveryShortcut');
        if (defaceShortcut) {
            if (cur.role === 'red' && (cur.id === 2 || cur.category.includes('Defac') || cur.category.includes('Injection'))) {
                defaceShortcut.classList.remove('d-none');
            } else {
                defaceShortcut.classList.add('d-none');
            }
        }
        if (recoveryShortcut) {
            if (cur.role === 'white' && (cur.id === 11 || cur.category.includes('Response') || cur.category.includes('Defense') || cur.category.includes('Recovery'))) {
                recoveryShortcut.classList.remove('d-none');
            } else {
                recoveryShortcut.classList.add('d-none');
            }
        }
    }

    function selectMission(idx) {
        if (idx < 0 || idx >= MISSIONS.length) return;
        currentMissionIdx = idx;
        currentStage = 0;
        resetNetworkMap();
        renderMissions();
        updateHUD();
        updateStageUI();
        sfxClick();
        logGlobal(`[TARGET SWITCH] Target dialihkan ke #${MISSIONS[idx].id}: ${MISSIONS[idx].targetIP} (${MISSIONS[idx].title})`);
    }

    function updateHUD() {
        const cur = MISSIONS[currentMissionIdx];
        if (!cur) return;
        const ipEl = document.getElementById('hudTargetIP');
        if (ipEl) ipEl.textContent = cur.targetIP;
        const secEl = document.getElementById('hudSecLevel');
        if (secEl) secEl.textContent = cur.secLevel;
        const scoreEl = document.getElementById('hudScore');
        if (scoreEl) scoreEl.textContent = playerXP;

        let rank = "Script Kiddie";
        if (playerXP >= 1500) rank = "CYBER LEGEND 👑";
        else if (playerXP >= 1000) rank = "National CSIRT Commander";
        else if (playerXP >= 600) rank = "Zero-Day Master";
        else if (playerXP >= 300) rank = "Cryptanalyst";
        else if (playerXP >= 150) rank = "Net Infiltrator";
        else if (playerXP >= 50) rank = "Packet Sniffer";
        const rankEl = document.getElementById('rankBadge');
        if (rankEl) rankEl.textContent = rank;
    }

    // -------------------------------------------------------------
    // DEFACEMENT & RECOVERY STUDIO ENGINE (MULTI-STAGE PUZZLES)
    // -------------------------------------------------------------
    let defaceProgress = { step1: false, step2: false, step3: false, step4: false };
    let recProgress = { step1: false, step2: false, step3: false, step4: false };

    function setStudioMode(mode) {
        studioMode = mode;
        const btnDeface = document.getElementById('btnStudioDeface');
        const btnRec = document.getElementById('btnStudioRecovery');
        const boxDeface = document.getElementById('defaceControlBox');
        const boxRec = document.getElementById('recoveryControlBox');
        const tag = document.getElementById('studioModeTag');

        if (mode === 'deface') {
            if (btnDeface) btnDeface.classList.add('active');
            if (btnRec) btnRec.classList.remove('active');
            if (boxDeface) boxDeface.classList.remove('d-none');
            if (boxRec) boxRec.classList.add('d-none');
            if (tag) {
                tag.textContent = 'Mode: Red Team Defacing';
                tag.className = 'cyber-tag danger';
            }
        } else {
            if (btnDeface) btnDeface.classList.remove('active');
            if (btnRec) btnRec.classList.add('active');
            if (boxDeface) boxDeface.classList.add('d-none');
            if (boxRec) boxRec.classList.remove('d-none');
            if (tag) {
                tag.textContent = 'Mode: White Hat Recovery';
                tag.className = 'cyber-tag info';
            }
        }
        sfxClick();
    }

    // --- Red Team Deface Methods ---
    function defaceVerifyStep1() {
        const val = parseInt((document.getElementById('defaceMathAnswer')?.value || '').trim());
        // 80 + 22 - X = 75 => 102 - 75 = 27
        if (val === 27) {
            defaceProgress.step1 = true;
            const b1 = document.getElementById('defaceBadge1');
            if (b1) { b1.className = "badge bg-success"; b1.textContent = "Bypassed ✓"; }
            const inp = document.getElementById('defaceMathAnswer');
            if (inp) inp.disabled = true;
            const btn = document.getElementById('btnDefaceStep1');
            if (btn) btn.disabled = true;

            // Unlock Step 2
            const box2 = document.getElementById('defaceStepBox2');
            if (box2) box2.classList.remove('opacity-60');
            const b2 = document.getElementById('defaceBadge2');
            if (b2) { b2.className = "badge bg-warning text-dark"; b2.textContent = "Pending"; }
            document.querySelectorAll('input[name="defaceSqliOption"]').forEach(r => r.disabled = false);
            const btn2 = document.getElementById('btnDefaceStep2');
            if (btn2) btn2.disabled = false;

            sfxSuccess();
            logGlobal(`[RED TEAM] Port firewall bypass calculated successfully! Offset X = 27.`);
        } else {
            sfxAlert();
            alert(`Kalkulasi port checksum salah! Hitung kembali: 80 + 22 - X = 75.`);
        }
    }

    function defaceVerifyStep2() {
        const selected = document.querySelector('input[name="defaceSqliOption"]:checked')?.value;
        if (!selected) {
            sfxAlert();
            alert('Silakan pilih salah satu opsi query SQL injection!');
            return;
        }

        if (selected === 'sqli') {
            defaceProgress.step2 = true;
            const b2 = document.getElementById('defaceBadge2');
            if (b2) { b2.className = "badge bg-success"; b2.textContent = "Injected ✓"; }
            document.querySelectorAll('input[name="defaceSqliOption"]').forEach(r => r.disabled = true);
            const btn2 = document.getElementById('btnDefaceStep2');
            if (btn2) btn2.disabled = true;

            // Unlock Step 3
            const box3 = document.getElementById('defaceStepBox3');
            if (box3) box3.classList.remove('opacity-60');
            const b3 = document.getElementById('defaceBadge3');
            if (b3) { b3.className = "badge bg-warning text-dark"; b3.textContent = "Pending"; }
            document.querySelectorAll('input[name="defaceMimeOption"]').forEach(r => r.disabled = false);
            const btn3 = document.getElementById('btnDefaceStep3');
            if (btn3) btn3.disabled = false;

            sfxLaser();
            logGlobal(`[RED TEAM] SQL Injection auth bypass granted! Session admin_root diekstrak.`);
        } else {
            sfxAlert();
            alert(`Payload yang dipilih salah! Vektor '${selected}' tidak dapat membypass autentikasi password admin.`);
        }
    }

    function defaceVerifyStep3() {
        const selected = document.querySelector('input[name="defaceMimeOption"]:checked')?.value;
        if (!selected) {
            sfxAlert();
            alert('Silakan pilih teknik MIME bypass untuk upload web shell!');
            return;
        }

        if (selected === 'magic') {
            defaceProgress.step3 = true;
            const b3 = document.getElementById('defaceBadge3');
            if (b3) { b3.className = "badge bg-success"; b3.textContent = "Uploaded ✓"; }
            document.querySelectorAll('input[name="defaceMimeOption"]').forEach(r => r.disabled = true);
            const btn3 = document.getElementById('btnDefaceStep3');
            if (btn3) btn3.disabled = true;

            // Unlock Step 4 (Designer & Execute)
            const box4 = document.getElementById('defaceStepBox4');
            if (box4) box4.classList.remove('opacity-60');
            const b4 = document.getElementById('defaceBadge4');
            if (b4) { b4.className = "badge bg-warning text-dark"; b4.textContent = "Siap Injeksi"; }
            const btnExec = document.getElementById('btnExecuteDeface');
            if (btnExec) btnExec.disabled = false;

            sfxMatrixRattle();
            logGlobal(`[RED TEAM] Web shell backdoor agent_relay.dat berhasil tertanam di /uploads/agent_relay.dat!`);
        } else {
            sfxAlert();
            alert('Teknik gagal! Backend menolak ekstensi yang tidak memiliki magic bytes gambar.');
        }
    }

    function injectDefacePayload(showAlert = true) {
        if (!defaceProgress.step1 || !defaceProgress.step2 || !defaceProgress.step3) {
            sfxAlert();
            alert('Selesaikan terlebih dahulu Tahap 1, 2, dan 3 sebelum meluncurkan defacement!');
            return;
        }

        const title = (document.getElementById('defaceTitle')?.value || '').trim() || "HACKED BY MMC CYBER TEAM";
        const name = (document.getElementById('defaceHackerName')?.value || '').trim() || "x-Shadow_MMC";
        const msg = (document.getElementById('defaceMessage')?.value || '').trim() || "Security is an illusion. Your defenses have fallen before the Multimedia Cyber Team!";
        const theme = document.getElementById('defaceVisualTheme')?.value || 'matrix';

        const defacedContainer = document.getElementById('defacedWebView');
        const lTitle = document.getElementById('liveDefaceTitle');
        const lName = document.getElementById('liveDefaceName');
        const lMsg = document.getElementById('liveDefaceMessage');
        const lIcon = document.getElementById('liveDefaceIcon');
        const lFooter = document.getElementById('liveDefaceFooter');

        if (lTitle) lTitle.textContent = title;
        if (lName) lName.textContent = "Greetz from: " + name;
        if (lMsg) lMsg.textContent = msg;

        // Apply Theme Styles
        if (defacedContainer) {
            if (theme === 'blood') {
                defacedContainer.style.background = '#1a0000';
                if (lTitle) { lTitle.style.color = '#ff1a1a'; lTitle.style.textShadow = '0 0 15px #ff0000'; }
                if (lName) lName.style.color = '#ff8080';
                if (lMsg) lMsg.style.borderColor = '#ff1a1a';
                if (lIcon) lIcon.innerHTML = '<i class="fa-solid fa-skull text-danger"></i>';
            } else if (theme === 'synthwave') {
                defacedContainer.style.background = '#120424';
                if (lTitle) { lTitle.style.color = '#e879f9'; lTitle.style.textShadow = '0 0 15px #c084fc'; }
                if (lName) lName.style.color = '#38bdf8';
                if (lMsg) lMsg.style.borderColor = '#e879f9';
                if (lIcon) lIcon.innerHTML = '<i class="fa-solid fa-ghost text-purple"></i>';
            } else if (theme === 'gold') {
                defacedContainer.style.background = '#1c1502';
                if (lTitle) { lTitle.style.color = '#fbbf24'; lTitle.style.textShadow = '0 0 15px #f59e0b'; }
                if (lName) lName.style.color = '#fde68a';
                if (lMsg) lMsg.style.borderColor = '#fbbf24';
                if (lIcon) lIcon.innerHTML = '<i class="fa-solid fa-crown text-warning"></i>';
            } else {
                // Matrix Green
                defacedContainer.style.background = '#020e05';
                if (lTitle) { lTitle.style.color = '#00ff66'; lTitle.style.textShadow = '0 0 15px #00ff66'; }
                if (lName) lName.style.color = '#39ff14';
                if (lMsg) lMsg.style.borderColor = '#00ff66';
                if (lIcon) lIcon.innerHTML = '<i class="fa-solid fa-skull-crossbones text-success"></i>';
            }
        }

        document.getElementById('normalWebView')?.classList.add('d-none');
        document.getElementById('defacedWebView')?.classList.remove('d-none');
        const statusEl = document.getElementById('livePortalStatus');
        if (statusEl) {
            statusEl.textContent = "STATUS: 403 DEFACED (HACKED)";
            statusEl.className = "cyber-tag danger";
        }

        const b4 = document.getElementById('defaceBadge4');
        if (b4) { b4.className = "badge bg-success"; b4.textContent = "Defaced ✓"; }

        playerXP += 100;
        updateHUD();
        recordServerScore(10, playerXP);
        sfxRootDrop();
        logGlobal(`[DEFACE INJECTED] Target portal successfully defaced with signature: ${name} (+100 XP)`);
        
        if (showAlert) {
            alert(`DEFACE PAYLOAD INJECTED!\n\nWebsite portal target telah berhasil dideface dengan tema '${theme}'. Root index.html telah di-overwrite! (+100 XP)`);
        }
    }

    // --- Automated Guided Deface Solver ---
    function triggerAutoDeface(callback = null) {
        switchTab('defacestudio');
        setStudioMode('deface');
        sfxLaser();
        logGlobal(`[AUTO DEFACE] Menginisialisasi 4-Tahap Infiltrasi Defacement Otomatis...`);

        // Stage 1: Port Checksum Math (80 + 22 - X = 75 => X = 27)
        const mathInp = document.getElementById('defaceMathAnswer');
        if (mathInp) {
            mathInp.disabled = false;
            mathInp.value = 27;
        }
        defaceVerifyStep1();

        setTimeout(() => {
            // Stage 2: SQL Injection Auth Bypass
            const sqliOpt = document.querySelector('input[name="defaceSqliOption"][value="sqli"]');
            if (sqliOpt) {
                sqliOpt.disabled = false;
                sqliOpt.checked = true;
            }
            defaceVerifyStep2();

            setTimeout(() => {
                // Stage 3: Web Shell Upload Magic Bytes
                const mimeOpt = document.querySelector('input[name="defaceMimeOption"][value="magic"]');
                if (mimeOpt) {
                    mimeOpt.disabled = false;
                    mimeOpt.checked = true;
                }
                defaceVerifyStep3();

                setTimeout(() => {
                    // Stage 4: Custom Theme Deface Overwrite
                    const hackerName = document.getElementById('defaceHackerName');
                    if (hackerName && !hackerName.value) hackerName.value = "x-Shadow_MMC";
                    const titleEl = document.getElementById('defaceTitle');
                    if (titleEl && !titleEl.value) titleEl.value = "HACKED BY MMC CYBER TEAM";
                    
                    const themeSel = document.getElementById('defaceVisualTheme');
                    if (themeSel) {
                        const themes = ['matrix', 'blood', 'synthwave', 'gold'];
                        themeSel.value = themes[Math.floor(Math.random() * themes.length)];
                    }

                    injectDefacePayload(true);

                    const btnNext = document.getElementById('btnNextMission');
                    if (btnNext) {
                        btnNext.disabled = false;
                        btnNext.classList.add('btn-cyber');
                        btnNext.classList.remove('btn-cyber-amber');
                    }

                    if (typeof callback === 'function') callback();
                }, 450);
            }, 450);
        }, 450);
    }

    // --- White Hat Recovery Methods ---
    function recVerifyStep1() {
        const val = (document.getElementById('recAttackerIP')?.value || '').trim();
        if (val === '185.220.101.99') {
            recProgress.step1 = true;
            const b1 = document.getElementById('recBadge1');
            if (b1) { b1.className = "badge bg-success"; b1.textContent = "Blocked ✓"; }
            const inp = document.getElementById('recAttackerIP');
            if (inp) inp.disabled = true;
            const btn = document.getElementById('btnRecStep1');
            if (btn) btn.disabled = true;

            // Unlock Step 2
            const box2 = document.getElementById('recStepBox2');
            if (box2) box2.classList.remove('opacity-60');
            const b2 = document.getElementById('recBadge2');
            if (b2) { b2.className = "badge bg-warning text-dark"; b2.textContent = "Pending"; }
            document.getElementById('recFileAgent').disabled = false;
            document.getElementById('recFileCss').disabled = false;
            document.getElementById('recFileDaemon').disabled = false;
            const btn2 = document.getElementById('btnRecStep2');
            if (btn2) btn2.disabled = false;

            sfxLaser();
            logGlobal(`[INCIDENT RESPONSE] Attacker IP 185.220.101.99 berhasil diisolasi di iptables firewall!`);
        } else {
            sfxAlert();
            alert('IP penyerang salah! Periksa access log di atas dan temukan IP yang mengirim request POST ke webshell.');
        }
    }

    function recVerifyStep2() {
        const agent = document.getElementById('recFileAgent')?.checked;
        const css = document.getElementById('recFileCss')?.checked;
        const daemon = document.getElementById('recFileDaemon')?.checked;

        if (agent && daemon && !css) {
            recProgress.step2 = true;
            const b2 = document.getElementById('recBadge2');
            if (b2) { b2.className = "badge bg-success"; b2.textContent = "Purged ✓"; }
            document.getElementById('recFileAgent').disabled = true;
            document.getElementById('recFileCss').disabled = true;
            document.getElementById('recFileDaemon').disabled = true;
            const btn2 = document.getElementById('btnRecStep2');
            if (btn2) btn2.disabled = true;

            // Unlock Step 3
            const box3 = document.getElementById('recStepBox3');
            if (box3) box3.classList.remove('opacity-60');
            const b3 = document.getElementById('recBadge3');
            if (b3) { b3.className = "badge bg-warning text-dark"; b3.textContent = "Pending"; }
            const mathInp = document.getElementById('recMathAnswer');
            if (mathInp) mathInp.disabled = false;
            const btn3 = document.getElementById('btnRecStep3');
            if (btn3) btn3.disabled = false;

            sfxDecrypted();
            logGlobal(`[INCIDENT RESPONSE] Web shell backdoor agent_relay.dat & temp_daemon.log berhasil di-quarantine & dihapus!`);
        } else {
            sfxAlert();
            alert('Pilihan file malware salah! Hanya centang file backdoor berbahaya (file mencurigakan (agent dan daemon)), jangan hapus file legitimate CSS!');
        }
    }

    function recVerifyStep3() {
        const val = parseInt((document.getElementById('recMathAnswer')?.value || '').trim());
        // 12 * 8 + 4 = 96 + 4 = 100
        if (val === 100) {
            recProgress.step3 = true;
            const b3 = document.getElementById('recBadge3');
            if (b3) { b3.className = "badge bg-success"; b3.textContent = "Decrypted ✓"; }
            const inp = document.getElementById('recMathAnswer');
            if (inp) inp.disabled = true;
            const btn = document.getElementById('btnRecStep3');
            if (btn) btn.disabled = true;

            // Unlock Step 4
            const box4 = document.getElementById('recStepBox4');
            if (box4) box4.classList.remove('opacity-60');
            const b4 = document.getElementById('recBadge4');
            if (b4) { b4.className = "badge bg-warning text-dark"; b4.textContent = "Pending"; }
            document.querySelectorAll('input[name="recPatchOption"]').forEach(r => r.disabled = false);
            const btn4 = document.getElementById('btnRecStep4');
            if (btn4) btn4.disabled = false;

            sfxSuccess();
            logGlobal(`[INCIDENT RESPONSE] Kunci database didekripsi: Key = 100. Koneksi database berhasil dipulihkan!`);
        } else {
            sfxAlert();
            alert(`Kalkulasi kunci database salah! Hitung formula: (12 * 8) + 4.`);
        }
    }

    function recVerifyStep4() {
        const selected = document.querySelector('input[name="recPatchOption"]:checked')?.value;
        if (!selected) {
            sfxAlert();
            alert('Silakan pilih salah satu opsi perbaikan kode keamanan!');
            return;
        }

        if (selected === 'prepared') {
            recProgress.step4 = true;
            const b4 = document.getElementById('recBadge4');
            if (b4) { b4.className = "badge bg-success"; b4.textContent = "Patched ✓"; }
            document.querySelectorAll('input[name="recPatchOption"]').forEach(r => r.disabled = true);
            const btn4 = document.getElementById('btnRecStep4');
            if (btn4) btn4.disabled = true;

            // Unlock Step 5 (Final Restore)
            const box5 = document.getElementById('recStepBox5');
            if (box5) box5.classList.remove('opacity-60');
            const btnRestore = document.getElementById('btnCompleteRestore');
            if (btnRestore) btnRestore.disabled = false;

            sfxMatrixRattle();
            logGlobal(`[INCIDENT RESPONSE] Celah SQLi ditambal dengan PDO Prepared Statements & WAF rule diaktifkan!`);
        } else {
            sfxAlert();
            alert('Opsi salah! String concatenation tetap rentan terhadap SQL Injection. Pilih Prepared Statements.');
        }
    }

    function completeEmergencyRestore(showAlert = true) {
        if (!recProgress.step1 || !recProgress.step2 || !recProgress.step3 || !recProgress.step4) {
            sfxAlert();
            alert('Selesaikan seluruh 4 tahap mitigasi sebelum melakukan deployment restore!');
            return;
        }

        document.getElementById('normalWebView')?.classList.remove('d-none');
        document.getElementById('defacedWebView')?.classList.add('d-none');
        const statusEl = document.getElementById('livePortalStatus');
        if (statusEl) {
            statusEl.textContent = "STATUS: 200 OK (PORTAL AKTIF)";
            statusEl.className = "cyber-tag info";
        }

        playerXP += 120;
        updateHUD();
        recordServerScore(11, playerXP);
        sfxVictoryFanfare();
        logGlobal(`[RECOVERY COMPLETE] Portal sekolah kembali online & aman. Insiden siber ditutup.`);
        
        if (showAlert) {
            alert(`RECOVERY SUKSES!\n\nWebsite portal telah berhasil dipulihkan & diamankan 100% dari seluruh vektor serangan defacement. (+120 XP)`);
        }
    }

    // --- Automated Guided Incident Recovery Solver ---
    function triggerAutoRecovery(callback = null) {
        switchTab('defacestudio');
        setStudioMode('recovery');
        sfxRadar();
        logGlobal(`[AUTO RECOVERY] Menginisialisasi 5-Tahap Incident Response & Recovery Otomatis...`);

        // Stage 1: Attacker IP Isolation
        const ipInp = document.getElementById('recAttackerIP');
        if (ipInp) {
            ipInp.disabled = false;
            ipInp.value = '185.220.101.99';
        }
        recVerifyStep1();

        setTimeout(() => {
            // Stage 2: File Quarantine
            const agent = document.getElementById('recFileAgent');
            const daemon = document.getElementById('recFileDaemon');
            const css = document.getElementById('recFileCss');
            if (agent) { agent.disabled = false; agent.checked = true; }
            if (daemon) { daemon.disabled = false; daemon.checked = true; }
            if (css) { css.disabled = false; css.checked = false; }
            recVerifyStep2();

            setTimeout(() => {
                // Stage 3: Database Key Recovery
                const mathInp = document.getElementById('recMathAnswer');
                if (mathInp) {
                    mathInp.disabled = false;
                    mathInp.value = 100;
                }
                recVerifyStep3();

                setTimeout(() => {
                    // Stage 4: Code Security Patch
                    const prepOpt = document.querySelector('input[name="recPatchOption"][value="prepared"]');
                    if (prepOpt) {
                        prepOpt.disabled = false;
                        prepOpt.checked = true;
                    }
                    recVerifyStep4();

                    setTimeout(() => {
                        // Stage 5: Clean Deployment Restore
                        completeEmergencyRestore(true);

                        const btnNext = document.getElementById('btnNextMission');
                        if (btnNext) {
                            btnNext.disabled = false;
                            btnNext.classList.add('btn-cyber');
                            btnNext.classList.remove('btn-cyber-amber');
                        }

                        if (typeof callback === 'function') callback();
                    }, 450);
                }, 450);
            }, 450);
        }, 450);
    }

    // -------------------------------------------------------------
    // 8-STAGE INFILTRATION PIPELINE ENGINE
    // -------------------------------------------------------------
    function updateStageUI() {
        const cur = MISSIONS[currentMissionIdx];
        if (!cur) return;
        const desc = document.getElementById('guidedStepDesc');
        const btnText = document.getElementById('guidedStepBtnText');

        document.querySelectorAll('#stageStepper .stage-step-item').forEach(item => {
            const stepId = parseInt(item.getAttribute('data-step-id'));
            item.classList.remove('active', 'done');
            if (stepId < currentStage) {
                item.classList.add('done');
            } else if (stepId === currentStage) {
                item.classList.add('active');
            }
        });

        if (btnText) btnText.textContent = STAGE_LABELS[currentStage] || "Misi Selesai";

        if (currentStage === 0) {
            if (desc) desc.textContent = `Tahap 1: Jalankan Network Recon & Nmap Scan pada target IP (${cur.targetIP}).`;
        } else if (currentStage === 1) {
            if (desc) desc.textContent = `Tahap 2: Bypass WAF / IDS target dengan membuat tunnel proksi terenkripsi.`;
        } else if (currentStage === 2) {
            if (desc) desc.textContent = `Tahap 3: Injeksi payload CVE untuk menyadap shadow vault memo terenkripsi!`;
        } else if (currentStage === 3) {
            if (desc) desc.textContent = `Tahap 4: Dekripsi sandi ${cur.cipherMethod} untuk mengekstrak Target Hash!`;
        } else if (currentStage === 4) {
            if (desc) desc.textContent = `Tahap 5: Lakukan profiling signature pada hash ${cur.hashType} & siapkan kamus kata sandi.`;
        } else if (currentStage === 5) {
            if (desc) desc.textContent = `Tahap 6: Luncurkan serangan ${cur.attackMethod} untuk membobol password!`;
        } else if (currentStage === 6) {
            if (desc) desc.textContent = `Tahap 7: Password didapat! Lakukan Root Privilege Escalation ke UID 0!`;
        } else if (currentStage === 7) {
            if (desc) desc.textContent = `Tahap 8: Akses ROOT terbuka! Exfiltrasi data rahasia & klaim +5 Poin Ranking!`;
        }
    }

    function execGuidedNextStep() {
        const cur = MISSIONS[currentMissionIdx];
        if (!cur) return;

        // Custom Flow for White Hat Incident Recovery Missions
        if (cur.role === 'white' && (cur.id === 11 || cur.category.includes('Response') || cur.category.includes('Recovery') || cur.category.includes('Defense'))) {
            switchTab('defacestudio');
            setStudioMode('recovery');
            triggerAutoRecovery();
            return;
        }

        // Custom Flow for Red Team Web Defacing Missions
        if (cur.role === 'red' && (cur.id === 2 || cur.category.includes('Defac'))) {
            if (currentStage === 0) {
                switchTab('terminal');
                runQuickCommand(`nmap -sS -p 80,443 ${cur.targetIP}`);
                sfxRadar();
                logGlobal(`[STAGE 1/2 - RECON] Memindai portal web target ${cur.targetIP}... Port 80 HTTP terbuka.`);
                currentStage = 1;
            } else {
                switchTab('defacestudio');
                setStudioMode('deface');
                triggerAutoDeface();
                currentStage = 0;
            }
            updateStageUI();
            return;
        }

        if (currentStage === 0) {
            switchTab('terminal');
            runQuickCommand(`nmap -sS -A ${cur.targetIP}`);
            sfxRadar();
            logGlobal(`[STAGE 1/8 - RECON] Memindai subnet ${cur.targetIP}... Ditemukan port terbuka: 22, 80, 443.`);
            currentStage = 1;
        } else if (currentStage === 1) {
            switchTab('network');
            scanNetworkNodes();
            sfxModem();
            logGlobal(`[STAGE 2/8 - WAF BYPASS] Terowongan proksi SSH dibuat. Status WAF target: BYPASSED.`);
            currentStage = 2;
        } else if (currentStage === 2) {
            switchTab('decryptor');
            loadInterceptedCipher();
            sfxLaser();
            logGlobal(`[STAGE 3/8 - CVE EXPLOIT] Memori dumped! Intercepted payload file '${cur.cipherMethod}' dimuat.`);
            currentStage = 3;
        } else if (currentStage === 3) {
            processCipher('decrypt');
            sfxDecrypted();
            logGlobal(`[STAGE 4/8 - DECRYPT] Ciphertext berhasil didekripsi! Target hash terungkap.`);
            currentStage = 4;
        } else if (currentStage === 4) {
            sendResultToCracker();
            loadMissionHash();
            sfxMatrixRattle();
            logGlobal(`[STAGE 5/8 - PROFILING] Hash signature diidentifikasi: ${cur.hashType} (${cur.attackMethod} mode loaded).`);
            currentStage = 5;
        } else if (currentStage === 5) {
            startCracking();
        } else if (currentStage === 6) {
            switchTab('terminal');
            runQuickCommand(`sudo su - root --auth='${cur.crackedPlain}'`);
            sfxRootDrop();
            logGlobal(`[STAGE 7/8 - PRIVILEGE ESCALATION] UID 0 ROOT ACQUIRED. Target system under full control!`);
            currentStage = 7;
        } else if (currentStage === 7) {
            completeCurrentMission();
            currentStage = 0;
        }

        updateStageUI();
    }

    function triggerFullAutoHack() {
        const cur = MISSIONS[currentMissionIdx];
        if (!cur) return;
        currentStage = 0;
        updateStageUI();

        // Custom Full Auto for White Hat Incident Recovery Missions
        if (cur.role === 'white' && (cur.id === 11 || cur.category.includes('Response') || cur.category.includes('Recovery') || cur.category.includes('Defense'))) {
            switchTab('defacestudio');
            setStudioMode('recovery');
            logGlobal(`[AUTO DEFENSE] Misi Incident Response aktif. Menjalankan Auto-Recovery Studio...`);
            triggerAutoRecovery(() => {
                completeCurrentMission();
            });
            return;
        }

        // Custom Full Auto for Red Team Web Defacing Missions
        if (cur.role === 'red' && (cur.id === 2 || cur.category.includes('Defac'))) {
            switchTab('terminal');
            runQuickCommand(`nmap -sV -p 80,443 ${cur.targetIP}`);
            sfxRadar();
            logGlobal(`[AUTO DEFACE 1/2] Reconnaissance pada ${cur.targetIP}... Port 80 Web terbuka.`);

            setTimeout(() => {
                switchTab('defacestudio');
                setStudioMode('deface');
                logGlobal(`[AUTO DEFACE 2/2] Meluncurkan 4-Tahap Infiltrasi Deface Studio...`);
                triggerAutoDeface(() => {
                    completeCurrentMission();
                });
            }, 800);
            return;
        }

        // Standard 8-Stage Killchain for Infiltration & Cryptanalysis Missions
        switchTab('terminal');
        runQuickCommand(`nmap -sV -p 22,80,443,3306 ${cur.targetIP}`);
        sfxRadar();
        logGlobal(`[AUTO-KILLCHAIN 1/8] Reconnaissance pada ${cur.targetIP}... Port 80 & 443 terbuka.`);

        setTimeout(() => {
            currentStage = 1;
            updateStageUI();
            switchTab('network');
            scanNetworkNodes();
            sfxModem();
            logGlobal(`[AUTO-KILLCHAIN 2/8] WAF Firewall dilewati melalui proksi relay tunnel.`);

            setTimeout(() => {
                currentStage = 2;
                updateStageUI();
                switchTab('decryptor');
                loadInterceptedCipher();
                sfxLaser();
                logGlobal(`[AUTO-KILLCHAIN 3/8] CVE Injeksi berhasil. Vault memory dumped.`);

                setTimeout(() => {
                    currentStage = 3;
                    updateStageUI();
                    processCipher('decrypt');
                    sfxDecrypted();
                    logGlobal(`[AUTO-KILLCHAIN 4/8] Algoritma ${cur.cipherMethod} berhasil didekripsi.`);

                    setTimeout(() => {
                        currentStage = 4;
                        updateStageUI();
                        sendResultToCracker();
                        loadMissionHash();
                        sfxMatrixRattle();
                        logGlobal(`[AUTO-KILLCHAIN 5/8] Hash ${cur.hashType} dimuat ke Dictionary Cracker.`);

                        setTimeout(() => {
                            currentStage = 5;
                            updateStageUI();
                            startCracking();
                        }, 500);
                    }, 600);
                }, 600);
            }, 700);
        }, 800);
    }

    function loadMissionHash() {
        const cur = MISSIONS[currentMissionIdx];
        if (!cur) return;
        const hashIn = document.getElementById('inputTargetHash');
        if (hashIn) hashIn.value = cur.targetHash;
        setHashType(cur.hashType);
        setAttackMethod(cur.attackMethod);
        logCracker(`[INFO] Target hash loaded: ${cur.targetHash} (${cur.hashType}) - Attack: ${cur.attackMethod}`);
        sfxClick();
    }

    function loadInterceptedCipher() {
        const cur = MISSIONS[currentMissionIdx];
        if (!cur) return;
        const txtEl = document.getElementById('cipherInputText');
        if (txtEl) txtEl.value = cur.cipherText;
        setCipherMethod(cur.cipherMethod);
        if (cur.cipherKey) {
            const keyEl = document.getElementById('cipherKeyInput');
            if (keyEl) keyEl.value = cur.cipherKey;
        }
        logCipher(`[INTERCEPT] Sniffed payload loaded for "${cur.title}". Method: ${cur.cipherMethod}`);
        sfxClick();
    }

    // -------------------------------------------------------------
    // PASSWORD CRACKER ENGINE
    // -------------------------------------------------------------
    function setHashType(type) {
        currentHashType = type;
        document.querySelectorAll('[data-hash-type]').forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('data-hash-type') === type);
        });
        sfxClick();
    }

    function setAttackMethod(method) {
        currentAttackMethod = method;
        document.querySelectorAll('[data-attack-method]').forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('data-attack-method') === method);
        });
        sfxClick();
    }

    function startCracking() {
        const hashInput = (document.getElementById('inputTargetHash')?.value || '').trim();
        if (!hashInput) {
            logCracker("[ERROR] Please enter or load a target hash.");
            sfxAlert();
            return;
        }

        const cur = MISSIONS[currentMissionIdx];
        isCracking = true;
        const btnStart = document.getElementById('btnStartCrack');
        const btnStop = document.getElementById('btnStopCrack');
        if (btnStart) btnStart.disabled = true;
        if (btnStop) btnStop.disabled = false;
        
        logCracker(`[START] Initializing ${currentAttackMethod} attack on ${currentHashType} hash...`);
        logGlobal(`[CRACK] Attack launched against target hash: ${hashInput.substring(0, 10)}...`);

        let progress = 0;
        const fill = document.getElementById('crackProgressFill');
        const label = document.getElementById('crackProgressLabel');

        const speed = currentAttackMethod === 'Rainbow Table' ? 380000 : (currentAttackMethod === 'Dictionary' ? 145000 : 72000);
        const speedBadge = document.getElementById('crackerSpeedBadge');
        if (speedBadge) speedBadge.textContent = `Speed: ${(speed/1000).toFixed(0)}k h/s`;

        const stepTime = 100;
        const totalSteps = currentAttackMethod === 'Rainbow Table' ? 20 : 35;

        crackInterval = setInterval(() => {
            progress += (100 / totalSteps);
            sfxCrackHit();

            const randChars = Math.random().toString(36).substring(2, 10);
            if (progress < 100) {
                if (fill) fill.style.width = `${progress}%`;
                if (label) label.textContent = `Cracking... ${Math.floor(progress)}% (${randChars})`;
                if (Math.random() > 0.6) {
                    logCracker(`> Trying wordlist candidate: [${randChars}] -> mismatch`);
                }
            } else {
                clearInterval(crackInterval);
                isCracking = false;
                if (fill) fill.style.width = '100%';
                if (btnStart) btnStart.disabled = false;
                if (btnStop) btnStop.disabled = true;

                let crackedWord = cur ? cur.crackedPlain : "password";
                if (cur && hashInput.toLowerCase() !== cur.targetHash.toLowerCase()) {
                    crackedWord = "generic_pass_" + Math.random().toString(36).substring(2, 6);
                }

                if (label) label.textContent = `SUCCESS (100%) - KEY: ${crackedWord}`;
                logCracker(`[SUCCESS] Hash successfully cracked!`);
                logCracker(`[RESULT] Plaintext Password: >>> ${crackedWord} <<<`);
                logGlobal(`[BREACH] Password cracked successfully: ${crackedWord}`);
                sfxDecrypted();

                currentStage = 6;
                updateStageUI();

                const btnNext = document.getElementById('btnNextMission');
                if (btnNext) {
                    btnNext.disabled = false;
                    btnNext.classList.add('btn-cyber');
                    btnNext.classList.remove('btn-cyber-amber');
                }
                logGlobal(`[OBJECTIVE] Kata sandi '${crackedWord}' siap diinjeksi ke root shell.`);
            }
        }, stepTime);
    }

    function stopCracking() {
        if (crackInterval) clearInterval(crackInterval);
        isCracking = false;
        const btnStart = document.getElementById('btnStartCrack');
        const btnStop = document.getElementById('btnStopCrack');
        if (btnStart) btnStart.disabled = false;
        if (btnStop) btnStop.disabled = true;
        const label = document.getElementById('crackProgressLabel');
        if (label) label.textContent = 'Cracking halted by user.';
        logCracker('[HALT] Cracking operation aborted.');
        sfxClick();
    }

    function logCracker(msg) {
        const box = document.getElementById('crackerLog');
        if (!box) return;
        const line = document.createElement('div');
        line.textContent = `[${new Date().toLocaleTimeString()}] ${msg}`;
        box.appendChild(line);
        box.scrollTop = box.scrollHeight;
    }

    // -------------------------------------------------------------
    // ENCRYPTION & DECRYPTION ENGINE
    // -------------------------------------------------------------
    function setCipherMethod(method) {
        currentCipherMethod = method;
        document.querySelectorAll('[data-cipher-method]').forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('data-cipher-method') === method);
        });
        const keyLabel = document.getElementById('cipherKeyLabel');
        if (keyLabel) {
            if (method === 'Base64') keyLabel.textContent = 'KEY (NOT REQUIRED FOR BASE64):';
            else if (method === 'Caesar') keyLabel.textContent = 'CAESAR SHIFT (NUMBER, e.g. 3, 7, 13):';
            else keyLabel.textContent = 'SECRET ENCRYPTION KEY:';
        }
        sfxClick();
    }

    function processCipher(operation) {
        const text = (document.getElementById('cipherInputText')?.value || '').trim();
        const key = (document.getElementById('cipherKeyInput')?.value || '').trim();

        if (!text) {
            logCipher('[ERROR] Input text area is empty. Click "Muat Sandi Sadapan" first.');
            sfxAlert();
            return;
        }

        let output = "";
        try {
            if (currentCipherMethod === 'Base64') {
                if (operation === 'encrypt') {
                    output = btoa(unescape(encodeURIComponent(text)));
                } else {
                    output = decodeURIComponent(escape(atob(text)));
                }
            } else if (currentCipherMethod === 'Caesar') {
                output = rotCipher(text, key, operation === 'decrypt');
            } else if (currentCipherMethod === 'AES' || currentCipherMethod === 'RSA') {
                output = fakeAesTransform(text, key, operation === 'decrypt');
            }

            logCipher(`[${operation.toUpperCase()} OK] Method: ${currentCipherMethod}`);
            logCipher(`> Result: ${output}`);
            logGlobal(`[CIPHER] ${operation.toUpperCase()} executed via ${currentCipherMethod}`);
            sfxDecrypted();

            window._lastDecryptedText = output;
        } catch (e) {
            logCipher(`[ERROR] Failed to ${operation} string. Invalid format or cipher.`);
            sfxAlert();
        }
    }

    function sendResultToCracker() {
        if (!window._lastDecryptedText) {
            logCipher('[WARN] No decrypted text found yet. Run Decrypt first.');
            sfxAlert();
            return;
        }

        const hashMatch = window._lastDecryptedText.match(/[a-fA-F0-9]{32,64}/);
        const hashInput = document.getElementById('inputTargetHash');
        if (hashMatch && hashInput) {
            hashInput.value = hashMatch[0];
            if (hashMatch[0].length === 32) setHashType('MD5');
            else if (hashMatch[0].length === 40) setHashType('SHA1');
            else if (hashMatch[0].length === 64) setHashType('SHA256');

            logCracker(`[AUTO-FILL] Extracted target hash: ${hashMatch[0]}`);
            logCipher(`[EXPORT] Target Hash successfully forwarded to Password Cracker!`);
            switchTab('decryptor');
            sfxClick();
        } else if (hashInput) {
            hashInput.value = window._lastDecryptedText;
            logCipher(`[EXPORT] Copied plain text into Password Cracker input.`);
            sfxClick();
        }
    }

    function logCipher(msg) {
        const box = document.getElementById('cipherOutputLog');
        if (!box) return;
        const line = document.createElement('div');
        line.textContent = `[${new Date().toLocaleTimeString()}] ${msg}`;
        box.appendChild(line);
        box.scrollTop = box.scrollHeight;
    }

    function logGlobal(msg) {
        const box = document.getElementById('globalLog');
        if (box) {
            const line = document.createElement('div');
            line.textContent = `[${new Date().toLocaleTimeString()}] ${msg}`;
            box.appendChild(line);
            box.scrollTop = box.scrollHeight;
        }

        const dBox = document.getElementById('dashboardLog');
        if (dBox) {
            const dLine = document.createElement('div');
            dLine.textContent = `[${new Date().toLocaleTimeString()}] ${msg}`;
            dBox.appendChild(dLine);
            dBox.scrollTop = dBox.scrollHeight;
        }
    }

    function clearLog(id) {
        const box = document.getElementById(id);
        if (box) box.innerHTML = `<div>[${new Date().toLocaleTimeString()}] Log console cleared.</div>`;
        sfxClick();
    }

    // -------------------------------------------------------------
    // COMPLETE MISSION & RANK UP MODAL
    // -------------------------------------------------------------
    function completeCurrentMission() {
        const cur = MISSIONS[currentMissionIdx];
        if (!cur) return;
        cur.status = "Completed";
        playerXP += cur.reward;

        recordServerScore(cur.id, playerXP);

        const msgEl = document.getElementById('modalSuccessMessage');
        if (msgEl) {
            msgEl.textContent = `Misi "${cur.title}" Berhasil Diselesaikan! Kredensial (${cur.crackedPlain}) telah diverifikasi & diamankan.`;
        }
        const rewardEl = document.getElementById('modalRewardXP');
        if (rewardEl) {
            rewardEl.textContent = `+${cur.reward} XP`;
        }

        const modalEl = document.getElementById('modalMissionSuccess');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        renderMissions();
        updateHUD();
        sfxVictoryFanfare();
    }

    function recordServerScore(level, score) {
        const formData = new FormData();
        formData.append('game_id', 'aku-hacker');
        formData.append('level', level);
        formData.append('score', score);
        formData.append('stars', 3);

        fetch(window.MMC_API_RECORD_SCORE || '/mini-game/api/record-score', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.status === 'success') {
                logGlobal(`[RANKING] ${data.message}`);
            }
        })
        .catch(() => {});
    }

    // -------------------------------------------------------------
    // INTERACTIVE SHELL CLI
    // -------------------------------------------------------------
    function runQuickCommand(cmd) {
        const inEl = document.getElementById('shellInput');
        if (inEl) inEl.value = cmd;
        execShellCommand();
    }

    function execShellCommand() {
        const input = document.getElementById('shellInput');
        if (!input) return;
        const cmd = input.value.trim();
        if (!cmd) return;

        input.value = '';
        logShell(`root@cyber:~# ${cmd}`, true);
        sfxClick();

        const lower = cmd.toLowerCase();
        const cur = MISSIONS[currentMissionIdx] || MISSIONS[0];

        if (lower === 'help') {
            logShell(`Available commands:
  help              - Display this helper guide
  scan / nmap [ip]  - Scan target server for open ports & CVEs
  cat [file]        - Read file contents (e.g. cat secret.txt, cat shadow)
  crack [hash]      - Send hash into cracking engine
  decrypt [str]     - Send string to cipher decryptor
  inject --sql      - Launch automated SQL injection probe
  whoami            - Display current user identity & rank
  matrix            - Run digital matrix visual stream
  clear             - Clear terminal screen`);
        } else if (lower.startsWith('scan') || lower.startsWith('nmap')) {
            logShell(`Starting Nmap scan on ${cur.targetIP}...
PORT     STATE SERVICE       VERSION
22/tcp   OPEN  ssh           OpenSSH 8.4p1
80/tcp   OPEN  http          Apache httpd 2.4.52
443/tcp  OPEN  https         TLSv1.3
3306/tcp OPEN  mysql         MySQL 8.0.28
[VULN DETECTED] CVE-2023-44487 & Insecure Hash Storage detected on target!`);
            sfxRadar();
        } else if (lower.startsWith('sudo') || lower.includes('root')) {
            logShell(`[AUTHENTICATED] Elevating user to UID 0 (root)...
root@${cur.targetIP}:~# access granted. Privilege escalation verified.`);
            sfxRootDrop();
        } else if (lower === 'cat secret.txt' || lower === 'cat secret') {
            logShell(`[FILE: secret.txt]
--------------------------------------------------
CONFIDENTIAL MEMO - MMC ADMIN:
Target Name: ${cur.title}
Intercepted token: ${cur.cipherText}
Method: ${cur.cipherMethod} | Target Hash: ${cur.targetHash}`);
            sfxSuccess();
        } else if (lower.includes('shadow')) {
            logShell(`[FILE: /etc/shadow]
root:$6$mmc$${cur.targetHash}:19200:0:99999:7:::
admin:$6$system$5f4dcc3b5aa765d61d8327deb882cf99:19200:0:99999:7:::`);
        } else if (lower.startsWith('inject')) {
            logShell(`[EXPLOIT] Injecting SQL payload: FETCH RECORD WHERE id = target_id --auth
[200 OK] Extracted target hash: ${cur.targetHash}`);
            sfxLaser();
        } else if (lower === 'whoami') {
            logShell(`Identity: root / MMC Cyber Infiltrator
Target: ${cur.targetIP} (${cur.secLevel})
Current XP: ${playerXP}`);
        } else if (lower === 'clear') {
            const shLog = document.getElementById('shellLog');
            if (shLog) shLog.innerHTML = '';
        } else if (lower === 'matrix') {
            logShell(`101001010111010101010010101011100101
010101101010001010101011101010101001
110101010100101010111001010101011010
[MATRIX STREAM INJECTED]`);
            sfxMatrixRattle();
        } else {
            logShell(`bash: ${cmd}: command not recognized. Type 'help' for instructions.`);
            sfxAlert();
        }
    }

    function logShell(text, isCmd = false) {
        const log = document.getElementById('shellLog');
        if (!log) return;
        const div = document.createElement('div');
        div.style.whiteSpace = 'pre-wrap';
        if (isCmd) {
            div.className = 'text-white fw-bold mt-2';
        } else {
            div.className = 'text-success';
        }
        div.textContent = text;
        log.appendChild(div);
        log.scrollTop = log.scrollHeight;
    }

    // -------------------------------------------------------------
    // INTERACTIVE NETWORK MAP & TOPOLOGY SANDBOX ENGINE
    // -------------------------------------------------------------
    let canvas, ctx;
    let animFrameId = null;
    let packetT = 0;
    let radarAngle = 0;

    // Topology state (Starts empty until scanned)
    let nodes = [];
    let links = []; // Array of [fromNodeId, toNodeId]
    let isScanned = false;
    let linkMode = false;
    let deleteMode = false;
    let selectedNodeForLink = null;
    let draggedNode = null;
    let isDragging = false;
    let dragOffset = { x: 0, y: 0 };
    let attackActive = false;

    function generateMissionNodes(cur) {
        const tIP = cur.targetIP || "192.168.1.1";
        const role = cur.role || "red";
        
        if (cur.category === "WiFi Infiltration") {
            return [
                { id: 'gw', label: 'Main Router', ip: tIP, x: 0.16, y: 0.50, color: '#00ff66', status: 'Hacked', port: '80/443' },
                { id: 'ap', label: 'AP-SMANIT-WLAN', ip: '192.168.1.254', x: 0.38, y: 0.28, color: '#00e5ff', status: 'Vulnerable', port: '802.11/WPA' },
                { id: 'sw', label: 'Switch Core', ip: '192.168.1.2', x: 0.38, y: 0.72, color: '#a855f7', status: 'Bridged', port: '22/SSH' },
                { id: 'adm', label: 'Admin Station', ip: '192.168.1.10', x: 0.64, y: 0.38, color: '#f59e0b', status: 'Target', port: '445/SMB' },
                { id: 'vlt', label: 'Shadow Vault', ip: tIP + '/auth', x: 0.86, y: 0.58, color: '#ef4444', status: 'Secured', port: '2026/Encrypted' }
            ];
        } else if (cur.category === "Web Defacing" || cur.category === "Incident Response") {
            return [
                { id: 'gw', label: 'Internet Gateway', ip: '192.168.1.1', x: 0.16, y: 0.50, color: '#00ff66', status: 'Hacked', port: '80/443' },
                { id: 'waf', label: 'ModSecurity WAF', ip: '192.168.1.4', x: 0.38, y: 0.32, color: '#00e5ff', status: 'Infiltrated', port: '8080/Proxy' },
                { id: 'srv', label: 'Apache Web Portal', ip: tIP, x: 0.62, y: 0.38, color: '#f59e0b', status: 'Target', port: '80/HTTP' },
                { id: 'shl', label: 'Web Shell Root', ip: tIP + '/var/www', x: 0.84, y: 0.65, color: '#ef4444', status: 'Secured', port: '22/SFTP' }
            ];
        } else if (cur.category === "IoT / Surveillance") {
            return [
                { id: 'gw', label: 'Gateway Node', ip: '10.0.0.1', x: 0.16, y: 0.50, color: '#00ff66', status: 'Hacked', port: '80' },
                { id: 'prx', label: 'RTSP Proxy Pivot', ip: '10.0.1.5', x: 0.38, y: 0.30, color: '#a855f7', status: 'Infiltrated', port: '8554' },
                { id: 'rtsp', label: 'Studio Stream Hub', ip: '10.0.1.20', x: 0.62, y: 0.68, color: '#00e5ff', status: 'Vulnerable', port: '554/RTSP' },
                { id: 'cam', label: 'CCTV Studio Cam', ip: tIP, x: 0.84, y: 0.38, color: '#ef4444', status: 'Target', port: '80/Live' }
            ];
        } else if (cur.category === "Database Breach" || cur.category === "Database Defense") {
            return [
                { id: 'gw', label: 'Corporate Gateway', ip: '10.0.1.1', x: 0.16, y: 0.50, color: '#00ff66', status: 'Hacked', port: '80/443' },
                { id: 'app', label: 'API Application Srv', ip: '10.0.4.15', x: 0.40, y: 0.30, color: '#00e5ff', status: 'Exploitable', port: '8000' },
                { id: 'db', label: 'MySQL Database', ip: tIP, x: 0.65, y: 0.65, color: '#f59e0b', status: 'Target', port: '3306/SQL' },
                { id: 'bck', label: 'Encrypted Shadow DB', ip: tIP + '/dump', x: 0.86, y: 0.38, color: '#ef4444', status: 'Secured', port: 'AES-256' }
            ];
        } else {
            // General / High Tier Cyber Fortress
            return [
                { id: 'gw', label: 'Main Gateway', ip: '192.168.1.1', x: 0.16, y: 0.50, color: '#00ff66', status: 'Hacked', port: '80/443' },
                { id: 'fw', label: 'Core Firewall Relay', ip: '10.0.2.1', x: 0.38, y: 0.28, color: '#a855f7', status: 'Infiltrated', port: '8080' },
                { id: 'node1', label: 'Target Subnet Host', ip: tIP, x: 0.62, y: 0.68, color: '#00e5ff', status: 'Vulnerable', port: '22/SSH' },
                { id: 'vlt', label: 'Master Root Vault', ip: tIP + '/root', x: 0.86, y: 0.42, color: '#ef4444', status: 'Target', port: 'Root UID 0' }
            ];
        }
    }

    function generateMissionLinks(nodeList) {
        if (nodeList.length === 0) return [];
        const res = [];
        for (let i = 0; i < nodeList.length - 1; i++) {
            res.push([nodeList[i].id, nodeList[i + 1].id]);
        }
        if (nodeList.length >= 4) {
            res.push([nodeList[0].id, nodeList[2].id]);
        }
        return res;
    }

    function updateTopologyBadge() {
        const badge = document.getElementById('networkTopologyBadge');
        if (!badge) return;
        if (!isScanned || nodes.length === 0) {
            badge.textContent = 'Topologi: Belum Dipindai / Kosong';
            badge.className = 'cyber-tag';
        } else {
            badge.textContent = `${nodes.length} Node | ${links.length} Jalur Aktif`;
            badge.className = 'cyber-tag text-white';
        }
    }

    function initNetworkCanvas() {
        canvas = document.getElementById('networkCanvas');
        if (!canvas) return;
        ctx = canvas.getContext('2d');

        resizeNetworkCanvas();
        window.addEventListener('resize', resizeNetworkCanvas);

        // Interactive Pointer / Mouse / Touch Listeners
        canvas.addEventListener('mousedown', handlePointerDown);
        canvas.addEventListener('mousemove', handlePointerMove);
        window.addEventListener('mouseup', handlePointerUp);

        // Double Click to Edit existing node or Add new node
        canvas.addEventListener('dblclick', (e) => {
            if (deleteMode) return;
            const pos = getCanvasPos(e);
            const clicked = findNodeAt(pos);
            if (clicked) {
                openEditNodeModal(clicked);
            } else {
                openAddNodeModal();
            }
        });

        let lastTouchTime = 0;
        canvas.addEventListener('touchstart', (e) => {
            const now = Date.now();
            if (e.touches.length === 1) {
                const t = e.touches[0];
                const pos = getCanvasPos(t);
                const clicked = findNodeAt(pos);
                if (now - lastTouchTime < 350 && clicked && !deleteMode) {
                    // Double tap on node -> open edit modal
                    e.preventDefault();
                    openEditNodeModal(clicked);
                    lastTouchTime = 0;
                    return;
                }
                lastTouchTime = now;
                handlePointerDown({ clientX: t.clientX, clientY: t.clientY, preventDefault: () => e.preventDefault() });
            }
        }, { passive: false });

        canvas.addEventListener('touchmove', (e) => {
            if (e.touches.length === 1 && isDragging) {
                const t = e.touches[0];
                handlePointerMove({ clientX: t.clientX, clientY: t.clientY });
                e.preventDefault();
            }
        }, { passive: false });

        window.addEventListener('touchend', handlePointerUp);

        startNetworkAnimation();
    }

    function getCanvasPos(e) {
        const rect = canvas.getBoundingClientRect();
        return {
            x: (e.clientX - rect.left) * (canvas.width / rect.width),
            y: (e.clientY - rect.top) * (canvas.height / rect.height)
        };
    }

    function findNodeAt(pos) {
        for (let i = nodes.length - 1; i >= 0; i--) {
            const n = nodes[i];
            const nx = n.x * canvas.width;
            const ny = n.y * canvas.height;
            if (Math.hypot(pos.x - nx, pos.y - ny) <= 26) {
                return n;
            }
        }
        return null;
    }

    function handlePointerDown(e) {
        if (!isScanned || nodes.length === 0) return;
        const pos = getCanvasPos(e);
        const clicked = findNodeAt(pos);

        if (!clicked) {
            if (linkMode && selectedNodeForLink) {
                selectedNodeForLink = null;
                logNetwork('[LINK] Pemilihan node dibatalkan.');
            }
            return;
        }

        if (deleteMode) {
            // Mode Hapus Node Satu Per Satu
            const targetName = clicked.label;
            const targetIp = clicked.ip;
            nodes = nodes.filter(n => n.id !== clicked.id);
            links = links.filter(l => l[0] !== clicked.id && l[1] !== clicked.id);
            if (nodes.length === 0) {
                isScanned = false;
            }
            updateTopologyBadge();
            logNetwork(`[NODE DELETED] Node '${targetName}' (${targetIp}) berhasil dihapus dari topologi.`);
            sfxLaser();
            return;
        }

        if (linkMode) {
            // Sambung / Putus Jalur Mode
            if (!selectedNodeForLink) {
                selectedNodeForLink = clicked;
                sfxClick();
                logNetwork(`[LINK] Node 1 dipilih: '${clicked.label}' (${clicked.ip}). Klik node kedua untuk menyambung/memutus jalur.`);
            } else if (selectedNodeForLink.id === clicked.id) {
                selectedNodeForLink = null;
                sfxClick();
                logNetwork(`[LINK] Node '${clicked.label}' batal dipilih.`);
            } else {
                toggleLink(selectedNodeForLink.id, clicked.id);
                selectedNodeForLink = null;
            }
        } else {
            // Drag & Drop Mode / Node Inspection
            draggedNode = clicked;
            isDragging = true;
            dragOffset = {
                x: clicked.x * canvas.width - pos.x,
                y: clicked.y * canvas.height - pos.y
            };
            canvas.style.cursor = 'grabbing';
            logNetwork(`[INSPECT NODE] ${clicked.label} (${clicked.ip}) | Status: ${clicked.status} | Ports: ${clicked.port} (Klik ganda untuk edit)`);
            sfxClick();
        }
    }

    function handlePointerMove(e) {
        const pos = getCanvasPos(e);
        if (isDragging && draggedNode) {
            const newX = (pos.x + dragOffset.x) / canvas.width;
            const newY = (pos.y + dragOffset.y) / canvas.height;
            draggedNode.x = Math.max(0.06, Math.min(0.94, newX));
            draggedNode.y = Math.max(0.08, Math.min(0.92, newY));
        } else {
            const hovered = findNodeAt(pos);
            canvas.style.cursor = hovered ? (deleteMode ? 'not-allowed' : (linkMode ? 'crosshair' : 'grab')) : 'default';
        }
    }

    function handlePointerUp() {
        if (isDragging) {
            isDragging = false;
            draggedNode = null;
            if (canvas) canvas.style.cursor = 'default';
        }
    }

    function toggleLink(idA, idB) {
        const existingIdx = links.findIndex(l => 
            (l[0] === idA && l[1] === idB) || (l[0] === idB && l[1] === idA)
        );

        const nA = nodes.find(n => n.id === idA);
        const nB = nodes.find(n => n.id === idB);
        const nameA = nA ? nA.label : idA;
        const nameB = nB ? nB.label : idB;

        if (existingIdx >= 0) {
            links.splice(existingIdx, 1);
            sfxLaser();
            logNetwork(`[DISCONNECTED] Jalur antara '${nameA}' dan '${nameB}' TELAH DIPUTUS.`);
        } else {
            links.push([idA, idB]);
            sfxDecrypted();
            logNetwork(`[CONNECTED] Jalur baru TERHUBUNG antara '${nameA}' dan '${nameB}'.`);
        }
        updateTopologyBadge();
    }

    function toggleLinkMode() {
        linkMode = !linkMode;
        selectedNodeForLink = null;
        if (linkMode) {
            deleteMode = false;
            const btnDel = document.getElementById('btnToggleDeleteMode');
            if (btnDel) {
                btnDel.classList.remove('btn-cyber-red');
                btnDel.classList.add('btn-cyber');
                btnDel.innerHTML = '<i class="fa-solid fa-trash-can me-1"></i> Hapus Node (Mode)';
            }
        }

        const btn = document.getElementById('btnToggleLinkMode');
        const hint = document.getElementById('networkModeHint');

        if (linkMode) {
            if (btn) {
                btn.classList.add('btn-cyber-amber');
                btn.classList.remove('btn-cyber');
                btn.innerHTML = '<i class="fa-solid fa-link text-dark me-1"></i> Mode Link: ON';
            }
            if (hint) {
                hint.innerHTML = '<i class="fa-solid fa-link text-warning me-1"></i> <strong class="text-warning">MODE EDIT JALUR AKTIF:</strong> Klik Node 1 lalu klik Node 2 untuk menyambungkan atau memutus jalur.';
            }
            logNetwork('[MODE LINK] Mode Sambung/Putus Jalur Aktif. Klik 2 node pada peta.');
        } else {
            if (btn) {
                btn.classList.remove('btn-cyber-amber');
                btn.classList.add('btn-cyber');
                btn.innerHTML = '<i class="fa-solid fa-link me-1"></i> Sambung / Putus Jalur';
            }
            if (hint) {
                hint.innerHTML = '<i class="fa-solid fa-circle-info text-info me-1"></i> <strong>Mode Geser (Drag):</strong> Klik & tahan node untuk memindahkan posisi. Aktifkan "Sambung / Putus" untuk menghubungkan 2 node.';
            }
            logNetwork('[MODE NORMAL] Kembali ke mode Geser (Drag & Drop) & Inspeksi node.');
        }
        sfxClick();
    }

    function toggleDeleteMode() {
        deleteMode = !deleteMode;
        if (deleteMode) {
            linkMode = false;
            selectedNodeForLink = null;
            const btnLink = document.getElementById('btnToggleLinkMode');
            if (btnLink) {
                btnLink.classList.remove('btn-cyber-amber');
                btnLink.classList.add('btn-cyber');
                btnLink.innerHTML = '<i class="fa-solid fa-link me-1"></i> Sambung / Putus Jalur';
            }
        }

        const btn = document.getElementById('btnToggleDeleteMode');
        const hint = document.getElementById('networkModeHint');

        if (deleteMode) {
            if (btn) {
                btn.classList.add('btn-cyber-red');
                btn.classList.remove('btn-cyber');
                btn.innerHTML = '<i class="fa-solid fa-trash-can text-white me-1"></i> Mode Hapus: ON';
            }
            if (hint) {
                hint.innerHTML = '<i class="fa-solid fa-trash-can text-danger me-1"></i> <strong class="text-danger">MODE HAPUS AKTIF:</strong> Klik node mana saja pada peta untuk langsung menghapusnya satu per satu.';
            }
            logNetwork('[MODE HAPUS] Mode Hapus Node Aktif. Klik node pada peta untuk menghapusnya satu per satu.');
        } else {
            if (btn) {
                btn.classList.remove('btn-cyber-red');
                btn.classList.add('btn-cyber');
                btn.innerHTML = '<i class="fa-solid fa-trash-can me-1"></i> Hapus Node (Mode)';
            }
            if (hint) {
                hint.innerHTML = '<i class="fa-solid fa-circle-info text-info me-1"></i> <strong>Mode Geser (Drag):</strong> Klik/tahan node untuk geser. <strong>Klik Ganda node untuk EDIT</strong>, atau tombol <strong>[+ Tambah Node]</strong> untuk node baru.';
            }
            logNetwork('[MODE NORMAL] Kembali ke mode Geser (Drag & Drop) & Inspeksi node.');
        }
        sfxClick();
    }

    function resizeNetworkCanvas() {
        if (!canvas) return;
        const parentWidth = canvas.parentElement ? canvas.parentElement.clientWidth : 800;
        canvas.width = Math.max(parentWidth || 600, 320);
        canvas.height = 400;
    }

    function startNetworkAnimation() {
        if (animFrameId) cancelAnimationFrame(animFrameId);
        function loop() {
            packetT = (packetT + (attackActive ? 0.03 : 0.012)) % 1;
            radarAngle = (radarAngle + 0.02) % (Math.PI * 2);
            drawNetwork();
            animFrameId = requestAnimationFrame(loop);
        }
        loop();
    }

    function drawNetwork() {
        if (!ctx || !canvas || canvas.width === 0) return;
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        const w = canvas.width;
        const h = canvas.height;
        const cx = w / 2;
        const cy = h / 2;

        // Draw Matrix Radar Grid Background
        ctx.save();
        ctx.strokeStyle = 'rgba(0, 255, 102, 0.08)';
        ctx.lineWidth = 1;
        
        // Concentric Rings
        for (let r = 40; r < Math.max(w, h); r += 60) {
            ctx.beginPath();
            ctx.arc(cx, cy, r, 0, Math.PI * 2);
            ctx.stroke();
        }
        // Crosshair Lines
        ctx.beginPath();
        ctx.moveTo(0, cy); ctx.lineTo(w, cy);
        ctx.moveTo(cx, 0); ctx.lineTo(cx, h);
        ctx.stroke();

        // If Unmapped / Empty: Draw Radar Scan Sweep & Prompt
        if (!isScanned || nodes.length === 0) {
            // Radar Sweep Beam
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.arc(cx, cy, Math.max(w, h), radarAngle - 0.35, radarAngle);
            ctx.closePath();
            const grad = ctx.createRadialGradient(cx, cy, 10, cx, cy, Math.max(w, h));
            grad.addColorStop(0, 'rgba(0, 255, 102, 0.25)');
            grad.addColorStop(1, 'rgba(0, 255, 102, 0.0)');
            ctx.fillStyle = grad;
            ctx.fill();

            // Center Prompt Box
            ctx.fillStyle = 'rgba(2, 8, 3, 0.88)';
            ctx.strokeStyle = 'rgba(0, 255, 102, 0.5)';
            ctx.lineWidth = 1.5;
            const bw = Math.min(420, w - 40);
            const bh = 100;
            ctx.fillRect(cx - bw/2, cy - bh/2, bw, bh);
            ctx.strokeRect(cx - bw/2, cy - bh/2, bw, bh);

            ctx.fillStyle = '#39ff14';
            ctx.font = 'bold 13px monospace';
            ctx.textAlign = 'center';
            ctx.fillText('⚠ TOPOLOGY UNMAPPED', cx, cy - 14);

            ctx.fillStyle = '#a7f3d0';
            ctx.font = '11px monospace';
            ctx.fillText('Klik tombol [ 🛰️ SCAN SUBNET ] di atas', cx, cy + 8);
            ctx.fillText('untuk mendeteksi arsitektur jaringan target misi aktif.', cx, cy + 26);

            ctx.restore();
            return;
        }

        ctx.restore();

        // Draw Links / Connections between Nodes
        links.forEach(([id1, id2]) => {
            const n1 = nodes.find(n => n.id === id1);
            const n2 = nodes.find(n => n.id === id2);
            if (!n1 || !n2) return;

            const x1 = n1.x * w;
            const y1 = n1.y * h;
            const x2 = n2.x * w;
            const y2 = n2.y * h;

            // Link Line
            ctx.beginPath();
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.strokeStyle = attackActive ? 'rgba(239, 68, 68, 0.8)' : 'rgba(0, 255, 102, 0.35)';
            ctx.lineWidth = attackActive ? 2.5 : 1.8;
            ctx.stroke();

            // Flowing Packet Particle
            const px = x1 + (x2 - x1) * packetT;
            const py = y1 + (y2 - y1) * packetT;
            ctx.beginPath();
            ctx.arc(px, py, attackActive ? 4.5 : 3.5, 0, Math.PI * 2);
            ctx.fillStyle = attackActive ? '#ef4444' : '#39ff14';
            ctx.shadowColor = attackActive ? '#ef4444' : '#39ff14';
            ctx.shadowBlur = 10;
            ctx.fill();
            ctx.shadowBlur = 0;
        });

        // Draw Nodes
        nodes.forEach(n => {
            const nx = n.x * w;
            const ny = n.y * h;
            const isSelected = selectedNodeForLink && selectedNodeForLink.id === n.id;

            // Pulsing Selected Ring in Link Mode
            if (isSelected) {
                ctx.save();
                ctx.beginPath();
                ctx.arc(nx, ny, 28, 0, Math.PI * 2);
                ctx.strokeStyle = '#f59e0b';
                ctx.lineWidth = 2.5;
                ctx.setLineDash([4, 4]);
                ctx.stroke();
                ctx.restore();
            }

            // Outer Glow Halo
            ctx.beginPath();
            ctx.arc(nx, ny, 19, 0, Math.PI * 2);
            ctx.fillStyle = n.color;
            ctx.shadowColor = n.color;
            ctx.shadowBlur = isSelected ? 22 : 14;
            ctx.fill();
            ctx.shadowBlur = 0;

            // Inner Core
            ctx.beginPath();
            ctx.arc(nx, ny, 10, 0, Math.PI * 2);
            ctx.fillStyle = '#020703';
            ctx.fill();

            // Center LED Dot
            ctx.beginPath();
            ctx.arc(nx, ny, 4, 0, Math.PI * 2);
            ctx.fillStyle = n.color;
            ctx.fill();

            // Node Labels
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 11px monospace';
            ctx.textAlign = 'center';
            ctx.fillText(n.label, nx, ny + 32);

            ctx.fillStyle = n.color;
            ctx.font = '9px monospace';
            ctx.fillText(`[${n.status}]`, nx, ny + 45);
        });
    }

    function scanNetworkNodes() {
        const cur = MISSIONS[currentMissionIdx] || MISSIONS[0];
        logNetwork(`[SCAN INITIATED] Meluncurkan probe ARP / TCP SYN ke subnet target ${cur.targetIP}...`);
        sfxRadar();

        isScanned = true;
        nodes = generateMissionNodes(cur);
        links = generateMissionLinks(nodes);
        updateTopologyBadge();

        setTimeout(() => {
            logNetwork(`[DISCOVERY SUCCESS] Subnet terpetakan! Ditemukan ${nodes.length} node & ${links.length} jalur koneksi.`);
            sfxDecrypted();
        }, 500);
    }

    function resetNetworkMap() {
        nodes = [];
        links = [];
        isScanned = false;
        selectedNodeForLink = null;
        updateTopologyBadge();
        logNetwork('[RESET] Topologi jaringan dikosongkan. Peta siap dipindai kembali.');
        sfxClick();
    }

    function openAddNodeModal() {
        const idx = nodes.length + 1;
        const modalEl = document.getElementById('modalNodeEditor');
        const titleEl = document.getElementById('nodeEditorTitle');
        const idInp = document.getElementById('nodeEditId');
        const labelInp = document.getElementById('nodeEditLabel');
        const ipInp = document.getElementById('nodeEditIP');
        const portInp = document.getElementById('nodeEditPort');
        const statusSel = document.getElementById('nodeEditStatus');
        const delBtn = document.getElementById('btnDeleteEditingNode');

        if (idInp) idInp.value = '';
        if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-plus text-success me-1"></i> TAMBAH NODE JARINGAN BARU';
        if (labelInp) {
            labelInp.value = 'Relay Proxy #' + idx;
            setTimeout(() => { labelInp.focus(); labelInp.select(); }, 400);
        }
        if (ipInp) ipInp.value = '10.0.' + Math.floor(Math.random() * 50) + '.' + (100 + idx);
        if (portInp) portInp.value = '8080/Proxy';
        if (statusSel) statusSel.value = 'Active|#39ff14';
        if (delBtn) delBtn.classList.add('d-none');

        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
        sfxClick();
    }

    function openEditNodeModal(node) {
        if (!node) return;
        const modalEl = document.getElementById('modalNodeEditor');
        const titleEl = document.getElementById('nodeEditorTitle');
        const idInp = document.getElementById('nodeEditId');
        const labelInp = document.getElementById('nodeEditLabel');
        const ipInp = document.getElementById('nodeEditIP');
        const portInp = document.getElementById('nodeEditPort');
        const statusSel = document.getElementById('nodeEditStatus');
        const delBtn = document.getElementById('btnDeleteEditingNode');

        if (idInp) idInp.value = node.id;
        if (titleEl) titleEl.innerHTML = `<i class="fa-solid fa-pen-to-square text-warning me-1"></i> EDIT NODE: ${node.label}`;
        if (labelInp) {
            labelInp.value = node.label || '';
            setTimeout(() => { labelInp.focus(); labelInp.select(); }, 400);
        }
        if (ipInp) ipInp.value = node.ip || '';
        if (portInp) portInp.value = node.port || '80/HTTP';
        
        // Match status & color option
        if (statusSel) {
            let found = false;
            for (let opt of statusSel.options) {
                const [optStatus] = opt.value.split('|');
                if (optStatus.toLowerCase() === (node.status || '').toLowerCase()) {
                    statusSel.value = opt.value;
                    found = true;
                    break;
                }
            }
            if (!found) {
                statusSel.value = `${node.status || 'Active'}|${node.color || '#39ff14'}`;
            }
        }
        if (delBtn) delBtn.classList.remove('d-none');

        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
        sfxClick();
    }

    function saveNodeEditor() {
        const idInp = document.getElementById('nodeEditId');
        const labelInp = document.getElementById('nodeEditLabel');
        const ipInp = document.getElementById('nodeEditIP');
        const portInp = document.getElementById('nodeEditPort');
        const statusSel = document.getElementById('nodeEditStatus');

        const nodeId = idInp ? idInp.value.trim() : '';
        const label = (labelInp ? labelInp.value.trim() : '') || 'Node Host';
        const ip = (ipInp ? ipInp.value.trim() : '') || '192.168.1.100';
        const port = (portInp ? portInp.value.trim() : '') || '80/HTTP';
        const statusVal = statusSel ? statusSel.value : 'Active|#39ff14';
        const [status, color] = statusVal.split('|');

        if (nodeId) {
            // Edit existing node
            const target = nodes.find(n => n.id === nodeId);
            if (target) {
                target.label = label;
                target.ip = ip;
                target.port = port;
                target.status = status;
                target.color = color || target.color;
                logNetwork(`[NODE UPDATED] Data node '${label}' (${ip}) berhasil disimpan.`);
                sfxSuccess();
            }
        } else {
            // Add new custom single node (without generating default mission nodes)
            isScanned = true;
            const newId = 'custom_' + Date.now();
            const newNode = {
                id: newId,
                label: label,
                ip: ip,
                x: nodes.length === 0 ? 0.50 : (0.2 + Math.random() * 0.6),
                y: nodes.length === 0 ? 0.50 : (0.2 + Math.random() * 0.6),
                color: color || '#39ff14',
                status: status,
                port: port
            };
            nodes.push(newNode);

            if (nodes.length > 1) {
                const nearest = nodes[0];
                links.push([newNode.id, nearest.id]);
            }
            logNetwork(`[NODE ADDED] Node baru '${label}' (${ip}) [${status}] berhasil ditambahkan ke topologi.`);
            sfxDecrypted();
        }

        updateTopologyBadge();
        const modalEl = document.getElementById('modalNodeEditor');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }
    }

    function deleteCurrentEditingNode() {
        const idInp = document.getElementById('nodeEditId');
        const nodeId = idInp ? idInp.value.trim() : '';
        if (!nodeId) return;

        const targetNode = nodes.find(n => n.id === nodeId);
        const name = targetNode ? targetNode.label : nodeId;

        nodes = nodes.filter(n => n.id !== nodeId);
        links = links.filter(l => l[0] !== nodeId && l[1] !== nodeId);
        if (nodes.length === 0) {
            isScanned = false;
        }

        updateTopologyBadge();
        logNetwork(`[NODE DELETED] Node '${name}' dan jalur koneksinya telah dihapus dari topologi.`);
        sfxLaser();

        const modalEl = document.getElementById('modalNodeEditor');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }
    }

    function addCustomNode() {
        openAddNodeModal();
    }

    function launchNodeAttack() {
        if (!isScanned || nodes.length === 0) {
            scanNetworkNodes();
        }
        logNetwork('[ATTACK] Meluncurkan injeksi exploit payload melintasi seluruh jalur topologi...');
        sfxLaser();
        attackActive = true;

        setTimeout(() => {
            const targetNode = nodes.find(n => n.status === 'Target' || n.status === 'Secured') || nodes[nodes.length - 1];
            if (targetNode) {
                targetNode.status = 'Breached';
                targetNode.color = '#00ff66';
                logNetwork(`[EXPLOIT SUCCESS] Node target '${targetNode.label}' (${targetNode.ip}) PENETRATED! Root access verified.`);
                sfxRootDrop();
            }
            attackActive = false;
        }, 850);
    }

    function interceptPackets() {
        if (!isScanned || nodes.length === 0) {
            scanNetworkNodes();
        }
        const cur = MISSIONS[currentMissionIdx] || MISSIONS[0];
        logNetwork(`[SNIFFER] Menyadap paket subnet ${cur.targetIP}... Ditemukan hash signature: ${cur.targetHash.substring(0, 16)}...`);
        sfxModem();
    }

    function logNetwork(msg) {
        const box = document.getElementById('networkLog');
        if (!box) return;
        const line = document.createElement('div');
        line.textContent = `[${new Date().toLocaleTimeString()}] ${msg}`;
        box.appendChild(line);
        box.scrollTop = box.scrollHeight;
    }

    // -------------------------------------------------------------
    // QUICK GAME: PORT MATRIX BREAKER
    // -------------------------------------------------------------
    const HEX_POOL = ['7A', 'FF', '09', '3C', '1B', 'E4', '5D', '88', 'A1', 'C0', '9F', '2E'];
    let targetSeq = [];
    let userBuffer = [];
    let quickScore = 0;
    let quickRound = 1;
    let quickTimerVal = 30;
    let quickTimerInterval = null;

    function startQuickGame() {
        quickScore = 0;
        quickRound = 1;
        quickTimerVal = 30;
        const sEl = document.getElementById('quickScore');
        if (sEl) sEl.textContent = quickScore;
        const rEl = document.getElementById('quickRound');
        if (rEl) rEl.textContent = `${quickRound}/5`;
        const btnStart = document.getElementById('btnStartQuickGame');
        if (btnStart) btnStart.disabled = true;

        nextQuickRound();

        if (quickTimerInterval) clearInterval(quickTimerInterval);
        quickTimerInterval = setInterval(() => {
            quickTimerVal--;
            const tEl = document.getElementById('quickTimer');
            if (tEl) tEl.textContent = `${quickTimerVal}s`;
            if (quickTimerVal <= 0) {
                clearInterval(quickTimerInterval);
                if (btnStart) btnStart.disabled = false;
                sfxAlert();
                alert(`Game Over! Firewall timed out. Skor Akhir: ${quickScore}`);
            }
        }, 1000);
    }

    function nextQuickRound() {
        targetSeq = [];
        userBuffer = [];
        for (let i = 0; i < 4; i++) {
            targetSeq.push(HEX_POOL[Math.floor(Math.random() * HEX_POOL.length)]);
        }

        const tSeq = document.getElementById('quickTargetSeq');
        if (tSeq) tSeq.textContent = targetSeq.join(' : ');
        const uBuf = document.getElementById('quickUserBuffer');
        if (uBuf) uBuf.textContent = '_ _ _ _';

        renderHexMatrix();
    }

    function renderHexMatrix() {
        const container = document.getElementById('quickHexMatrix');
        if (!container) return;
        container.innerHTML = '';

        const shuffled = [...HEX_POOL].sort(() => Math.random() - 0.5);
        shuffled.forEach(hex => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn-cyber font-monospace py-2 px-3 fw-bold';
            btn.textContent = hex;
            btn.onclick = () => tapHex(hex);
            container.appendChild(btn);
        });
    }

    function tapHex(hex) {
        if (userBuffer.length >= 4) return;
        userBuffer.push(hex);
        const uBuf = document.getElementById('quickUserBuffer');
        if (uBuf) uBuf.textContent = userBuffer.join(' : ');
        sfxClick();

        if (userBuffer.length === 4) {
            if (userBuffer.join('') === targetSeq.join('')) {
                quickScore += 100;
                playerXP += 25;
                const sEl = document.getElementById('quickScore');
                if (sEl) sEl.textContent = quickScore;
                updateHUD();
                sfxDecrypted();
                logGlobal(`[RAPID HACK] Firewall level breached! +100 Pts`);

                if (quickRound < 5) {
                    quickRound++;
                    const rEl = document.getElementById('quickRound');
                    if (rEl) rEl.textContent = `${quickRound}/5`;
                    setTimeout(nextQuickRound, 500);
                } else {
                    clearInterval(quickTimerInterval);
                    const btnStart = document.getElementById('btnStartQuickGame');
                    if (btnStart) btnStart.disabled = false;
                    recordServerScore(quickRound, quickScore);
                    sfxVictoryFanfare();
                    alert(`CONGRATULATIONS! Seluruh 5 Lapisan Firewall Telah Dibobol! Total Score: ${quickScore}`);
                }
            } else {
                sfxAlert();
                if (uBuf) uBuf.textContent = 'MISMATCH! TRY AGAIN';
                setTimeout(() => {
                    userBuffer = [];
                    if (uBuf) uBuf.textContent = '_ _ _ _';
                }, 600);
            }
        }
    }

    function resetQuickBuffer() {
        userBuffer = [];
        const uBuf = document.getElementById('quickUserBuffer');
        if (uBuf) uBuf.textContent = '_ _ _ _';
        sfxClick();
    }

    function startStatsFluctuation() {
        setInterval(() => {
            const cpu = Math.floor(30 + Math.random() * 35);
            const mem = (1.2 + Math.random() * 0.5).toFixed(1);
            const traf = (4.5 + Math.random() * 4.0).toFixed(1);
            const conns = Math.floor(10 + Math.random() * 8);

            const cpuEl = document.getElementById('statCpu');
            if (cpuEl) cpuEl.textContent = `${cpu}%`;
            const memEl = document.getElementById('statMem');
            if (memEl) memEl.textContent = `${mem} GB / 8 GB`;
            const trafEl = document.getElementById('statTraffic');
            if (trafEl) trafEl.textContent = `${traf} MB/s`;
            const conEl = document.getElementById('statConnections');
            if (conEl) conEl.textContent = `${conns} Sockets`;
        }, 3000);
    }

    return {
        init,
        selectMission,
        switchTab,
        setHashType,
        setAttackMethod,
        startCracking,
        stopCracking,
        loadMissionHash,
        setCipherMethod,
        processCipher,
        loadInterceptedCipher,
        sendResultToCracker,
        completeCurrentMission,
        runQuickCommand,
        execShellCommand,
        scanNetworkNodes,
        toggleLinkMode,
        toggleDeleteMode,
        resetNetworkMap,
        launchNodeAttack,
        interceptPackets,
        addCustomNode,
        openAddNodeModal,
        openEditNodeModal,
        saveNodeEditor,
        deleteCurrentEditingNode,
        startQuickGame,
        resetQuickBuffer,
        openTutorialModal,
        execGuidedNextStep,
        triggerFullAutoHack,
        triggerAutoDeface,
        triggerAutoRecovery,
        filterMissions,
        setStudioMode,
        defaceVerifyStep1,
        defaceVerifyStep2,
        defaceVerifyStep3,
        injectDefacePayload,
        recVerifyStep1,
        recVerifyStep2,
        recVerifyStep3,
        recVerifyStep4,
        completeEmergencyRestore,
        clearLog,
        sfxClick,
        sfxTab
    };
})();

window.HackerApp = HackerApp;

document.addEventListener('DOMContentLoaded', () => {
    HackerApp.init();
});
