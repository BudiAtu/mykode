<div id="posts">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        
      <center><p class='sidebar-title text-danger produk-title'> Data Profile Anda </p></center>
 
        <?php 
        echo $this->session->flashdata('message'); 
        $this->session->unset_userdata('message');
        echo "
          <div class='col-md-3'>
            <div class='box box-widget widget-user'>
              <div class='widget-user-header bg-gray-active'></div>
                <center><div class='widget-user-image'>";
                if (trim($row['foto'])=='' OR !file_exists("asset/foto_user/".$row['foto'])){
                   echo "<span id='foto-profile'><img style='height:120px; width:120px' class='img-circle'  src='".base_url()."asset/foto_user/blank.png'alt='User Avatar'></span>";
                }else{
                  echo "<span id='foto-profile'><img style='height:120px; width:120px' class='img-circle'  src='".base_url()."asset/foto_user/$row[foto]' alt='User Avatar'></span>";
                } 
                echo "
                </div></center>
              </div>
              <div class='user'>
                <div class='user-head text-center'>
                  <span style='color:green' class='glyphicon glyphicon-ok-sign' aria-hidden='true'></span> 
                    <span style='color:green'>Verified</span>
                      <h3 style='margin:3px 0px'>$row[nama_lengkap]</h3>
                        <h5>Popularitas : 
                          <i class='fa fa-star-o text-red'></i>
                          <i class='fa fa-star-o text-red'></i>
                          <i class='fa fa-star-o text-red'></i>
                          <i class='fa fa-star-o text-red'></i>
                          <i class='fa fa-star-o text-red'></i>
                          <i class='fa fa-star-o text-red'></i>
                          <i class='fa fa-star-o text-red'></i>
                        </h5>
                        <h5 style='margin-bottom:0px'>Saldo : Rp 0</h5>
                        <small>
                          <i><a data-toggle='modal' href='#saldoanda' data-target='#saldoanda'>Dimana uang anda disimpan?</a></i>
                        </small>
                        <br><br>
                        <center>
                        <a href='".base_url()."members/edit_profile' style='margin-top:3px; text-align:left' class='btn btn-primary btn-sm  btn-block'>
                        <i class='glyphicon glyphicon-user'></i> Edit Profile
                        </a>
                        <a href='' style='margin-top:3px; text-align:left' class='btn btn-primary btn-sm  btn-block'>
                          <i class='glyphicon glyphicon-usd'></i> Tarik Dana (Withdraw)
                        </a>
                        <a style='text-align:left; margin-top:3px;' href='' class='btn btn-success btn-sm btn-block'>
                          <i class='glyphicon glyphicon-shopping-cart'></i> Data Pembelian
                        </a>
                        <a style='text-align:left' href='' class='btn btn-success btn-sm btn-block'>
                          <i class='glyphicon glyphicon-shopping-cart'></i> Data Penjualan
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
            </div>
<div class='col-md-8 py-5'>
              <div class='col-md-9 animated fadeIn' style='padding-right:10px; padding-left:10px;'>
                <div class='alert alert-warning'><strong>Penting</strong> - Saat ini anda menggunakan No telpon sebagai Password, Harap ganti segera demi keamanan akun!
              </div>                
              <div class='box-body'>
 
                  <a style='text-decoration:underline; color:blue;' class='pull-right' data-toggle='modal' href='#notifikasi' data-target='#notifikasi'><i class='fa fa-bell-o'></i> Notifikasi</a> &nbsp; 
                    <a style='text-decoration:underline; color:orangered; margin-right:10px' class='pull-right' data-toggle='modal' href='#privasi' data-target='#privasi'><i class='fa fa-key fa-fw'></i> Privasi</a>                    
                      
                    <dl class='dl-horizontal'>
										    <dt>Link Referral Anda</dt>		
                          <dd style='margin-bottom:10px'><a target='_BLANK' href='' class='referralmu'>$row[referral]</a>					
                            <button type='button' style='border-radius:5px !important' id='copy' data-toggle='button' title='Copy Link Referral' aria-pressed='false' autocomplete='off' class='btn btn-default btn-xs myButton' onclick='copyToClipboard('.referralmu')'>
                              <span style='font-size:12px' class='fa fa-copy'></span> Copy
                            </button><br>
													    <i style='font-size:11px'>Fee Rp 50,000 / Sponsorisasi, Baca tentang Program Referral <a target='_BLANK' href=''><b>Disini</b></a></i>
                          </dd>
					                <dt>Username</dt>		<dd>$row[username]</dd>
					                <dt>Nama Lengkap</dt>		<dd>$row[nama_lengkap]</dd>
                          <dt>Alamat Email</dt>		<dd><span style='color:red'>$row[email]</span></dd>
                          <dt>Password</dt>			<dd><span style='color:red'></span></dd>
                          <dt>No Telpon</dt>			<dd>$row[no_hp] </dd>
                          <dt>Whatsapp</dt>			<dd>$row[no_hp] </dd>
                          <dt>Jenis Kelamin</dt>		<dd>$row[jenis_kelamin]</dd>
                          <dt>Pekerjaan</dt>			<dd>$row[pekerjaan]</dd>
                          <dt>Alamat Lengkap</dt>		<dd class='dont-break-out'>$row[alamat_lengkap]</dd>
                    
                          <hr style='margin-top: 10px; margin-bottom: 10px; border-top: 1px solid #fff;'>
                          <dt>Nama Bank</dt>		<dd>$row[nama_bank]<i class='text-success'>Nama Bank Belum ada...</i></dd>
                          <dt>Atas Nama</dt>		<dd><i class='text-success'>Nama Pemegang Rekening Belum ada...</i></dd>
                          <dt>No Rekening</dt>		<dd><i class='text-success'>No Rekening Belum ada...</i></dd>
                          <dt>Login Terakhir</dt>		<dd><i>20 Jam lalu, - 26 Jan 2023 13:54:11 WIB</i></dd>
                          <dt>Update Profile</dt>		<dd><span style='color:red'>3 dari 3 Kali</span> <br><small style='color:#000'><i>(Maksimal Update Profile Hanya 3 Kali)</i></small></dd>					                    <dt>Website / Blog</dt>		<dd><i>Alamat Website/Blog Belum ada...</i></dd>
                    
                          <dt>Status</dt>		<dd><span style='color:red;'>Free Account</span> &nbsp; <a href='https://members.phpmu.com/members/welcome' onmouseover='this.style.color='red' onmouseout='this.style.color='green'' class='label label-default blink_me' style='color:green; background-color:#fff; border:1px solid'> Upgrade Akun Premium</a></dd>
                          <dt>Waktu Join</dt>		<dd>$row[tanggal_daftar]<br></dd>
                          <dt>Tentang Saya</dt>		<dd class='dont-break-out'><i>Tidak ditemukan informasi detail tentang budi atu saja...</i></dd>
                      </div></div>
                      </div>";

                      echo "<table class='table table-hover table-condensed'>
                      <thead>
                        <tr>
                          <th width='20px'>No</th>
                          <th>Nama Penjual</th>
                          <th>Belanja</th>
                          <th>Status</th>
                          <th>Total</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>";
                    
                          $no = 1;
                          $record = $this->model_reseller->orders_report($this->session->id_konsumen,'reseller');
                          foreach ($record->result_array() as $row){
                          if ($row['proses']=='0'){ $proses = '<i class="text-danger">Pending</i>'; }elseif($row['proses']=='1'){ $proses = '<i class="text-success">Proses</i>'; }else{ $proses = '<i class="text-info">Konfirmasi</i>'; }
                          $total = $this->db->query("SELECT sum(a.harga_jual-a.diskon) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
                          echo "<tr><td>$no</td>
                                    <td><a href='".base_url()."members/detail_reseller/$row[id_konsumen]'><small><b>$row[nama_lengkap]</b></small><br><small class='text-success'>$row[kode_transaksi]</small></a></td>
                                    <td><span style='color:blue;'>Rp ".rupiah($total['total'])."</span></td>
                                    <td>$proses <br><small>$row[nama_lengkap]</small></td>
                                    <td width='130px'>";
                                    if ($row['proses']=='0'){
                                      echo "<a style='margin-right:3px' class='btn btn-success btn-sm' title='Konfirmasi Pembayaran' href='".base_url()."konfirmasi?kode=$row[kode_transaksi]'>Konfirmasi</a>";
                                    }else{
                                      echo "<a style='margin-right:3px' class='btn btn-default btn-sm' href='#'  onclick=\"return confirm('Maaf, Pembayaran ini sudah di konfirmasi!')\">Konfirmasi</a>";
                                    }
                                  
                                  echo "<a class='btn btn-info btn-sm' title='Detail data pesanan' href='".base_url()."members/keranjang_detail/$row[id_penjualan]'><span class='glyphicon glyphicon-search'></span></a>";
                                  echo "<a class='btn btn-info btn-danger' title='Detail data pesanan' href='".base_url()."members/batalkan_transaksi/$row[id_penjualan]'><span class='glyphicon glyphicon-search'></span></a></td>
                                </tr>";
                            $no++;
                          }
                    
                      echo "</tbody>
                    </table>
					  
            </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>";?>
