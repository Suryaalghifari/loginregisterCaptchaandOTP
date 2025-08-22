<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url','html']);

        // wajib login
        $user = $this->session->userdata('user');
        if (!$user) {
            redirect('masuk'); 
        }

       
        $this->output
            ->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0')
            ->set_header('Cache-Control: post-check=0, pre-check=0', false)
            ->set_header('Pragma: no-cache');
    }

    public function index()
    {
        $user = $this->session->userdata('user'); 
        $data = [
            'id'      => (int)$user['id'],
            'name'    => $user['name'],
            'email'   => $user['email'],
            'role_id' => (int)$user['role_id'],
            'role'    => isset($user['role']) ? $user['role'] : null,
        ];
        $this->load->view('dashboard/index', $data);
    }
}
