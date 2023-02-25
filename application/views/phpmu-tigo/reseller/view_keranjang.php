<p class='sidebar-title text-danger produk-title'> Berikut Data Pesanan anda</p>
<div class='col-md-8'>
<?php 
  echo $this->session->flashdata('message'); 
  $this->session->unset_userdata('message');
  if ($this->session->idp == ''){
    echo "<center style='padding:15%'><i class='text-danger'>Maaf, Keranjang belanja anda saat ini masih kosong,...</i><br>
            <a class='btn btn-warning btn-sm' href='".base_url()."produk'>Klik Disini Untuk mulai Belanja!</a></center>";
  }else{
?>

      <table class="table table-striped">
          <tbody>
        <?php 
          $no = 1;
          foreach ($record as $row){
          $sub_total = $row['harga_jual']-$row['diskon'];
          $ex = explode(';', $row['gambar']);
          if (trim($ex[0])==''){ $foto_produk = 'no-image.png'; }else{ $foto_produk = $ex[0]; }
          echo "<tr><td>$no</td>
                    <td width='70px'><img style='border:1px solid #cecece; width:60px' src='".base_url()."asset/foto_produk/$foto_produk'></td>
                    <td><a style='color:#ab0534' href='".base_url()."produk/detail/$row[produk_seo]'><b>$row[nama_produk]</b></a>
                        <br>Qty. <b>$row[jumlah]</b>, Harga. Rp ".rupiah($row['harga_jual']-$row['diskon'])." / $row[satuan], 
                    <td>Rp ".rupiah($sub_total)."</td>
                    <td width='30px'><a class='btn btn-danger btn-xs' title='Delete' href='".base_url()."produk/keranjang_delete/$row[id_penjualan_detail]'><span class='glyphicon glyphicon-remove'></span></a></td>
                </tr>";
            $no++;
          }
          $total = $this->db->query("SELECT sum(a.harga_jual-a.diskon) as total FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->idp."'")->row_array();
          echo "<tr>
                  <td colspan='3'><b>Total Harga</b></td>
                  <td><b>Rp ".rupiah($total['total'])."</b></td>
                  <td></td>
                </tr>


        </tbody>
      </table>

      <a class='btn btn-success btn-sm' href='".base_url()."produk/produk_reseller/$rows[id_konsumen]'>Lanjut Belanja</a>
      <a class='btn btn-primary btn-sm' href='".base_url()."produk/checkouts'>Selesai Belanja</a>";

      $ket = $this->db->query("SELECT * FROM rb_keterangan where id_konsumen='".$rows['id_konsumen']."'")->row_array();
      echo "<hr><br>$ket[keterangan]";
}
?>
</div>

</div>
<div class="col-sm-4 colom44">
  <?php $res = $this->db->query("SELECT * FROM rb_konsumen
                  where id_konsumen='$rows[id_konsumen]'")->row_array(); ?>
  <table class='table table-condensed'>
  <tbody>
    <tr class='alert alert-info'><th scope='row' style='width:90px'>Pengirim</th> <td><?php echo $res['nama_lengkap']?></td></tr>
    <tr><th scope='row'>Email</th> <td><?php echo $rows['email']?></td></tr>
    
    <tr><th scope='row'>Alamat</th> <td><?php echo $res['alamat_lengkap'].', '.$res['nama_kota'].', '.$res['nama_provinsi']; ?></td></tr>
    <tr><th scope='row'>Keterangan</th> <td><?php echo $rows['keterangan']?></td></tr>
  </tbody>
  </table>
  <img style='width:100%' src='<?php echo base_url(); ?>asset/foto_pasangiklan/ekpedisi2.jpg'>
  <hr>
</div>