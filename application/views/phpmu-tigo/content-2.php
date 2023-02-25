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
<div class="main-page left">
	<div class="double-block">
		<div class="content-block main right">
			
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
			    if (strlen($row['nama_produk']) > 43){ $judul = substr($row['nama_produk'],0,43).',..';  }else{ $judul = $row['nama_produk']; }
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
				
				echo "<li style='width:180px col-md-2 col-xs-6'><hr>
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
			
			
			<div class="block">
				<div class="block-title">
					<a href="<?php echo base_url(); ?>berita/indeks_berita" class="right">+ Indexs Berita</a>
					<h2>Berita Utama</h2>
				</div>
				<div class="block-content">
					<ul class="article-block-big">
						<?php 
							$no = 1;
							$hot = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('utama' => 'Y','status' => 'Y'),'id_berita','DESC',0,6);
                			foreach ($hot->result_array() as $row) {	
							$total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $row['id_berita']))->num_rows();
							$tgl = tgl_indo($row['tanggal']);
							$baca = $row['dibaca']+1;	
							echo "<li style='width:180px'><hr>
									<div class='article-photo'>
										<a href='".base_url()."$row[judul_seo]' class='hover-effect'>";
											if ($row['gambar'] ==''){
												echo "<a class='hover-effect' href='".base_url()."$row[judul_seo]'><img style='height:110px; width:200px' src='".base_url()."asset/foto_berita/no-image.jpg' alt='' /></a>";
											}else{
												echo "<a class='hover-effect' href='".base_url()."$row[judul_seo]'><img style='height:110px; width:200px' src='".base_url()."/asset/foto_berita/$row[gambar]' alt='' /></a>";
											}
									echo "</a>
									</div>
									<div class='article-content'>
										<h4><a href='".base_url()."$row[judul_seo]'>$row[judul]</a><a href='".base_url()."$row[judul_seo].html' class='h-comment'>$total_komentar</a></h4>
										<span class='meta'>
											<a href='".base_url()."$row[judul_seo]'><span class='icon-text'>&#128340;</span>$row[jam], $tgl- Oleh : $row[nama_lengkap] - Dibaca $row[dibaca] Kali</a>
										</span>
									</div>
								  </li>";
							}
						?>
					</ul>
				</div>
			</div><hr>
			
			 <?php
				$ia = $this->model_utama->view_ordering_limit('iklantengah','id_iklantengah','ASC',0,1)->row_array();
				echo "<a href='$ia[url]' target='_blank'>";
					$string = $ia['gambar'];
					if ($ia['gambar'] != ''){
						if(preg_match("/swf\z/i", $string)) {
							echo "<embed style='margin-top:-10px' src='".base_url()."asset/foto_iklantengah/$ia[gambar]' width='100%' height=90px quality='high' type='application/x-shockwave-flash'>";
						} else {
							echo "<img style='margin-top:-10px; margin-bottom:5px' width='100%' src='".base_url()."asset/foto_iklantengah/$ia[gambar]' title='$ia[judul]' />";
						}
					}
				echo "</a>";
			?>
					
			<div class="block">
			<?php $r = $this->model_utama->view_where('kategori',array('sidebar' => 1))->row_array(); ?>
				<div class="block-title">
					<a href="<?php echo base_url()?>kategori/detail/<?php echo $r['kategori_seo']; ?>" class="right">Semua Artikel dari kategori ini </a>
					<h2>Berita kategori <?php echo "$r[nama_kategori]"; ?></h2>
				</div>
				<div class="block-content">
				<ul class="article-block">
					<?php 
						$kategori1 = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $r['id_kategori'],'berita.status' => 'Y'),'id_berita','DESC',0,1);			
						foreach ($kategori1->result_array() as $r1) {
							$tglr = tgl_indo($r1['tanggal']);
							$baca = $r1['dibaca']+1;	
							$isi_berita = strip_tags($r1['isi_berita']); 
							$isi = substr($isi_berita,0,250); 
							$isi = substr($isi_berita,0,strrpos($isi," "));
							$total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $r1['id_berita']))->num_rows();
							echo "<li><hr>
							<div class='article-photo hidden-xs' style='float:left; margin-right:15px'>
							<a href='".base_url()."$r1[judul_seo]' class='hover-effect'>";
								if ($r1['gambar'] == ''){
									echo "<img style='width:210px;' src='".base_url()."asset/foto_berita/no-image.jpg' alt='no-image.jpg' /></a>";
								}else{
									echo "<img style='width:200px;height:120px' src='".base_url()."asset/foto_berita/$r1[gambar]' alt='$r1[gambar]' /></a>";
								}
							echo "</a>
						</div>

						<div class='article-photo visible-xs' style='float:left; margin-right:5px'>";
									if ($r1['gambar'] ==''){
										echo "<a class='hover-effect' href='".base_url()."$r1[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/small_no-image.jpg' alt='' /></a>";
									}else{
										echo "<a class='hover-effect' href='".base_url()."$r1[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/$r1[gambar]' alt='' /></a>";
									}
							echo "</div>
							
								<div class='article-content'>
									<h2><a href='".base_url()."$r1[judul_seo]'>$r1[judul]</a><a href='".base_url()."$r1[judul_seo]' class='h-comment'>$total_komentar</a></h2>
									<span class='meta'>
										<a href='".base_url()."$r1[judul_seo]'><span class='icon-text'>&#128340;</span>$r1[jam], $tglr - Oleh : $r1[nama_lengkap]- Dibaca $r1[dibaca] Kali</a>
									</span>
									<p>$isi . . .</p>
								</div>
							</li>";
						}
					?>
					</ul>
				

					
						<!-- BEGIN .column6 -->
						
							<ul class="article-block">
								<?php 
									$kategori1a = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $r['id_kategori'],'berita.status' => 'Y'),'id_berita','DESC',1,3);			
									foreach ($kategori1a->result_array() as $r2) {
									$total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $r2['id_berita']))->num_rows();
									$tglr2 = tgl_indo($r2['tanggal']);
									$baca = $r2['dibaca']+1;	
									$isi_berita = strip_tags($r2['isi_berita']); 
									$isi = substr($isi_berita,0,250); 
									$isi = substr($isi_berita,0,strrpos($isi," "));
								
									echo "<li><hr>
									<div class='article-photo hidden-xs' style='float:left; margin-right:15px'>
									<a href='".base_url()."$r2[judul_seo]' class='hover-effect'>";
										if ($r2['gambar'] == ''){
											echo "<img style='width:210px;' src='".base_url()."asset/foto_berita/no-image.jpg' alt='no-image.jpg' /></a>";
										}else{
											echo "<img style='width:200px;height:120px' src='".base_url()."asset/foto_berita/$r2[gambar]' alt='$r2[gambar]' /></a>";
										}
									echo "</a>
									</div>
									<div class='article-photo visible-xs' style='float:left; margin-right:5px'>";
												if ($r2['gambar'] ==''){
													echo "<a class='hover-effect' href='".base_url()."$r2[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/small_no-image.jpg' alt='small_no-image.jpg' /></a>";
												}else{
													echo "<a class='hover-effect' href='".base_url()."$r2[judul_seo].html'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/$r2[gambar]' alt='$r2[gambar]' /></a>";
												}
										echo "</div>
										<div class='article-content'>
										<h2><a href='".base_url()."$r2[judul_seo]'>$r2[judul]</a><a href='".base_url()."$r2[judul_seo]' class='h-comment'>$total_komentar</a></h2>
										<span class='meta'>
											<a href='".base_url()."$r2[judul_seo]'><span class='icon-text'>&#128340;</span>$r2[jam], $tglr2 - Oleh : $r2[nama_lengkap]- Dibaca $r2[dibaca] Kali</a>
										</span>
										<p>$isi . . .</p>
									</div>
										</li>";
									}
								?>
							</ul>
					

						
							<ul class="article-block">
								<?php 
									$kategori1b = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $r['id_kategori'],'berita.status' => 'Y'),'id_berita','DESC',6,3);			
									foreach ($kategori1b->result_array() as $r2x) {
									$total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $r2x['id_berita']))->num_rows();
									$tglr2 = tgl_indo($r2x['tanggal']);
									$baca = $r2x['dibaca']+1;	
									$isi_berita = strip_tags($r2x['isi_berita']); 
									$isi = substr($isi_berita,0,250); 
									$isi = substr($isi_berita,0,strrpos($isi," "));
								
									echo "<li><hr>
									<div class='article-photo hidden-xs' style='float:left; margin-right:15px'>
									<a href='".base_url()."$r2x[judul_seo]' class='hover-effect'>";
										if ($r2x['gambar'] == ''){
											echo "<img style='width:210px;' src='".base_url()."asset/foto_berita/no-image.jpg' alt='no-image.jpg' /></a>";
										}else{
											echo "<img style='width:200px;height:120px' src='".base_url()."asset/foto_berita/$r2x[gambar]' alt='$r2x[gambar]' /></a>";
										}
									echo "</a>
									</div>
									<div class='article-photo visible-xs' style='float:left; margin-right:5px'>";
												if ($r2x['gambar'] ==''){
													echo "<a class='hover-effect' href='".base_url()."$r2x[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/small_no-image.jpg' alt='small_no-image.jpg' /></a>";
												}else{
													echo "<a class='hover-effect' href='".base_url()."$r2x[judul_seo].html'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/$r2x[gambar]' alt='$r2x[gambar]' /></a>";
												}
										echo "</div>
										<div class='article-content'>
										<h2><a href='".base_url()."$r2x[judul_seo]'>$r2x[judul]</a><a href='".base_url()."$r2x[judul_seo]' class='h-comment'>$total_komentar</a></h2>
										<span class='meta'>
											<a href='".base_url()."$r2x[judul_seo]'><span class='icon-text'>&#128340;</span>$r2x[jam], $tglr2 - Oleh : $r2x[nama_lengkap]- Dibaca $r2x[dibaca] Kali</a>
										</span>
										<p>$isi . . .</p>
									</div>
										</li>";
									}
								?>
							</ul>
						<!-- END .column6 -->
						</div>
				</div>
	<hr>
			
			<?php
				$ib = $this->model_utama->view_ordering_limit('iklantengah','id_iklantengah','ASC',1,1)->row_array();
				echo "<a href='$ib[url]' target='_blank'>";
					$string = $ib['gambar'];
					if ($ib['gambar'] != ''){
						if(preg_match("/swf\z/i", $string)) {
							echo "<embed style='margin-top:-10px' src='".base_url()."asset/foto_iklantengah/$ib[gambar]' width='100%' height=90px quality='high' type='application/x-shockwave-flash'>";
						} else {
							echo "<img style='margin-top:-10px; margin-bottom:5px' width='100%' src='".base_url()."asset/foto_iklantengah/$ib[gambar]' title='$ib[judul]' />";
						}
					}
				echo "</a>";
			?>

		<div class="block">

			<?php $ra = $this->model_utama->view_where('kategori',array('sidebar' => 2))->row_array(); ?>
				<div class="block-title" style="background: #2182b4;">
					<a href="<?php echo base_url(); ?>kategori/detail/<?php echo "$ra[kategori_seo]"; ?>" class="right">Semua Artikel dari kategori ini </a>
					<h2>Berita kategori <?php echo "$ra[nama_kategori]"; ?></h2>
				</div>
				<div class="block-content">
				<ul class="article-block">
					<?php 
						$kategori2 = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $ra['id_kategori'],'berita.status' => 'Y'),'id_berita','DESC',0,1);			
						foreach ($kategori2->result_array() as $r1m) {
						$tglr = tgl_indo($r1m['tanggal']);
						$baca = $r1m['dibaca']+1;
						$isi_berita = strip_tags($r1m['isi_berita']); 
						$isi = substr($isi_berita,0,250); 
						$isi = substr($isi_berita,0,strrpos($isi," "));
						$total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $r1m['id_berita']))->num_rows();
						echo "<li><hr>
						<div class='article-photo hidden-xs' style='float:left; margin-right:15px'>
						<a href='".base_url()."$r1m[judul_seo]' class='hover-effect'>";
							if ($r1m['gambar'] == ''){
								echo "<img style='width:210px;' src='".base_url()."asset/foto_berita/no-image.jpg' alt='no-image.jpg' /></a>";
							}else{
								echo "<img style='width:200px;height:120px' src='".base_url()."asset/foto_berita/$r1m[gambar]' alt='$r1m[gambar]' /></a>";
							}
						echo "</a>
					</div>

					<div class='article-photo visible-xs' style='float:left; margin-right:5px'>";
								if ($r1m['gambar'] ==''){
									echo "<a class='hover-effect' href='".base_url()."$r1m[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/small_no-image.jpg' alt='' /></a>";
								}else{
									echo "<a class='hover-effect' href='".base_url()."$r1m[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/$r1m[gambar]' alt='' /></a>";
								}
						echo "</div>
						
							<div class='article-content'>
								<h2><a href='".base_url()."$r1m[judul_seo]'>$r1m[judul]</a><a href='".base_url()."$r1m[judul_seo]' class='h-comment'>$total_komentar</a></h2>
								<span class='meta'>
									<a href='".base_url()."$r1m[judul_seo]'><span class='icon-text'>&#128340;</span>$r1m[jam], $tglr - Oleh : $r1m[nama_lengkap]- Dibaca $r1m[dibaca] Kali</a>
								</span>
								<p>$isi . . .</p>
							</div>
						</li>";
					}
					
					?>
					</ul>

				
							<ul class="article-block">
								<?php 
									$kategori2a = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $ra['id_kategori'],'berita.status' => 'Y'),'id_berita','DESC',1,3);			
									foreach ($kategori2a->result_array() as $r2) {
									$total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $r2['id_berita']))->num_rows();
									$tglr2 = tgl_indo($r2['tanggal']);
									$baca = $r2['dibaca']+1;
									$isi_berita = strip_tags($r2['isi_berita']); 
									$isi = substr($isi_berita,0,250); 
									$isi = substr($isi_berita,0,strrpos($isi," "));	
									echo "<li><hr>
									<div class='article-photo hidden-xs' style='float:left; margin-right:15px'>
									<a href='".base_url()."$r2[judul_seo]' class='hover-effect'>";
										if ($r2['gambar'] == ''){
											echo "<img style='width:210px;' src='".base_url()."asset/foto_berita/no-image.jpg' alt='no-image.jpg' /></a>";
										}else{
											echo "<img style='width:200px;height:120px' src='".base_url()."asset/foto_berita/$r2[gambar]' alt='$r2[gambar]' /></a>";
										}
									echo "</a>
								</div>
			
								<div class='article-photo visible-xs' style='float:left; margin-right:5px'>";
											if ($r2['gambar'] ==''){
												echo "<a class='hover-effect' href='".base_url()."$r2[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/small_no-image.jpg' alt='' /></a>";
											}else{
												echo "<a class='hover-effect' href='".base_url()."$r2[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/$r2[gambar]' alt='' /></a>";
											}
									echo "</div>
									
										<div class='article-content'>
											<h2><a href='".base_url()."$r2[judul_seo]'>$r2[judul]</a><a href='".base_url()."$r2[judul_seo]' class='h-comment'>$total_komentar</a></h2>
											<span class='meta'>
												<a href='".base_url()."$r2[judul_seo]'><span class='icon-text'>&#128340;</span>$r2[jam], $tglr - Oleh : $r2[nama_lengkap]- Dibaca $r2[dibaca] Kali</a>
											</span>
											<p>$isi . . .</p>
										</div>
									</li>";
									}
								?>
							</ul>
						
							<ul class="article-block">
								<?php 
									$kategori2b = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $ra['id_kategori'],'berita.status' => 'Y'),'id_berita','DESC',6,3);			
									foreach ($kategori2b->result_array() as $r2x) {
									$total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $r2x['id_berita']))->num_rows();
									$tglr2 = tgl_indo($r2x['tanggal']);
									$baca = $r2x['dibaca']+1;
									$isi_berita = strip_tags($r2x['isi_berita']); 
									$isi = substr($isi_berita,0,250); 
									$isi = substr($isi_berita,0,strrpos($isi," "));	
									echo "<li><hr>
									<div class='article-photo hidden-xs' style='float:left; margin-right:15px'>
									<a href='".base_url()."$r2x[judul_seo]' class='hover-effect'>";
										if ($r2x['gambar'] == ''){
											echo "<img style='width:210px;' src='".base_url()."asset/foto_berita/no-image.jpg' alt='no-image.jpg' /></a>";
										}else{
											echo "<img style='width:200px;height:120px' src='".base_url()."asset/foto_berita/$r2x[gambar]' alt='$r2x[gambar]' /></a>";
										}
									echo "</a>
								</div>
			
								<div class='article-photo visible-xs' style='float:left; margin-right:5px'>";
											if ($r2x['gambar'] ==''){
												echo "<a class='hover-effect' href='".base_url()."$r2x[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/small_no-image.jpg' alt='' /></a>";
											}else{
												echo "<a class='hover-effect' href='".base_url()."$r2x[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/$r2x[gambar]' alt='' /></a>";
											}
									echo "</div>
									
										<div class='article-content'>
											<h2><a href='".base_url()."$r2x[judul_seo]'>$r2x[judul]</a><a href='".base_url()."$r2x[judul_seo]' class='h-comment'>$total_komentar</a></h2>
											<span class='meta'>
												<a href='".base_url()."$r2x[judul_seo]'><span class='icon-text'>&#128340;</span>$r2x[jam], $tglr - Oleh : $r2x[nama_lengkap]- Dibaca $r2x[dibaca] Kali</a>
											</span>
											<p>$isi . . .</p>
										</div>
									</li>";
									}
								?>
							</ul>
						<!-- END .column6 -->
						</div>
				</div><hr>
			
			<?php
				$ic = $this->model_utama->view_ordering_limit('iklantengah','id_iklantengah','ASC',2,1)->row_array();
				echo "<a href='$ic[url]' target='_blank'>";
					$string = $ic['gambar'];
					if ($ic['gambar'] != ''){
						if(preg_match("/swf\z/i", $string)) {
							echo "<embed style='margin-top:-10px' src='".base_url()."asset/foto_iklantengah/$ic[gambar]' width='100%' height=90px quality='high' type='application/x-shockwave-flash'>";
						} else {
							echo "<img style='margin-top:-10px; margin-bottom:5px' width='100%' src='".base_url()."asset/foto_iklantengah/$ic[gambar]' title='$ic[judul]' />";
						}
					}
				echo "</a>";
			?>

<div class="block">
			<?php $ra = $this->model_utama->view_where('kategori',array('sidebar' => 3))->row_array(); ?>
				<div class="block-title" style="background: #2182b4;">
					<a href="<?php echo base_url(); ?>kategori/detail/<?php echo "$ra[kategori_seo]"; ?>" class="right">Semua Artikel dari kategori ini </a>
					<h2>Berita kategori <?php echo "$ra[nama_kategori]"; ?></h2>
				</div>
				<div class="block-content">
				<ul class="article-block">
					<?php 
						$kategori3 = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $ra['id_kategori'],'berita.status' => 'Y'),'id_berita','DESC',0,1);			
						foreach ($kategori3->result_array() as $r2m) {
						$tglr = tgl_indo($r2m['tanggal']);
						$baca = $r2m['dibaca']+1;
						$isi_berita = strip_tags($r2m['isi_berita']); 
						$isi = substr($isi_berita,0,250); 
						$isi = substr($isi_berita,0,strrpos($isi," "));
						$total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $r2m['id_berita']))->num_rows();
							echo "<li><hr>
							<div class='article-photo hidden-xs' style='float:left; margin-right:15px'>
							<a href='".base_url()."$r2m[judul_seo]' class='hover-effect'>";
								if ($r2m['gambar'] == ''){
									echo "<img style='width:210px;' src='".base_url()."asset/foto_berita/no-image.jpg' alt='no-image.jpg' /></a>";
								}else{
									echo "<img style='width:200px;height:120px' src='".base_url()."asset/foto_berita/$r2m[gambar]' alt='$r2m[gambar]' /></a>";
								}
							echo "</a>
						</div>
	
						<div class='article-photo visible-xs' style='float:left; margin-right:5px'>";
									if ($r2m['gambar'] ==''){
										echo "<a class='hover-effect' href='".base_url()."$r2m[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/small_no-image.jpg' alt='' /></a>";
									}else{
										echo "<a class='hover-effect' href='".base_url()."$r2m[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/$r2m[gambar]' alt='' /></a>";
									}
							echo "</div>
							
								<div class='article-content'>
									<h2><a href='".base_url()."$r2m[judul_seo]'>$r2m[judul]</a><a href='".base_url()."$r2m[judul_seo]' class='h-comment'>$total_komentar</a></h2>
									<span class='meta'>
										<a href='".base_url()."$r2m[judul_seo]'><span class='icon-text'>&#128340;</span>$r2m[jam], $tglr - Oleh : $r2m[nama_lengkap]- Dibaca $r2m[dibaca] Kali</a>
									</span>
									<p>$isi . . .</p>
								</div>
							</li>";
					}
					
					?>
						</ul>


							<ul class="article-block">
								<?php 
									$kategori3a = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $ra['id_kategori'],'berita.status' => 'Y'),'id_berita','DESC',1,3);			
									foreach ($kategori3a->result_array() as $r2e) {
									$total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $r2e['id_berita']))->num_rows();
									$tglr2 = tgl_indo($r2e['tanggal']);
									$baca = $r3['dibaca']+1;
									$isi_berita = strip_tags($r2['isi_berita']); 
									$isi = substr($isi_berita,0,250); 
									$isi = substr($isi_berita,0,strrpos($isi," "));	
									echo "<li><hr>
									<div class='article-photo hidden-xs' style='float:left; margin-right:15px'>
									<a href='".base_url()."$r2[judul_seo]' class='hover-effect'>";
										if ($r2['gambar'] == ''){
											echo "<img style='width:210px;' src='".base_url()."asset/foto_berita/no-image.jpg' alt='no-image.jpg' /></a>";
										}else{
											echo "<img style='width:200px;height:120px' src='".base_url()."asset/foto_berita/$r2[gambar]' alt='$r2[gambar]' /></a>";
										}
									echo "</a>
								</div>
			
								<div class='article-photo visible-xs' style='float:left; margin-right:5px'>";
											if ($r2['gambar'] ==''){
												echo "<a class='hover-effect' href='".base_url()."$r2[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/small_no-image.jpg' alt='' /></a>";
											}else{
												echo "<a class='hover-effect' href='".base_url()."$r2[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/$r2[gambar]' alt='' /></a>";
											}
									echo "</div>
									
										<div class='article-content'>
											<h2><a href='".base_url()."$r2[judul_seo]'>$r2[judul]</a><a href='".base_url()."$r2[judul_seo]' class='h-comment'>$total_komentar</a></h2>
											<span class='meta'>
												<a href='".base_url()."$r2[judul_seo]'><span class='icon-text'>&#128340;</span>$r2[jam], $tglr - Oleh : $r2[nama_lengkap]- Dibaca $r2[dibaca] Kali</a>
											</span>
											<p>$isi . . .</p>
										</div>
									</li>";
									}
								?>
							</ul>
					
						
						
							<ul class="article-block">
								<?php 
									$kategori3b = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.id_kategori' => $ra['id_kategori'],'berita.status' => 'Y'),'id_berita','DESC',6,3);			
									foreach ($kategori3b->result_array() as $r2x) {
									$total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $r2x['id_berita']))->num_rows();
									$tglr2 = tgl_indo($r2x['tanggal']);
									$baca = $r2x['dibaca']+1;	
									$isi_berita = strip_tags($r2x['isi_berita']); 
									$isi = substr($isi_berita,0,250); 
									$isi = substr($isi_berita,0,strrpos($isi," "));
								
									echo "<li><hr>
									<div class='article-photo hidden-xs' style='float:left; margin-right:15px'>
									<a href='".base_url()."$r2x[judul_seo]' class='hover-effect'>";
										if ($r2x['gambar'] == ''){
											echo "<img style='width:210px;' src='".base_url()."asset/foto_berita/no-image.jpg' alt='no-image.jpg' /></a>";
										}else{
											echo "<img style='width:200px;height:120px' src='".base_url()."asset/foto_berita/$r2x[gambar]' alt='$r2x[gambar]' /></a>";
										}
									echo "</a>
									</div>
									<div class='article-photo visible-xs' style='float:left; margin-right:5px'>";
												if ($r2x['gambar'] ==''){
													echo "<a class='hover-effect' href='".base_url()."$r2x[judul_seo]'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/small_no-image.jpg' alt='small_no-image.jpg' /></a>";
												}else{
													echo "<a class='hover-effect' href='".base_url()."$r2x[judul_seo].html'><img style='width:160px; height:117px;' src='".base_url()."asset/foto_berita/$r2x[gambar]' alt='$r2x[gambar]' /></a>";
												}
										echo "</div>
										<div class='article-content'>
										<h2><a href='".base_url()."$r2x[judul_seo]'>$r2x[judul]</a><a href='".base_url()."$r2x[judul_seo]' class='h-comment'>$total_komentar</a></h2>
										<span class='meta'>
											<a href='".base_url()."$r2x[judul_seo]'><span class='icon-text'>&#128340;</span>$r2x[jam], $tglr2 - Oleh : $r2x[nama_lengkap]- Dibaca $r2x[dibaca] Kali</a>
										</span>
										<p>$isi . . .</p>
									</div>
										</li>";
									}
								?>
							</ul>
						<!-- END .column6 -->
				</div>
				</div><hr>
			
			<?php
				$ic = $this->model_utama->view_ordering_limit('iklantengah','id_iklantengah','ASC',2,1)->row_array();
				echo "<a href='$ic[url]' target='_blank'>";
					$string = $ic['gambar'];
					if ($ic['gambar'] != ''){
						if(preg_match("/swf\z/i", $string)) {
							echo "<embed style='margin-top:-10px' src='".base_url()."asset/foto_iklantengah/$ic[gambar]' width='100%' height=90px quality='high' type='application/x-shockwave-flash'>";
						} else {
							echo "<img style='margin-top:-10px; margin-bottom:5px' width='100%' src='".base_url()."asset/foto_iklantengah/$ic[gambar]' title='$ic[judul]' />";
						}
					}
				echo "</a>";
			?>
			
			<div class="block">
				<div class="block-title" style="background: #dd8229;">
					<a href="#" class="right">Beberapa Berita Pilihan</a>
					<h2>Berita Pilihan Redaksi</h2>
				</div>
				<div class="block-content">
					<ul class="article-block-big">
						<?php 
							$pilihan = $this->model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.aktif' => 'Y','berita.status' => 'Y'),'id_berita','DESC',0,6);
							foreach ($pilihan->result_array() as $pi) {
							$total_komentar = $this->model_utama->view_where('komentar',array('id_berita' => $pi['id_berita']))->num_rows();
							 $tgl = tgl_indo($pi['tanggal']);
							 $baca = $pi['dibaca']+1;
								echo "<li style='width:180px'>
										<div class='article-photo'>
											<a href='".base_url()."$pi[judul_seo]' class='hover-effect'>";
												if ($pi['gambar'] ==''){
													echo "<a class='hover-effect' href='".base_url()."$pi[judul_seo]'><img style='height:110px; width:210px' src='".base_url()."asset/foto_berita/no-image.jpg' alt='' /></a>";
												}else{
													echo "<a class='hover-effect' href='".base_url()."$pi[judul_seo]'><img style='height:110px; width:210px' src='".base_url()."asset/foto_berita/$pi[gambar]' alt='' /></a>";
												}
										echo "</a>
										</div>
										<div class='article-content'>
											<h4><a href='".base_url()."$pi[judul_seo]'>$pi[judul]</a><a href='".base_url()."$pi[judul_seo]' class='h-comment'>$total_komentar</a></h4>
											<span class='meta'>
												<a href='".base_url()."$pi[judul_seo]'><span class='icon-text'>&#128340;</span>$pi[jam], $tgl-Dibaca $pi[dibaca] Kali</a>
											</span>
										</div>
									  </li>";
							}
						?>
					</ul>
				</div>
			</div>
		</div>				
		<div class="content-block left">
			<?php include "sidebar_kiri.php"; ?>
		</div>
	</div>
</div>
<div class="main-sidebar right">
	<?php include "sidebar_kanan.php"; ?>
</div>