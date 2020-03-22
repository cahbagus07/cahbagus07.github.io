<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		$this->load->view('v_login'); 
	}

	public function aksi()
	{

		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if($this->form_validation->run() != false){

			// menangkap data username dan password dari halaman login
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$where = array(
				'pengguna_username' => $username,
				'pengguna_password' => md5($password),
				'pengguna_status' => 1
			);

			$this->load->model('m_data');

			// cek kesesuaian login pada table pengguna
			$cek = $this->m_data->cek_login('pengguna',$where)->num_rows();


			//cek jika login benar
			if($cek > 0){

				// ambil data pengguna yang melakukan login
				$data = $this->m_data->cek_login('pengguna',$where)->row();
				if ($data->pengguna_level == 'admin') 
				{
					// buat session untuk pengguna yang berhasil login
					$data_session = array(
						'id' => $data->pengguna_id,
						'email' => $data->pengguna_email,
						'username' => $data->pengguna_username,
						'level' => $data->pengguna_level,
						'status' => 'telah_login'
					);
					$this->session->set_userdata($data_session);

					//alihkan halaman ke halaman dashboard pengguna

					redirect(base_url().'dashboard');
				}
				else if ($data->pengguna_level == 'penulis') 
				{
					// buat session untuk pengguna yang berhasil login
					$data_session = array(
						'id' => $data->pengguna_id,
						'email' => $data->pengguna_email,
						'username' => $data->pengguna_username,
						'level' => $data->pengguna_level,
						'status' => 'telah_login'
					);
					$this->session->set_userdata($data_session);

					//alihkan halaman ke halaman dashboard pengguna

					redirect(base_url().'dashboard');
				}
				else if ($data->pengguna_level == 'siswa') 
				{
					// buat session untuk pengguna yang berhasil login
					$data_session = array(
						'id' => $data->pengguna_id,
						'email' => $data->pengguna_email,
						'username' => $data->pengguna_username,
						'nama' => $data->pengguna_nama,
						'level' => $data->pengguna_level,
						'status' => 'telah_login'
					);
					$this->session->set_userdata($data_session);

					//alihkan halaman ke halaman dashboard pengguna

					redirect(base_url().'dashboard/siswa');
				}

				
			}else{
				redirect(base_url().'login?alert=gagal');
			}

		}else{
			$this->load->view('v_login');
			
		}
	}
}
