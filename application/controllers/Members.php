<?php
/*
-- ---------------------------------------------------------------
-- MARKETPLACE MULTI BUYER MULTI SELLER + SUPPORT RESELLER SYSTEM
-- CREATED BY : ROBBY PRIHANDAYA
-- COPYRIGHT  : Copyright (c) 2018 - 2019, PHPMU.COM. (https://phpmu.com/)
-- LICENSE    : http://opensource.org/licenses/MIT  MIT License
-- CREATED ON : 2019-03-26
-- UPDATED ON : 2019-03-27
-- ---------------------------------------------------------------
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Members extends CI_Controller {
	function foto(){
		cek_session_members();
		if (isset($_POST['submit'])){
			$this->model_reseller->modupdatefoto();
			redirect('members/profile');
		}else{
			redirect('members/profile');
		}
	}

	function profile(){
		cek_session_members();
		$data['title'] = 'Profile Anda';
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		$this->template->load(template().'/template',template().'/reseller/view_profile',$data);
	}

	
	function edit_profile(){
		cek_session_members();
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$this->model_reseller->profile_update($this->session->id_konsumen);
			redirect('members/profile');
		}else{
			$data['title'] = 'Edit Profile Anda';
			$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$row = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$this->template->load(template().'/template',template().'/reseller/view_profile_edit',$data);
		}
	}
	
	function toko(){
		cek_session_members();
		$data['title'] = 'Toko Anda';
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		$this->template->load(template().'/template',template().'/reseller/view_toko',$data);
	}

	function reseller(){
		cek_session_members();
		$jumlah= $this->model_app->view('rb_konsumen')->num_rows();
		$config['base_url'] = base_url().'members/reseller';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 12; 	
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}

		if (is_numeric($dari)) {
			$data['title'] = 'Semua Daftar Reseller';
			$this->pagination->initialize($config);
			if (isset($_POST['submit'])){
				$data['record'] = $this->model_reseller->cari_reseller(filter($this->input->post('cari_reseller')));
			}elseif (isset($_GET['cari_reseller'])){
				$data['record'] = $this->model_reseller->cari_reseller(filter($this->input->get('cari_reseller')));
				$total = $this->model_reseller->cari_reseller(filter($this->input->get('cari_reseller')));
				if ($total->num_rows()==1){
					$row = $total->row_array();
					redirect('produk/keranjang/'.$row['id_konsumen'].'/'.$this->session->produk);
				}
			}else{
				$data['record'] = $this->db->query("SELECT * FROM rb_konsumen ORDER BY id_konsumen DESC LIMIT $dari,$config[per_page]");
			}
			$this->template->load(template().'/template',template().'/reseller/view_reseller',$data);
		}else{
			redirect('main');
		}
	}

	function detail_reseller(){
		cek_session_members();
		$data['title'] = 'Detail Profile Reseller';
		$id = $this->uri->segment(3);
		$data['rows'] = $this->model_app->edit('rb_konsumen',array('id_konsumen'=>$id))->row_array();
		$data['record'] = $this->model_reseller->penjualan_list_konsumen($id,'id_konsumen');
		$data['rekening'] = $this->model_app->view_where('rb_rekening_reseller',array('id_konsumen'=>$id));
		$this->template->load(template().'/template',template().'/reseller/view_reseller_detail',$data);

	}

	function orders_report(){
		cek_session_members();
		$data['title'] = 'Laporan Pesanan Anda';
		$data['record'] = $this->model_reseller->orders_report($this->session->id_konsumen,'reseller');
		$this->template->load(template().'/template',template().'/reseller/members/view_orders_report',$data);
	}

	function produk_reseller(){
		cek_session_members();
		$jumlah= $this->model_app->view('rb_produk')->num_rows();
		$config['base_url'] = base_url().'members/produk_reseller/'.$this->uri->segment('3');
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 12; 	
		if ($this->uri->segment('4')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('4');
		}

		if (is_numeric($dari)) {
			$data['title'] = 'Data Produk Reseller';
			$id = $this->uri->segment(3);
			$data['rows'] = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='$id'")->row_array();
			$data['record'] = $this->model_app->view_where_ordering_limit('rb_produk',array('id_konsumen!='=>'0'),'id_produk','DESC',$dari,$config['per_page']);
			$this->pagination->initialize($config);
			$this->template->load(template().'/template',template().'/reseller/view_reseller_produk',$data);
		}else{
			redirect('main');
		}
	}

	function keranjang(){
		cek_session_members();
		$id_reseller = $this->uri->segment(3);
		$id_produk   = $this->uri->segment(4);

		$j = $this->model_reseller->jual_reseller($id_reseller,$id_produk)->row_array();
        $b = $this->model_reseller->beli_reseller($id_reseller,$id_produk)->row_array();


		if ($id_produk!=''){
			if ($produk = ''){
				$produk = $this->model_app->edit('rb_produk',array('id_produk'=>$id_produk))->row_array();
				$produk_cek = filter($produk['nama_produk']);
				echo "<script>window.alert('Maaf, Stok untuk Produk $produk_cek pada Reseller ini telah habis!');
                                 window.location=('".base_url()."members/reseller')</script>";
			}else{
				$this->session->unset_userdata('produk');
				if ($this->session->idp == ''){
					$kode_transaksi = 'TRX-'.date('YmdHis');
					$data = array('kode_transaksi'=>$kode_transaksi,
				        		  'id_pembeli'=>$this->session->id_konsumen,
				        		  'id_penjual'=>$id_reseller,
				        		  'status_pembeli'=>'konsumen',
				        		  'status_penjual'=>'reseller',
				        		  'waktu_transaksi'=>date('Y-m-d H:i:s'),
				        		  'proses'=>'0');
					$this->model_app->insert('rb_penjualan',$data);
					$idp = $this->db->insert_id();
					$this->session->set_userdata(array('idp'=>$idp));
				}

				$qty = $this->input->post('qty');
				$reseller = $this->model_app->view_where('rb_penjualan',array('id_penjualan'=>$this->session->idp))->row_array();
				$cek = $this->model_app->view_where('rb_penjualan_detail',array('id_penjualan'=>$this->session->idp,'id_produk'=>$id_produk))->num_rows();
				if ($reseller['id_penjual']==$id_reseller){
					if ($cek >=1){
						$this->db->query("SELECT * FROM rb_penjualan_detail where id_penjualan='".$this->session->idp."' AND id_produk='$id_produk'");
					}else{
						$harga = $this->model_app->view_where('rb_produk',array('id_produk'=>$id_produk))->row_array();
						$disk = $this->model_app->edit('rb_produk_diskon',array('id_produk'=>$id_produk,'id_konsumen'=>$id_konsumen))->row_array();
	                    $harga_konsumen = $harga['harga_konsumen']-$disk['diskon'];
						$data = array('id_penjualan'=>$this->session->idp,
					        		  'id_produk'=>$id_produk,
					        		  'harga_jual'=>$harga_konsumen);
						$this->model_app->insert('rb_penjualan_detail',$data);
					}
					redirect('members/keranjang');
				}else{
					if ($this->session->idp != ''){
						$data['rows'] = $this->model_reseller->penjualan_konsumen_detail($this->session->idp)->row_array();
						$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->session->idp),'id_penjualan_detail','ASC');
					}
					$data['title'] = 'Keranjang Belanja';
					$data['error_reseller'] = "<div class='alert alert-danger'>Maaf, Dalam 1 Transaksi hanya boleh order dari 1 Reseller saja.</div>";
					$this->template->load(template().'/template',template().'/reseller/members/view_keranjang',$data);
				}
			}
		}else{
			if ($this->session->idp != ''){
				$data['rows'] = $this->model_reseller->penjualan_konsumen_detail($this->session->idp)->row_array();
				$data['rowsk'] = $this->model_app->view_where('rb_konsumen',array('id_konsumen'=>$this->session->id_konsumen))->row_array();
				$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->session->idp),'id_penjualan_detail','ASC');
			}
				$data['title'] = 'Keranjang Belanja';
				$this->template->load(template().'/template',template().'/reseller/members/view_keranjang',$data);

		}
	}

	function keranjang_detail(){
		cek_session_members();
		$data['rows'] = $this->model_reseller->penjualan_konsumen_detail($this->uri->segment(3))->row_array();
		$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->uri->segment(3)),'id_penjualan_detail','ASC');
		$data['title'] = 'Detail Belanja';
		$this->template->load(template().'/template',template().'/reseller/members/view_keranjang_detail',$data);
	}


	function keranjang_delete(){
		$id = array('id_penjualan_detail' => $this->uri->segment(3));
		$this->model_app->delete('rb_penjualan_detail',$id);
		$isi_keranjang = $this->db->query("SELECT sum(jumlah) as jumlah FROM rb_penjualan_detail where id_penjualan='".$this->session->idp."'")->row_array();
		if ($isi_keranjang['jumlah']==''){
			$idp = array('id_penjualan' => $this->session->idp);
			$this->model_app->delete('rb_penjualan',$idp);
			$this->session->unset_userdata('idp');
		}
		redirect('members/keranjang');
	}

	function selesai_belanja(){
		if (isset($_POST['submit'])){
			$iden = $this->model_app->view_where('identitas',array('id_identitas'=>'1'))->row_array();
			$cekres = $this->model_app->view_where('rb_penjualan',array('id_penjualan'=>$this->session->idp))->row_array();
			$kons = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();

			$res = $this->model_app->view_where('rb_konsumen',array('id_konsumen'=>$cekres['id_penjual']))->row_array();
			$data['rekening_reseller'] = $this->model_app->view_where('rb_rekening_reseller',array('id_konsumen'=>$cekres['id_penjual']));



			$email_tujuan = $kons['email'];
			$tglaktif = date("d-m-Y H:i:s");

			$subject      = "$iden[nama_website] - Detail Orderan anda";
			$message      = "<html><body>Halooo! <b>".$kons['nama_lengkap']."</b> ... <br> Hari ini pada tanggal <span style='color:red'>$tglaktif</span> Anda telah order produk di $iden[nama_website].
				<br><table style='width:100%;'>
	   				<tr><td style='background:#337ab7; color:#fff; pading:20px' cellpadding=6 colspan='2'><b>Berikut Data Anda : </b></td></tr>
					<tr><td width='140px'><b>Nama Lengkap</b></td>  <td> : ".$kons['nama_lengkap']."</td></tr>
					<tr><td><b>Alamat Email</b></td>			<td> : ".$kons['email']."</td></tr>
					<tr><td><b>No Telpon</b></td>				<td> : ".$kons['no_hp']."</td></tr>
					<tr><td><b>Alamat</b></td>					<td> : ".$kons['alamat_lengkap']." </td></tr>
					<tr><td><b>Negara</b></td>					<td> : ".$kons['negara']." </td></tr>
					<tr><td><b>Provinsi</b></td>				<td> : ".$kons['propinsi']." </td></tr>
					<tr><td><b>Kabupaten/Kota</b></td>			<td> : ".$kons['kota']." </td></tr>
					<tr><td><b>Kecamatan</b></td>				<td> : ".$kons['kecamatan']." </td></tr>
				</table><br>

				<table style='width:100%;'>
	   				<tr><td style='background:#337ab7; color:#fff; pading:20px' cellpadding=6 colspan='2'><b>Berikut Data Reseller : </b></td></tr>
					<tr><td width='140px'><b>Nama Reseller</b></td>	<td> : ".$res['nama_lengkap']."</td></tr>
					<tr><td><b>Alamat</b></td>					<td> : ".$res['alamat_lengkap']."</td></tr>
					<tr><td><b>No Telpon</b></td>				<td> : ".$res['no_telpon']."</td></tr>
					<tr><td><b>Email</b></td>					<td> : ".$res['email']." </td></tr>
					<tr><td><b>Keterangan</b></td>				<td> : ".$res['keterangan']." </td></tr>
				</table><br>

				No Orderan anda : <b>".$cekres['kode_transaksi']."</b><br>
				Berikut Detail Data Orderan Anda :
				<table style='width:100%;' class='table table-striped'>
			          <thead>
			            <tr bgcolor='#337ab7'>
			              <th style='width:40px'>No</th>
			              <th width='47%'>Nama Produk</th>
			              <th>Harga</th>
			              <th>Qty</th>
			              <th>Berat</th>
			              <th>Subtotal</th>
			            </tr>
			          </thead>
			          <tbody>";

			          $no = 1;
			          $belanjaan = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->session->idp),'id_penjualan_detail','ASC');
			          foreach ($belanjaan as $row){
			          $sub_total = ($row['harga_jual']*$row['jumlah']);
			$message .= "<tr bgcolor='#e3e3e3'><td>$no</td>
			                    <td>$row[nama_produk]</td>
			                    <td>".rupiah($row['harga_jual'])."</td>
			                    <td>$row[jumlah]</td>
			                    <td>".($row['berat']*$row['jumlah'])." Kg</td>
			                    <td>Rp ".rupiah($sub_total)."</td>
			                </tr>";
			            $no++;
			          }
			          $total = $this->db->query("SELECT sum(a.harga_jual-a.diskon) FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_penjualan='".$this->session->idp."'")->row_array();
			$message .= "<tr bgcolor='lightgreen'>
			                  <td colspan='5'><b>Total Harga</b></td>
			                  <td><b>Rp ".rupiah($total['total'])."</b></td>
			                </tr>

			                <tr bgcolor='lightblue'>
			                  <td colspan='5'><b>Total Berat</b></td>
			                  <td><b>$total[total_berat] Kg</b></td>
			                </tr>

			        </tbody>
			      </table><br>

			      Silahkan melakukan pembayaran ke rekening reseller :
			      <table style='width:100%;' class='table table-hover table-condensed'>
					<thead>
					  <tr bgcolor='#337ab7'>
					    <th width='20px'>No</th>
					    <th>Nama Bank</th>
					    <th>No Rekening</th>
					    <th>Atas Nama</th>
					  </tr>
					</thead>
					<tbody>";
					    $noo = 1;
					    $rekening = $this->model_app->view_where('rb_rekening_reseller',array('id_konsumen'=>$cekres['id_penjual']));
					    foreach ($rekening->result_array() as $row){
			$message .= "<tr bgcolor='#e3e3e3'><td>$noo</td>
					              <td>$row[nama_bank]</td>
					              <td>$row[no_rekening]</td>
					              <td>$row[pemilik_rekening]</td>
					          </tr>";
					      $noo++;
					    }
			$message .= "</tbody>
				  </table><br><br>

			      Jika sudah melakukan transfer, jangan lupa konfirmasi transferan anda <a href='".base_url()."konfirmasi'>disini</a><br>
			      Admin, $iden[nama_website] </body></html> \n";
			
				  $this->load->library('email');
				  $config['protocol']    = 'smtp';
				  $config['smtp_host']    = 'ssl://smtp.googlemail.com';
				  $config['smtp_port']    = '465';
				  $config['smtp_timeout'] = '7';
				  $config['smtp_user']    = "daganganku12@gmail.com";
				  $config['smtp_pass']    = "i3udis4ntoso";
				  $config['charset']    = 'utf-8';
				  $config['newline']    = "\r\n";
				  $config['mailtype'] = 'html'; 
				  $config['validation'] = TRUE; 
				  $this->email->initialize($config);
				  $this->load->library('email', $config);
	$this->email->set_newline("\r\n");
	$this->email->initialize($config);
	$this->email->set_newline("\r\n");

			$this->email->from($iden['email'], $iden['nama_website']);
			$this->email->to($email_tujuan);
			$this->email->cc('');
			$this->email->bcc('');

			$this->email->subject($subject);
			$this->email->message($message);
			$this->email->set_mailtype("html");
			$this->email->send();
			
			$this->session->unset_userdata('idp');
		}
		redirect('members/orders_report/orders');
	}

	function batalkan_transaksi(){
		echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Anda Telah mebatalkan Transaksi!</center></div>');
		$idp = array('id_penjualan' => $this->session->idp);
		$this->model_app->delete('rb_penjualan',$idp);
		$idp_detail = array('id_penjualan' => $this->session->idp);
		$this->model_app->delete('rb_penjualan_detail',$idp_detail);

		$this->session->unset_userdata('idp');
		redirect('members/profile');
	}

	function order(){
		cek_session_members();
		$this->session->set_userdata(array('produk'=>$this->uri->segment(3)));
		$cek = $this->db->query("SELECT b.nama_kota FROM rb_konsumen a JOIN rb_kota b ON a.kota_id=b.kota_id where a.id_konsumen='".$this->session->id_konsumen."'")->row_array();
		redirect('members/reseller?cari_reseller='.$cek['nama_kota']);
	}

	public function username_check(){
        // allow only Ajax request    
        if($this->input->is_ajax_request()) {
	        // grab the email value from the post variable.
	        $username = $this->input->post('a');

            if(!$this->form_validation->is_unique($username, 'rb_konsumen.username')) {          
	         	$this->output->set_content_type('application/json')->set_output(json_encode(array('messageusername' => 'Maaf, Username ini sudah terdaftar,..')));
            }

        }
    }

    public function email_check(){
        // allow only Ajax request    
        if($this->input->is_ajax_request()) {
	        // grab the email value from the post variable.
	        $email = $this->input->post('d');

	        if(!$this->form_validation->is_unique($email, 'rb_konsumen.email')) {          
	         	$this->output->set_content_type('application/json')->set_output(json_encode(array('message' => 'Maaf, Email ini sudah terdaftar,..')));
            }
        }
    }

	function logout(){
		cek_session_members();
		$this->session->sess_destroy();
		redirect('main');
	}

	public function hubungi(){
		$query = $this->model_utama->view_where('mod_alamat',array('id_alamat' => 1));
		$data['iden'] = $this->model_utama->view_where('identitas',array('id_identitas' => 1))->row_array();
		$row = $query->row_array();
		$data['title'] = 'Hubungi Kami';
		$data['description'] = 'Silahkan Mengisi Form Dibawah ini untuk menghubungi kami';
		$data['keywords'] = 'hubungi, kontak, kritik, saran, pesan';
		$data['rows'] = $row;

		$this->load->helper('captcha');
		$vals = array(
            'img_path'   => './captcha/',
			'img_url'    => base_url().'captcha/',
			'font_path' => base_url().'asset/Tahoma.ttf',
			'font_size'     => 17,
			'img_width'  => '320',
			'img_height' => 33,
			'border' => 0, 
			'word_length'   => 5,
			'expiration' => 7200
        );

        $cap = create_captcha($vals);
        $data['image'] = $cap['image'];
        $this->session->set_userdata('mycaptcha', $cap['word']);
		$this->template->load(template().'/template',template().'/hubungi',$data);
	}

	function kirim(){
		if (isset($_POST['submit'])){
			if ($this->input->post() && (strtolower($this->input->post('security_code')) == strtolower($this->session->userdata('mycaptcha')))) {
				$data = array('nama'=>cetak($this->input->post('a')),
	                            'email'=>cetak($this->input->post('b')),
	                            'subjek'=>$_SERVER['REMOTE_ADDR'],
	                            'pesan'=>cetak($this->input->post('c')),
	                            'tanggal'=>date('Y-m-d'),
								'jam'=>date('H:i:s'));
								
				$this->model_utama->insert('hubungi',$data);
				echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Pesan terkirim!, akan kami respon via email!</center></div>');
			}else{
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Security Code yang anda masukkan salah!</center></div>');
			}
			redirect('hubungi');
		}
	}

	//TOKO TOKO TOKO
	
	// Controller Modul Produk
	function produk(){
		cek_session_members();
		if (isset($_POST['submit'])){
			$jml = $this->model_app->view('rb_produk')->num_rows();
			for ($i=1; $i<=$jml; $i++){
				$a  = $_POST['a'][$i];
				$b  = $_POST['b'][$i];
				$cek = $this->model_app->edit('rb_produk_diskon',array('id_produk'=>$a,'id_konsumen'=>$this->session->id_konsumen))->num_rows();
				if ($cek >= 1){
					if ($b > 0){
						$data = array('diskon'=>$b);
						$where = array('id_produk' => $a,'id_konsumen' => $this->session->id_konsumen);
						$this->model_app->update('rb_produk_diskon', $data, $where);
					}else{
						$this->model_app->delete('rb_produk_diskon',array('id_produk'=>$a,'id_konsumen'=>$this->session->id_konsumen));
					}
				}else{
					if ($b > 0){
						$data = array('id_produk'=>$a,
			                          'id_konsumen'=>$this->session->id_konsumen,
			                          'diskon'=>$b);
						$this->model_app->insert('rb_produk_diskon',$data);
					}
				}
			}
			redirect($this->uri->segment(1).'/produk');
		}else{
			$data['record'] = $this->model_app->view_where_ordering('rb_produk',array('id_konsumen'=>$this->session->id_konsumen),'id_produk','DESC');
			$this->template->load(template(1).'/template',template(1).'/mod_produk/view_produk',$data);
		}
	}

	function tambah_produk(){
		cek_session_members();
        if (isset($_POST['submit'])){
            $files = $_FILES;
            $cpt = count($_FILES['userfile']['name']);
            for($i=0; $i<$cpt; $i++){
                $_FILES['userfile']['name']= $files['userfile']['name'][$i];
                $_FILES['userfile']['type']= $files['userfile']['type'][$i];
                $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
                $_FILES['userfile']['error']= $files['userfile']['error'][$i];
                $_FILES['userfile']['size']= $files['userfile']['size'][$i];
                $this->load->library('upload');
                $this->upload->initialize($this->set_upload_options());
                $this->upload->do_upload();
                $fileName = $this->upload->data()['file_name'];
                $images[] = $fileName;
			}
            $fileName = implode(';',$images);
            $fileName = str_replace(' ','_',$fileName);
            if (trim($fileName)!=''){
                $data = array('id_kategori_produk'=>$this->input->post('a'),
                			  'id_kategori_produk_sub'=>$this->input->post('aa'),
							  'id_konsumen'=>$this->session->id_konsumen,
                              'nama_produk'=>$this->input->post('b'),
                              'produk_seo'=>seo_title($this->input->post('b')),
                              'url_demo'=>$this->input->post('c'),
                              'harga_beli'=>$this->input->post('d'),
                              'harga_reseller'=>$this->input->post('e'),
                              'harga_konsumen'=>$this->input->post('f'),
                              'sorurl'=>$this->input->post('berat'),
                              'gambar'=>$fileName,
                              'keterangan'=>$this->input->post('ff'),
                              'username'=>$this->session->username,
                              'waktu_input'=>date('Y-m-d H:i:s'));
            }else{
                $data = array('id_kategori_produk'=>$this->input->post('a'),
                			  'id_kategori_produk_sub'=>$this->input->post('aa'),
							  'id_konsumen'=>$this->session->id_konsumen,
                              'nama_produk'=>$this->input->post('b'),
                              'produk_seo'=>seo_title($this->input->post('b')),
                              'url_demo'=>$this->input->post('c'),
                              'harga_beli'=>$this->input->post('d'),
                              'harga_reseller'=>$this->input->post('e'),
                              'harga_konsumen'=>$this->input->post('f'),
                              'sorurl'=>$this->input->post('berat'),
                              'keterangan'=>$this->input->post('ff'),
                              'username'=>$this->session->username,
                              'waktu_input'=>date('Y-m-d H:i:s'));
            }
            $this->model_app->insert('rb_produk',$data);
            $id_produk = $this->db->insert_id();
            if ($this->input->post('diskon') > 0){
            	$cek = $this->db->query("SELECT * FROM rb_produk_diskon where id_produk='".$id_produk."' AND id_konsumen='".$this->session->id_konsumen."'");
				if ($cek->num_rows()>=1){
					$data = array('diskon'=>$this->input->post('diskon'));
					$where = array('id_produk' => $id_produk,'id_konsumen' => $this->session->id_konsumen);
					$this->model_app->update('rb_produk_diskon', $data, $where);
				}else{
					$data = array('id_produk'=>$id_produk,
			                      'id_konsumen'=>$this->session->id_konsumen,
			                      'diskon'=>$this->input->post('diskon'));
					$this->model_app->insert('rb_produk_diskon',$data);
				}
			}


			if ($this->input->post('stok') != ''){
				$kode_transaksi = "TRX-".date('YmdHis');
				$data = array('kode_transaksi'=>$kode_transaksi,
			        		  'id_pembeli'=>$this->session->id_konsumen,
			        		  'id_penjual'=>'1',
			        		  'status_pembeli'=>'konsumen',
			        		  'status_penjual'=>'admin',
			        		  'waktu_transaksi'=>date('Y-m-d H:i:s'),
			        		  'proses'=>'1');
				$this->model_app->insert('rb_penjualan',$data);
				$idp = $this->db->insert_id();

				$data = array('id_penjualan'=>$idp,
		        			  'id_produk'=>$id_produk,
		        			  'jumlah'=>$this->input->post('stok'),
		        			  'harga_jual'=>$this->input->post('e'),
		        			  'satuan'=>$this->input->post('c'));
				$this->model_app->insert('rb_penjualan_detail',$data);
			}

            redirect('members/produk');
        }else{
            $data['record'] = $this->model_app->view_ordering('rb_kategori_produk','id_kategori_produk','DESC');
            $this->template->load(template(1).'/template',template(1).'/mod_produk//view_produk_tambah',$data);
        }
    }

	private function set_upload_options(){
        $config = array();
        $config['upload_path'] = 'asset/foto_produk/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '5000'; // kb
        $config['encrypt_name'] = FALSE;
        $this->load->library('upload', $config);
      return $config;
    }

	function edit_produk(){
        cek_session_members();
        $id = $this->uri->segment(3);
        if (isset($_POST['submit'])){
            $files = $_FILES;
            $cpt = count($_FILES['userfile']['name']);
            for($i=0; $i<$cpt; $i++){
                $_FILES['userfile']['name']= $files['userfile']['name'][$i];
                $_FILES['userfile']['type']= $files['userfile']['type'][$i];
                $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
                $_FILES['userfile']['error']= $files['userfile']['error'][$i];
                $_FILES['userfile']['size']= $files['userfile']['size'][$i];
                $this->load->library('upload');
                $this->upload->initialize($this->set_upload_options());
                $this->upload->do_upload();
                $fileName = $this->upload->data()['file_name'];
                $images[] = $fileName;
            }
            $fileName = implode(';',$images);
            $fileName = str_replace(' ','_',$fileName);
            if (trim($fileName)!=''){
                $data = array('id_kategori_produk'=>$this->input->post('a'),
                			  'id_kategori_produk_sub'=>$this->input->post('aa'),
                              'nama_produk'=>$this->input->post('b'),
                              'produk_seo'=>seo_title($this->input->post('b')),
                              'url_demo'=>$this->input->post('c'),
                              'harga_beli'=>$this->input->post('d'),
                              'harga_reseller'=>$this->input->post('e'),
                              'harga_konsumen'=>$this->input->post('f'),
                              'sorurl'=>$this->input->post('berat'),
                              'gambar'=>$fileName,
                              'keterangan'=>$this->input->post('ff'),
                              'username'=>$this->session->username);
            }else{
                $data = array('id_kategori_produk'=>$this->input->post('a'),
                			  'id_kategori_produk_sub'=>$this->input->post('aa'),
                              'nama_produk'=>$this->input->post('b'),
                              'produk_seo'=>seo_title($this->input->post('b')),
                              'url_demo'=>$this->input->post('c'),
                              'harga_beli'=>$this->input->post('d'),
                              'harga_reseller'=>$this->input->post('e'),
                              'harga_konsumen'=>$this->input->post('f'),
                              'sorurl'=>$this->input->post('berat'),
                              'keterangan'=>$this->input->post('ff'),
                              'username'=>$this->session->username);
            }

            $where = array('id_produk' => $this->input->post('id'),'id_konsumen'=>$this->session->id_konsumen);
            $this->model_app->update('rb_produk', $data, $where);

            if ($this->input->post('diskon') >= 0){
            	$cek = $this->db->query("SELECT * FROM rb_produk_diskon where id_produk='".$this->input->post('id')."' AND id_konsumen='".$this->session->id_konsumen."'");
				if ($cek->num_rows()>=1){
					$data = array('diskon'=>$this->input->post('diskon'));
					$where = array('id_produk' => $this->input->post('id'),'id_konsumen' => $this->session->id_konsumen);
					$this->model_app->update('rb_produk_diskon', $data, $where);
				}else{
					$data = array('id_produk'=>$this->input->post('id'),
			                      'id_konsumen'=>$this->session->id_konsumen,
			                      'diskon'=>$this->input->post('diskon'));
					$this->model_app->insert('rb_produk_diskon',$data);
				}
			}

			if ($this->input->post('stok') != ''){
				$kode_transaksi = "TRX-".date('YmdHis');
				$data = array('kode_transaksi'=>$kode_transaksi,
			        		  'id_pembeli'=>$this->session->id_konsumen,
			        		  'id_penjual'=>'1',
			        		  'status_pembeli'=>'konsumen',
			        		  'status_penjual'=>'admin',
			        		  'service'=>'Stok Otomatis (Pribadi)',			        		  'waktu_transaksi'=>date('Y-m-d H:i:s'),
			        		  'proses'=>'1');
				$this->model_app->insert('rb_penjualan',$data);
				$idp = $this->db->insert_id();

				$data = array('id_penjualan'=>$idp,
		        			  'id_produk'=>$this->input->post('id'),
		        			  'jumlah'=>$this->input->post('stok'),
		        			  'harga_jual'=>$this->input->post('e'),
		        			  'satuan'=>$this->input->post('c'));
				$this->model_app->insert('rb_penjualan_detail',$data);
			}

            redirect('members/produk');
        }else{
            $data['record'] = $this->model_app->view_ordering('rb_kategori_produk','id_kategori_produk','DESC');
            $data['rows'] = $this->model_app->edit('rb_produk',array('id_produk'=>$id,'id_konsumen'=>$this->session->id_konsumen))->row_array();
			$this->template->load(template(1).'/template',template(1).'/mod_produk/view_produk_edit',$data);
        }
    }

	function delete_produk(){
        cek_session_members();
        $id = array('id_produk' => $this->uri->segment(3));
        $this->model_app->delete('rb_produk',$id);
        redirect('members/produk');
    }
	

	// Controller Modul Rekening

	function rekening(){
		cek_session_members();
		$data['record'] = $this->model_app->view_where('rb_rekening_reseller',array('id_konsumen'=>$this->session->id_konsumen));
		$this->template->load(template(1).'/template',template(1).'/mod_rekening/view_rekening',$data);
	}

	function tambah_rekening(){
		cek_session_members();
		if (isset($_POST['submit'])){
			$data = array('id_konsumen'=>$this->session->id_konsumen,
			              'nama_bank'=>$this->input->post('a'),
			              'no_rekening'=>$this->input->post('b'),
			              'pemilik_rekening'=>$this->input->post('c'));
						$this->model_app->insert('rb_rekening_reseller',$data);
			redirect($this->uri->segment(1).'/rekening');
		}else{
			$this->template->load(template(1).'/template',template(1).'/mod_rekening/view_rekening_tambah');
		}
	}

	function edit_rekening(){
		cek_session_members();
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$data = array('id_konsumen'=>$this->session->id_konsumen,
			              'nama_bank'=>$this->input->post('a'),
			              'no_rekening'=>$this->input->post('b'),
			              'pemilik_rekening'=>$this->input->post('c'));
			$where = array('id_rekening_reseller' => $this->input->post('id'),'id_konsumen' => $this->session->id_konsumen);
			$this->model_app->update('rb_rekening_reseller', $data, $where);
			redirect($this->uri->segment(1).'/rekening');
		}else{
			$data['rows'] = $this->model_app->edit('rb_rekening_reseller',array('id_rekening_reseller'=>$id))->row_array();
			$this->template->load(template(1).'/template',template(1).'/mod_rekening/view_rekening_edit',$data);
		}
	}

	function delete_rekening(){
		cek_session_members();
		$id = array('id_rekening_reseller' => $this->uri->segment(3));
		$this->model_app->delete('rb_rekening_reseller',$id);
		redirect($this->uri->segment(1).'/rekening');
	}

	//Controler Data Pembayaran
	function pembayaran_konsumen(){
		//bank admin
		//cek_session_reseller();
		//$data['record'] = $this->db->query("SELECT a.*, b.*, c.kode_transaksi, c.proses FROM `rb_konfirmasi_pembayaran_konsumen` a JOIN rb_rekening b ON a.id_rekening=b.id_rekening JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan where c.id_penjual='".$this->session->id_reseller."' AND c.status_penjual='reseller'");
		//$this->template->load($this->uri->segment(1).'/template',$this->uri->segment(1).'/mod_konsumen/view_konsumen_pembayaran',$data);
		cek_session_members();
		$data['record'] = $this->db->query("SELECT a.*, b.*, c.kode_transaksi, c.proses FROM rb_konfirmasi_pembayaran_konsumen a JOIN rb_rekening_reseller b ON a.id_rekening=b.id_rekening_reseller JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan where c.id_penjual='".$this->session->id_konsumen."' AND c.status_penjual='reseller'");
		$this->template->load(template(1).'/template',template(1).'/mod_konsumen/view_konsumen_pembayaran',$data);
	}

	// Controler Keterangan
	function keterangan(){
		cek_session_members();
		if (isset($_POST['submit'])){
			$cek = $this->model_app->view_where('rb_keterangan',array('id_konsumen'=>$this->session->id_konsumen))->num_rows();
			if ($cek>=1){
				$data1 = array('keterangan'=>$this->input->post('a'));
				$where = array('id_keterangan' => $this->input->post('id'),'id_konsumen'=>$this->session->id_konsumen);
				$this->model_app->update('rb_keterangan', $data1, $where);
			}else{
				$data = array('id_konsumen'=>$this->session->id_konsumen,
							   'keterangan'=>$this->input->post('a'),
							   'tanggal_posting'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_keterangan',$data);
			}
			redirect($this->uri->segment(1).'/keterangan');
		}else{
			$data['record'] = $this->model_app->edit('rb_keterangan',array('id_konsumen'=>$this->session->id_konsumen))->row_array();
			$this->template->load(template(1).'/template',template(1).'/mod_keterangan/view_keterangan',$data);
		}
	}

	
	function download(){
		$name = $this->uri->segment(3);
		$data = file_get_contents("asset/files/".$name);
		force_download($name, $data);
	}

	//Proses Penjualan

	function penjualan(){
		cek_session_members();
		$this->session->unset_userdata('idp');
		$id = $this->session->id_konsumen;
		$data['record'] = $this->model_reseller->penjualan_list_konsumen($id,'reseller');
		$this->template->load(template(1).'/template',template(1).'/mod_penjualan/view_penjualan',$data);
	}

	function detail_penjualan(){
		cek_session_members();
		$data['rows'] = $this->model_reseller->penjualan_konsumen_detail_reseller($this->uri->segment(3))->row_array();
		$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->uri->segment(3)),'id_penjualan_detail','DESC');
		$this->template->load(template(1).'/template',template(1).'/mod_penjualan/view_penjualan_detail',$data);
	}

	function tambah_penjualan(){
		cek_session_members();
		if (isset($_POST['submit1'])){
			if ($this->session->idp == ''){
				$data = array('kode_transaksi'=>$this->input->post('a'),
			        		  'id_pembeli'=>$this->input->post('b'),
			        		  'id_penjual'=>$this->session->id_konsumen,
			        		  'status_pembeli'=>'konsumen',
			        		  'status_penjual'=>'konsumen',
			        		  'waktu_transaksi'=>date('Y-m-d H:i:s'),
			        		  'proses'=>'0');
				$this->model_app->insert('rb_penjualan',$data);
				$idp = $this->db->insert_id();
				$this->session->set_userdata(array('idp'=>$idp));
			}else{
				$data = array('kode_transaksi'=>$this->input->post('a'),
			        		  'id_pembeli'=>$this->input->post('b'));
				$where = array('id_penjualan' => $this->session->idp);
				$this->model_app->update('rb_penjualan', $data, $where);
			}
				redirect($this->uri->segment(1).'/tambah_penjualan');

		}elseif(isset($_POST['submit'])){
			$jual = $this->model_reseller->jual_reseller($this->session->id_konsumen, $this->input->post('aa'))->row_array();
            $beli = $this->model_reseller->beli_reseller($this->session->id_konsumen, $this->input->post('aa'))->row_array();

            if ($this->input->post('dd') > $stok){
            	echo "<script>window.alert('Maaf, Stok Tidak Mencukupi!');
                                  window.location=('".base_url().$this->uri->segment(1)."/tambah_penjualan')</script>";
            }else{
		        if ($this->input->post('idpd')==''){
					$data = array('id_penjualan'=>$this->session->idp,
			        			  'id_produk'=>$this->input->post('aa'),
			        			  'harga_jual'=>$this->input->post('bb'));
					$this->model_app->insert('rb_penjualan_detail',$data);
				}else{
			        $data = array('id_produk'=>$this->input->post('aa'),
			        			  'harga_jual'=>$this->input->post('bb'));
					$where = array('id_penjualan_detail' => $this->input->post('idpd'));
					$this->model_app->update('rb_penjualan_detail', $data, $where);
				}
				redirect($this->uri->segment(1).'/tambah_penjualan');
			}
			
		}else{
			$data['rows'] = $this->model_reseller->penjualan_konsumen_detail_reseller($this->session->idp)->row_array();
			$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->session->idp),'id_penjualan_detail','DESC');
			$data['barang'] = $this->model_app->view_ordering('rb_produk','id_produk','ASC');
			$data['konsumen'] = $this->model_app->view_ordering('rb_konsumen','id_konsumen','ASC');
			if ($this->uri->segment(3)!=''){
				$data['row'] = $this->model_app->view_where('rb_penjualan_detail',array('id_penjualan_detail'=>$this->uri->segment(3)))->row_array();
			}
			$this->template->load(template(1).'/template',template(1).'/mod_penjualan/view_penjualan_tambah',$data);
		}
	}

	function edit_penjualan(){
		cek_session_members();
		if (isset($_POST['submit1'])){
			$data = array('kode_transaksi'=>$this->input->post('a'),
			        	  'id_pembeli'=>$this->input->post('b'),
			        	  'waktu_transaksi'=>$this->input->post('c'));
			$where = array('id_penjualan' => $this->input->post('idp'));
			$this->model_app->update('rb_penjualan', $data, $where);
			redirect($this->uri->segment(1).'/edit_penjualan/'.$this->input->post('idp'));

		}elseif(isset($_POST['submit'])){
			$cekk = $this->db->query("SELECT * FROM rb_penjualan_detail where id_penjualan='".$this->input->post('idp')."' AND id_produk='".$this->input->post('aa')."'")->row_array();
			$jual = $this->model_reseller->jual_reseller($this->session->id_konsumen, $this->input->post('aa'))->row_array();
            $beli = $this->model_reseller->beli_reseller($this->session->id_konsumen, $this->input->post('aa'))->row_array();
            $stok = $beli['beli']-$jual['jual'];
            if ($this->input->post('dd') > $stok){
            	echo "<script>window.alert('Maaf, Stok $stok Tidak Mencukupi!');
                                  window.location=('".base_url().$this->uri->segment(1)."/edit_penjualan/".$this->input->post('idp')."')</script>";
            }else{
				if ($this->input->post('idpd')==''){
					$data = array('id_penjualan'=>$this->input->post('idp'),
			        			  'id_produk'=>$this->input->post('aa'),
			        			  'harga_jual'=>$this->input->post('bb'));
					$this->model_app->insert('rb_penjualan_detail',$data);
				}else{
			        $data = array('id_produk'=>$this->input->post('aa'),
			        			  'harga_jual'=>$this->input->post('bb'));
					$where = array('id_penjualan_detail' => $this->input->post('idpd'));
					$this->model_app->update('rb_penjualan_detail', $data, $where);
				}
				redirect($this->uri->segment(1).'/edit_penjualan/'.$this->input->post('idp'));
			}
			
		}else{
			$data['rows'] = $this->model_reseller->penjualan_konsumen_detail_reseller($this->uri->segment(3))->row_array();
			$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->uri->segment(3)),'id_penjualan_detail','DESC');
			$data['barang'] = $this->model_app->view_ordering('rb_produk','id_produk','ASC');
			$data['konsumen'] = $this->model_app->view_ordering('rb_konsumen','id_konsumen','ASC');
			if ($this->uri->segment(4)!=''){
				$data['row'] = $this->model_app->view_where('rb_penjualan_detail',array('id_penjualan_detail'=>$this->uri->segment(4)))->row_array();
			}
			$this->template->load(template(1).'/template',template(1).'/mod_penjualan/view_penjualan_edit',$data);
		}
	}

	function proses_penjualan(){
		cek_session_members();
	        $data = array('proses'=>$this->uri->segment(4));
			$where = array('id_penjualan' => $this->uri->segment(3));
			$this->model_app->update('rb_penjualan', $data, $where);
			redirect($this->uri->segment(1).'/penjualan');
	}

	function proses_penjualan_detail(){
		cek_session_members();
        $data = array('proses'=>$this->uri->segment(4));
		$where = array('id_penjualan' => $this->uri->segment(3));
		$this->model_app->update('rb_penjualan', $data, $where);
		redirect($this->uri->segment(1).'/detail_penjualan'.$this->uri->segment(3));
	}

	function delete_penjualan(){
        cek_session_members();
		$id = array('id_penjualan' => $this->uri->segment(3));
		$this->model_app->delete('rb_penjualan',$id);
		$this->model_app->delete('rb_penjualan_detail',$id);
		redirect($this->uri->segment(1).'/penjualan');
	}

	function delete_penjualan_detail(){
        cek_session_members();
		$id = array('id_penjualan_detail' => $this->uri->segment(4));
		$this->model_app->delete('rb_penjualan_detail',$id);
		redirect($this->uri->segment(1).'/edit_penjualan/'.$this->uri->segment(3));
	}

	function delete_penjualan_tambah_detail(){
        cek_session_members();
		$id = array('id_penjualan_detail' => $this->uri->segment(3));
		$this->model_app->delete('rb_penjualan_detail',$id);
		redirect($this->uri->segment(1).'/tambah_penjualan');
	}

	function detail_konsumen(){
		cek_session_members();
		$id = $this->uri->segment(3);
		$edit = $this->model_app->edit('rb_konsumen',array('id_konsumen'=>$id))->row_array();
		$data = array('rows' => $edit);
		$this->template->load(template(1).'/template',template(1).'/mod_konsumen/view_konsumen_detail',$data);
	}


	// Controler Konfirmasi
	
	function konfirmasi_pembayaran(){
		cek_session_members();
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/bukti_transfer/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '10000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('f');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
				$data = array('id_penjualan'=>$this->input->post('id'),
			        		  'total_transfer'=>$this->input->post('b'),
			        		  'id_rekening'=>$this->input->post('c'),
			        		  'nama_pengirim'=>$this->input->post('d'),
			        		  'tanggal_transfer'=>$this->input->post('e'),
			        		  'waktu_konfirmasi'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konfirmasi_pembayaran',$data);
			}else{
				$data = array('id_penjualan'=>$this->input->post('id'),
			        		  'total_transfer'=>$this->input->post('b'),
			        		  'id_rekening'=>$this->input->post('c'),
			        		  'nama_pengirim'=>$this->input->post('d'),
			        		  'tanggal_transfer'=>$this->input->post('e'),
			        		  'bukti_transfer'=>$hasil['file_name'],
			        		  'waktu_konfirmasi'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konfirmasi_pembayaran',$data);
			}
				$data1 = array('proses'=>'2');
				$where = array('id_penjualan' => $this->input->post('id'));
				$this->model_app->update('rb_penjualan', $data1, $where);
			redirect($this->uri->segment(1).'/pembelian');
		}else{
			$data['record'] = $this->model_app->view('rb_rekening');
			$data['total'] = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-a.diskon) as total FROM `rb_penjualan_detail` a where a.id_penjualan='".$this->uri->segment(3)."'")->row_array();
			$data['rows'] = $this->model_app->view_where('rb_penjualan',array('id_penjualan'=>$this->uri->segment(3)))->row_array();
			$this->template->load(template(1).'/template',template(1).'/mod_pembelian/view_konfirmasi_pembayaran',$data);
		}
	}
	


}