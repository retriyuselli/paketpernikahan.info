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
                'views_count'  => 35,
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
                'views_count'  => 30,
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
                'views_count'  => 577271,
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
                'views_count'  => 560122,
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
                'views_count'  => 443946,
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
                'views_count'  => 12500,
                'sort_order'   => 6,
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
