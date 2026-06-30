<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $fillable = ['nama'];
    public $timestamps  = false;
    public function prodi()
    {
        return $this->hasMany(Prodi::class, 'fakultas_id');
    }
}
// INSERT INTO prodis (fakultas_id, nama_prodi, created_at, updated_at) VALUES
// -- 1. Adab dan Humaniora (ID: 1)
// (1, 'Bahasa dan Sastra Arab', NOW(), NOW()),
// (1, 'Bahasa dan Sastra Inggris', NOW(), NOW()),
// (1, 'Sejarah Peradaban Islam', NOW(), NOW()),
// (1, 'Ilmu Perpustakaan', NOW(), NOW()),

// -- 2. Dakwah dan Komunikasi (ID: 2)
// (2, 'Komunikasi dan Penyiaran Islam', NOW(), NOW()),
// (2, 'Bimbingan dan Penyuluhan Islam', NOW(), NOW()),
// (2, 'Manajemen Dakwah', NOW(), NOW()),
// (2, 'Kesejahteraan Sosial', NOW(), NOW()),
// (2, 'Pengembangan Masyarakat Islam', NOW(), NOW()),
// (2, 'Jurnalistik', NOW(), NOW()),
// (2, 'Ilmu Komunikasi', NOW(), NOW()),
// (2, 'Manajemen Haji dan Umrah', NOW(), NOW()),

// -- 3. Ekonomi dan Bisnis Islam (ID: 3)
// (3, 'Ilmu Akuntansi', NOW(), NOW()),
// (3, 'Perbankan Syariah', NOW(), NOW()),
// (3, 'Ekonomi Islam', NOW(), NOW()),
// (3, 'Manajemen', NOW(), NOW()),
// (3, 'Ilmu Ekonomi', NOW(), NOW()),

// -- 4. Kedokteran dan Ilmu Kesehatan (ID: 4)
// (4, 'Pendidikan Kedokteran', NOW(), NOW()),
// (4, 'Farmasi', NOW(), NOW()),
// (4, 'Kesehatan Masyarakat', NOW(), NOW()),
// (4, 'Profesi Dokter', NOW(), NOW()),
// (4, 'Profesi Apoteker', NOW(), NOW()),
// (4, 'D3 Kebidanan', NOW(), NOW()),
// (4, 'Profesi Ners', NOW(), NOW()),
// (4, 'S1 Kebidanan ', NOW(), NOW()),
// (4, 'Pendidikan Profesi Bidan', NOW(), NOW()),
// (4, 'Keperawatan', NOW(), NOW()),

// -- 5. Sains dan Teknologi (ID: 5)
// (5, 'Teknik Informatika', NOW(), NOW()),
// (5, 'Sistem Informasi', NOW(), NOW()),
// (5, 'Matematika', NOW(), NOW()),
// (5, 'Fisika', NOW(), NOW()),
// (5, 'Kimia', NOW(), NOW()),
// (5, 'Biologi', NOW(), NOW()),
// (5, 'Ilmu Peternakan', NOW(), NOW()),
// (5, 'Perencanaan Arsitektur', NOW(), NOW()),
// (5, 'Perencanaan Wilayah dan Kota', NOW(), NOW()),

// -- 6. Syariah dan Hukum (ID: 6)
// (6, 'Hukum Keluarga Islam', NOW(), NOW()),
// (6, 'Hukum Ekonomi Syariah', NOW(), NOW()),
// (6, 'Hukum Tata Negara', NOW(), NOW()),
// (6, 'Ilmu Hukum', NOW(), NOW()),
// (6, 'Ilmu Falak', NOW(), NOW()),
// (6, 'Perbandingan Mazhab dan Hukum', NOW(), NOW()),

// -- 7. Tarbiyah dan Keguruan (ID: 7)
// (7, 'Pendidikan Bahasa Arab', NOW(), NOW()),
// (7, 'Pendidikan Bahasa Inggris', NOW(), NOW()),
// (7, 'Pendidikan Biologi', NOW(), NOW()),
// (7, 'Pendidikan Matematikas', NOW(), NOW()),
// (7, 'Pendidikan Fisika', NOW(), NOW()),
// (7, 'Pendidikan Agama Islam', NOW(), NOW()),
// (7, 'Pendidikan Islam Anak Usia Dini', NOW(), NOW()),
// (7, 'Pendidikan Profesi Guru', NOW(), NOW()),
// (7, 'Pendidikan Guru Madrasah Ibtidaiyah', NOW(), NOW()),
// (7, 'Manajemen Pendidikan Islam', NOW(), NOW()),

// -- 8. Ushuluddin dan Filsafat (ID: 8)
// (8, 'Studi Agama-Agama', NOW(), NOW()),
// (8, 'Aqidah dan Filsafat Islam', NOW(), NOW());
// (8, 'Ilmu Politik', NOW(), NOW());
// (8, 'Ilmu Hadist', NOW(), NOW());
// (8, 'Hubungan Internasional', NOW(), NOW());
// (8, 'Sosiologi Agama', NOW(), NOW());
// (8, 'Ilmu Al-Quran dan Tafsir', NOW(), NOW());

// -- 9. Pasca Sarjana (ID: 9)
// (9, 'Jurusan Dirasah Islamiyah (S2)', NOW(), NOW()),
// (9, 'Jurusan Dirasah Islamiyah (S3)', NOW(), NOW()),
// (9, 'Pendidikan Agama Islam (S2)', NOW(), NOW()),
// (9, 'Pendidikan Bahasa Inggris (S2)', NOW(), NOW()),
// (9, 'Pendidikan Bahasa Arab (S2)', NOW(), NOW()),
// (9, 'Jurusan Ilmu Al-Qur'an dan Tafsir (S2)', NOW(), NOW()),
// (9, 'Jurusan Komunikasi dan Penyiaran Islam (S2)', NOW(), NOW()),
// (9, 'Manajemen dan Bisnis Syariah (S2)', NOW(), NOW()),
// (9, 'Keperawatan (S2)', NOW(), NOW()),
// (9, 'Jurusan Manajemen Pendidikan Islam (S2)', NOW(), NOW()),
// (9, 'Jurusan Ekonomi Syariah (S2)', NOW(), NOW()),
// (9, 'Jurusan Ilmu Hadist (S2)', NOW(), NOW()),
// (9, 'Jurusan Kesehatan Masyarakat (S2)', NOW(), NOW()),
// (9, 'Akuntansi Syariah (S2)', NOW(), NOW()),
// (9, 'Hukum (S2)', NOW(), NOW()),

