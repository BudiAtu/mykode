					<div class="main-content">
						<div class="main-page left">
							<div class="double-block">
								<div class="content-block main left">
									<div class="block">
										<div class="block-title" style="background:#2a9230;">
											<a href="<?php echo base_url(); ?>" class="right">Back to homepage</a>											
											<h2>Agenda</h2>	
										</div>
										<div class="block-content">
											<?php
											  foreach ($agenda->result_array() as $r) {	
												  $tgl_posting = tgl_indo($r['tgl_posting']);
												  $tgl_mulai   = tgl_indo($r['tgl_mulai']);
												  $tgl_selesai = tgl_indo($r['tgl_selesai']);
												  $baca = $r['dibaca']+1;
												  $judull = substr($r['tema'],0,33); 
												  $isi_agenda =(strip_tags($r['isi_agenda'])); 
												  $isi = substr($isi_agenda,0,280); 
												  $isi = substr($isi_agenda,0,strrpos($isi," "));
												  
												  echo "<div class='article-big'>
															<div class='article-photo'>";
															if ($r['gambar']==''){
																echo "<img width='210px' height='150px' src='".base_url()."asset/foto_agenda/small_no-image.jpg'>";
															}else{
																echo "<img width='210px' height='150px' src='".base_url()."asset/foto_agenda/$r[gambar]'>";
															}	
															echo "</div>
															<div class='article-content'>
																<h2><a title='$r[tema]' href='".base_url()."agenda/detail/$r[tema_seo]'>$judull</a></h2>
																<span class='meta'>
																	<a href='".base_url()."agenda/detail/$r[tema_seo]'><span class='icon-text'>&#128100;</span>$r[nama_lengkap]</a>
																	<a href='".base_url()."agenda/detail/$r[tema_seo]'><span class='icon-text'>&#128340;</span>$tgl_posting - $baca view</a>
																</span>
																<p>$isi . . .</p>
																<span class='meta'>
																	<a href='".base_url()."agenda/detail/$r[tema_seo]' class='more'>See More<span class='icon-text'>&#9656;</span></a>
																</span>
															</div>
														</div>";
											  }
										?>
											<div class="pagination">
												<?php echo $this->pagination->create_links(); ?>
											</div>
										</div>
									</div>



									<div class="block">
				<div class="block-title">
					<a href="<?php echo base_url(); ?>produk" class="right">+ Produk Lainnya</a>
					<h2>PRODUK TERKINI</h2>
				</div>
				<div class="block-content">
				<ul class="article-block-big">
			<?php 
			  $no = 1;
			  $record = $this->db->query("SELECT * FROM rb_produk where id_konsumen!='0' AND id_produk_perusahaan='0' ORDER BY id_produk DESC LIMIT 12");
			    foreach ($record->result_array() as $row){
			    $ex = explode(';', $row['gambar']);
			    if (trim($ex[0])==''){ $foto_produk = 'no-image.png'; }else{ $foto_produk = $ex[0]; }
			    if (strlen($row['nama_produk']) > 43){ $judul = substr($row['nama_produk'],0,43).',..';  }else{ $judul = $row['nama_produk']; }
			    $jual = $this->model_reseller->jual_reseller($row['id_konsumen'],$row['id_produk'])->row_array();
			    $beli = $this->model_reseller->beli_reseller($row['id_konsumen'],$row['id_produk'])->row_array();
			    if ($beli['beli']-$jual['jual']<=0){ $stok = '<b style="color:red">Stok Habis</b>'; }else{ $stok = "".($beli['beli']-$jual['jual'])." $row[satuan]"; }

				$disk = $this->model_app->view_where("rb_produk_diskon",array('id_produk'=>$row['id_produk']))->row_array();
				$diskon = rupiah(($disk['diskon']/$row['harga_konsumen'])*100,0)."%";
				if ($diskon>0){ $diskon_persen = "<div class='top-right'>$diskon</div>"; }else{ $diskon_persen = ''; }
				if ($diskon>=1){ 
					$harga =  "<del style='color:#8a8a8a'><small>Rp ".rupiah($row['harga_konsumen'])."</small></del> Rp ".rupiah($row['harga_konsumen']-$disk['diskon']);
				}else{
					$harga =  "Rp ".rupiah($row['harga_konsumen']);
				}
				
				echo "<li style='width:180px' col-md-2 col-xs-6>
				<div style='height:140px; overflow:hidden'>
					<a class='hover-effect' title='$row[nama_produk]' href='".base_url()."produk/detail/$row[produk_seo]'><img style=' min-height:140px; width:100%' src='".base_url()."asset/foto_produk/$foto_produk'></a>
					$diskon_persen
				</div>
				<div class='article-content'><center>
				<h4 class='produk-title'><a href='".base_url()."produk/detail/$row[produk_seo]' title ='$row[nama_produk]'>$judul</a></h4>
				<span class='harga'>$harga</span><br>
				<i>$stok</i><br>
				<small>$row[nama_kota]</small>
					</center>
					
				</div>
			   </li>";
			      $no++;
			    }
			  echo "<div style='clear:both'><br></div>";
			  ?>
			  </ul>
			  </div>
			</div>



								</div>
								<div class="content-block right">
									<?php include "sidebar_kiri.php"; ?>
								</div>
							</div>
						</div>
						<div class="main-sidebar right">
							<?php include "sidebar_kanan.php"; ?>
						</div>
						<div class="clear-float"></div>
					</div>
				<!-- END .wrapper -->