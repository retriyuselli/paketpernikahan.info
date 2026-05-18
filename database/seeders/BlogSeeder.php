<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title'        => '8 Etika Silaturahmi ke Rumah Calon Mertua demi Beri Kesan Positif',
                'category'     => 'Relationship Tips',
                'excerpt'      => 'Pertemuan pertama dengan calon mertua adalah momen krusial. Simak 8 etika penting yang harus kamu perhatikan agar memberikan kesan positif.',
                'content'      => '<p>Mengunjungi rumah calon mertua untuk pertama kali tentu membuat deg-degan. Namun, dengan persiapan yang matang dan etika yang tepat, kamu bisa memberikan kesan terbaik kepada keluarga pasangan.</p><p>Berikut adalah 8 etika yang perlu kamu perhatikan:</p><ol><li>Datang tepat waktu dan jangan terlambat.</li><li>Berpakaian sopan dan rapi sesuai budaya keluarga pasangan.</li><li>Bawa buah tangan yang berkesan.</li><li>Sapa semua anggota keluarga dengan hormat.</li><li>Jadilah pendengar yang baik.</li><li>Jangan bermain ponsel saat berbincang.</li><li>Tawarkan bantuan dengan tulus.</li><li>Pamit dengan sopan dan ucapkan terima kasih.</li></ol>',
                'cover_image'  => null,
                'tags'         => ['mertua', 'silaturahmi', 'relationship'],
                'is_published' => true,
                'published_at' => '2026-03-17 08:00:00',
                'views_count'  => 0,
                'sort_order'   => 1,
            ],
            [
                'title'        => 'Begini Cara Atur THR untuk DP Vendor tanpa Ganggu Budget Lebaran',
                'category'     => 'Budget Planning',
                'excerpt'      => 'Mendapat THR saat mau menikah adalah berkah tersendiri. Yuk, kelola dengan bijak agar bisa bayar DP vendor sekaligus tetap menikmati momen Lebaran.',
                'content'      => '<p>Bagi pasangan yang sedang dalam proses persiapan pernikahan, momen THR Lebaran bisa menjadi kesempatan emas untuk membayar down payment (DP) vendor.</p><p>Berikut tips mengatur THR agar tidak mengorbankan kebutuhan Lebaran:</p><ul><li>Buat daftar prioritas vendor yang harus dibayar DP-nya.</li><li>Sisihkan 40% THR untuk keperluan Lebaran (baju, mudik, THR ke orang tua).</li><li>Alokasikan 50% untuk DP vendor pernikahan.</li><li>Sisakan 10% sebagai dana darurat.</li></ul>',
                'cover_image'  => null,
                'tags'         => ['thr', 'budget', 'vendor', 'lebaran'],
                'is_published' => true,
                'published_at' => '2026-03-16 09:00:00',
                'views_count'  => 0,
                'sort_order'   => 2,
            ],
            [
                'title'        => '12 Tahap dalam Susunan Acara Lamaran Pernikahan',
                'category'     => 'Wedding Ideas',
                'excerpt'      => 'Lamaran adalah langkah awal yang sakral. Kenali 12 tahap susunan acara lamaran pernikahan agar momen spesial ini berjalan lancar dan berkesan.',
                'content'      => '<p>Acara lamaran pernikahan merupakan salah satu momen yang sangat dinantikan oleh kedua keluarga. Berikut susunan acara yang umum dilakukan:</p><ol><li>Pembukaan oleh pembawa acara.</li><li>Sambutan dari keluarga pihak laki-laki.</li><li>Sambutan dari keluarga pihak perempuan.</li><li>Penyerahan hantaran lamaran.</li><li>Penyerahan cincin pertunangan.</li><li>Acara makan bersama.</li><li>Doa bersama.</li><li>Sesi foto bersama kedua keluarga.</li><li>Diskusi tanggal pernikahan.</li><li>Ramah tamah.</li><li>Penutup.</li><li>Foto kenangan.</li></ol>',
                'cover_image'  => null,
                'tags'         => ['lamaran', 'acara', 'susunan acara'],
                'is_published' => true,
                'published_at' => '2026-02-20 08:00:00',
                'views_count'  => 0,
                'sort_order'   => 3,
            ],
            [
                'title'        => '18 Ide Unik dan Romantis untuk Melamar Sang Kekasih',
                'category'     => 'Wedding Ideas',
                'excerpt'      => 'Proposal yang berkesan tidak harus mahal. Temukan 18 ide lamaran unik dan romantis yang pasti membuat pasanganmu terkejut dan bahagia.',
                'content'      => '<p>Melamar adalah momen yang akan dikenang seumur hidup. Jadikan momen tersebut istimewa dengan ide-ide kreatif berikut ini.</p><p>Dari flash mob di tempat favorit kalian, hingga proposal sederhana di rumah dengan dekorasi lampu yang hangat — semua tergantung dari karakter pasanganmu.</p>',
                'cover_image'  => null,
                'tags'         => ['lamaran', 'romantis', 'proposal', 'ide'],
                'is_published' => true,
                'published_at' => '2026-02-10 09:00:00',
                'views_count'  => 0,
                'sort_order'   => 4,
            ],
            [
                'title'        => 'Panduan Rangkaian Prosesi Pernikahan Adat Jawa Beserta Makna di Balik Setiap Ritualnya',
                'category'     => 'Wedding Ideas',
                'excerpt'      => 'Pernikahan adat Jawa kaya akan simbolisme dan makna. Pelajari setiap prosesi dari siraman hingga resepsi dalam panduan lengkap ini.',
                'content'      => '<p>Pernikahan adat Jawa merupakan salah satu warisan budaya yang sangat kaya. Setiap prosesi memiliki makna filosofis yang mendalam.</p><h2>Tahapan Prosesi Pernikahan Adat Jawa</h2><p><strong>1. Siraman</strong> — Ritual mandi air bunga yang melambangkan pembersihan diri sebelum hari pernikahan.</p><p><strong>2. Midodareni</strong> — Malam sebelum akad, pengantin wanita tidak boleh keluar rumah.</p><p><strong>3. Ijab Qabul / Akad</strong> — Prosesi peresmian pernikahan secara agama.</p><p><strong>4. Panggih</strong> — Pertemuan kedua mempelai dengan berbagai ritual simbolis.</p><p><strong>5. Resepsi</strong> — Pesta perayaan bersama seluruh keluarga dan tamu undangan.</p>',
                'cover_image'  => null,
                'tags'         => ['adat jawa', 'prosesi', 'pernikahan tradisional'],
                'is_published' => true,
                'published_at' => '2026-01-15 10:00:00',
                'views_count'  => 0,
                'sort_order'   => 5,
            ],
            [
                'title'        => 'Tips Memilih Fotografer Pernikahan yang Tepat untuk Hari Spesialmu',
                'category'     => 'Photography',
                'excerpt'      => 'Fotografer pernikahan adalah investasi kenangan abadi. Berikut panduan lengkap memilih fotografer yang sesuai gaya dan budget-mu.',
                'content'      => '<p>Memilih fotografer pernikahan bukan sekadar soal harga. Ada banyak faktor yang perlu dipertimbangkan agar foto-foto pernikahanmu menjadi karya yang indah dan berkesan.</p><ul><li>Periksa portofolio secara menyeluruh.</li><li>Pastikan gaya fotografer sesuai dengan selera kamu.</li><li>Diskusikan rundown dan shot list sebelum hari H.</li><li>Baca ulasan dari klien sebelumnya.</li><li>Pastikan kontrak sudah jelas dan tertulis.</li></ul>',
                'cover_image'  => null,
                'tags'         => ['fotografer', 'tips', 'pernikahan'],
                'is_published' => true,
                'published_at' => '2026-01-05 08:00:00',
                'views_count'  => 0,
                'sort_order'   => 6,
            ],
            [
                'title'        => 'Cara Membuat Checklist Pernikahan agar Tidak Ada yang Terlewat',
                'category'     => 'Wedding Planning',
                'excerpt'      => 'Persiapan pernikahan bisa terasa overwhelming. Dengan checklist yang tepat, semua kebutuhan hari H bisa terpenuhi tanpa stres.',
                'content'      => '<p>Merencanakan pernikahan membutuhkan koordinasi ratusan detail kecil. Checklist adalah senjata utama setiap pasangan yang ingin hari H berjalan sempurna.</p><h2>Checklist 12 Bulan Sebelum Pernikahan</h2><ul><li>Tentukan tanggal dan venue pernikahan.</li><li>Susun estimasi budget keseluruhan.</li><li>Buat daftar tamu undangan sementara.</li><li>Pilih konsep dan tema pernikahan.</li></ul><h2>Checklist 6 Bulan Sebelum</h2><ul><li>Book fotografer dan videografer.</li><li>Cari dan book catering.</li><li>Pilih vendor dekorasi dan bunga.</li><li>Mulai cari gaun pengantin.</li></ul><h2>Checklist 1 Bulan Sebelum</h2><ul><li>Konfirmasi ulang semua vendor.</li><li>Kirim undangan ke seluruh tamu.</li><li>Fitting gaun dan baju pengantin pria.</li><li>Susun rundown acara detail.</li></ul>',
                'cover_image'  => null,
                'tags'         => ['checklist', 'pernikahan', 'planning'],
                'is_published' => true,
                'published_at' => '2026-03-20 08:00:00',
                'views_count'  => 0,
                'sort_order'   => 7,
            ],
            [
                'title'        => 'Berapa Biaya Pernikahan di Gedung vs Outdoor? Ini Perbandingannya',
                'category'     => 'Budget Planning',
                'excerpt'      => 'Bingung memilih gedung atau outdoor untuk resepsi? Simak perbandingan biaya dan kelebihan masing-masing pilihan venue pernikahan.',
                'content'      => '<p>Pemilihan venue adalah salah satu keputusan terbesar dalam persiapan pernikahan. Dua pilihan populer adalah gedung pernikahan dan lokasi outdoor.</p><h2>Pernikahan di Gedung</h2><p><strong>Kelebihan:</strong> Tidak tergantung cuaca, fasilitas lengkap, AC tersedia, parkir luas.</p><p><strong>Perkiraan biaya:</strong> Rp 30 juta – Rp 200 juta tergantung kapasitas dan lokasi.</p><h2>Pernikahan Outdoor</h2><p><strong>Kelebihan:</strong> Suasana alami dan romantis, foto lebih estetik, biaya sewa bisa lebih terjangkau.</p><p><strong>Perkiraan biaya:</strong> Rp 15 juta – Rp 100 juta, namun perlu tambahan tenda dan generator.</p><h2>Tips Memilih</h2><ul><li>Sesuaikan dengan jumlah tamu undangan.</li><li>Pertimbangkan musim hujan jika memilih outdoor.</li><li>Bandingkan harga minimal 3 venue sebelum memutuskan.</li></ul>',
                'cover_image'  => null,
                'tags'         => ['venue', 'gedung', 'outdoor', 'budget'],
                'is_published' => true,
                'published_at' => '2026-03-25 09:00:00',
                'views_count'  => 0,
                'sort_order'   => 8,
            ],
            [
                'title'        => 'Inspirasi Dekorasi Pernikahan Minimalis yang Elegan dan Hemat Budget',
                'category'     => 'Wedding Ideas',
                'excerpt'      => 'Dekorasi mewah tidak harus mahal. Temukan inspirasi dekorasi pernikahan minimalis yang tetap elegan dan memukau tamu undangan.',
                'content'      => '<p>Tren dekorasi pernikahan minimalis semakin digemari pasangan modern. Dengan konsep yang tepat, dekorasi sederhana pun bisa tampil mewah dan berkesan.</p><h2>Elemen Kunci Dekorasi Minimalis</h2><ul><li><strong>Palet warna netral</strong> — putih, krem, sage green, atau dusty rose.</li><li><strong>Bunga segar</strong> — pilih jenis bunga lokal yang lebih terjangkau seperti krisan, mawar lokal, atau baby breath.</li><li><strong>Pencahayaan hangat</strong> — fairy lights atau candle holder menciptakan atmosfer romantis.</li><li><strong>Greenery</strong> — tanaman hijau sebagai dekorasi utama yang murah meriah.</li></ul><h2>Tips Hemat</h2><ul><li>Sewa dekorasi daripada membeli.</li><li>DIY beberapa elemen dekorasi kecil.</li><li>Fokus pada area foto utama seperti backdrop dan meja pengantin.</li></ul>',
                'cover_image'  => null,
                'tags'         => ['dekorasi', 'minimalis', 'inspirasi', 'wedding ideas'],
                'is_published' => true,
                'published_at' => '2026-04-01 08:00:00',
                'views_count'  => 0,
                'sort_order'   => 9,
            ],
            [
                'title'        => 'Panduan Memilih Catering Pernikahan: Dari Prasmanan hingga Plated Dinner',
                'category'     => 'Wedding Planning',
                'excerpt'      => 'Catering adalah salah satu hal yang paling diingat tamu. Pelajari cara memilih catering yang lezat, sesuai tema, dan tepat budget.',
                'content'      => '<p>Makanan adalah salah satu hal yang paling diingat tamu setelah acara selesai. Memilih catering pernikahan yang tepat sangat penting untuk keberhasilan resepsi.</p><h2>Jenis Catering Pernikahan</h2><ol><li><strong>Prasmanan</strong> — Paling umum di Indonesia, tamu bebas mengambil makanan sesuai selera. Budget lebih terjangkau.</li><li><strong>Plated Dinner</strong> — Makanan disajikan langsung ke meja tamu. Berkesan lebih formal dan mewah.</li><li><strong>Food Station</strong> — Beberapa booth makanan berbeda tema, cocok untuk pernikahan casual.</li><li><strong>Kotak</strong> — Praktis dan efisien, cocok untuk pernikahan sederhana.</li></ol><h2>Tips Memilih Catering</h2><ul><li>Lakukan food tasting sebelum memutuskan.</li><li>Tanyakan pengalaman di event dengan kapasitas serupa.</li><li>Pastikan ada opsi untuk tamu vegetarian atau alergi makanan.</li><li>Cek ulasan dan reputasi catering.</li></ul>',
                'cover_image'  => null,
                'tags'         => ['catering', 'makanan', 'pernikahan', 'tips'],
                'is_published' => true,
                'published_at' => '2026-04-05 09:00:00',
                'views_count'  => 0,
                'sort_order'   => 10,
            ],
            [
                'title'        => '7 Kesalahan Umum dalam Perencanaan Pernikahan yang Harus Dihindari',
                'category'     => 'Wedding Planning',
                'excerpt'      => 'Banyak pasangan melakukan kesalahan yang sama saat merencanakan pernikahan. Kenali 7 kesalahan umum ini agar kamu tidak mengulanginya.',
                'content'      => '<p>Perencanaan pernikahan adalah proses panjang yang penuh tekanan. Tidak sedikit pasangan yang terjebak dalam kesalahan-kesalahan yang sebenarnya bisa dihindari.</p><ol><li><strong>Tidak membuat anggaran sejak awal</strong> — Tanpa budget yang jelas, pengeluaran bisa membengkak tanpa disadari.</li><li><strong>Terlalu banyak melibatkan pendapat orang lain</strong> — Pernikahan ini milik kalian berdua, bukan keluarga besar.</li><li><strong>Book vendor terlalu dekat dengan hari H</strong> — Vendor favorit bisa sudah fully booked setahun sebelumnya.</li><li><strong>Mengabaikan detail kecil</strong> — Seperti sound system, transportasi tamu VIP, atau tempat ibadah di venue.</li><li><strong>Tidak ada rencana cadangan untuk cuaca</strong> — Terutama untuk pernikahan outdoor.</li><li><strong>Lupa alokasi waktu untuk istirahat</strong> — Pasangan perlu tetap segar di hari H.</li><li><strong>Tidak menyiapkan dana darurat</strong> — Selalu sisihkan 10-15% dari total budget untuk kejutan tak terduga.</li></ol>',
                'cover_image'  => null,
                'tags'         => ['kesalahan', 'planning', 'pernikahan', 'tips'],
                'is_published' => true,
                'published_at' => '2026-04-08 08:00:00',
                'views_count'  => 0,
                'sort_order'   => 11,
            ],
            [
                'title'        => 'Tren Gaun Pengantin 2026: Dari Bridal Minimalis hingga Ballgown Mewah',
                'category'     => 'Fashion & Beauty',
                'excerpt'      => 'Seperti apa tren gaun pengantin 2026? Dari siluet minimalis yang bersih hingga ballgown berhias detail rumit — temukan inspirasi untuk hari istimewamu.',
                'content'      => '<p>Industri fashion pengantin terus berkembang setiap tahunnya. Tahun 2026 menghadirkan beragam tren yang menarik untuk dijadikan inspirasi.</p><h2>Tren Utama Gaun Pengantin 2026</h2><ul><li><strong>Minimalist Sleek</strong> — Gaun dengan siluet bersih, tanpa banyak ornamen, cocok untuk pernikahan modern.</li><li><strong>Cape & Overskirt</strong> — Layer tambahan yang dramatis namun tetap elegan.</li><li><strong>Floral 3D</strong> — Aplikasi bunga tiga dimensi di bagian bodice atau skirt.</li><li><strong>Colored Wedding Dress</strong> — Gaun berwarna sage, champagne, atau blush pink semakin populer.</li><li><strong>Jumpsuit Pengantin</strong> — Pilihan modern bagi pengantin yang menginginkan kenyamanan maksimal.</li></ul><h2>Tips Memilih Gaun</h2><ul><li>Sesuaikan dengan tema dan venue pernikahan.</li><li>Pertimbangkan kenyamanan saat duduk, berdiri, dan berfoto.</li><li>Lakukan fitting minimal 2 bulan sebelum hari H.</li></ul>',
                'cover_image'  => null,
                'tags'         => ['gaun pengantin', 'fashion', 'tren 2026'],
                'is_published' => true,
                'published_at' => '2026-04-10 10:00:00',
                'views_count'  => 0,
                'sort_order'   => 12,
            ],
            [
                'title'        => 'Honeymoon Impian dengan Budget Terbatas: Destinasi Lokal yang Romantis',
                'category'     => 'Honeymoon',
                'excerpt'      => 'Bulan madu tidak harus ke luar negeri. Indonesia menyimpan banyak destinasi romantis yang bisa membuat momen honeymoon tak terlupakan.',
                'content'      => '<p>Setelah lelah mempersiapkan pernikahan, saatnya menikmati momen berdua dalam perjalanan honeymoon yang berkesan. Berikut rekomendasi destinasi lokal yang romantis dengan budget terjangkau.</p><h2>Destinasi Honeymoon Terbaik di Indonesia</h2><ol><li><strong>Raja Ampat, Papua Barat</strong> — Keindahan bawah laut yang tak tertandingi. Cocok untuk pasangan yang suka snorkeling dan diving.</li><li><strong>Labuan Bajo, NTT</strong> — Sunset terbaik di Indonesia, komodo, dan pulau-pulau eksotis.</li><li><strong>Ubud, Bali</strong> — Suasana tenang, alam hijau, spa mewah, dan budaya yang kaya.</li><li><strong>Lombok</strong> — Pantai bersih, Gunung Rinjani, dan suasana lebih sepi dari Bali.</li><li><strong>Belitung</strong> — Pantai batu granit yang ikonik dengan biaya perjalanan yang relatif terjangkau.</li></ol><h2>Tips Hemat Honeymoon</h2><ul><li>Book tiket dan hotel jauh-jauh hari untuk harga terbaik.</li><li>Pilih paket honeymoon dari vendor terpercaya.</li><li>Hindari perjalanan saat peak season.</li></ul>',
                'cover_image'  => null,
                'tags'         => ['honeymoon', 'bulan madu', 'destinasi', 'liburan'],
                'is_published' => true,
                'published_at' => '2026-04-12 09:00:00',
                'views_count'  => 0,
                'sort_order'   => 13,
            ],
            [
                'title'        => 'Undangan Pernikahan Digital vs Cetak: Mana yang Lebih Baik?',
                'category'     => 'Wedding Planning',
                'excerpt'      => 'Di era digital, undangan online semakin populer. Tapi undangan cetak tetap punya daya tarik tersendiri. Mana yang sebaiknya kamu pilih?',
                'content'      => '<p>Undangan pernikahan adalah kesan pertama tamu terhadap acara yang kamu selenggarakan. Di era digital ini, pilihan semakin beragam antara undangan digital dan cetak.</p><h2>Undangan Digital</h2><p><strong>Kelebihan:</strong></p><ul><li>Lebih hemat biaya dan ramah lingkungan.</li><li>Mudah disebarkan via WhatsApp, email, atau media sosial.</li><li>Bisa update informasi sewaktu-waktu.</li><li>Bisa ditambahkan fitur RSVP online, maps, dan galeri foto.</li></ul><p><strong>Kekurangan:</strong></p><ul><li>Tidak semua tamu, terutama generasi tua, nyaman dengan format digital.</li></ul><h2>Undangan Cetak</h2><p><strong>Kelebihan:</strong></p><ul><li>Memberikan kesan lebih personal dan mewah.</li><li>Bisa dijadikan kenangan yang disimpan.</li><li>Cocok untuk tamu yang tidak terbiasa dengan teknologi.</li></ul><p><strong>Kekurangan:</strong></p><ul><li>Biaya lebih tinggi, terutama untuk desain dan cetak premium.</li><li>Membutuhkan waktu distribusi yang lebih lama.</li></ul><h2>Solusi Terbaik</h2><p>Gunakan keduanya — undangan digital untuk tamu yang mudah dijangkau secara online, dan undangan cetak untuk keluarga inti dan tamu VIP.</p>',
                'cover_image'  => null,
                'tags'         => ['undangan', 'digital', 'cetak', 'pernikahan'],
                'is_published' => true,
                'published_at' => '2026-04-15 08:00:00',
                'views_count'  => 0,
                'sort_order'   => 14,
            ],
            [
                'title'        => 'Makna di Balik Prosesi Akad Nikah yang Perlu Kamu Tahu',
                'category'     => 'Wedding Ideas',
                'excerpt'      => 'Akad nikah bukan sekadar formalitas. Di balik setiap prosesinya tersimpan makna mendalam yang menjadi fondasi kehidupan rumah tangga.',
                'content'      => '<p>Akad nikah adalah inti dari seluruh rangkaian pernikahan. Memahami makna di balik setiap prosesinya akan membuat momen ini terasa lebih sakral dan berkesan.</p><h2>Prosesi dan Maknanya</h2><ol><li><strong>Ijab Qabul</strong> — Ikrar pernikahan yang diucapkan wali pengantin wanita dan diterima pengantin pria. Ini adalah momen paling sakral yang secara resmi menyatukan dua insan.</li><li><strong>Mahar</strong> — Pemberian dari pengantin pria sebagai simbol tanggung jawab dan komitmen untuk menafkahi. Mahar bisa berupa uang, perhiasan, atau ilmu yang bermanfaat.</li><li><strong>Saksi</strong> — Kehadiran minimal dua saksi menjadi bukti sahnya pernikahan di hadapan agama dan hukum.</li><li><strong>Wali Nikah</strong> — Representasi keluarga pengantin wanita yang memberikan restu dan perlindungan.</li><li><strong>Doa Bersama</strong> — Permohonan keberkahan untuk kehidupan rumah tangga yang sakinah, mawaddah, warahmah.</li></ol>',
                'cover_image'  => null,
                'tags'         => ['akad nikah', 'makna', 'prosesi', 'pernikahan'],
                'is_published' => true,
                'published_at' => '2026-04-18 10:00:00',
                'views_count'  => 0,
                'sort_order'   => 15,
            ],
            [
                'title'        => 'Tips Menjaga Kesehatan Mental Selama Persiapan Pernikahan',
                'category'     => 'Relationship Tips',
                'excerpt'      => 'Stres saat mempersiapkan pernikahan adalah hal yang sangat umum. Berikut cara menjaga kesehatan mental agar tetap bahagia hingga hari H.',
                'content'      => '<p>Persiapan pernikahan seringkali menjadi salah satu periode paling menegangkan dalam kehidupan seseorang. Wedding planning stress atau yang dikenal sebagai "bridezilla syndrome" bisa terjadi pada siapa saja.</p><h2>Tanda-tanda Stres Berlebihan</h2><ul><li>Mudah marah dan sensitif terhadap hal kecil.</li><li>Sulit tidur karena memikirkan detail pernikahan.</li><li>Sering bertengkar dengan pasangan atau keluarga.</li><li>Kehilangan antusiasme terhadap pernikahan itu sendiri.</li></ul><h2>Cara Mengatasinya</h2><ol><li><strong>Delegasikan tugas</strong> — Tidak semua harus kamu kerjakan sendiri. Libatkan pasangan, keluarga, atau gunakan jasa wedding organizer.</li><li><strong>Tetapkan batas waktu diskusi</strong> — Jangan biarkan obrolan pernikahan menguasai seluruh waktu bersama pasangan.</li><li><strong>Jadwalkan me-time</strong> — Luangkan waktu untuk aktivitas yang kamu nikmati di luar urusan pernikahan.</li><li><strong>Komunikasi terbuka dengan pasangan</strong> — Berbagi perasaan dan kekhawatiran agar tidak menumpuk.</li><li><strong>Ingat tujuan utama</strong> — Pernikahan adalah tentang cinta dan komitmen, bukan tentang pesta yang sempurna.</li></ol>',
                'cover_image'  => null,
                'tags'         => ['mental health', 'stres', 'persiapan pernikahan', 'tips'],
                'is_published' => true,
                'published_at' => '2026-04-20 09:00:00',
                'views_count'  => 0,
                'sort_order'   => 16,
            ],

            // === ARTIKEL LOKAL PALEMBANG ===

            [
                'title'        => 'Wedding Organizer Palembang Terbaik: Panduan Memilih WO yang Tepat',
                'category'     => 'Wedding Planning',
                'excerpt'      => 'Mencari wedding organizer terbaik di Palembang? Panduan lengkap ini membantu kamu memilih WO yang sesuai konsep, budget, dan kebutuhan pernikahanmu di Sumatera Selatan.',
                'content'      => '<p>Memilih <strong>wedding organizer di Palembang</strong> yang tepat adalah kunci sukses hari pernikahanmu. Dengan semakin banyaknya pilihan WO di Palembang dan sekitarnya, penting bagi calon pengantin untuk tahu kriteria apa yang harus dipertimbangkan sebelum memutuskan.</p>

<h2>Mengapa Membutuhkan Wedding Organizer di Palembang?</h2>
<p>Pernikahan di Palembang memiliki keunikan tersendiri — mulai dari tradisi adat Palembang yang kaya, pilihan venue dari tepian Sungai Musi hingga gedung modern, hingga kuliner khas Palembang yang wajib hadir di meja makan. Seorang <strong>wedding organizer Palembang</strong> yang berpengalaman akan memahami semua nuansa lokal ini dengan baik.</p>
<p>Selain itu, menggunakan jasa WO akan menghemat waktu, tenaga, dan bahkan uang — karena mereka sudah memiliki jaringan vendor lokal terpercaya dengan harga yang lebih baik dari harga pasaran.</p>

<h2>Jenis Wedding Organizer di Palembang</h2>
<h3>1. Full Wedding Organizer</h3>
<p>WO mengelola seluruh persiapan dari awal hingga hari H, termasuk pemilihan vendor, negosiasi harga, koordinasi, dan on-site management. Cocok untuk pasangan yang sibuk bekerja atau yang ingin pernikahan all-in-one tanpa pusing.</p>
<h3>2. Partial Wedding Organizer</h3>
<p>WO membantu di bagian-bagian tertentu saja — misalnya hanya dekorasi, atau hanya koordinasi di hari H. Harga lebih terjangkau dan kamu tetap bisa terlibat aktif dalam persiapan.</p>
<h3>3. Wedding Consultant / Planner</h3>
<p>Lebih ke arah konsultasi dan perencanaan, tanpa eksekusi langsung. Cocok jika kamu ingin mengurus sendiri tapi butuh panduan dari yang sudah berpengalaman.</p>

<h2>Tips Memilih Wedding Organizer di Palembang</h2>
<ol>
<li><strong>Cek portofolio dan pengalaman</strong> — Tanyakan sudah berapa pernikahan yang ditangani, terutama di Palembang. Kenali gaya dan estetika yang biasa mereka kerjakan.</li>
<li><strong>Baca ulasan dari klien sebelumnya</strong> — Testimoni nyata dari pasangan yang pernah menggunakan jasanya adalah referensi terpercaya.</li>
<li><strong>Tanyakan detail paket yang ditawarkan</strong> — Apa saja yang sudah termasuk? Berapa jumlah tim yang terjun di hari H? Apakah ada backup plan jika terjadi kendala?</li>
<li><strong>Pastikan chemistry yang baik</strong> — Kamu akan sering berkomunikasi dengan WO selama berbulan-bulan. Pastikan nyaman berdiskusi dan mereka responsif.</li>
<li><strong>Bandingkan minimal 3 WO</strong> — Jangan langsung memutuskan dari satu pilihan. Bandingkan paket, harga, dan pelayanan dari beberapa WO Palembang sebelum menentukan pilihan.</li>
<li><strong>Perhatikan kontrak dan detail pembayaran</strong> — Pastikan semua sudah tertulis jelas sebelum menandatangani perjanjian.</li>
</ol>

<h2>Kisaran Harga Wedding Organizer di Palembang</h2>
<p>Harga jasa <strong>wedding organizer Palembang</strong> bervariasi tergantung jenis layanan dan reputasi WO:</p>
<ul>
<li><strong>Partial WO / Day-of Coordinator:</strong> Rp 3.000.000 – Rp 8.000.000</li>
<li><strong>Full WO (paket dasar):</strong> Rp 8.000.000 – Rp 20.000.000</li>
<li><strong>Full WO (paket premium):</strong> Rp 20.000.000 – Rp 50.000.000+</li>
</ul>
<p>Harga di atas belum termasuk biaya vendor lain seperti dekorasi, katering, fotografer, dan venue. Untuk mendapatkan gambaran harga yang lebih lengkap, kamu bisa membandingkan berbagai paket dari WO di Palembang melalui platform seperti Makna Wedding.</p>

<h2>Pertanyaan yang Wajib Ditanyakan ke WO</h2>
<ul>
<li>Apakah sudah pernah menangani pernikahan adat Palembang?</li>
<li>Berapa rasio staf per tamu undangan di hari H?</li>
<li>Bagaimana prosedur jika ada vendor yang mendadak batal?</li>
<li>Apakah menyediakan garansi kepuasan atau refund policy?</li>
</ul>

<p>Dengan persiapan yang matang dan WO yang tepat, pernikahanmu di Palembang pasti akan menjadi momen yang tak terlupakan. Temukan pilihan <strong>wedding organizer Palembang</strong> terpercaya dan bandingkan paket terbaik sesuai budget kamu.</p>',
                'cover_image'  => null,
                'tags'         => ['wedding organizer palembang', 'wo palembang', 'tips pernikahan', 'palembang'],
                'is_published' => true,
                'published_at' => '2026-05-01 08:00:00',
                'views_count'  => 0,
                'sort_order'   => 17,
            ],

            [
                'title'        => 'Harga Paket Pernikahan di Palembang 2026: Panduan Lengkap Budget',
                'category'     => 'Budget Planning',
                'excerpt'      => 'Berapa biaya pernikahan di Palembang? Dari paket murah hingga mewah, panduan ini merangkum estimasi harga paket pernikahan di Palembang 2026 untuk semua budget.',
                'content'      => '<p>Merencanakan pernikahan di Palembang membutuhkan perencanaan anggaran yang matang. Harga <strong>paket pernikahan di Palembang</strong> sangat bervariasi tergantung konsep, jumlah tamu, venue, dan vendor yang dipilih. Artikel ini akan membantu kamu memahami kisaran biaya yang realistis.</p>

<h2>Komponen Biaya Pernikahan di Palembang</h2>
<p>Sebelum melihat paket, penting untuk memahami bahwa biaya pernikahan terdiri dari beberapa komponen utama:</p>
<ul>
<li><strong>Venue / Gedung pernikahan</strong> — 20–35% dari total budget</li>
<li><strong>Katering</strong> — 25–40% dari total budget</li>
<li><strong>Dekorasi</strong> — 10–20% dari total budget</li>
<li><strong>Dokumentasi (foto & video)</strong> — 8–15% dari total budget</li>
<li><strong>Busana pengantin</strong> — 5–10% dari total budget</li>
<li><strong>Wedding Organizer</strong> — 5–10% dari total budget</li>
<li><strong>Lain-lain (undangan, souvenir, transportasi)</strong> — 5–10% dari total budget</li>
</ul>

<h2>Estimasi Harga Paket Pernikahan di Palembang Berdasarkan Budget</h2>

<h3>Paket Pernikahan Sederhana (Rp 30 juta – Rp 80 juta)</h3>
<p>Cocok untuk pernikahan dengan 100–200 tamu undangan. Biasanya mencakup:</p>
<ul>
<li>Venue pernikahan sederhana atau aula komunitas</li>
<li>Katering prasmanan standar 150–200 porsi</li>
<li>Dekorasi minimalis</li>
<li>Fotografer paket dasar</li>
<li>Busana pengantin sewa</li>
</ul>

<h3>Paket Pernikahan Menengah (Rp 80 juta – Rp 200 juta)</h3>
<p>Paling populer di Palembang, cocok untuk 200–400 tamu. Mencakup:</p>
<ul>
<li>Gedung pernikahan representatif di Palembang</li>
<li>Katering prasmanan dengan menu beragam termasuk masakan khas Palembang</li>
<li>Dekorasi tema dengan bunga segar</li>
<li>Fotografer + videografer profesional</li>
<li>Busana pengantin adat atau modern</li>
<li>Partial wedding organizer</li>
</ul>

<h3>Paket Pernikahan Premium (Rp 200 juta – Rp 500 juta+)</h3>
<p>Untuk pernikahan mewah dengan 400+ tamu. Mencakup semua layanan full paket:</p>
<ul>
<li>Gedung mewah di Palembang seperti hotel bintang empat atau lima</li>
<li>Katering premium dengan plated dinner atau food station</li>
<li>Dekorasi mewah dengan floral arrangement premium</li>
<li>Tim foto & video profesional dengan drone dan cinema grade</li>
<li>Full wedding organizer berpengalaman</li>
<li>Entertainment live music atau band</li>
</ul>

<h2>Tips Hemat Biaya Pernikahan di Palembang</h2>
<ol>
<li><strong>Pilih hari weekday atau Sabtu pagi</strong> — Harga venue dan vendor biasanya lebih murah dibanding Sabtu-Minggu malam.</li>
<li><strong>Book vendor jauh-jauh hari</strong> — Minimal 6–12 bulan sebelum hari H untuk mendapatkan harga terbaik dan ketersediaan vendor favorit.</li>
<li><strong>Manfaatkan paket bundling</strong> — Banyak vendor di Palembang menawarkan paket yang sudah mencakup beberapa layanan sekaligus dengan harga lebih hemat.</li>
<li><strong>Batasi jumlah tamu</strong> — Setiap penghematan 50 orang bisa memotong biaya katering hingga Rp 5–15 juta.</li>
<li><strong>Bandingkan harga dari berbagai platform</strong> — Gunakan platform seperti Makna Wedding untuk membandingkan harga paket dari berbagai vendor Palembang dalam satu tempat.</li>
</ol>

<h2>Pertanyaan Umum tentang Biaya Pernikahan di Palembang</h2>
<p><strong>Apakah harga paket pernikahan di Palembang sudah termasuk semua?</strong></p>
<p>Belum tentu. Selalu tanyakan detail apa saja yang sudah termasuk dalam paket. Beberapa vendor memisahkan harga dekorasi, katering, dan dokumentasi.</p>
<p><strong>Berapa uang muka (DP) yang biasanya diminta vendor di Palembang?</strong></p>
<p>Umumnya vendor meminta DP 30–50% dari total harga untuk mengamankan tanggal pernikahanmu. Sisanya dibayar beberapa hari sebelum atau pada hari H.</p>
<p><strong>Apakah ada paket pernikahan murah di Palembang?</strong></p>
<p>Ya, tersedia banyak pilihan <strong>paket pernikahan murah di Palembang</strong> yang tetap berkualitas, terutama untuk pernikahan dengan tamu di bawah 200 orang. Kuncinya adalah merencanakan dari jauh-jauh hari dan memilih vendor yang tepat.</p>',
                'cover_image'  => null,
                'tags'         => ['harga paket pernikahan palembang', 'biaya pernikahan palembang', 'paket pernikahan murah palembang', 'budget pernikahan'],
                'is_published' => true,
                'published_at' => '2026-05-05 08:00:00',
                'views_count'  => 0,
                'sort_order'   => 18,
            ],

            [
                'title'        => 'Rekomendasi Gedung Pernikahan di Palembang: Dari Mewah hingga Terjangkau',
                'category'     => 'Wedding Ideas',
                'excerpt'      => 'Mencari tempat pernikahan di Palembang yang sesuai budget dan kapasitas tamu? Simak rekomendasi gedung dan venue pernikahan terbaik di Palembang untuk berbagai kebutuhan.',
                'content'      => '<p>Palembang memiliki beragam pilihan <strong>gedung pernikahan</strong> yang bisa disesuaikan dengan konsep dan budget pernikahanmu. Dari hotel bintang lima di jantung kota hingga gedung pernikahan terjangkau di pinggiran kota — semua tersedia untuk mewujudkan pernikahan impianmu di Bumi Sriwijaya.</p>

<h2>Jenis Venue Pernikahan di Palembang</h2>

<h3>1. Hotel Bintang 4 dan 5</h3>
<p>Hotel berbintang di Palembang menawarkan fasilitas lengkap dengan kapasitas besar. Kelebihan utamanya adalah ketersediaan katering in-house berkualitas, parkir luas, dan pelayanan profesional. Cocok untuk pernikahan formal dengan tamu 300–1000 orang.</p>
<p><strong>Perkiraan harga sewa:</strong> Rp 30 juta – Rp 150 juta (belum termasuk katering)</p>

<h3>2. Gedung Pernikahan Khusus</h3>
<p>Gedung yang memang didesain khusus untuk pernikahan biasanya menawarkan paket lengkap (venue + dekorasi + katering). Lebih fleksibel dalam hal konsep dan dekorasi dibanding hotel.</p>
<p><strong>Perkiraan harga paket:</strong> Rp 50 juta – Rp 200 juta (sudah termasuk paket)</p>

<h3>3. Gedung Serbaguna / Aula</h3>
<p>Pilihan ekonomis untuk pernikahan sederhana namun tetap terhormat. Banyak aula di Palembang yang bisa disewa dengan harga terjangkau.</p>
<p><strong>Perkiraan harga sewa:</strong> Rp 5 juta – Rp 25 juta</p>

<h3>4. Outdoor / Taman</h3>
<p>Venue outdoor semakin digemari untuk pernikahan konsep garden party atau rustic. Beberapa tempat di Palembang dan sekitarnya menawarkan latar alam yang cantik.</p>
<p><strong>Perkiraan harga:</strong> Rp 10 juta – Rp 50 juta (perlu tambahan tenda dan fasilitas)</p>

<h2>Hal-hal yang Perlu Diperhatikan Saat Memilih Venue di Palembang</h2>
<ul>
<li><strong>Kapasitas tamu</strong> — Jangan memilih gedung yang terlalu besar (terasa sepi) atau terlalu kecil (sesak). Idealnya kapasitas gedung 10–20% lebih besar dari jumlah tamu yang diundang.</li>
<li><strong>Akses dan parkir</strong> — Palembang terkenal dengan kemacetan di jam-jam tertentu. Pastikan venue mudah diakses dan memiliki lahan parkir yang memadai.</li>
<li><strong>Ketersediaan ruang ibadah</strong> — Penting untuk kenyamanan tamu yang ingin melaksanakan shalat.</li>
<li><strong>Catering policy</strong> — Apakah boleh membawa katering dari luar? Atau wajib menggunakan katering venue? Ini sangat mempengaruhi total biaya.</li>
<li><strong>Fasilitas pendingin udara</strong> — Cuaca Palembang yang panas menjadikan AC sebagai kebutuhan utama, terutama untuk acara siang hari.</li>
<li><strong>Dekat dengan penginapan</strong> — Memudahkan tamu yang datang dari luar kota.</li>
</ul>

<h2>Tips Mendapatkan Harga Terbaik untuk Venue di Palembang</h2>
<ol>
<li>Kunjungi dan survei minimal 3–5 gedung sebelum memutuskan.</li>
<li>Negosiasikan harga dan minta detail lengkap apa saja yang termasuk dalam paket.</li>
<li>Tanyakan apakah ada diskon untuk hari weekday atau bulan-bulan tertentu.</li>
<li>Periksa ketersediaan tanggal pernikahanmu sesegera mungkin — venue bagus sering habis dipesan jauh hari.</li>
<li>Baca ulasan dari pasangan yang sudah menggunakan venue tersebut sebelumnya.</li>
</ol>

<p>Menemukan <strong>tempat pernikahan di Palembang</strong> yang sempurna membutuhkan riset dan persiapan yang matang. Mulailah pencarian dari sekarang dan jangan ragu untuk berkonsultasi dengan wedding organizer lokal yang sudah mengenal landscape venue Palembang dengan baik.</p>',
                'cover_image'  => null,
                'tags'         => ['gedung pernikahan palembang', 'venue pernikahan palembang', 'tempat pernikahan palembang', 'palembang'],
                'is_published' => true,
                'published_at' => '2026-05-10 09:00:00',
                'views_count'  => 0,
                'sort_order'   => 19,
            ],

            [
                'title'        => 'Fotografer Pernikahan di Palembang: Tips Memilih dan Kisaran Harga',
                'category'     => 'Photography',
                'excerpt'      => 'Foto pernikahan adalah kenangan abadi. Panduan ini membantu kamu menemukan fotografer pernikahan terbaik di Palembang sesuai gaya dan budget.',
                'content'      => '<p>Di antara semua vendor pernikahan, <strong>fotografer pernikahan di Palembang</strong> adalah salah satu yang paling penting untuk dipilih dengan cermat. Foto dan video pernikahan adalah satu-satunya hal yang akan kamu bawa setelah semua rangkaian acara selesai.</p>

<h2>Gaya Fotografi Pernikahan yang Populer di Palembang</h2>

<h3>1. Documentary / Photojournalism</h3>
<p>Mengabadikan momen secara natural tanpa banyak arahan. Hasilnya terasa autentik dan penuh emosi. Cocok untuk pasangan yang tidak suka berpose dan ingin kenangan yang jujur.</p>

<h3>2. Fine Art</h3>
<p>Lebih artistic dengan komposisi, pencahayaan, dan editing yang detail. Hasilnya seperti lukisan — sangat estetik dan sering tampak di majalah pernikahan.</p>

<h3>3. Traditional / Classic</h3>
<p>Gaya formal dengan pose yang terstruktur. Memastikan semua anggota keluarga dan momen penting terabadikan dengan jelas. Cocok untuk pernikahan adat Palembang yang formal.</p>

<h3>4. Candid</h3>
<p>Campuran antara documentary dan traditional — mengabadikan momen natural sambil sesekali memberi arahan ringan untuk pose yang lebih baik.</p>

<h2>Kisaran Harga Fotografer Pernikahan di Palembang</h2>
<ul>
<li><strong>Paket dasar (1 fotografer, 8 jam):</strong> Rp 3.000.000 – Rp 7.000.000</li>
<li><strong>Paket menengah (2 fotografer + 1 videografer):</strong> Rp 8.000.000 – Rp 20.000.000</li>
<li><strong>Paket premium (full dokumentasi foto + video cinematic):</strong> Rp 20.000.000 – Rp 50.000.000+</li>
</ul>
<p>Harga sangat bergantung pada pengalaman fotografer, durasi liputan, dan output yang diberikan (jumlah foto yang diedit, format video, album fisik, dll).</p>

<h2>Checklist Memilih Fotografer Pernikahan di Palembang</h2>
<ol>
<li><strong>Lihat portofolio</strong> — Pastikan gaya editingnya sesuai seleramu. Jangan hanya melihat 1–2 foto terbaik, tapi minta melihat satu set lengkap dari pernikahan sebelumnya.</li>
<li><strong>Tanyakan pengalaman liputan pernikahan adat</strong> — Pernikahan adat Palembang memiliki banyak prosesi khusus. Fotografer yang berpengalaman akan tahu momen mana yang tidak boleh terlewat.</li>
<li><strong>Diskusikan shot list</strong> — Buat daftar foto wajib bersama fotografer sebelum hari H, termasuk foto keluarga inti, prosesi adat, dan momen candid.</li>
<li><strong>Tanyakan delivery time</strong> — Berapa lama foto selesai diedit dan dikirimkan? Standarnya 2–4 minggu setelah hari H.</li>
<li><strong>Pastikan ada backup</strong> — Fotografer profesional selalu membawa kamera cadangan dan backup data foto secara berkala.</li>
<li><strong>Baca kontrak dengan teliti</strong> — Pastikan tertulis jelas tentang hak penggunaan foto, jumlah foto yang diedit, dan apa yang terjadi jika fotografer sakit mendadak.</li>
</ol>

<h2>Pertanyaan yang Wajib Ditanyakan</h2>
<ul>
<li>Berapa jumlah foto final yang akan saya terima setelah editing?</li>
<li>Apakah raw file diberikan atau hanya hasil editing?</li>
<li>Apakah termasuk sesi prewedding di Palembang?</li>
<li>Bagaimana jika cuaca buruk di hari H (untuk outdoor)?</li>
<li>Apakah ada fotografer pengganti jika berhalangan?</li>
</ul>

<p>Mengabadikan momen pernikahan di Palembang dengan <strong>fotografer yang tepat</strong> akan memastikan setiap detail indah dari hari istimewamu terekam dengan sempurna — dari prosesi adat, sungkeman, hingga momen bahagia bersama keluarga.</p>',
                'cover_image'  => null,
                'tags'         => ['fotografer pernikahan palembang', 'foto pernikahan palembang', 'videografer palembang', 'palembang'],
                'is_published' => true,
                'published_at' => '2026-05-15 08:00:00',
                'views_count'  => 0,
                'sort_order'   => 20,
            ],
        ];

        foreach ($items as $item) {
            $item['slug'] = Str::slug($item['title']);

            Blog::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }

        $this->command->info('BlogSeeder: ' . count($items) . ' blog posts seeded.');
    }
}
