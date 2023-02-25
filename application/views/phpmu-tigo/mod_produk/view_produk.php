<?php 
$pembelian = $this->model_reseller->pembelian($this->session->id_konsumen)->row_array();
$penjualan_perusahaan = $this->model_reseller->penjualan_perusahaan($this->session->id_konsumen)->row_array();
$penjualan = $this->model_reseller->penjualan($this->session->id_konsumen)->row_array();
$modal_perusahaan = $this->model_reseller->modal_perusahaan($this->session->id_konsumen)->row_array();
$modal_pribadi = $this->model_reseller->modal_pribadi($this->session->id_konsumen)->row_array();
$set = $this->db->query("SELECT * FROM rb_setting where aktif='Y'")->row_array();
?>

<?php
echo"
<div class='img-wrapper'>
  <img class='tokoku' src='https://members.phpmu.com/asset/header/logo.png'>
    <div class='img-overlay'>
      <div class='header-profile hidden-xs hidden-sm hidden-md'>
        <a href=''>
          <img class='tokoku1' src='https://members.phpmu.com/asset/members_lapak/no-image.jpg' ></a>
          <span class='name-header' style='margin-top:-5px'>$row[nama_lengkap]</span>
          <span class='deskripsi-header' style='font-size:14px;margin-top:30px;'>$row[alamat_lengkap]</span>
      </div>
    </div>
</div>";?><br><br>       
          
           <div class="col-xs-12">  
<?php
echo"
                  <center><h3 class='box-title'>Data Stok Produk anda</h3></center>
                  <a class='pull-right btn btn-primary btn-sm' href='".base_url().$this->uri->segment(1)."/tambah_produk'>Tambahkan Data</a>
                </div><!-- /.box-header -->
                <div class='box-body'>
                  <table id='' class='table table-bordered table-striped table-condensed'>
                    <thead>
                      <tr>
                        <th style='width:30px'>No</th>
                        <th>cover</th>
                        <th>Nama Produk</th>
                        <th>Harga Modal</th>
                        <th>Harga Jual</th>
                        <th>Url Demo</th>
                        <th>Sourcode Url (Gram)</th>
                        <th>Diskon (Rp)</th>
                        <th style='width:80px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>";?>
                  <?php 
                    $no = 1;
                    foreach ($record as $row){
                      $jual = $this->model_reseller->jual_reseller($this->session->id_konsumen,$row['id_produk'])->row_array();
                      $beli = $this->model_reseller->beli_reseller($this->session->id_konsumen,$row['id_produk'])->row_array();
                      $disk = $this->model_app->edit('rb_produk_diskon',array('id_produk'=>$row['id_produk'],'id_konsumen'=>$this->session->id_konsumen))->row_array();
                      if ($disk['diskon']=='' OR $disk['diskon']=='0'){ $diskon = '0'; $line = ''; $harga = ''; }else{ $diskon = $disk['diskon']; $line = 'line-through'; $harga = "/ <span style='color:red'>".rupiah($row['harga_konsumen']-$disk['diskon'])."</span>";}
                      if ($row['id_produk_perusahaan']!='0'){ $perusahaan = "<small><i style='color:green'>(Perusahaan)</i></small>"; }else{ $perusahaan = ''; }
                      if ($row['id_produk_perusahaan']=='0'){ $modal = $row['harga_beli'];  }else{ $modal = $row['harga_reseller']; }
                    echo "<tr><td>$no</td>
                    <td><img src='".base_url()."asset/foto_produk/$row[gambar]' width='50'></td>
                              <td>$row[nama_produk] $perusahaan</td>
                              <td>Rp ".rupiah($modal)."</td>
                              <td>Rp <span style='text-decoration:$line'>".rupiah($row['harga_konsumen'])."</span> $harga</td>
                              <td>$row[url_demo]</td>
                              <td>$row[sorurl]</td>
                              <td>$diskon</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='".base_url()."members/edit_produk/$row[id_produk]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='".base_url()."members/delete_produk/$row[id_produk]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table><hr>
              </div>