<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['fakultas_id' => 1, 'nama_prodi' => 'Bahasa dan Sastra Arab'],
            ['fakultas_id' => 1, 'nama_prodi' => 'Bahasa dan Sastra Inggris'],
            ['fakultas_id' => 1, 'nama_prodi' => 'Sejarah Peradaban Islam'],
            ['fakultas_id' => 1, 'nama_prodi' => 'Ilmu Perpustakaan'],

            ['fakultas_id' => 2, 'nama_prodi' => 'Komunikasi dan Penyiaran Islam'],
            ['fakultas_id' => 2, 'nama_prodi' => 'Bimbingan dan Penyuluhan Islam'],
            ['fakultas_id' => 2, 'nama_prodi' => 'Manajemen Dakwah'],
            ['fakultas_id' => 2, 'nama_prodi' => 'Kesejahteraan Sosial'],
            ['fakultas_id' => 2, 'nama_prodi' => 'Pengembangan Masyarakat Islam'],
            ['fakultas_id' => 2, 'nama_prodi' => 'Jurnalistik'],
            ['fakultas_id' => 2, 'nama_prodi' => 'Ilmu Komunikasi'],
            ['fakultas_id' => 2, 'nama_prodi' => 'Manajemen Haji dan Umrah'],

            ['fakultas_id' => 3, 'nama_prodi' => 'Ilmu Akuntansi'],
            ['fakultas_id' => 3, 'nama_prodi' => 'Perbankan Syariah'],
            ['fakultas_id' => 3, 'nama_prodi' => 'Ekonomi Islam'],
            ['fakultas_id' => 3, 'nama_prodi' => 'Manajemen'],
            ['fakultas_id' => 3, 'nama_prodi' => 'Ilmu Ekonomi'],

            ['fakultas_id' => 4, 'nama_prodi' => 'Pendidikan Kedokteran'],
            ['fakultas_id' => 4, 'nama_prodi' => 'Farmasi'],
            ['fakultas_id' => 4, 'nama_prodi' => 'Kesehatan Masyarakat'],
            ['fakultas_id' => 4, 'nama_prodi' => 'Profesi Dokter'],
            ['fakultas_id' => 4, 'nama_prodi' => 'Profesi Apoteker'],
            ['fakultas_id' => 4, 'nama_prodi' => 'D3 Kebidanan'],
            ['fakultas_id' => 4, 'nama_prodi' => 'Profesi Ners'],
            ['fakultas_id' => 4, 'nama_prodi' => 'S1 Kebidanan'],
            ['fakultas_id' => 4, 'nama_prodi' => 'Pendidikan Profesi Bidan'],
            ['fakultas_id' => 4, 'nama_prodi' => 'Keperawatan'],

            ['fakultas_id' => 5, 'nama_prodi' => 'Teknik Informatika'],
            ['fakultas_id' => 5, 'nama_prodi' => 'Sistem Informasi'],
            ['fakultas_id' => 5, 'nama_prodi' => 'Matematika'],
            ['fakultas_id' => 5, 'nama_prodi' => 'Fisika'],
            ['fakultas_id' => 5, 'nama_prodi' => 'Kimia'],
            ['fakultas_id' => 5, 'nama_prodi' => 'Biologi'],
            ['fakultas_id' => 5, 'nama_prodi' => 'Ilmu Peternakan'],
            ['fakultas_id' => 5, 'nama_prodi' => 'Perencanaan Arsitektur'],
            ['fakultas_id' => 5, 'nama_prodi' => 'Perencanaan Wilayah dan Kota'],

            ['fakultas_id' => 6, 'nama_prodi' => 'Hukum Keluarga Islam'],
            ['fakultas_id' => 6, 'nama_prodi' => 'Hukum Ekonomi Syariah'],
            ['fakultas_id' => 6, 'nama_prodi' => 'Hukum Tata Negara'],
            ['fakultas_id' => 6, 'nama_prodi' => 'Ilmu Hukum'],
            ['fakultas_id' => 6, 'nama_prodi' => 'Ilmu Falak'],
            ['fakultas_id' => 6, 'nama_prodi' => 'Perbandingan Mazhab dan Hukum'],

            ['fakultas_id' => 7, 'nama_prodi' => 'Pendidikan Bahasa Arab'],
            ['fakultas_id' => 7, 'nama_prodi' => 'Pendidikan Bahasa Inggris'],
            ['fakultas_id' => 7, 'nama_prodi' => 'Pendidikan Biologi'],
            ['fakultas_id' => 7, 'nama_prodi' => 'Pendidikan Matematika'],
            ['fakultas_id' => 7, 'nama_prodi' => 'Pendidikan Fisika'],
            ['fakultas_id' => 7, 'nama_prodi' => 'Pendidikan Agama Islam'],
            ['fakultas_id' => 7, 'nama_prodi' => 'Pendidikan Islam Anak Usia Dini'],
            ['fakultas_id' => 7, 'nama_prodi' => 'Pendidikan Profesi Guru'],
            ['fakultas_id' => 7, 'nama_prodi' => 'Pendidikan Guru Madrasah Ibtidaiyah'],
            ['fakultas_id' => 7, 'nama_prodi' => 'Manajemen Pendidikan Islam'],

            ['fakultas_id' => 8, 'nama_prodi' => 'Studi Agama-Agama'],
            ['fakultas_id' => 8, 'nama_prodi' => 'Aqidah dan Filsafat Islam'],
            ['fakultas_id' => 8, 'nama_prodi' => 'Ilmu Politik'],
            ['fakultas_id' => 8, 'nama_prodi' => 'Ilmu Hadist'],
            ['fakultas_id' => 8, 'nama_prodi' => 'Hubungan Internasional'],
            ['fakultas_id' => 8, 'nama_prodi' => 'Sosiologi Agama'],
            ['fakultas_id' => 8, 'nama_prodi' => 'Ilmu Al-Quran dan Tafsir'],

            ['fakultas_id' => 9, 'nama_prodi' => 'Jurusan Dirasah Islamiyah (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Jurusan Dirasah Islamiyah (S3)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Pendidikan Agama Islam (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Pendidikan Bahasa Inggris (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Pendidikan Bahasa Arab (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => "Jurusan Ilmu Al-Qur'an dan Tafsir (S2)"],
            ['fakultas_id' => 9, 'nama_prodi' => 'Jurusan Komunikasi dan Penyiaran Islam (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Manajemen dan Bisnis Syariah (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Keperawatan (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Jurusan Manajemen Pendidikan Islam (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Jurusan Ekonomi Syariah (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Jurusan Ilmu Hadist (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Jurusan Kesehatan Masyarakat (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Akuntansi Syariah (S2)'],
            ['fakultas_id' => 9, 'nama_prodi' => 'Hukum (S2)'],
        ];

        foreach ($data as $item) {
            Prodi::create($item);
        }
    }
}
