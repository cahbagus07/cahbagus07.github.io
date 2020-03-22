<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		date_default_timezone_set('Asia/Jakarta');
		 $this->load->library('pagination');

		$this->load->model('m_data');
         $this->load->library('pdf');

		// cek session yang login, 
		// jika session status tidak sama dengan session telah_login, berarti pengguna belum login
		// maka halaman akan di alihkan kembali ke halaman login.
		if($this->session->userdata('status')!="telah_login"){
			redirect(base_url().'login?alert=belum_login');
		}
	}

	public function materi()
	{
		$data['pengaturan'] = $this->m_data->get_data('pengaturan')->row();

		// SEO META
		$data['meta_keyword'] = $data['pengaturan']->nama;
		$data['meta_description'] = $data['pengaturan']->deskripsi;
		//konfigurasi pagination
        $config['base_url'] = site_url('siswa/materi'); //site url
        $config['total_rows'] = $this->db->count_all('artikel'); //total row
        $config['per_page'] = 5;  //show record per halaman
        $config["uri_segment"] = 3;  // uri parameter
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
 
        // Membuat Style pagination untuk BootStrap v4
      $config['first_link']       = 'First';
        $config['last_link']        = 'Last';
        $config['next_link']        = 'Next';
        $config['prev_link']        = 'Prev';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';
 
        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
 
        //panggil function get_mahasiswa_list yang ada pada mmodel mahasiswa_model. 
        $data['artikel'] = $this->m_data->get_data_artikel($config["per_page"], $data['page']);         
 
        $data['pagination'] = $this->pagination->create_links();


		// $data['artikel'] = $this->db->query("SELECT * FROM artikel,pengguna,kategori WHERE artikel_status='publish' AND artikel_author=pengguna_id AND artikel_kategori=kategori_id ORDER BY artikel_id DESC LIMIT $config[per_page] OFFSET $from")->result();
		$this->load->view('siswa/header',$data);
		$this->load->view('siswa/materi',$data);
		$this->load->view('siswa/footer',$data);
	}
	public function search()
	{	
		 //mengambil nilai keyword dari form pencarian
		$cari = htmlentities((trim($this->input->post('cari',true)))? trim($this->input->post('cari',true)) : '');
		$key = $this->input->post('cari',true);

     		//jika uri segmen 2 ada, maka nilai variabel $search akan diganti dengan nilai uri segmen 2
		$cari = ($this->uri->segment(2)) ? $this->uri->segment(2) : $cari;

		// data pengaturan website
		$data['pengaturan'] = $this->m_data->get_data('pengaturan')->row();

		// SEO META
		$data['meta_keyword'] = $data['pengaturan']->nama;
		$data['meta_description'] = $data['pengaturan']->deskripsi;
		
		//$this->load->library('pagination');
		$config['base_url'] = site_url('siswa/search'); //site url

        $config['total_rows'] = $this->db->query("SELECT * FROM list_artikel where artikel_judul LIKE '%$key%' OR artikel_konten LIKE '%$key%'")->num_rows();

        $config['per_page'] = 5;  //show record per halaman
        $config["uri_segment"] = 3;  // uri parameter
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
 
        // Membuat Style pagination untuk BootStrap v4
      $config['first_link']       = 'First';
        $config['last_link']        = 'Last';
        $config['next_link']        = 'Next';
        $config['prev_link']        = 'Prev';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';
 
        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;   
		$data['artikel'] = $this->db->query("SELECT * FROM artikel,pengguna,kategori WHERE artikel_status='publish' AND artikel_author=pengguna_id AND artikel_kategori=kategori_id AND (artikel_judul LIKE '%$key%' OR artikel_konten LIKE '%$key%')")->result();
		$data['cari'] = $cari;
		$data['pagination'] = $this->pagination->create_links();
		$this->load->view('siswa/header',$data);
		$this->load->view('siswa/search',$data);
		$this->load->view('siswa/footer',$data);
	}
    public function single($slug)
    {
        $data['artikel'] = $this->db->query("SELECT * FROM artikel,pengguna,kategori WHERE artikel_status='publish' AND artikel_author=pengguna_id AND artikel_kategori=kategori_id AND artikel_slug='$slug'")->result();

        // data pengaturan website
        $data['pengaturan'] = $this->m_data->get_data('pengaturan')->row();
        
        // SEO META
        if(count($data['artikel']) > 0){
            $data['meta_keyword'] = $data['artikel'][0]->artikel_judul;
            $data['meta_description'] = substr($data['artikel'][0]->artikel_konten, 0,100);
        }else{
            $data['meta_keyword'] = $data['pengaturan']->nama;
            $data['meta_description'] = $data['pengaturan']->deskripsi;
        }

        $this->load->view('siswa/header',$data);
        $this->load->view('siswa/single',$data);
        $this->load->view('siswa/footer',$data);
    }
    public function download($id)
    {
        $a = $this->m_data->get($id)->row();
        
        
        
        //$image_file = K_PATH_IMAGES.'logo_example.jpg';
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintFooter(false);
        $pdf->setPrintHeader(false);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage('');
        $pdf->SetFont('', 'B', 20);
        $pdf->Write(0, $a->artikel_judul, '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('', '', 12);
        $pdf->Write(0,'', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0,'', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0,'Penulis : '. $a->pengguna_nama, '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0,'Kategori : '. $a->kategori_nama, '', 0, 'L', true, 0, false, false, 0);
 
        $data = 
        '<p align="justify">
            '.$a->artikel_konten.'
            </p>

        ';
        $pdf->writeHTML($data,'J');
        $pdf->Output($a->kategori_nama, 'I');
        

    }
    public function profile()
    {
        $id = $this->session->userdata('id');
        $data['pengaturan'] = $this->m_data->get_data('pengaturan')->row();
        $data['meta_keyword'] = $data['pengaturan']->nama;
        $data['meta_description'] = $data['pengaturan']->deskripsi;
        $data['a'] = $this->db->query("select * from pengguna join detail_siswa on pengguna.pengguna_id = detail_siswa.pengguna_id where pengguna.pengguna_id = '$id'")->row();
        $this->load->view('siswa/header',$data);
        $this->load->view('siswa/profile',$data);
        $this->load->view('siswa/footer',$data);
    }
    public function kategori($slug)
    {       

        // data pengaturan website
        $data['pengaturan'] = $this->m_data->get_data('pengaturan')->row();

        $jumlah_artikel = $this->db->query("SELECT * FROM artikel,pengguna,kategori WHERE artikel_status='publish' AND artikel_author=pengguna_id AND artikel_kategori=kategori_id AND kategori_slug='$slug'")->num_rows();
        
        $this->load->library('pagination');
        $config['base_url'] = base_url().'Siswa/kategori/'.$slug;
        $config['total_rows'] = $jumlah_artikel;
        $config['per_page'] = 2;

        $config['first_link']       = 'First';
        $config['last_link']        = 'Last';
        $config['next_link']        = 'Next';
        $config['prev_link']        = 'Prev';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';


        
        $from = 0;
        
        $this->pagination->initialize($config);

        $data['artikel'] = $this->db->query("SELECT * FROM artikel,pengguna,kategori WHERE artikel_status='publish' AND artikel_author=pengguna_id AND artikel_kategori=kategori_id AND kategori_slug='$slug' ORDER BY artikel_id DESC LIMIT $config[per_page] OFFSET $from")->result();

        // SEO META
        $data['meta_keyword'] = $data['pengaturan']->nama;
        $data['meta_description'] = $data['pengaturan']->deskripsi;

        $this->load->view('siswa/header',$data);
        $this->load->view('siswa/kategori',$data);
        $this->load->view('siswa/footer',$data);
    }
    public function update_profile()
    {
        if ($this->input->post('password') != '')
        {
            $pwd =  $this->input->post('password');
            $x = strlen($pwd);
            if ($x < 8) 
            {
                $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-danger alert-dismissible fade show' role='alert'>
           
               <span class='alert-inner--text'><strong>Gagal!</strong> Password minimal terdiri dari 8 karakter</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
                redirect('siswa/profile');
            }
            else
            {

            $update = $this->m_data->update_profile($this->input->post());
            $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success alert-dismissible fade show' role='alert'>
           
           <span class='alert-inner--text'><strong>Success!</strong> Update profile</span>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
            </div>");
            redirect('siswa/profile');
            }
        }
        else
        {
            $update = $this->m_data->update_nama($this->input->post());
            $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success alert-dismissible fade show' role='alert'>
           
           <span class='alert-inner--text'><strong>Success!</strong> Update profile</span>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
            </div>");
            redirect('siswa/profile');
        }
    }
    public function private_learning()
    {
        $data['kategori'] = $this->m_data->get_pelajaran();
        $data['pengajar'] = $this->m_data->get_pengajar();
        $id = $this->session->userdata('id');
        $data['datasiswa'] = $this->m_data->get_data_private($id);
        //$data['datapengajar'] = $this->m_data->get_data_private1($id);
        $data['pengaturan'] = $this->m_data->get_data('pengaturan')->row();
        $data['meta_keyword'] = $data['pengaturan']->nama;
        $data['meta_description'] = $data['pengaturan']->deskripsi;
        $this->load->view('siswa/header',$data);
        $this->load->view('siswa/private_learning',$data);
        $this->load->view('siswa/footer',$data);
    }
    public function pengajuan()
    {
        $waktu = $this->input->post('waktu');
        $tujuan = new DateTime($waktu);
        $sekarang =  date_create();
        $diff  = date_diff($sekarang, $tujuan);
        $batas = $diff->d;
        if ($sekarang > $tujuan) {
            $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-danger alert-dismissible fade show' role='alert'>
               
               <span class='alert-inner--text'><strong>Gagal!</strong> Tanggal pelaksanaan harus lebih besar dari hari ini</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
                redirect('siswa/private_learning');
        }
        else
        {

            if ($batas < 2) {
                $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-danger alert-dismissible fade show' role='alert'>
               
               <span class='alert-inner--text'><strong>Gagal!</strong> Anda harus mendaftar 2 hari sebelum hari pelaksanaan</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
                redirect('siswa/private_learning');
            }
            else
            {
                $this->m_data->pengajuan($this->input->post());
                $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success alert-dismissible fade show' role='alert'>
               
               <span class='alert-inner--text'><strong>Success!</strong> Permintaan anda telah terkirim, silahkan tunggu dalam waktu max 24 jam</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
                redirect('siswa/private_learning');

            }
        }
    }
        public function update_pengajuan()
        {
        $waktu = $this->input->post('waktu');
        $tujuan = new DateTime($waktu);
        $sekarang =  date_create();
        $diff  = date_diff($sekarang, $tujuan);
        $batas = $diff->d;
        if ($sekarang > $tujuan) {
            $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-danger alert-dismissible fade show' role='alert'>
               
               <span class='alert-inner--text'><strong>Gagal!</strong> Tanggal pelaksanaan harus lebih besar dari hari ini</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
                redirect('siswa/private_learning');
        }
        else
        {

            if ($batas < 2) {
                $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-danger alert-dismissible fade show' role='alert'>
               
               <span class='alert-inner--text'><strong>Gagal!</strong> Anda harus mendaftar 2 hari sebelum hari pelaksanaan</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
                redirect('siswa/private_learning');
            }
            else
            {
                
                $this->m_data->update_pengajuan($this->input->post());
                $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success alert-dismissible fade show' role='alert'>
               
               <span class='alert-inner--text'><strong>Success!</strong> Permintaan anda telah terkirim, silahkan tunggu dalam waktu max 24 jam</span>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                </div>");
                redirect('siswa/private_learning');

            }
        }

    }
    public function hapus_pengajuan()
    {
        $id = $this->input->post('id');
        $query = $this->db->query("delete from private_learning where id = '$id'");
        $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success alert-dismissible fade show' role='alert'>
           
           <span class='alert-inner--text'><strong>Success!</strong> Hapus pengajuan</span>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
        </div>");
        redirect('siswa/private_learning');
    }
    public function ujian()
    {
        $data['ujian'] = $this->db->query("select * from subkategori left join kategori on subkategori.kategori_id = kategori.kategori_id left join pengguna on subkategori.author_id = pengguna.pengguna_id where subkategori.status = 'Diterima'")->result();
        $data['pengaturan'] = $this->m_data->get_data('pengaturan')->row();
        $data['meta_keyword'] = $data['pengaturan']->nama;
        $data['meta_description'] = $data['pengaturan']->deskripsi;
        $this->load->view('siswa/header',$data);
        $this->load->view('siswa/ujian',$data);
        $this->load->view('siswa/footer',$data);
    }
    public function kerjakan($id)
    {
        $a = $this->db->query("select * from subkategori where subkategori_id = '$id'")->row_array();
        $tipe = $a['tipe_soal'];

        if ($tipe == 'Pilihan Ganda') {
           $data['soal'] = $this->db->query("select * from soal where subkategori_id = '$id'")->result();
            $data['jumlah'] = $this->db->query("select * from soal where subkategori_id = '$id'")->num_rows();
        }
        else
        {
            $data['soal'] = $this->db->query("select * from essay where subkategori_id = '$id'")->result();
            $data['jumlah'] = $this->db->query("select * from essay where subkategori_id = '$id'")->num_rows();
        }
        $data['tipe'] = $a['tipe_soal'];
        $data['pengaturan'] = $this->m_data->get_data('pengaturan')->row();
        $data['meta_keyword'] = $data['pengaturan']->nama;
        $data['meta_description'] = $data['pengaturan']->deskripsi;
        $this->load->view('siswa/header',$data);
        $this->load->view('siswa/kerjakan',$data);
        $this->load->view('siswa/footer',$data);
    }
    public function submit()
    {
        $sub = $this->input->post('sub');
        $pilihan = $this->input->post('pilihan');
        $id_soal = $this->input->post('id');
        $jumlah = $this->input->post('jumlah');
 
        $score = 0;
        $benar = 0;
        $salah = 0;
 
        for($i=0;$i<$jumlah;$i++){
                
            $cek = $this->db->query("select * from soal where soal_id = '$id_soal[$i]' AND kunci = '$pilihan[$i]' ")->num_rows();
 
            if($cek){
                $benar++;
            } else {
                $salah++;
            }
        }
            $score = $benar * 5;
            $this->db->query("insert into history values ('','$sub','$score')");
            $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success alert-dismissible fade show' role='alert'>
           <span class='alert-inner--text'><strong>Success!</strong>Anda berhasil mengerjakan soal. cek history nilai pada bagian history</span>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
            </div>");
            redirect('siswa/ujian');
    }
    public function histori($id)
    {
        $data['histori'] = $this->db->query("select * from history where subkategori_id = '$id'")->result();
        $data['subkategori'] = $this->db->query("select * from subkategori where subkategori_id = '$id'")->row_array();
        $data['jumlah'] = $this->db->query("select * from soal where subkategori_id = '$id'")->num_rows();
        $data['essay'] = $this->db->query("select * from essay where subkategori_id = '$id'")->num_rows();

        $data['pengaturan'] = $this->m_data->get_data('pengaturan')->row();
        $data['meta_keyword'] = $data['pengaturan']->nama;
        $data['meta_description'] = $data['pengaturan']->deskripsi;
        $this->load->view('siswa/header',$data);
        $this->load->view('siswa/histori',$data);
        $this->load->view('siswa/footer',$data);
    }
    public function submit_essay()
    {
        $a = $this->db->query("select max(event) as b from jawaban_essay")->row();
        $event = $a->b + 1;
        $jumlah = $this->input->post('jumlah');
        $soal_id = $this->input->post('id');
        $siswa_id = $this->session->userdata('id');
        $sub = $this->input->post('sub');
        $jawaban = $this->input->post('jawaban');
        for($i=0;$i<$jumlah;$i++)
        {
            $this->db->query("insert into jawaban_essay values ('','$event','$sub','$siswa_id','$soal_id[$i]','$jawaban[$i]','Belum Dikoreksi')"); 
        }
        $this->session->set_flashdata("msg", "<div id='myalert' class='alert alert-success alert-dismissible fade show' role='alert'>
           <span class='alert-inner--text'><strong>Success!</strong>Jawaban berhasil disimpan, silahkan tunggu untuk jawaban dikoreksi pengajar</span>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
            </div>");
        redirect('siswa/ujian');
    }
}