<?php 

// WWW.MALASNGODING.COM === Author : Diki Alfarabi Hadi
// Model yang terstruktur. agar bisa digunakan berulang kali untuk membuat CRUD. 
// Sehingga proses pembuatan CRUD menjadi lebih cepat dan efisien.

class M_data extends CI_Model{
	
	function cek_login($table,$where){
		return $this->db->get_where($table,$where);
	}
	
	// FUNGSI CRUD
	// fungsi untuk mengambil data dari database
	function get_data($table){
		return $this->db->get($table);
	}

	// fungsi untuk menginput data ke database
	function insert_data($data,$table){
		$this->db->insert($table,$data);
	}

	// fungsi untuk mengedit data
	function edit_data($where,$table){
		return $this->db->get_where($table,$where);
	}

	// fungsi untuk mengupdate atau mengubah data di database
	function update_data($where,$data,$table){
		$this->db->where($where);
		$this->db->update($table,$data);
	}

	// fungsi untuk menghapus data dari database
	function delete_data($where,$table){
		$this->db->delete($table,$where);
	}
	// AKHIR FUNGSI CRUD
	function get_data_artikel($limit, $start)
	{
		$query =  $this->db->query("SELECT * FROM artikel,pengguna,kategori WHERE artikel_status='publish' AND artikel_author=pengguna_id AND artikel_kategori=kategori_id ORDER BY artikel_id DESC LIMIT $limit OFFSET $start")->result();
        return $query;
	}
	function get($id)
	{
		$data = array
		(
			'artikel_id' => $id
		);
		return $this->db->get_where('list_artikel',$data);
	}
	function update_profile($data)
	{
		$where = array(
			'pengguna_id' => $data['id'],
		);
		$pwd = $data['password'];
		$password = md5($pwd);
		$dataku = array (
			'pengguna_nama' => $data['nama'],
			'pengguna_password' => $password,
		);
		$this->db->where($where);
		$this->db->update('pengguna',$dataku);
		$datax = array (
			'nama' => $data['nama']
		);
		$this->session->set_userdata($datax);
	}
	function update_nama($data)
	{
		$where = array(
			'pengguna_id' => $data['id'],
		);
		$dataku = array (
			'pengguna_nama' => $data['nama'],
		);
		$this->db->where($where);
		$this->db->update('pengguna',$dataku);
		$datax = array (
			'nama' => $data['nama']
		);
		$this->session->set_userdata($datax);
	}
	public function get_data_private($id)
	{
		$query = $this->db->query("select * from private_learning join pengguna on private_learning.siswa_id = pengguna.pengguna_id join kategori on private_learning.kategori_id = kategori.kategori_id  where siswa_id = '$id' ");
		return $query->result();
	}
	public function get_data_private1($id)
	{
		$query = $this->db->query("select * from private_learning join pengguna on private_learning.pengajar_id = pengguna.pengguna_id join kategori on private_learning.kategori_id = kategori.kategori_id where siswa_id = '$id' && pengguna_level = 'penulis' ");
		return $query->result();
	}
	public function get_pengajar()
	{
		$query = $this->db->query("select * from pengguna where pengguna_level = 'penulis'");
		return $query->result();
	}
	public function get_pelajaran()
	{
		$query = $this->db->get('kategori');
		return $query->result();
	}
	public function pengajuan($data)
	{
		$dataku = array
		(
			'siswa_id' => $data['id'],
			'email' => $data['email'],
			'no_telf' => $data['no_telf'],
			'alamat' => $data['alamat'],
			'pengajar_id' => $data['pengajar'],
			'kategori_id' => $data['pelajaran'],
			'durasi' => $data['durasi'],
			'jam' => $data['time'],
			'waktu' => $data['waktu'],
			'status' => 'Menunggu Konfirmasi',
		);
		$this->db->insert('private_learning', $dataku);
	}
	public function update_pengajuan($data)
	{
		$id = $data['id'];
		$dataku = array 
		(
			'siswa_id' => $data['siswa_id'],
			'email' => $data['email'],
			'no_telf' => $data['no_telf'],
			'alamat' => $data['alamat'],
			'pengajar_id' => $data['pengajar'],
			'kategori_id' => $data['pelajaran'],
			'durasi' => $data['durasi'],
			'jam' => $data['time'],
			'waktu' => $data['waktu'],
			'status' => 'Menunggu Konfirmasi',
		);
		$this->db->where('id',$id);
		$this->db->update('private_learning',$dataku);
	}
	public function get_private_penulis($id)
	{
		$query = $this->db->query("select * from private_learning join pengguna on private_learning.siswa_id = pengguna.pengguna_id join kategori on private_learning.kategori_id = kategori.kategori_id where pengajar_id = '$id' ");
		return $query->result();
	}
	public function add_sub($data)
	{
		$dataku = array
		(
			'kategori_id' => $data['kategori_id'],
			'author_id' => $this->session->userdata('id'),
			'subkategori_nama' => $data['nama'],
			'tipe_soal' => $data['tipe'],
			'status' => 'Menunggu Konfirmasi',
		);
		$this->db->insert('subkategori',$dataku);
	}
	public function add_soal($data)
	{
		$dataku = array 
		(
			'subkategori_id' => $data['subkategori_id'],
			'soal' => $data['soal'],
			'pilihan_a' => $data['a'],
			'pilihan_b' => $data['b'],
			'pilihan_c' => $data['c'],
			'pilihan_d' => $data['d'],
			'kunci' => $data['kunci'],
		);
		$this->db->insert('soal',$dataku);
	}
	public function add_soalessay($data)
	{
		$dataku = array 
		(
			'subkategori_id' => $data['subkategori_id'],
			'soal' => $data['soal'],
		);
		$this->db->insert('essay',$dataku);
	}
	public function insert_siswa($data)
	{
		$pwd = $data['password'];
		$password = md5($pwd);
		$dataku = array 
		(
			'pengguna_nama' => $data['nama'],
			'pengguna_email' => $data['email'],
			'pengguna_username' => $data['username'],
			'pengguna_password' =>$password,
			'pengguna_level' => 'siswa',
			'pengguna_status' => '1kka',

		);
		$this->db->insert('pengguna',$dataku);
	}
	public function insert_pengajar($data)
	{
		$pwd = $data['password'];
		$password = md5($pwd);
		$dataku = array 
		(
			'pengguna_nama' => $data['nama'],
			'pengguna_email' => $data['email'],
			'pengguna_username' => $data['username'],
			'pengguna_password' =>$password,
			'pengguna_level' => 'penulis',
			'pengguna_status' => '0',

		);
		$this->db->insert('pengguna',$dataku);
	}
	
}

?>