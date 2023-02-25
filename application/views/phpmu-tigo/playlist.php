<div class="full-width">
	<div class="block">
		<div class="block-title">
			<a href="<?php echo base_url(); ?>" class="right">Back to homepage</a>
			<h2>Playlist</h2>
		</div>
		<div class="block-content">

						<?php 
							$no=$this->uri->segment(3)+1;
							foreach ($playlist->result_array() as $h) {	
							$total_video = $this->model_utama->view_where('video',array('id_playlist' => $h['id_playlist']))->num_rows();
								echo "<div class='produk col-md-2 col-xs-6'>
										<div>
										<div style='height:150px; overflow:hidden;'>
											<a class='hover-effect' href='".base_url()."playlist/detail/$h[playlist_seo]'>";
												if ($h['gbr_playlist'] ==''){
													echo "<a class='hover-effect' href='".base_url()."playlist/detail/$h[playlist_seo]'><img style=' min-height:140px; width:100%'  src='".base_url()."asset/img_playlist/no-image.jpg' alt='no-image.jpg' /></a>";
												}else{
													echo "<a class='hover-effect' href='".base_url()."playlist/detail/$h[playlist_seo]'><img style=' min-height:140px; width:100%'  src='".base_url()."asset/img_playlist/$h[gbr_playlist]' alt='$h[gbr_playlist]' /></a>";
												}
										echo "</a>
										</div>
										<div class='article-content'><center>
											<span style='font-size:14px; color:#8a8a8a;'><center>Ada $total_video Video</center></span>
											<h4 align=center><a href='".base_url()."playlist/detail/$h[playlist_seo]'>$h[jdl_playlist]</a></h4>
											</center>
											</div>
											</div>
										</div>";
							}
						?>
			
		</div>
	</div>
</div>
