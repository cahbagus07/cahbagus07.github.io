<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index() 
	{		
		$data['judul'] = 'Dashboard';
		$this->load->view('frontend/v_header',$data);
		$this->load->view('frontend/v_homepage');
		$this->load->view('frontend/v_footer');
	}
	public function tentang() 
	{		
		$data['judul'] = 'About Me';
		$this->load->view('frontend/v_header',$data);
		$this->load->view('frontend/v_tentang');
		$this->load->view('frontend/v_footer');
	}
	public function service() 
	{		
		$data['judul'] = 'Service';
		$this->load->view('frontend/v_header',$data);
		$this->load->view('frontend/v_service');
		$this->load->view('frontend/v_footer');
	}
	public function corona() 
	{		
		$url = "https://coronavirus-19-api.herokuapp.com/countries";
		$get_url = file_get_contents($url);
		$data = json_decode($get_url);

		$data_array = array(
		'list' => $data
		);
		$data['judul'] = 'Update Corona';
		$this->load->view('frontend/v_header',$data);
		$this->load->view('frontend/v_corona',$data_array);
		$this->load->view('frontend/v_footer');
	}

	
}
