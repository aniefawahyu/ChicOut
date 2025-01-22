# ChicOut - E-Commerce Penjualan Fashion Style

### _**Created By:**_ ChicOut 
- **Florencia - 222180554**  
- **Aniefa - 222180551**  
- **Defson - 222180553**  
- **Praisezilia - 222180565**  

## Deskripsi  
ChicOut hadir sebagai toko baju dengan konsep klasik yang dirancang untuk memberikan kemudahan dan kenyamanan bagi pengguna dalam berbelanja.

## API  
Kami menggunakan API:  
1.  Midtrans
2.  Google Maps
3.  

## Fitur  

### Guest Show:  
1. Pengguna yang belum login dapat melihat semua produk yang tersedia, namun tidak dapat melakukan transaksi apapun.  
2. Pengguna dapat melakukan registrasi jika belum memiliki akun, dan login bila telah mendaftar.
3. Pengguna dapat melakukan transaksi setelah berhasil login.    

### Customer Show:  
1. Customer yang login dapat melihat semua produk yang tersedia, dan dapat melakukan transaksi menggunakan **API MIDTRANS**.  
2. Dapat melakukan pembatalan, penambahan, dan pengurangan pada keranjang belanja sebelum melakukan pembayaran.  
3. Customer bisa memberikan ulasan produk dengan catatan harus membeli produk terlebih dahulu. Customer juga dapat melihat ulasan dari orang lain yang sudah membeli dan memberikan ulasan produk.  
4. Customer dapat mengedit profil untuk mengubah password, display name, dan alamat.  
5. Customer dapat melihat riwayat pembelian dari menu profil. 

### Staff Show:  
1. Mengatur stok barang.  
2. Melakukan CRUD (Create, Read, Update, Delete) Items.  
3. Melakukan CRUD Category.  
4. Edit profil.

### Admin Show:  
1. Admin dapat melihat laporan penjualan dengan tampilan grafik dan dapat mencetak dalam format Excel.  
2. Admin dapat melakukan CRUD Items di semua kategori.  
3. Admin dapat melakukan CRUD kategori.
4. Admin dapat melakukan CRUD brand.  
5. Admin dapat mengubah role pengguna / karyawan.  
6. Admin dapat menyimpan daftar produk secara lokal dan mengunduhnya dalam bentuk file Excel.  
7. Edit profil.

### Cara Penggunaan

## User
- User / Guest sebelum melakukan login dapat melihat semua list item yang ada namun tidak dapat melakukan transaksi pembelian. User / Guest dapat login jika sudah terdaftar jika belum maka user dapat melakukan registrasi terlebih dahulu kemudian login.
- Pada saat login user dapat memasukan username dan password yang digunakan pada saat registrasi, user dapat melakukan pembelian dengan memasukan barang ke dalam cart, dan bisa melakukan transaksi pembelian dengan midtrans. User yang sudah membeli barang, dapat memberikan review/ulasan pada halaman barang yang telah dibeli. User juga dapat melihat history/riwayat pembeliannya serta dapat melakukan retur barang.

## Admin
- Admin dapat melakukan pengecekan transaksi menggunakan chart yang sudah terintegrasi dengan transaksi yang dilakukan user dan dapat mengunduhnya dalam bentuk file excel, kemudian dapat melihat semua daftar transaksi yang pernah dilakukan semua user di halaman home
- Pada halaman item, admin dapat menambah, mengedit, dan menghapus barang. Untuk menambah barang baru, admin harus mengisi form tambah barang baru yang berisi nama barang, quantity, deskripsi, harga, discount, serta link gambar yang sesuai.
- Pada halaman category, admin dapat menambah, mengedit, dan menghapus kategori dengan mengisi form tambah kategori baru yang berisi nama kategori dan link gambar yang sesuai.
- Pada halaman payment, admin dapat menambah, mengedit, dan menghapus payment dengan mengisi form tambah payment baru yang berisi jenis pembayaran, nomor rekening serta logo payment.
- Pada halaman brand, admin dapat menambah, mengedit, dan menghapus brand dengan mengisi form tambah brand baru yang berisi nama brand, serta logo payment.
- Admin juga dapat mengubah role user dan melihat detail profilenya.

## Staff
- Staff dapat melihat riwayat transaksi yang dilakukan semua user
- Pada halaman item, admin dapat menambah, mengedit, dan menghapus barang. Untuk menambah barang baru, admin harus mengisi form tambah barang baru yang berisi nama barang, quantity, deskripsi, harga, discount, serta link gambar yang sesuai.
- Pada halaman category, admin dapat menambah, mengedit, dan menghapus kategori dengan mengisi form tambah kategori baru yang berisi nama kategori dan link gambar yang sesuai.
- Pada halaman profile, staff dapat mengedit data pribadi yang berisi nama, nomor telepon.
