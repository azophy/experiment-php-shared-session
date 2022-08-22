<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sesi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('string');
		$this->load->helper('url');
	}

	public function index()
	{
		if (!$this->session->random_value)
			$this->session->random_value = random_string('alnum', 16);

		echo "Random value in session: " . $this->session->random_value;
		echo "<br/>Date Time: " . date('Y-m-d H:i:s');
		echo "<br/><a href='" . site_url('sesi/reset') . "' target='_blank'>Reset value</a>";
	}

	public function reset()
	{
			$this->session->random_value = random_string('alnum', 16);
			echo "New random value: " . $this->session->random_value;
	}
}
