# Skrip Ujian Audit Dalaman Pegawai IT

**SPEKTRA**  
Sistem Pemantauan Kontrak Sewaan Premis AADK  
Agensi Antidadah Kebangsaan

| Medan | Butiran |
| --- | --- |
| No. dokumen | AADK/CP/SPEKTRA/IAD/001 |
| Versi | 1.0 |
| Tarikh | 20 November 2026 |
| Status | Untuk pelaksanaan audit dalaman IT |
| Klasifikasi | Terhad |
| Pemilik sistem | Cawangan Pembangunan, AADK |
| Pelaksana | Pegawai IT |
| Penyemak | Ketua Unit IT atau pegawai IT yang diberi kuasa |

Dokumen ini ialah skrip ujian audit dalaman kawalan IT. Pegawai IT mengisi lajur **Keputusan sebenar**, **Status**, **Penguji** dan **Tarikh**. Kawalan IT diterima apabila kriteria keluar di Seksyen 8 dipenuhi dan borang perakuan di Seksyen 14 ditandatangani.

Skrip ini berdiri di samping FAT (`AADK/CP/SPEKTRA/FAT/001`) dan UAT (`AADK/CP/SPEKTRA/UAT/001`). FAT dijalankan oleh pasukan pembangunan pada persekitaran kilang. UAT dijalankan oleh pengguna bisnes. Audit ini dijalankan oleh pegawai IT untuk mengesahkan kawalan akses, sesi, akaun, jejak audit, kerahsiaan dokumen, konfigurasi, operasi dan sandaran.

## Kandungan

1. Kawalan dokumen
2. Tujuan
3. Rujukan
4. Skop
5. Peranan dan kawalan yang diaudit
6. Persekitaran dan garis asas
7. Kaedah, klasifikasi dan bukti
8. Kriteria masuk dan keluar
9. Ringkasan kes
10. Skrip ujian
11. Log dapatan
12. Ujian semula
13. Rumusan keputusan
14. Perakuan

## 1. Kawalan dokumen

| Peranan | Jawatan | Nama | Tarikh | Tandatangan |
| --- | --- | --- | --- | --- |
| Penyedia | Pegawai IT |  |  |  |
| Pelaksana ujian | Pegawai IT |  |  |  |
| Penyemak | Ketua Unit IT |  |  |  |
| Saksi pemilik sistem | Cawangan Pembangunan AADK |  |  |  |
| Pelulus | Pegawai IT yang diberi kuasa |  |  |  |

### Sejarah pindaan

| Versi | Tarikh | Penyedia | Ringkasan perubahan |
| --- | --- | --- | --- |
| 1.0 | 20 November 2026 | Pegawai IT | Terbitan pertama skrip audit dalaman IT bagi SPEKTRA, meliputi akses, akaun, pengesahan dua faktor, pengasingan data, jejak audit, dokumen, aliran, konfigurasi, operasi, sandaran dan laporan. |

### Identiti persekitaran yang diaudit

| Medan | Nilai |
| --- | --- |
| URL |  |
| Persekitaran |  |
| Tanda binaan |  |
| Tarikh mula |  |
| Tarikh tamat |  |
| Pangkalan data |  |
| Pegawai IT pelaksana |  |
| Penyemak |  |

## 2. Tujuan

Audit ini mengesahkan bahawa kawalan IT SPEKTRA beroperasi seperti yang dibina:

- hanya akaun aktif yang lulus pengesahan mendapat sesi kerja;
- kata laluan, had kadar dan pengesahan dua faktor mengikut garis asas;
- pentadbir Negeri terhad kepada negeri sendiri, dan kuasa Ibu Pejabat tidak terbuka kepada Negeri;
- jejak audit merekod pelaku dan peristiwa tanpa menyimpan rahsia pengesahan;
- dokumen kontrak berada di storan peribadi dan tidak dimuat turun tanpa kuasa;
- tindakan di luar peranan atau di luar peringkat tidak mengubah status;
- konfigurasi pelayan, header keselamatan, jadual, pemakluman dan sandaran boleh dibuktikan.

Keputusan lulus bermaksud kawalan IT yang disenaraikan diterima oleh pegawai IT. Ia tidak menggantikan penerimaan bisnes dalam UAT.

## 3. Rujukan

| Dokumen | No. | Kegunaan dalam audit ini |
| --- | --- | --- |
| Dokumen FAT SPEKTRA | AADK/CP/SPEKTRA/FAT/001 | Garis asas pemasangan dan nama kes teknikal yang berkaitan. |
| Dokumen UAT SPEKTRA | AADK/CP/SPEKTRA/UAT/001 | Penerimaan bisnes. Cabang kelulusan MOF, KE dan BPH tidak diulang di sini. |
| Manual Pengguna SPEKTRA | AADK/CP/SPEKTRA/MANUAL/001 | Nama menu, butang, status dan label jejak pada skrin. |

Jika label pada skrin berbeza daripada manual, label skrin binaan yang diaudit ialah bukti. Perbezaan itu ditulis dalam keputusan sebenar.

## 4. Skop

### Dalam skop

- Log masuk, log keluar, sesi, token borang, had kadar dan halaman ralat.
- Pendaftaran, nyahaktif, kata laluan, lupa kata laluan, peranan dan paparan senarai pengguna.
- Pengesahan dua faktor, kod salah, kod pemulihan dan had cabaran.
- Pengasingan rekod antara negeri, termasuk URL terus, carian, laporan dan emel jawatan.
- Jejak audit pada skrin dan, jika akses baca diberi, log sistem serta log amaran log masuk gagal.
- Jenis, saiz, storan dan muat turun dokumen.
- Satu sampel integriti aliran: kuasa peranan, langkah wajib, catatan susulan, medan lanjutan, pengakuan akhir, kontrak diganti, dan had alasan.
- Garis asas konfigurasi, header keselamatan, fail sensitif melalui URL awam, HTTPS dan kata laluan benih.
- Jadual peringatan, penghantar mel, peti masuk, giliran dan masa pelayan.
- Bukti sandaran pangkalan data dan storan peribadi, serta pulihan pada lokasi berasingan.
- Had laporan dan kandungan PDF.

### Luar skop

- Ujian penerimaan bisnes bagi setiap item senarai semak MOF, KE dan BPH. Itu skop UAT.
- Ujian beban, ujian penembusan berbayar dan audit kod sumber baris demi baris.
- Proses luar sistem: kelulusan MOF, Kementerian Ekonomi, BPH, tandatangan fizikal TKPP, Sistem PROMIS dan LHDN. Sistem hanya merekod pengakuan.
- Perubahan dasar yang belum dibina pada binaan yang diaudit.

## 5. Peranan dan kawalan yang diaudit

| Peranan sistem | Label pada skrin | Kuasa yang disahkan dalam audit ini |
| --- | --- | --- |
| admin_hq | Ibu Pejabat | Semua negeri. Semak dan luluskan aliran Ibu Pejabat, urus status pengguna, emel jawatan dan laporan. Menu Permohonan Baharu tidak dipaparkan. |
| admin_negeri | Negeri | Negeri sendiri sahaja. Cipta permohonan, lengkapkan langkah, muat naik dokumen dan draf, serta hantar mati setem. Tidak mengurus status akaun, emel jawatan atau laporan. |

Pegawai IT melaksanakan ujian dengan akaun yang disediakan. Pegawai IT tidak menulis kata laluan, kod pemulihan, APP_KEY atau kandungan fail .env dalam skrip ini.

### Objektif kawalan

| Objektif | Kes |
| --- | --- |
| Sesi hanya untuk akaun yang sah | ACC-01 hingga ACC-08, USR-05 |
| Kata laluan dan pengesahan dua faktor | USR-03, USR-10, USR-11, MFA-01 hingga MFA-06 |
| Kuasa daftar dan tutup akaun | USR-01, USR-02, USR-07, USR-08, USR-09, USR-12 |
| Pengasingan negeri dan laporan | SEG-01 hingga SEG-08, LAP-01, LAP-03 |
| Jejak boleh disiasat dan tidak diubah pada skrin | AUD-01 hingga AUD-08 |
| Dokumen tidak boleh dimuat turun tanpa kuasa | DOC-01 hingga DOC-05, CFG-06 |
| Aliran tidak dilangkau | INT-01 hingga INT-07 |
| Konfigurasi keras | CFG-01 hingga CFG-09 |
| Operasi berjadual dan pemakluman | OPS-01 hingga OPS-07 |
| Sandaran boleh dipulihkan di lokasi berasingan | BAK-01 hingga BAK-05 |

## 6. Persekitaran dan garis asas

| Item | Nilai audit |
| --- | --- |
| URL |  |
| Pelayan aplikasi |  |
| PHP | 8.2 atau lebih baharu. Rujukan projek ialah PHP 8.4. |
| Pangkalan data |  |
| Pelayar | Versi semasa Chrome atau Edge |
| Mel | Peti ujian, atau bukti penghantaran yang boleh dibaca oleh pegawai IT |
| Tarikh audit |  |

Akaun minimum: satu Ibu Pejabat, Negeri A, Negeri B, dan satu akaun yang akan dinyahaktifkan semasa kes berkaitan. Sediakan aplikasi pengesah jika pengesahan dua faktor dihidupkan.

### Garis asas yang disahkan

| Tetapan | Nilai yang diterima |
| --- | --- |
| APP_NAME | SPEKTRA |
| APP_DEBUG | false |
| APP_KEY | Diisi. Tidak disalin ke dokumen ini. |
| APP_URL | URL sebenar persekitaran yang diaudit. |
| LOG_LEVEL pada production | warning atau lebih ketat |
| SESSION_DRIVER | database |
| SESSION_LIFETIME | 120 |
| SESSION_ENCRYPT | true |
| SESSION_HTTP_ONLY | true |
| SESSION_SECURE_COOKIE | true apabila URL menggunakan HTTPS |
| SESSION_SAME_SITE | lax |
| FILESYSTEM_DISK | local, akar storage/app/private |
| Had dokumen sokongan | PDF, JPG atau PNG, maksimum 10 MB |
| Had draf dan dokumen PUU | PDF sahaja, maksimum 40 MB |
| Kata laluan baharu | Sekurang-kurangnya 8 aksara, huruf besar, huruf kecil dan nombor. Pada production, kata laluan yang diketahui tercemar juga ditolak. |
| Had log masuk | 10 percubaan seminit bagi gabungan emel dan alamat IP |
| Had lupa kata laluan | 5 permintaan seminit bagi gabungan emel dan alamat IP |
| Had cabaran dua faktor | 5 percubaan seminit |
| Had tindakan borang | 60 permintaan seminit bagi setiap pengguna. Jangan ulang hantar melebihi had ini semasa audit. |
| Alasan padam dan tarik semula | Sekurang-kurangnya 10 aksara dan tidak melebihi 2,000 aksara |
| Pengesahan dua faktor | Wajib pada production apabila AUTH_MFA_REQUIRED tidak ditetapkan atau bernilai benar. |
| Jadual peringatan | contracts:send-expiry-reminders, setiap hari jam 09:00, zon Asia/Kuala_Lumpur |

### Peringatan tamat kontrak

| Baki tempoh | Penerima | Kekerapan | Berhenti apabila |
| --- | --- | --- | --- |
| Bawah 8 bulan hingga 5 bulan | Pentadbir negeri terlibat | 3 kali dalam 3 hari | Baki mencecah 5 bulan, 3 penghantaran selesai, atau permohonan susulan dihantar ke Ibu Pejabat |
| Bawah 5 bulan hingga 3 bulan | Pentadbir negeri terlibat | Setiap Isnin | Baki mencecah 3 bulan |
| 3 bulan atau kurang | Pentadbir negeri, salinan kepada Ibu Pejabat dan emel jawatan | Setiap Isnin dan Rabu | Permohonan dihantar ke Ibu Pejabat |

Subjek laluan pertama ialah *SPEKTRA: PERINGATAN PENGHANTARAN PERMOHONAN PELANJUTAN SEWAAN RUANG PEJABAT AADK UNTUK KONTRAK YANG AKAN TAMAT DALAM TEMPOH LAPAN (8) BULAN*. Subjek laluan kedua ialah *SPEKTRA: Peringatan Tegas Kontrak Bawah 5 Bulan*. Subjek laluan ketiga ialah *SPEKTRA: AMARAN Kontrak Baki 3 Bulan*.

Emel jawatan yang disalin pada amaran baki 3 bulan, apabila penerima ujian tidak diisi, ialah Ketua Pengarah, TKPP, PKP dan KPP. Jika MAIL_TEST_RECIPIENT diisi, pemakluman pergi ke alamat itu.

### Label jejak pada skrin

Tajuk yang diterjemah pada skrin: Log masuk, Log keluar, Kemaskini profil, Reset kata laluan, Ubah peranan, Aktifkan akaun, Nyahaktif akaun, Akaun didaftarkan, Kemaskini emel jawatan dan Muat naik dokumen. Penghantaran permohonan ke Ibu Pejabat disemak di AUD-07.

Tindakan aliran yang belum mempunyai terjemahan tajuk dipaparkan daripada kod tindakan, contoh *Application sent to hq*. Keterangan tindakan itu kekal dalam Bahasa Melayu. Ketidakhadiran peristiwa ialah dapatan. Tajuk yang belum diterjemah direkod sebagai pemerhatian di bawah AUD-07.

Skrin Audit Trail memaparkan tajuk, keterangan, tarikh, masa, sumber dan nama pelaku. Sumber ialah Log Sistem, Proses Kontrak, Permohonan atau Dokumen. Alamat IP disimpan pada log sistem dan tidak dipaparkan pada skrin.

## 7. Kaedah, klasifikasi dan bukti

### Peraturan pelaksanaan

- Jalankan Seksyen 10.1 sebelum modul lain. Jika akaun atau persekitaran belum sedia, kes selepasnya ditanda Disekat.
- Satu pegawai IT melaksanakan. Penyemak mengesahkan kes Kritikal dan kes yang menyentuh rahsia, dokumen atau sandaran.
- Status yang dibenarkan: **Lulus**, **Gagal**, **Disekat**, **Tidak diuji**.
- Jika gagal, cipta satu rekod dalam log dapatan dan tulis nombor dapatan pada kes ujian.
- Pemerhatian yang tidak menggagalkan kawalan, contoh tajuk jejak yang belum diterjemah, ditulis dalam log dapatan pada tahap Pemerhatian.
- Bukti yang diterima: tangkapan skrin, subjek emel, nama fail PDF, tarikh sandaran, dan petikan log tanpa kata laluan. Jangan lampirkan fail .env, kod pemulihan atau eksport sesi.
- Nama bukti: `IAD-<ID kes>-<nn>`.

### Klasifikasi dapatan

| Tahap | Maksud | Contoh |
| --- | --- | --- |
| Kritikal | Kawalan utama gagal, data negeri lain terdedah, dokumen peribadi boleh dimuat turun tanpa kuasa, atau sesi terbuka tanpa pengesahan yang diwajibkan. | Negeri B membuka dokumen Negeri A. |
| Besar | Kawalan penting salah dan jalan sementara menyusahkan operasi atau siasatan. | Jejak tidak merekod pelaku nyahaktif akaun. |
| Kecil | Pengesahan atau mesej salah, manakala kawalan utama masih menahan tindakan. | Mesej ralat tidak tepat. |
| Pemerhatian | Keadaan semasa yang direkod untuk tindakan susulan, tanpa menggagalkan kes jika peristiwa dan kawalan wujud. | Tajuk jejak aliran masih dalam bahasa Inggeris, keterangan Melayu lengkap. |

Status dapatan: Terbuka, Dibaiki, Diuji semula, Ditutup, Diterima dengan syarat.

## 8. Kriteria masuk dan keluar

### Kriteria masuk

- Persekitaran yang diaudit dikenal pasti dalam Seksyen 1.
- Akaun PRE-02 dan data PRE-03 tersedia.
- Akses baca konfigurasi dan bukti sandaran diatur, tanpa penyerahan fail rahsia.
- Pelaksana dan penyemak dilantik.

### Kriteria keluar

Kawalan IT dicadangkan diterima apabila semua syarat berikut dipenuhi:

- Semua kes berkeutamaan **Kritikal** lulus.
- Sekurang-kurangnya 95% kes berkeutamaan **Tinggi** lulus.
- Tiada dapatan kritikal atau besar yang masih terbuka.
- Dapatan kecil yang diterima mempunyai tindakan susulan bertulis.
- BAK-01 dan BAK-02 lulus, atau mempunyai pengecualian bertulis daripada pelulus.
- Borang perakuan ditandatangani oleh pegawai IT pelaksana dan pelulus.

Bilangan semasa: 38 kes Kritikal, 42 kes Tinggi, 3 kes Sederhana. Jumlah 83 kes.

## 9. Ringkasan kes

| Modul | ID | Bilangan | Keutamaan utama |
| --- | --- | --- | --- |
| Persediaan audit | PRE-01 hingga PRE-04 | 4 | Tinggi |
| Kawalan akses dan sesi | ACC-01 hingga ACC-08 | 8 | Kritikal |
| Pengurusan akaun dan kata laluan | USR-01 hingga USR-12 | 12 | Tinggi |
| Pengesahan dua faktor | MFA-01 hingga MFA-06 | 6 | Kritikal |
| Pengasingan data dan kuasa | SEG-01 hingga SEG-08 | 8 | Kritikal |
| Jejak audit | AUD-01 hingga AUD-08 | 8 | Tinggi |
| Kerahsiaan dokumen | DOC-01 hingga DOC-05 | 5 | Tinggi |
| Integriti aliran | INT-01 hingga INT-07 | 7 | Tinggi |
| Konfigurasi keselamatan | CFG-01 hingga CFG-09 | 9 | Kritikal |
| Operasi, log dan pemakluman | OPS-01 hingga OPS-07 | 7 | Tinggi |
| Sandaran dan pulihan | BAK-01 hingga BAK-05 | 5 | Kritikal |
| Laporan dan eksport | LAP-01 hingga LAP-04 | 4 | Tinggi |
| **Jumlah** |  | **83** |  |

## 10. Skrip ujian

Lajur status diisi **Lulus**, **Gagal**, **Disekat** atau **Tidak diuji**.

## 10.1 Persediaan audit

Pegawai IT mengesahkan persekitaran, akaun dan data sebelum kes kawalan dimulakan. Kata laluan tidak ditulis dalam dokumen ini.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| PRE-01 | Tinggi | 1. Catat URL, tarikh audit, tanda binaan, nama pangkalan data dan nama pegawai IT pada Seksyen 1. 2. Sahkan ujian dibuat pada persekitaran yang diisytiharkan. | Medan identiti diisi. Kes selepas ini dijalankan pada persekitaran yang sama. Jika ujian mengubah rekod, persekitaran itu berasingan daripada data operasi. |  |  |  |  |
| PRE-02 | Kritikal | 1. Sahkan akaun Ibu Pejabat, Negeri A, Negeri B bagi negeri yang berbeza, dan satu akaun yang boleh dinyahaktifkan. 2. Sediakan aplikasi pengesah jika pengesahan dua faktor dihidupkan. | Empat akaun tersedia. Kata laluan disimpan di luar dokumen ini. Akaun Negeri A dan Negeri B bukan negeri yang sama. |  |  |  |  |
| PRE-03 | Tinggi | 1. Sediakan satu rekod fiksyen Negeri A dan satu rekod fiksyen Negeri B. 2. Sediakan PDF, JPG dan PNG di bawah had, serta fail jenis atau saiz yang tidak sah. | Data fiksyen sedia untuk kes pengasingan dan dokumen. Tangkapan skrin yang akan diedarkan tidak menggunakan premis sebenar yang sulit. |  |  |  |  |
| PRE-04 | Tinggi | 1. Dapatkan akses baca kepada tetapan Seksyen 6 dan kepada bukti sandaran. 2. Jangan salin fail .env, APP_KEY atau kata laluan ke dalam dokumen atau lampiran. | Pegawai IT boleh mengesahkan nama tetapan dan nilai kawalan. Rahsia tidak ditulis dalam kertas kerja. |  |  |  |  |

## 10.2 Kawalan akses dan sesi

Sesi kerja hanya terbuka untuk akaun yang sah, ditutup selepas log keluar, dan dilindungi oleh token borang serta had kadar.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| ACC-01 | Kritikal | 1. Tanpa log masuk, buka Papan Pemuka, Status Permohonan, Kontrak Sewaan, Pengguna dan Laporan. | Setiap halaman membawa pelawat ke log masuk. Badan halaman tidak memaparkan data permohonan, kontrak atau pengguna. |  |  |  |  |
| ACC-02 | Tinggi | 1. Log masuk dengan akaun aktif. 2. Buka jejak audit akaun itu. | Papan Pemuka terbuka dan nama pengguna kelihatan. Jejak merekod tajuk Log masuk. |  |  |  |  |
| ACC-03 | Kritikal | 1. Log keluar. 2. Guna butang undur pelayar. 3. Cuba buka Papan Pemuka dan satu URL dalaman yang disalin tadi. | Sesi ditutup. Halaman dalaman mengembalikan pengguna ke log masuk. Jejak merekod tajuk Log keluar. |  |  |  |  |
| ACC-04 | Tinggi | 1. Selepas log masuk berjaya, semak kuki sesi pada pelayar. | Kuki mempunyai HttpOnly. SameSite ialah Lax. Pada HTTPS, bendera Secure ada. Hayat sesi dikonfigurasi 120 minit. |  |  |  |  |
| ACC-05 | Kritikal | 1. Log masuk. 2. Dengan akses baca pangkalan data, lihat rekod sesi baharu tanpa mengeksport nilai penuh ke dokumen. | Rekod sesi wujud dalam pangkalan data. Nilai tersimpan tidak memaparkan kata laluan dalam teks jelas. |  |  |  |  |
| ACC-06 | Tinggi | 1. Hantar log masuk gagal bagi emel dan alamat IP yang sama lebih daripada 10 kali dalam satu minit. 2. Selepas tempoh rehat, log masuk dengan kata laluan yang sah. | Percubaan selepas had ditolak. Akaun yang sah masih boleh masuk selepas tempoh rehat. |  |  |  |  |
| ACC-07 | Kritikal | 1. Hantar borang log masuk tanpa token borang. | Permintaan ditolak. Sesi kerja tidak terbuka. |  |  |  |  |
| ACC-08 | Kritikal | 1. Sahkan APP_DEBUG palsu. 2. Buka satu URL yang tidak wujud. | Halaman ralat tidak memaparkan jejak kod, pertanyaan SQL atau nilai konfigurasi. |  |  |  |  |

## 10.3 Pengurusan akaun dan kata laluan

Akaun dicipta, dihadkan dan ditutup mengikut peranan. Kata laluan mematuhi dasar, dan pengguna tidak menukar kuasa sendiri.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| USR-01 | Tinggi | 1. Sebagai Ibu Pejabat, daftar satu pengguna Negeri bagi negeri yang dipilih. 2. Daftar satu pengguna Ibu Pejabat. 3. Guna kata laluan yang memenuhi dasar. | Kedua-dua akaun wujud dalam Senarai Pengguna. Pengguna Negeri mempunyai negeri. Pengguna Ibu Pejabat tidak diikat pada satu negeri. Jejak merekod Akaun didaftarkan. Akaun aktif boleh log masuk. |  |  |  |  |
| USR-02 | Kritikal | 1. Sebagai Negeri A, buka pendaftaran. 2. Cuba daftar peranan Ibu Pejabat. 3. Cuba daftar negeri selain Negeri A. | Peranan Ibu Pejabat tidak tersedia atau ditolak. Negeri dikunci kepada negeri pentadbir. Akaun bagi negeri lain tidak tercipta. |  |  |  |  |
| USR-03 | Kritikal | 1. Cuba daftar atau set semula dengan kata laluan password, kata laluan tanpa huruf besar, tanpa nombor, atau kurang daripada 8 aksara. 2. Pada production, cuba satu kata laluan yang diketahui tercemar. | Setiap percubaan ditolak. Akaun tidak menyimpan kata laluan yang lemah. |  |  |  |  |
| USR-04 | Tinggi | 1. Daftar pengguna dengan emel yang sudah wujud. | Pendaftaran ditolak. Akaun asal tidak berubah. |  |  |  |  |
| USR-05 | Kritikal | 1. Ibu Pejabat nyahaktifkan akaun ujian. 2. Cuba log masuk dengan akaun itu. 3. Buka jejak akaun itu. | Status menjadi Nyahaktif. Log masuk ditolak. Jejak merekod Nyahaktif akaun dan nama pelaku Ibu Pejabat. |  |  |  |  |
| USR-06 | Tinggi | 1. Aktifkan semula akaun yang sama. 2. Log masuk. 3. Buka jejak. | Status menjadi Aktif. Log masuk berjaya. Jejak merekod Aktifkan akaun. |  |  |  |  |
| USR-07 | Kritikal | 1. Sebagai Ibu Pejabat, cuba nyahaktifkan akaun yang sedang digunakan. 2. Jika hanya satu Ibu Pejabat aktif, cuba nyahaktifkan akaun itu. | Akaun sendiri tidak dinyahaktifkan. Jika akaun itu satu-satunya Ibu Pejabat aktif, sistem mengekalkan sekurang-kurangnya satu Ibu Pejabat aktif. |  |  |  |  |
| USR-08 | Kritikal | 1. Bandingkan Senarai Pengguna sebagai Ibu Pejabat dan sebagai Negeri A. | Ibu Pejabat melihat jadual Ibu Pejabat dan Negeri. Negeri hanya melihat pengguna Negeri bagi negeri sendiri. Suis status hanya kelihatan kepada Ibu Pejabat, dan hanya untuk akaun orang lain. Negeri tidak melihat borang Salinan Emel Jawatan. |  |  |  |  |
| USR-09 | Tinggi | 1. Ibu Pejabat tukar satu akaun ujian daripada Negeri kepada Ibu Pejabat, kemudian kembalikan kepada Negeri. 2. Pada profil sendiri, cuba tukar peranan. | Pertukaran oleh Ibu Pejabat berjaya. Jejak merekod Ubah peranan bersama peranan lama dan baharu. Pada profil sendiri, peranan ialah paparan sahaja. |  |  |  |  |
| USR-10 | Tinggi | 1. Hantar lupa kata laluan untuk emel yang wujud dan untuk emel yang tidak wujud. 2. Buka pautan set semula akaun ujian. 3. Tetapkan kata laluan baharu. 4. Cuba kata laluan lama, kemudian kata laluan baharu. | Mesej tidak mendedahkan sama ada emel wujud. Pautan membuka borang. Kata laluan baharu diterima. Kata laluan lama ditolak. Jejak merekod Reset kata laluan. |  |  |  |  |
| USR-11 | Tinggi | 1. Hantar lupa kata laluan bagi emel yang sama lebih daripada 5 kali dalam satu minit. | Permintaan selepas had ditolak. |  |  |  |  |
| USR-12 | Kritikal | 1. Sebagai Negeri A, buka URL kemaskini profil pengguna Negeri B dan pengguna Ibu Pejabat. | Halaman tidak dijumpai atau ditolak. Profil sasaran tidak berubah. |  |  |  |  |

## 10.4 Pengesahan dua faktor

Apabila kawalan dihidupkan, sesi kerja terbuka hanya selepas cabaran berjaya. Kod pengesah dan kod pemulihan tidak masuk ke jejak audit.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| MFA-01 | Kritikal | 1. Baca APP_ENV dan AUTH_MFA_REQUIRED. 2. Log masuk akaun yang belum mengesahkan dua faktor. | Pada production, jika AUTH_MFA_REQUIRED tidak ditetapkan atau benar, cabaran dua faktor wajib sebelum sesi kerja terbuka. Jika ditetapkan palsu, kelakuan mengikut nilai itu. Nilai tetapan direkod tanpa menyalin rahsia. |  |  |  |  |
| MFA-02 | Tinggi | 1. Pada persediaan pertama, imbas kod QR dengan aplikasi pengesah. 2. Simpan kod pemulihan di luar dokumen ini. 3. Sahkan kod. | Kod QR dijana dalam sistem. Kod pemulihan dipaparkan untuk disimpan oleh pemilik akaun. Sesi kerja terbuka selepas kod disahkan. |  |  |  |  |
| MFA-03 | Kritikal | 1. Pada cabaran log masuk, masukkan kod yang salah. | Mesej kod tidak sah dipaparkan. Sesi kerja tidak terbuka. |  |  |  |  |
| MFA-04 | Kritikal | 1. Guna satu kod pemulihan yang disimpan. 2. Cuba kod yang sama sekali lagi. | Penggunaan pertama membuka sesi. Penggunaan semula ditolak. |  |  |  |  |
| MFA-05 | Tinggi | 1. Hantar kod salah lebih daripada 5 kali dalam satu minit. | Permintaan selepas had ditolak. Sesi kerja tidak terbuka. |  |  |  |  |
| MFA-06 | Kritikal | 1. Lengkapkan cabaran yang sah. 2. Buka jejak audit akaun itu dan cari kod pengesah atau kod pemulihan. | Log masuk direkod selepas cabaran lulus. Kod pengesah dan kod pemulihan tidak dipaparkan dalam tajuk atau keterangan jejak. |  |  |  |  |

## 10.5 Pengasingan data dan kuasa

Pentadbir Negeri hanya mengurus negeri sendiri. Laporan, emel jawatan dan tindakan Ibu Pejabat tidak terbuka kepada Negeri.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| SEG-01 | Kritikal | 1. Bandingkan papan pemuka, senarai permohonan dan senarai kontrak Negeri A dengan Negeri B. | Kiraan dan senarai Negeri A tidak mengandungi rekod Negeri B. Kiraan dan senarai Negeri B tidak mengandungi rekod Negeri A. |  |  |  |  |
| SEG-02 | Kritikal | 1. Sebagai Negeri B, buka URL semakan, butiran kontrak dan muat turun dokumen milik Negeri A. | Setiap permintaan ditolak atau tidak dijumpai. Butiran Negeri A tidak dipaparkan. Fail tidak dihantar. |  |  |  |  |
| SEG-03 | Kritikal | 1. Log masuk sebagai Negeri. 2. Periksa menu. 3. Buka URL laporan dan URL muat turun PDF laporan. | Menu Laporan tidak tersedia. Kedua-dua URL ditolak. |  |  |  |  |
| SEG-04 | Kritikal | 1. Log masuk sebagai Ibu Pejabat. 2. Periksa menu Pengurusan Permohonan. 3. Buka URL permohonan baharu dan cuba simpan. | Menu Permohonan Baharu tidak dipaparkan. Halaman atau simpanan ditolak. Rekod permohonan baharu tidak tercipta oleh Ibu Pejabat. |  |  |  |  |
| SEG-05 | Tinggi | 1. Sebagai Negeri, buka Senarai Pengguna dan URL kemaskini emel jawatan. 2. Sebagai Ibu Pejabat, kemaskini satu emel jawatan ujian kepada alamat yang sah, kemudian kembalikan nilai asal. | Negeri tidak melihat dan tidak boleh menyimpan emel jawatan. Ibu Pejabat boleh menyimpan emel yang sah. Jejak merekod Kemaskini emel jawatan. Nilai asal dipulihkan selepas bukti diambil. |  |  |  |  |
| SEG-06 | Tinggi | 1. Sebagai Negeri A, hantar permohonan dengan daerah yang bukan daerah negeri itu. | Daerah ditolak dengan mesej daerah tidak sah bagi negeri yang dipilih. Permohonan tidak tersimpan dengan daerah negeri lain. |  |  |  |  |
| SEG-07 | Tinggi | 1. Sebagai Ibu Pejabat, buka laporan tanpa penapis. 2. Tapis kepada Negeri A. 3. Muat turun PDF bagi penapis itu. | Tanpa penapis, laporan boleh merangkumi semua negeri. Dengan penapis Negeri A, jadual dan PDF hanya mengandungi Negeri A. |  |  |  |  |
| SEG-08 | Kritikal | 1. Sebagai Negeri A, cari nama premis atau rujukan yang hanya wujud pada Negeri B. | Carian tidak memaparkan rekod Negeri B. |  |  |  |  |

## 10.6 Jejak audit

Jejak pada skrin Audit Trail membolehkan pegawai IT mengenal pasti pelaku, masa dan peristiwa. Rahsia pengesahan tidak direkod. Rekod jejak tidak diubah melalui skrin.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| AUD-01 | Tinggi | 1. Hasilkan satu sampel bagi log masuk, log keluar, kemaskini profil, reset kata laluan, ubah peranan, aktifkan akaun, nyahaktif akaun, daftar akaun dan kemaskini emel jawatan. 2. Buka Audit Trail pengguna berkenaan. | Setiap peristiwa muncul dengan tajuk Log masuk, Log keluar, Kemaskini profil, Reset kata laluan, Ubah peranan, Aktifkan akaun, Nyahaktif akaun, Akaun didaftarkan atau Kemaskini emel jawatan, mengikut tindakan yang dibuat. |  |  |  |  |
| AUD-02 | Tinggi | 1. Buka satu rekod jejak bagi tindakan pentadbir ke atas pengguna lain. 2. Jika akses baca pangkalan data ada, semak lajur alamat IP pada log sistem tanpa menyalin nilai ke lampiran awam. | Skrin menunjukkan tajuk, keterangan, tarikh, masa, sumber dan label Oleh beserta nama pelaku. Alamat IP disimpan pada log sistem. Ketiadaan alamat IP pada skrin direkod dalam keputusan sebenar. Kes gagal apabila tajuk, masa atau pelaku tindakan pentadbir tiada. |  |  |  |  |
| AUD-03 | Kritikal | 1. Semak keterangan jejak bagi log masuk, reset kata laluan dan cabaran dua faktor. 2. Semak log amaran login_failed pada pelayan. | Jejak dan log mengandungi identiti yang perlu untuk siasatan, dan tidak mengandungi kata laluan, token set semula, kod pengesah atau kod pemulihan. |  |  |  |  |
| AUD-04 | Kritikal | 1. Sebagai Negeri A, buka jejak audit pengguna Negeri B dan pengguna Ibu Pejabat. | Jejak tidak dibuka. Butiran aktiviti sasaran tidak dipaparkan. |  |  |  |  |
| AUD-05 | Sederhana | 1. Buat dua tindakan berurutan pada akaun yang sama. 2. Buka semula jejak. | Rekod terbaharu dipaparkan di atas. |  |  |  |  |
| AUD-06 | Tinggi | 1. Pada permohonan ujian yang dibenarkan, minta tarik semula dengan alasan sekurang-kurangnya 10 aksara. 2. Pada permohonan yang boleh dipadam, padam dengan alasan yang sah. 3. Buka jejak pelaku. | Jejak merekod permohonan tarik semula. Jika rekod dipadam, jejak merekod alasan padam. Alasan kurang daripada 10 aksara atau melebihi 2,000 aksara ditolak dan status tidak berubah. |  |  |  |  |
| AUD-07 | Tinggi | 1. Pada satu permohonan ujian, hantar ke Ibu Pejabat, muat naik draf, dan buat satu keputusan semakan. 2. Buka jejak pelaku bagi setiap tindakan. | Setiap tindakan meninggalkan keterangan Bahasa Melayu yang menyebut premis dan tindakan. Sumber bagi log sistem ialah Log Sistem. Tajuk skrin yang belum diterjemah, contoh Application sent to hq, direkod sebagai pemerhatian. Kes gagal apabila peristiwa itu sendiri tiada. |  |  |  |  |
| AUD-08 | Tinggi | 1. Pada halaman Audit Trail, cari fungsi ubah atau padam rekod jejak. | Skrin menyenaraikan rekod untuk dibaca. Tiada butang ubah atau padam rekod jejak. |  |  |  |  |

## 10.7 Kerahsiaan dokumen

Dokumen kontrak disimpan di luar capaian awam dan hanya dimuat turun oleh pengguna yang berkuasa.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| DOC-01 | Tinggi | 1. Muat naik PDF, JPG dan PNG di bawah 10 MB sebagai dokumen sokongan. 2. Cuba fail DOC atau EXE, fail melebihi 10 MB, dan borang tanpa fail. | Tiga jenis yang sah tersimpan dan boleh dibuka oleh pengguna berkuasa. Percubaan tidak sah ditolak. Rekod sedia ada tidak dipadam oleh cubaan yang gagal. |  |  |  |  |
| DOC-02 | Tinggi | 1. Pada penyediaan draf, muat naik PDF di bawah 40 MB. 2. Cuba PDF melebihi 40 MB dan fail bukan PDF. | PDF yang sah tersimpan. Fail besar atau bukan PDF ditolak dan status tidak berubah. |  |  |  |  |
| DOC-03 | Kritikal | 1. Selepas muat naik, cari fail di storage/app/private. 2. Cuba buka laluan yang sama melalui URL awam /storage. | Fail wujud di storan peribadi. URL awam tidak memuat turun fail itu. |  |  |  |  |
| DOC-04 | Kritikal | 1. Salin URL muat turun dokumen yang sah. 2. Buka URL itu tanpa sesi. 3. Buka URL yang sama sebagai negeri yang tidak memiliki kontrak. | Muat turun tanpa kuasa ditolak. Fail tidak dihantar. |  |  |  |  |
| DOC-05 | Tinggi | 1. Muat turun satu dokumen yang sah. 2. Semak header respons. | Respons mengandungi X-Content-Type-Options: nosniff. Pelayar tidak melaksanakan fail sebagai skrip halaman. |  |  |  |  |

## 10.8 Integriti aliran

Satu sampel aliran mencukupi untuk audit IT. Penerimaan bisnes bagi setiap cabang MOF, KE dan BPH kekal dalam skop UAT.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| INT-01 | Kritikal | 1. Sebagai Negeri, cuba luluskan semakan PUU, batalkan semakan PUU, buat pengesahan tandatangan Ibu Pejabat, dan tekan Selesai. 2. Sebagai Ibu Pejabat, cuba cipta permohonan baharu. | Tindakan di luar kuasa ditolak. Status permohonan tidak berubah. Ibu Pejabat tidak mencipta permohonan baharu. |  |  |  |  |
| INT-02 | Tinggi | 1. Pada permohonan baharu yang langkah tindakan atau dokumen wajib belum lengkap, tekan hantar ke Ibu Pejabat. | Penghantaran ditolak. Status kekal sebelum semakan Ibu Pejabat. |  |  |  |  |
| INT-03 | Tinggi | 1. Pada permohonan pindah atau lanjutan tanpa catatan, cuba hantar ke Ibu Pejabat. | Penghantaran ditolak sehingga catatan diisi. |  |  |  |  |
| INT-04 | Tinggi | 1. Pada permohonan lanjutan, cuba tukar nama premis, jenis bangunan, alamat, nama pemilik dan keluasan, kemudian simpan. | Medan yang dikunci kekal pada nilai kontrak asal. Kadar sewa, tarikh, daerah dan catatan masih boleh diubah. |  |  |  |  |
| INT-05 | Kritikal | 1. Pada pengesahan akhir, cuba tekan Selesai sebelum kedua-dua pengakuan Ibu Pejabat selepas mati setem ditanda. | Tindakan ditolak. Rekod belum masuk ke Senarai Kontrak Sewaan. |  |  |  |  |
| INT-06 | Tinggi | 1. Luluskan satu permohonan susulan ujian yang mempunyai kontrak asal. 2. Buka kontrak asal dan jejak. | Kontrak asal ditandakan diganti. Jejak merekod penggantian dan merujuk permohonan baharu. |  |  |  |  |
| INT-07 | Tinggi | 1. Cuba padam atau tarik semula dengan alasan 9 aksara. 2. Ulang dengan alasan melebihi 2,000 aksara. | Kedua-dua percubaan ditolak. Status permohonan tidak berubah. |  |  |  |  |

## 10.9 Konfigurasi keselamatan

Pegawai IT mengesahkan garis asas Seksyen 6 pada persekitaran yang diaudit. Nilai rahsia disahkan wujud, kemudian tidak disalin.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| CFG-01 | Kritikal | 1. Sahkan APP_NAME, APP_ENV, APP_DEBUG dan APP_URL. 2. Sahkan APP_KEY diisi. | Nama aplikasi ialah SPEKTRA. APP_DEBUG palsu. APP_URL sepadan URL yang dibuka. APP_KEY disahkan diisi dan tidak disalin ke dokumen. |  |  |  |  |
| CFG-02 | Kritikal | 1. Sahkan SESSION_DRIVER, SESSION_ENCRYPT, SESSION_LIFETIME, SESSION_SAME_SITE, SESSION_SECURE_COOKIE dan SESSION_HTTP_ONLY. | Pemacu database, penyulitan benar, hayat 120, SameSite lax dan HttpOnly benar. Pada HTTPS, kuki selamat benar. |  |  |  |  |
| CFG-03 | Kritikal | 1. Sahkan FILESYSTEM_DISK dan lokasi akar storan. | Cakera local dan akar storage/app/private. |  |  |  |  |
| CFG-04 | Tinggi | 1. Pada production, sahkan LOG_LEVEL. | Tahap log ialah warning atau lebih ketat. Pada persekitaran lain, nilai sebenar direkod. |  |  |  |  |
| CFG-05 | Kritikal | 1. Minta header respons halaman log masuk. | Respons mengandungi X-Content-Type-Options: nosniff, X-Frame-Options: SAMEORIGIN, Referrer-Policy: strict-origin-when-cross-origin, Permissions-Policy yang menutup kamera, mikrofon dan geolokasi, serta Content-Security-Policy. Polisi skrip tidak mengandungi unsafe-eval. Pada production, Strict-Transport-Security hadir. |  |  |  |  |
| CFG-06 | Kritikal | 1. Cuba buka /.env, /storage/logs dan satu laluan storan peribadi melalui URL awam. | Fail konfigurasi, log pelayan dan dokumen peribadi tidak dimuat turun. |  |  |  |  |
| CFG-07 | Kritikal | 1. Sahkan dengan pemilik sistem bahawa akaun pada persekitaran yang diaudit tidak lagi menggunakan kata laluan benih pembangunan. | Tiada akaun pada persekitaran yang diaudit yang masih menggunakan kata laluan benih. Kata laluan semasa memenuhi dasar Seksyen 6. |  |  |  |  |
| CFG-08 | Kritikal | 1. Buka URL yang diaudit. 2. Semak skema dan sijil. | Pada production, URL menggunakan HTTPS dan sijil sah untuk hos itu. Pada persekitaran lain, skema sebenar direkod. |  |  |  |  |
| CFG-09 | Tinggi | 1. Jika aplikasi berada di belakang proksi, sahkan senarai proksi dipercayai hanya mengandungi proksi yang dilantik. 2. Bandingkan alamat IP pada log sistem dengan alamat pelanggan yang dijangkakan. | Apabila proksi dipercayai dikonfigurasi, log merekod alamat IP pelanggan. Jika tiada proksi, keadaan itu direkod. |  |  |  |  |

## 10.10 Operasi, log dan pemakluman

Jadual, penghantar mel dan peti masuk menyokong kawalan operasi. Pegawai IT merekod keadaan sebenar tanpa menyalin kandungan emel yang sulit ke luar pasukan audit.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| OPS-01 | Tinggi | 1. Minta pentadbir pelayan menunjukkan senarai jadual atau konfigurasi penjadual. | Arahan contracts:send-expiry-reminders dijadualkan setiap hari pada 09:00, zon Asia/Kuala_Lumpur. |  |  |  |  |
| OPS-02 | Tinggi | 1. Semak satu contoh bagi setiap jalur jika data wujud: baki bawah 8 bulan hingga 5 bulan, bawah 5 bulan hingga 3 bulan, dan 3 bulan atau kurang. 2. Jika data tidak wujud, tandakan Disekat dan catat sebab. | Penerima jalur pertama dan kedua ialah pentadbir negeri terlibat. Jalur 3 bulan atau kurang juga menyalin Ibu Pejabat dan emel jawatan. Subjek sepadan dengan teks sistem di Seksyen 6. Peringatan berhenti apabila permohonan susulan dihantar ke Ibu Pejabat. |  |  |  |  |
| OPS-03 | Tinggi | 1. Sahkan sama ada MAIL_TEST_RECIPIENT diisi. | Jika diisi pada persekitaran ujian, pemakluman pergi ke alamat itu. Jika kosong pada production, emel jawatan Ketua Pengarah, TKPP, PKP dan KPP menerima salinan pemakluman Ibu Pejabat dan salinan amaran baki 3 bulan. Keadaan direkod. |  |  |  |  |
| OPS-04 | Tinggi | 1. Semak satu emel yang dijana, atau konfigurasi penghantar. | Nama penghantar dikenali sebagai SPEKTRA. Pada production, mailer bukan log. |  |  |  |  |
| OPS-05 | Tinggi | 1. Gerakkan satu permohonan supaya notifikasi terhasil. 2. Buka peti masuk Negeri yang terlibat dan Negeri yang tidak terlibat. | Notifikasi sampai kepada peranan yang perlu bertindak. Negeri yang tidak terlibat tidak melihat item itu. |  |  |  |  |
| OPS-06 | Tinggi | 1. Sahkan jadual jobs wujud. 2. Semak kerja gagal yang belum diurus. | Jadual wujud. Kerja gagal, jika ada, direkod sebagai dapatan bersama tarikh. |  |  |  |  |
| OPS-07 | Sederhana | 1. Bandingkan masa pelayan dengan cap masa jejak selepas satu log masuk. | Cap masa jejak sepadan dengan zon Asia/Kuala_Lumpur dalam toleransi jam pelayan yang direkod. |  |  |  |  |

## 10.11 Sandaran dan pulihan

Sandaran dibuktikan dengan tarikh dan lokasi. Pulihan diuji pada lokasi berasingan supaya data operasi tidak ditimpa.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| BAK-01 | Kritikal | 1. Minta bukti sandaran pangkalan data terkini: tarikh, lokasi dan status kerja. | Sandaran berjadual wujud. Sandaran terkini berjaya. Lokasi bukan folder web awam. |  |  |  |  |
| BAK-02 | Kritikal | 1. Minta bukti sandaran storage/app/private yang meliputi dokumen kontrak. | Sandaran fail wujud dan tarikhnya direkod. Lokasi bukan folder web awam. |  |  |  |  |
| BAK-03 | Tinggi | 1. Pulihkan sandaran terkini ke lokasi yang berasingan daripada pangkalan operasi. 2. Buka satu rekod pengguna dan satu dokumen. | Rekod dan fail boleh dibaca pada lokasi pulihan. Pangkalan operasi tidak ditimpa. |  |  |  |  |
| BAK-04 | Kritikal | 1. Semak siapa yang boleh membaca lokasi sandaran. | Akses terhad kepada pegawai IT atau pentadbir pelayan yang dilantik. Senarai jawatan direkod. Kandungan sandaran tidak disalin ke dokumen. |  |  |  |  |
| BAK-05 | Tinggi | 1. Pada salinan pulihan, sahkan salinan itu tidak disambungkan ke URL operasi. 2. Sahkan sesi dalam sandaran tidak diterima sebagai sesi semasa pada sistem yang diaudit. | URL operasi terus menggunakan pangkalan semasa. Sesi lama dalam sandaran tidak membuka sesi pada sistem yang diaudit. |  |  |  |  |

## 10.12 Laporan dan eksport

Eksport laporan kekal dalam kuasa Ibu Pejabat dan tidak membawa rahsia pengesahan.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| LAP-01 | Kritikal | 1. Cuba buka laporan dan PDF sebagai Negeri. 2. Ulang sebagai Ibu Pejabat. | Negeri ditolak. Ibu Pejabat boleh membuka laporan dan memuat turun PDF. |  |  |  |  |
| LAP-02 | Tinggi | 1. Buka PDF laporan. 2. Cari medan kata laluan, token, kod pemulihan atau nilai sesi. | PDF mengandungi data kontrak yang dibenarkan untuk peranan itu dan tidak mengandungi rahsia pengesahan. |  |  |  |  |
| LAP-03 | Tinggi | 1. Muat turun PDF dengan penapis Negeri A. 2. Bandingkan baris PDF dengan senarai pada skrin. | Baris PDF sepadan dengan penapis skrin. Negeri lain tidak terselit. |  |  |  |  |
| LAP-04 | Sederhana | 1. Selepas muat turun, semak jejak aplikasi dan log pelayan bagi tindakan muat turun laporan. | Pegawai IT merekod sama ada muat turun meninggalkan jejak. Jika tiada jejak khusus, catat pemerhatian pada log dapatan. Kes ini lulus apabila pemerhatian itu ditulis. |  |  |  |  |

## 11. Log dapatan

Satu dapatan bagi satu kegagalan atau pemerhatian. Nombor dapatan ditulis pada lajur keputusan sebenar kes berkaitan.

| No. | ID kes | Tahap | Dapatan | Kesan | Tindakan | Status | Tarikh tutup |
| --- | --- | --- | --- | --- | --- | --- | --- |
| 1 |  |  |  |  |  |  |  |
| 2 |  |  |  |  |  |  |  |
| 3 |  |  |  |  |  |  |  |
| 4 |  |  |  |  |  |  |  |
| 5 |  |  |  |  |  |  |  |
| 6 |  |  |  |  |  |  |  |
| 7 |  |  |  |  |  |  |  |
| 8 |  |  |  |  |  |  |  |
| 9 |  |  |  |  |  |  |  |
| 10 |  |  |  |  |  |  |  |
| 11 |  |  |  |  |  |  |  |
| 12 |  |  |  |  |  |  |  |

## 12. Ujian semula

| No. dapatan | ID kes | Binaan atau tarikh pembaikan | Keputusan ujian semula | Penguji dan tarikh |
| --- | --- | --- | --- | --- |
|  |  |  |  |  |
|  |  |  |  |  |
|  |  |  |  |  |
|  |  |  |  |  |
|  |  |  |  |  |
|  |  |  |  |  |

## 13. Rumusan keputusan

| Modul | Jumlah | Lulus | Gagal | Disekat | Tidak diuji |
| --- | --- | --- | --- | --- | --- |
| Persediaan audit | 4 |  |  |  |  |
| Kawalan akses dan sesi | 8 |  |  |  |  |
| Pengurusan akaun dan kata laluan | 12 |  |  |  |  |
| Pengesahan dua faktor | 6 |  |  |  |  |
| Pengasingan data dan kuasa | 8 |  |  |  |  |
| Jejak audit | 8 |  |  |  |  |
| Kerahsiaan dokumen | 5 |  |  |  |  |
| Integriti aliran | 7 |  |  |  |  |
| Konfigurasi keselamatan | 9 |  |  |  |  |
| Operasi, log dan pemakluman | 7 |  |  |  |  |
| Sandaran dan pulihan | 5 |  |  |  |  |
| Laporan dan eksport | 4 |  |  |  |  |
| Jumlah | 83 |  |  |  |  |

| Syarat keluar | Dipenuhi | Catatan |
| --- | --- | --- |
| Semua kes Kritikal lulus |  |  |
| Sekurang-kurangnya 95% kes Tinggi lulus |  |  |
| Tiada dapatan kritikal atau besar yang masih terbuka |  |  |
| Dapatan kecil yang diterima mempunyai tindakan susulan |  |  |
| Sandaran pangkalan data dan storan peribadi dibuktikan |  |  |

### Syor pegawai IT

Tandakan satu:

- Kawalan IT diterima.
- Kawalan IT diterima dengan syarat. Senaraikan syarat di bawah.
- Kawalan IT belum diterima.

Syarat atau catatan:

_______________________________________________________________________________

_______________________________________________________________________________

## 14. Perakuan

Saya mengesahkan skrip ini telah dilaksanakan pada persekitaran yang dinyatakan di Seksyen 1. Keputusan dalam dokumen ini menggambarkan kawalan yang disemak. Rahsia pengesahan, kata laluan dan fail konfigurasi tidak dilampirkan.

| Peranan | Nama | Jawatan | Tarikh | Tandatangan |
| --- | --- | --- | --- | --- |
| Pegawai IT pelaksana |  |  |  |  |
| Penyemak IT |  |  |  |  |
| Saksi pemilik sistem |  |  |  |  |
| Pelulus |  |  |  |  |

Klasifikasi: Terhad. Edaran terhad kepada Cawangan Pembangunan, Unit IT dan pegawai yang diberi kuasa.

