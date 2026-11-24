# Dokumen Pengujian Akhir

**SPEKTRA**  
Sistem Pemantauan Kontrak Sewaan Premis AADK  
Agensi Antidadah Kebangsaan

| Medan | Butiran |
| --- | --- |
| No. dokumen | AADK/CP/SPEKTRA/PA/001 |
| Versi | 1.0 |
| Tarikh | 23 November 2026 |
| Status | Untuk pelaksanaan pengujian akhir |
| Klasifikasi | Terhad |
| Pemilik sistem | Cawangan Pembangunan, AADK |
| Pelaksana | Pasukan pengujian akhir |
| Saksi | Wakil Cawangan Pembangunan dan wakil Pentadbir Negeri |

Dokumen ini ialah skrip pengujian akhir sebelum kuat kuasa. Penguji mengisi lajur **Keputusan sebenar**, **Status**, **Penguji** dan **Tarikh**. Sistem dicadangkan untuk dikuatkuasakan apabila kriteria keluar di Seksyen 8 dipenuhi dan borang perakuan di Seksyen 23 ditandatangani.

Pengujian akhir dijalankan pada persekitaran sasaran, iaitu persekitaran yang akan digunakan untuk operasi. Ia datang selepas Ujian Penerimaan Kilang (`AADK/CP/SPEKTRA/FAT/001`), Ujian Penerimaan Pengguna (`AADK/CP/SPEKTRA/UAT/001`) dan audit dalaman IT (`AADK/CP/SPEKTRA/IAD/001`). FAT membuktikan binaan di kilang. UAT membuktikan penerimaan bisnes. Audit IT membuktikan kawalan IT. Pengujian akhir mengesahkan binaan yang sama, pada persekitaran sasaran, masih melaksanakan fungsi, kawalan akses dan kesediaan operasi yang diperlukan untuk kuat kuasa.

## Kandungan

1. Kawalan dokumen
2. Tujuan
3. Rujukan
4. Skop
5. Peranan dan aliran yang diuji
6. Persekitaran sasaran
7. Kaedah dan klasifikasi
8. Kriteria masuk dan keluar
9. Prasyarat dokumen terdahulu
10. Ringkasan kes
11. Skrip kesediaan persekitaran
12. Skrip ujian fungsi
13. Kes hujung ke hujung
14. Semakan pelayar
15. Jejak pemakluman
16. Log kecacatan
17. Ujian semula
18. Matriks kebolehkesanan
19. Rumusan keputusan
20. Lampiran bukti
21. Konfigurasi yang disahkan
22. Senarai semak kuat kuasa
23. Perakuan pengujian akhir

---

## 1. Kawalan dokumen

| Peranan | Jawatan | Nama | Tarikh | Tandatangan |
| --- | --- | --- | --- | --- |
| Penyedia | Pasukan pengujian akhir | | | |
| Ketua pengujian akhir | Pasukan pengujian akhir | | | |
| Wakil Ibu Pejabat | Cawangan Pembangunan AADK | | | |
| Wakil Pentadbir Negeri | Pentadbir Negeri yang dilantik | | | |
| Pegawai IT | Unit IT | | | |
| Pelulus kuat kuasa | Pemilik sistem / Ketua Cawangan Pembangunan | | | |

### Sejarah pindaan

| Versi | Tarikh | Penyedia | Ringkasan perubahan |
| --- | --- | --- | --- |
| 1.0 | 23 November 2026 | Pasukan pengujian akhir | Terbitan pertama skrip pengujian akhir pada persekitaran sasaran, meliputi kesediaan operasi, fungsi SPEKTRA semasa dan tiga laluan hujung ke hujung. |

### Identiti binaan dan persekitaran

| Medan | Nilai |
| --- | --- |
| Versi atau tanda binaan | |
| Sama dengan binaan FAT (Ya/Tidak) | |
| No. perakuan FAT | |
| No. perakuan UAT | |
| No. perakuan audit IT | |
| URL persekitaran sasaran | |
| Tarikh mula | |
| Tarikh tamat | |

Binaan yang diuji mesti binaan yang sama dengan binaan yang lulus FAT. Jika binaan berbeza, pengujian akhir tidak dimulakan sehingga FAT diulang pada binaan baharu atau perbezaan itu diluluskan secara bertulis oleh pemilik sistem.

---

## 2. Tujuan

Pengujian akhir mengesahkan bahawa SPEKTRA pada persekitaran sasaran:

- dibuka melalui URL rasmi, dengan nama SPEKTRA, tanpa mendedahkan jejak ralat;
- menggunakan konfigurasi sesi, pengesahan dua faktor, mel, had muat naik dan storan peribadi yang diterima untuk kuat kuasa;
- menolak akses tanpa sesi, tanpa token borang, dan merentas negeri;
- melaksanakan aliran permohonan dari pendaftaran negeri sehingga kontrak berkuat kuasa, termasuk laluan MOF, KE dan BPH yang terpakai;
- menjana pemakluman, laporan PDF dan peringatan tamat kontrak yang betul;
- mempunyai penjadual, sandaran dan pulihan yang boleh dibuktikan;
- tidak lagi menggunakan kata laluan benih pembangunan.

Keputusan lulus bermaksud sistem dicadangkan untuk kuat kuasa. Kecacatan yang diterima dengan syarat ditulis dalam rumusan dan tidak dibiarkan tersirat.

## 3. Rujukan

| Dokumen | No. | Kegunaan dalam pengujian akhir |
| --- | --- | --- |
| Dokumen FAT SPEKTRA | AADK/CP/SPEKTRA/FAT/001 | Bukti binaan di kilang. Prasyarat masuk. Matriks di Seksyen 18 memaut kes. |
| Dokumen UAT SPEKTRA | AADK/CP/SPEKTRA/UAT/001 | Penerimaan bisnes. Kecacatan UAT yang masih terbuka dibawa ke Seksyen 16. |
| Skrip audit dalaman IT | AADK/CP/SPEKTRA/IAD/001 | Kawalan IT. Dapatan terbuka disenaraikan sebelum kuat kuasa. |
| Manual Pengguna SPEKTRA | AADK/CP/SPEKTRA/MANUAL/001 | Nama menu, butang dan status pada skrin. |

Jika label pada skrin berbeza daripada manual, label skrin binaan yang diuji ialah keputusan yang dijangka. Perbezaan itu direkod dalam keputusan sebenar dan, jika ia mengubah makna operasi, dibuka sebagai kecacatan.

## 4. Skop

### Dalam skop

- Identiti binaan, URL, HTTPS, halaman ralat dan aset hadapan pada persekitaran sasaran.
- Sesi, pengesahan dua faktor, mel, had muat naik, header keselamatan dan storan peribadi.
- Kata laluan serahan, akaun ujian dan akaun yang akan dikekalkan.
- Penjadual peringatan, sandaran dan satu pulihan pada lokasi berasingan.
- Log masuk, log keluar, lupa kata laluan, profil, penampilan, pengguna, jejak audit dan emel jawatan.
- Papan pemuka, permohonan baharu, pindah, lanjutan, langkah tindakan dan dokumen sokongan.
- Semakan Ibu Pejabat, draf perjanjian, semakan PUU, pengesahan tandatangan, mati setem dan pengesahan akhir Ibu Pejabat.
- Carian senarai, tarik semula, padam dan batal permohonan susulan.
- Senarai kontrak sewaan, laporan PDF, peti masuk dan lencana menu.
- Tiga kes hujung ke hujung pada persekitaran sasaran.
- Semakan Chrome, Edge dan satu paparan telefon.

### Luar skop

- Ujian beban dan ujian penembusan berbayar.
- Ujian automatik pemaju dan semakan gaya kod. Bukti itu kekal dalam FAT, kes REG-01 dan REG-02, bagi binaan yang sama.
- Proses luar sistem: kelulusan sebenar MOF, Kementerian Ekonomi, BPH, tandatangan fizikal TKPP, muat naik sebenar Sistem PROMIS dan urusan LHDN. Sistem merekod pengakuan bahawa tindakan itu telah dibuat.
- Perubahan dasar atau borang yang belum dibina dalam binaan yang diuji.
- Pengujian semula setiap kes teknikal kilang yang tidak mengubah kelakuan pada persekitaran sasaran. Kesediaan di Seksyen 11 menggantikan ulang pasang bersih.

## 5. Peranan dan aliran yang diuji

| Peranan sistem | Label pada skrin | Kuasa utama |
| --- | --- | --- |
| `admin_hq` | Ibu Pejabat | Semua negeri. Semak permohonan, lulus atau batalkan semakan PUU, sahkan tandatangan, sahkan penerimaan salinan mati setem dan muat naik Sistem PROMIS, urus status pengguna, emel jawatan dan laporan. |
| `admin_negeri` | Negeri | Negeri sendiri sahaja. Cipta dan kemaskini permohonan, lengkapkan langkah tindakan, muat naik dokumen dan draf perjanjian, sahkan pengakuan negeri, serta hantar mati setem kepada Ibu Pejabat. |

Pentadbir Negeri hanya melihat dan bertindak ke atas rekod negerinya. Pentadbir Negeri hanya boleh mendaftarkan pengguna Negeri bagi negeri yang sama. Ibu Pejabat boleh mendaftarkan pengguna Ibu Pejabat dan pengguna Negeri bagi mana-mana negeri. Ibu Pejabat tidak mempunyai menu **Permohonan Baharu**.

### Peringkat pada skrin semakan

1. **Permohonan Baru** — butiran premis dan langkah tindakan negeri.
2. **Semakan Ibu Pejabat** — senarai semak kelulusan, kemudian permohonan diteruskan ke penyediaan draf.
3. **Penyediaan Draf** — Negeri memuat naik draf perjanjian dalam format PDF.
4. **Dalam tindakan PUU** — Ibu Pejabat menekan **Luluskan** atau **Semak Semula**. Kedua-dua tindakan memerlukan PDF yang sah. Setiap muat naik negeri menambah pusingan Semakan PUU 1, Semakan PUU 2, dan seterusnya.
5. **Draf Lulus** — status senarai ialah *Draf Perjanjian dikembalikan ke Pentadbir Negeri untuk dapatkan Tandatangan Pemilik Premis*. Negeri mengesahkan empat perkara, kemudian mengembalikan dokumen.
6. **Pengesahan dan Tandatangan** — status senarai selepas dikembalikan ialah *Dokumen Perjanjian dikembalikan ke Cawangan Pembangunan untuk tindakan Tandatangan Penyewa*. Ibu Pejabat mengesahkan empat perkara.
7. **Mati Setem** — status senarai ialah *Menunggu Mati Setem*. Negeri mengesahkan tiga perkara, kemudian menekan **Hantar ke Ibu Pejabat**. Status menjadi *Menunggu pengesahan Ibu Pejabat*. Penunjuk kemajuan masih berada pada Mati Setem.
8. **Selesai** — Ibu Pejabat mengesahkan dua perkara pada skrin Tindakan Ibu Pejabat, kemudian menekan **Selesai**. Rekod masuk ke Senarai Kontrak Sewaan.

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

Permohonan pindah atau lanjutan memaparkan bahagian **Maklumat Kontrak Sedia Ada**: nama premis lama, kadar sewa lama, tarikh mula lama, tarikh akhir tempoh tawaran lama, baki bulan, jenis bangunan lama, pemilik lama dan alamat lama. Bahagian **Maklumat Baharu** diisi untuk permohonan susulan.

Bagi lanjutan, nama premis, jenis bangunan, alamat penuh, nama pemilik dan keluasan dikunci kepada nilai kontrak asal. Kadar sewa, tarikh, daerah dan catatan masih boleh diubah. Percubaan menukar medan yang dikunci tidak mengubah rekod.

Bagi pindah, butiran premis baharu boleh disimpan. Apabila kadar sewa baharu diisi, sistem memaparkan notis perbezaan: *Perbezaan < RM500* atau *Perbezaan ≥ RM500*, bersama kadar lama, kadar baharu, beza ringgit dan peratus.

Permohonan pindah atau lanjutan memerlukan catatan sebelum boleh dihantar ke Ibu Pejabat. Selepas permohonan susulan diluluskan Ibu Pejabat, kontrak asal ditandakan diganti.

Daerah pada borang permohonan wajib dan mesti wujud dalam senarai daerah AADK bagi negeri pentadbir. Jenis bangunan yang sah ialah Kompleks kerajaan, Komersial atau Rumah Kediaman.

### Senarai semak Ibu Pejabat

Setiap item merekod tarikh apabila ditanda. Item MOF dan KE memaparkan ruang catatan selepas ditanda.

| Item pada skrin | Peraturan |
| --- | --- |
| Kelulusan Pengurusan Tertinggi | Laluan kenaikan sewa. Dinyahaktifkan bagi lanjutan yang tidak memenuhi ambang di bawah. |
| Permohonan kepada Kementerian Kewangan (MOF) untuk permohonan Baru / Pindah / Pelanjutan dengan Kenaikan Sewa Lebih 10% @ minimum RM500 | Ruang catatan dipaparkan selepas ditanda. |
| Cawangan Pembangunan AADK menerima Kelulusan daripada MOF melalui KDN | Status *Permohonan ke MOF* kekal sehingga item ini ditanda, jika laluan kenaikan sewa terpakai. |
| Permohonan kepada Kementerian Ekonomi (KE) (jika keluasan melebihi 465 mps) — untuk permohonan perpindahan ruang pejabat / ruang pejabat baharu | Bagi pindah di bawah 465 meter persegi, item ini dan item ulasan KE dinyahaktifkan. |
| Cawangan Pembangunan AADK menerima ulasan daripada Kementerian Ekonomi (KE) | Status *Permohonan ke KE* kekal sehingga item ini ditanda, jika laluan KE terpakai. |
| Cawangan Pembangunan AADK mengemukakan permohonan Bahagian Hartanah (BPH) Jabatan Perdana Menteri untuk mendapatkan kelulusan | Ruang catatan tidak dipaparkan. |
| Cawangan Pembangunan AADK terima surat kelulusan daripada BPH | Status *Permohonan ke BPH* kekal sehingga item ini ditanda, selepas sekurang-kurangnya satu item lain ditanda. |

Status senarai semasa menunggu semakan Ibu Pejabat:

- *Minit Ceraian Kelulusan Kos* apabila laluan kenaikan sewa terpakai dan Kelulusan Pengurusan Tertinggi belum ditanda.
- *Permohonan ke MOF* selepas itu, sehingga kelulusan MOF melalui KDN ditanda.
- *Permohonan ke KE* bagi kategori Baru atau Pindah yang keluasannya melebihi 465 meter persegi, sehingga ulasan KE ditanda.
- *Semakan Ibu Pejabat* apabila tiada laluan di atas dan belum ada item yang ditanda.
- *Permohonan ke BPH* selepas sekurang-kurangnya satu item ditanda dan surat kelulusan BPH belum ditanda.

Laluan kenaikan sewa bagi Baru atau Pindah terpakai apabila ada kadar sewa kontrak asal yang lebih daripada sifar, kenaikan sekurang-kurangnya RM500, dan peratus kenaikan lebih daripada 10. Bagi Lanjutan, laluan ini terpakai apabila kenaikan sekurang-kurangnya RM499 dan sekurang-kurangnya 10 peratus. Jika ambang lanjutan tidak dipenuhi, Kelulusan Pengurusan Tertinggi, permohonan MOF dan penerimaan kelulusan MOF dinyahaktifkan. Lanjutan tidak melalui laluan KE.

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

### Pengakuan negeri untuk mati setem

- 3 Salinan Dokumen Perjanjian telah dimatikan setem di LHDN.
- 1 Salinan Dokumen Perjanjian telah diedar kepada Pemilik Premis.
- Salinan dihantar ke Cawangan Pembangunan melalui Pos/Kourier.

Butang pada peringkat ini ialah **Hantar ke Ibu Pejabat**, bukan penutup terus ke senarai kontrak.

### Pengakuan Ibu Pejabat selepas mati setem

- 1 Salinan Dokumen Perjanjian yang telah dimatikan setem diterima dari AADK Negeri.
- Perjanjian telah dimuat naik ke dalam Sistem PROMIS.

Kedua-dua pengakuan wajib sebelum **Selesai**. Tarikh diisi dengan tarikh semasa waktu Malaysia apabila kotak ditanda, dan boleh ditukar. Peraturan tarikh yang sama terpakai pada pengakuan negeri dan tandatangan.

### Peringatan tamat kontrak

Arahan `contracts:send-expiry-reminders` dijadualkan setiap hari pada jam 9.00 pagi, zon `Asia/Kuala_Lumpur`.

| Baki tempoh | Subjek | Penerima | Kekerapan | Berhenti apabila |
| --- | --- | --- | --- | --- |
| Bawah 8 bulan hingga 5 bulan | SPEKTRA: PERINGATAN PENGHANTARAN PERMOHONAN PELANJUTAN SEWAAN RUANG PEJABAT AADK UNTUK KONTRAK YANG AKAN TAMAT DALAM TEMPOH LAPAN (8) BULAN | Pentadbir negeri terlibat | 3 kali dalam 3 hari | Baki mencecah 5 bulan, 3 penghantaran selesai, atau permohonan susulan dihantar ke Ibu Pejabat |
| Bawah 5 bulan hingga 3 bulan | SPEKTRA: Peringatan Tegas Kontrak Bawah 5 Bulan | Pentadbir negeri terlibat | Setiap Isnin | Baki mencecah 3 bulan |
| 3 bulan atau kurang | SPEKTRA: AMARAN Kontrak Baki 3 Bulan | Pentadbir negeri, salinan kepada Ibu Pejabat dan emel jawatan | Setiap Isnin dan Rabu | Permohonan dihantar ke Ibu Pejabat |

Jika `MAIL_TEST_RECIPIENT` diisi, pemakluman aliran kerja dan peringatan dihantar ke alamat itu. Salinan emel jawatan dan salinan amaran tiga bulan tidak dihantar secara berasingan. Jika pemboleh ubah itu kosong, emel jawatan Ketua Pengarah, TKPP, PKP dan KPP menerima salinan pemakluman yang ditujukan kepada Ibu Pejabat, dan menerima salinan amaran baki 3 bulan.

Pada pengujian akhir, peti penerima mesti dipersetujui oleh pemilik sistem sebelum sebarang emel dihantar kepada pemegang jawatan sebenar. Sehingga kebenaran itu ditulis, penerima ujian digunakan.

## 6. Persekitaran sasaran

| Item | Nilai ujian |
| --- | --- |
| URL | |
| Pelayan aplikasi | |
| PHP | 8.2 atau lebih baharu. Binaan rujukan projek ialah PHP 8.4. |
| Pangkalan data | |
| Pelayar | Versi semasa Chrome dan Edge |
| Mel | Peti yang dipersetujui pemilik sistem. Pada production, mailer log tidak digunakan. |
| Pengesahan dua faktor | Wajib apabila `APP_ENV=production` dan `AUTH_MFA_REQUIRED` tidak ditetapkan. Dalam persekitaran lain, ikut nilai `AUTH_MFA_REQUIRED`. |
| Tarikh ujian | |
| Ketua pengujian | |

Akaun dan kata laluan dikeluarkan oleh ketua pengujian. Jangan tulis kata laluan dalam dokumen ini.

Sediakan sekurang-kurangnya:

- Satu akaun Ibu Pejabat.
- Satu akaun Negeri A.
- Satu akaun Negeri B, negeri yang berbeza.
- Satu akaun Negeri yang dinyahaktifkan semasa kes berkaitan.
- Aplikasi pengesah (Google Authenticator atau setara) jika pengesahan dua faktor dihidupkan.
- Fail contoh: PDF di bawah 10 MB, JPG, PNG, PDF draf di bawah 40 MB, PDF melebihi 40 MB, fail bukan PDF, dan fail dokumen sokongan melebihi 10 MB.
- Sekurang-kurangnya satu kontrak aktif fiksyen bagi negeri yang diuji, termasuk satu kontrak berbaki bawah 8 bulan jika peringatan diuji.

Data premis ialah data fiksyen, atau data yang pemilik sistem benarkan secara bertulis. Jangan guna premis sebenar yang sulit dalam tangkapan skrin yang akan diedarkan di luar pasukan pengujian.

### Garis asas konfigurasi kuat kuasa

| Tetapan | Nilai yang diterima |
| --- | --- |
| `APP_NAME` | SPEKTRA |
| `APP_ENV` | `production` untuk kuat kuasa. Persekitaran praoperasi yang diisytiharkan boleh digunakan jika pemilik sistem merekodnya. |
| `APP_DEBUG` | `false` |
| `APP_KEY` | Diisi dan tidak dikongsi dalam dokumen ini |
| `APP_URL` | URL sebenar persekitaran yang diuji. Pada `production`, hos yang dipercayai ialah hos URL ini sahaja. |
| `LOG_LEVEL` | `warning` atau lebih ketat pada production |
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

- Sahkan Seksyen 9 sebelum memulakan kes. Jalankan Seksyen 11 sebelum Seksyen 12. Kes hujung ke hujung di Seksyen 13 dijalankan selepas kes modul kritikal lulus. Semakan pelayar di Seksyen 14 hanya meliputi kombinasi yang dinyatakan.
- Satu penguji melaksanakan. Ketua pengujian mengesahkan kes kritikal. Wakil bisnes menyaksikan tiga kes hujung ke hujung. Pegawai IT menyaksikan kesediaan persekitaran dan kawalan akses.
- Status yang dibenarkan: **Lulus**, **Gagal**, **Disekat**, **Tidak diuji**.
- Jika gagal, cipta satu rekod dalam log kecacatan dan tulis nombor kecacatan pada kes ujian.
- Kes yang memerlukan arahan pelayan dijalankan oleh pegawai IT pada persekitaran sasaran, dengan kebenaran perubahan yang berkuat kuasa. Jangan jalankan migrasi semula, benih semula, atau perintah yang memadam data operasi.
- Bukti yang diterima: tangkapan skrin, petikan log tanpa kata laluan, subjek emel, nama fail PDF, dan output arahan. Jangan lampirkan fail `.env`, kata laluan, kod pemulihan atau dokumen premis sebenar.

### Klasifikasi kecacatan

| Tahap | Maksud | Contoh |
| --- | --- | --- |
| Kritikal | Aliran utama berhenti, data negeri lain terdedah, dokumen peribadi boleh dimuat turun tanpa kuasa, atau persekitaran sasaran tidak selamat untuk dibuka kepada pengguna. | Negeri B membuka permohonan Negeri A. |
| Besar | Fungsi penting salah, ada jalan sementara yang menyusahkan. | PDF laporan tidak dimuat turun, skrin laporan masih boleh dibaca. |
| Kecil | Pengesahan input atau mesej salah, kerja utama masih boleh disiapkan. | Mesej ralat tidak tepat. |
| Kosmetik | Paparan, ejaan atau jarak yang tidak mengubah keputusan. | Label terpotong pada telefon. |

Status kecacatan: Terbuka, Dibaiki, Diuji semula, Ditutup, Diterima dengan syarat.

## 8. Kriteria masuk dan keluar

### Kriteria masuk

- Binaan dan URL dikenal pasti dalam Seksyen 1, dan binaan itu sama dengan binaan FAT atau perbezaan telah diluluskan secara bertulis.
- Perakuan FAT, keputusan UAT dan keputusan audit IT tersedia, termasuk senarai kecacatan yang masih terbuka.
- Akaun, peti mel yang dipersetujui, pelayar dan aplikasi pengesah tersedia.
- Pangkalan data sasaran bukan pangkalan kilang yang masih mengandungi kata laluan benih.
- Ketua pengujian, wakil Ibu Pejabat, wakil Negeri dan pegawai IT telah dilantik.
- Pemilik sistem telah menyatakan sama ada emel boleh dihantar kepada pemegang jawatan sebenar.

### Kriteria keluar

Sistem dicadangkan untuk kuat kuasa apabila semua syarat berikut dipenuhi:

- PA-E2E-01, PA-E2E-02 dan PA-E2E-03 lulus.
- Semua kes berkeutamaan **Kritikal** lulus.
- Sekurang-kurangnya 95% kes berkeutamaan **Tinggi** lulus.
- Tiada kecacatan kritikal atau besar yang masih terbuka.
- Kecacatan kecil yang diterima mempunyai tindakan susulan bertulis dan tarikh sasaran.
- Dapatan audit IT yang masih terbuka mempunyai pelan tutup atau penerimaan bertulis pemilik sistem.
- Senarai semak kuat kuasa di Seksyen 22 ditandakan lengkap.
- Borang perakuan ditandatangani oleh ketua pengujian, wakil Negeri, wakil Ibu Pejabat, pegawai IT dan pelulus kuat kuasa.

## 9. Prasyarat dokumen terdahulu

Penguji mencatat rujukan. Pengujian akhir tidak menutup semula setiap kes kilang.

| Prasyarat | Rujukan | Dipenuhi (Ya/Tidak) | Catatan |
| --- | --- | --- | --- |
| FAT versi 1.1 atau lebih baharu lulus pada binaan yang sama | AADK/CP/SPEKTRA/FAT/001 | | |
| REG-01 lulus pada binaan yang sama | Output ujian automatik FAT | | |
| UAT kritikal lulus, atau syarat UAT diterima secara bertulis | AADK/CP/SPEKTRA/UAT/001 | | |
| Audit IT lulus, atau dapatan terbuka disenaraikan di bawah | AADK/CP/SPEKTRA/IAD/001 | | |
| Manual pengguna diserahkan kepada penguji dan wakil bisnes | AADK/CP/SPEKTRA/MANUAL/001 | | |
| Kecacatan FAT, UAT dan audit yang masih terbuka disalin ke Seksyen 16 | Log kecacatan terdahulu | | |

Dapatan terbuka yang dibawa masuk:

| Sumber | No. dapatan | Tahap | Ringkasan | Kesan pada pengujian akhir |
| --- | --- | --- | --- | --- |
| | | | | |
| | | | | |
| | | | | |

## 10. Ringkasan kes

| Modul | ID | Bilangan | Keutamaan utama |
| --- | --- | --- | --- |
| Kesediaan persekitaran | PA-SED-01 hingga PA-SED-15 | 15 | Kritikal |
| Pengesahan | PA-AUTH-01 hingga PA-AUTH-07 | 7 | Kritikal |
| Pengesahan dua faktor | PA-MFA-01 hingga PA-MFA-05 | 5 | Kritikal |
| Profil dan penampilan | PA-PROF-01 hingga PA-PROF-03 | 3 | Sederhana |
| Pengguna | PA-USER-01 hingga PA-USER-08 | 8 | Tinggi |
| Papan pemuka | PA-DASH-01 hingga PA-DASH-04 | 4 | Tinggi |
| Permohonan | PA-APP-01 hingga PA-APP-06 | 6 | Kritikal |
| Langkah tindakan | PA-PROC-01 hingga PA-PROC-05 | 5 | Kritikal |
| Dokumen sokongan | PA-DOC-01 hingga PA-DOC-04 | 4 | Tinggi |
| Semakan Ibu Pejabat | PA-HQ-01 hingga PA-HQ-04 | 4 | Kritikal |
| Draf dan PUU | PA-DRAF-01 hingga PA-DRAF-04 | 4 | Kritikal |
| Pengesahan negeri | PA-ACK-01 hingga PA-ACK-02 | 2 | Kritikal |
| Tandatangan Ibu Pejabat | PA-SIGN-01 | 1 | Kritikal |
| Mati setem dan pengesahan akhir | PA-STEM-01 hingga PA-STEM-02 | 2 | Kritikal |
| Pindah dan lanjutan | PA-FOL-01 hingga PA-FOL-04 | 4 | Kritikal |
| Tarik semula dan padam | PA-WD-01 hingga PA-WD-06 | 6 | Tinggi |
| Kontrak sewaan | PA-KON-01 hingga PA-KON-04 | 4 | Tinggi |
| Laporan | PA-LAP-01 hingga PA-LAP-05 | 5 | Tinggi |
| Peti masuk | PA-NOT-01 hingga PA-NOT-03 | 3 | Tinggi |
| Kawalan akses skrin | PA-SEC-01 hingga PA-SEC-03 | 3 | Kritikal |
| Carian senarai | PA-LIST-01 | 1 | Tinggi |
| Hujung ke hujung | PA-E2E-01 hingga PA-E2E-03 | 3 | Kritikal |
| **Jumlah** | | **99** | |

Semakan pelayar di Seksyen 14 ialah bukti tambahan dan tidak dikira dalam 99 kes.

## 11. Skrip kesediaan persekitaran

Lajur status diisi **Lulus**, **Gagal**, **Disekat** atau **Tidak diuji**.

| ID | Keutamaan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| PA-SED-01 | Kritikal | 1. Buka URL sasaran. 2. Sahkan sambungan menggunakan HTTPS. 3. Semak amaran sijil pelayar. | Halaman log masuk dipaparkan. Nama SPEKTRA dipaparkan. Sijil sah untuk hos itu dan pelayar tidak memberi amaran. | | | | |
| PA-SED-02 | Kritikal | 1. Sahkan `APP_DEBUG=false`. 2. Buka satu URL yang tidak wujud, dengan dan tanpa sesi. | Halaman ralat tidak memaparkan jejak kod, pertanyaan SQL atau nilai konfigurasi. | | | | |
| PA-SED-03 | Kritikal | 1. Pada binaan sasaran, jalankan `php artisan migrate:status`. | Tiada migrasi berstatus belum dijalankan. Arahan tidak diulang sebagai `migrate` atau `migrate:fresh`. | | | | |
| PA-SED-04 | Kritikal | 1. Buka log masuk dan Papan Pemuka. 2. Semak log pelayar. | Halaman tidak memaparkan ralat Vite dan tidak memuatkan `cdn.tailwindcss.com`. | | | | |
| PA-SED-05 | Kritikal | 1. Muat naik satu dokumen sokongan pada permohonan ujian. 2. Cari fail dalam `storage/app/private`. 3. Cuba buka laluan yang sama melalui URL awam `/storage`. | Fail wujud di bawah storan peribadi. URL awam tidak memuat turun fail itu. | | | | |
| PA-SED-06 | Kritikal | 1. Sahkan `SESSION_DRIVER=database`, `SESSION_ENCRYPT=true` dan hayat 120 minit. 2. Log masuk dan semak jadual sesi. 3. Sahkan jadual `jobs` dan `cache` wujud. | Sesi baharu wujud. Nilai sesi yang disimpan tidak memaparkan kata laluan dalam teks jelas. Tiga jadual wujud. Pada HTTPS, kuki sesi mempunyai bendera Secure dan `SameSite` ialah Lax. | | | | |
| PA-SED-07 | Kritikal | 1. Baca `APP_ENV` dan `AUTH_MFA_REQUIRED`. 2. Log masuk akaun yang belum mengesahkan dua faktor. | Jika persekitaran production dan pemboleh ubah tidak ditetapkan, pengesahan dua faktor wajib. Jika pemboleh ubah ditetapkan, kelakuan mengikut nilai benar atau palsu itu. Keputusan dan nilai tetapan direkod. | | | | |
| PA-SED-08 | Kritikal | 1. Sahkan penghantar emel menggunakan nama SPEKTRA. 2. Sahkan mailer. 3. Rekod sama ada `MAIL_TEST_RECIPIENT` diisi, tanpa menyalin alamat ke dalam dokumen jika alamat itu sulit. | Pada production, mailer bukan `log`. Set semula kata laluan tidak dihalang oleh mailer log. Dasar penerima di Seksyen 5 dipatuhi. | | | | |
| PA-SED-09 | Tinggi | 1. Baca `upload_max_filesize` dan `post_max_size`. | Kedua-duanya sekurang-kurangnya 40 MB supaya kes draf 40 MB boleh diuji. Jika had pelayan lebih rendah, had berkesan direkod dan kes saiz penuh ditanda Disekat dengan sebab. | | | | |
| PA-SED-10 | Kritikal | 1. Sahkan setiap akaun yang kekal pada persekitaran sasaran telah menerima kata laluan baharu. 2. Sahkan tiada akaun serahan yang masih menggunakan kata laluan benih pembangunan. | Kata laluan baharu memenuhi dasar Seksyen 6. Kata laluan tidak ditulis dalam dokumen ini. | | | | |
| PA-SED-11 | Kritikal | 1. Minta header respons halaman log masuk. | Respons mengandungi `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy` yang menutup kamera, mikrofon dan geolokasi, serta `Content-Security-Policy`. Polisi skrip mengandungi nonce dan tidak mengandungi `unsafe-eval`. | | | | |
| PA-SED-12 | Kritikal | 1. Jalankan `php artisan schedule:list`. 2. Sahkan penjadual sistem operasi memanggil `php artisan schedule:run` setiap minit. | Arahan `contracts:send-expiry-reminders` tersenarai setiap hari pada 09:00, zon Asia/Kuala_Lumpur. Penjadual sistem operasi dipasang. Jika belum dipasang, kes gagal atau diterima hanya dengan tindakan susulan bertulis sebelum kuat kuasa. | | | | |
| PA-SED-13 | Kritikal | 1. Ambil sandaran pangkalan data sasaran dan salinan `storage/app/private`. 2. Pulihkan kedua-duanya ke lokasi kosong yang lain, bukan ke pangkalan hidup. 3. Arahkan satu salinan semakan ke pulihan itu. 4. Log masuk dan buka satu permohonan serta satu dokumen. | Sandaran wujud dan lokasi direkod. Pulihan boleh log masuk. Permohonan dan dokumen yang disandarkan boleh dibuka oleh peranan yang berkuasa. Pangkalan hidup tidak ditimpa. | | | | |
| PA-SED-14 | Kritikal | 1. Hantar borang log masuk tanpa token borang. | Permintaan ditolak. Sesi kerja tidak terbuka. | | | | |
| PA-SED-15 | Kritikal | 1. Tanpa log masuk, minta Papan Pemuka, Status Permohonan, Kontrak Sewaan, Pengguna dan Laporan. | Setiap permintaan membawa pelawat ke log masuk. Badan respons tidak mengandungi data permohonan. | | | | |

## 12. Skrip ujian fungsi

### 12.1 Pengesahan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-AUTH-01 | Kritikal | Semua | 1. Buka URL sistem. 2. Masukkan emel dan kata laluan akaun aktif. 3. Hantar. | Halaman log masuk dipaparkan untuk pelawat. Log masuk berjaya membuka Papan Pemuka. Nama pengguna kelihatan. | | | | |
| PA-AUTH-02 | Kritikal | Semua | 1. Masukkan emel yang sah dan kata laluan yang salah. 2. Hantar. | Log masuk ditolak. Mesej ralat dipaparkan. Papan pemuka tidak terbuka. | | | | |
| PA-AUTH-03 | Kritikal | Ibu Pejabat, kemudian akaun yang dinyahaktif | 1. Ibu Pejabat nyahaktifkan akaun Negeri. 2. Cuba log masuk dengan akaun itu. 3. Aktifkan semula selepas bukti diambil. | Akaun berstatus Nyahaktif tidak boleh masuk. Selepas diaktifkan semula, akaun boleh masuk. | | | | |
| PA-AUTH-04 | Tinggi | Semua | 1. Log masuk. 2. Pilih log keluar. 3. Tekan butang undur pelayar dan cuba buka Papan Pemuka. | Sesi ditutup. Halaman dalaman tidak boleh digunakan selepas log keluar. | | | | |
| PA-AUTH-05 | Tinggi | Semua | 1. Pada log masuk, pilih lupa kata laluan. 2. Hantar emel akaun yang wujud dan emel yang tidak wujud. 3. Buka pautan set semula. 4. Tetapkan kata laluan baharu yang sah dan log masuk. | Kedua-dua permintaan memaparkan mesej yang tidak mendedahkan sama ada emel wujud. Pautan akaun yang wujud membuka borang set semula. Kata laluan baharu boleh digunakan. Kata laluan lama ditolak. Subjek emel ialah *SPEKTRA: Set Semula Kata Laluan*. | | | | |
| PA-AUTH-06 | Tinggi | Semua | 1. Pada set semula, cuba kata laluan tanpa huruf besar, tanpa nombor, atau kurang daripada 8 aksara. 2. Cuba dua nilai yang tidak sama. | Setiap percubaan ditolak. Kata laluan tidak berubah. | | | | |
| PA-AUTH-07 | Sederhana | Semua | 1. Cuba log masuk gagal berulang kali dalam satu minit bagi emel dan alamat IP yang sama. | Selepas 10 percubaan, sistem menolak percubaan seterusnya. Akaun yang sah masih boleh masuk selepas had berehat. | | | | |

### 12.2 Pengesahan dua faktor

Jalankan modul ini apabila pengesahan dua faktor dihidupkan. Jika ia dimatikan pada persekitaran yang diisytiharkan, tandakan kes sebagai **Tidak diuji** dan catat rujukan tetapan. Pada production tanpa tetapan yang mematikannya, modul ini wajib dan tidak boleh ditanda Tidak diuji.

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-MFA-01 | Kritikal | Pengguna baharu | 1. Log masuk akaun yang belum mengesahkan dua faktor. | Sistem membawa pengguna ke persediaan pengesahan. Menu kerja tidak boleh digunakan sebelum pengesahan selesai. | | | | |
| PA-MFA-02 | Kritikal | Semua | 1. Imbas kod QR pada halaman persediaan. 2. Jika imbasan gagal, buka kunci persediaan manual. 3. Simpan lapan kod pemulihan di tempat selamat ujian, di luar dokumen ini. 4. Masukkan kod 6 digit semasa. | Kod QR dipaparkan sebagai imej dalam halaman, tanpa permintaan ke perkhidmatan luar. Kunci manual boleh dibuka. Kod yang sah mengesahkan akaun. Kod pemulihan dipaparkan sekali. Log masuk seterusnya meminta kod pengesah. | | | | |
| PA-MFA-03 | Tinggi | Semua | 1. Pada cabaran log masuk, masukkan kod yang salah. 2. Ulang lebih daripada 5 kali dalam satu minit. | Mesej kod tidak sah dipaparkan. Sesi kerja tidak terbuka. Permintaan selepas had ditolak. | | | | |
| PA-MFA-04 | Kritikal | Semua | 1. Pada cabaran log masuk, gunakan satu kod pemulihan yang disimpan. | Kod pemulihan yang sah membuka sesi. Kod itu tidak lagi boleh digunakan. | | | | |
| PA-MFA-05 | Tinggi | Semua | 1. Guna semula kod pemulihan yang sama. | Kod yang telah digunakan ditolak. | | | | |

### 12.3 Profil dan penampilan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-PROF-01 | Sederhana | Semua | 1. Buka Profil. 2. Tukar nama. 3. Simpan. | Nama baharu tersimpan dan dipaparkan selepas muat semula. | | | | |
| PA-PROF-02 | Tinggi | Semua | 1. Cuba tukar emel atau kata laluan tanpa kata laluan semasa. 2. Ulang dengan kata laluan semasa yang betul dan kata laluan baharu yang sah. | Perubahan emel atau kata laluan ditolak tanpa kata laluan semasa. Perubahan berjaya apabila kata laluan semasa sah. | | | | |
| PA-PROF-03 | Sederhana | Semua | 1. Buka Penampilan. 2. Tukar pilihan paparan yang disediakan. 3. Beralih ke halaman lain dan kembali. | Pilihan kekal pada pelayar yang sama selepas navigasi. | | | | |

### 12.4 Pengurusan pengguna

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-USER-01 | Tinggi | Ibu Pejabat | 1. Buka Pendaftaran Pengguna. 2. Daftar satu pengguna Negeri bagi negeri yang dipilih, dengan kata laluan sah. 3. Daftar satu pengguna Ibu Pejabat. | Kedua-dua akaun wujud dalam Senarai Pengguna. Pengguna Negeri mempunyai negeri. Pengguna Ibu Pejabat tidak diikat pada satu negeri. Akaun baharu boleh log masuk. | | | | |
| PA-USER-02 | Kritikal | Negeri | 1. Log masuk sebagai Negeri A. 2. Buka pendaftaran. 3. Cuba daftar pengguna. | Peranan yang dibenarkan ialah Negeri sahaja. Negeri dikunci kepada negeri pentadbir. Pengguna negeri lain atau Ibu Pejabat tidak boleh didaftarkan. | | | | |
| PA-USER-03 | Tinggi | Ibu Pejabat | 1. Daftar pengguna dengan emel yang sudah wujud. | Pendaftaran ditolak. Mesej menyatakan emel telah didaftarkan. | | | | |
| PA-USER-04 | Kritikal | Ibu Pejabat | 1. Pada Senarai Pengguna, nyahaktifkan pengguna Negeri. 2. Aktifkan semula. | Status bertukar antara Aktif dan Nyahaktif. Hanya Ibu Pejabat melihat tindakan ini. | | | | |
| PA-USER-05 | Tinggi | Ibu Pejabat | 1. Cuba nyahaktifkan akaun yang sedang digunakan sendiri. | Sistem menolak. Akaun sendiri kekal aktif. | | | | |
| PA-USER-06 | Tinggi | Ibu Pejabat dan Negeri | 1. Buat satu tindakan yang direkod, contoh kemaskini profil. 2. Buka jejak audit pengguna itu. | Jejak memaparkan tindakan yang berkaitan pengguna. Negeri tidak boleh membuka jejak pengguna negeri lain. Jejak tidak memaparkan kata laluan atau kod pengesahan. | | | | |
| PA-USER-07 | Tinggi | Ibu Pejabat | 1. Buka Senarai Pengguna. 2. Kemaskini emel jawatan Ketua Pengarah, TKPP, PKP dan KPP kepada peti yang dipersetujui. 3. Simpan. 4. Muat semula. | Empat jawatan tersimpan. Emel kosong atau format tidak sah ditolak. | | | | |
| PA-USER-08 | Kritikal | Negeri | 1. Sebagai Negeri A, buka Senarai Pengguna. | Senarai hanya mengandungi pengguna Negeri bagi negeri sendiri. Pengguna Ibu Pejabat dan negeri lain tidak dipaparkan. | | | | |

### 12.5 Papan pemuka

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-DASH-01 | Tinggi | Ibu Pejabat | 1. Buka Papan Pemuka. 2. Bandingkan kiraan permohonan, kontrak aktif dan pecahan status dengan senarai berkaitan. 3. Klik satu baris Status Tindakan. | Kad dan pecahan status dipaparkan untuk semua negeri. Angka sepadan dengan senarai selepas penapis yang sama. Klik baris membuka senarai projek pada status itu. | | | | |
| PA-DASH-02 | Kritikal | Negeri | 1. Buka Papan Pemuka sebagai Negeri A. 2. Ulang sebagai Negeri B. | Setiap papan pemuka hanya mengira premis negeri sendiri. | | | | |
| PA-DASH-03 | Tinggi | Semua pentadbir | 1. Semak senarai status tindakan. 2. Klik setiap baris yang mempunyai data. | Label berikut dipaparkan: Belum Terima Permohonan, Semakan Cawangan Pembangunan, Minit Ceraian Kelulusan Kos, Pertimbangan MOF, Pertimbangan KE, Pertimbangan BPH/SUPS, Sedia Draf Perjanjian, Semakan Cawangan Pembangunan Sebelum ke PUU, Semakan PUU, Kuiri PUU, Pindaan Berdasarkan Syor PUU dan Tandatangan Pemilik Premis, Tandatangan Penyewa (AADK), Tindakan Mati Setem. | | | | |
| PA-DASH-04 | Tinggi | Semua pentadbir | 1. Semak bahagian kontrak yang akan tamat. | Kontrak aktif dibahagi kepada baki 3 bulan atau kurang, baki bawah 8 bulan tetapi lebih 3 bulan, dan baki lebih 8 bulan. Senarai amaran paling hampir tamat dipaparkan dahulu. | | | | |

### 12.6 Permohonan baharu

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-APP-01 | Kritikal | Negeri | 1. Buka Permohonan Baharu. 2. Pilih kategori Baru. 3. Isi nama premis, alamat, daerah negeri sendiri, pemilik, jenis bangunan, kadar sewa, keluasan, tarikh mula dan tarikh akhir tawaran. 4. Simpan. | Rekod tercipta di Status Permohonan. Negeri pada rekod sama dengan negeri pentadbir. Daerah tersimpan. Jenis bangunan yang sah ialah Kompleks kerajaan, Komersial atau Rumah Kediaman. | | | | |
| PA-APP-02 | Tinggi | Negeri | 1. Hantar borang kosong. 2. Isi tarikh akhir lebih awal daripada tarikh mula. 3. Isi kadar sewa negatif. | Setiap kes dipaparkan mesej pengesahan. Rekod tidak rosak. | | | | |
| PA-APP-03 | Kritikal | Negeri | 1. Cuba hantar nilai negeri lain. | Permohonan untuk negeri lain ditolak. | | | | |
| PA-APP-04 | Sederhana | Negeri | 1. Buka permohonan yang belum dihantar. 2. Ubah satu medan dan tunggu autosimpan. 3. Muat semula halaman. | Nilai terakhir yang sah kekal. | | | | |
| PA-APP-05 | Tinggi | Ibu Pejabat | 1. Cari menu Permohonan Baharu. 2. Cuba buka URL borang permohonan secara terus. | Menu cipta permohonan tidak dipaparkan. Akses terus ditolak. | | | | |
| PA-APP-06 | Kritikal | Negeri | 1. Hantar daerah yang bukan daerah AADK bagi negeri pentadbir. 2. Kosongkan daerah. | Kedua-dua percubaan ditolak. Mesej meminta daerah atau menyatakan daerah tidak sah bagi negeri yang dipilih. | | | | |

### 12.7 Langkah tindakan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-PROC-01 | Kritikal | Negeri | 1. Buka permohonan kategori Baru. 2. Pada langkah awal, tekan Simpan dan pastikan langkah tidak beralih. 3. Tekan Seterusnya. 4. Pada Surat kepada agensi, pastikan hanya Simpan dipaparkan. 5. Lengkapkan Surat niat, Borang JRP dan Surat kepada agensi. | Hanya langkah 2, 3 dan 5 dipaparkan. Simpan ada pada setiap langkah. Seterusnya disembunyikan pada langkah terakhir. Butang Hantar ke Ibu Pejabat boleh digunakan selepas ketiga-tiga langkah selesai. | | | | |
| PA-PROC-02 | Kritikal | Negeri | 1. Buka permohonan kategori Lanjutan. 2. Semak langkah yang dipaparkan. | Hanya Notis kepada pemilik premis, Borang JRP dan Surat kepada agensi dipaparkan. | | | | |
| PA-PROC-03 | Kritikal | Negeri | 1. Pada permohonan yang langkahnya belum lengkap, tekan Hantar ke Ibu Pejabat dan sahkan Ya, Hantar. | Penghantaran ditolak. Status kekal menunggu langkah tindakan. | | | | |
| PA-PROC-04 | Tinggi | Negeri | 1. Pada Surat kepada agensi, tanda surat JPPH tanpa tarikh. 2. Tanda Pelan Lantai dan Gambar Bangunan Terkini tanpa tarikh. 3. Lengkapkan tarikh bagi enam item yang memerlukannya. | Item bertarikh tidak boleh disimpan sebagai lengkap tanpa tarikh. Pelan lantai dan gambar bangunan tidak memerlukan tarikh. | | | | |
| PA-PROC-05 | Kritikal | Negeri | 1. Pada permohonan pindah atau lanjutan yang langkahnya sudah lengkap, kosongkan catatan. 2. Cuba hantar ke Ibu Pejabat. 3. Isi catatan dan hantar semula. | Penghantaran ditolak selagi catatan kosong. Selepas catatan diisi dan Ya, Hantar disahkan, status senarai mengikut peraturan Seksyen 5. Permohonan baharu tanpa kontrak induk dan berkeluasan 465 meter persegi atau kurang menjadi Semakan Ibu Pejabat. | | | | |

### 12.8 Dokumen sokongan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-DOC-01 | Tinggi | Negeri | 1. Muat naik satu PDF sebagai surat tawaran, satu JPG sebagai gambar premis, dan satu PNG sebagai lain-lain. 2. Buka semula setiap fail. | Tiga fail tersimpan di bawah kontrak yang betul dan boleh dibuka oleh pengguna yang berkuasa. | | | | |
| PA-DOC-02 | Tinggi | Negeri | 1. Cuba muat naik fail DOC atau EXE. 2. Cuba fail yang melebihi had saiz. 3. Hantar tanpa memilih fail. | Setiap percubaan ditolak. Mesej menyebut jenis yang dibenarkan atau had saiz. | | | | |
| PA-DOC-03 | Tinggi | Ibu Pejabat | 1. Buka permohonan yang mempunyai dokumen. 2. Muat turun dokumen. | Ibu Pejabat boleh membaca dokumen permohonan yang dihantar. | | | | |
| PA-DOC-04 | Kritikal | Negeri | 1. Sebagai Negeri B, cuba buka pautan dokumen Negeri A. | Akses ditolak. Fail tidak dimuat turun. | | | | |

### 12.9 Semakan Ibu Pejabat

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-HQ-01 | Kritikal | Ibu Pejabat | 1. Buka permohonan baharu tanpa kontrak induk, berkeluasan 465 meter persegi atau kurang. 2. Sahkan status senarai ialah Semakan Ibu Pejabat. 3. Tanda item permohonan BPH bersama tarikh. 4. Isi catatan pada item MOF atau KE selepas item itu ditanda, jika item itu aktif. 5. Lengkapkan senarai semak yang aktif dan sahkan permohonan. | Butiran negeri dipaparkan sebagai baca sahaja. Selepas item BPH ditanda dan kelulusan BPH belum ditanda, status menjadi Permohonan ke BPH. Selepas sah, status menjadi penyediaan draf perjanjian. Emel *SPEKTRA: PERMOHONAN DILULUSKAN OLEH BAHAGIAN PENGURUSAN HARTANAH, JABATAN PERDANA MENTERI* wujud. | | | | |
| PA-HQ-02 | Kritikal | Ibu Pejabat | 1. Buka pindah berkeluasan di bawah 465 meter persegi. 2. Buka Baru atau Pindah yang melebihi 465 meter persegi. 3. Buka lanjutan dengan kenaikan bawah 10 peratus atau bawah RM499. 4. Buka lanjutan dengan kenaikan sekurang-kurangnya 10 peratus dan sekurang-kurangnya RM499. | Pada pindah di bawah 465, dua item KE dinyahaktifkan. Pada keluasan melebihi 465 bagi Baru atau Pindah, status ialah Permohonan ke KE sehingga ulasan KE ditanda. Pada lanjutan di bawah ambang, Kelulusan Pengurusan Tertinggi, permohonan MOF dan penerimaan kelulusan MOF dinyahaktifkan, dan status awal ialah Semakan Ibu Pejabat. Pada lanjutan yang memenuhi ambang, status ialah Minit Ceraian Kelulusan Kos sehingga Kelulusan Pengurusan Tertinggi ditanda, kemudian Permohonan ke MOF sehingga kelulusan MOF ditanda. | | | | |
| PA-HQ-03 | Tinggi | Negeri | 1. Semasa status masih menunggu semakan Ibu Pejabat, cuba sahkan permohonan. | Tindakan kelulusan Ibu Pejabat tidak tersedia kepada Negeri. | | | | |
| PA-HQ-04 | Sederhana | Ibu Pejabat | 1. Tanda sebahagian senarai semak. 2. Tunggu autosimpan. 3. Keluar dan buka semula. | Tanda, tarikh dan catatan yang telah disimpan kekal. | | | | |

### 12.10 Draf perjanjian dan semakan PUU

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-DRAF-01 | Kritikal | Negeri | 1. Pada peringkat Penyediaan Draf, muat naik PDF draf perjanjian di bawah 40 MB. 2. Cuba fail lebih 40 MB dan fail bukan PDF. | Muat naik PDF yang sah berjaya. Status menjadi Semakan PUU. Fail boleh dibuka dari storan peribadi. Fail melebihi 40 MB atau bukan PDF ditolak. | | | | |
| PA-DRAF-02 | Kritikal | Ibu Pejabat | 1. Buka permohonan berstatus Semakan PUU. 2. Tekan Luluskan. 3. Muat naik PDF di bawah 40 MB dan sahkan Ya, Luluskan. 4. Pada permohonan lain, cuba tanpa fail, fail bukan PDF, atau fail melebihi 40 MB. | Luluskan tanpa PDF yang sah ditolak. Selepas berjaya, status menjadi Draf Perjanjian dikembalikan ke Pentadbir Negeri untuk dapatkan Tandatangan Pemilik Premis. Sejarah memaparkan dokumen lulus PUU. Subjek emel ialah *SPEKTRA: DRAF PERJANJIAN DILULUSKAN OLEH PENASIHAT UNDANG-UNDANG*. | | | | |
| PA-DRAF-03 | Kritikal | Ibu Pejabat, kemudian Negeri | 1. Pada pusingan semakan yang lain, tekan Semak Semula. 2. Muat naik PDF pindaan di bawah 40 MB dan sahkan Ya, Semak Semula. 3. Cuba tanpa fail, fail bukan PDF, atau fail melebihi 40 MB. 4. Negeri memuat turun dokumen pindaan, kemudian muat naik PDF baharu. | Semak Semula tanpa PDF yang sah ditolak. Selepas berjaya, status menjadi Kuiri PUU. Subjek emel ialah *SPEKTRA: KUIRI OLEH PENASIHAT UNDANG-UNDANG*. Sejarah memaparkan dokumen pindaan. Muat naik baharu menjadi Semakan PUU seterusnya, bukan Semakan PUU 1 semula. | | | | |
| PA-DRAF-04 | Tinggi | Negeri | 1. Pada penyediaan draf, cuba muat naik JPG atau DOCX. | Hanya PDF diterima untuk draf perjanjian. | | | | |

### 12.11 Pengesahan negeri selepas draf lulus

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-ACK-01 | Kritikal | Negeri | 1. Pada Draf Lulus, tanda kurang daripada empat pengakuan dalam Seksyen 5. 2. Cuba kembalikan kepada Ibu Pejabat. 3. Tanda keempat-empat pengakuan dan tukar salah satu tarikh. | Penghantaran disekat selagi ada pengakuan yang belum ditanda. Tarikh diisi secara automatik apabila ditanda dan tarikh yang ditukar kekal. | | | | |
| PA-ACK-02 | Kritikal | Negeri | 1. Selepas empat pengakuan lengkap, kembalikan dokumen. | Status menjadi Dokumen Perjanjian dikembalikan ke Cawangan Pembangunan untuk tindakan Tandatangan Penyewa. Emel *SPEKTRA: PERJANJIAN UNTUK DITANDATANGANI PENGURUSAN AADK* dihantar. Tindakan ini tidak tersedia kepada Ibu Pejabat. | | | | |

### 12.12 Tandatangan Ibu Pejabat

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-SIGN-01 | Kritikal | Ibu Pejabat | 1. Buka permohonan yang dikembalikan negeri. 2. Cuba hantar untuk mati setem sebelum empat pengakuan lengkap. 3. Tanda empat pengakuan dalam Seksyen 5, semak tarikh di sebelah kanan, dan sahkan. | Penghantaran disekat sehingga empat pengakuan lengkap. Tarikh diisi dengan tarikh tanda dan boleh diubah. Selepas sah, status menjadi Menunggu Mati Setem. Emel *SPEKTRA: PERJANJIAN UNTUK TINDAKAN MATI SETEM* dihantar. | | | | |

### 12.13 Mati setem dan pengesahan akhir

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-STEM-01 | Kritikal | Negeri | 1. Pada Mati Setem, cuba hantar dengan kurang daripada tiga pengakuan. 2. Tanda ketiga-tiga pengakuan dalam Seksyen 5, semak tarikh, dan tekan Hantar ke Ibu Pejabat. 3. Buka Senarai Kontrak Sewaan. | Pengakuan yang tidak lengkap tidak mengubah peringkat. Selepas ketiga-tiganya ditanda, status menjadi Menunggu pengesahan Ibu Pejabat. Rekod belum masuk Senarai Kontrak Sewaan. Emel *SPEKTRA: PERJANJIAN SELESAI DIMATI SETEM* sampai kepada Ibu Pejabat. | | | | |
| PA-STEM-02 | Kritikal | Ibu Pejabat | 1. Buka permohonan yang menunggu pengesahan Ibu Pejabat. 2. Cuba tekan Selesai dengan kurang daripada dua pengakuan. 3. Tanda penerimaan salinan daripada negeri dan muat naik Sistem PROMIS, tukar salah satu tarikh, dan tekan Selesai. 4. Buka Senarai Kontrak Sewaan. | Selesai disekat selagi ada pengakuan yang belum ditanda. Selepas kedua-duanya ditanda, permohonan hilang daripada senarai kerja aktif dan muncul sebagai kontrak sewaan. Emel *SPEKTRA: PERJANJIAN SELESAI DAN TELAH DIMASUKKAN KE DALAM SENARAI KONTRAK SEWAAN* sampai kepada negeri. | | | | |

### 12.14 Pindah dan lanjutan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-FOL-01 | Kritikal | Negeri | 1. Pada kontrak aktif negeri sendiri, cipta permohonan Lanjutan. 2. Semak Maklumat Kontrak Sedia Ada. 3. Cuba tukar nama premis, jenis bangunan, alamat, pemilik dan keluasan. 4. Tukar kadar sewa, daerah dan tarikh, kemudian simpan. | Borang memaparkan maklumat lama dan Maklumat Baharu. Medan identiti premis dan keluasan kekal seperti kontrak asal selepas simpan. Kadar sewa, daerah dan tarikh baharu tersimpan. Notis perbezaan sewa dipaparkan. Langkah yang dipaparkan ialah notis, JRP dan surat agensi. | | | | |
| PA-FOL-02 | Kritikal | Negeri | 1. Pada kontrak aktif yang lain, cipta permohonan Pindah. 2. Kemaskini nama premis, alamat, daerah, pemilik, kadar sewa, keluasan dan tarikh kepada nilai baharu. | Kategori ialah Pindah. Butiran baharu boleh disimpan. Notis perbezaan sewa memaparkan kadar lama, kadar baharu, beza ringgit dan peratus. Langkah yang dipaparkan ialah surat niat, JRP dan surat agensi. | | | | |
| PA-FOL-03 | Tinggi | Negeri | 1. Pada permohonan susulan yang masih di langkah tindakan, pilih Batal dan Kembali ke Kontrak Asal. | Permohonan susulan dibatalkan. Kontrak asal kembali ke senarai kontrak. Butang Padam biasa tidak menggantikan tindakan ini. | | | | |
| PA-FOL-04 | Kritikal | Ibu Pejabat | 1. Hantar satu permohonan lanjutan atau pindah yang lengkap. 2. Ibu Pejabat sahkan permohonan. 3. Semak kontrak asal. | Kontrak asal ditandakan diganti oleh permohonan baharu. Ia tidak lagi dikira sebagai kontrak aktif semasa. | | | | |

### 12.15 Tarik semula dan padam

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-WD-01 | Tinggi | Negeri | 1. Pada permohonan berstatus menunggu semakan Ibu Pejabat, mohon tarik semula dengan alasan sekurang-kurangnya 10 aksara. | Permohonan ditanda menunggu kelulusan tarik semula. Ibu Pejabat melihat tindakan lulus atau tolak. | | | | |
| PA-WD-02 | Sederhana | Negeri | 1. Hantar alasan tarik semula kurang daripada 10 aksara, dan satu alasan melebihi 2,000 aksara. | Kedua-dua permintaan ditolak dan mesej pengesahan dipaparkan. | | | | |
| PA-WD-03 | Tinggi | Ibu Pejabat | 1. Luluskan tarik semula. | Permohonan kembali kepada pentadbir negeri untuk dikemaskini. Status semakan Ibu Pejabat berakhir. | | | | |
| PA-WD-04 | Tinggi | Ibu Pejabat | 1. Pada permohonan tarik semula yang lain, tolak permintaan. | Permohonan kekal dalam senarai semakan Ibu Pejabat. Status memaparkan permohonan tarik semula ditolak. | | | | |
| PA-WD-05 | Tinggi | Negeri | 1. Pada permohonan biasa yang belum dihantar, pilih Padam. 2. Isi alasan sekurang-kurangnya 10 aksara dan sahkan. | Permohonan keluar daripada senarai aktif. Ibu Pejabat boleh melihat tarikh dan alasan pada sejarah padam. | | | | |
| PA-WD-06 | Kritikal | Negeri | 1. Cari butang Padam pada permohonan yang sedang disemak Ibu Pejabat dan pada permohonan di peringkat draf perjanjian. | Butang Padam tidak dipaparkan. Permohonan tersebut tidak boleh dipadam. | | | | |

### 12.16 Kontrak sewaan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-KON-01 | Tinggi | Semua pentadbir | 1. Buka Senarai Kontrak Sewaan. 2. Semak tab aktif dan sejarah. | Tab aktif memaparkan kontrak yang masih berkuat kuasa. Sejarah memaparkan kontrak yang telah diganti atau tamat, mengikut data ujian. | | | | |
| PA-KON-02 | Sederhana | Semua pentadbir | 1. Cari menggunakan nama premis dan nama negeri. | Hasil carian sepadan. Carian kosong memulangkan senarai asal. | | | | |
| PA-KON-03 | Tinggi | Semua pentadbir | 1. Buka butiran satu kontrak. | Butiran premis, daerah, tempoh, kategori dan dokumen yang berkaitan dipaparkan. | | | | |
| PA-KON-04 | Kritikal | Negeri | 1. Sebagai Negeri A, cuba buka butiran kontrak Negeri B melalui senarai atau pautan terus. | Kontrak negeri lain tidak tersenarai dan pautan terus ditolak. | | | | |

### 12.17 Laporan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-LAP-01 | Tinggi | Ibu Pejabat | 1. Buka Jana Laporan. 2. Jana setiap jenis: Ringkasan Keseluruhan, Senarai Permohonan, Senarai Kontrak Aktif, Laporan Kontrak mengikut Negeri, dan Laporan Status Sewaan. | Kelima-lima jenis dipaparkan. Laporan status sewaan menggunakan tarikh semasa pada tajuk dan mengumpul rekod mengikut bahagian BPH, PUU, MOF, KE, Ibu Pejabat dan PTJ apabila data wujud. Dalam Tindakan PTJ, baki bulan paling rendah berada di atas. | | | | |
| PA-LAP-02 | Tinggi | Ibu Pejabat | 1. Jana laporan yang sama tanpa negeri, kemudian dengan satu negeri. | Penapis negeri mengecilkan hasil kepada negeri itu sahaja. Tanpa penapis, semua negeri termasuk. | | | | |
| PA-LAP-03 | Tinggi | Ibu Pejabat | 1. Muat turun PDF bagi setiap jenis laporan. 2. Buka fail. | Fail bermula dengan `laporan-spektra-` dan tarikh. Halaman ialah landskap. Laporan Kontrak mengikut Negeri dan Laporan Status Sewaan menggunakan kertas A3. Jenis lain menggunakan A4. Kandungan sepadan dengan skrin. | | | | |
| PA-LAP-04 | Kritikal | Negeri | 1. Semak menu sisi. 2. Cuba buka URL laporan dan URL PDF secara terus. | Menu Jana Laporan tidak dipaparkan. Akses terus ditolak. | | | | |
| PA-LAP-05 | Tinggi | Ibu Pejabat | 1. Jana Laporan Kontrak mengikut Negeri dengan satu daerah. 2. Ulang tanpa daerah. 3. Cuba daerah yang bukan daerah negeri yang dipilih. | Penapis daerah mengecilkan hasil kepada daerah itu. Tanpa daerah, semua daerah negeri yang dipilih termasuk. Daerah yang tidak sah ditolak. | | | | |

### 12.18 Peti masuk dan lencana

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-NOT-01 | Tinggi | Semua pentadbir | 1. Jalankan satu tindakan yang menjana pemakluman, contoh hantar ke Ibu Pejabat. 2. Buka ikon peti masuk dan halaman Peti Masuk. 3. Tanda semua sebagai dibaca. | Item baharu muncul untuk peranan yang perlu bertindak. Tanda dibaca mengosongkan kiraan belum baca. | | | | |
| PA-NOT-02 | Sederhana | Semua pentadbir | 1. Perhatikan lencana pada Pengurusan Permohonan dan Kontrak Sewaan. 2. Selesaikan atau buka item yang belum dilihat. | Lencana memaparkan bilangan item yang menunggu. Bilangan berkurang selepas item dilihat atau selesai, selepas segar semula menu. | | | | |
| PA-NOT-03 | Tinggi | Pentadbir sistem bersama Negeri | 1. Dengan kebenaran perubahan, sediakan kontrak fiksyen pada tiga julat baki di Seksyen 5. 2. Jalankan arahan peringatan pada hari yang berjadual bagi setiap julat, atau tunggu slot 09:00. 3. Hantar permohonan susulan bagi kontrak amaran 3 bulan dan jalankan arahan sekali lagi. | Subjek, penerima dan peraturan berhenti sepadan dengan Seksyen 5. Selepas permohonan dihantar ke Ibu Pejabat, peringatan kontrak itu berhenti. | | | | |

### 12.19 Kawalan akses skrin

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-SEC-01 | Kritikal | Pelawat | 1. Tanpa log masuk, buka terus Papan Pemuka, Status Permohonan, Kontrak Sewaan, Pengguna dan Laporan. | Setiap halaman membawa pengguna ke log masuk. Data tidak dipaparkan. | | | | |
| PA-SEC-02 | Kritikal | Negeri | 1. Salin pautan semakan permohonan Negeri A. 2. Buka pautan itu semasa log masuk sebagai Negeri B. | Permohonan tidak dibuka. Akses ditolak atau halaman tidak dijumpai, tanpa mendedahkan butiran. | | | | |
| PA-SEC-03 | Tinggi | Semua | 1. Pada telefon, buka menu. 2. Semak halaman utama setiap peranan. 3. Kecilkan tetingkap desktop. | Menu boleh dibuka dan ditutup. Tajuk, jadual dan borang utama boleh digunakan. Tindakan penting tidak terpotong. | | | | |

### 12.20 Carian senarai permohonan

| ID | Keutamaan | Peranan | Langkah | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-LIST-01 | Tinggi | Semua pentadbir | 1. Pada Status Permohonan, cari nama premis, negeri, pemilik dan kategori. 2. Kosongkan carian. 3. Sebagai Negeri A, cari premis Negeri B. | Hasil sepadan dengan teks yang ditaip, termasuk label status yang dipaparkan. Carian kosong memulangkan senarai asal. Negeri A tidak melihat rekod Negeri B. | | | | |

## 13. Kes hujung ke hujung

Gunakan premis fiksyen yang baharu bagi setiap kes. Catat nama premis dalam keputusan sebenar. Wakil Ibu Pejabat dan wakil Negeri menyaksikan ketiga-tiga kes.

| ID | Keutamaan | Senario | Langkah ringkas | Keputusan dijangka | Keputusan sebenar | Status | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PA-E2E-01 | Kritikal | Permohonan baharu hingga kontrak aktif | 1. Negeri mencipta permohonan Baru, memilih daerah negeri sendiri, dan memuat naik dokumen sokongan. 2. Negeri melengkapkan surat niat, JRP dan surat agensi. 3. Negeri menekan Hantar ke Ibu Pejabat dan mengesahkan Ya, Hantar. 4. Ibu Pejabat menyemak senarai semak dan meneruskan ke draf. 5. Negeri memuat naik PDF draf. 6. Ibu Pejabat meluluskan Semakan PUU 1 dengan PDF. 7. Negeri mengesahkan empat pengakuan dan mengembalikan dokumen. 8. Ibu Pejabat mengesahkan empat pengakuan tandatangan. 9. Negeri mengesahkan tiga pengakuan mati setem dan menekan Hantar ke Ibu Pejabat. 10. Ibu Pejabat mengesahkan penerimaan salinan dan muat naik Sistem PROMIS, kemudian menekan Selesai. | Satu kontrak baharu muncul dalam Senarai Kontrak Sewaan negeri tersebut dan dalam laporan Ibu Pejabat hanya selepas langkah 10. Papan pemuka kedua-dua peranan berubah dengan betul. Pemakluman penghantaran, kelulusan ke draf, muat naik draf, lulus PUU, tandatangan, mati setem dan selesai wujud. Fail draf berada di storan peribadi. | | | | |
| PA-E2E-02 | Kritikal | Lanjutan kontrak | 1. Negeri mencipta Lanjutan daripada kontrak aktif. 2. Negeri mengisi catatan, notis pemilik, JRP dan surat agensi tanpa mengubah identiti premis yang dikunci. 3. Ulang langkah semakan hingga pengesahan akhir Ibu Pejabat seperti PA-E2E-01, dengan langkah lanjutan. | Kontrak asal diganti selepas kelulusan Ibu Pejabat. Nama premis, alamat, pemilik, jenis bangunan dan keluasan kekal seperti kontrak asal. Kontrak lanjutan yang selesai menjadi kontrak aktif. | | | | |
| PA-E2E-03 | Kritikal | Pindah premis, termasuk satu pembatalan semakan PUU | 1. Negeri mencipta Pindah dan mengisi butiran premis baharu, termasuk daerah. 2. Lengkapkan surat niat, JRP, surat agensi dan catatan, kemudian hantar. 3. Ibu Pejabat meneruskan ke draf. 4. Negeri memuat naik draf pertama. 5. Ibu Pejabat menekan Semak Semula dengan PDF pindaan. 6. Negeri memuat naik draf kedua. 7. Ibu Pejabat meluluskan Semakan PUU 2 dengan PDF. 8. Siapkan pengesahan negeri, tandatangan Ibu Pejabat, mati setem negeri dan pengesahan akhir Ibu Pejabat. | Pusingan kedua direkod sebagai Semakan PUU 2. Kontrak pindah yang selesai aktif. Kontrak premis lama tidak lagi aktif. | | | | |

## 14. Semakan pelayar

Jalankan PA-AUTH-01, PA-APP-01 dan paparan Senarai Kontrak Sewaan pada setiap kombinasi. Kes lain kekal pada pelayar desktop utama.

| Kombinasi | Pelayar dan saiz | PA-AUTH-01 | PA-APP-01 | Senarai kontrak | Catatan | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Desktop utama | Chrome, lebar biasa | | | | | | |
| Desktop kedua | Edge, lebar biasa | | | | | | |
| Telefon | Chrome, lebar lebih kurang 390 piksel | | | | | | |

## 15. Jejak pemakluman

Penguji menanda apabila emel atau rekod log wujud. Alamat sebenar jawatan tidak ditulis di sini.

| Peristiwa | Subjek yang dijangka | Bukti | Status |
| --- | --- | --- | --- |
| Permohonan dihantar ke Ibu Pejabat | SPEKTRA: PERMOHONAN BAHARU UNTUK SEMAKAN IBU PEJABAT | | |
| Salinan penghantaran kepada negeri | SPEKTRA: PERMOHONAN DIHANTAR KE IBU PEJABAT UNTUK SEMAKAN | | |
| Ibu Pejabat meneruskan ke draf | SPEKTRA: PERMOHONAN DILULUSKAN OLEH BAHAGIAN PENGURUSAN HARTANAH, JABATAN PERDANA MENTERI | | |
| Draf dimuat naik kepada Ibu Pejabat | SPEKTRA: DRAF PERJANJIAN UNTUK SEMAKAN PENASIHAT UNDANG-UNDANG | | |
| Draf dimuat naik kepada negeri | SPEKTRA: DRAF PERJANJIAN DIHANTAR UNTUK SEMAKAN PENASIHAT UNDANG-UNDANG | | |
| Semakan PUU diluluskan | SPEKTRA: DRAF PERJANJIAN DILULUSKAN OLEH PENASIHAT UNDANG-UNDANG | | |
| Semakan PUU dikembalikan untuk pindaan | SPEKTRA: KUIRI OLEH PENASIHAT UNDANG-UNDANG | | |
| Dokumen dikembalikan ke Cawangan Pembangunan | SPEKTRA: PERJANJIAN UNTUK DITANDATANGANI PENGURUSAN AADK | | |
| Dihantar untuk mati setem | SPEKTRA: PERJANJIAN UNTUK TINDAKAN MATI SETEM | | |
| Negeri menghantar selepas mati setem | SPEKTRA: PERJANJIAN SELESAI DIMATI SETEM | | |
| Ibu Pejabat menyelesaikan permohonan | SPEKTRA: PERJANJIAN SELESAI DAN TELAH DIMASUKKAN KE DALAM SENARAI KONTRAK SEWAAN | | |
| Peringatan bawah 8 bulan | Subjek penuh dalam Seksyen 5 | | |
| Peringatan bawah 5 bulan | SPEKTRA: Peringatan Tegas Kontrak Bawah 5 Bulan | | |
| Amaran baki 3 bulan | SPEKTRA: AMARAN Kontrak Baki 3 Bulan | | |
| Set semula kata laluan | SPEKTRA: Set Semula Kata Laluan | | |

## 16. Log kecacatan

Salin kecacatan terbuka daripada FAT, UAT dan audit IT ke jadual ini sebelum ujian bermula. Tambah kecacatan baharu yang ditemui pada persekitaran sasaran.

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

## 17. Ujian semula

| No. kecacatan | Versi baiki | ID kes diuji semula | Keputusan | Penguji | Tarikh |
| --- | --- | --- | --- | --- | --- |
| | | | | | |
| | | | | | |
| | | | | | |

## 18. Matriks kebolehkesanan

Kes PA-SED mengesahkan persekitaran sasaran. Kes fungsi mengulang bukti skrin FAT dan UAT pada persekitaran itu. Audit IT kekal rujukan kawalan; pengujian akhir tidak menggantikan skrip IAD.

| Kes pengujian akhir | Kes FAT | Kes UAT | Catatan |
| --- | --- | --- | --- |
| PA-SED-01 hingga PA-SED-15 | ENV, CFG, SEC, FILE dan BAK yang berkaitan | — | Diulang pada persekitaran sasaran, bukan sebagai pasang bersih. |
| PA-AUTH-01 hingga PA-AUTH-07 | F-AUTH-01 hingga F-AUTH-07 | AUTH-01 hingga AUTH-07 | |
| PA-MFA-01 hingga PA-MFA-05 | F-MFA-01 hingga F-MFA-05 | MFA-01 hingga MFA-05 | Wajib pada production. |
| PA-PROF-01 hingga PA-PROF-03 | F-PROF-01 hingga F-PROF-03 | PROF-01 hingga PROF-03 | |
| PA-USER-01 hingga PA-USER-08 | F-USER-01 hingga F-USER-08 | USER-01 hingga USER-08 | |
| PA-DASH-01 hingga PA-DASH-04 | F-DASH-01 hingga F-DASH-04 | DASH-01 hingga DASH-04 | |
| PA-APP-01 hingga PA-APP-06 | F-APP-01 hingga F-APP-06 | APP-01 hingga APP-05 | PA-APP-06 dan F-APP-06 mewajibkan daerah. UAT 1.0 tidak mempunyai kes daerah berasingan. |
| PA-PROC-01 hingga PA-PROC-05 | F-PROC-01 hingga F-PROC-05 | PROC-01 hingga PROC-05 | |
| PA-DOC-01 hingga PA-DOC-04 | F-DOC-01 hingga F-DOC-04 | DOC-01 hingga DOC-04 | |
| PA-HQ-01 hingga PA-HQ-04 | F-HQ-01 hingga F-HQ-04 | HQ-01 hingga HQ-04 | Laluan MOF, KE dan BPH mengikut Seksyen 5. |
| PA-DRAF-01 hingga PA-DRAF-04 | F-DRAF-01 hingga F-DRAF-04 | DRAF-01 hingga DRAF-04 | Lulus dan Semak Semula memerlukan PDF. |
| PA-ACK-01 hingga PA-ACK-02 | F-ACK-01 hingga F-ACK-02 | ACK-01 hingga ACK-02 | |
| PA-SIGN-01 | F-SIGN-01 | SIGN-01 | Empat pengakuan Ibu Pejabat mengikut Seksyen 5. |
| PA-STEM-01 | F-STEM-01 | STEM-01 | Negeri menghantar ke Ibu Pejabat. Rekod belum menjadi kontrak. |
| PA-STEM-02 | F-STEM-02 | — | Pengesahan akhir Ibu Pejabat dan Sistem PROMIS. Belum ada dalam UAT 1.0. |
| PA-FOL-01 hingga PA-FOL-04 | F-FOL-01 hingga F-FOL-04 | FOL-01 hingga FOL-04 | |
| PA-WD-01 hingga PA-WD-06 | F-WD-01 hingga F-WD-06 | WD-01 hingga WD-06 | |
| PA-KON-01 hingga PA-KON-04 | F-KON-01 hingga F-KON-04 | KON-01 hingga KON-04 | |
| PA-LAP-01 hingga PA-LAP-05 | F-LAP-01 hingga F-LAP-05 | LAP-01 hingga LAP-04 | Penapis daerah belum ada dalam UAT 1.0. |
| PA-NOT-01 hingga PA-NOT-03 | F-NOT-01 hingga F-NOT-03 | NOT-01 hingga NOT-03 | |
| PA-SEC-01 hingga PA-SEC-03 | F-SEC-01 hingga F-SEC-03 | SEC-01 hingga SEC-03 | |
| PA-LIST-01 | F-LIST-01 | — | Carian senarai permohonan. Belum ada dalam UAT 1.0. |
| PA-E2E-01 hingga PA-E2E-03 | F-E2E-01 hingga F-E2E-03 | E2E-01 hingga E2E-03 | Diulang pada persekitaran sasaran dengan saksi bisnes. |

## 19. Rumusan keputusan

| Perkara | Bilangan |
| --- | --- |
| Jumlah kes | 99 |
| Lulus | |
| Gagal | |
| Disekat | |
| Tidak diuji | |
| Kecacatan kritikal terbuka | |
| Kecacatan besar terbuka | |
| Kecacatan kecil diterima | |

| Syarat keluar | Dipenuhi (Ya/Tidak) | Catatan |
| --- | --- | --- |
| PA-E2E-01, PA-E2E-02 dan PA-E2E-03 lulus | | |
| Semua kes kritikal lulus | | |
| Sekurang-kurangnya 95% kes tinggi lulus | | |
| Tiada kecacatan kritikal atau besar terbuka | | |
| Dapatan audit IT terbuka mempunyai pelan atau penerimaan bertulis | | |
| Senarai semak kuat kuasa lengkap | | |

Cadangan ketua pengujian akhir:

- [ ] Layak dikuatkuasakan
- [ ] Layak dikuatkuasakan dengan syarat
- [ ] Tidak layak dikuatkuasakan

Syarat atau pengecualian:

_______________________________________________________________________________

_______________________________________________________________________________

## 20. Lampiran bukti

| Bukti | Nama fail | Tarikh | Disimpan oleh |
| --- | --- | --- | --- |
| Perakuan FAT binaan yang sama | | | |
| Keputusan UAT dan senarai syarat | | | |
| Keputusan audit IT | | | |
| Output `php artisan migrate:status` | | | |
| Output `php artisan schedule:list` | | | |
| Bukti penjadual sistem operasi | | | |
| Header respons log masuk | | | |
| Rekod sandaran dan pulihan, tanpa data premis | | | |
| Sampel PDF laporan | | | |
| Sampel emel atau petikan log mel tanpa rahsia | | | |
| Tangkapan skrin PA-E2E-01 hingga PA-E2E-03 | | | |

## 21. Konfigurasi yang disahkan

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
| Kebenaran hantar kepada jawatan sebenar (Ya/Tidak) | | | |
| `upload_max_filesize` | | | |
| `post_max_size` | | | |
| Penjadual `schedule:run` terpasang (Ya/Tidak) | | | |
| Lokasi sandaran terakhir | | | |
| Tarikh sandaran terakhir | | | |

## 22. Senarai semak kuat kuasa

| Item | Selesai (Ya/Tidak) | Catatan |
| --- | --- | --- |
| Binaan sama dengan binaan yang lulus FAT, atau perbezaan diluluskan secara bertulis | | |
| `APP_DEBUG=false` | | |
| URL rasmi menggunakan HTTPS dan sijil sah | | |
| Kata laluan benih pembangunan telah diganti | | |
| Pengesahan dua faktor ditetapkan mengikut keputusan pemilik sistem | | |
| Mel production bukan mailer log | | |
| Penerima emel dipersetujui. Peti ujian digunakan sehingga kebenaran jawatan sebenar ditulis | | |
| Dokumen kontrak tidak boleh dibuka melalui URL awam | | |
| Penjadual peringatan dipasang pada sistem operasi | | |
| Sandaran pangkalan data dan storan peribadi wujud, dan satu pulihan telah dibuktikan | | |
| Prosedur undur dikenal pasti: binaan sebelumnya dan sandaran yang akan dipulihkan jika kuat kuasa dihentikan | | |
| Akaun operasi Ibu Pejabat dan Negeri disediakan tanpa menulis kata laluan dalam dokumen | | |
| Data ujian fiksyen yang tidak patut kekal telah ditanda atau dikeluarkan, dengan kebenaran pemilik sistem | | |
| Manual pengguna diserahkan kepada Ibu Pejabat dan Negeri | | |
| Log kecacatan pengujian akhir diserahkan bersama perakuan | | |
| Nama pegawai yang dihubungi pada lima hari bekerja pertama selepas kuat kuasa direkod | | |

Pegawai dihubungi pada tempoh awal operasi:

| Peranan | Nama | Cara dihubungi | Tempoh |
| --- | --- | --- | --- |
| Pasukan sistem | | | Lima hari bekerja pertama |
| Pegawai IT | | | Lima hari bekerja pertama |
| Pemilik sistem | | | Lima hari bekerja pertama |

## 23. Perakuan pengujian akhir

Saya mengesahkan bahawa kes ujian dalam dokumen ini telah dijalankan pada binaan dan persekitaran sasaran yang dinyatakan dalam Seksyen 1, dan keputusan yang ditulis adalah benar.

| Peranan | Nama | Jawatan | Keputusan | Tarikh | Tandatangan |
| --- | --- | --- | --- | --- | --- |
| Ketua pengujian akhir | | | Layak / Bersyarat / Tidak layak | | |
| Wakil Pentadbir Negeri | | | Setuju / Setuju dengan syarat / Tidak setuju | | |
| Wakil Ibu Pejabat | | | Setuju / Setuju dengan syarat / Tidak setuju | | |
| Pegawai IT | | | Sedia operasi / Tidak sedia | | |
| Pelulus kuat kuasa | | | Dikuatkuasakan / Tidak dikuatkuasakan | | |

---

*Tamat dokumen AADK/CP/SPEKTRA/PA/001, versi 1.0.*
