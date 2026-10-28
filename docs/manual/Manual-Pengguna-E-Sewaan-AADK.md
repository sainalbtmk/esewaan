# Manual Pengguna

**SPEKTRA**  
Sistem Pemantauan Kontrak Sewaan Premis AADK  
Agensi Antidadah Kebangsaan

| Medan | Butiran |
| --- | --- |
| No. dokumen | AADK/CP/SPEKTRA/MANUAL/001 |
| Versi | 1.0 |
| Tarikh | 22 Oktober 2026 |
| Status | Untuk kegunaan pengguna |
| Klasifikasi | Terhad |
| Pemilik sistem | Cawangan Pembangunan, AADK |
| Pembaca | Pentadbir Ibu Pejabat dan Pentadbir Negeri |

Manual ini menerangkan cara menggunakan setiap fungsi yang dipaparkan pada skrin. Nama butang, menu dan status ditulis sama seperti yang kelihatan dalam sistem.

---

## Kandungan

1. [Pengenalan](#1-pengenalan)
2. [Peranan dan had kuasa](#2-peranan-dan-had-kuasa)
3. [Antara muka](#3-antara-muka)
4. [Log masuk, log keluar dan lupa kata laluan](#4-log-masuk-log-keluar-dan-lupa-kata-laluan)
5. [Pengesahan dua faktor](#5-pengesahan-dua-faktor)
6. [Profil saya](#6-profil-saya)
7. [Penampilan](#7-penampilan)
8. [Papan Pemuka](#8-papan-pemuka)
9. [Aliran permohonan dari mula hingga kontrak](#9-aliran-permohonan-dari-mula-hingga-kontrak)
10. [Permohonan baharu](#10-permohonan-baharu)
11. [Langkah tindakan negeri](#11-langkah-tindakan-negeri)
12. [Dokumen sokongan](#12-dokumen-sokongan)
13. [Hantar ke Ibu Pejabat](#13-hantar-ke-ibu-pejabat)
14. [Status Permohonan dan Semak Permohonan](#14-status-permohonan-dan-semak-permohonan)
15. [Semakan Ibu Pejabat](#15-semakan-ibu-pejabat)
16. [Draf perjanjian dan semakan PUU](#16-draf-perjanjian-dan-semakan-puu)
17. [Draf lulus dan pengesahan negeri](#17-draf-lulus-dan-pengesahan-negeri)
18. [Pengesahan dan tandatangan Ibu Pejabat](#18-pengesahan-dan-tandatangan-ibu-pejabat)
19. [Mati setem](#19-mati-setem)
20. [Pindah dan lanjutan](#20-pindah-dan-lanjutan)
21. [Tarik semula, batal dan padam](#21-tarik-semula-batal-dan-padam)
22. [Senarai Kontrak Sewaan](#22-senarai-kontrak-sewaan)
23. [Jana Laporan](#23-jana-laporan)
24. [Pengurusan pengguna](#24-pengurusan-pengguna)
25. [Peti masuk, lencana dan emel](#25-peti-masuk-lencana-dan-emel)
26. [Peraturan input dan mesej biasa](#26-peraturan-input-dan-mesej-biasa)
27. [Glosari](#27-glosari)

---

## 1. Pengenalan

SPEKTRA merekod perjalanan permohonan sewaan premis, dari pendaftaran oleh pentadbir negeri sehingga kontrak berkuat kuasa. Sistem menyimpan butiran premis, langkah tindakan, senarai semak kelulusan, draf perjanjian, pengakuan tandatangan, pengakuan mati setem, senarai kontrak dan laporan Ibu Pejabat.

Sistem **merekod** bahawa tindakan luar telah dibuat. Kelulusan MOF, Kementerian Ekonomi, BPH, tandatangan fizikal TKPP, muat naik ProMIS dan proses LHDN tetap dilakukan di luar sistem. Pengguna menanda item yang berkaitan selepas tindakan itu selesai.

Dua peranan menggunakan sistem:

| Label pada skrin | Siapa | Skop |
| --- | --- | --- |
| Ibu Pejabat | Pegawai Cawangan Pembangunan | Semua negeri |
| Negeri | Pentadbir negeri | Negeri sendiri sahaja |

Pelayar yang disyorkan ialah versi semasa Chrome atau Edge. Sistem boleh digunakan pada komputer dan telefon. Pada telefon, menu dibuka melalui ikon di penjuru kiri atas.

---

## 2. Peranan dan had kuasa

### Ibu Pejabat

Ibu Pejabat boleh:

- Melihat papan pemuka, permohonan dan kontrak bagi semua negeri.
- Menyemak permohonan yang dihantar negeri, mengisi senarai semak, dan meneruskan ke penyediaan draf.
- Meluluskan atau membatalkan semakan PUU.
- Mengesahkan tandatangan dan menghantar dokumen untuk mati setem.
- Meluluskan atau menolak permohonan tarik semula.
- Melihat sejarah permohonan yang dipadam.
- Menjana dan memuat turun laporan.
- Mendaftarkan pengguna Ibu Pejabat dan pengguna Negeri bagi mana-mana negeri.
- Mengaktifkan atau menyahaktifkan akaun pengguna lain.
- Mengemaskini emel jawatan Ketua Pengarah, TKPP, PKP dan KPP.
- Membuka jejak audit pengguna.

Ibu Pejabat tidak mencipta permohonan baharu. Menu **Permohonan Baharu** tidak dipaparkan.

### Negeri

Negeri boleh:

- Melihat papan pemuka, permohonan dan kontrak bagi negeri sendiri sahaja.
- Mencipta permohonan baharu.
- Memulakan permohonan pindah atau lanjutan daripada kontrak aktif negeri sendiri.
- Mengisi borang, melengkapkan langkah tindakan, memuat naik dokumen sokongan dan draf perjanjian.
- Menghantar permohonan ke Ibu Pejabat.
- Mengesahkan pengakuan selepas draf lulus, dan pengakuan mati setem.
- Memohon tarik semula semasa permohonan menunggu semakan Ibu Pejabat.
- Memadam permohonan yang masih belum dihantar, dengan alasan.
- Membatalkan permohonan susulan yang masih di peringkat langkah tindakan.
- Mendaftarkan pengguna Negeri bagi negeri yang sama sahaja.
- Mengemaskini profil pengguna negeri sendiri dan melihat jejak audit mereka.

Negeri tidak boleh membuka rekod negeri lain, menjana laporan, menukar status aktif akaun, atau mengemaskini emel jawatan.

---

## 3. Antara muka

Selepas log masuk, skrin mempunyai tiga bahagian:

- **Menu sisi** di kiri. Pada telefon, menu ini tersembunyi sehingga ikon menu ditekan, dan ditutup dengan butang tutup.
- **Kepala halaman** di atas, mengandungi tajuk halaman dan ikon **Peti Masuk**.
- **Kandungan** di tengah.

### Menu

| Menu | Isi | Siapa nampak |
| --- | --- | --- |
| Papan Pemuka | Ringkasan angka dan senarai amaran | Semua |
| Pengurusan Permohonan | Permohonan Baharu, dan Status Permohonan atau Senarai Permohonan | Permohonan Baharu untuk Negeri sahaja. Senarai untuk semua |
| Kontrak Sewaan | Senarai Kontrak Sewaan | Semua |
| Jana Laporan | Laporan Ibu Pejabat | Ibu Pejabat sahaja |
| Pengurusan Pengguna | Pendaftaran Pengguna dan Senarai Pengguna | Semua pentadbir, dengan had kuasa berbeza |

Nombor merah pada menu ialah lencana. Ia memberitahu bilangan item yang menunggu tindakan. Lencana pada **Pengurusan Permohonan** mengira permohonan yang perlu dilihat. Bagi Ibu Pejabat, lencana ini juga mengira permohonan tarik semula yang belum diputuskan. Lencana pada **Kontrak Sewaan** mengira kontrak baharu dalam senarai. Lencana berkurang selepas item dibuka atau selesai, dan selepas menu disegarkan.

### Bahagian bawah menu

Nama dan emel pengguna membuka **Profil Saya**. Ikon gear di sebelahnya membuka **Penampilan**. Butang **Log Keluar** menutup sesi.

### Petunjuk warna pada senarai permohonan

| Petunjuk | Maksud |
| --- | --- |
| Perlu tindakan anda | Rekod ini menunggu tindakan peranan anda |
| Anda telah selesai memproses | Bahagian anda bagi rekod ini sudah selesai |
| Perlu disemak | Rekod perlu disemak, apabila petunjuk ini dipaparkan |

### Pengesahan sebelum tindakan penting

Tindakan seperti hantar, padam, lulus dan batal membuka tetingkap pengesahan. Baca mesej, kemudian pilih butang pengesahan seperti **Ya, Hantar** atau **Batal**. Tindakan tidak berlaku jika tetingkap ditutup.

### Simpan automatik

Pada borang permohonan, langkah tindakan dan senarai semak, sistem menyimpan nilai yang sah semasa anda mengisi. Teks status simpan dipaparkan berhampiran borang. Muat semula halaman untuk mengesahkan nilai terakhir yang tersimpan.

---

## 4. Log masuk, log keluar dan lupa kata laluan

### Log masuk

1. Buka alamat sistem. Halaman **Kontrak Sewaan Premis** dipaparkan.
2. Isi **Emel** dan **Kata laluan**.
3. Hantar borang.

Jika emel dan kata laluan betul dan akaun berstatus **Aktif**, sistem membuka **Papan Pemuka**. Jika pengesahan dua faktor dihidupkan dan akaun belum disahkan, sistem membuka persediaan pengesahan dahulu. Menu kerja tidak boleh digunakan sebelum pengesahan selesai.

Log masuk ditolak apabila:

- Kata laluan salah. Mesej ralat dipaparkan dan papan pemuka tidak dibuka.
- Akaun berstatus **Nyahaktif**.
- Percubaan gagal terlalu kerap dalam masa yang singkat. Tunggu sebentar, kemudian cuba semula dengan kata laluan yang betul.

### Log keluar

Tekan **Log Keluar** di bahagian bawah menu. Sesi ditutup. Halaman dalaman tidak boleh digunakan selepas itu. Jika pelayar dibawa ke halaman dalaman, sistem mengembalikan pengguna ke log masuk.

### Lupa kata laluan

1. Pada halaman log masuk, pilih **Lupa Katalaluan?**.
2. Pada **Lupa Kata Laluan**, isi emel berdaftar dan tekan **Hantar Pautan Set Semula**.
3. Buka pautan dalam emel. Pautan membuka borang set semula.
4. Isi emel, **Kata laluan baharu** dan **Sahkan kata laluan baharu**.
5. Log masuk dengan kata laluan baharu. Kata laluan lama tidak lagi diterima.

Sistem memaparkan mesej yang sama sama ada emel itu wujud atau tidak, supaya kehadiran akaun tidak didedahkan.

### Peraturan kata laluan

Kata laluan baharu mestilah:

- Sekurang-kurangnya 8 aksara.
- Mengandungi huruf besar dan huruf kecil.
- Mengandungi nombor.
- Sama pada medan pengesahan.

Dalam persekitaran pengeluaran, kata laluan yang diketahui telah tercemar juga ditolak. Jika dua medan tidak sama, mesej **Kata laluan tidak sama** atau **Pengesahan kata laluan tidak sepadan** dipaparkan.

---

## 5. Pengesahan dua faktor

Fungsi ini digunakan apabila pentadbir sistem menghidupkannya. Dalam pengeluaran, ia wajib.

### Persediaan kali pertama

1. Log masuk. Sistem membuka **Sediakan pengesahan dua faktor**.
2. Imbas kod QR, atau masukkan kunci rahsia, ke aplikasi authenticator seperti Google Authenticator atau aplikasi setara.
3. Simpan lapan **kod pemulihan** di tempat selamat. Kod ini dipaparkan sekali sahaja.
4. Masukkan kod 6 digit semasa dari aplikasi authenticator.
5. Selepas kod sah, halaman **Simpan kod pemulihan** dipaparkan. Simpan kod itu, kemudian teruskan ke papan pemuka.

Kod yang salah memaparkan **Kod pengesahan tidak sah**. Sesi kerja tidak dibuka.

### Log masuk seterusnya

Selepas emel dan kata laluan diterima, halaman **Kod pengesahan** dipaparkan. Masukkan salah satu daripada:

- Kod 6 digit semasa daripada aplikasi authenticator, atau
- Satu kod pemulihan yang belum digunakan.

Tekan **Sahkan dan masuk**. Kod pemulihan yang telah digunakan ditolak pada percubaan seterusnya.

Jika telefon authenticator hilang, gunakan kod pemulihan. Jika semua kod pemulihan telah digunakan atau hilang, hubungi pentadbir sistem untuk bantuan akaun.

---

## 6. Profil saya

Buka nama anda di bahagian bawah menu.

**Profil Saya** memaparkan nama, emel, peranan, tarikh daftar dan kemaskini terakhir. Pengguna Negeri juga melihat negeri. Ibu Pejabat tidak diikat pada satu negeri, jadi medan negeri tidak dipaparkan.

### Kemaskini profil

Tekan **Kemaskini profil**.

| Medan | Penerangan |
| --- | --- |
| Nama penuh | Nama yang dipaparkan pada menu dan rekod tindakan |
| Emel | Emel log masuk. Pertukaran memerlukan kata laluan semasa |
| Negeri | Dipaparkan untuk pengguna Negeri. Negeri dikunci mengikut akaun |
| Peranan | Paparan sahaja. Pengguna tidak menukar peranan sendiri di sini |
| Kata laluan semasa | Wajib jika emel atau kata laluan ditukar |
| Kata laluan baharu dan pengesahan | Pilihan. Ikut peraturan kata laluan |

Simpan. Nama baharu kelihatan selepas halaman dimuat semula. Pertukaran emel atau kata laluan tanpa kata laluan semasa yang betul ditolak.

Pentadbir juga boleh mengemaskini profil pengguna lain melalui **Senarai Pengguna**. Lihat [Pengurusan pengguna](#24-pengurusan-pengguna).

---

## 7. Penampilan

Buka ikon gear di sebelah nama anda. Halaman **Penampilan** mengubah rupa skrin pada pelayar ini sahaja. Pilihan kekal selepas anda beralih ke halaman lain, selagi data pelayar tidak dipadam.

| Bahagian | Pilihan | Kegunaan |
| --- | --- | --- |
| Tema | Cerah, Gelap, Sistem | Cerah ialah latar terang. Gelap ialah mod malam. Sistem mengikut tetapan peranti |
| Saiz fon | Kecil, Sederhana, Besar, Sangat besar | Membesarkan atau mengecilkan teks seluruh aplikasi |
| Kontras | Kontras biasa, Kontras tinggi | Kontras tinggi menguatkan teks dan sempadan |

**Reset ke Default** mengembalikan tema cerah, saiz sederhana dan kontras biasa.

---

## 8. Papan Pemuka

Papan pemuka ialah halaman utama selepas log masuk. Tajuk kecil menyatakan sama ada ringkasan itu untuk satu negeri atau untuk semua negeri.

Ibu Pejabat melihat angka semua negeri. Negeri hanya melihat angka negeri sendiri.

### Kad ringkasan

Tekan kad untuk pergi ke senarai atau bahagian yang berkaitan.

| Kad | Maksud | Pergi ke |
| --- | --- | --- |
| Permohonan Baharu | Permohonan yang menunggu semakan Ibu Pejabat | Senarai permohonan |
| Dalam Tindakan | Permohonan yang sedang dalam penyediaan draf perjanjian dan peringkat selepasnya | Bahagian Status Tindakan |
| Kontrak Aktif | Kontrak yang sudah dalam senarai kontrak sewaan | Senarai Kontrak Sewaan |
| Kontrak Bawah 8 Bulan | Kontrak aktif dengan baki 3 bulan hingga bawah 8 bulan | Senarai amaran kuning |
| Kontrak Bawah 3 Bulan | Kontrak aktif dengan baki 3 bulan atau kurang. Perlu tindakan segera | Senarai amaran merah |

### Status Kontrak Sewaan Premis AADK

Bahagian ini memaparkan carta dan pecahan portfolio bagi tahun semasa. Hirisan carta ialah:

- Pertimbangan BPH
- Semakan PUU
- Pertimbangan MOF
- Pertimbangan KE
- Tindakan PTJ
- Semakan Ibu Pejabat
- Kontrak Sedang Berkuatkuasa

Setiap hirisan menunjukkan bilangan dan peratus. Jadual di sebelahnya menyenaraikan kontrak yang berbaki kurang daripada 8 bulan dan belum mengemukakan permohonan susulan. Lajur baki bulan berwarna merah jika tempoh sudah luput, dan kuning jika masih berbaki.

### Status Tindakan

Senarai ini mengira kontrak mengikut status kerja semasa. Label yang dipaparkan, mengikut turutan:

1. Belum Terima Permohonan
2. Semakan Cawangan Pembangunan Sebelum ke BPH
3. Minit Ceraian Kelulusan Kos
4. Pertimbangan MOF
5. Pertimbangan KE
6. Pertimbangan BPH/SUPS
7. Sedia Draf Perjanjian
8. Semakan Cawangan Pembangunan Sebelum ke PUU
9. Semakan PUU
10. Kuiri PUU
11. Pindaan Berdasarkan Syor PUU dan Tandatangan Pemilik Premis
12. Tandatangan Penyewa (AADK)
13. Tindakan Mati Setem

Angka bertambah atau berkurang apabila senarai semak, draf dan pengakuan dikemaskini. **Belum Terima Permohonan** mengira kontrak aktif yang berbaki bawah 8 bulan dan permohonan susulannya belum dihantar ke Ibu Pejabat. Klik mana-mana baris untuk membuka senarai projek yang dikira pada status itu. Angka di hujung baris kekal.

### Senarai amaran tamat kontrak

- **Kontrak Bawah 3 Bulan** menyenaraikan kontrak aktif yang paling hampir tamat. Tekan baris untuk membuka butiran kontrak.
- **Kontrak Bawah 8 Bulan** menyenaraikan baki dari 3 bulan hingga bawah 8 bulan. Kontrak bawah 3 bulan tidak diulang di senarai ini.

---

## 9. Aliran permohonan dari mula hingga kontrak

Setiap permohonan melalui lapan peringkat. Peringkat semasa dipaparkan pada bar **Kemajuan Permohonan** di halaman **Semak Permohonan**. Klik mana-mana langkah untuk membaca butiran bahagian itu.

| No. | Peringkat pada skrin | Siapa bertindak | Hasil |
| --- | --- | --- | --- |
| 1 | Permohonan Baru | Negeri | Butiran premis dan langkah tindakan lengkap, kemudian dihantar |
| 2 | Semakan Ibu Pejabat | Ibu Pejabat | Senarai semak diisi, kemudian permohonan diteruskan ke draf |
| 3 | Penyediaan Draf | Negeri | Draf perjanjian PDF dimuat naik |
| 4 | Dalam tindakan PUU | Ibu Pejabat | Semakan diluluskan, atau dibatalkan supaya draf baharu dimuat naik |
| 5 | Draf Lulus | Negeri | Empat pengakuan ditanda, dokumen dikembalikan ke Cawangan Pembangunan |
| 6 | Pengesahan & Tandatangan | Ibu Pejabat | Tiga pengakuan ditanda, dokumen dihantar untuk mati setem |
| 7 | Mati Setem | Negeri | Dua pengakuan ditanda |
| 8 | Selesai | Sistem | Rekod masuk ke Senarai Kontrak Sewaan |

### Kategori permohonan

| Kategori | Bila digunakan | Langkah tindakan yang wajib |
| --- | --- | --- |
| Baru | Premis baharu yang belum mempunyai kontrak dalam sistem | 2 Surat niat, 3 Borang JRP, 5 Surat kepada agensi |
| Pindah | Pindah daripada kontrak sedia ada ke premis baharu | 2 Surat niat, 3 Borang JRP, 5 Surat kepada agensi |
| Lanjutan | Lanjutan kontrak sedia ada | 1 Notis kepada pemilik, 3 Borang JRP, 5 Surat kepada agensi |

Pindah dan lanjutan dimulakan dari **Senarai Kontrak Sewaan**, supaya sistem mengaitkan permohonan dengan kontrak asal. Selepas Ibu Pejabat mengesahkan permohonan susulan itu, kontrak asal ditandakan diganti dan tidak lagi dikira sebagai kontrak aktif semasa.

---

## 10. Permohonan baharu

Menu: **Pengurusan Permohonan**, kemudian **Permohonan Baharu**. Fungsi ini untuk Negeri sahaja.

Halaman bertajuk **Borang Permohonan Baru Sewaan**. Medan bertanda * wajib diisi.

### Medan borang

| Medan | Penerangan |
| --- | --- |
| Kategori Permohonan | Pilih **Baru** untuk premis baharu. Kategori dikunci selepas permohonan dicipta dan tidak boleh ditukar |
| Negeri | Dikunci kepada negeri pentadbir. Permohonan untuk negeri lain ditolak |
| Nama Premis | Nama pejabat atau premis, contoh AADK Daerah Pasir Puteh |
| Jenis Bangunan | Kompleks kerajaan, Komersial, atau Rumah Kediaman |
| Kadar Sewa (RM) | Kadar sewa bulanan. Nilai tidak boleh negatif |
| Keluasan (mps) | Keluasan dalam meter persegi. Sistem menukarnya kepada kaki persegi |
| Keluasan (kps) | Keluasan dalam kaki persegi. Sistem menukarnya kepada meter persegi. Anggaran penukaran ialah 1 meter persegi bersamaan 10.7639 kaki persegi |
| Tarikh Mula | Tarikh mula tempoh tawaran |
| Tarikh Akhir Tempoh Tawaran Penyewaan | Mesti selepas tarikh mula |
| Alamat Penuh Premis | Alamat lengkap, termasuk tingkat, jalan, bandar dan poskod |
| Nama Pemilik | Nama pemilik atau agensi pemilik |

### Simpan

Tekan **Teruskan ke Tindakan**. Sistem mencipta rekod dan membuka borang yang sama bersama bahagian **Langkah Tindakan**. Rekod muncul dalam **Status Permohonan** dengan status menunggu langkah tindakan.

Jika borang kosong, tarikh akhir lebih awal daripada tarikh mula, atau kadar sewa tidak sah, mesej pengesahan dipaparkan dan rekod tidak rosak.

Pada kemaskini kemudian, perubahan yang sah disimpan secara automatik. Butang **Simpan** menyimpan langkah semasa. Butang **Seterusnya** menyimpan dan bergerak ke langkah tindakan berikutnya, dan hanya dipaparkan sebelum langkah terakhir.

---

## 11. Langkah tindakan negeri

Bahagian **Langkah Tindakan** berada pada borang permohonan selepas rekod dicipta. Lengkapkan semua langkah yang dipaparkan sebelum menghantar permohonan kepada Ibu Pejabat.

Hanya langkah yang berkaitan kategori dipaparkan. Bagi Baru dan Pindah, tiga langkah dipaparkan: surat niat, borang JRP dan surat kepada agensi. Bagi Lanjutan, tiga langkah dipaparkan: notis kepada pemilik, borang JRP dan surat kepada agensi. Panel kiri menunjukkan kedudukan, contoh **Langkah 1 / 3**.

### Isi setiap langkah

| Langkah | Apa yang direkod | Medan khas |
| --- | --- | --- |
| Notis kepada pemilik premis | Notis untuk sambung kontrak | No. Rujukan dan Tarikh Surat, kerana ini langkah pertama lanjutan |
| Surat niat kepada pemilik premis | Surat niat untuk sewa pejabat baru atau pindah | No. Rujukan dan Tarikh Surat, kerana ini langkah pertama bagi Baru dan Pindah |
| Borang JRP | Borang JRP yang berkenaan | Dua pengesahan di bawah. Tiada kotak "langkah selesai" yang berasingan |
| Surat kepada agensi | Surat kepada agensi dengan kepilkan Borang JRP | Senarai semak agensi, tarikh bagi item yang memerlukannya, dan pengesahan selesai |

Langkah pertama dalam turutan meminta **No. Rujukan** dan **Tarikh Surat**. Contoh nombor rujukan pada skrin ialah `AADK/BKP/PB 200-3/02`.

Setiap langkah mempunyai **Catatan**. Catatan langkah ialah pilihan.

### Pengesahan pada langkah

Pada langkah pertama turutan, tandakan:

- Saya mengesahkan surat niat telah dihantar ke pemilik premis.
- Surat tawaran pemilik premis telah diterima dan dimuat naik ke PROMIS.
- Saya mengesahkan langkah ini telah selesai dilaksanakan.

Pada **Borang JRP**, tandakan kedua-dua ayat berikut. Langkah dianggap selesai apabila kedua-duanya ditanda:

- Saya mengesahkan borang JRP telah diisi dan dimuat naik ke PROMIS.
- Saya mengesahkan maklumat ini tepat dan benar.

Pada langkah lain, tandakan:

- Saya mengesahkan maklumat ini tepat dan benar.
- Maklumat ini telah dimuat naik ke dalam sistem PROMIS.
- Saya mengesahkan langkah ini telah selesai dilaksanakan.

### Surat kepada agensi

Tandakan kesemua item. Item yang memerlukan tarikh tidak boleh disimpan sebagai lengkap tanpa tarikh. Tarikh muncul selepas item ditanda.

| Item | Tarikh |
| --- | --- |
| Surat Jabatan Penilaian dan Perkhidmatan Harta | Wajib |
| Ketua Pegawai Keselamatan Kerajaan Malaysia | Wajib |
| Jabatan Bomba dan Penyelamat Malaysia | Wajib |
| Jabatan Ketua Pengarah Tanah dan Galian Persekutuan | Wajib |
| Carian Rasmi terkini dari Pejabat Tanah Daerah | Wajib |
| Surat tawaran dari Pemilik Premis | Wajib |
| Pelan Lantai | Tidak perlu |
| Gambar Bangunan Terkini | Tidak perlu |

Jika ada item yang belum ditanda, mesej meminta semua agensi ditandakan sebelum langkah disahkan.

### Siap untuk dihantar

Apabila semua langkah aktif selesai, butang **Hantar ke Ibu Pejabat** muncul. Bagi pindah dan lanjutan, medan **Remark** juga mesti diisi. Lihat bahagian [Hantar ke Ibu Pejabat](#13-hantar-ke-ibu-pejabat).

---

## 12. Dokumen sokongan

Dokumen sokongan ialah fail premis seperti surat tawaran, gambar dan lampiran lain. Draf perjanjian dimuat naik di tempat yang berasingan, pada peringkat Penyediaan Draf. Jangan campurkan kedua-duanya.

Halaman **Muat Naik Dokumen Sokongan** digunakan untuk kontrak yang berkenaan.

| Medan | Penerangan |
| --- | --- |
| Jenis Dokumen | Surat Tawaran, Gambar Premis, atau Lain-lain |
| Fail | PDF, JPG atau PNG. Saiz maksimum 10 MB, atau had pelayan yang lebih rendah jika pelayan menetapkannya |

Tekan **Muat Naik**. Fail tersimpan di bawah kontrak itu dan boleh dibuka oleh pengguna yang berkuasa: pentadbir negeri kontrak tersebut dan Ibu Pejabat. Negeri lain tidak boleh membuka atau memuat turun fail itu.

Fail DOC, DOCX, EXE dan jenis lain ditolak. Borang tanpa fail juga ditolak. Mesej menyebut jenis yang dibenarkan atau had saiz.

**Batal** kembali ke senarai permohonan.

---

## 13. Hantar ke Ibu Pejabat

Butang **Hantar ke Ibu Pejabat** tersedia kepada Negeri apabila:

- Semua langkah tindakan kategori itu selesai.
- Bagi pindah atau lanjutan, **Remark** telah diisi.

Jika remark kosong, skrin memaparkan **Sila isi Remark sebelum menghantar ke Ibu Pejabat** dan penghantaran ditolak.

1. Tekan **Hantar ke Ibu Pejabat**.
2. Baca tetingkap **Hantar Permohonan ke Ibu Pejabat**.
3. Tekan **Ya, Hantar**.

Status pada senarai menjadi **Permohonan kepada MOF**. Ibu Pejabat menerima pemakluman. Negeri tidak lagi boleh memadam permohonan ini. Jika negeri perlu menarik balik permohonan pada peringkat ini, gunakan **Mohon Tarik Semula**.

Butang yang sama juga ada pada baris senarai **Status Permohonan** apabila permohonan sudah sedia.

---

## 14. Status Permohonan dan Semak Permohonan

Menu: **Pengurusan Permohonan**, kemudian **Status Permohonan** bagi Negeri, atau **Senarai Permohonan** bagi Ibu Pejabat.

### Senarai

Kotak carian menerima nama premis, kategori, negeri dan status yang dipaparkan. Carian kosong memulihkan senarai penuh. Senarai dikemas kini secara berkala.

Semasa permohonan menunggu semakan Ibu Pejabat, lajur **Status** mengikut item senarai semak yang belum ditanda. Hanya satu status dipaparkan:

1. **Permohonan kepada MOF**
2. **Permohonan kepada KE**
3. **Pertimbangan BPH**

Selepas ketiga-tiga item ditanda, status kembali kepada **Menunggu semakan Ibu Pejabat** sehingga permohonan diteruskan ke draf perjanjian.

Ibu Pejabat mempunyai dua tab:

| Tab | Isi |
| --- | --- |
| Senarai Aktif | Permohonan yang masih dalam aliran kerja |
| Sejarah | Permohonan yang dipadam, termasuk tarikh padam dan alasan padam |

### Tindakan pada baris

Ikon yang kelihatan bergantung pada status dan peranan.

| Tindakan | Siapa | Bila |
| --- | --- | --- |
| Edit | Negeri | Permohonan masih boleh dikemaskini |
| Hantar ke Ibu Pejabat | Negeri | Langkah lengkap dan, bagi susulan, remark diisi |
| Mohon Tarik Semula | Negeri | Status menunggu semakan Ibu Pejabat |
| Batal & Kembali ke Kontrak Asal | Negeri | Permohonan susulan masih di langkah tindakan |
| Padam | Negeri | Permohonan biasa yang belum dihantar dan belum masuk peringkat draf |
| Semak, atau Semak & Sahkan Permohonan | Ibu Pejabat | Permohonan menunggu tindakan Ibu Pejabat |
| Lihat Permohonan | Pihak yang berkuasa | Membuka butiran tanpa tindakan baharu |
| Luluskan atau Tolak Tarik Semula | Ibu Pejabat | Ada permohonan tarik semula yang menunggu |

### Halaman Semak Permohonan

Halaman ini menunjukkan maklumat asas, butiran premis, dan bar kemajuan lapan peringkat. Butiran negeri dipaparkan sebagai baca sahaja kepada Ibu Pejabat.

Bagi pindah dan lanjutan, bahagian **Perbandingan Permohonan** membandingkan kontrak lama dengan maklumat baharu.

Bahagian **Langkah Tindakan Pegawai Negeri** memaparkan langkah yang telah dilengkapkan, termasuk nombor rujukan, tarikh surat, agensi dan catatan.

---

## 15. Semakan Ibu Pejabat

Apabila permohonan menunggu semakan Ibu Pejabat, Ibu Pejabat membuka permohonan dan mengisi senarai semak pada **Pengesahan Ibu Pejabat**. Lajur status pada senarai bertukar mengikut item MOF, KE dan BPH yang belum ditanda.

Tanda, tarikh dan catatan disimpan secara automatik. Keluar dan buka semula: nilai yang telah disimpan kekal.

### Item senarai semak

Setiap item yang ditanda direkod bersama tarikh.

| Item pada skrin | Bila ia relevan |
| --- | --- |
| Kelulusan Pengurusan Tertinggi | Direkod apabila kelulusan diperoleh |
| Permohonan kepada Kementerian Kewangan (MOF) untuk permohonan Baru / Pindah / Pelanjutan dengan Kenaikan Sewa Lebih 10% @ minimum RM500 | Kenaikan sewa melepasi ambang itu. Ruang catatan dipaparkan selepas item ditanda. |
| Permohonan kepada Kementerian Ekonomi (KE) (jika keluasan melebihi 465 mps) — untuk permohonan perpindahan ruang pejabat / ruang pejabat baharu | Keluasan melebihi 465 meter persegi bagi pejabat baharu atau pindah. Ruang catatan dipaparkan selepas item ditanda. |
| Cawangan Pembangunan AADK menerima ulasan daripada Kementerian Ekonomi (KE) | Selepas ulasan KE diterima |
| Cawangan Pembangunan AADK menerima Kelulusan daripada MOF melalui KDN | Selepas kelulusan MOF diterima |
| Cawangan Pembangunan AADK mengemukakan permohonan Bahagian Hartanah (BPH) Jabatan Perdana Menteri untuk mendapatkan kelulusan | Selepas permohonan dikemukakan kepada BPH |
| Cawangan Pembangunan AADK terima surat kelulusan daripada BPH | Selepas surat kelulusan BPH diterima |

Pada borang susulan, lencana kadar sewa memberitahu sama ada perbezaan sewa bawah RM500 atau sekurang-kurangnya RM500. Gunakan angka itu bersama aturan kenaikan 10 peratus semasa menanda item berkaitan.

Apabila sedia, tekan **Teruskan Dengan Draf Perjanjian** dan sahkan **Ya, Teruskan**. Status menjadi penyediaan draf perjanjian. Negeri menerima pemakluman. Negeri tidak boleh menekan butang kelulusan ini.

Jika negeri telah memohon tarik semula, selesaikan keputusan tarik semula dahulu. Lihat [Tarik semula, batal dan padam](#21-tarik-semula-batal-dan-padam).

---

## 16. Draf perjanjian dan semakan PUU

### Negeri memuat naik draf

Pada peringkat **Penyediaan Draf Perjanjian**:

1. Buka permohonan.
2. Pilih fail pada **Muat Naik Draf Perjanjian (PDF)**.
3. Tekan **Hantar untuk Semakan PUU**.

Hanya PDF diterima. JPG, PNG dan DOCX ditolak. Had saiz draf perjanjian ialah 40 MB, atau had pelayan yang lebih rendah.

Status selepas muat naik ialah **Semakan PUU**. Sejarah draf merekod pusingan **Semakan PUU 1**, **Semakan PUU 2**, dan seterusnya, bersama nama fail dan hasil:

| Hasil | Maksud |
| --- | --- |
| Menunggu Semakan PUU | Draf belum diputuskan |
| Diluluskan | Semakan PUU diluluskan |
| Pindaan berdasarkan ulasan PUU | Semakan dibatalkan dan draf dikembalikan |

### Ibu Pejabat memutuskan semakan

Pada status **Semakan PUU**, Ibu Pejabat mempunyai dua tindakan:

| Butang | Kesan |
| --- | --- |
| Luluskan | Semakan diluluskan. Pengesahan meminta muat naik dokumen yang telah lulus (PDF, maksimum 40 MB). Status menjadi **Draf Perjanjian dikembalikan ke Pentadbir Negeri untuk dapatkan Tandatangan Pemilik Premis**. Dokumen lulus dipaparkan dalam sejarah muat naik. Negeri menyediakan dokumen perjanjian |
| Semak Semula | Draf dikembalikan kepada Negeri. Pengesahan meminta muat naik dokumen pindaan PUU (PDF, maksimum 40 MB). Mesej menyatakan draf perjanjian dikembalikan oleh PUU untuk pindaan lanjut. Status menjadi **Kuiri PUU**. Dokumen pindaan dipaparkan dalam sejarah muat naik. Negeri memuat naik PDF baharu |

**Luluskan** dan **Semak Semula** membuka pengesahan yang mengandungi ruang muat naik PDF dengan had saiz yang sama. **Ya, Luluskan** hanya diteruskan selepas dokumen yang telah lulus dipilih. **Ya, Semak Semula** hanya diteruskan selepas dokumen pindaan dipilih.

Jika status ialah **Pindaan Berdasarkan PUU**, Ibu Pejabat menekan **Selesai** selepas pindaan selesai. Permohonan diteruskan ke draf lulus.

---

## 17. Draf lulus dan pengesahan negeri

Selepas semakan PUU diluluskan, Negeri mengesahkan empat perkara pada **Draf Lulus**, kemudian mengembalikan dokumen.

Tandakan kesemua empat. Setiap kotak mempunyai tarikh di sebelah kanan. Tarikh diisi dengan tarikh semasa apabila kotak ditanda, dan boleh ditukar.

1. Draf akhir diterima untuk penyediaan dokumen perjanjian.
2. 3 Salinan Dokumen Perjanjian telah disediakan.
3. Dokumen Perjanjian telah ditandatangani oleh pemilik premis.
4. Dokumen perjanjian telah dihantar melalui kurier / Serahan tangan kepada Cawangan Pembangunan.

Tanda dan tarikh disimpan secara automatik. Butang **Hantar Semula ke Ibu Pejabat** tidak menghantar selagi ada pengakuan yang belum ditanda.

Selepas empat pengakuan lengkap, tekan butang itu. Status menjadi **Dokumen Perjanjian dikembalikan ke Cawangan Pembangunan untuk tindakan Tandatangan Penyewa**. Tindakan ini tidak tersedia kepada Ibu Pejabat.

---

## 18. Pengesahan dan tandatangan Ibu Pejabat

Ibu Pejabat membuka permohonan yang telah dikembalikan negeri. Pada **Tindakan Ibu Pejabat**, tandakan empat perkara sebelum menghantar untuk mati setem:

1. Cawangan Pembangunan terima Perjanjian yang telah ditandatangani oleh Pemilik Premis.
2. Perjanjian dihantar ke Pejabat PKP untuk tandatangan Saksi.
3. Perjanjian dihantar ke Pejabat TKPP untuk tandatangan Penyewa.
4. Perjanjian dikembalikan ke AADK Negeri untuk tindakan mati setem dan edaran kepada Pemilik Premis.

Tarikh di sebelah kanan setiap kotak diisi dengan tarikh tanda dan boleh diubah. Tanda disimpan secara automatik. Penghantaran disekat sehingga empat pengakuan lengkap. Tekan **Hantar ke Negeri**. Status menjadi **Menunggu Mati Setem**. Negeri menerima pemakluman.

---

## 19. Mati setem

Negeri membuka permohonan berstatus **Menunggu Mati Setem** dan menandakan:

1. 3 Salinan Dokumen Perjanjian telah dimatikan setem di LHDN.
2. 1 Salinan Dokumen Perjanjian telah diedar kepada Pemilik Premis.
3. Salinan dihantar ke Cawangan Pembangunan melalui Pos/Kourier.

Tarikh di sebelah kanan setiap kotak diisi dengan tarikh tanda dan boleh diubah. Nyah tanda memadam tarikh itu. Satu pengakuan tidak mencukupi. Selepas ketiga-tiganya ditanda, tekan **Selesai**.

Permohonan keluar daripada senarai kerja aktif dan muncul dalam **Senarai Kontrak Sewaan**. Mesej kejayaan dipaparkan. Papan pemuka dan laporan Ibu Pejabat mengira kontrak baharu itu. Pemakluman selesai dihantar kepada pihak yang ditetapkan.

---

## 20. Pindah dan lanjutan

Fungsi ini dimulakan dari kontrak aktif, bukan dari menu Permohonan Baharu.

1. Buka **Senarai Kontrak Sewaan**, tab **Senarai Aktif**.
2. Pada kontrak negeri sendiri, tekan **Pindah** atau **Lanjutan**.
3. Sahkan **Ya, Pindah** atau **Ya, Lanjutan**.

Kontrak asal dikeluarkan daripada senarai aktif sementara permohonan diproses. Borang susulan terbuka dengan kaitan kepada kontrak asal. Tajuk borang ialah **Maklumat Baharu**.

### Perbezaan borang susulan

| Perkara | Pindah | Lanjutan |
| --- | --- | --- |
| Kategori | Pindah, dikunci | Lanjutan, dikunci |
| Keluasan | Boleh diisi semula | Dikunci mengikut kontrak sedia ada |
| Kadar sewa, tarikh, alamat, pemilik, nama premis | Diisi dengan nilai baharu | Diisi dengan nilai baharu bagi tempoh lanjutan |
| Remark | Wajib sebelum hantar ke Ibu Pejabat | Wajib sebelum hantar ke Ibu Pejabat |
| Langkah | Surat niat, JRP, surat agensi | Notis kepada pemilik, JRP, surat agensi |
| Lencana sewa | Menunjukkan perbezaan bawah RM500 atau sekurang-kurangnya RM500 berbanding kontrak asal | Sama |

Bahagian atas borang memaparkan **Maklumat Kontrak Sedia Ada** supaya nilai lama boleh dibanding.

Selepas dihantar, aliran semakan, draf, tandatangan dan mati setem sama seperti permohonan baharu. Apabila Ibu Pejabat mengesahkan permohonan susulan, kontrak asal ditandakan diganti.

### Batal permohonan susulan

Selagi permohonan masih di langkah tindakan, Negeri boleh menekan **Batal permohonan susulan** pada borang, atau **Batal & Kembali ke Kontrak Asal** pada senarai. Sahkan **Ya, Batalkan**.

Permohonan susulan dibuang. Kontrak asal kembali ke senarai kontrak. Butang **Padam** biasa tidak digunakan untuk kes ini.

---

## 21. Tarik semula, batal dan padam

Tiga cara menghentikan permohonan. Gunakan yang sepadan dengan status.

### Mohon tarik semula

Gunakan apabila permohonan sudah berstatus **Menunggu semakan Ibu Pejabat** dan negeri perlu menariknya balik.

1. Pada senarai, tekan **Mohon Tarik Semula**.
2. Isi **Alasan Permohonan Tarik Semula**. Sekurang-kurangnya 10 aksara dan tidak melebihi 2,000 aksara.
3. Tekan **Hantar Permohonan**.

Permohonan ditanda menunggu kelulusan tarik semula. Ibu Pejabat melihat alasan dan dua tindakan:

| Keputusan | Kesan |
| --- | --- |
| Luluskan Tarik Semula | Permohonan kembali kepada negeri untuk dikemaskini. Status semakan Ibu Pejabat berakhir |
| Tolak Tarik Semula | Permohonan kekal dalam senarai semakan Ibu Pejabat |

Alasan kurang daripada 10 aksara ditolak.

### Padam

Gunakan pada permohonan biasa yang belum dihantar ke Ibu Pejabat dan belum masuk peringkat draf perjanjian.

1. Tekan **Padam**.
2. Isi alasan sekurang-kurangnya 10 aksara dan tidak melebihi 2,000 aksara.
3. Sahkan **Ya, Padam**.

Permohonan keluar daripada senarai aktif. Ibu Pejabat melihat tarikh dan alasan pada tab **Sejarah**.

Butang **Padam** tidak dipaparkan semasa permohonan sedang disemak Ibu Pejabat atau berada di peringkat draf perjanjian.

### Batal susulan

Lihat [Pindah dan lanjutan](#20-pindah-dan-lanjutan). Tindakan ini hanya untuk permohonan pindah atau lanjutan yang masih di langkah tindakan.

---

## 22. Senarai Kontrak Sewaan

Menu: **Kontrak Sewaan**, kemudian **Senarai Kontrak Sewaan**. Negeri hanya melihat kontrak negerinya. Pautan terus ke kontrak negeri lain ditolak.

### Tab

| Tab | Isi | Carian |
| --- | --- | --- |
| Senarai Aktif | Kontrak yang masih berkuat kuasa | Nama premis, negeri, kategori |
| Sejarah | Kontrak yang telah diganti atau tamat tempoh | Kontrak tamat tempoh |

Carian kosong memulihkan senarai asal.

### Butiran kontrak

Tekan baris untuk membuka **Butiran Kontrak Sewaan**. Halaman memaparkan:

- Kategori, negeri dan nama pemohon.
- Nama premis, jenis bangunan, kadar sewa, keluasan meter persegi dan kaki persegi, tarikh mula tawaran, tarikh akhir tawaran, nama pemilik dan alamat.
- Tarikh disahkan Ibu Pejabat, status, aliran kerja, tempoh kontrak, baki tempoh, tarikh mula dan tarikh tamat kontrak.
- Kemajuan permohonan yang telah dilalui.

Pada kontrak aktif negeri sendiri, butang **Pindah** dan **Lanjutan** memulakan permohonan susulan.

---

## 23. Jana Laporan

Menu **Jana Laporan** untuk Ibu Pejabat sahaja. Negeri tidak melihat menu ini. Cubaan membuka alamat laporan secara terus ditolak.

### Penapis

| Medan | Penerangan |
| --- | --- |
| Jenis Laporan | Pilih salah satu daripada lima jenis di bawah |
| Negeri | **Semua negeri**, atau satu negeri. Penapis negeri mengecilkan hasil kepada negeri itu sahaja |

Tekan **Papar Laporan** untuk melihat hasil pada skrin. Tekan **Eksport PDF** untuk memuat turun fail yang sama. Laporan yang lebar menggunakan halaman landskap. Kandungan PDF sepadan dengan skrin.

Di atas jadual, lima kad ringkasan dan pecahan **Status Tindakan** dipaparkan bagi penapis semasa.

### Jenis laporan

| Jenis | Kegunaan |
| --- | --- |
| Ringkasan Keseluruhan | Gabungan permohonan yang masih berjalan dan kontrak aktif, disusun mengikut negeri dan nama premis |
| Senarai Permohonan | Permohonan yang belum menjadi kontrak berkuat kuasa |
| Senarai Kontrak Aktif | Kontrak yang diluluskan, belum diganti dan belum tamat, disusun mengikut nama premis |
| Laporan Kontrak mengikut Negeri | Jadual lebar: negeri, PTJ, alamat, jenis bangunan, tarikh mula dan tamat, baki bulan, keluasan, kadar sewa, pemilik, kategori, status permohonan, kronologi proses Cawangan Pembangunan, rumusan dan tindakan |
| Laporan Status Sewaan | Tajuk menggunakan tarikh semasa, contoh "setakat" tarikh hari itu. Rekod dikumpulkan mengikut Pertimbangan BPH, Semakan PUU, Pertimbangan MOF, Pertimbangan KE, Semakan Ibu Pejabat dan Tindakan PTJ. Bahagian Tindakan PTJ disusun mengikut baki bulan paling rendah dahulu |

Pada laporan kontrak mengikut negeri, baki bulan kritikal diwarnakan merah dan baki amaran diwarnakan kuning. Status yang telah diterima diwarnakan hijau.

Jika tiada rekod bagi penapis itu, jadual memaparkan **Tiada rekod untuk laporan ini.**

---

## 24. Pengurusan pengguna

Menu: **Pengurusan Pengguna**.

### Pendaftaran Pengguna

Isi borang dan simpan.

| Medan | Penerangan |
| --- | --- |
| Nama | Nama penuh pengguna |
| Emel | Mestilah unik. Emel yang sudah didaftarkan ditolak |
| Kata laluan dan pengesahan | Ikut peraturan kata laluan |
| Peranan | Ibu Pejabat atau Negeri |
| Negeri | Wajib untuk peranan Negeri |

Had pendaftaran:

- Ibu Pejabat boleh mendaftar pengguna Ibu Pejabat, dan pengguna Negeri bagi mana-mana negeri. Pengguna Ibu Pejabat tidak diikat pada satu negeri.
- Negeri hanya boleh mendaftar peranan Negeri, dan negeri dikunci kepada negeri pentadbir. Pengguna Ibu Pejabat atau negeri lain tidak boleh didaftarkan.
- Akaun baharu yang aktif boleh log masuk. Jika pengesahan dua faktor dihidupkan, pengguna baharu menyiapkannya pada log masuk pertama.

### Senarai Pengguna

Senarai boleh dicari dan disusun mengikut nama, emel, status dan tarikh daftar.

Ibu Pejabat melihat dua jadual: **Ibu Pejabat** dan **Negeri**, dengan lajur negeri pada pengguna negeri. Negeri hanya melihat pengguna Negeri bagi negeri sendiri. Pengguna Ibu Pejabat dan negeri lain tidak dipaparkan.

| Lajur atau ikon | Fungsi |
| --- | --- |
| Status | **Aktif** atau **Nyahaktif**. Suis hijau atau merah hanya kelihatan kepada Ibu Pejabat, dan hanya untuk akaun orang lain |
| Urus profil | Membuka **Kemaskini profil** pengguna itu: nama, emel, peranan, negeri dan kata laluan baharu |
| Audit trail | Membuka jejak aktiviti pengguna |

Suis status meminta pengesahan, contoh "Nyahaktifkan akaun" atau "Aktifkan semula akaun". Akaun **Nyahaktif** tidak boleh log masuk. Ibu Pejabat tidak boleh menyahaktifkan akaun yang sedang digunakan sendiri.

### Jejak audit

Halaman **Audit Trail** menyenaraikan aktiviti berkaitan pengguna, yang terbaharu di atas. Setiap rekod menunjukkan tajuk, keterangan, tarikh, masa, sumber dan pelaku jika ada.

Contoh tajuk: Log masuk, Log keluar, Kemaskini profil, Reset kata laluan, Ubah peranan, Aktifkan akaun, Nyahaktif akaun, Akaun didaftarkan, Kemaskini emel jawatan, Hantar permohonan dan Muat naik dokumen.

Negeri tidak boleh membuka jejak pengguna negeri lain.

### Salinan Emel Jawatan

Bahagian ini pada **Senarai Pengguna**, untuk Ibu Pejabat sahaja. Empat jawatan disalin pada emel amaran kontrak yang berbaki 3 bulan:

- Ketua Pengarah
- TKPP
- PKP
- KPP

Tukar emel di sini apabila penyandang jawatan bertukar, kemudian simpan. Emel kosong atau format tidak sah ditolak. Nilai kekal selepas halaman dimuat semula. Negeri tidak melihat borang ini.

---

## 25. Peti masuk, lencana dan emel

### Peti Masuk dalam sistem

Ikon sampul di kepala halaman membuka pratonton notifikasi. Nombor merah menunjukkan bilangan belum dibaca. **Lihat semua** atau **Buka peti masuk penuh** membuka halaman **Peti Masuk**.

Setiap item menunjukkan kategori, tajuk, mesej dan masa. Tekan item untuk membuka permohonan atau kontrak yang berkaitan.

**Tanda semua sebagai dibaca** mengosongkan kiraan belum baca. Jika tiada item, skrin memaparkan **Tiada notifikasi baharu.**

Notifikasi dalam sistem dijana apabila aliran kerja bergerak, contoh permohonan dihantar ke Ibu Pejabat, draf dimuat naik, semakan diputuskan, dokumen dikembalikan, mati setem diminta, atau permohonan selesai. Item ditujukan kepada peranan yang perlu bertindak.

### Emel aliran kerja

Sistem menghantar emel kepada pentadbir yang berkenaan pada peristiwa berikut:

| Peristiwa | Penerima |
| --- | --- |
| Permohonan dihantar ke Ibu Pejabat | Ibu Pejabat |
| Ibu Pejabat meneruskan ke draf | Negeri yang terlibat |
| Draf dimuat naik | Pihak semakan yang ditetapkan |
| Semakan PUU diluluskan | Negeri |
| Semakan PUU dibatalkan | Negeri |
| Dokumen dikembalikan ke Cawangan Pembangunan | Ibu Pejabat |
| Dihantar untuk mati setem | Negeri |
| Permohonan selesai | Negeri dan pihak yang ditetapkan |
| Set semula kata laluan | Pemilik akaun |

### Peringatan tamat kontrak

Arahan berjadual dihantar pada jam 9.00 pagi waktu Malaysia. Penerima negeri ialah pentadbir negeri yang terlibat.

| Baki tempoh | Subjek emel | Kekerapan | Berhenti apabila |
| --- | --- | --- | --- |
| Bawah 8 bulan hingga 5 bulan | SPEKTRA: Peringatan Kontrak Bawah 8 Bulan | 3 kali dalam 3 hari | Baki mencecah 5 bulan, atau 3 penghantaran selesai |
| Bawah 5 bulan hingga 3 bulan | SPEKTRA: Peringatan Tegas Kontrak Bawah 5 Bulan | Setiap Isnin | Baki mencecah 3 bulan |
| 3 bulan atau kurang | SPEKTRA: AMARAN Kontrak Baki 3 Bulan | Setiap Isnin dan Rabu, dengan salinan kepada Ibu Pejabat dan emel jawatan | Permohonan dihantar ke Ibu Pejabat |

Emel amaran 3 bulan meminta negeri melengkapkan permohonan lanjutan atau pindah, kemudian menekan **Hantar ke Ibu Pejabat** dan mengesahkan **Ya, Hantar**. Selepas permohonan dihantar, peringatan bagi kontrak itu berhenti.

Peringatan tidak diulang di luar jadual di atas.

---

## 26. Peraturan input dan mesej biasa

| Perkara | Peraturan |
| --- | --- |
| Kata laluan | Sekurang-kurangnya 8 aksara, huruf besar, huruf kecil dan nombor. Dalam pengeluaran, kata laluan tercemar ditolak |
| Dokumen sokongan | PDF, JPG atau PNG, maksimum 10 MB |
| Draf perjanjian | PDF sahaja, had saiz yang sama |
| Alasan padam dan tarik semula | Sekurang-kurangnya 10 aksara, maksimum 2,000 aksara |
| Tarikh tawaran | Tarikh akhir mestilah selepas tarikh mula |
| Kadar sewa dan keluasan | Nombor sifar atau lebih |
| Negeri pada permohonan | Mesti sama dengan negeri pentadbir |
| Log masuk gagal berulang | Selepas had percubaan, sistem memperlahankan percubaan seterusnya |
| Akaun dinyahaktif | Log masuk ditolak |
| Rekod negeri lain | Tidak tersenarai. Pautan terus ditolak tanpa mendedahkan butiran |
| Pelawat tanpa log masuk | Papan pemuka, permohonan, kontrak, pengguna dan laporan mengembalikan pengguna ke log masuk |

Medan wajib yang kosong dipaparkan mesej di bawah medan. Betulkan nilai itu dan hantar semula. Rekod yang sudah sah tidak dipadam oleh cubaan yang gagal.

---

## 27. Glosari

| Istilah | Maksud dalam sistem |
| --- | --- |
| AADK | Agensi Antidadah Kebangsaan |
| BPH | Bahagian Hartanah, Jabatan Perdana Menteri |
| Cawangan Pembangunan | Pemilik sistem di Ibu Pejabat. Label skrin bagi peranan ini ialah Ibu Pejabat |
| Draf perjanjian | Fail PDF perjanjian, maksimum 40 MB. Status semasa ialah Semakan PUU. Sejarah menyimpan pusingan Semakan PUU 1, Semakan PUU 2, dan seterusnya |
| Ibu Pejabat | Peranan yang melihat semua negeri dan mengesahkan permohonan |
| JRP | Borang yang dilengkapkan negeri pada langkah tindakan |
| KE | Kementerian Ekonomi |
| Kontrak aktif | Kontrak yang telah selesai, belum diganti dan belum tamat |
| Kontrak diganti | Kontrak asal yang telah digantikan oleh permohonan pindah atau lanjutan yang diluluskan |
| LHDN | Lembaga Hasil Dalam Negeri, tempat setem dimatikan |
| MOF | Kementerian Kewangan |
| Mati setem | Peringkat akhir sebelum kontrak masuk ke senarai kontrak |
| Negeri | Peranan pentadbir yang hanya mengurus negeri sendiri |
| ProMIS | Sistem luar tempat salinan perjanjian dimuat naik. SPEKTRA merekod pengakuan muat naik |
| PTJ | Pusat Tanggungjawab. Dalam borang, nama premis diisi pada medan Nama Premis |
| PUU | Semakan perundangan ke atas draf perjanjian |
| Remark | Catatan wajib bagi permohonan pindah dan lanjutan sebelum dihantar |
| TKPP | Timbalan Ketua Pengarah Penguatkuasaan dan Pencegahan. Tandatangan fizikal direkod melalui pengakuan Ibu Pejabat |
| mps | Meter persegi |
| kps | Kaki persegi |

---

*Tamat dokumen AADK/CP/SPEKTRA/MANUAL/001, versi 1.0.*
