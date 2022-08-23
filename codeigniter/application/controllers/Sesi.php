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
        $output = json_encode([
            'random_value' => $this->session->random_value,
		    'dat_time' => date('Y-m-d H:i:s'),
		    'session_id' => $this->session->session_id,
		    'reset_url' => site_url('sesi/reset'),
            'session_data' => $this->session,
        ]);

        return $this->output
            ->set_content_type('application/json')
            ->set_output($output);
	}

	public function reset()
	{
			$this->session->random_value = random_string('alnum', 16);
			echo "New random value: " . $this->session->random_value;
	}
}
