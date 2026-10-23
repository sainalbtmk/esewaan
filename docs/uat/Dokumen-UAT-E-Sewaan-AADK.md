# Dokumen Ujian Penerimaan Pengguna (UAT)

**Sistem E-Sewaan AADK**  
Sistem Pengurusan Kontrak Sewaan  
Agensi Antidadah Kebangsaan

| Medan | Butiran |
| --- | --- |
| No. dokumen | AADK/CP/E-SEWAAN/UAT/001 |
| Versi | 1.0 |
| Tarikh | 21 Oktober 2026 |
| Status | Untuk pelaksanaan UAT |
| Klasifikasi | Terhad |
| Pemilik sistem | Cawangan Pembangunan, AADK |
| Skop pengguna | Pentadbir Ibu Pejabat dan Pentadbir Negeri |

Dokumen ini ialah skrip ujian penerimaan. Penguji mengisi lajur **Keputusan sebenar**, **Status**, **Penguji** dan **Tarikh**. Sistem diterima apabila kriteria keluar di Seksyen 6 dipenuhi dan borang perakuan di Seksyen 16 ditandatangani.

---

## 1. Kawalan dokumen

| Peranan | Jawatan | Nama | Tarikh | Tandatangan |
| --- | --- | --- | --- | --- |
| Penyedia | Pasukan pembangunan sistem | | | |
| Penyemak | Cawangan Pembangunan AADK | | | |
| Pengesah UAT Negeri | Wakil Pentadbir Negeri | | | |
| Pelulus penerimaan | Pemilik sistem / Ketua Cawangan Pembangunan | | | |

### Sejarah pindaan

| Versi | Tarikh | Penyedia | Ringkasan perubahan |
| --- | --- | --- | --- |
| 1.0 | 21 Oktober 2026 | Pasukan pembangunan | Terbitan pertama skrip UAT berdasarkan fungsi sistem semasa. |

---

## 2. Tujuan

Ujian ini mengesahkan bahawa E-Sewaan AADK memenuhi keperluan operasi pengurusan permohonan dan kontrak sewaan premis, dari pendaftaran permohonan negeri sehingga kontrak berkuat kuasa, termasuk kawalan akses, pemakluman dan laporan Ibu Pejabat.

UAT dijalankan oleh pengguna bisnes. Ia bukan ujian teknikal pembangun. Keputusan lulus bermaksud skrin, mesej, aliran kerja dan had kuasa sepadan dengan operasi sebenar AADK.

## 3. Skop

### Dalam skop

- Log masuk, log keluar, lupa kata laluan dan pengesahan dua faktor.
- Profil pengguna dan tetapan penampilan.
- Pendaftaran pengguna, status akaun, jejak audit dan emel jawatan.
- Papan pemuka dan pecahan status tindakan.
- Permohonan baharu, pindah dan lanjutan, termasuk langkah tindakan dan dokumen sokongan.
- Semakan Ibu Pejabat, draf perjanjian, semakan PUU, pengesahan tandatangan dan mati setem.
- Tarik semula, padam dan batal permohonan susulan.
- Senarai kontrak sewaan.
- Laporan Ibu Pejabat dan muat turun PDF.
- Peti masuk, lencana menu dan peringatan tamat kontrak.
- Had kuasa antara Ibu Pejabat dan Negeri.

### Luar skop

- Ujian beban, penembusan keselamatan dan audit kod.
- Ujian pelayan mel pengeluaran selain pengesahan bahawa sistem menjana pemakluman yang betul.
- Proses luar sistem seperti kelulusan MOF, KE, BPH, tandatangan fizikal TKPP, ProMIS dan LHDN. Sistem hanya merekod pengakuan bahawa tindakan itu telah dibuat.
- Perubahan dasar atau borang yang belum dibina dalam sistem.

## 4. Peranan

| Peranan sistem | Label pada skrin | Kuasa utama |
| --- | --- | --- |
| `admin_hq` | Ibu Pejabat | Semua negeri. Semak permohonan, lulus atau batal semakan PUU, sahkan tandatangan, urus status pengguna, emel jawatan dan laporan. |
| `admin_negeri` | Negeri | Negeri sendiri sahaja. Cipta dan kemaskini permohonan, lengkapkan langkah tindakan, muat naik dokumen dan draf perjanjian, sahkan pengakuan negeri serta mati setem. |

Pentadbir Negeri hanya melihat dan bertindak ke atas rekod negerinya. Pentadbir Negeri hanya boleh mendaftarkan pengguna Negeri bagi negeri yang sama. Ibu Pejabat boleh mendaftarkan pengguna Ibu Pejabat dan pengguna Negeri bagi mana-mana negeri.

## 5. Aliran bisnes yang diterima

Setiap permohonan melalui lapan peringkat pada skrin semakan:

1. **Permohonan Baru** — butiran premis dan langkah tindakan negeri.
2. **Semakan Ibu Pejabat** — senarai semak kelulusan, kemudian permohonan diteruskan ke penyediaan draf.
3. **Penyediaan Draf** — Negeri memuat naik draf perjanjian dalam format PDF.
4. **Dalam tindakan PUU** — Ibu Pejabat meluluskan semakan, atau membatalkan semakan supaya Negeri memuat naik draf baharu. Setiap muat naik menambah pusingan Semakan PUU 1, Semakan PUU 2, dan seterusnya.
5. **Draf Lulus** — Negeri mengesahkan empat perkara, kemudian mengembalikan dokumen kepada Cawangan Pembangunan.
6. **Pengesahan dan Tandatangan** — Ibu Pejabat mengesahkan tiga perkara dan menghantar dokumen untuk mati setem.
7. **Mati Setem** — Negeri mengesahkan dua perkara.
8. **Selesai** — rekod masuk ke Senarai Kontrak Sewaan.

### Kategori dan langkah tindakan

| Kategori | Maksud | Langkah yang wajib |
| --- | --- | --- |
| Baru | Premis baharu | 2 Surat niat, 3 Borang JRP, 5 Surat kepada agensi |
| Pindah | Pindah premis daripada kontrak sedia ada | 2 Surat niat, 3 Borang JRP, 5 Surat kepada agensi |
| Lanjutan | Lanjutan kontrak sedia ada | 1 Notis kepada pemilik, 3 Borang JRP, 5 Surat kepada agensi |

Langkah 5 merekod agensi yang dikepilkan. Item yang memerlukan tarikh ialah surat JPPH, KPKKM, Bomba, JKPTG, carian rasmi dan surat tawaran pemilik. Pelan lantai dan gambar bangunan tidak memerlukan tarikh.

Permohonan pindah atau lanjutan memerlukan catatan sebelum boleh dihantar ke Ibu Pejabat. Selepas permohonan susulan diluluskan Ibu Pejabat, kontrak asal ditandakan diganti.

### Senarai semak Ibu Pejabat

Item berikut direkod bersama tarikh apabila ditanda:

- Kelulusan Pengurusan Tertinggi.
- Permohonan kepada Kementerian Kewangan (MOF) untuk permohonan baru, pindah atau pelanjutan dengan kenaikan sewa lebih 10% atau minimum RM500. Ruang catatan dipaparkan selepas item ditanda.
- Permohonan kepada Kementerian Ekonomi jika keluasan melebihi 465 meter persegi, bagi pejabat baharu atau pindah. Ruang catatan dipaparkan selepas item ditanda.
- Ulasan KE diterima.
- Kelulusan MOF melalui KDN diterima.
- Permohonan dikemukakan kepada BPH.
- Surat kelulusan BPH diterima.

### Pengakuan selepas draf lulus

Negeri, sebelum mengembalikan dokumen:

- Draf akhir diterima untuk penyediaan dokumen perjanjian.
- 3 salinan dokumen perjanjian telah disediakan.
- Dokumen perjanjian telah ditandatangani oleh pemilik premis.
- Dokumen perjanjian telah dihantar melalui kurier / Serahan tangan kepada Cawangan Pembangunan. Setiap pengakuan merekod tarikh di sebelah kanan. Tarikh diisi secara automatik apabila ditanda dan boleh ditukar.

Ibu Pejabat, sebelum mati setem:

- Cawangan Pembangunan menerima dokumen dan mengemukakannya kepada TKPP AADK untuk tandatangan bagi pihak AADK atau Kerajaan Malaysia.
- Perjanjian yang ditandatangani TKPP dikembalikan kepada AADK Negeri untuk dimatikan setem dan diedarkan kepada pemilik.
- 1 salinan dihantar ke Cawangan Pembangunan untuk ProMIS dan rekod fail.

Negeri, untuk menutup permohonan:

- 3 salinan telah dimatikan setem di LHDN.
- 1 salinan telah diedarkan kepada pemilik premis.

### Peringatan tamat kontrak

Arahan berjadual dihantar pada jam 9.00 pagi waktu Malaysia.

| Baki tempoh | Penerima | Kekerapan | Berhenti apabila |
| --- | --- | --- | --- |
| Bawah 8 bulan hingga 5 bulan | Pentadbir negeri terlibat | 3 kali dalam 3 hari | Baki mencecah 5 bulan, atau 3 penghantaran selesai |
| Bawah 5 bulan hingga 3 bulan | Pentadbir negeri terlibat | Setiap Isnin | Baki mencecah 3 bulan |
| 3 bulan atau kurang | Pentadbir negeri, salinan kepada Ibu Pejabat | Setiap Isnin dan Rabu | Permohonan dihantar ke Ibu Pejabat |

## 6. Kriteria masuk dan keluar

### Kriteria masuk

- Persekitaran UAT stabil dan URL rasmi UAT dimaklumkan.
- Akaun Ibu Pejabat dan sekurang-kurangnya dua negeri berbeza telah disediakan.
- Data contoh mengandungi sekurang-kurangnya satu kontrak aktif bagi negeri yang diuji, termasuk satu kontrak berbaki bawah 8 bulan jika peringatan diuji.
- Akaun aplikasi pengesah (Google Authenticator atau setara) tersedia jika pengesahan dua faktor dihidupkan.
- Peti mel ujian atau paparan log mel boleh disemak.
- Penyemak bisnes dan penguji telah dilantik.

### Kriteria keluar

Sistem dicadangkan untuk diterima apabila semua syarat berikut dipenuhi:

- Kes hujung ke hujung E2E-01, E2E-02 dan E2E-03 lulus.
- Semua kes berkeutamaan **Kritikal** lulus.
- Sekurang-kurangnya 95% kes berkeutamaan **Tinggi** lulus.
- Tiada kecacatan kritikal atau besar yang masih terbuka.
- Kecacatan kecil yang diterima mempunyai tindakan susulan bertulis.
- Borang perakuan ditandatangani oleh wakil Negeri, Ibu Pejabat dan pemilik sistem.

## 7. Persekitaran, data dan cara merekod

| Item | Nilai ujian |
| --- | --- |
| URL | Diisi oleh pentadbir UAT: _______________________________ |
| Pelayar | Versi semasa Chrome atau Edge, desktop dan satu semakan telefon |
| Tarikh ujian | _______________________________ |
| Pengesahan dua faktor | Wajib dalam persekitaran pengeluaran. Dalam persekitaran lain, ikut tetapan `AUTH_MFA_REQUIRED`. |

Akaun dan kata laluan dikeluarkan oleh pentadbir UAT. Jangan tulis kata laluan dalam dokumen ini. Sediakan sekurang-kurangnya:

- Satu akaun Ibu Pejabat.
- Satu akaun Negeri A, contoh Selangor.
- Satu akaun Negeri B, negeri yang berbeza, untuk ujian sempadan.
- Satu akaun Negeri yang dinyahaktifkan.

Data permohonan ujian menggunakan premis fiksyen. Kadar sewa, keluasan dan tarikh direkod dalam lajur keputusan sebenar bagi kes yang menciptanya.

### Peraturan pelaksanaan

- Jalankan kes mengikut turutan dalam modul. Kes hujung ke hujung dijalankan selepas kes modul kritikal lulus, atau sebagai bukti penerimaan akhir.
- Satu penguji melaksanakan. Seorang penyemak bisnes mengesahkan kes kritikal.
- Status yang dibenarkan: **Lulus**, **Gagal**, **Disekat**, **Tidak diuji**.
- Jika gagal, cipta satu rekod dalam log kecacatan dan tulis nombor kecacatan pada kes ujian.
- Jangan guna data premis sebenar yang sulit dalam tangkapan skrin yang akan diedarkan di luar pasukan UAT.
- Kata laluan baharu mestilah sekurang-kurangnya 8 aksara, mengandungi huruf besar, huruf kecil dan nombor. Dalam pengeluaran, kata laluan yang diketahui tercemar juga ditolak.
- Dokumen sokongan: PDF, JPG atau PNG, tidak melebihi 10 MB atau had pelayan yang lebih rendah. Draf perjanjian: PDF sahaja, maksimum 40 MB atau had pelayan yang lebih rendah.
- Alasan padam dan alasan tarik semula: sekurang-kurangnya 10 aksara dan tidak melebihi 2,000 aksara.

### Klasifikasi kecacatan

| Tahap | Maksud | Contoh |
| --- | --- | --- |
| Kritikal | Aliran utama berhenti, data salah negeri terdedah, atau kontrak selesai tidak terbentuk. | Negeri B dapat membuka permohonan Negeri A. |
| Besar | Fungsi penting salah, ada jalan sementara yang menyusahkan. | Laporan PDF tidak memuat turun, aliran skrin masih boleh dibaca. |
| Kecil | Fungsi sekunder atau pengesahan input salah, kerja utama masih boleh disiapkan. | Mesej ralat tidak tepat. |
| Kosmetik | Paparan, ejaan atau jarak yang tidak mengubah keputusan. | Label terpotong pada telefon. |

## 8. Ringkasan kes

| Modul | ID | Bilangan | Keutamaan utama |
| --- | --- | --- | --- |
| Pengesahan | AUTH-01 hingga AUTH-07 | 7 | Kritikal |
| Pengesahan dua faktor | MFA-01 hingga MFA-05 | 5 | Kritikal |
| Profil dan penampilan | PROF-01 hingga PROF-03 | 3 | Sederhana |
| Pengguna | USER-01 hingga USER-08 | 8 | Tinggi |
| Papan pemuka | DASH-01 hingga DASH-04 | 4 | Tinggi |
| Permohonan | APP-01 hingga APP-05 | 5 | Kritikal |
| Langkah tindakan | PROC-01 hingga PROC-05 | 5 | Kritikal |
| Dokumen sokongan | DOC-01 hingga DOC-04 | 4 | Tinggi |
| Semakan Ibu Pejabat | HQ-01 hingga HQ-04 | 4 | Kritikal |
| Draf dan PUU | DRAF-01 hingga DRAF-04 | 4 | Kritikal |
| Pengesahan negeri | ACK-01 hingga ACK-02 | 2 | Kritikal |
| Tandatangan Ibu Pejabat | SIGN-01 | 1 | Kritikal |
| Mati setem | STEM-01 | 1 | Kritikal |
| Pindah dan lanjutan | FOL-01 hingga FOL-04 | 4 | Kritikal |
| Tarik semula dan padam | WD-01 hingga WD-06 | 6 | Tinggi |
| Kontrak sewaan | KON-01 hingga KON-04 | 4 | Tinggi |
| Laporan | LAP-01 hingga LAP-04 | 4 | Tinggi |
| Pemakluman | NOT-01 hingga NOT-03 | 3 | Tinggi |
| Kawalan akses | SEC-01 hingga SEC-03 | 3 | Kritikal |
| Penerimaan hujung ke hujung | E2E-01 hingga E2E-03 | 3 | Kritikal |
| **Jumlah** | | **80** | |

## 9. Skrip ujian

Lajur status diisi **Lulus**, **Gagal**, **Disekat** atau **Tidak diuji**.

### 9.1 Pengesahan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| AUTH-01 | Kritikal | Semua | 1. Buka URL sistem. 2. Masukkan emel dan kata laluan akaun aktif. 3. Hantar. | Halaman log masuk dipaparkan untuk pelawat. Log masuk berjaya membuka Papan Pemuka. Nama pengguna kelihatan. | | | | |
| AUTH-02 | Kritikal | Semua | 1. Masukkan emel yang sah dan kata laluan yang salah. 2. Hantar. | Log masuk ditolak. Mesej ralat dipaparkan. Papan pemuka tidak terbuka. | | | | |
| AUTH-03 | Kritikal | Ibu Pejabat, kemudian akaun yang dinyahaktif | 1. Ibu Pejabat nyahaktifkan akaun Negeri. 2. Cuba log masuk dengan akaun itu. | Akaun berstatus Nyahaktif tidak boleh masuk. | | | | |
| AUTH-04 | Tinggi | Semua | 1. Log masuk. 2. Pilih log keluar. 3. Tekan butang undur pelayar dan cuba buka Papan Pemuka. | Sesi ditutup. Halaman dalaman tidak boleh digunakan selepas log keluar. | | | | |
| AUTH-05 | Tinggi | Semua | 1. Pada log masuk, pilih lupa kata laluan. 2. Hantar emel akaun yang wujud. 3. Buka pautan set semula. 4. Tetapkan kata laluan baharu yang sah dan log masuk. | Sistem menerima permintaan tanpa mendedahkan sama ada emel tiada dalam sistem. Pautan membuka borang set semula. Kata laluan baharu boleh digunakan. Kata laluan lama ditolak. | | | | |
| AUTH-06 | Tinggi | Semua | 1. Pada set semula, cuba kata laluan tanpa huruf besar, tanpa nombor, atau kurang daripada 8 aksara. 2. Cuba dua nilai yang tidak sama. | Setiap percubaan ditolak dengan mesej yang menerangkan peraturan. Kata laluan tidak berubah. | | | | |
| AUTH-07 | Sederhana | Semua | 1. Cuba log masuk gagal berulang kali dalam satu minit. | Selepas had percubaan, sistem memperlahankan atau menolak percubaan seterusnya. Akaun yang sah masih boleh masuk selepas had berehat. | | | | |

### 9.2 Pengesahan dua faktor

Jalankan modul ini apabila pengesahan dua faktor dihidupkan. Jika ia dimatikan pada persekitaran ujian, tandakan kes sebagai **Tidak diuji** dan catat rujukan tetapan.

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| MFA-01 | Kritikal | Pengguna baharu | 1. Log masuk akaun yang belum mengesahkan dua faktor. 2. Perhatikan halaman yang terbuka. | Sistem membawa pengguna ke persediaan pengesahan. Menu kerja tidak boleh digunakan sebelum pengesahan selesai. | | | | |
| MFA-02 | Kritikal | Semua | 1. Imbas atau masukkan kunci ke aplikasi pengesah. 2. Simpan lapan kod pemulihan di tempat selamat ujian. 3. Masukkan kod 6 digit semasa. | Kod yang sah mengesahkan akaun. Kod pemulihan dipaparkan sekali. Log masuk seterusnya meminta kod pengesah. | | | | |
| MFA-03 | Tinggi | Semua | 1. Pada cabaran log masuk, masukkan kod yang salah. | Mesej kod tidak sah dipaparkan. Sesi kerja tidak terbuka. | | | | |
| MFA-04 | Kritikal | Semua | 1. Pada cabaran log masuk, gunakan satu kod pemulihan yang disimpan. | Kod pemulihan yang sah membuka sesi. | | | | |
| MFA-05 | Tinggi | Semua | 1. Guna semula kod pemulihan yang sama. | Kod yang telah digunakan ditolak. | | | | |

### 9.3 Profil dan penampilan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PROF-01 | Sederhana | Semua | 1. Buka Profil. 2. Tukar nama. 3. Simpan. | Nama baharu tersimpan dan dipaparkan selepas muat semula. | | | | |
| PROF-02 | Tinggi | Semua | 1. Cuba tukar emel atau kata laluan tanpa kata laluan semasa. 2. Ulang dengan kata laluan semasa yang betul dan kata laluan baharu yang sah. | Perubahan emel atau kata laluan ditolak tanpa kata laluan semasa. Perubahan berjaya apabila kata laluan semasa sah. | | | | |
| PROF-03 | Sederhana | Semua | 1. Buka Penampilan. 2. Tukar pilihan paparan yang disediakan. 3. Beralih ke halaman lain dan kembali. | Pilihan kekal pada pelayar yang sama selepas navigasi. | | | | |

### 9.4 Pengurusan pengguna

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| USER-01 | Tinggi | Ibu Pejabat | 1. Buka Pendaftaran Pengguna. 2. Daftar satu pengguna Negeri bagi negeri yang dipilih, dengan kata laluan sah. 3. Daftar satu pengguna Ibu Pejabat. | Kedua-dua akaun wujud dalam Senarai Pengguna. Pengguna Negeri mempunyai negeri. Pengguna Ibu Pejabat tidak diikat pada satu negeri. Akaun baharu boleh log masuk. | | | | |
| USER-02 | Kritikal | Negeri | 1. Log masuk sebagai Negeri A. 2. Buka pendaftaran. 3. Cuba daftar pengguna. | Peranan yang dibenarkan ialah Negeri sahaja. Negeri dikunci kepada negeri pentadbir. Pengguna negeri lain atau Ibu Pejabat tidak boleh didaftarkan. | | | | |
| USER-03 | Tinggi | Ibu Pejabat | 1. Daftar pengguna dengan emel yang sudah wujud. | Pendaftaran ditolak. Mesej menyatakan emel telah didaftarkan. | | | | |
| USER-04 | Kritikal | Ibu Pejabat | 1. Pada Senarai Pengguna, nyahaktifkan pengguna Negeri. 2. Aktifkan semula. | Status bertukar antara Aktif dan Nyahaktif. Hanya Ibu Pejabat melihat tindakan ini. | | | | |
| USER-05 | Tinggi | Ibu Pejabat | 1. Cuba nyahaktifkan akaun yang sedang digunakan sendiri. | Sistem menolak. Akaun sendiri kekal aktif. | | | | |
| USER-06 | Tinggi | Ibu Pejabat dan Negeri | 1. Buat satu tindakan yang direkod, contoh kemaskini profil atau kelulusan. 2. Buka jejak audit pengguna itu. | Jejak memaparkan tindakan yang berkaitan pengguna. Negeri tidak boleh membuka jejak pengguna negeri lain. | | | | |
| USER-07 | Tinggi | Ibu Pejabat | 1. Buka Senarai Pengguna. 2. Kemaskini emel jawatan Ketua Pengarah, TKPP, PKP dan KPP kepada peti mel ujian. 3. Simpan. 4. Muat semula. | Empat jawatan tersimpan. Emel kosong atau format tidak sah ditolak. | | | | |
| USER-08 | Kritikal | Negeri | 1. Sebagai Negeri A, buka Senarai Pengguna. | Senarai hanya mengandungi pengguna Negeri bagi negeri sendiri. Pengguna Ibu Pejabat dan negeri lain tidak dipaparkan. | | | | |

### 9.5 Papan pemuka

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| DASH-01 | Tinggi | Ibu Pejabat | 1. Buka Papan Pemuka. 2. Bandingkan kiraan permohonan baharu, kontrak aktif dan pecahan status dengan senarai berkaitan. | Kad dan pecahan status tindakan dipaparkan untuk semua negeri. Angka sepadan dengan senarai selepas penapis yang sama digunakan. | | | | |
| DASH-02 | Kritikal | Negeri | 1. Buka Papan Pemuka sebagai Negeri A. 2. Ulang sebagai Negeri B. | Setiap papan pemuka hanya mengira premis negeri sendiri. Angka dua negeri tidak bercampur. | | | | |
| DASH-03 | Tinggi | Semua pentadbir | 1. Semak senarai status tindakan. | Label berikut dipaparkan: Belum Terima Permohonan, Semakan Cawangan Pembangunan Sebelum ke BPH, Minit Ceraian Kelulusan Kos, Pertimbangan MOF, Pertimbangan KE, Pertimbangan BPH/SUPS, Sedia Draf Perjanjian, Semakan Cawangan Pembangunan Sebelum ke PUU, Semakan PUU, Kuiri PUU, Pindaan Berdasarkan Syor PUU dan Tandatangan Pemilik Premis, Tandatangan Penyewa (AADK), Tindakan Mati Setem. | | | | |
| DASH-04 | Tinggi | Semua pentadbir | 1. Semak bahagian kontrak yang akan tamat. | Kontrak aktif dibahagi kepada baki 3 bulan atau kurang, baki bawah 8 bulan tetapi lebih 3 bulan, dan baki lebih 8 bulan. Senarai amaran paling hampir tamat dipaparkan dahulu. | | | | |

### 9.6 Permohonan baharu

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| APP-01 | Kritikal | Negeri | 1. Buka Permohonan Baharu. 2. Pilih kategori Baru. 3. Isi nama premis, alamat, pemilik, jenis bangunan, kadar sewa, keluasan, tarikh mula dan tarikh akhir tawaran. 4. Simpan. | Rekod tercipta di Status Permohonan. Negeri pada borang sama dengan negeri pentadbir. Jenis bangunan yang sah ialah Kompleks kerajaan, Komersial atau Rumah Kediaman. | | | | |
| APP-02 | Tinggi | Negeri | 1. Hantar borang kosong. 2. Isi tarikh akhir lebih awal daripada tarikh mula. 3. Isi kadar sewa negatif. | Setiap kes dipaparkan mesej pengesahan. Rekod tidak tercipta atau tidak rosak. | | | | |
| APP-03 | Kritikal | Negeri | 1. Cuba tukar negeri pada borang kepada negeri lain, jika kawalan membenarkan, atau hantar nilai negeri lain. | Negeri mesti sepadan dengan pentadbir. Permohonan untuk negeri lain ditolak. | | | | |
| APP-04 | Sederhana | Negeri | 1. Buka permohonan yang belum dihantar. 2. Ubah satu medan dan tunggu autosimpan. 3. Muat semula halaman. | Nilai terakhir yang sah kekal. | | | | |
| APP-05 | Tinggi | Ibu Pejabat | 1. Cari menu Permohonan Baharu. | Menu cipta permohonan tidak dipaparkan kepada Ibu Pejabat. | | | | |

### 9.7 Langkah tindakan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PROC-01 | Kritikal | Negeri | 1. Buka permohonan kategori Baru. 2. Pada langkah awal, tekan Simpan dan pastikan langkah tidak beralih. 3. Tekan Seterusnya untuk ke langkah berikut. 4. Pada Surat kepada agensi, pastikan hanya Simpan dipaparkan. 5. Lengkapkan Surat niat, Borang JRP dan Surat kepada agensi. | Hanya langkah 2, 3 dan 5 dipaparkan. Notis kepada pemilik tidak diwajibkan. Simpan ada pada setiap langkah. Seterusnya disembunyikan pada langkah terakhir. Butang Hantar ke Ibu Pejabat boleh digunakan selepas ketiga-tiga langkah selesai. | | | | |
| PROC-02 | Kritikal | Negeri | 1. Buka permohonan kategori Lanjutan. 2. Semak langkah yang dipaparkan. | Hanya Notis kepada pemilik, Borang JRP dan Surat kepada agensi dipaparkan. Surat niat tidak diwajibkan. | | | | |
| PROC-03 | Kritikal | Negeri | 1. Pada permohonan yang langkahnya belum lengkap, tekan Hantar ke Ibu Pejabat dan sahkan Ya, Hantar. | Penghantaran ditolak. Status kekal menunggu langkah tindakan. | | | | |
| PROC-04 | Tinggi | Negeri | 1. Pada langkah Surat kepada agensi, tanda surat JPPH tanpa tarikh. 2. Tanda Pelan Lantai dan Gambar Bangunan tanpa tarikh. 3. Lengkapkan tarikh bagi item yang memerlukannya. | Item bertarikh tidak boleh disimpan sebagai lengkap tanpa tarikh. Pelan lantai dan gambar bangunan tidak memerlukan tarikh. | | | | |
| PROC-05 | Kritikal | Negeri | 1. Pada permohonan pindah atau lanjutan yang langkahnya sudah lengkap, kosongkan catatan. 2. Cuba hantar ke Ibu Pejabat. 3. Isi catatan dan hantar semula. | Penghantaran ditolak selagi catatan kosong. Selepas catatan diisi, status pada senarai menjadi Permohonan kepada MOF. Pengesahan Ya, Hantar dipaparkan sebelum penghantaran. | | | | |

### 9.8 Dokumen sokongan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| DOC-01 | Tinggi | Negeri | 1. Muat naik satu PDF sebagai surat tawaran, satu JPG sebagai gambar premis, dan satu PNG sebagai lain-lain. 2. Buka semula setiap fail. | Tiga fail tersimpan di bawah kontrak yang betul dan boleh dibuka oleh pengguna yang berkuasa. | | | | |
| DOC-02 | Tinggi | Negeri | 1. Cuba muat naik fail DOC atau EXE. 2. Cuba fail yang melebihi had saiz. 3. Hantar tanpa memilih fail. | Setiap percubaan ditolak. Mesej menyebut jenis yang dibenarkan atau had saiz. | | | | |
| DOC-03 | Tinggi | Ibu Pejabat | 1. Buka permohonan yang mempunyai dokumen. 2. Muat turun dokumen. | Ibu Pejabat boleh membaca dokumen permohonan yang dihantar. | | | | |
| DOC-04 | Kritikal | Negeri | 1. Sebagai Negeri B, cuba buka pautan dokumen Negeri A. | Akses ditolak. Fail tidak dimuat turun. | | | | |

### 9.9 Semakan Ibu Pejabat

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| HQ-01 | Kritikal | Ibu Pejabat | 1. Buka permohonan yang menunggu semakan Ibu Pejabat. 2. Semak status senarai: Permohonan kepada MOF, kemudian Permohonan kepada KE, kemudian Pertimbangan BPH, mengikut item yang belum ditanda. 3. Semak butiran, langkah tindakan dan dokumen. 4. Tanda senarai semak bersama tarikh. 5. Sahkan permohonan. | Butiran negeri dipaparkan sebagai baca sahaja. Status senarai bertukar selepas item MOF, KE atau BPH ditanda. Selepas sah, status menjadi penyediaan draf perjanjian. Pemakluman kepada negeri dihantar atau direkod. | | | | |
| HQ-02 | Tinggi | Ibu Pejabat | 1. Bandingkan permohonan berkeluasan 465 meter persegi atau kurang dengan permohonan melebihi 465 meter persegi bagi kategori Baru atau Pindah. | Item Kementerian Ekonomi dipaparkan apabila keluasan melebihi 465 meter persegi untuk pejabat baharu atau pindah. | | | | |
| HQ-03 | Tinggi | Negeri | 1. Semasa status masih menunggu semakan Ibu Pejabat, cuba sahkan permohonan. | Tindakan kelulusan Ibu Pejabat tidak tersedia kepada Negeri. | | | | |
| HQ-04 | Sederhana | Ibu Pejabat | 1. Tanda sebahagian senarai semak. 2. Tunggu autosimpan. 3. Keluar dan buka semula. | Tanda dan tarikh yang telah disimpan kekal. | | | | |

### 9.10 Draf perjanjian dan semakan PUU

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| DRAF-01 | Kritikal | Negeri | 1. Pada peringkat Penyediaan Draf, muat naik PDF draf perjanjian sehingga 40 MB. 2. Cuba fail lebih 40 MB dan fail bukan PDF. | Muat naik PDF yang sah berjaya. Status menjadi Semakan PUU. Fail boleh dibuka. Fail melebihi 40 MB atau bukan PDF ditolak. Pemakluman dihantar atau direkod. | | | | |
| DRAF-02 | Kritikal | Ibu Pejabat | 1. Buka permohonan berstatus Semakan PUU. 2. Pilih Luluskan dan sahkan. | Status menjadi Draf Perjanjian dikembalikan ke Pentadbir Negeri untuk dapatkan Tandatangan Pemilik Premis. Hasil semakan pada dokumen ialah diluluskan. | | | | |
| DRAF-03 | Kritikal | Ibu Pejabat, kemudian Negeri | 1. Pada pusingan semakan yang lain, Ibu Pejabat menekan Semak Semula. 2. Muat naik dokumen pindaan PUU dalam PDF sehingga 40 MB dan sahkan. 3. Cuba tanpa fail, fail bukan PDF, atau fail melebihi 40 MB. 4. Negeri memuat turun dokumen pindaan, kemudian muat naik PDF baharu. | Semak Semula tanpa PDF yang sah ditolak. Selepas berjaya, mesej menyatakan draf perjanjian dikembalikan oleh PUU untuk pindaan lanjut. Status menjadi Kuiri PUU. Sejarah memaparkan dokumen pindaan PUU. Muat naik baharu menjadi Semakan PUU seterusnya, bukan Semakan PUU 1 semula. | | | | |
| DRAF-04 | Tinggi | Negeri | 1. Pada penyediaan draf, cuba muat naik JPG atau DOCX. | Hanya PDF diterima untuk draf perjanjian. | | | | |

### 9.11 Pengesahan negeri selepas draf lulus

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| ACK-01 | Kritikal | Negeri | 1. Pada Draf Lulus, tanda kurang daripada empat pengakuan. 2. Cuba kembalikan kepada Ibu Pejabat. 3. Tanda keempat-empat pengakuan dan tukar salah satu tarikh. | Penghantaran disekat selagi ada pengakuan yang belum ditanda. Empat label pengakuan sepadan dengan Seksyen 5. Tarikh diisi secara automatik apabila ditanda dan tarikh yang ditukar kekal. | | | | |
| ACK-02 | Kritikal | Negeri | 1. Selepas empat pengakuan lengkap, kembalikan dokumen. | Status menjadi Dokumen Perjanjian dikembalikan ke Cawangan Pembangunan untuk tindakan Tandatangan Penyewa. Tindakan ini tidak tersedia kepada Ibu Pejabat. | | | | |

### 9.12 Tandatangan Ibu Pejabat

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| SIGN-01 | Kritikal | Ibu Pejabat | 1. Buka permohonan yang dikembalikan negeri. 2. Cuba hantar untuk mati setem sebelum tiga pengakuan lengkap. 3. Tanda tiga pengakuan dan sahkan. | Penghantaran disekat sehingga tiga pengakuan lengkap. Selepas sah, status menjadi Menunggu Mati Setem. | | | | |

### 9.13 Mati setem

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| STEM-01 | Kritikal | Negeri | 1. Pada Mati Setem, cuba selesaikan dengan satu pengakuan sahaja. 2. Tanda kedua-dua pengakuan dan tekan Selesai. 3. Buka Senarai Kontrak Sewaan. | Satu pengakuan tidak mencukupi. Selepas kedua-duanya ditanda, permohonan hilang daripada senarai kerja aktif dan muncul sebagai kontrak sewaan. Mesej kejayaan dipaparkan. | | | | |

### 9.14 Pindah dan lanjutan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| FOL-01 | Kritikal | Negeri | 1. Pada kontrak aktif negeri sendiri, cipta permohonan Lanjutan. | Borang susulan terbuka dengan kaitan kepada kontrak asal. Kategori ialah Lanjutan. Langkah yang dipaparkan ialah notis, JRP dan surat agensi. | | | | |
| FOL-02 | Kritikal | Negeri | 1. Pada kontrak aktif yang lain, cipta permohonan Pindah. 2. Kemaskini nama premis, alamat, pemilik, kadar sewa, keluasan dan tarikh kepada nilai baharu. | Kategori ialah Pindah. Butiran baharu boleh disimpan. Langkah yang dipaparkan ialah surat niat, JRP dan surat agensi. | | | | |
| FOL-03 | Tinggi | Negeri | 1. Pada permohonan susulan yang masih di langkah tindakan, pilih Batal dan Kembali ke Kontrak Asal. | Permohonan susulan dibatalkan. Kontrak asal kembali ke senarai kontrak. Butang Padam biasa tidak menggantikan tindakan ini. | | | | |
| FOL-04 | Kritikal | Ibu Pejabat | 1. Hantar satu permohonan lanjutan atau pindah yang lengkap. 2. Ibu Pejabat sahkan permohonan. 3. Semak kontrak asal. | Kontrak asal ditandakan diganti oleh permohonan baharu. Ia tidak lagi dikira sebagai kontrak aktif semasa. | | | | |

### 9.15 Tarik semula dan padam

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| WD-01 | Tinggi | Negeri | 1. Pada permohonan berstatus Menunggu semakan Ibu Pejabat, mohon tarik semula dengan alasan sekurang-kurangnya 10 aksara. | Permohonan ditanda menunggu kelulusan tarik semula. Ibu Pejabat melihat tindakan lulus atau tolak. | | | | |
| WD-02 | Sederhana | Negeri | 1. Hantar alasan tarik semula kurang daripada 10 aksara. | Permintaan ditolak dan mesej pengesahan dipaparkan. | | | | |
| WD-03 | Tinggi | Ibu Pejabat | 1. Luluskan tarik semula. | Permohonan kembali kepada pentadbir negeri untuk dikemaskini. Status semakan Ibu Pejabat berakhir. | | | | |
| WD-04 | Tinggi | Ibu Pejabat | 1. Pada permohonan tarik semula yang lain, tolak permintaan. | Permohonan kekal dalam senarai semakan Ibu Pejabat. | | | | |
| WD-05 | Tinggi | Negeri | 1. Pada permohonan biasa yang belum dihantar, pilih Padam. 2. Isi alasan sekurang-kurangnya 10 aksara dan sahkan. | Permohonan keluar daripada senarai aktif. Ibu Pejabat boleh melihat tarikh dan alasan pada sejarah padam. | | | | |
| WD-06 | Kritikal | Negeri | 1. Cari butang Padam pada permohonan yang sedang disemak Ibu Pejabat dan pada permohonan di peringkat draf perjanjian. | Butang Padam tidak dipaparkan. Permohonan tersebut tidak boleh dipadam. | | | | |

### 9.16 Kontrak sewaan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| KON-01 | Tinggi | Semua pentadbir | 1. Buka Senarai Kontrak Sewaan. 2. Semak tab aktif dan sejarah. | Tab aktif memaparkan kontrak yang masih berkuat kuasa. Sejarah memaparkan kontrak yang telah diganti atau tamat, mengikut data ujian. | | | | |
| KON-02 | Sederhana | Semua pentadbir | 1. Cari menggunakan nama premis dan nama negeri. | Hasil carian sepadan. Carian kosong memulangkan senarai asal. | | | | |
| KON-03 | Tinggi | Semua pentadbir | 1. Buka butiran satu kontrak. | Butiran premis, tempoh, kategori dan dokumen yang berkaitan dipaparkan. | | | | |
| KON-04 | Kritikal | Negeri | 1. Sebagai Negeri A, cuba buka butiran kontrak Negeri B melalui senarai atau pautan terus. | Kontrak negeri lain tidak tersenarai dan pautan terus ditolak. | | | | |

### 9.17 Laporan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| LAP-01 | Tinggi | Ibu Pejabat | 1. Buka Jana Laporan. 2. Jana setiap jenis: Ringkasan Keseluruhan, Senarai Permohonan, Senarai Kontrak Aktif, Laporan Kontrak mengikut Negeri, dan Laporan Status Sewaan. | Kelima-lima jenis dipaparkan. Laporan status sewaan menggunakan tarikh semasa pada tajuk dan mengumpul rekod mengikut bahagian BPH, PUU, MOF, KE, Ibu Pejabat dan PTJ apabila data wujud. Dalam Tindakan PTJ, baki bulan paling rendah berada di atas. | | | | |
| LAP-02 | Tinggi | Ibu Pejabat | 1. Jana laporan yang sama tanpa negeri, kemudian dengan satu negeri. | Penapis negeri mengecilkan hasil kepada negeri itu sahaja. Tanpa penapis, semua negeri termasuk. | | | | |
| LAP-03 | Tinggi | Ibu Pejabat | 1. Muat turun PDF bagi laporan ringkasan dan laporan kontrak mengikut negeri. 2. Buka fail. | Fail PDF dimuat turun. Laporan lebar menggunakan halaman landskap. Kandungan sepadan dengan skrin. | | | | |
| LAP-04 | Kritikal | Negeri | 1. Semak menu sisi. 2. Cuba buka URL laporan secara terus. | Menu Jana Laporan tidak dipaparkan. Akses terus ditolak. | | | | |

### 9.18 Peti masuk dan peringatan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| NOT-01 | Tinggi | Semua pentadbir | 1. Jalankan satu tindakan yang menjana pemakluman, contoh hantar ke Ibu Pejabat. 2. Buka ikon peti masuk dan halaman Peti Masuk. 3. Tanda semua sebagai dibaca. | Item baharu muncul untuk peranan yang perlu bertindak. Tanda dibaca mengosongkan kiraan belum baca. | | | | |
| NOT-02 | Sederhana | Semua pentadbir | 1. Perhatikan lencana pada Pengurusan Permohonan dan Kontrak Sewaan. 2. Selesaikan atau buka item yang belum dilihat. | Lencana memaparkan bilangan item yang menunggu. Bilangan berkurang selepas item dilihat atau selesai, selepas segar semula menu. | | | | |
| NOT-03 | Tinggi | Pentadbir sistem bersama Negeri | 1. Sediakan kontrak aktif dalam setiap julat baki: bawah 8 bulan, bawah 5 bulan, dan 3 bulan atau kurang. 2. Jalankan peringatan pada tarikh yang sepadan, atau minta pentadbir sistem menjalankan arahan peringatan sekali. 3. Semak peti mel ujian. | Subjek dan jadual penerima sepadan dengan Seksyen 5. Peringatan 3 bulan menyalin Ibu Pejabat. Penghantaran tidak diulang di luar peraturan kekerapan. Selepas permohonan 3 bulan dihantar ke Ibu Pejabat, peringatan bagi kontrak itu berhenti. | | | | |

### 9.19 Kawalan akses am

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| SEC-01 | Kritikal | Pelawat | 1. Tanpa log masuk, buka terus Papan Pemuka, Status Permohonan, Kontrak Sewaan, Pengguna dan Laporan. | Setiap halaman membawa pengguna ke log masuk. Data tidak dipaparkan. | | | | |
| SEC-02 | Kritikal | Negeri | 1. Salin pautan semakan permohonan Negeri A. 2. Buka pautan itu semasa log masuk sebagai Negeri B. | Permohonan tidak dibuka. Akses ditolak atau halaman tidak dijumpai, tanpa mendedahkan butiran. | | | | |
| SEC-03 | Tinggi | Semua | 1. Pada telefon, buka menu. 2. Semak halaman utama setiap peranan. 3. Kecilkan tetingkap desktop. | Menu boleh dibuka dan ditutup. Tajuk, jadual dan borang utama boleh digunakan tanpa tindakan penting terpotong. | | | | |

## 10. Kes penerimaan hujung ke hujung

Kes ini ialah bukti penerimaan aliran bisnes. Gunakan premis fiksyen yang baharu bagi setiap kes. Catat nombor atau nama premis dalam keputusan sebenar.

| ID | Keutamaan | Senario | Langkah ringkas | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| E2E-01 | Kritikal | Permohonan baharu hingga kontrak aktif | 1. Negeri mencipta permohonan Baru dan memuat naik dokumen sokongan. 2. Negeri melengkapkan surat niat, JRP dan surat agensi. 3. Negeri menekan Hantar ke Ibu Pejabat dan mengesahkan Ya, Hantar. 4. Ibu Pejabat menyemak senarai semak dan meneruskan ke draf. 5. Negeri memuat naik PDF draf. 6. Ibu Pejabat meluluskan Semakan PUU 1. 7. Negeri mengesahkan empat pengakuan dan mengembalikan dokumen. 8. Ibu Pejabat mengesahkan tiga pengakuan tandatangan. 9. Negeri mengesahkan dua pengakuan mati setem. | Satu kontrak baharu muncul dalam Senarai Kontrak Sewaan negeri tersebut dan dalam laporan Ibu Pejabat. Papan pemuka kedua-dua peranan berubah dengan betul. Pemakluman setiap peringkat diterima pihak yang berkenaan. | | | | |
| E2E-02 | Kritikal | Lanjutan kontrak | 1. Negeri mencipta Lanjutan daripada kontrak aktif. 2. Negeri mengisi catatan, notis pemilik, JRP dan surat agensi. 3. Ulang langkah semakan hingga mati setem seperti E2E-01, dengan langkah lanjutan dan bukan surat niat. | Kontrak asal diganti selepas kelulusan Ibu Pejabat. Kontrak lanjutan yang selesai menjadi kontrak aktif. | | | | |
| E2E-03 | Kritikal | Pindah premis, termasuk satu pembatalan semakan PUU | 1. Negeri mencipta Pindah dan mengisi butiran premis baharu. 2. Lengkapkan surat niat, JRP, surat agensi dan catatan, kemudian hantar. 3. Ibu Pejabat meneruskan ke draf. 4. Negeri memuat naik draf pertama. 5. Ibu Pejabat membatalkan semakan. 6. Negeri memuat naik draf kedua. 7. Ibu Pejabat meluluskan Semakan PUU 2. 8. Siapkan pengesahan negeri, tandatangan Ibu Pejabat dan mati setem. | Pusingan kedua direkod sebagai Semakan PUU 2. Kontrak pindah yang selesai aktif. Kontrak premis lama tidak lagi aktif. | | | | |

## 11. Semakan rentas pelayar dan telefon

Jalankan AUTH-01, APP-01 dan paparan Senarai Kontrak Sewaan pada setiap kombinasi. Kes lain kekal pada pelayar desktop utama.

| Kombinasi | Pelayar dan saiz | AUTH-01 | APP-01 | Senarai kontrak | Catatan | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Desktop utama | Chrome, lebar biasa | | | | | | |
| Desktop kedua | Edge, lebar biasa | | | | | | |
| Telefon | Chrome, lebar lebih kurang 390 piksel | | | | | | |

## 12. Log kecacatan

| No. | Tarikh | ID kes | Tahap | Ringkasan | Langkah ulang | Keputusan sebenar | Keputusan dijangka | Status | Disahkan oleh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| DEF-001 | | | | | | | | Terbuka | |
| DEF-002 | | | | | | | | Terbuka | |
| DEF-003 | | | | | | | | Terbuka | |
| DEF-004 | | | | | | | | Terbuka | |
| DEF-005 | | | | | | | | Terbuka | |

Status kecacatan: Terbuka, Dibaiki, Diuji semula, Ditutup, Diterima dengan syarat.

## 13. Ujian semula

| No. kecacatan | Versi baiki | ID kes diuji semula | Keputusan | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- |
| | | | | | |
| | | | | | |
| | | | | | |

## 14. Rumusan keputusan

| Perkara | Bilangan |
| --- | --- |
| Jumlah kes | 80 |
| Lulus | |
| Gagal | |
| Disekat | |
| Tidak diuji | |
| Kecacatan kritikal terbuka | |
| Kecacatan besar terbuka | |
| Kecacatan kecil diterima | |

| Syarat keluar | Dipenuhi (Ya/Tidak) | Catatan |
| --- | --- | --- |
| E2E-01, E2E-02 dan E2E-03 lulus | | |
| Semua kes kritikal lulus | | |
| Sekurang-kurangnya 95% kes tinggi lulus | | |
| Tiada kecacatan kritikal atau besar terbuka | | |

Cadangan penguji:

- [ ] Diterima
- [ ] Diterima dengan syarat
- [ ] Tidak diterima

Syarat atau pengecualian:

_______________________________________________________________________________

_______________________________________________________________________________

## 15. Jejak pemakluman yang perlu dibuktikan

Penguji menanda apabila emel atau rekod pemakluman wujud. Alamat sebenar jawatan tidak ditulis di sini. Emel jawatan ujian ditetapkan melalui USER-07.

| Peristiwa | Penerima yang dijangka | Bukti | Status |
| --- | --- | --- | --- |
| Permohonan dihantar ke Ibu Pejabat | Ibu Pejabat | | |
| Ibu Pejabat meneruskan ke draf | Negeri | | |
| Draf dimuat naik | Pihak semakan yang ditetapkan | | |
| Semakan PUU diluluskan | Negeri | | |
| Semakan PUU dibatalkan | Negeri | | |
| Dokumen dikembalikan ke Cawangan Pembangunan | Ibu Pejabat | | |
| Dihantar untuk mati setem | Negeri | | |
| Permohonan selesai | Negeri dan pihak yang ditetapkan | | |
| Peringatan bawah 8 bulan | Pentadbir negeri | | |
| Peringatan bawah 5 bulan | Pentadbir negeri | | |
| Amaran baki 3 bulan | Pentadbir negeri dan salinan Ibu Pejabat | | |
| Set semula kata laluan | Pemilik akaun | | |

## 16. Perakuan penerimaan

Saya mengesahkan bahawa kes ujian dalam dokumen ini telah dijalankan pada persekitaran UAT yang dinyatakan, dan keputusan yang ditulis adalah benar.

| Peranan | Nama | Jawatan | Keputusan | Tarikh | Tandatangan |
| --- | --- | --- | --- | --- | --- |
| Penguji Negeri | | | Diterima / Bersyarat / Tidak diterima | | |
| Penguji Ibu Pejabat | | | Diterima / Bersyarat / Tidak diterima | | |
| Pemilik sistem | | | Diterima / Bersyarat / Tidak diterima | | |

---

*Tamat dokumen AADK/CP/E-SEWAAN/UAT/001, versi 1.0.*
