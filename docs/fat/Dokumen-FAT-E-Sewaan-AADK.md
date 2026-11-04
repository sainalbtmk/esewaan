# Dokumen Ujian Penerimaan Kilang (FAT)

**SPEKTRA**  
Sistem Pemantauan Kontrak Sewaan Premis AADK  
Agensi Antidadah Kebangsaan

| Medan | Butiran |
| --- | --- |
| No. dokumen | AADK/CP/SPEKTRA/FAT/001 |
| Versi | 1.0 |
| Tarikh | 28 Oktober 2026 |
| Status | Untuk pelaksanaan FAT |
| Klasifikasi | Terhad |
| Pemilik sistem | Cawangan Pembangunan, AADK |
| Pelaksana | Pasukan pembangunan sistem |
| Saksi | Wakil Cawangan Pembangunan, jika hadir |

Dokumen ini ialah skrip ujian penerimaan kilang. Penguji mengisi lajur **Keputusan sebenar**, **Status**, **Penguji** dan **Tarikh**. Sistem layak diserahkan untuk Ujian Penerimaan Pengguna apabila kriteria keluar di Seksyen 8 dipenuhi dan borang perakuan di Seksyen 22 ditandatangani.

FAT membuktikan binaan yang akan diserahkan boleh dipasang, dikonfigurasi, dan menjalankan fungsi SPEKTRA pada persekitaran kilang. UAT (`AADK/CP/SPEKTRA/UAT/001`) dijalankan selepas itu oleh pengguna bisnes.

## Kandungan

1. Kawalan dokumen
2. Tujuan
3. Rujukan
4. Skop
5. Peranan dan aliran yang diuji
6. Persekitaran kilang
7. Kaedah dan klasifikasi
8. Kriteria masuk dan keluar
9. Ringkasan kes
10. Skrip ujian teknikal
11. Skrip ujian fungsi
12. Kes hujung ke hujung
13. Semakan pelayar
14. Jejak pemakluman
15. Log kecacatan
16. Ujian semula
17. Matriks kebolehkesanan ke UAT
18. Rumusan keputusan
19. Lampiran bukti
20. Konfigurasi yang disahkan
21. Senarai semak serahan ke UAT
22. Perakuan serahan

---

## 1. Kawalan dokumen

| Peranan | Jawatan | Nama | Tarikh | Tandatangan |
| --- | --- | --- | --- | --- |
| Penyedia | Pasukan pembangunan sistem | | | |
| Ketua ujian kilang | Pasukan pembangunan sistem | | | |
| Penyemak teknikal | Pasukan pembangunan sistem | | | |
| Saksi pemilik sistem | Cawangan Pembangunan AADK | | | |
| Pelulus serahan ke UAT | Ketua pasukan pembangunan | | | |

### Sejarah pindaan

| Versi | Tarikh | Penyedia | Ringkasan perubahan |
| --- | --- | --- | --- |
| 1.0 | 28 Oktober 2026 | Pasukan pembangunan | Terbitan pertama skrip FAT berdasarkan binaan sistem semasa, termasuk pemasangan, keselamatan, pemakluman dan aliran permohonan. |

### Identiti binaan yang diuji

| Medan | Nilai |
| --- | --- |
| Versi atau tanda binaan | |
| Cawangan atau rujukan keluaran | |
| Tarikh binaan | |
| URL persekitaran kilang | |
| Pangkalan data FAT | |

---

## 2. Tujuan

FAT mengesahkan bahawa binaan SPEKTRA yang akan diserahkan:

- boleh dipasang pada persekitaran kilang yang bersih;
- mematuhi garis asas konfigurasi keselamatan;
- lulus suite ujian automatik projek;
- melaksanakan aliran permohonan dari pendaftaran negeri sehingga kontrak berkuat kuasa;
- mengasingkan data Ibu Pejabat dan Negeri;
- menjana pemakluman, laporan PDF dan peringatan tamat kontrak yang betul;
- menyimpan dokumen kontrak di luar capaian awam.

Keputusan lulus FAT bermaksud binaan ini sedia untuk dipasang pada persekitaran UAT. Ia belum bermaksud pengguna bisnes telah menerima sistem.

## 3. Rujukan

| Dokumen | No. | Kegunaan dalam FAT |
| --- | --- | --- |
| Dokumen UAT SPEKTRA | AADK/CP/SPEKTRA/UAT/001 | Penerimaan bisnes selepas FAT. Matriks di Seksyen 17 memaut kes fungsi. |
| Manual Pengguna SPEKTRA | AADK/CP/SPEKTRA/MANUAL/001 | Nama menu, butang dan status pada skrin. |
| Skrip ujian automatik | `tests/Feature` | Bukti REG-01. |

Jika label pada skrin berbeza daripada dokumen rujukan, label skrin binaan yang diuji ialah keputusan yang dijangka. Perbezaan itu direkod dalam keputusan sebenar.

## 4. Skop

### Dalam skop

- Pemasangan bersih, migrasi, benih data dan binaan aset.
- Konfigurasi aplikasi, sesi, pengesahan dua faktor, mel, giliran dan had muat naik.
- Suite ujian automatik dan semakan gaya kod.
- Header keselamatan, token borang, had kadar, log masuk gagal dan storan peribadi.
- Log masuk, log keluar, lupa kata laluan dan pengesahan dua faktor.
- Profil, penampilan, pengguna, jejak audit dan emel jawatan.
- Papan pemuka dan pecahan status tindakan.
- Permohonan baharu, pindah dan lanjutan, termasuk daerah, langkah tindakan dan dokumen sokongan.
- Semakan Ibu Pejabat, draf perjanjian, semakan PUU, pengesahan tandatangan dan mati setem.
- Tarik semula, padam dan batal permohonan susulan.
- Senarai kontrak sewaan.
- Laporan Ibu Pejabat, penapis negeri dan daerah, serta muat turun PDF.
- Peti masuk, lencana menu dan arahan peringatan tamat kontrak.
- Sandaran dan pulihan pangkalan data serta fail peribadi pada persekitaran kilang.

### Luar skop

- Ujian beban, penembusan keselamatan berbayar dan audit kod sumber baris demi baris.
- Proses luar sistem: kelulusan MOF, Kementerian Ekonomi, BPH, tandatangan fizikal TKPP, ProMIS dan LHDN. Sistem merekod pengakuan bahawa tindakan itu telah dibuat.
- Penerimaan bisnes oleh pentadbir negeri. Itu skop UAT.
- Perubahan dasar atau borang yang belum dibina dalam binaan yang diuji.

## 5. Peranan dan aliran yang diuji

| Peranan sistem | Label pada skrin | Kuasa utama |
| --- | --- | --- |
| `admin_hq` | Ibu Pejabat | Semua negeri. Semak permohonan, lulus atau batalkan semakan PUU, sahkan tandatangan, urus status pengguna, emel jawatan dan laporan. |
| `admin_negeri` | Negeri | Negeri sendiri sahaja. Cipta dan kemaskini permohonan, lengkapkan langkah tindakan, muat naik dokumen dan draf perjanjian, sahkan pengakuan negeri serta mati setem. |

Pentadbir Negeri hanya melihat dan bertindak ke atas rekod negerinya. Pentadbir Negeri hanya boleh mendaftarkan pengguna Negeri bagi negeri yang sama. Ibu Pejabat boleh mendaftarkan pengguna Ibu Pejabat dan pengguna Negeri bagi mana-mana negeri. Ibu Pejabat tidak mempunyai menu **Permohonan Baharu**.

### Peringkat pada skrin semakan

1. **Permohonan Baru** — butiran premis dan langkah tindakan negeri.
2. **Semakan Ibu Pejabat** — senarai semak kelulusan, kemudian permohonan diteruskan ke penyediaan draf.
3. **Penyediaan Draf** — Negeri memuat naik draf perjanjian dalam format PDF.
4. **Dalam tindakan PUU** — Ibu Pejabat menekan **Luluskan** atau **Semak Semula**. Kedua-dua tindakan memerlukan PDF yang sah. Setiap muat naik negeri menambah pusingan Semakan PUU 1, Semakan PUU 2, dan seterusnya.
5. **Draf Lulus** — status senarai ialah *Draf Perjanjian dikembalikan ke Pentadbir Negeri untuk dapatkan Tandatangan Pemilik Premis*. Negeri mengesahkan empat perkara, kemudian mengembalikan dokumen.
6. **Pengesahan dan Tandatangan** — status senarai selepas dikembalikan ialah *Dokumen Perjanjian dikembalikan ke Cawangan Pembangunan untuk tindakan Tandatangan Penyewa*. Ibu Pejabat mengesahkan empat perkara.
7. **Mati Setem** — status senarai ialah *Menunggu Mati Setem*. Negeri mengesahkan tiga perkara.
8. **Selesai** — rekod masuk ke Senarai Kontrak Sewaan.

### Kategori dan langkah tindakan

| Kategori | Langkah yang dipaparkan |
| --- | --- |
| Baru | 2 Surat niat kepada pemilik premis, 3 Borang JRP, 5 Surat kepada agensi |
| Pindah | 2 Surat niat kepada pemilik premis, 3 Borang JRP, 5 Surat kepada agensi |
| Lanjutan | 1 Notis kepada pemilik premis, 3 Borang JRP, 5 Surat kepada agensi |

Butang **Simpan** ada pada setiap langkah. Butang **Seterusnya** disembunyikan pada langkah terakhir. Langkah 4 tidak dipaparkan sebagai langkah berasingan.

Item Surat kepada agensi yang memerlukan tarikh:

- Surat Jabatan Penilaian dan Perkhidmatan Harta
- Ketua Pegawai Keselamatan Kerajaan Malaysia
- Jabatan Bomba dan Penyelamat Malaysia
- Jabatan Ketua Pengarah Tanah dan Galian Persekutuan
- Carian Rasmi terkini dari Pejabat Tanah Daerah
- Surat tawaran dari Pemilik Premis

Pelan Lantai dan Gambar Bangunan Terkini tidak memerlukan tarikh.

Permohonan pindah atau lanjutan memerlukan catatan sebelum boleh dihantar ke Ibu Pejabat. Selepas permohonan susulan diluluskan Ibu Pejabat, kontrak asal ditandakan diganti.

Daerah pada borang permohonan wajib dan mesti wujud dalam senarai daerah AADK bagi negeri pentadbir. Jenis bangunan yang sah ialah Kompleks kerajaan, Komersial atau Rumah Kediaman.

### Senarai semak Ibu Pejabat

Setiap item merekod tarikh apabila ditanda. Item MOF dan KE memaparkan ruang catatan selepas ditanda.

| Item pada skrin | Catatan status senarai |
| --- | --- |
| Kelulusan Pengurusan Tertinggi | Ditanda awal untuk kategori Baru jika senarai semak belum pernah disimpan. |
| Permohonan kepada Kementerian Kewangan (MOF) untuk permohonan Baru / Pindah / Pelanjutan dengan Kenaikan Sewa Lebih 10% @ minimum RM500 | Selagi belum ditanda, status senarai ialah *Permohonan kepada MOF*. |
| Permohonan kepada Kementerian Ekonomi (KE) (jika keluasan melebihi 465 mps) — untuk permohonan perpindahan ruang pejabat / ruang pejabat baharu | Ditanda awal jika keluasan melebihi 465 meter persegi dan senarai semak belum pernah disimpan. Selagi belum ditanda, dan MOF sudah ditanda, status senarai ialah *Permohonan kepada KE*. |
| Cawangan Pembangunan AADK menerima ulasan daripada Kementerian Ekonomi (KE) | |
| Cawangan Pembangunan AADK menerima Kelulusan daripada MOF melalui KDN | |
| Cawangan Pembangunan AADK mengemukakan permohonan Bahagian Hartanah (BPH) Jabatan Perdana Menteri untuk mendapatkan kelulusan | Selagi belum ditanda, dan MOF serta KE sudah ditanda, status senarai ialah *Pertimbangan BPH*. |
| Cawangan Pembangunan AADK terima surat kelulusan daripada BPH | |

Ambang kenaikan sewa yang digunakan sistem untuk perbandingan kontrak susulan ialah lebih daripada atau sama dengan RM500.

### Pengakuan negeri selepas draf lulus

- Draf akhir diterima untuk penyediaan dokumen perjanjian.
- 3 Salinan Dokumen Perjanjian telah disediakan.
- Dokumen Perjanjian telah ditandatangani oleh pemilik premis.
- Dokumen perjanjian telah dihantar melalui kurier / Serahan tangan kepada Cawangan Pembangunan.

### Pengakuan Ibu Pejabat sebelum mati setem

- Cawangan Pembangunan terima Perjanjian yang telah ditandatangani oleh Pemilik Premis.
- Perjanjian dihantar ke Pejabat PKP untuk tandatangan Saksi.
- Perjanjian dihantar ke Pejabat TKPP untuk tandatangan Penyewa.
- Perjanjian dikembalikan ke AADK Negeri untuk tindakan mati setem dan edaran kepada Pemilik Premis.

### Pengakuan negeri untuk menutup permohonan

- 3 Salinan Dokumen Perjanjian telah dimatikan setem di LHDN.
- 1 Salinan Dokumen Perjanjian telah diedar kepada Pemilik Premis.
- Salinan dihantar ke Cawangan Pembangunan melalui Pos/Kourier.

Tarikh pengakuan diisi dengan tarikh semasa waktu Malaysia apabila kotak ditanda, dan boleh ditukar.

### Peringatan tamat kontrak

Arahan `contracts:send-expiry-reminders` dijadualkan setiap hari pada jam 9.00 pagi, zon `Asia/Kuala_Lumpur`.

| Baki tempoh | Subjek | Penerima | Kekerapan | Berhenti apabila |
| --- | --- | --- | --- | --- |
| Bawah 8 bulan hingga 5 bulan | SPEKTRA: Peringatan Kontrak Bawah 8 Bulan | Pentadbir negeri terlibat | 3 kali dalam 3 hari | Baki mencecah 5 bulan, atau 3 penghantaran selesai |
| Bawah 5 bulan hingga 3 bulan | SPEKTRA: Peringatan Tegas Kontrak Bawah 5 Bulan | Pentadbir negeri terlibat | Setiap Isnin | Baki mencecah 3 bulan |
| 3 bulan atau kurang | SPEKTRA: AMARAN Kontrak Baki 3 Bulan | Pentadbir negeri, salinan kepada Ibu Pejabat | Setiap Isnin dan Rabu | Permohonan dihantar ke Ibu Pejabat |

Jika `MAIL_TEST_RECIPIENT` diisi, pemakluman aliran kerja dihantar ke alamat itu dan bukannya ke peti sebenar jawatan atau pengguna.

## 6. Persekitaran kilang

| Item | Nilai ujian |
| --- | --- |
| URL | |
| Pelayan aplikasi | |
| PHP | 8.2 atau lebih baharu. Binaan rujukan projek ialah PHP 8.4. |
| Pangkalan data | |
| Pelayar | Versi semasa Chrome dan Edge |
| Mel | Peti ujian, atau `MAIL_MAILER=log` dengan fail log yang boleh dibaca. Pada simulasi pengeluaran, mailer log tidak digunakan untuk set semula kata laluan. |
| Pengesahan dua faktor | Wajib apabila `APP_ENV=production` dan `AUTH_MFA_REQUIRED` tidak ditetapkan. Dalam persekitaran lain, ikut nilai `AUTH_MFA_REQUIRED`. |
| Tarikh ujian | |
| Ketua ujian | |

Akaun dan kata laluan dikeluarkan oleh ketua ujian. Jangan tulis kata laluan dalam dokumen ini.

Sediakan sekurang-kurangnya:

- Satu akaun Ibu Pejabat.
- Satu akaun Negeri A.
- Satu akaun Negeri B, negeri yang berbeza.
- Satu akaun Negeri yang dinyahaktifkan semasa kes berkaitan.
- Aplikasi pengesah (Google Authenticator atau setara) jika pengesahan dua faktor dihidupkan.
- Fail contoh: PDF di bawah 10 MB, JPG, PNG, PDF draf di bawah 40 MB, PDF melebihi 40 MB, fail bukan PDF, dan fail dokumen sokongan melebihi 10 MB.

Data premis ialah data fiksyen. Jangan guna premis sebenar yang sulit dalam tangkapan skrin yang akan diedarkan di luar pasukan FAT.

### Garis asas konfigurasi serahan

| Tetapan | Nilai yang diterima untuk serahan |
| --- | --- |
| `APP_NAME` | SPEKTRA |
| `APP_ENV` | Persekitaran yang diisytiharkan untuk FAT. Simulasi pengeluaran menggunakan `production`. |
| `APP_DEBUG` | `false` |
| `APP_KEY` | Diisi dan tidak dikongsi dalam dokumen ini |
| `APP_URL` | URL sebenar persekitaran yang diuji |
| `LOG_LEVEL` | `warning` atau lebih ketat pada simulasi pengeluaran |
| `SESSION_DRIVER` | `database` |
| `SESSION_LIFETIME` | 120 |
| `SESSION_ENCRYPT` | `true` |
| `SESSION_SECURE_COOKIE` | `true` apabila URL menggunakan HTTPS |
| `SESSION_SAME_SITE` | `lax` |
| `QUEUE_CONNECTION` | `database` |
| `CACHE_STORE` | `database` |
| `FILESYSTEM_DISK` | `local`, akar `storage/app/private` |
| Had dokumen sokongan | PDF, JPG atau PNG, maksimum 10 MB atau had PHP yang lebih rendah |
| Had draf dan dokumen PUU | PDF sahaja, maksimum 40 MB atau had PHP yang lebih rendah |
| Kata laluan baharu | Sekurang-kurangnya 8 aksara, huruf besar, huruf kecil dan nombor. Pada `production`, kata laluan yang diketahui tercemar juga ditolak. |
| Alasan padam dan tarik semula | Sekurang-kurangnya 10 aksara dan tidak melebihi 2,000 aksara |

## 7. Kaedah dan klasifikasi

### Peraturan pelaksanaan

- Jalankan Seksyen 10 sebelum Seksyen 11. Kes hujung ke hujung di Seksyen 12 dijalankan selepas kes modul kritikal lulus. Semakan pelayar di Seksyen 13 hanya meliputi kombinasi yang dinyatakan.
- Satu penguji melaksanakan. Penyemak teknikal mengesahkan kes kritikal dan kes keselamatan.
- Status yang dibenarkan: **Lulus**, **Gagal**, **Disekat**, **Tidak diuji**.
- Jika gagal, cipta satu rekod dalam log kecacatan dan tulis nombor kecacatan pada kes ujian.
- Arahan artisan dijalankan pada akar projek binaan yang diuji.
- Bukti yang diterima: tangkapan skrin, petikan log tanpa kata laluan, subjek emel, nama fail PDF, dan output arahan. Jangan lampirkan fail `.env`.

### Klasifikasi kecacatan

| Tahap | Maksud | Contoh |
| --- | --- | --- |
| Kritikal | Pemasangan gagal, aliran utama berhenti, data negeri lain terdedah, atau dokumen peribadi boleh dimuat turun tanpa kuasa. | Negeri B membuka permohonan Negeri A. |
| Besar | Fungsi penting salah, ada jalan sementara yang menyusahkan. | PDF laporan tidak dimuat turun, skrin laporan masih boleh dibaca. |
| Kecil | Pengesahan input atau mesej salah, kerja utama masih boleh disiapkan. | Mesej ralat tidak tepat. |
| Kosmetik | Paparan, ejaan atau jarak yang tidak mengubah keputusan. | Label terpotong pada telefon. |

Status kecacatan: Terbuka, Dibaiki, Diuji semula, Ditutup, Diterima dengan syarat.

## 8. Kriteria masuk dan keluar

### Kriteria masuk

- Binaan yang akan diuji dikenal pasti dalam Seksyen 1.
- Pangkalan data FAT kosong tersedia, berasingan daripada data pengeluaran.
- Akaun pelayan, peti mel ujian dan pelayar tersedia.
- Ketua ujian dan penyemak teknikal telah dilantik.

### Kriteria keluar

Binaan dicadangkan untuk serahan UAT apabila semua syarat berikut dipenuhi:

- REG-01 lulus pada binaan yang sama.
- Kes hujung ke hujung F-E2E-01, F-E2E-02 dan F-E2E-03 lulus.
- Semua kes berkeutamaan **Kritikal** lulus.
- Sekurang-kurangnya 95% kes berkeutamaan **Tinggi** lulus.
- Tiada kecacatan kritikal atau besar yang masih terbuka.
- Kecacatan kecil yang diterima mempunyai tindakan susulan bertulis.
- Senarai semak serahan di Seksyen 21 ditandakan lengkap.
- Borang perakuan ditandatangani oleh ketua ujian kilang dan pelulus serahan.

## 9. Ringkasan kes

| Modul | ID | Bilangan | Keutamaan utama |
| --- | --- | --- | --- |
| Pemasangan | ENV-01 hingga ENV-08 | 8 | Kritikal |
| Konfigurasi | CFG-01 hingga CFG-06 | 6 | Kritikal |
| Regresi automatik | REG-01 hingga REG-02 | 2 | Kritikal |
| Keselamatan kilang | SEC-01 hingga SEC-12 | 12 | Kritikal |
| Data dan migrasi | DATA-01 hingga DATA-04 | 4 | Tinggi |
| Storan fail | FILE-01 hingga FILE-04 | 4 | Kritikal |
| Pemakluman | MAIL-01 hingga MAIL-10 | 10 | Tinggi |
| Jadual peringatan | JOB-01 hingga JOB-04 | 4 | Tinggi |
| Sandaran dan pulihan | BAK-01 hingga BAK-03 | 3 | Tinggi |
| Halaman ralat | ERR-01 hingga ERR-03 | 3 | Tinggi |
| Pengesahan | F-AUTH-01 hingga F-AUTH-07 | 7 | Kritikal |
| Pengesahan dua faktor | F-MFA-01 hingga F-MFA-05 | 5 | Kritikal |
| Profil dan penampilan | F-PROF-01 hingga F-PROF-03 | 3 | Sederhana |
| Pengguna | F-USER-01 hingga F-USER-08 | 8 | Tinggi |
| Papan pemuka | F-DASH-01 hingga F-DASH-04 | 4 | Tinggi |
| Permohonan | F-APP-01 hingga F-APP-06 | 6 | Kritikal |
| Langkah tindakan | F-PROC-01 hingga F-PROC-05 | 5 | Kritikal |
| Dokumen sokongan | F-DOC-01 hingga F-DOC-04 | 4 | Tinggi |
| Semakan Ibu Pejabat | F-HQ-01 hingga F-HQ-04 | 4 | Kritikal |
| Draf dan PUU | F-DRAF-01 hingga F-DRAF-04 | 4 | Kritikal |
| Pengesahan negeri | F-ACK-01 hingga F-ACK-02 | 2 | Kritikal |
| Tandatangan Ibu Pejabat | F-SIGN-01 | 1 | Kritikal |
| Mati setem | F-STEM-01 | 1 | Kritikal |
| Pindah dan lanjutan | F-FOL-01 hingga F-FOL-04 | 4 | Kritikal |
| Tarik semula dan padam | F-WD-01 hingga F-WD-06 | 6 | Tinggi |
| Kontrak sewaan | F-KON-01 hingga F-KON-04 | 4 | Tinggi |
| Laporan | F-LAP-01 hingga F-LAP-05 | 5 | Tinggi |
| Peti masuk | F-NOT-01 hingga F-NOT-03 | 3 | Tinggi |
| Kawalan akses skrin | F-SEC-01 hingga F-SEC-03 | 3 | Kritikal |
| Hujung ke hujung | F-E2E-01 hingga F-E2E-03 | 3 | Kritikal |
| **Jumlah** | | **138** | |

Semakan pelayar di Seksyen 13 ialah bukti tambahan dan tidak dikira dalam 138 kes.

## 10. Skrip ujian teknikal

Lajur status diisi **Lulus**, **Gagal**, **Disekat** atau **Tidak diuji**.

### 10.1 Pemasangan

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| ENV-01 | Kritikal | 1. Sediakan pangkalan data FAT yang kosong. 2. Pasang kebergantungan PHP bagi binaan serahan. 3. Jana `APP_KEY` jika belum ada. 4. Jalankan migrasi. 5. Buka URL akar. | Aplikasi selesai dipasang tanpa ralat migrasi. URL akar membawa pelawat ke halaman log masuk. Nama SPEKTRA dipaparkan. | | | | |
| ENV-02 | Tinggi | 1. Jalankan `php -v`. 2. Semak sambungan `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `bcmath` dan pemacu pangkalan data yang digunakan. | PHP sekurang-kurangnya 8.2. Semua sambungan yang disemak tersedia. Versi direkod. | | | | |
| ENV-03 | Kritikal | 1. Jalankan `php artisan migrate:status`. | Tiada migrasi berstatus belum dijalankan. | | | | |
| ENV-04 | Tinggi | 1. Pada pangkalan FAT yang masih kosong, jalankan `php artisan db:seed --force`. 2. Buka senarai pengguna sebagai Ibu Pejabat. 3. Buka borang permohonan sebagai satu Negeri dan lihat senarai daerah. | Akaun Ibu Pejabat benih wujud. Sekurang-kurangnya satu pentadbir wujud bagi setiap negeri dalam senarai negeri. Senarai daerah AADK bagi negeri itu tidak kosong. Kata laluan tidak ditulis dalam dokumen ini. | | | | |
| ENV-05 | Kritikal | 1. Jalankan `npm ci` dan `npm run build`. 2. Buka log masuk dan Papan Pemuka. 3. Semak log pelayar. | Aset hadapan terbina. Halaman tidak memaparkan ralat Vite dan tidak memuatkan `cdn.tailwindcss.com`. | | | | |
| ENV-06 | Kritikal | 1. Muat naik satu dokumen sokongan pada permohonan ujian. 2. Cari fail dalam `storage/app/private`. 3. Cuba buka laluan yang sama melalui URL awam `/storage`. | Fail wujud di bawah storan peribadi. URL awam tidak memuat turun fail itu. | | | | |
| ENV-07 | Tinggi | 1. Jalankan `php artisan schedule:list`. | Arahan `contracts:send-expiry-reminders` tersenarai setiap hari pada 09:00, zon Asia/Kuala_Lumpur. | | | | |
| ENV-08 | Kritikal | 1. Pastikan `APP_DEBUG=false`. 2. Buka satu URL yang tidak wujud. | Halaman ralat tidak memaparkan jejak kod, pertanyaan SQL atau nilai konfigurasi. | | | | |

### 10.2 Konfigurasi

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| CFG-01 | Kritikal | 1. Sahkan `APP_KEY` diisi. 2. Sahkan `SESSION_ENCRYPT=true` dan `SESSION_DRIVER=database`. 3. Log masuk, kemudian semak jadual sesi. | Sesi baharu wujud dalam pangkalan data. Nilai sesi yang disimpan tidak memaparkan kata laluan dalam teks jelas. | | | | |
| CFG-02 | Tinggi | 1. Pada respons log masuk berjaya, semak kuki sesi. | Hayat sesi dikonfigurasi 120 minit. `SameSite` ialah Lax. Pada HTTPS, bendera Secure ada. | | | | |
| CFG-03 | Kritikal | 1. Baca `APP_ENV` dan `AUTH_MFA_REQUIRED`. 2. Log masuk akaun yang belum mengesahkan dua faktor. | Jika persekitaran production dan pemboleh ubah tidak ditetapkan, pengesahan dua faktor wajib. Jika pemboleh ubah ditetapkan, kelakuan mengikut nilai benar atau palsu itu. Keputusan dan nilai tetapan direkod. | | | | |
| CFG-04 | Kritikal | 1. Sahkan `MAIL_FROM_NAME` menggunakan nama aplikasi. 2. Sahkan sama ada `MAIL_TEST_RECIPIENT` diisi. 3. Pada simulasi pengeluaran, sahkan mailer bukan `log`. | Penghantar emel dikenali sebagai SPEKTRA. Pada production, set semula kata laluan tidak dihalang oleh mailer log. Penerima ujian, jika diisi, direkod tanpa menyalin kata laluan. | | | | |
| CFG-05 | Tinggi | 1. Baca `upload_max_filesize` dan `post_max_size`. | Kedua-duanya sekurang-kurangnya 40 MB supaya kes draf 40 MB boleh diuji. Jika had pelayan lebih rendah, had berkesan direkod dan kes saiz penuh ditanda Disekat dengan sebab. | | | | |
| CFG-06 | Tinggi | 1. Sahkan jadual `jobs`, `cache` dan `sessions` wujud selepas migrasi. | Tiga jadual wujud dan aplikasi boleh log masuk. | | | | |

### 10.3 Regresi automatik

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| REG-01 | Kritikal | 1. Pada salinan binaan yang sama, jalankan `php artisan test --compact`. 2. Simpan ringkasan output. | Semua ujian lulus. Bilangan ujian dan tarikh larian direkod. Kegagalan membuka kecacatan. | | | | |
| REG-02 | Sederhana | 1. Jalankan `vendor/bin/pint --test`. | Tiada fail yang perlu diformat semula. | | | | |

### 10.4 Keselamatan kilang

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| SEC-01 | Kritikal | 1. Minta header respons halaman log masuk. | Respons mengandungi `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy` yang menutup kamera, mikrofon dan geolokasi, serta `Content-Security-Policy`. Polisi skrip mengandungi nonce dan tidak mengandungi `unsafe-eval`. | | | | |
| SEC-02 | Kritikal | 1. Hantar borang log masuk tanpa token borang. | Permintaan ditolak. Sesi kerja tidak terbuka. | | | | |
| SEC-03 | Kritikal | 1. Tanpa log masuk, minta Papan Pemuka, Status Permohonan, Kontrak Sewaan, Pengguna dan Laporan. | Setiap permintaan membawa pelawat ke log masuk. Badan respons tidak mengandungi data permohonan. | | | | |
| SEC-04 | Tinggi | 1. Hantar log masuk gagal bagi emel dan alamat IP yang sama lebih daripada 10 kali dalam satu minit. | Permintaan selepas had ditolak. Selepas tempoh rehat, akaun yang sah masih boleh masuk. | | | | |
| SEC-05 | Tinggi | 1. Hantar lupa kata laluan bagi emel yang sama lebih daripada 5 kali dalam satu minit. | Permintaan selepas had ditolak. Mesej biasa tidak mendedahkan sama ada emel wujud. | | | | |
| SEC-06 | Tinggi | 1. Pada cabaran dua faktor, hantar kod salah lebih daripada 5 kali dalam satu minit. | Permintaan selepas had ditolak. Sesi kerja tidak terbuka. | | | | |
| SEC-07 | Kritikal | 1. Cuba log masuk dengan kata laluan salah. 2. Baca log amaran `login_failed`. | Log mengandungi emel dan tidak mengandungi kata laluan. | | | | |
| SEC-08 | Kritikal | 1. Daftar pengguna dengan kata laluan `password`, kata laluan tanpa huruf besar, tanpa nombor, atau kurang daripada 8 aksara. 2. Pada production, cuba satu kata laluan yang diketahui tercemar. | Setiap percubaan ditolak. Akaun tidak tercipta dengan kata laluan lemah. | | | | |
| SEC-09 | Kritikal | 1. Sebagai Negeri B, minta URL semakan, dokumen dan butiran kontrak Negeri A. | Setiap permintaan ditolak atau tidak dijumpai. Butiran Negeri A tidak dipaparkan. | | | | |
| SEC-10 | Kritikal | 1. Nyahaktifkan akaun Negeri. 2. Cuba log masuk. 3. Aktifkan semula selepas bukti diambil. | Akaun nyahaktif tidak mendapat sesi kerja. | | | | |
| SEC-11 | Tinggi | 1. Sebagai Ibu Pejabat, cuba nyahaktifkan akaun yang sedang digunakan sendiri. | Permintaan ditolak. Akaun sendiri kekal aktif. | | | | |
| SEC-12 | Kritikal | 1. Sebelum serahan keluar dari kilang, ganti kata laluan semua akaun benih yang akan wujud pada persekitaran UAT. | Tiada akaun serahan yang masih menggunakan kata laluan benih pembangunan. Kata laluan baharu memenuhi dasar Seksyen 6. | | | | |

### 10.5 Data dan migrasi

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| DATA-01 | Tinggi | 1. Selepas migrasi, sahkan jadual pengguna, premis, kontrak sewaan, dokumen kontrak, sesi, kerja giliran, cache, jejak audit dan daerah AADK wujud. | Jadual domain yang disemak wujud. Nama fizikal jadual direkod. | | | | |
| DATA-02 | Tinggi | 1. Sebagai Negeri A, buka senarai daerah. 2. Hantar permohonan dengan daerah negeri lain. | Daerah negeri lain ditolak dengan mesej daerah tidak sah bagi negeri yang dipilih. | | | | |
| DATA-03 | Kritikal | 1. Cipta satu permohonan Negeri A dan satu kontrak atau permohonan Negeri B. 2. Bandingkan kiraan papan pemuka, senarai permohonan dan senarai kontrak kedua-dua akaun. | Kiraan dan senarai tidak bercampur. | | | | |
| DATA-04 | Sederhana | 1. Catat cara pangkalan FAT dibersihkan selepas ujian, sama ada pangkalan diganti atau data ujian ditanda. | Kaedah pembersihan direkod. Skema migrasi kekal lengkap selepas pembersihan. | | | | |

### 10.6 Storan fail

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| FILE-01 | Tinggi | 1. Muat naik PDF, JPG dan PNG sebagai dokumen sokongan, setiap satu di bawah 10 MB. 2. Cuba DOC atau EXE, fail melebihi 10 MB, dan borang tanpa fail. | Tiga jenis yang sah tersimpan dan boleh dibuka oleh pengguna berkuasa. Tiga percubaan tidak sah ditolak dengan mesej jenis atau had saiz. | | | | |
| FILE-02 | Kritikal | 1. Pada penyediaan draf, muat naik PDF di bawah 40 MB. 2. Cuba PDF melebihi 40 MB, JPG dan borang tanpa fail. | PDF yang sah tersimpan di storan peribadi dan status menjadi Semakan PUU. Fail besar, bukan PDF, atau kosong ditolak. | | | | |
| FILE-03 | Kritikal | 1. Pada Semakan PUU, tekan Luluskan tanpa fail, dengan fail bukan PDF, dan dengan PDF melebihi 40 MB. 2. Ulang untuk Semak Semula. 3. Ulang dengan satu PDF yang sah bagi setiap tindakan pada permohonan yang berasingan. | Tindakan tanpa PDF yang sah ditolak dan status tidak berubah. PDF yang sah menerima tindakan dan sejarah memaparkan dokumen itu. | | | | |
| FILE-04 | Kritikal | 1. Salin URL muat turun dokumen yang sah. 2. Buka URL itu tanpa sesi, kemudian sebagai Negeri yang tidak memiliki kontrak. | Muat turun tanpa kuasa ditolak. Fail tidak dihantar. | | | | |

### 10.7 Pemakluman

Jalankan kes ini dengan peti ujian atau log mel. Jika `MAIL_TEST_RECIPIENT` diisi, semua penerima aliran kerja di bawah menjadi alamat itu. Rekod subjek sebenar.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| MAIL-01 | Kritikal | 1. Negeri menekan Hantar ke Ibu Pejabat dan mengesahkan Ya, Hantar. | Emel *SPEKTRA: Permohonan Baharu Menunggu Semakan Ibu Pejabat* sampai kepada Ibu Pejabat. Emel *SPEKTRA: Permohonan Dihantar ke Ibu Pejabat* sampai kepada pentadbir negeri permohonan. | | | | |
| MAIL-02 | Tinggi | 1. Ibu Pejabat mengesahkan permohonan ke penyediaan draf. | Emel *SPEKTRA: Permohonan Disahkan oleh Ibu Pejabat* sampai kepada negeri dan Ibu Pejabat. | | | | |
| MAIL-03 | Tinggi | 1. Negeri memuat naik draf PDF yang sah. | Emel *SPEKTRA: Draf Perjanjian Dihantar untuk Semakan PUU* dihantar. Badan emel menyebut label pusingan, contoh Semakan PUU 1. | | | | |
| MAIL-04 | Tinggi | 1. Ibu Pejabat meluluskan semakan PUU dengan PDF yang sah. | Emel *SPEKTRA: Draf Perjanjian Diluluskan* dihantar. | | | | |
| MAIL-05 | Tinggi | 1. Pada pusingan lain, Ibu Pejabat menekan Semak Semula dengan PDF yang sah. | Emel *SPEKTRA: Semakan PUU Memerlukan Pindaan* dihantar. | | | | |
| MAIL-06 | Tinggi | 1. Negeri mengembalikan dokumen selepas empat pengakuan lengkap. | Emel *SPEKTRA: Dokumen Dikembalikan untuk Pengesahan & Tandatangan* dihantar. | | | | |
| MAIL-07 | Tinggi | 1. Ibu Pejabat menghantar perjanjian untuk mati setem selepas empat pengakuan lengkap. | Emel *SPEKTRA: Perjanjian Dihantar untuk Mati Setem* dihantar. | | | | |
| MAIL-08 | Tinggi | 1. Negeri menyelesaikan mati setem selepas tiga pengakuan lengkap. | Emel *SPEKTRA: Permohonan Selesai — Dalam Senarai Kontrak Sewaan* dihantar. | | | | |
| MAIL-09 | Tinggi | 1. Minta set semula kata laluan untuk akaun yang wujud. 2. Buka pautan dan tetapkan kata laluan baharu. | Emel *SPEKTRA: Set Semula Kata Laluan* dihantar kepada pemilik akaun. Pautan membuka borang set semula. Pada production dengan mailer log, permintaan disekat dan kes ini hanya lulus jika mailer sebenar digunakan. | | | | |
| MAIL-10 | Tinggi | 1. Pada satu salinan ujian, jadikan konfigurasi mel tidak sah. 2. Jalankan satu tindakan yang biasa menghantar emel, contoh hantar ke Ibu Pejabat. 3. Pulihkan konfigurasi mel. | Rekod permohonan kekal dan statusnya berubah mengikut tindakan. Kegagalan emel direkod dalam log. Konfigurasi dipulihkan sebelum kes seterusnya. | | | | |

### 10.8 Jadual peringatan

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| JOB-01 | Tinggi | 1. Sediakan kontrak aktif berbaki bawah 8 bulan hingga 5 bulan. 2. Jalankan `php artisan contracts:send-expiry-reminders` pada tiga hari yang berturutan, atau gunakan tarikh ujian yang menggerakkan penghantaran. 3. Jalankan sekali lagi pada hari yang sama. | Subjek *SPEKTRA: Peringatan Kontrak Bawah 8 Bulan* dihantar kepada pentadbir negeri. Penghantaran berhenti selepas 3 kali. Ulangan pada hari yang sama tidak menambah penghantaran di luar peraturan. | | | | |
| JOB-02 | Tinggi | 1. Sediakan kontrak berbaki bawah 5 bulan hingga 3 bulan. 2. Jalankan arahan pada hari Isnin dan pada satu hari yang bukan Isnin. | Subjek *SPEKTRA: Peringatan Tegas Kontrak Bawah 5 Bulan* dihantar pada Isnin kepada pentadbir negeri. Hari lain tidak menghantar bagi julat ini. | | | | |
| JOB-03 | Kritikal | 1. Sediakan kontrak berbaki 3 bulan atau kurang. 2. Jalankan arahan pada Isnin atau Rabu. 3. Hantar permohonan lanjutan atau pindah ke Ibu Pejabat. 4. Jalankan arahan sekali lagi pada hari jadual berikutnya. | Subjek *SPEKTRA: AMARAN Kontrak Baki 3 Bulan* dihantar kepada pentadbir negeri dengan salinan Ibu Pejabat. Selepas permohonan dihantar ke Ibu Pejabat, peringatan kontrak itu berhenti. | | | | |
| JOB-04 | Tinggi | 1. Pada pelayan FAT, sahkan penjadual sistem memanggil `php artisan schedule:run` setiap minit, atau rekod bahawa penjadual belum dipasang. | Jika FAT mensimulasikan pelayan serahan, penjadual terpasang dan zon masa 09:00 dibuktikan melalui `schedule:list`. Jika penjadual belum dipasang, kes ditanda Gagal untuk serahan pelayan atau Disekat dengan tindakan susulan bertulis. | | | | |

### 10.9 Sandaran dan pulihan

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| BAK-01 | Tinggi | 1. Ambil salinan pangkalan data FAT selepas sekurang-kurangnya satu permohonan dan satu dokumen wujud. 2. Simpan salinan di lokasi ujian yang bukan folder awam. | Fail sandaran terhasil dan saiznya lebih daripada kosong. Laluan sandaran direkod. Fail tidak diletakkan di bawah `public`. | | | | |
| BAK-02 | Tinggi | 1. Salin `storage/app/private` bagi dokumen ujian ke lokasi sandaran yang sama. | Salinan fail peribadi lengkap. Nama fail storan direkod tanpa mengedarkan kandungan premis. | | | | |
| BAK-03 | Kritikal | 1. Pulihkan sandaran pangkalan data dan fail ke satu pangkalan atau folder kosong yang lain. 2. Arahkan satu salinan aplikasi ke pulihan itu. 3. Log masuk dan buka permohonan serta dokumen yang disandarkan. | Log masuk berjaya. Permohonan dan dokumen yang disandarkan boleh dibuka oleh peranan yang berkuasa. | | | | |

### 10.10 Halaman ralat

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| ERR-01 | Tinggi | 1. Buka URL yang tidak wujud, dengan dan tanpa sesi. | Halaman tidak dijumpai dipaparkan. Jejak kod tidak dipaparkan. | | | | |
| ERR-02 | Kritikal | 1. Sebagai Negeri B, buka URL semakan Negeri A. | Akses ditolak atau halaman tidak dijumpai. Nama premis, pemilik dan dokumen Negeri A tidak kelihatan. | | | | |
| ERR-03 | Tinggi | 1. Buka borang yang sudah dimuat. 2. Biarkan sesi tamat atau buang kuki sesi. 3. Hantar borang. | Sistem tidak menyimpan tindakan. Pengguna boleh kembali ke log masuk dan meneruskan selepas masuk semula. | | | | |

## 11. Skrip ujian fungsi

### 11.1 Pengesahan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-AUTH-01 | Kritikal | Semua | 1. Buka URL sistem. 2. Masukkan emel dan kata laluan akaun aktif. 3. Hantar. | Halaman log masuk dipaparkan untuk pelawat. Log masuk berjaya membuka Papan Pemuka. Nama pengguna kelihatan. | | | | |
| F-AUTH-02 | Kritikal | Semua | 1. Masukkan emel yang sah dan kata laluan yang salah. 2. Hantar. | Log masuk ditolak. Mesej ralat dipaparkan. Papan pemuka tidak terbuka. | | | | |
| F-AUTH-03 | Kritikal | Ibu Pejabat, kemudian akaun yang dinyahaktif | 1. Ibu Pejabat nyahaktifkan akaun Negeri. 2. Cuba log masuk dengan akaun itu. | Akaun berstatus Nyahaktif tidak boleh masuk. | | | | |
| F-AUTH-04 | Tinggi | Semua | 1. Log masuk. 2. Pilih log keluar. 3. Tekan butang undur pelayar dan cuba buka Papan Pemuka. | Sesi ditutup. Halaman dalaman tidak boleh digunakan selepas log keluar. Rekod sesi itu tidak lagi sah. | | | | |
| F-AUTH-05 | Tinggi | Semua | 1. Pada log masuk, pilih lupa kata laluan. 2. Hantar emel akaun yang wujud dan emel yang tidak wujud. 3. Buka pautan set semula. 4. Tetapkan kata laluan baharu yang sah dan log masuk. | Kedua-dua permintaan memaparkan mesej yang tidak mendedahkan sama ada emel wujud. Pautan akaun yang wujud membuka borang set semula. Kata laluan baharu boleh digunakan. Kata laluan lama ditolak. | | | | |
| F-AUTH-06 | Tinggi | Semua | 1. Pada set semula, cuba kata laluan tanpa huruf besar, tanpa nombor, atau kurang daripada 8 aksara. 2. Cuba dua nilai yang tidak sama. | Setiap percubaan ditolak. Kata laluan tidak berubah. | | | | |
| F-AUTH-07 | Sederhana | Semua | 1. Cuba log masuk gagal berulang kali dalam satu minit. | Selepas 10 percubaan bagi emel dan IP yang sama, sistem menolak percubaan seterusnya. Akaun yang sah masih boleh masuk selepas had berehat. | | | | |

### 11.2 Pengesahan dua faktor

Jalankan modul ini apabila pengesahan dua faktor dihidupkan. Jika ia dimatikan, tandakan kes sebagai **Tidak diuji** dan catat rujukan tetapan.

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-MFA-01 | Kritikal | Pengguna baharu | 1. Log masuk akaun yang belum mengesahkan dua faktor. | Sistem membawa pengguna ke persediaan pengesahan. Menu kerja tidak boleh digunakan sebelum pengesahan selesai. | | | | |
| F-MFA-02 | Kritikal | Semua | 1. Imbas atau masukkan kunci ke aplikasi pengesah. 2. Simpan lapan kod pemulihan di tempat selamat ujian. 3. Masukkan kod 6 digit semasa. | Kod yang sah mengesahkan akaun. Kod pemulihan dipaparkan sekali. Log masuk seterusnya meminta kod pengesah. | | | | |
| F-MFA-03 | Tinggi | Semua | 1. Pada cabaran log masuk, masukkan kod yang salah. | Mesej kod tidak sah dipaparkan. Sesi kerja tidak terbuka. | | | | |
| F-MFA-04 | Kritikal | Semua | 1. Pada cabaran log masuk, gunakan satu kod pemulihan yang disimpan. | Kod pemulihan yang sah membuka sesi. Kod itu tidak lagi boleh digunakan. | | | | |
| F-MFA-05 | Tinggi | Semua | 1. Guna semula kod pemulihan yang sama. | Kod yang telah digunakan ditolak. | | | | |

### 11.3 Profil dan penampilan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-PROF-01 | Sederhana | Semua | 1. Buka Profil. 2. Tukar nama. 3. Simpan. | Nama baharu tersimpan dan dipaparkan selepas muat semula. | | | | |
| F-PROF-02 | Tinggi | Semua | 1. Cuba tukar emel atau kata laluan tanpa kata laluan semasa. 2. Ulang dengan kata laluan semasa yang betul dan kata laluan baharu yang sah. | Perubahan emel atau kata laluan ditolak tanpa kata laluan semasa. Perubahan berjaya apabila kata laluan semasa sah. | | | | |
| F-PROF-03 | Sederhana | Semua | 1. Buka Penampilan. 2. Tukar pilihan paparan yang disediakan. 3. Beralih ke halaman lain dan kembali. | Pilihan kekal pada pelayar yang sama selepas navigasi. | | | | |

### 11.4 Pengurusan pengguna

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-USER-01 | Tinggi | Ibu Pejabat | 1. Buka Pendaftaran Pengguna. 2. Daftar satu pengguna Negeri bagi negeri yang dipilih, dengan kata laluan sah. 3. Daftar satu pengguna Ibu Pejabat. | Kedua-dua akaun wujud dalam Senarai Pengguna. Pengguna Negeri mempunyai negeri. Pengguna Ibu Pejabat tidak diikat pada satu negeri. Akaun baharu boleh log masuk. | | | | |
| F-USER-02 | Kritikal | Negeri | 1. Log masuk sebagai Negeri A. 2. Buka pendaftaran. 3. Cuba daftar pengguna. | Peranan yang dibenarkan ialah Negeri sahaja. Negeri dikunci kepada negeri pentadbir. Pengguna negeri lain atau Ibu Pejabat tidak boleh didaftarkan. | | | | |
| F-USER-03 | Tinggi | Ibu Pejabat | 1. Daftar pengguna dengan emel yang sudah wujud. | Pendaftaran ditolak. Mesej menyatakan emel telah didaftarkan. | | | | |
| F-USER-04 | Kritikal | Ibu Pejabat | 1. Pada Senarai Pengguna, nyahaktifkan pengguna Negeri. 2. Aktifkan semula. | Status bertukar antara Aktif dan Nyahaktif. Hanya Ibu Pejabat melihat tindakan ini. | | | | |
| F-USER-05 | Tinggi | Ibu Pejabat | 1. Cuba nyahaktifkan akaun yang sedang digunakan sendiri. | Sistem menolak. Akaun sendiri kekal aktif. | | | | |
| F-USER-06 | Tinggi | Ibu Pejabat dan Negeri | 1. Buat satu tindakan yang direkod, contoh kemaskini profil. 2. Buka jejak audit pengguna itu. | Jejak memaparkan tindakan yang berkaitan pengguna. Negeri tidak boleh membuka jejak pengguna negeri lain. | | | | |
| F-USER-07 | Tinggi | Ibu Pejabat | 1. Buka Senarai Pengguna. 2. Kemaskini emel jawatan Ketua Pengarah, TKPP, PKP dan KPP kepada peti mel ujian. 3. Simpan. 4. Muat semula. | Empat jawatan tersimpan. Emel kosong atau format tidak sah ditolak. | | | | |
| F-USER-08 | Kritikal | Negeri | 1. Sebagai Negeri A, buka Senarai Pengguna. | Senarai hanya mengandungi pengguna Negeri bagi negeri sendiri. Pengguna Ibu Pejabat dan negeri lain tidak dipaparkan. | | | | |

### 11.5 Papan pemuka

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-DASH-01 | Tinggi | Ibu Pejabat | 1. Buka Papan Pemuka. 2. Bandingkan kiraan permohonan, kontrak aktif dan pecahan status dengan senarai berkaitan. 3. Klik satu baris Status Tindakan. | Kad dan pecahan status dipaparkan untuk semua negeri. Angka sepadan dengan senarai selepas penapis yang sama. Klik baris membuka senarai projek pada status itu. | | | | |
| F-DASH-02 | Kritikal | Negeri | 1. Buka Papan Pemuka sebagai Negeri A. 2. Ulang sebagai Negeri B. | Setiap papan pemuka hanya mengira premis negeri sendiri. | | | | |
| F-DASH-03 | Tinggi | Semua pentadbir | 1. Semak senarai status tindakan. 2. Klik setiap baris yang mempunyai data. | Label berikut dipaparkan: Belum Terima Permohonan, Semakan Cawangan Pembangunan Sebelum ke BPH, Minit Ceraian Kelulusan Kos, Pertimbangan MOF, Pertimbangan KE, Pertimbangan BPH/SUPS, Sedia Draf Perjanjian, Semakan Cawangan Pembangunan Sebelum ke PUU, Semakan PUU, Kuiri PUU, Pindaan Berdasarkan Syor PUU dan Tandatangan Pemilik Premis, Tandatangan Penyewa (AADK), Tindakan Mati Setem. | | | | |
| F-DASH-04 | Tinggi | Semua pentadbir | 1. Semak bahagian kontrak yang akan tamat. | Kontrak aktif dibahagi kepada baki 3 bulan atau kurang, baki bawah 8 bulan tetapi lebih 3 bulan, dan baki lebih 8 bulan. Senarai amaran paling hampir tamat dipaparkan dahulu. | | | | |

### 11.6 Permohonan baharu

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-APP-01 | Kritikal | Negeri | 1. Buka Permohonan Baharu. 2. Pilih kategori Baru. 3. Isi nama premis, alamat, daerah negeri sendiri, pemilik, jenis bangunan, kadar sewa, keluasan, tarikh mula dan tarikh akhir tawaran. 4. Simpan. | Rekod tercipta di Status Permohonan. Negeri pada rekod sama dengan negeri pentadbir. Daerah tersimpan. Jenis bangunan yang sah ialah Kompleks kerajaan, Komersial atau Rumah Kediaman. | | | | |
| F-APP-02 | Tinggi | Negeri | 1. Hantar borang kosong. 2. Isi tarikh akhir lebih awal daripada tarikh mula. 3. Isi kadar sewa negatif. | Setiap kes dipaparkan mesej pengesahan. Rekod tidak rosak. | | | | |
| F-APP-03 | Kritikal | Negeri | 1. Cuba hantar nilai negeri lain. | Permohonan untuk negeri lain ditolak. | | | | |
| F-APP-04 | Sederhana | Negeri | 1. Buka permohonan yang belum dihantar. 2. Ubah satu medan dan tunggu autosimpan. 3. Muat semula halaman. | Nilai terakhir yang sah kekal. | | | | |
| F-APP-05 | Tinggi | Ibu Pejabat | 1. Cari menu Permohonan Baharu. 2. Cuba buka URL borang permohonan secara terus. | Menu cipta permohonan tidak dipaparkan. Akses terus ditolak. | | | | |
| F-APP-06 | Kritikal | Negeri | 1. Pilih daerah yang bukan daerah AADK bagi negeri pentadbir, jika kawalan membenarkan, atau hantar nilai daerah negeri lain. 2. Kosongkan daerah. | Kedua-dua percubaan ditolak. Mesej meminta daerah atau menyatakan daerah tidak sah bagi negeri yang dipilih. | | | | |

### 11.7 Langkah tindakan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-PROC-01 | Kritikal | Negeri | 1. Buka permohonan kategori Baru. 2. Pada langkah awal, tekan Simpan dan pastikan langkah tidak beralih. 3. Tekan Seterusnya. 4. Pada Surat kepada agensi, pastikan hanya Simpan dipaparkan. 5. Lengkapkan Surat niat, Borang JRP dan Surat kepada agensi. | Hanya langkah 2, 3 dan 5 dipaparkan. Simpan ada pada setiap langkah. Seterusnya disembunyikan pada langkah terakhir. Butang Hantar ke Ibu Pejabat boleh digunakan selepas ketiga-tiga langkah selesai. | | | | |
| F-PROC-02 | Kritikal | Negeri | 1. Buka permohonan kategori Lanjutan. 2. Semak langkah yang dipaparkan. | Hanya Notis kepada pemilik premis, Borang JRP dan Surat kepada agensi dipaparkan. | | | | |
| F-PROC-03 | Kritikal | Negeri | 1. Pada permohonan yang langkahnya belum lengkap, tekan Hantar ke Ibu Pejabat dan sahkan Ya, Hantar. | Penghantaran ditolak. Status kekal menunggu langkah tindakan. | | | | |
| F-PROC-04 | Tinggi | Negeri | 1. Pada Surat kepada agensi, tanda surat JPPH tanpa tarikh. 2. Tanda Pelan Lantai dan Gambar Bangunan Terkini tanpa tarikh. 3. Lengkapkan tarikh bagi enam item yang memerlukannya. | Item bertarikh tidak boleh disimpan sebagai lengkap tanpa tarikh. Pelan lantai dan gambar bangunan tidak memerlukan tarikh. | | | | |
| F-PROC-05 | Kritikal | Negeri | 1. Pada permohonan pindah atau lanjutan yang langkahnya sudah lengkap, kosongkan catatan. 2. Cuba hantar ke Ibu Pejabat. 3. Isi catatan dan hantar semula. | Penghantaran ditolak selagi catatan kosong. Selepas catatan diisi dan Ya, Hantar disahkan, status senarai menjadi Permohonan kepada MOF. | | | | |

### 11.8 Dokumen sokongan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-DOC-01 | Tinggi | Negeri | 1. Muat naik satu PDF sebagai surat tawaran, satu JPG sebagai gambar premis, dan satu PNG sebagai lain-lain. 2. Buka semula setiap fail. | Tiga fail tersimpan di bawah kontrak yang betul dan boleh dibuka oleh pengguna yang berkuasa. | | | | |
| F-DOC-02 | Tinggi | Negeri | 1. Cuba muat naik fail DOC atau EXE. 2. Cuba fail yang melebihi had saiz. 3. Hantar tanpa memilih fail. | Setiap percubaan ditolak. Mesej menyebut jenis yang dibenarkan atau had saiz. | | | | |
| F-DOC-03 | Tinggi | Ibu Pejabat | 1. Buka permohonan yang mempunyai dokumen. 2. Muat turun dokumen. | Ibu Pejabat boleh membaca dokumen permohonan yang dihantar. | | | | |
| F-DOC-04 | Kritikal | Negeri | 1. Sebagai Negeri B, cuba buka pautan dokumen Negeri A. | Akses ditolak. Fail tidak dimuat turun. | | | | |

### 11.9 Semakan Ibu Pejabat

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-HQ-01 | Kritikal | Ibu Pejabat | 1. Buka permohonan yang menunggu semakan. 2. Semak status senarai bermula Permohonan kepada MOF, kemudian Permohonan kepada KE, kemudian Pertimbangan BPH, mengikut item yang belum ditanda. 3. Tanda senarai semak bersama tarikh. 4. Isi catatan pada MOF dan KE selepas item ditanda. 5. Sahkan permohonan. | Butiran negeri dipaparkan sebagai baca sahaja. Status senarai bertukar mengikut item yang masih terbuka. Selepas sah, status menjadi penyediaan draf perjanjian. Pemakluman MAIL-02 wujud. | | | | |
| F-HQ-02 | Tinggi | Ibu Pejabat | 1. Buka permohonan baharu berkeluasan 465 meter persegi atau kurang yang senarai semaknya belum disimpan. 2. Ulang dengan permohonan melebihi 465 meter persegi. | Item KE dipaparkan pada kedua-dua permohonan. Pada permohonan melebihi 465 meter persegi, item KE ditanda awal. Kelulusan Pengurusan Tertinggi ditanda awal untuk kategori Baru. | | | | |
| F-HQ-03 | Tinggi | Negeri | 1. Semasa status masih menunggu semakan Ibu Pejabat, cuba sahkan permohonan. | Tindakan kelulusan Ibu Pejabat tidak tersedia kepada Negeri. | | | | |
| F-HQ-04 | Sederhana | Ibu Pejabat | 1. Tanda sebahagian senarai semak. 2. Tunggu autosimpan. 3. Keluar dan buka semula. | Tanda, tarikh dan catatan yang telah disimpan kekal. | | | | |

### 11.10 Draf perjanjian dan semakan PUU

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-DRAF-01 | Kritikal | Negeri | 1. Pada peringkat Penyediaan Draf, muat naik PDF draf perjanjian di bawah 40 MB. 2. Cuba fail lebih 40 MB dan fail bukan PDF. | Muat naik PDF yang sah berjaya. Status menjadi Semakan PUU. Fail boleh dibuka dari storan peribadi. Fail melebihi 40 MB atau bukan PDF ditolak. | | | | |
| F-DRAF-02 | Kritikal | Ibu Pejabat | 1. Buka permohonan berstatus Semakan PUU. 2. Tekan Luluskan. 3. Muat naik PDF di bawah 40 MB dan sahkan Ya, Luluskan. 4. Pada permohonan lain, cuba tanpa fail, fail bukan PDF, atau fail melebihi 40 MB. | Luluskan tanpa PDF yang sah ditolak. Selepas berjaya, status menjadi Draf Perjanjian dikembalikan ke Pentadbir Negeri untuk dapatkan Tandatangan Pemilik Premis. Sejarah memaparkan dokumen lulus PUU. | | | | |
| F-DRAF-03 | Kritikal | Ibu Pejabat, kemudian Negeri | 1. Pada pusingan semakan yang lain, tekan Semak Semula. 2. Muat naik PDF pindaan di bawah 40 MB dan sahkan Ya, Semak Semula. 3. Cuba tanpa fail, fail bukan PDF, atau fail melebihi 40 MB. 4. Negeri memuat turun dokumen pindaan, kemudian muat naik PDF baharu. | Semak Semula tanpa PDF yang sah ditolak. Selepas berjaya, status menjadi Kuiri PUU. Sejarah memaparkan dokumen pindaan. Muat naik baharu menjadi Semakan PUU seterusnya, bukan Semakan PUU 1 semula. | | | | |
| F-DRAF-04 | Tinggi | Negeri | 1. Pada penyediaan draf, cuba muat naik JPG atau DOCX. | Hanya PDF diterima untuk draf perjanjian. | | | | |

### 11.11 Pengesahan negeri selepas draf lulus

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-ACK-01 | Kritikal | Negeri | 1. Pada Draf Lulus, tanda kurang daripada empat pengakuan. 2. Cuba kembalikan kepada Ibu Pejabat. 3. Tanda keempat-empat pengakuan dalam Seksyen 5 dan tukar salah satu tarikh. | Penghantaran disekat selagi ada pengakuan yang belum ditanda. Tarikh diisi secara automatik apabila ditanda dan tarikh yang ditukar kekal. | | | | |
| F-ACK-02 | Kritikal | Negeri | 1. Selepas empat pengakuan lengkap, kembalikan dokumen. | Status menjadi Dokumen Perjanjian dikembalikan ke Cawangan Pembangunan untuk tindakan Tandatangan Penyewa. Tindakan ini tidak tersedia kepada Ibu Pejabat. | | | | |

### 11.12 Tandatangan Ibu Pejabat

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-SIGN-01 | Kritikal | Ibu Pejabat | 1. Buka permohonan yang dikembalikan negeri. 2. Cuba hantar untuk mati setem sebelum empat pengakuan lengkap. 3. Tanda empat pengakuan dalam Seksyen 5, semak tarikh di sebelah kanan, dan sahkan. | Penghantaran disekat sehingga empat pengakuan lengkap. Tarikh diisi dengan tarikh tanda dan boleh diubah. Selepas sah, status menjadi Menunggu Mati Setem. | | | | |

### 11.13 Mati setem

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-STEM-01 | Kritikal | Negeri | 1. Pada Mati Setem, cuba selesaikan dengan kurang daripada tiga pengakuan. 2. Tanda ketiga-tiga pengakuan dalam Seksyen 5, semak tarikh, dan tekan Selesai. 3. Buka Senarai Kontrak Sewaan. | Pengakuan yang tidak lengkap tidak menutup permohonan. Selepas ketiga-tiganya ditanda, permohonan hilang daripada senarai kerja aktif dan muncul sebagai kontrak sewaan. Mesej kejayaan dipaparkan. | | | | |

### 11.14 Pindah dan lanjutan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-FOL-01 | Kritikal | Negeri | 1. Pada kontrak aktif negeri sendiri, cipta permohonan Lanjutan. | Borang susulan terbuka dengan kaitan kepada kontrak asal. Kategori ialah Lanjutan. Langkah yang dipaparkan ialah notis, JRP dan surat agensi. | | | | |
| F-FOL-02 | Kritikal | Negeri | 1. Pada kontrak aktif yang lain, cipta permohonan Pindah. 2. Kemaskini nama premis, alamat, daerah, pemilik, kadar sewa, keluasan dan tarikh kepada nilai baharu. | Kategori ialah Pindah. Butiran baharu boleh disimpan. Langkah yang dipaparkan ialah surat niat, JRP dan surat agensi. | | | | |
| F-FOL-03 | Tinggi | Negeri | 1. Pada permohonan susulan yang masih di langkah tindakan, pilih Batal dan Kembali ke Kontrak Asal. | Permohonan susulan dibatalkan. Kontrak asal kembali ke senarai kontrak. Butang Padam biasa tidak menggantikan tindakan ini. | | | | |
| F-FOL-04 | Kritikal | Ibu Pejabat | 1. Hantar satu permohonan lanjutan atau pindah yang lengkap. 2. Ibu Pejabat sahkan permohonan. 3. Semak kontrak asal. | Kontrak asal ditandakan diganti oleh permohonan baharu. Ia tidak lagi dikira sebagai kontrak aktif semasa. | | | | |

### 11.15 Tarik semula dan padam

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-WD-01 | Tinggi | Negeri | 1. Pada permohonan berstatus menunggu semakan Ibu Pejabat, mohon tarik semula dengan alasan sekurang-kurangnya 10 aksara. | Permohonan ditanda menunggu kelulusan tarik semula. Ibu Pejabat melihat tindakan lulus atau tolak. | | | | |
| F-WD-02 | Sederhana | Negeri | 1. Hantar alasan tarik semula kurang daripada 10 aksara, dan satu alasan melebihi 2,000 aksara. | Kedua-dua permintaan ditolak dan mesej pengesahan dipaparkan. | | | | |
| F-WD-03 | Tinggi | Ibu Pejabat | 1. Luluskan tarik semula. | Permohonan kembali kepada pentadbir negeri untuk dikemaskini. Status semakan Ibu Pejabat berakhir. | | | | |
| F-WD-04 | Tinggi | Ibu Pejabat | 1. Pada permohonan tarik semula yang lain, tolak permintaan. | Permohonan kekal dalam senarai semakan Ibu Pejabat. Status memaparkan permohonan tarik semula ditolak. | | | | |
| F-WD-05 | Tinggi | Negeri | 1. Pada permohonan biasa yang belum dihantar, pilih Padam. 2. Isi alasan sekurang-kurangnya 10 aksara dan sahkan. | Permohonan keluar daripada senarai aktif. Ibu Pejabat boleh melihat tarikh dan alasan pada sejarah padam. | | | | |
| F-WD-06 | Kritikal | Negeri | 1. Cari butang Padam pada permohonan yang sedang disemak Ibu Pejabat dan pada permohonan di peringkat draf perjanjian. | Butang Padam tidak dipaparkan. Permohonan tersebut tidak boleh dipadam. | | | | |

### 11.16 Kontrak sewaan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-KON-01 | Tinggi | Semua pentadbir | 1. Buka Senarai Kontrak Sewaan. 2. Semak tab aktif dan sejarah. | Tab aktif memaparkan kontrak yang masih berkuat kuasa. Sejarah memaparkan kontrak yang telah diganti atau tamat, mengikut data ujian. | | | | |
| F-KON-02 | Sederhana | Semua pentadbir | 1. Cari menggunakan nama premis dan nama negeri. | Hasil carian sepadan. Carian kosong memulangkan senarai asal. | | | | |
| F-KON-03 | Tinggi | Semua pentadbir | 1. Buka butiran satu kontrak. | Butiran premis, daerah, tempoh, kategori dan dokumen yang berkaitan dipaparkan. | | | | |
| F-KON-04 | Kritikal | Negeri | 1. Sebagai Negeri A, cuba buka butiran kontrak Negeri B melalui senarai atau pautan terus. | Kontrak negeri lain tidak tersenarai dan pautan terus ditolak. | | | | |

### 11.17 Laporan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-LAP-01 | Tinggi | Ibu Pejabat | 1. Buka Jana Laporan. 2. Jana setiap jenis: Ringkasan Keseluruhan, Senarai Permohonan, Senarai Kontrak Aktif, Laporan Kontrak mengikut Negeri, dan Laporan Status Sewaan. | Kelima-lima jenis dipaparkan. Laporan status sewaan menggunakan tarikh semasa pada tajuk dan mengumpul rekod mengikut bahagian BPH, PUU, MOF, KE, Ibu Pejabat dan PTJ apabila data wujud. Dalam Tindakan PTJ, baki bulan paling rendah berada di atas. | | | | |
| F-LAP-02 | Tinggi | Ibu Pejabat | 1. Jana laporan yang sama tanpa negeri, kemudian dengan satu negeri. | Penapis negeri mengecilkan hasil kepada negeri itu sahaja. Tanpa penapis, semua negeri termasuk. | | | | |
| F-LAP-03 | Tinggi | Ibu Pejabat | 1. Muat turun PDF bagi setiap jenis laporan. 2. Buka fail. | Fail bermula dengan `laporan-spektra-` dan tarikh. Halaman ialah landskap. Laporan Kontrak mengikut Negeri dan Laporan Status Sewaan menggunakan kertas A3. Jenis lain menggunakan A4. Kandungan sepadan dengan skrin. | | | | |
| F-LAP-04 | Kritikal | Negeri | 1. Semak menu sisi. 2. Cuba buka URL laporan dan URL PDF secara terus. | Menu Jana Laporan tidak dipaparkan. Akses terus ditolak. | | | | |
| F-LAP-05 | Tinggi | Ibu Pejabat | 1. Jana Laporan Kontrak mengikut Negeri dengan satu daerah. 2. Ulang tanpa daerah. 3. Cuba daerah yang bukan daerah negeri yang dipilih, jika kawalan membenarkan. | Penapis daerah mengecilkan hasil kepada daerah itu. Tanpa daerah, semua daerah negeri yang dipilih termasuk. Daerah yang tidak sah ditolak. | | | | |

### 11.18 Peti masuk dan lencana

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-NOT-01 | Tinggi | Semua pentadbir | 1. Jalankan satu tindakan yang menjana pemakluman, contoh hantar ke Ibu Pejabat. 2. Buka ikon peti masuk dan halaman Peti Masuk. 3. Tanda semua sebagai dibaca. | Item baharu muncul untuk peranan yang perlu bertindak. Tanda dibaca mengosongkan kiraan belum baca. | | | | |
| F-NOT-02 | Sederhana | Semua pentadbir | 1. Perhatikan lencana pada Pengurusan Permohonan dan Kontrak Sewaan. 2. Selesaikan atau buka item yang belum dilihat. | Lencana memaparkan bilangan item yang menunggu. Bilangan berkurang selepas item dilihat atau selesai, selepas segar semula menu. | | | | |
| F-NOT-03 | Tinggi | Pentadbir sistem bersama Negeri | 1. Laksanakan JOB-01 hingga JOB-03. 2. Bandingkan peti mel dengan jadual Seksyen 5. | Subjek, penerima dan peraturan berhenti sepadan dengan Seksyen 5. | | | | |

### 11.19 Kawalan akses skrin

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-SEC-01 | Kritikal | Pelawat | 1. Tanpa log masuk, buka terus Papan Pemuka, Status Permohonan, Kontrak Sewaan, Pengguna dan Laporan. | Setiap halaman membawa pengguna ke log masuk. Data tidak dipaparkan. | | | | |
| F-SEC-02 | Kritikal | Negeri | 1. Salin pautan semakan permohonan Negeri A. 2. Buka pautan itu semasa log masuk sebagai Negeri B. | Permohonan tidak dibuka. Akses ditolak atau halaman tidak dijumpai, tanpa mendedahkan butiran. | | | | |
| F-SEC-03 | Tinggi | Semua | 1. Pada telefon, buka menu. 2. Semak halaman utama setiap peranan. 3. Kecilkan tetingkap desktop. | Menu boleh dibuka dan ditutup. Tajuk, jadual dan borang utama boleh digunakan. Tindakan penting tidak terpotong. | | | | |

## 12. Kes hujung ke hujung

Gunakan premis fiksyen yang baharu bagi setiap kes. Catat nama premis dalam keputusan sebenar.

| ID | Keutamaan | Senario | Langkah ringkas | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| F-E2E-01 | Kritikal | Permohonan baharu hingga kontrak aktif | 1. Negeri mencipta permohonan Baru, memilih daerah negeri sendiri, dan memuat naik dokumen sokongan. 2. Negeri melengkapkan surat niat, JRP dan surat agensi. 3. Negeri menekan Hantar ke Ibu Pejabat dan mengesahkan Ya, Hantar. 4. Ibu Pejabat menyemak senarai semak dan meneruskan ke draf. 5. Negeri memuat naik PDF draf. 6. Ibu Pejabat meluluskan Semakan PUU 1 dengan PDF. 7. Negeri mengesahkan empat pengakuan dan mengembalikan dokumen. 8. Ibu Pejabat mengesahkan empat pengakuan tandatangan. 9. Negeri mengesahkan tiga pengakuan mati setem. | Satu kontrak baharu muncul dalam Senarai Kontrak Sewaan negeri tersebut dan dalam laporan Ibu Pejabat. Papan pemuka kedua-dua peranan berubah dengan betul. Pemakluman MAIL-01 hingga MAIL-04 dan MAIL-06 hingga MAIL-08 wujud. Fail draf berada di storan peribadi. | | | | |
| F-E2E-02 | Kritikal | Lanjutan kontrak | 1. Negeri mencipta Lanjutan daripada kontrak aktif. 2. Negeri mengisi catatan, notis pemilik, JRP dan surat agensi. 3. Ulang langkah semakan hingga mati setem seperti F-E2E-01, dengan langkah lanjutan. | Kontrak asal diganti selepas kelulusan Ibu Pejabat. Kontrak lanjutan yang selesai menjadi kontrak aktif. | | | | |
| F-E2E-03 | Kritikal | Pindah premis, termasuk satu pembatalan semakan PUU | 1. Negeri mencipta Pindah dan mengisi butiran premis baharu, termasuk daerah. 2. Lengkapkan surat niat, JRP, surat agensi dan catatan, kemudian hantar. 3. Ibu Pejabat meneruskan ke draf. 4. Negeri memuat naik draf pertama. 5. Ibu Pejabat menekan Semak Semula dengan PDF pindaan. 6. Negeri memuat naik draf kedua. 7. Ibu Pejabat meluluskan Semakan PUU 2 dengan PDF. 8. Siapkan pengesahan negeri, tandatangan Ibu Pejabat dan mati setem. | Pusingan kedua direkod sebagai Semakan PUU 2. Kontrak pindah yang selesai aktif. Kontrak premis lama tidak lagi aktif. | | | | |

## 13. Semakan pelayar

Jalankan F-AUTH-01, F-APP-01 dan paparan Senarai Kontrak Sewaan pada setiap kombinasi. Kes lain kekal pada pelayar desktop utama.

| Kombinasi | Pelayar dan saiz | F-AUTH-01 | F-APP-01 | Senarai kontrak | Catatan | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Desktop utama | Chrome, lebar biasa | | | | | | |
| Desktop kedua | Edge, lebar biasa | | | | | | |
| Telefon | Chrome, lebar lebih kurang 390 piksel | | | | | | |

## 14. Jejak pemakluman

Penguji menanda apabila emel atau rekod log wujud. Alamat sebenar jawatan tidak ditulis di sini.

| Peristiwa | Subjek atau penerima yang dijangka | Bukti | Status |
| --- | --- | --- | --- |
| Permohonan dihantar ke Ibu Pejabat | SPEKTRA: Permohonan Baharu Menunggu Semakan Ibu Pejabat | | |
| Salinan penghantaran kepada negeri | SPEKTRA: Permohonan Dihantar ke Ibu Pejabat | | |
| Ibu Pejabat meneruskan ke draf | SPEKTRA: Permohonan Disahkan oleh Ibu Pejabat | | |
| Draf dimuat naik | SPEKTRA: Draf Perjanjian Dihantar untuk Semakan PUU | | |
| Semakan PUU diluluskan | SPEKTRA: Draf Perjanjian Diluluskan | | |
| Semakan PUU dikembalikan untuk pindaan | SPEKTRA: Semakan PUU Memerlukan Pindaan | | |
| Dokumen dikembalikan ke Cawangan Pembangunan | SPEKTRA: Dokumen Dikembalikan untuk Pengesahan & Tandatangan | | |
| Dihantar untuk mati setem | SPEKTRA: Perjanjian Dihantar untuk Mati Setem | | |
| Permohonan selesai | SPEKTRA: Permohonan Selesai — Dalam Senarai Kontrak Sewaan | | |
| Peringatan bawah 8 bulan | SPEKTRA: Peringatan Kontrak Bawah 8 Bulan | | |
| Peringatan bawah 5 bulan | SPEKTRA: Peringatan Tegas Kontrak Bawah 5 Bulan | | |
| Amaran baki 3 bulan | SPEKTRA: AMARAN Kontrak Baki 3 Bulan | | |
| Set semula kata laluan | SPEKTRA: Set Semula Kata Laluan | | |

## 15. Log kecacatan

| No. | Tarikh | ID kes | Tahap | Ringkasan | Langkah ulang | Keputusan sebenar | Keputusan dijangka | Status | Disahkan oleh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| DEF-001 | | | | | | | | Terbuka | |
| DEF-002 | | | | | | | | Terbuka | |
| DEF-003 | | | | | | | | Terbuka | |
| DEF-004 | | | | | | | | Terbuka | |
| DEF-005 | | | | | | | | Terbuka | |
| DEF-006 | | | | | | | | Terbuka | |
| DEF-007 | | | | | | | | Terbuka | |
| DEF-008 | | | | | | | | Terbuka | |

## 16. Ujian semula

| No. kecacatan | Versi baiki | ID kes diuji semula | Keputusan | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- |
| | | | | | |
| | | | | | |
| | | | | | |
| | | | | | |

## 17. Matriks kebolehkesanan ke UAT

Kes teknikal ENV, CFG, REG, SEC, DATA, FILE, MAIL, JOB, BAK dan ERR ialah bukti kilang. Kes fungsi di bawah dipaut ke skrip UAT supaya UAT boleh menggunakan binaan yang sudah dibuktikan.

| Kes FAT | Kes UAT | Catatan |
| --- | --- | --- |
| F-AUTH-01 hingga F-AUTH-07 | AUTH-01 hingga AUTH-07 | F-AUTH-05 turut menguji emel yang tidak wujud. |
| F-MFA-01 hingga F-MFA-05 | MFA-01 hingga MFA-05 | |
| F-PROF-01 hingga F-PROF-03 | PROF-01 hingga PROF-03 | |
| F-USER-01 hingga F-USER-08 | USER-01 hingga USER-08 | |
| F-DASH-01 hingga F-DASH-04 | DASH-01 hingga DASH-04 | |
| F-APP-01 hingga F-APP-05 | APP-01 hingga APP-05 | F-APP-01 turut mewajibkan daerah. |
| F-APP-06 | — | Daerah wajib dan mesti milik negeri pentadbir. |
| F-PROC-01 hingga F-PROC-05 | PROC-01 hingga PROC-05 | |
| F-DOC-01 hingga F-DOC-04 | DOC-01 hingga DOC-04 | |
| F-HQ-01 hingga F-HQ-04 | HQ-01 hingga HQ-04 | F-HQ-02 merekod item KE yang dipaparkan dan ditanda awal apabila keluasan melebihi 465 meter persegi. |
| F-DRAF-01 hingga F-DRAF-04 | DRAF-01 hingga DRAF-04 | Lulus dan Semak Semula memerlukan PDF. |
| F-ACK-01 hingga F-ACK-02 | ACK-01 hingga ACK-02 | |
| F-SIGN-01 | SIGN-01 | Empat label pengakuan mengikut Seksyen 5 dokumen ini. |
| F-STEM-01 | STEM-01 | Tiga label pengakuan mengikut Seksyen 5, termasuk salinan ke Cawangan Pembangunan. |
| F-FOL-01 hingga F-FOL-04 | FOL-01 hingga FOL-04 | |
| F-WD-01 hingga F-WD-06 | WD-01 hingga WD-06 | F-WD-02 turut menguji alasan melebihi 2,000 aksara. |
| F-KON-01 hingga F-KON-04 | KON-01 hingga KON-04 | |
| F-LAP-01 hingga F-LAP-04 | LAP-01 hingga LAP-04 | F-LAP-03 merekod saiz kertas A3 dan A4. |
| F-LAP-05 | — | Penapis daerah pada laporan. |
| F-NOT-01 hingga F-NOT-03 | NOT-01 hingga NOT-03 | |
| F-SEC-01 hingga F-SEC-03 | SEC-01 hingga SEC-03 | |
| F-E2E-01 hingga F-E2E-03 | E2E-01 hingga E2E-03 | |

## 18. Rumusan keputusan

| Perkara | Bilangan |
| --- | --- |
| Jumlah kes | 138 |
| Lulus | |
| Gagal | |
| Disekat | |
| Tidak diuji | |
| Kecacatan kritikal terbuka | |
| Kecacatan besar terbuka | |
| Kecacatan kecil diterima | |

| Syarat keluar | Dipenuhi (Ya/Tidak) | Catatan |
| --- | --- | --- |
| REG-01 lulus pada binaan yang sama | | |
| F-E2E-01, F-E2E-02 dan F-E2E-03 lulus | | |
| Semua kes kritikal lulus | | |
| Sekurang-kurangnya 95% kes tinggi lulus | | |
| Tiada kecacatan kritikal atau besar terbuka | | |
| Senarai semak serahan lengkap | | |

Cadangan ketua ujian kilang:

- [ ] Layak diserahkan untuk UAT
- [ ] Layak diserahkan untuk UAT dengan syarat
- [ ] Tidak layak diserahkan

Syarat atau pengecualian:

_______________________________________________________________________________

_______________________________________________________________________________

## 19. Lampiran bukti

| Bukti | Nama fail | Tarikh | Disimpan oleh |
| --- | --- | --- | --- |
| Output `php artisan test --compact` | | | |
| Output `php artisan migrate:status` | | | |
| Output `php artisan schedule:list` | | | |
| Header respons log masuk | | | |
| Sandaran pangkalan data FAT | | | |
| Sampel PDF laporan | | | |
| Sampel emel atau petikan log mel tanpa rahsia | | | |

## 20. Konfigurasi yang disahkan

Penguji menanda nilai yang benar-benar digunakan. Jangan tampal rahsia.

| Tetapan | Nilai yang disahkan | Penguji | Tarikh |
| --- | --- | --- | --- |
| `APP_ENV` | | | |
| `APP_DEBUG` | | | |
| `APP_URL` | | | |
| `SESSION_ENCRYPT` | | | |
| `SESSION_SECURE_COOKIE` | | | |
| `AUTH_MFA_REQUIRED` atau nilai berkesan | | | |
| `MAIL_MAILER` | | | |
| `MAIL_TEST_RECIPIENT` diisi (Ya/Tidak) | | | |
| `upload_max_filesize` | | | |
| `post_max_size` | | | |
| Penjadual `schedule:run` terpasang (Ya/Tidak) | | | |

## 21. Senarai semak serahan ke UAT

| Item | Selesai (Ya/Tidak) | Catatan |
| --- | --- | --- |
| Binaan yang lulus FAT dikenal pasti dalam Seksyen 1 | | |
| Migrasi telah dijalankan pada pangkalan UAT yang kosong | | |
| Aset hadapan telah dibina | | |
| `APP_DEBUG=false` | | |
| Kata laluan benih pembangunan telah diganti | | |
| Pengesahan dua faktor ditetapkan mengikut keputusan pemilik sistem | | |
| Mel UAT menghantar ke peti ujian, bukan ke pemegang jawatan sebenar, sehingga pemilik sistem membenarkan | | |
| Dokumen kontrak tidak boleh dibuka melalui URL awam | | |
| Penjadual peringatan dipasang atau tindakan susulan bertulis wujud | | |
| Akaun UAT Ibu Pejabat, Negeri A dan Negeri B disediakan tanpa menulis kata laluan dalam dokumen | | |
| Log kecacatan FAT diserahkan bersama binaan | | |
| Manual pengguna dan skrip UAT diserahkan bersama binaan | | |

## 22. Perakuan serahan

Saya mengesahkan bahawa kes ujian dalam dokumen ini telah dijalankan pada binaan dan persekitaran kilang yang dinyatakan dalam Seksyen 1, dan keputusan yang ditulis adalah benar.

| Peranan | Nama | Jawatan | Keputusan | Tarikh | Tandatangan |
| --- | --- | --- | --- | --- | --- |
| Ketua ujian kilang | | | Layak / Bersyarat / Tidak layak | | |
| Penyemak teknikal | | | Layak / Bersyarat / Tidak layak | | |
| Saksi pemilik sistem | | | Hadir / Tidak hadir | | |
| Pelulus serahan ke UAT | | | Diserahkan / Tidak diserahkan | | |

---

*Tamat dokumen AADK/CP/SPEKTRA/FAT/001, versi 1.0.*
