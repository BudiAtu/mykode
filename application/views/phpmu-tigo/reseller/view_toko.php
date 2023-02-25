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
        <a href='https://members.phpmu.com/members/account/PHP0060025'>
          <img class='tokoku1' src='https://members.phpmu.com/asset/members_lapak/no-image.jpg' ></a>
          <span class='name-header' style='margin-top:-5px'>$row[nama_lengkap]</span>
          <span class='deskripsi-header' style='font-size:14px;margin-top:30px;'>$row[alamat_lengkap]</span>
      </div>
    </div>
</div>";?>

<div id="posts">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <?php
        echo"
        <div class='post text'>
          <div class='flash-judul' data-judul='Produk'></div>
          <div class='flash-data' data-flashdata=''></div>
            <span class='hidden-xs'><br><br></span>
	          <div class='alert alert-success ftitle'>
            $row[nama_lengkap]
             <a target='_BLANK' href='' style='margin-top:-5px; margin-right:5px;' class='btn btn-info btn-sm pull-right'>
             <span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> 
             <span class='hidden-xs'>Ketentuan</span>
             </a>
	        	 <a target='_BLANK' href='' style='margin-top:-5px; margin-right:5px;' class='btn btn-success btn-sm pull-right'>
             <span class='glyphicon glyphicon-question-sign' aria-hidden='true'></span> 
             <span class='hidden-xs'>Keuntungan</span>
             </a>    
             <a target='_BLANK' href='".base_url()."members/profile' style='margin-top:-5px; margin-right:5px;' class='btn btn-danger btn-sm pull-right'>
             <span class='glyphicon glyphicon-question-sign' aria-hidden='true'></span> 
             <span class='hidden-xs'>Kembali</span>
             </a>    
          </div>";?>

		        <div style="clear:both"></div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="posts">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <?php 
        echo $this->session->flashdata('message'); 
        $this->session->unset_userdata('message');
        echo "
          <div class='col-md-3'>
            <div class='box box-widget widget-user'>
              <div class='widget-user-header bg-gray-active'></div>

                <div class='user-head text-center'>
                        <center>
                        <a href='".base_url().$this->uri->segment(1)."/produk' style='margin-top:3px; text-align:left' class='btn btn-primary btn-sm  btn-block'>
                        <i class='glyphicon glyphicon-user'></i> Data Produk
                        </a>
                        <a href='".base_url().$this->uri->segment(1)."/keterangan' style='margin-top:3px; text-align:left' class='btn btn-primary btn-sm  btn-block'>
                        <i class='glyphicon glyphicon-user'></i> Info/Keterangan
                        </a>
                        <a href='".base_url().$this->uri->segment(1)."/penjualan' style='margin-top:3px; text-align:left' class='btn btn-primary btn-sm  btn-block'>
                          <i class='glyphicon glyphicon-usd'></i> Data Penjualan
                        </a>
                        <a style='text-align:left; margin-top:3px;' href='".base_url().$this->uri->segment(1)."/rekening' class='btn btn-success btn-sm btn-block'>
                          <i class='glyphicon glyphicon-shopping-cart'></i> Data Rekening
                        </a>
                        <a style='text-align:left' href='".base_url().$this->uri->segment(1)."/pembayaran_konsumen'' class='btn btn-success btn-sm btn-block'>
                          <i class='glyphicon glyphicon-shopping-cart'></i> Data Pembayaran
                        </a>
                        <a style='text-align:left' href='".base_url()."members/toko' class='btn btn-success btn-sm btn-block'>
                        <i class='glyphicon glyphicon-home'></i> Toko Anda
                        </a>
                        
                        <a class='btn btn-default btn-xs btn-block badge-info' href=''>Jumlah Pelanggan 
                          <span class='badge'>0</span>
                        </a>
                        <a class='btn btn-default btn-xs btn-block badge-info' href=''>Pesanan Diterima 
                          <span class='badge'>0</span>
                        </a>
                        <a class='btn btn-default btn-xs btn-block badge-info' href=''>Login Terakhir 
                          <span class='badge'>2 Menit lalu</span>
                        </a>  </center>  
                        <hr>
                      
                </div>
              </div>	
            </div>";?>


            <section class="col-lg-7 connectedSortable">

            <div class="box box-success">
            <div class="box-header">
            <i class="fa fa-th-list"></i>
            <center><h3 class="box-title">10 Transaksi Penjualan Terbaru</h3> </center>
   
            </div>
            <div class="box-body">
              <table class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th style='width:40px'>No</th>
                      <th>Kode Transaksi</th>
                      <th>Status</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                <?php 
                  $no = 1;
                  $record = $this->model_reseller->penjualan_list_konsumen_top($this->session->id_konsumen,'reseller');
                  foreach ($record->result_array() as $row){
                  if ($row['proses']=='0'){ 
                    $proses = '<i class="text-danger">Pending</i>'; $status = 'Proses'; $icon = 'star-empty'; $ubah = 1; }elseif($row['proses']=='1'){ $proses = '<i class="text-success">Proses</i>'; $status = 'Pending'; $icon = 'star text-yellow'; $ubah = 0; }else{ $proses = '<i class="text-info">Konfirmasi</i>'; $status = 'Proses'; $icon = 'star'; $ubah = 1; }
                  $total = $this->db->query("SELECT sum(a.harga_jual-a.diskon) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
                  echo "<tr><td>$no</td>
                            <td><a href='".base_url().$this->uri->segment(1)."/detail_penjualan/$row[id_penjualan]'>$row[kode_transaksi]</a></td>
                            <td>$proses</td>
                            <td style='color:red;'>Rp ".rupiah($total['total']+$row['ongkir'])."</td>
                        </tr>";
                    $no++;
                  }
                ?>
                </tbody>
              </table>
              <a class='btn btn-default btn-block' href='<?php echo base_url().$this->uri->segment(1); ?>/penjualan'>Lihat Semua</a>
            </div>
            </div>
          </section><!-- right col -->

