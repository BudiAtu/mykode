<div class="main-page left">
	<div class="single-block">
		<div class="content-block main left">
			<div class="block">
				<div class="block-title" style="background: #2a9230;">
					<a href="<?php echo base_url(); ?>" class="right">Back to Homepage</a>
					<h2>Semua daftar / List File Download</h2>
				</div>
				<div class="block-content">
					<div class="shortcode-content">
						<div class="paragraph-row">
							<div class="column12">
								<table class='table-download' style='font-weight:bold; border:1px solid #e3e3e3;' width='100%'>
									<tr style='background:#8a8a8a'>
										<th>No</th>
										<th>Nama File</th>
										<th>Hits</th>
										<th style='width:70px'></th>
									</tr>
									<?php
										$no=$this->uri->segment(3)+1;
										foreach ($download->result_array() as $r) {	
										if(($no % 2)==0){ $warna="#ffffff";}else{ $warna="#E1E1E1"; }
											echo "<tr bgcolor=$warna>
													<td>$no</td>
												  	<td>$r[judul]</td>
												  	<td>$r[hits] Kali</td>
												  	<td><a class='button' style='background:#29b332; color:#ffffff; padding:2px 10px' href='".base_url()."download/file/$r[nama_file]'>Download</a></td>
												  </tr>";
										$no++;
										}
									?>
								</table>
								<div class="pagination">
									<?php echo $this->pagination->create_links(); ?>
								</div>
							</div>
						</div>
						
						<?php
						$ia = $this->model_utama->view_ordering_limit('iklantengah','id_iklantengah','ASC',6,1)->row_array();
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
						<div class="article-title">
							<div class="share-block right">
								<div>
									<div class="share-article left">
										<span>Social media</span>
										<strong>Share this article</strong>
									</div>
									<div class="left">
										<script language="javascript">
										document.write("<a href='http://www.facebook.com/share.php?u=" + document.URL + " ' target='_blank' class='custom-soc icon-text'>&#62220;</a> <a href='http://twitter.com/home/?status=" + document.URL + "' target='_blank' class='custom-soc icon-text'>&#62217;</a> <a href='https://plus.google.com/share?url=" + document.URL + "' target='_blank' class='custom-soc icon-text'>&#62223;</a>");
										</script>
										<a href="#" class="custom-soc icon-text">&#62232;</a>
										<a href="#" class="custom-soc icon-text">&#62226;</a>
									</div>
								</div>
							</div>
						</div>

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
				
				echo "<li style='width:180px col-md-2 col-xs-6'>
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


<div class='main-sidebar right'>
	<?php include "sidebar_halaman.php"; ?>
</div>