<?php
// panggil berkas koneksi.php
require 'koneksi.php';
 
// buat koneksi ke database mysql
koneksi_buka();
 
?>
 
<table class="table table-condensed table-bordered table-hover" cellpadding="0" cellspacing="0">
<thead>
    <tr>
        <th style="width:20px">#</th>
        <th style="width:120px">NIM</th>
        <th style="width:200px">Nama</th>
        <th>Alamat</th>
        <th style="width:120px">Kelas</th>
        <th style="width:120px">Status</th>
        <th style="width:40px"></th>
    </tr>
</thead>
<tbody>
    <?php 
        $i = 1;
        $jml_per_halaman = 5; // jumlah data yg ditampilkan perhalaman
        $jml_data = mysql_num_rows(mysql_query("SELECT * FROM mahasiswa"));
        $jml_halaman = ceil($jml_data / $jml_per_halaman);
        // query pada saat mode pencarian
        if(isset($_POST['cari'])) {
            $kunci = $_POST['cari'];
            echo "<strong>Hasil pencarian untuk kata kunci $kunci</strong>";
            $query = mysql_query("
                SELECT * FROM mahasiswa 
                WHERE nim LIKE '%$kunci%'
                OR nama LIKE '%$kunci%'
                OR alamat LIKE '%$kunci%'
                OR kelas LIKE '%$kunci%'
                OR status LIKE '%$kunci%'
            ");
        // query jika nomor halaman sudah ditentukan
        } elseif(isset($_POST['halaman'])) {
            $halaman = $_POST['halaman'];
            echo $halaman;
            // $i = ($halaman - 1) * $jml_per_halaman  + 1;
            $query = mysql_query("SELECT * FROM mahasiswa LIMIT ".(($halaman - 1) * $jml_per_halaman).", $jml_per_halaman");
        // query ketika tidak ada parameter halaman maupun pencarian
        } else {
            $query = mysql_query("SELECT * FROM mahasiswa LIMIT 0, $jml_per_halaman");
            $halaman = 1; //tambahan
        }
         
        // tampilkan data mahasiswa selama masih ada
        while($data = mysql_fetch_array($query)) {
            if($data['status']==1) {
                $status = "Aktif";
            } else {
                $status = "Tidak Aktif";
            }
    ?>
    <tr>
        <td><?php echo $i ?></td>
        <td><?php echo $data['nim'] ?></td>
        <td><?php echo $data['nama'] ?></td>
        <td><?php echo $data['alamat'] ?></td>
        <td><?php echo $data['kelas'] ?></td>
        <td><?php echo $status ?></td>
        <td>
            <a href="#dialog-data" id="<?php echo $data['kd_mhs'] ?>" class="ubah" data-toggle="modal">
                <i class="icon-pencil"></i>
            </a>
            <a href="#" id="<?php echo $data['kd_mhs'] ?>" class="hapus">
                <i class="icon-trash"></i>
            </a>
        </td>
    </tr>
    <?php
        $i++;
        }
    ?>
</tbody>
</table>
 
<?php if(!isset($_POST['cari'])) { ?>
<!-- untuk menampilkan menu halaman -->
<div class="pagination pagination-right">
  <ul>
    <?php

    // tambahan
    // panjang pagig yang akan ditampilkan
    $no_hal_tampil = 5; // lebih besar dari 3

    if ($jml_halaman <= $no_hal_tampil) {
        $no_hal_awal = 1;
        $no_hal_akhir = $jml_halaman;
    } else {
        // $val = $no_hal_tampil - 2; //3
        // $mod = $halaman % $val; //
        // $kelipatan = ceil($halaman/$val);
        // $kelipatan2 = floor($halaman/$val);

        // if($halaman < $no_hal_tampil) {
        //     $no_hal_awal = 1;
        //     $no_hal_akhir = $no_hal_tampil;
        // } elseif ($mod == 2) {
        //     $no_hal_awal = $halaman - 1;
        //     $no_hal_akhir = $kelipatan * $val + 2;
        // } else {
        //     $no_hal_awal = ($kelipatan2 - 1) * $val + 1;
        //     $no_hal_akhir = $kelipatan2 * $val + 2;
        // }

        // if($jml_halaman <= $no_hal_akhir) {
        //     $no_hal_akhir = $jml_halaman;
        // }
    }
    $no_hal_awal = 1;
        $no_hal_akhir = $jml_halaman;
    for($i = $no_hal_awal; $i <= $no_hal_akhir; $i++) {
        // tambahan
        // menambahkan class active pada tag li
        $aktif = $i == $halaman ? ' active' : '';
    ?>
    <li class="halaman<?php echo $aktif ?>" id="<?php echo $i ?>"><a href="#"><?php echo $i ?></a></li>
    <?php } ?>
  </ul>
</div>
<?php } ?>
 
<?php 
// tutup koneksi ke database mysql
koneksi_tutup(); 
?>