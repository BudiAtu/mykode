            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Transaksi Penjualan / Orderan Konsumen</h3>
                  <!--<a class='pull-right btn btn-primary btn-sm' href='<?php echo base_url(); ?>members/tambah_penjualan'>Tambah Penjualan</a>-->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="" class="table table-bordered table-striped table-condensed">
                    <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Kode Transaksi</th>
                        <th>Nama Konsumen</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record->result_array() as $row){
                    if ($row['proses']=='0'){ 
                      $proses = '<i class="text-danger">Pending</i>'; $status = 'Proses'; $icon = 'star-empty'; 
                      $ubah = 1; 
                      }elseif($row['proses']=='1'){ 
                        $proses = '<i class="text-success">Proses</i>'; $status = 'Dikirim'; $icon = 'star text-yellow'; 
                        $ubah = 3; 
                      }elseif($row['proses']=='3'){ 
                        $proses = '<i class="text-success">Dikirim</i>'; $status = 'Diterima'; $icon = 'star text-yellow'; 
                        $ubah = 4; 
                      }elseif($row['proses']=='4'){ 
                        $proses = '<i class="text-success">Diterima</i>'; $status = 'Selesai'; $icon = 'star text-yellow'; 
                        $ubah = 5; 
                      }elseif($row['proses']=='5'){ 
                        $proses = '<i class="text-success">Selesai</i>'; $status = 'Selesai'; $icon = 'star text-yellow'; 
                        $ubah = 0; 
                      }else{ $proses = '<i class="text-info">Konfirmasi</i>'; $status = 'Selesai'; $icon = 'star';
                         $ubah = 1; }
                    $total = $this->db->query("SELECT sum(a.harga_jual-a.diskon) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
                    echo "<tr><td>$no</td>
                              <td>$row[kode_transaksi]</td>
                              <td><a href='".base_url()."members/detail_konsumen/$row[id_konsumen]'>$row[nama_lengkap]</a></td>
                              <td>$proses</td>
                              <td style='color:red;'>Rp ".rupiah($total['total']+$row['ongkir'])."</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Detail Data' href='".base_url()."members/detail_penjualan/$row[id_penjualan]'><span class='glyphicon glyphicon-search'></span> Detail</a>
                                <a class='btn btn-primary btn-xs' title='$status Data' href='".base_url()."members/proses_penjualan/$row[id_penjualan]/$ubah' onclick=\"return confirm('Apa anda yakin untuk ubah status jadi $status?')\"><span class='glyphicon glyphicon-$icon'></span></a>
                                <a class='btn btn-warning btn-xs' title='Edit Data' href='".base_url()."members/edit_penjualan/$row[id_penjualan]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='".base_url()."members/delete_penjualan/$row[id_penjualan]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>
              </div>
              </div>
              