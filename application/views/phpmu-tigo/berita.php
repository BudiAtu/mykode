<?php
echo "<div class='header-menu'>
		<div class='header-search'>
			".form_open('berita/index')."
				<input type='text' placeholder='Cari Berita..'' name='kata' class='search-input' required/>
				<input type='submit' value='Search' name='cari' class='button'/>
			</form>
		</div>
	</div><hr>";
	?>
					<div class="main-content">
						<div class="main-page left">
							<div class="double-block">
								<div class="content-block main left">
									<div class="block">
										<div class="block-title" style="background: #2182b4;">

											<a href="<?php echo base_url(); ?>" class="right">Back to homepage</a>
											<h2><?php echo $title; ?></h2>
										</div>
										<div class="block-content">
										<ul class="article-block">
											<?php
											  foreach ($berita->result_array() as $r) {	
												  $baca = $r['dibaca']+1;	
												  $isi_berita =(strip_tags($r['isi_berita'])); 
												  $isi = substr($isi_berita,0,150); 
												  $isi = substr($isi_berita,0,strrpos($isi," ")); 
												  $judul = substr($r['judul'],0,33); 
												  $total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $r['id_berita']))->num_rows();
												  
												  echo "<li><hr>
												  
															<div class='article-photo hidden-xs' style='float:left; margin-right:15px'>
																<a href='".base_url()."$r[judul_seo]' class='hover-effect'>";
																	if ($r['gambar'] == ''){
																		echo "<img style='width:210px;' src='".base_url()."asset/foto_berita/no-image.jpg' alt='no-image.jpg' /></a>";
																	}else{
																		echo "<img style='width:200px;height:200px' src='".base_url()."asset/foto_berita/$r[gambar]' alt='$r[gambar]' /></a>";
																	}
																echo "</a>
																</div>
																<div class='article-photo visible-xs' style='float:left; margin-right:5px'>";
																	if ($r['gambar'] ==''){
																		echo "<a class='hover-effect' href='".base_url()."$r[judul_seo]'><img style='width:59px; height:42px' src='".base_url()."asset/foto_berita/small_no-image.jpg' alt='small_no-image.jpg' /></a>";
																	}else{
																		echo "<a class='hover-effect' href='".base_url()."$r[judul_seo].html'><img style='width:170px; height:180px' src='".base_url()."asset/foto_berita/$r[gambar]' alt='$r[gambar]' /></a>";
																	}
															echo "</div>
															<div class='article-content'>
																<h2><a title='$r[judul]' href='".base_url()."$r[judul_seo]'>$judul...</a><a href='".base_url()."$r[judul_seo]' class='h-comment'>$total_komentar</a></h2>
																<span class='meta'>
																	<a href='".base_url()."$r[judul_seo]'><span class='icon-text'>&#128100;</span>$r[nama_lengkap]</a>
																	<a href='".base_url()."$r[judul_seo]'><span class='icon-text'>&#128340;</span>$r[jam], ".tgl_indo($r['tanggal']).",Dibaca $r[dibaca] Kali</a>
																</span>
																<p>".getSearchTermToBold($isi,$this->input->post('kata'))." . . .</p>
																<span class='meta'>
																	<a href='".base_url()."$r[judul_seo]' class='more'>Read Full Article<span class='icon-text'>&#9656;</span></a>
																</span>
															</div>
													
														</li>";
											  }
										?>
										</ul>
											<div class="pagination">
												<?php echo $this->pagination->create_links(); ?>
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
			  $record = $this->db->query("SELECT * FROM rb_produk where id_reseller!='0' AND id_produk_perusahaan='0' ORDER BY id_produk DESC LIMIT 12");
			    foreach ($record->result_array() as $row){
			    $ex = explode(';', $row['gambar']);
			    if (trim($ex[0])==''){ $foto_produk = 'no-image.png'; }else{ $foto_produk = $ex[0]; }
			    if (strlen($row['nama_produk']) > 25){ $judul = substr($row['nama_produk'],0,25).',..';  }else{ $judul = $row['nama_produk']; }
			    $jual = $this->model_reseller->jual_reseller($row['id_reseller'],$row['id_produk'])->row_array();
			    $beli = $this->model_reseller->beli_reseller($row['id_reseller'],$row['id_produk'])->row_array();
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
						<div class='article-photo'>
						<a class='hover-effect' href='".base_url()."produk/detail/$row[produk_seo]'><img style='height:140px; width:200px' src='".base_url()."asset/foto_produk/$foto_produk' alt='' /></a>
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
