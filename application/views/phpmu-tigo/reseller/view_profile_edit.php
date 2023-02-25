<div id="posts">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        
      <center><p class='sidebar-title text-danger produk-title'> Edit Data Profile Anda </p></center>
 
        <?php 
        echo $this->session->flashdata('message'); 
        $this->session->unset_userdata('message');
        echo "
          <div class='col-md-3'>
            <div class='box box-widget widget-user hidden-xs'>
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
              <div class='user hidden-xs'>
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
 
        <p>Berikut Informasi Data Profile anda.<br> 
           Pastikan data-data dibawah ini sudah benar, agar tidak terjadi kesalahan saat transaksi.</p>";                
                  $attributes = array('id' => 'formku','class'=>'form-horizontal','role'=>'form');
                  echo form_open_multipart('members/edit_profile',$attributes); 
                  echo "<table class='table table-hover table-condensed'>
                        <thead>
                          <tr><td width='140px'><b>Username</b></td> <td><input class='required form-control' style='width:50%; display:inline-block' name='aa' type='text' value='$row[username]'></td></tr>
                          <tr><td><b>Password</b></td>       <td><input class='form-control' style='width:50%; display:inline-block' type='password' name='a'> <small style='color:red'><i>Kosongkan Saja JIka Tidak ubah.</i></small></td></tr>
                          <tr><td><b>Nama Lengkap</b></td>   <td><input class='required form-control' type='text' name='b' value='$row[nama_lengkap]'></td></tr>
                          <tr><td><b>Email</b></td>          <td><input class='required email form-control' type='email' name='c' value='$row[email]'></td></tr>
                          <tr><td><b>Jenis Kelamin</b></td>  <td>"; if ($row['jenis_kelamin']=='Laki-laki'){ echo "<input type='radio' value='Laki-laki' name='d' checked> Laki-laki <input type='radio' value='Perempuan' name='d'> Perempuan "; }else{ echo "<input type='radio' value='Laki-laki' name='d'> Laki-laki <input type='radio' value='Perempuan' name='d' checked> Perempuan "; } echo "</td></tr>
                          <tr><td><b>Alamat</b></td>         <td><textarea class='required form-control' name='g'>$row[alamat_lengkap]</textarea></td></tr>
                          <tr><td><b>Pekerjaan</b></td>         <td><input class='required form-control' name='e' value='$row[pekerjaan]'></td></tr>
                          <tr><td><b>No Hp</b></td>                  <td><input style='width:40%' class='required number form-control' type='number' name='l' value='$row[no_hp]'></td></tr>
                          <tr><td><b>Keterangan</b></td>         <td><textarea class='required form-control' name='m'>$row[keterangan]</textarea></td></tr>
                          <tr><td>Foto</td>  <td><input style='width:40%' class='form-control' type='file' name='foto'></td></tr>
                          <tr><td><b>Referral</b></td>         <td><input class='required form-control' name='n' value='$row[referral]'></td></tr>
                         
                          <tr><td></td><td><input class='btn btn-sm btn-primary' type='submit' name='submit' value='Simpan Perubahan'></td></tr>
                        </thead>
                    </table>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>";?>


<!--<?php 
  echo "<p class='sidebar-title text-danger produk-title'> Edit Data Profile Anda</p>
        <p>Berikut Informasi Data Profile anda.<br> 
           Pastikan data-data dibawah ini sudah benar, agar tidak terjadi kesalahan saat transaksi.</p>";                
                  $attributes = array('id' => 'formku','class'=>'form-horizontal','role'=>'form');
                  echo form_open_multipart('members/edit_profile',$attributes); 
                  echo "<table class='table table-hover table-condensed'>
                        <thead>
                          <tr><td width='140px'><b>Username</b></td> <td><input class='required form-control' style='width:50%; display:inline-block' name='aa' type='text' value='$row[username]'></td></tr>
                          <tr><td><b>Password</b></td>       <td><input class='form-control' style='width:50%; display:inline-block' type='password' name='a'> <small style='color:red'><i>Kosongkan Saja JIka Tidak ubah.</i></small></td></tr>
                          <tr><td><b>Nama Lengkap</b></td>   <td><input class='required form-control' type='text' name='b' value='$row[nama_lengkap]'></td></tr>
                          <tr><td><b>Email</b></td>          <td><input class='required email form-control' type='email' name='c' value='$row[email]'></td></tr>
                          <tr><td><b>Jenis Kelamin</b></td>  <td>"; if ($row['jenis_kelamin']=='Laki-laki'){ echo "<input type='radio' value='Laki-laki' name='d' checked> Laki-laki <input type='radio' value='Perempuan' name='d'> Perempuan "; }else{ echo "<input type='radio' value='Laki-laki' name='d'> Laki-laki <input type='radio' value='Perempuan' name='d' checked> Perempuan "; } echo "</td></tr>
                          <tr><td><b>Tanggal Lahir</b></td>  <td><input class='required datepicker form-control' type='text' name='e' value='$row[tanggal_lahir]' data-date-format='yyyy-mm-dd'></td></tr>
                          <tr><td><b>Tempat Lahir</b></td>   <td><input class='required form-control' type='text' name='f' value='$row[tempat_lahir]'></td></tr>
                          <tr><td><b>Alamat</b></td>         <td><textarea class='required form-control' name='g'>$row[alamat_lengkap]</textarea></td></tr>
                          <tr><td><b>Kecamatan</b></td>  <td><input type='text' class='required form-control' name='k' value='$row[kecamatan]'></td></tr>
                          <tr><td><b>No Hp</b></td>                  <td><input style='width:40%' class='required number form-control' type='number' name='l' value='$row[no_hp]'></td></tr>
                          <tr><td><b>Keterangan</b></td>         <td><textarea class='required form-control' name='m'>$row[Keterangan]</textarea></td></tr>
                          <tr><td>Foto</td>  <td><input style='width:40%' class='form-control' type='file' name='foto'></td></tr>
                         
                          <tr><td></td><td><input class='btn btn-sm btn-primary' type='submit' name='submit' value='Simpan Perubahan'></td></tr>
                        </thead>
                    </table>";
                  echo form_close();
?>-->