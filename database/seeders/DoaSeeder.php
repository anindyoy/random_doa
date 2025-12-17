<?php

namespace Database\Seeders;

use App\Models\Doa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Doa::truncate();
        DB::table('doa_tag')->truncate();

        $konten = $this->getKonten();
        $sourcePath = database_path('seeders/doa');
        $storageDir = 'doa';

        Storage::disk('public')->deleteDirectory($storageDir);
        Storage::disk('public')->makeDirectory($storageDir);

        $allTagIds = DB::table('tags')->pluck('id')->toArray();

        if (empty($allTagIds)) {
            // Jika tidak ada tag, beri peringatan
            $this->command->warn('Tidak ada tag yang tersedia. Silakan seed tabel tags terlebih dahulu.');
        }

        foreach ($konten as $key => $value) {

            $sourceFile = $sourcePath . '/' . $value['gambar'];
            $destinationFile = $storageDir . '/' . $value['gambar'];

            if (File::exists($sourceFile)) {

                Storage::disk('public')->put(
                    $destinationFile,
                    File::get($sourceFile)
                );
            }

            $doa = new Doa();
            $doa->judul = $value['judul'];
            $doa->keterangan = $value['keterangan'];
            $doa->riwayat = $value['riwayat'];
            $doa->sumber_desain = $value['sumber_desain'];
            $doa->gambar = $destinationFile;
            $adminUser = User::whereIsAdmin(true)->first();
            $doa->user_id = $adminUser ? $adminUser->id : null;

            $doa->save();

            if (!empty($allTagIds)) {
                $numberOfTags = rand(1, 2); // Random 1 atau 2 tag
                $randomTagIds = array_rand(array_flip($allTagIds), min($numberOfTags, count($allTagIds)));

                // Pastikan $randomTagIds adalah array
                if (!is_array($randomTagIds)) {
                    $randomTagIds = [$randomTagIds];
                }

                foreach ($randomTagIds as $tagId) {
                    DB::table('doa_tag')->insert([
                        'doa_id' => $doa->id,
                        'tag_id' => $tagId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function getKonten()
    {
        // Nilai sumber desain yang sama
        $sumberDesain = "shahihfiqih.com";

        return [
            1 => [
                "judul" => "DOA MEMOHON SETIAP KEBAIKAN",
                "keterangan" => "“Ya Tuhanku, sesungguhnya aku sangat membutuhkan setiap kebaikan yang Engkau turunkan kepadaku.”",
                "riwayat" => "QS. Al-Qashas: 24",
                "gambar" => "1.jpg",
                "sumber_desain" => $sumberDesain
            ],
            2 => [
                "judul" => "DOA BERLINDUNG DARI FITNAH HARTA",
                "keterangan" => "\"Ya Allah, aku berlindung kepadaMu dari fitnah Neraka dan adzab Neraka, serta dari keburukan kekayaan dan kefakiran.”",
                "riwayat" => "HR. Abu Daud no. 1543. Syaikh Al Albani mengatakan bahwa hadits ini shahih",
                "gambar" => "2.jpg",
                "sumber_desain" => $sumberDesain
            ],
            3 => [
                "judul" => "DOA BERLINDUNG DARI VIRUS PENYAKIT YANG BERBAHAYA",
                "keterangan" => "“Ya Allah, aku berlindung kepada-Mu dari penyakit belang, gila, kusta, dan dari segala penyakit yang buruk/mengerikan lainnya.”",
                "riwayat" => "HR. Abu Dawud 1554, Nasa'i 5493 maktabu al ma'arif riyadh",
                "gambar" => "3.jpg",
                "sumber_desain" => $sumberDesain
            ],
            4 => [
                "judul" => "DOA BERLINDUNG DARI KESESATAN",
                "keterangan" => "\"Ya Allah, aku berlindung kepada-Mu dari ketersesatan atau ketergelinciran, atau dari berbuat kedzaliman maupun di dzalimi atau berbuat kebodohan maupun di bodohi.\"",
                "riwayat" => "HR. Ibnu Majah 3884 Maktabatu Al Ma'arif Riyadh",
                "gambar" => "4.jpg",
                "sumber_desain" => $sumberDesain
            ],
            5 => [
                "judul" => "DOA BERLINDUNG PASANGAN YANG CEREWET",
                "keterangan" => "“Ya Allah, sesungguhnya aku berlindung kepada-Mu dari tetangga yang jahat, dan dari pasangan yang menjadikanku tua (beruban) sebelum waktunya.”",
                "riwayat" => "HR. Thabrani, Ad-Du’a’ 3: 1425, 1339 Az-Zuhud, 1038 Syaikh Al-Albani menyebutkan dalam Silsilah Al-Ahadits Ash-Shahihah, 7: 377, no. 3137. hadits ini hanya maqthu’, perkataan tabi’in dan tidak marfu’ sampai Nabi.",
                "gambar" => "5.jpg",
                "sumber_desain" => $sumberDesain
            ],
            6 => [
                "judul" => "MEMOHON KEKUATAN UNTUK BERSABAR",
                "keterangan" => "“Ya Rabb kami, limpahkanlah kesabaran kepada kami dan wafatkanlah kami dalam Keadaan berserah diri (kepada-Mu)”.",
                "riwayat" => "Qs. Al-A’raf 126",
                "gambar" => "6.jpg",
                "sumber_desain" => $sumberDesain
            ],
            7 => [
                "judul" => "DOA AGAR MENDAPAT CINTA ALLAH",
                "keterangan" => "Ya Allah, aku memohon agar dapat mencintai-Mu, dan mencintai orang-orang yang mencintai-Mu, dan mencintai amal yang dapat mendekatkan diriku kepada cinta-Mu.\"",
                "riwayat" => "HR. Tirmidzi 3235",
                "gambar" => "7.jpg",
                "sumber_desain" => $sumberDesain
            ],
            8 => [
                "judul" => "DOA MOHON DIPERBAIKI SEGALA URUSAN",
                "keterangan" => "“Ya Allah aku mohon kebaikan pada urusan agamaku karena itu adalah penjaga semua urusanku. Aku mohon kebaikan pada urusan duniaku karena itu tempat hidupku. Aku mohon kebaikan pada urusan akhiratku karena itu tempat kembaliku. Jadikanlah hidup ini tambahan kebaikan bagiku, dan jadikanlah kematianku waktu istirahat bagiku dari segala keburukan.”",
                "riwayat" => "HR. Muslim, 2720 syarh shahih muslim",
                "gambar" => "8.jpg",
                "sumber_desain" => $sumberDesain
            ],
            9 => [
                "judul" => "DOA MOHON DIKUMPULKAN BERSAMA ORANG-ORANG SHALEH",
                "keterangan" => "\"Ya Tuhan) Pencipta langit dan bumi, Engkaulah pelindungku di dunia dan di akhirat, wafatkanlah aku dalam keadaan muslim dan gabungkanlah aku dengan orang yang saleh.\"",
                "riwayat" => "Qs. Yusuf 101",
                "gambar" => "9.jpg",
                "sumber_desain" => $sumberDesain
            ],
            10 => [
                "judul" => "DOA DIJAUHKAN DARI SIFAT PELIT DAN TAMAK",
                "keterangan" => "\"Ya Allah, hilangkanlah dariku sifat pelit (lagi tamak), dan jadikanlah aku orang-orang yang beruntung.\"",
                "riwayat" => "Ad Du’a minal Kitab was Sunnah",
                "gambar" => "10.jpg",
                "sumber_desain" => $sumberDesain
            ],
            11 => [
                "judul" => "DOA MEMOHON KEBERSIHAN DAN KESUCIAN JIWA",
                "keterangan" => "“Ya Allah, limpahkanlah ketakwaan pada jiwaku dan sucikanlah, sesungguhnya Engkau adalah Sebaik-baik Dzat yang menyucikan jiwa, Engkau-lah Yang Menjaganya serta Melindunginya.”",
                "riwayat" => "HR. Muslim 2722",
                "gambar" => "11.jpg",
                "sumber_desain" => $sumberDesain
            ],
            12 => [
                "judul" => "DOA BERLINDUNG DICABUTNYA NIKMAT LAHIR BATIN",
                "keterangan" => "“Ya Allah, sesungguhnya aku berlindung kepada-Mu dari hilangnya kenikmatan yang telah Engkau berikan, dari berubahnya kesehatan yang telah Engkau anugerahkan, dari siksa-Mu yang datang secara tiba-tiba, dan dari segala kemurkaan-Mu.”",
                "riwayat" => "HR. Muslim 2739",
                "gambar" => "12.jpg",
                "sumber_desain" => $sumberDesain
            ],
            13 => [
                "judul" => "DOA BERLINDUNG DARI PINTU-PINTU KEBURUKAN",
                "keterangan" => "“Ya Allah, sesungguhnya aku berlindung kepada-Mu dari ilmu yang tidak bermanfaat, dan dari hati yang tidak khusyu, dan dari jiwa yang tidak pernah merasa puas, dan dari doa yang tidak dikabulkan.”",
                "riwayat" => "HR. Muslim 2722",
                "gambar" => "13.jpg",
                "sumber_desain" => $sumberDesain
            ],
            14 => [
                "judul" => "DOA AGAR TIDAK MENJADI SASARAN FITNAH ORANG-ORANG ZALIM",
                "keterangan" => "“Ya Rabb kami, janganlah Engkau jadikan kami sasaran fitnah bagi kaum yang zalim. Dan selamatkanlah kami dengan rahmat Engkau dari (tipu daya) orang-orang yang kafir.”",
                "riwayat" => "Qs. Yunus: 85-86",
                "gambar" => "14.jpg",
                "sumber_desain" => $sumberDesain
            ],
            15 => [
                "judul" => "DOA BERLINDUNG DARI KEBURUKAN AMAL",
                "keterangan" => "“Ya Allah, aku berlindung kepada-Mu dari keburukan yang telah aku perbuat dan keburukan yang belum aku perbuat.”",
                "riwayat" => "HR. Muslim 2716",
                "gambar" => "15.jpg",
                "sumber_desain" => $sumberDesain
            ],
            16 => [
                "judul" => "DOA BERLINDUNG DARI RASA MALAS",
                "keterangan" => "\"Ya Allah, aku berlindung kepada-Mu dari kelemahan, rasa malas, rasa takut, kejelekan di waktu tua, dan sifat kikir. Dan aku juga berlindung kepada-Mu dari siksa kubur serta bencana kehidupan dan kematian.”",
                "riwayat" => "HR. Bukhari no. 6367 dan Muslim no. 2706",
                "gambar" => "16.jpg",
                "sumber_desain" => $sumberDesain
            ],
            17 => [
                "judul" => "DOA AGAR MUDAH MENCINTAI FAKIR MISKIN",
                "keterangan" => "\"Ya Allah, sesungguhnya aku meminta kepada-Mu untuk bisa berbuat baik, meninggalkan kemungkaran, mencintai orang-orang miskin. Ampunilah aku dan rahmatilah aku. Apabila Engkau menghendaki fitnah atas suatu kaum maka wafatkan aku kepada-Mu dalam keadaan tidak terkena fitnah. Aku mengharap cinta-Mu, cinta orang yang mencintai-Mu, dan cinta pada amalan yang mendekatkanku pada cinta-Mu.\"",
                "riwayat" => "HR. At-Tirmidzi 3235. Shahih",
                "gambar" => "17.jpg",
                "sumber_desain" => $sumberDesain
            ],
            18 => [
                "judul" => "DOA AGAR MEMILIKI ANAK KETURUNAN YANG RAJIN SHALAT",
                "keterangan" => "\"Ya Rabbku, jadikanlah aku dan anak cucuku orang yang melaksanakan sholat, ya Rabb kami, perkenankanlah doa kami.\"",
                "riwayat" => "Qs. Ibrahim Ayat 40",
                "gambar" => "18.jpg",
                "sumber_desain" => $sumberDesain
            ],
            19 => [
                "judul" => "DOA AGAR SETIAP URUSAN BERAKHIR BAIK",
                "keterangan" => "“Ya Allah, jadikan segala urusan kami berakhir dengan baik. Dan lindungi kami dari bencana dunia dan azab Akhirat.”",
                "riwayat" => "HR. Ahmad 4/181",
                "gambar" => "19.jpg",
                "sumber_desain" => $sumberDesain
            ],
            20 => [
                "judul" => "DOA AGAR TERHINDAR DARI COBAAN BERAT",
                "keterangan" => "“Ya Allah, sesungguhnya aku berlindung kepada-Mu dari keadaan yang berat dan dari kesengsaraan, buruknya takdir, dan kegembiraan musuh atas bencana yang menimpaku.”",
                "riwayat" => "HR. Bukhari 6347",
                "gambar" => "20.jpg",
                "sumber_desain" => $sumberDesain
            ]
        ];
    }
}
