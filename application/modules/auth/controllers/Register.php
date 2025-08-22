<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth/User_model', 'user');
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form']);
    }

    /** GET: form register | POST: proses register */
    public function index()
    {
        // sudah login? gak perlu daftar
        if ($this->session->userdata('user')) {
            return redirect('dashboard');
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('name', 'Full name', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('email','Email','required|trim|valid_email|is_unique[user.email]', ['is_unique' => 'Email ini sudah terdaftar!']);
            
            $this->form_validation->set_rules('password1', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('password2', 'Repeat Password', 'required|matches[password1]');

            if ($this->form_validation->run()) {
                $name  = $this->input->post('name',  true);
                $email = strtolower($this->input->post('email', true));
                $pass  = $this->input->post('password1', true);

                $data = [
                    'name'         => $name,
                    'email'        => $email,
                    'image'        => 'default.jpg',
                    'password'     => password_hash($pass, PASSWORD_BCRYPT),
                    'role_id'      => 2,                // default user
                    'is_active'    => 1,                // (Nanti: set 0 jika butuh verifikasi email)
                    'date_created' => date('Y-m-d H:i:s'),
                ];
                $this->user->create($data);

                $this->session->set_flashdata('message', '<div class="alert alert-success">Akun berhasil dibuat. Silakan login.</div>');
                return redirect('masuk');
            }
        }

        
        $this->load->view('auth/register');
    }

    /** callback untuk validasi unik email */
    public function email_unique($email)
    {
        if ($this->user->emailExists(strtolower($email))) {
            $this->form_validation->set_message('email_unique', 'Email sudah terdaftar.');
            return false;
        }
        return true;
    }
 



}
