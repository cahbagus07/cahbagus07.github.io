<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller { 
 
	function __construct()
	{
		parent::__construct();
 
		date_default_timezone_set('Asia/Jakarta');

		$this->load->model('m_data');

		// cek session yang login, 
		// jika session status tidak sama dengan session telah_login, berarti pengguna belum login
		// maka halaman akan di alihkan kembali ke halaman login.
		if($this->session->userdata('status')!="telah_login"){
			redirect(base_url().'login?alert=belum_login');
		}
	}

	public function index()
	{
		// hitung jumlah artikel
		$data['jumlah_artikel'] = $this->m_data->get_data('artikel')->num_rows();
		// hitung jumlah kategori
		$data['jumlah_kategori'] = $this->m_data->get_data('kategori')->num_rows();
		// hitung jumlah pengguna
		$data['jumlah_pengguna'] = $this->m_data->get_data('pengguna')->num_rows();
		// hitung jumlah halaman
		$data['jumlah_halaman'] = $this->m_data->get_data('halaman')->num_rows();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_index',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function keluar()
	{
		$this->session->sess_destroy();
		redirect('login?alert=logout');
	}

	public function ganti_password()
	{
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_ganti_password');
		$this->load->view('dashboard/v_footer');
	}

	public function ganti_password_aksi()
	{

		// form validasi
		$this->form_validation->set_rules('password_lama','Password Lama','required');
		$this->form_validation->set_rules('password_baru','Password Baru','required|min_length[8]');
		$this->form_validation->set_rules('konfirmasi_password','Konfirmasi Password Baru','required|matches[password_baru]');

		// cek validasi
		if($this->form_validation->run() != false){

			// menangkap data dari form
			$password_lama = $this->input->post('password_lama');
			$password_baru = $this->input->post('password_baru');
			$konfirmasi_password = $this->input->post('konfirmasi_password');

			// cek kesesuaian password lama dengan id pengguna yang sedang login dan password lama
			$where = array(
				'pengguna_id' => $this->session->userdata('id'),
				'pengguna_password' => md5($password_lama)
			);
			$cek = $this->m_data->cek_login('pengguna', $where)->num_rows();

			// cek kesesuaikan password lama
			if($cek > 0){

				// update data password pengguna
				$w = array(
					'pengguna_id' => $this->session->userdata('id')
				);
				$data = array(
					'pengguna_password' => md5($password_baru)
				);
				$this->m_data->update_data($where, $data, 'pengguna');

				// alihkan halaman kembali ke halaman ganti password
				redirect('dashboard/ganti_password?alert=sukses');
			}else{
				// alihkan halaman kembali ke halaman ganti password
				redirect('dashboard/ganti_password?alert=gagal');
			}

		}else{
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_ganti_password');
			$this->load->view('dashboard/v_footer');
		}

	}

	// CRUD KATEGORI
	public function kategori()
	{
		$data['kategori'] = $this->m_data->get_data('kategori')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_kategori',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function kategori_tambah()
	{
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_kategori_tambah');
		$this->load->view('dashboard/v_footer');
	}

	public function kategori_aksi()
	{
		$this->form_validation->set_rules('kategori','Kategori','required');

		if($this->form_validation->run() != false){

			$kategori = $this->input->post('kategori');

			$data = array(
				'kategori_nama' => $kategori,
				'kategori_slug' => strtolower(url_title($kategori))
			);

			$this->m_data->insert_data($data,'kategori');

			redirect(base_url().'dashboard/kategori');
			
		}else{
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_kategori_tambah');
			$this->load->view('dashboard/v_footer');
		}
	}

	public function kategori_edit($id)
	{
		$where = array(
			'kategori_id' => $id
		);
		$data['kategori'] = $this->m_data->edit_data($where,'kategori')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_kategori_edit',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function kategori_update()
	{
		$this->form_validation->set_rules('kategori','Kategori','required');

		if($this->form_validation->run() != false){

			$id = $this->input->post('id');
			$kategori = $this->input->post('kategori');

			$where = array(
				'kategori_id' => $id
			);

			$data = array(
				'kategori_nama' => $kategori,
				'kategori_slug' => strtolower(url_title($kategori))
			);

			$this->m_data->update_data($where, $data,'kategori');

			redirect(base_url().'dashboard/kategori');
			
		}else{

			$id = $this->input->post('id');
			$where = array(
				'kategori_id' => $id
			);
			$data['kategori'] = $this->m_data->edit_data($where,'kategori')->result();
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_kategori_edit',$data);
			$this->load->view('dashboard/v_footer');
		}
	}


	public function kategori_hapus($id)
	{
		$where = array(
			'kategori_id' => $id
		);

		$this->m_data->delete_data($where,'kategori');

		redirect(base_url().'dashboard/kategori');
	}
	// END CRUD KATEGORI

	// CRUD ARTIKEL
	public function artikel()
	{
		if ($this->session->userdata('level') == 'penulis') {
			$id = $this->session->userdata('id');
			$data['artikel'] = $this->db->query("SELECT * FROM artikel,kategori,pengguna WHERE artikel_kategori=kategori_id and artikel_author=pengguna_id AND artikel_author = '$id' order by artikel_id desc")->result();
		}
		else
		{
			$data['artikel'] = $this->db->query("SELECT * FROM artikel,kategori,pengguna WHERE artikel_kategori=kategori_id and artikel_author=pengguna_id  order by artikel_id desc")->result();	
		}
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_artikel',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function artikel_tambah()
	{
		$data['kategori'] = $this->m_data->get_data('kategori')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_artikel_tambah',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function artikel_aksi()
	{
		// Wajib isi judul,konten dan kategori
		$this->form_validation->set_rules('judul','Judul','required|is_unique[artikel.artikel_judul]');
		$this->form_validation->set_rules('konten','Konten','required');
		$this->form_validation->set_rules('kategori','Kategori','required');

		// Membuat gambar wajib di isi
		if (empty($_FILES['sampul']['name'])){
			$this->form_validation->set_rules('sampul', 'Gambar Sampul', 'required');
		}

		if($this->form_validation->run() != false){

			$config['upload_path']   = './gambar/artikel/';
			$config['allowed_types'] = 'gif|jpg|png';

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('sampul')) {

				// mengambil data tentang gambar
				$gambar = $this->upload->data();

				$tanggal = date('Y-m-d H:i:s');
				$judul = $this->input->post('judul');
				$slug = strtolower(url_title($judul));
				$konten = $this->input->post('konten');
				$sampul = $gambar['file_name'];
				$author = $this->session->userdata('id');
				$kategori = $this->input->post('kategori');
				$status = 'draft';

				$data = array(
					'artikel_tanggal' => $tanggal,
					'artikel_judul' => $judul,
					'artikel_slug' => $slug,
					'artikel_konten' => $konten,
					'artikel_sampul' => $sampul,
					'artikel_author' => $author,
					'artikel_kategori' => $kategori,
					'artikel_status' => $status,
				);

				$this->m_data->insert_data($data,'artikel');

				redirect(base_url().'dashboard/artikel');	
				
			} else {

				$this->form_validation->set_message('sampul', $data['gambar_error'] = $this->upload->display_errors());

				$data['kategori'] = $this->m_data->get_data('kategori')->result();
				$this->load->view('dashboard/v_header');
				$this->load->view('dashboard/v_artikel_tambah',$data);
				$this->load->view('dashboard/v_footer');
			}

		}else{
			$data['kategori'] = $this->m_data->get_data('kategori')->result();
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_artikel_tambah',$data);
			$this->load->view('dashboard/v_footer');
		}
	}


	public function artikel_edit($id)
	{
		$where = array(
			'artikel_id' => $id
		);
		$data['artikel'] = $this->m_data->edit_data($where,'artikel')->result();
		$data['kategori'] = $this->m_data->get_data('kategori')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_artikel_edit',$data);
		$this->load->view('dashboard/v_footer');
	}


	public function artikel_update()
	{
		// Wajib isi judul,konten dan kategori
		$this->form_validation->set_rules('judul','Judul','required');
		$this->form_validation->set_rules('konten','Konten','required');
		$this->form_validation->set_rules('kategori','Kategori','required');
		
		if($this->form_validation->run() != false){

			$id = $this->input->post('id');

			$judul = $this->input->post('judul');
			$slug = strtolower(url_title($judul));
			$konten = $this->input->post('konten');
			$kategori = $this->input->post('kategori');
			$status = $this->input->post('status');

			$where = array(
				'artikel_id' => $id
			);

			$data = array(
				'artikel_judul' => $judul,
				'artikel_slug' => $slug,
				'artikel_konten' => $konten,
				'artikel_kategori' => $kategori,
				'artikel_status' => $status,
			);

			$this->m_data->update_data($where,$data,'artikel');


			if (!empty($_FILES['sampul']['name'])){
				$config['upload_path']   = './gambar/artikel/';
				$config['allowed_types'] = 'gif|jpg|png';

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('sampul')) {

					// mengambil data tentang gambar
					$gambar = $this->upload->data();

					$data = array(
						'artikel_sampul' => $gambar['file_name'],
					);

					$this->m_data->update_data($where,$data,'artikel');

					redirect(base_url().'dashboard/artikel');	

				} else {
					$this->form_validation->set_message('sampul', $data['gambar_error'] = $this->upload->display_errors());
					
					$where = array(
						'artikel_id' => $id
					);
					$data['artikel'] = $this->m_data->edit_data($where,'artikel')->result();
					$data['kategori'] = $this->m_data->get_data('kategori')->result();
					$this->load->view('dashboard/v_header');
					$this->load->view('dashboard/v_artikel_edit',$data);
					$this->load->view('dashboard/v_footer');
				}
			}else{
				redirect(base_url().'dashboard/artikel');	
			}

		}else{
			$id = $this->input->post('id');
			$where = array(
				'artikel_id' => $id
			);
			$data['artikel'] = $this->m_data->edit_data($where,'artikel')->result();
			$data['kategori'] = $this->m_data->get_data('kategori')->result();
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_artikel_edit',$data);
			$this->load->view('dashboard/v_footer');
		}
	}

	public function artikel_hapus($id)
	{
		$where = array(
			'artikel_id' => $id
		);

		$this->m_data->delete_data($where,'artikel');

		redirect(base_url().'dashboard/artikel');
	}
	// end crud artikel


	// CRUD PAGES
	public function pages()
	{
		$data['halaman'] = $this->m_data->get_data('halaman')->result();	
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pages',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function pages_tambah()
	{
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pages_tambah');
		$this->load->view('dashboard/v_footer');
	}

	public function pages_aksi()
	{
		// Wajib isi judul,konten
		$this->form_validation->set_rules('judul','Judul','required|is_unique[halaman.halaman_judul]');
		$this->form_validation->set_rules('konten','Konten','required');

		if($this->form_validation->run() != false){

			$judul = $this->input->post('judul');
			$slug = strtolower(url_title($judul));
			$konten = $this->input->post('konten');

			$data = array(
				'halaman_judul' => $judul,
				'halaman_slug' => $slug,
				'halaman_konten' => $konten
			);

			$this->m_data->insert_data($data,'halaman');

			// alihkan kembali ke method pages
			redirect(base_url().'dashboard/pages');	

		}else{
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_pages_tambah');
			$this->load->view('dashboard/v_footer');
		}
	}

	public function pages_edit($id)
	{
		$where = array(
			'halaman_id' => $id
		);
		$data['halaman'] = $this->m_data->edit_data($where,'halaman')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pages_edit',$data);
		$this->load->view('dashboard/v_footer');
	}


	public function pages_update()
	{
		// Wajib isi judul,konten 
		$this->form_validation->set_rules('judul','Judul','required');
		$this->form_validation->set_rules('konten','Konten','required');
		
		if($this->form_validation->run() != false){

			$id = $this->input->post('id');

			$judul = $this->input->post('judul');
			$slug = strtolower(url_title($judul));
			$konten = $this->input->post('konten');
			
			$where = array(
				'halaman_id' => $id
			);

			$data = array(
				'halaman_judul' => $judul,
				'halaman_slug' => $slug,
				'halaman_konten' => $konten
			);

			$this->m_data->update_data($where,$data,'halaman');

			redirect(base_url().'dashboard/pages');
		}else{
			$id = $this->input->post('id');
			$where = array(
				'halaman_id' => $id
			);
			$data['halaman'] = $this->m_data->edit_data($where,'halaman')->result();
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_pages_edit',$data);
			$this->load->view('dashboard/v_footer');
		}
	}

	public function pages_hapus($id)
	{
		$where = array(
			'halaman_id' => $id
		);
		
		$this->m_data->delete_data($where,'halaman');

		redirect(base_url().'dashboard/pages');
	}
	// end crud pages


	public function profil()
	{
		// id pengguna yang sedang login
		$id_pengguna = $this->session->userdata('id');

		$data['penulis'] = $this->db->query("select * from pengguna join detail_penulis on pengguna.pengguna_id = detail_penulis.pengguna_id where pengguna.pengguna_id = '$id_pengguna'")->result();

		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_profil',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function profil_update()
	{
		// Wajib isi nama dan email
		$this->form_validation->set_rules('nama','Nama','required');
		$this->form_validation->set_rules('email','Email','required');
		
		if($this->form_validation->run() != false){

			$id = $this->session->userdata('id');

			$nama = $this->input->post('nama');
			$email = $this->input->post('email');
			
			$where = array(
				'pengguna_id' => $id
			);

			$data = array(
				'pengguna_nama' => $nama,
				'pengguna_email' => $email
			);

			$this->m_data->update_data($where,$data,'pengguna');

			redirect(base_url().'dashboard/profil/?alert=sukses');
		}else{
			// id pengguna yang sedang login
			$id_pengguna = $this->session->userdata('id');

			$where = array(
				'pengguna_id' => $id_pengguna
			);

			$data['profil'] = $this->m_data->edit_data($where,'pengguna')->result();

			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_profil',$data);
			$this->load->view('dashboard/v_footer');
		}
	}


	public function pengaturan()
	{
		$data['pengaturan'] = $this->m_data->get_data('pengaturan')->result();

		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pengaturan',$data);
		$this->load->view('dashboard/v_footer');
	}


	public function pengaturan_update()
	{
		// Wajib isi nama dan deskripsi website
		$this->form_validation->set_rules('nama','Nama Website','required');
		$this->form_validation->set_rules('deskripsi','Deskripsi Website','required');
		
		if($this->form_validation->run() != false){

			$nama = $this->input->post('nama');
			$deskripsi = $this->input->post('deskripsi');
			$link_facebook = $this->input->post('link_facebook');
			$link_twitter = $this->input->post('link_twitter');
			$link_instagram = $this->input->post('link_instagram');
			$link_github = $this->input->post('link_github');

			$where = array(

			);

			$data = array(
				'nama' => $nama,
				'deskripsi' => $deskripsi,
				'link_facebook' => $link_facebook,
				'link_twitter' => $link_twitter,
				'link_instagram' => $link_instagram,
				'link_github' => $link_github
			);

			// update pengaturan
			$this->m_data->update_data($where,$data,'pengaturan');

			// Periksa apakah ada gambar logo yang diupload
			if (!empty($_FILES['logo']['name'])){
				
				$config['upload_path']   = './gambar/website/';
				$config['allowed_types'] = 'jpg|png';

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('logo')) {
					// mengambil data tentang gambar logo yang diupload
					$gambar = $this->upload->data();

					$logo = $gambar['file_name'];
					
					$this->db->query("UPDATE pengaturan SET logo='$logo'");
				}
			}

			redirect(base_url().'dashboard/pengaturan/?alert=sukses');

		}else{
			$data['pengaturan'] = $this->m_data->get_data('pengaturan')->result();

			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_pengaturan',$data);
			$this->load->view('dashboard/v_footer');
		}
	}

	// CRUD PENGGUNA
	public function pengguna()
	{
		$data['pengguna'] = $this->m_data->get_data('pengguna')->result();	
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pengguna',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function pengguna_tambah()
	{
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pengguna_tambah');
		$this->load->view('dashboard/v_footer');
	}

	public function pengguna_aksi()
	{
		// Wajib isi
		$this->form_validation->set_rules('nama','Nama Pengguna','required');
		$this->form_validation->set_rules('email','Email Pengguna','required');
		$this->form_validation->set_rules('username','Username Pengguna','required');
		$this->form_validation->set_rules('password','Password Pengguna','required|min_length[8]');
		$this->form_validation->set_rules('level','Level Pengguna','required');
		$this->form_validation->set_rules('status','Status Pengguna','required');

		if($this->form_validation->run() != false){

			$nama = $this->input->post('nama');
			$email = $this->input->post('email');
			$username = $this->input->post('username');
			$password = md5($this->input->post('password'));
			$level = $this->input->post('level');
			$status = $this->input->post('status');

			$data = array(
				'pengguna_nama' => $nama,
				'pengguna_email' => $email,
				'pengguna_username' => $username,
				'pengguna_password' => $password,
				'pengguna_level' => $level,
				'pengguna_status' => $status
			);


			$this->m_data->insert_data($data,'pengguna');

			redirect(base_url().'dashboard/pengguna');	

		}else{
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_pengguna_tambah');
			$this->load->view('dashboard/v_footer');
		}
	}

	public function pengguna_edit($id)
	{
		$where = array(
			'pengguna_id' => $id
		);
		$data['pengguna'] = $this->m_data->edit_data($where,'pengguna')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pengguna_edit',$data);
		$this->load->view('dashboard/v_footer');
	}


	public function pengguna_update()
	{
		// Wajib isi
		$this->form_validation->set_rules('nama','Nama Pengguna','required');
		$this->form_validation->set_rules('email','Email Pengguna','required');
		$this->form_validation->set_rules('username','Username Pengguna','required');
		$this->form_validation->set_rules('level','Level Pengguna','required');
		$this->form_validation->set_rules('status','Status Pengguna','required');

		if($this->form_validation->run() != false){

			$id = $this->input->post('id');

			$nama = $this->input->post('nama');
			$email = $this->input->post('email');
			$username = $this->input->post('username');
			$password = md5($this->input->post('password'));
			$level = $this->input->post('level');
			$status = $this->input->post('status');

			if($this->input->post('password') == ""){
				$data = array(
					'pengguna_nama' => $nama,
					'pengguna_email' => $email,
					'pengguna_username' => $username,
					'pengguna_level' => $level,
					'pengguna_status' => $status
				);
			}else{
				$data = array(
					'pengguna_nama' => $nama,
					'pengguna_email' => $email,
					'pengguna_username' => $username,
					'pengguna_password' => $password,
					'pengguna_level' => $level,
					'pengguna_status' => $status
				);
			}
			
			$where = array(
				'pengguna_id' => $id
			);

			$this->m_data->update_data($where,$data,'pengguna');

			redirect(base_url().'dashboard/detail_pengguna');
		}else{
			$id = $this->input->post('id');
			$where = array(
				'pengguna_id' => $id
			);
			$data['pengguna'] = $this->m_data->edit_data($where,'pengguna')->result();
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_pengguna_edit',$data);
			$this->load->view('dashboard/v_footer');
		}
	}

	public function pengguna_hapus($id)
	{
		$where = array(
			'pengguna_id' => $id
		);
		$data['pengguna_hapus'] = $this->m_data->edit_data($where,'pengguna')->row();
		$data['pengguna_lain'] = $this->db->query("SELECT * FROM pengguna WHERE pengguna_id != $id")->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pengguna_hapus',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function pengguna_hapus_aksi()
	{
		$pengguna_hapus = $this->input->post('pengguna_hapus');
		$pengguna_tujuan = $this->input->post('pengguna_tujuan');

		// hapus pengguna
		$where = array(
			'pengguna_id' => $pengguna_hapus
		);

		$this->m_data->delete_data($where,'pengguna');

		// pindahkan semua artikel pengguna yang dihapus ke pengguna yang dipilih
		$w = array(
			'artikel_author' => $pengguna_hapus
		);

		$d = array(
			'artikel_author' => $pengguna_tujuan
		);

		$this->m_data->update_data($w,$d,'artikel');

		redirect(base_url().'dashboard/pengguna');
	}
	// end crud pengguna
	public function siswa()
	{
		// data pengaturan website
		$data['pengaturan'] = $this->m_data->get_data('pengaturan')->row();

		// SEO META
		$data['meta_keyword'] = $data['pengaturan']->nama;
		$data['meta_description'] = $data['pengaturan']->deskripsi;

		$data['artikel'] = $this->db->query("SELECT * FROM artikel,pengguna,kategori WHERE artikel_status='publish' AND artikel_author=pengguna_id AND artikel_kategori=kategori_id ORDER BY artikel_id DESC LIMIT 3 ")->result();
		$this->load->view('siswa/header',$data);
		$this->load->view('siswa/index',$data);
		$this->load->view('siswa/footer',$data);
	}
	public function konfirmasi_artikel()
	{
		$status = $this->input->post('statusartikel');
		$id = $this->input->post('id');
		$this->db->query("update artikel set artikel_status = '$status' where artikel_id = '$id' ");
        redirect('dashboard/artikel');
	}
	public function private_learning()
	{
		$id = $this->session->userdata('id');
        $data['datasiswa'] = $this->m_data->get_private_penulis($id);
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_private',$data);
		$this->load->view('dashboard/v_footer');
	
	}
	public function konfirmasi_pengajuan()
	{
		$status = $this->input->post('status');
		$keterangan =  $this->input->post('keterangan');
		$id =  $this->input->post('id');
		$query = $this->db->query("update private_learning set status = '$status',keterangan = '$keterangan' where id = '$id' ");
		if ($status == 'Diterima') {

		$this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success' role='alert'>
           
               <span class='alert-inner--text'><strong>Success!</strong> Anda menerima pekerjaan yang diberikan. silahkan hubungi siswa untuk kelanjutannya</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
		redirect('dashboard/private_learning');
		}
		else
		{
			$this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success' role='alert'>
           
               <span class='alert-inner--text'><strong>Success!</strong> Anda menolak pekerjaan yang diberikan.</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
		redirect('dashboard/private_learning');
		}
	}
	public function hapus_pengajuan()
	{
		$id =  $this->input->post('id');
		$query = $this->db->query("Delete from private_learning where id = '$id' ");

		$this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success' role='alert'>
           
               <span class='alert-inner--text'><strong>Success!</strong> Berhasil hapus data permintaan private learning</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
		redirect('dashboard/private_learning');
	}
	public function subkategori($id)
	{
		if ($this->session->userdata('level') != 'admin') {
			$idku = $this->session->userdata('id');
		$data['data'] = $this->db->query("Select * from subkategori where kategori_id = '$id' and author_id = '$idku'")->result();
		}
		else
		{
			$data['data'] = $this->db->query("Select * from subkategori where kategori_id = '$id'")->result();
		}
		
		$data['id'] = $id;
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_subkategori',$data);
		$this->load->view('dashboard/v_footer');

	}
	public function subkategori_add()
	{
		$query = $this->m_data->add_sub($this->input->post());
		$id = $this->input->post('kategori_id');
		$this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success' role='alert'>
           
               <span class='alert-inner--text'><strong>Success!</strong> Berhasil hapus data permintaan private learning</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
		redirect('dashboard/subkategori/'.$id);
	}
	public function soal($id)
	{
		$data = $this->db->query("Select * from subkategori where subkategori_id = '$id'")->row_array();
		
		$data['a'] = $this->db->query("Select * from subkategori where subkategori_id = '$id'")->row_array();
		if ($data['tipe_soal'] == 'Pilihan Ganda') {
			$data['jumlah'] = $this->db->query("Select * from soal where subkategori_id = '$id'")->num_rows();
		}
		else
		{
			$data['jumlah'] = $this->db->query("Select * from essay where subkategori_id = '$id'")->num_rows();
		}
		$data['id'] = $id;
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_soal',$data);
		$this->load->view('dashboard/v_footer');
	}
	public function soalpilgan_add()
	{ 
		$query = $this->m_data->add_soal($this->input->post());
		$id = $this->input->post('subkategori_id');
		$this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success' role='alert'>
           
               <span class='alert-inner--text'><strong>Success!</strong> Berhasil input soal, silahkan tambahkan soal hinga berjumlah 20 soal</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
		redirect('dashboard/soal/'.$id);
	}
	public function soalessay_add()
	{
		$query = $this->m_data->add_soalessay($this->input->post());
		$id = $this->input->post('subkategori_id');
		$this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success' role='alert'>
           
               <span class='alert-inner--text'><strong>Success!</strong> Berhasil input soal, silahkan tambahkan soal hinga berjumlah 10 soal</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
		redirect('dashboard/soal/'.$id);
	}
	public function lihat_soal($id)
	{
		$data = $this->db->query("Select * from subkategori where subkategori_id = '$id'")->row_array();
		$data['a'] = $this->db->query("Select * from subkategori where subkategori_id = '$id'")->row_array();

		if ($data['tipe_soal'] == 'Pilihan Ganda') {
			
			$data['jumlah'] = $this->db->query("Select * from soal where subkategori_id = '$id'")->num_rows();
			$data['soal'] = $this->db->query("Select * from soal where subkategori_id = '$id'")->result();
		}
		else
		{
			$data['jumlah'] = $this->db->query("Select * from essay where subkategori_id = '$id'")->num_rows();
			$data['soal'] = $this->db->query("Select * from essay where subkategori_id = '$id'")->result();
		}
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_lihat_soal',$data);
		$this->load->view('dashboard/v_footer');
	}
	public function aksi_subkategori()
	{
		$status = $this->input->post('status');
		$id = $this->input->post('id');
		$kategori_id = $this->input->post('kategori_id');
		$query = $this->db->query("update subkategori set status = '$status' where subkategori_id = '$id' ");
		if ($status == 'Diterima') {
			$this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success' role='alert'>
           
               <span class='alert-inner--text'><strong>Success!</strong> Berhasil mengaktifkan soal</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
			redirect('dashboard/subkategori/'.$kategori_id);
		}
		else
		{
			$this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success' role='alert'>
           
               <span class='alert-inner--text'><strong>Success!</strong> Berhasil menonaktifkan soal</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
			redirect('dashboard/subkategori/'.$kategori_id);
		}
		
	}
	public function ujian()
	{
		$id = $this->session->userdata('id');
		$data['ujian'] = $this->db->query("select jawaban_essay.*,pengguna.*,subkategori.subkategori_nama from jawaban_essay join subkategori on jawaban_essay.subkategori_id = subkategori.subkategori_id join pengguna on jawaban_essay.siswa_id = pengguna.pengguna_id where subkategori.author_id = '$id'group by jawaban_essay.event ")->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_ujian',$data);
		$this->load->view('dashboard/v_footer');
	}
	public function koreksi($id)
	{
		$data['list'] = $this->db->query("select * from jawaban_essay join essay on jawaban_essay.soal_id = essay.essay_id join pengguna on jawaban_essay.siswa_id = pengguna.pengguna_id where event = '$id'  ")->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_koreksi',$data);
		$this->load->view('dashboard/v_footer');
	}
	public function submit_koreksi()
	{
		$sub = $this->input->post('sub');
		$benar = $this->input->post('nilai');
		$event = $this->input->post('event');
		$a = count($benar);
		$score = $a * 10;
		$this->db->query("INSERT INTO history values ('','$sub','$score')");
		$this->db->query("Update jawaban_essay set status = 'Sudah Dikoreksi' where event = '$event' ");
		$this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success' role='alert'>
           
               <span class='alert-inner--text'><strong>Success!</strong> Berhasil memberikan nilai ujian essay</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
		redirect('dashboard/ujian');
	}
	public function detail_pengguna()
	{
		$data['siswa'] = $this->db->query("select * from pengguna where pengguna_level = 'siswa'")->result();
		$data['pengajar'] = $this->db->query("select * from pengguna where pengguna_level = 'penulis'")->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_detail_pengguna',$data);
		$this->load->view('dashboard/v_footer');
	}
	public function detail_siswa($id)
	{
		$data['siswa'] = $this->db->query("select * from pengguna join detail_siswa on pengguna.pengguna_id = detail_siswa.pengguna_id where pengguna.pengguna_id = '$id'")->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_detail_siswa',$data);
		$this->load->view('dashboard/v_footer');
	}
	public function detail_penulis($id)
	{
		$data['penulis'] = $this->db->query("select * from pengguna join detail_penulis on pengguna.pengguna_id = detail_penulis.pengguna_id where pengguna.pengguna_id = '$id'")->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_detail_penulis',$data);
		$this->load->view('dashboard/v_footer');
	}
	

	
}
