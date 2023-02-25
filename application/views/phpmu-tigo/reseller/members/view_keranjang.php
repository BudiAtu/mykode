
<p class='sidebar-title text-danger produk-title'> Berikut Data Pesanan anda</p>
<div class='col-md-8'>
<?php 
echo "<form action='".base_url()."members/selesai_belanja' method='POST'>";
  echo $error_reseller; 
  if ($this->session->idp == ''){
    echo "<center style='padding:10%'><i class='text-danger'>Maaf, Keranjang belanja anda saat ini masih kosong,...</i><br>
            <a class='btn btn-warning btn-sm' href='".base_url()."members/reseller'>Klik Disini Untuk mulai Belanja!</a></center>";
  }else{
?>

      <?php 
        echo "<a class='btn btn-success btn-sm' href='".base_url()."members/produk_reseller/$rows[id_konsumen]'>Lanjut Belanja</a>
              <a class='btn btn-danger btn-sm' href='".base_url()."members/batalkan_transaksi' onclick=\"return confirm('Apa anda yakin untuk Batalkan Transaksi ini?')\">Batalkan Transaksi</a>"; 
      ?>
      <div style="clear:both"><br></div>
      <table class="table table-striped table-condensed">
          <tbody>
        <?php 
          $no = 1;
          foreach ($record as $row){
          $ex = explode(';', $row['gambar']);
          if (trim($ex[0])==''){ $foto_produk = 'no-image.png'; }else{ $foto_produk = $ex[0]; }

          $sub_total = $row['harga_jual']-$row['diskon'];
          echo "<tr><td>$no</td>
                    <td width='70px'><img style='border:1px solid #cecece; width:60px' src='".base_url()."asset/foto_produk/$foto_produk'></td>
                    <td><a style='color:#ab0534' href='".base_url()."produk/detail/$row[produk_seo]'><b>$row[nama_produk]</b></a>
                        <br>Harga. Rp ".rupiah($row['harga_jual']-$row['diskon'])." / $row[satuan], 
                    <td>Rp ".rupiah($sub_total)."</td>
                    <td width='30px'><a class='btn btn-danger btn-xs' title='Delete' href='".base_url()."members/keranjang_delete/$row[id_penjualan_detail]'><span class='glyphicon glyphicon-remove'></span></a></td>
                </tr>";
            $no++;
          }
          $total = $this->db->query("SELECT sum(a.harga_jual-a.diskon) as total FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_penjualan='".$this->session->idp."'")->row_array();
          echo "<tr class='success'>
                  <td colspan='3'><b>Total Harga</b></td>
                  <td><b>$total[total]</b></td>
                  <td></td>
                </tr>

        </tbody>
      </table>

      <div class='col-md-4 pull-right'>
        <center>Total Bayar <br><h2 id='totalbayar'></h2>   
        <button type='submit' name='submit' id='oksimpan' class='btn btn-success btn-flat btn-sm' style=''>Lakukan Pembayaran</button>
        </center>
    </div>";

      $ket = $this->db->query("SELECT * FROM rb_keterangan where id_konsumen='".$rows['id_konsumen']."'")->row_array();
      $diskon_total = '0';
?>

<input type="hidden" name="total" id="total" value="<?php echo $total['total']; ?>"/>
<input type="hidden" name="diskonnilai" id="diskonnilai" value="<?php echo $diskon_total; ?>"/>


<?php
echo form_close();
?>
<script>
$(document).ready(function(){


$("#diskon").html(toDuit(0));
hitung();
});

function hitung(){
    var diskon=$('#diskonnilai').val();
    var total=$('#total').val();
    var bayar=parseFloat(total);
  
    $("#totalbayar").html(toDuit(bayar));
}
</script>

<?php 
echo "<div style='clear:both'></div><hr><br>$ket[keterangan]"; 
}
?>
</div>
<div class="col-sm-4 colom4">
  <?php $res = $this->db->query("SELECT * FROM rb_konsumen
                  where id_konsumen='$rows[id_konsumen]'")->row_array(); ?>
  <table class='table table-condensed'>
  <tbody>
    <tr class='alert alert-info'><th scope='row' style='width:90px'>Pengirim</th> <td><?php echo $res['nama_lengkap']?></td></tr>
    <tr class='alert alert-info'><th scope='row'>Alamat</th> <td><?php echo $res['alamat_lengkap'].', '.$res['nama_kota'].', '.$res['nama_provinsi']; ?></td></tr>
  </tbody>
  </table>

  <?php $usr = $this->db->query("SELECT * FROM rb_konsumen
                  where id_konsumen='".$this->session->id_konsumen."'")->row_array(); ?>
  <table class='table table-condensed'>
  <tbody>
    <tr class='alert alert-danger'><th scope='row' style='width:90px'>Penerima</th> <td><?php echo $usr['nama_lengkap']?></td></tr>
    <tr><th scope='row'>Alamat</th> <td><?php echo $usr['alamat_lengkap'].', '.$usr['nama_kota'].', '.$usr['nama_provinsi']; ?></td></tr>
  </tbody>
  </table>
    <img style='width:100%' src='<?php echo base_url(); ?>asset/foto_pasangiklan/ekpedisi2.jpg'>

</div>
