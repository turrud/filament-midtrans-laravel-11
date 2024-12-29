<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('1111'),
        ]);



        Package::create([
            'id' => (string) Str::uuid(),
            'name' => 'FIF',
            'price' => 100000,
            'description' => 'Program Studi S1 Teknik Informatika (S1-IF) berdiri tahun 1992 di bawah institusi Sekolah Tinggi Teknologi Telkom (STT Telkom) yang didirikan oleh Yayasan Pendidikan Telkom (YPT). Pada tahun pertamanya S1-IF menerima 205 mahasiswa baru, dan melahirkan lulusan pertamanya pada Desember 1996. Pada tahun 2007 STT Telkom berubah menjadi Institut Teknologi Telkom (IT Telkom). Sejak saat itu S1-IF berada di bawah Departemen Informatika bersama dengan Prodi D3 Teknik Informatika.',
        ]);
        Package::create([
            'id' => (string) Str::uuid(),
            'name' => 'FTE',
            'price' => 150000,
            'description' => 'Fakultas Teknik Elektro (FTE) merupakan fakultas terbesar dan tertua di Telkom University yang memiliki komitmen untuk terus mengembangkan penelitian, pendidikan, dan enterpreneurship dalam bidang teknik elektro dan teknik fisika, dengan berbasiskan teknologi informasi sehingga dapat menjadi fakultas yang berstandar internasional.',
        ]);
        Package::create([
            'id' => (string) Str::uuid(),
            'name' => 'FIK',
            'price' => 75000,
            'description' => 'Fakultas Industri Kreatif (FIK) Telkom University memiliki 7 Program Studi unggulan dan terlengkap di bidang kreatif yakni, S1 Desain Komunikasi Visual (S.Ds), S1 Desain Interior (S.Ds), S1 Desain Produk (S.Ds), S1 Kriya (S.Sn) serta S1 Seni Rupa (S.Sn), S1 Film dan Animasi (S.Ds./S.Sn.), dan program S2 Magister Desain (M.Ds). FIK telah menghasilkan banyak lulusan yang kiprahnya telah banyak memberikan warna pada perkembangan industri kreatif di Indonesia dan manca negara.',
        ]);
    }
}
