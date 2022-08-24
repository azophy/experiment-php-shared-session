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
		if (!$this->session->random_codeigniter_value)
			$this->session->random_codeigniter_value = random_string('alnum', 16);
        $ci_session_id = $this->session->session_id;

        // retrieve data from laravel
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://web_laravel:8000/',
        ]);
        try {
            $response = $client->request('GET', '/', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Cookie' => "ci_session=$ci_session_id",
                ],
            ]);
            $result_string = $response->getBody()->getContents();
            $laravel_result = json_decode($result_string, true);
        } catch (\Exception $e) {
            $laravel_result = [
                'encounter error' => $e->getMessage(),
            ];
        }

        // prepare output
        $output = json_encode([
            'codeigniter_data' => [
                'session_id' => $ci_session_id,
                'session_data' => $this->session,
                'dat_time' => date('Y-m-d H:i:s'),
                'reset_url' => site_url('sesi/reset'),
            ],
            'laravel_data' => $laravel_result,
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
