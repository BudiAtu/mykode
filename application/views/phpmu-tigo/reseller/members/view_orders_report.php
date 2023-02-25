<p class='sidebar-title text-danger produk-title'> Laporan Data Pesanan Anda</p>
              <?php 
                if ($this->uri->segment(3)=='success'){
                  echo "<div class='alert alert-success'><b>SUCCESS</b> - Terima kasih telah Melakukan Konfirmasi Pembayaran!</div>";
                }elseif($this->uri->segment(3)=='orders'){
                  echo "<div class='alert alert-success'><b>SUCCESS</b> - Orderan anda sukses terkirim, silahkan melakukan pembayaran ke rekening reseller pesanan anda dan selanjutnya lakukan konfirmasi pembayaran!</div>";
                }
              ?>
              <table id='example2' style='overflow-x:scroll; width:96%' class="table table-striped table-condensed">
                <thead>
                  <tr>
                    <th width="20px">No</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Lapak</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                    <th>Total + Ongkir</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record->result_array() as $row){
                    if ($row['proses']=='0'){ 
                      $proses = '<i class="text-danger">Pending</i>'; 
                    }elseif($row['proses']=='1'){ 
                      $proses = '<i class="text-success">Proses</i>'; 
                    }elseif($row['proses']=='3'){ 
                      $proses = '<i class="text-success">Dikirim</i>'; 
                    }elseif($row['proses']=='4'){ 
                      $proses = '<i class="text-success">Diterima</i>';
                    }elseif($row['proses']=='5'){ 
                      $proses = '<i class="text-success">Selesai</i>';  
                    }else{ $proses = '<i class="text-info">Konfirmasi</i>'; }
                    $total = $this->db->query("SELECT sum(a.harga_jual-a.diskon) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
                    echo "<tr><td>$no</td>
                              <td><span class='text-success'>$row[kode_transaksi]</span></td>
                              <td><a href='".base_url()."members/detail_reseller/$row[id_konsumen]'>$row[nama_lengkap]</a></td>
                              <td><span style='color:blue;'>Rp ".rupiah($total['total'])."</span></td>
                              <td>$proses</td>
                              <td style='color:red;'>Rp ".rupiah($total['total'])."</td>
                              <td width='130px'>";
                if ($row['proses']=='0'){
                  echo "<a style='margin-right:3px' class='btn btn-success btn-xs' title='Konfirmasi Pembayaran' href='".base_url()."konfirmasi?kode=$row[kode_transaksi]'>Konfirmasi</a>";
                }else{
                  echo "<a style='margin-right:3px' class='btn btn-default btn-xs' href='#'  onclick=\"return confirm('Maaf, Pembayaran ini sudah di konfirmasi!')\">Konfirmasi</a>";
                }
              
              echo "<a class='btn btn-info btn-xs' title='Detail data pesanan' href='".base_url()."members/keranjang_detail/$row[id_penjualan]'><span class='glyphicon glyphicon-search'></span></a></td>
                          </tr>

                          ";
                      $no++;
                    }
                  ?>
                </tbody>
              </table>
