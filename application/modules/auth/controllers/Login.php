<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth/User_model','user');
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form','captcha']); 
    }

  
    public function index()
    {
        // jika sudah login, lempar ke dashboard
        if ($this->session->userdata('user')) {
            return redirect('dashboard');
        }

        if ($this->input->method() === 'post') {
            // --- [1] Cek Captcha dulu
            $inputCap = trim((string) $this->input->post('captcha'));
            $sessCap  = (string) $this->session->userdata('captcha_word');
            if ($inputCap === '' || $sessCap === '' || $inputCap !== $sessCap) {
                $this->session->set_flashdata('message','<div class="alert alert-danger">Captcha salah.</div>');
                // optional: regenerate captcha next view
                return redirect('masuk');
            }
            // gunakan sekali saja
            $this->session->unset_userdata('captcha_word');

            // --- [2] Validasi kredensial
            $this->form_validation->set_rules('email','Email','trim|required|valid_email');
            $this->form_validation->set_rules('password','Password','required');

            if ($this->form_validation->run()) {
                $email = strtolower($this->input->post('email', true));
                $pass  = $this->input->post('password', true);

                $u = $this->user->findByEmail($email);
                if (!$u) {
                    $this->session->set_flashdata('message','<div class="alert alert-danger">Email tidak terdaftar.</div>');
                    return redirect('masuk');
                }
                if ((int)$u['is_active'] !== 1) {
                    $this->session->set_flashdata('message','<div class="alert alert-warning">Akun belum aktif.</div>');
                    return redirect('masuk');
                }
                if (!password_verify($pass, $u['password'])) {
                    $this->session->set_flashdata('message','<div class="alert alert-danger">Password salah.</div>');
                    return redirect('masuk');
                }

                // setelah password_verify($pass, $u['password']) === true:

                // 1) generate OTP 6 digit & simpan hash (jangan simpan plaintext)
                $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $hash = password_hash($code, PASSWORD_BCRYPT);

                $otp_id = $this->user->createOtp([
                'user_id'    => (int)$u['id'],
                'code_hash'  => $hash,
                'expires_at' => date('Y-m-d H:i:s', time()+300), // berlaku 5 menit
                'ip'         => $this->input->ip_address(),
                'user_agent' => substr($this->input->user_agent(), 0, 255),
                ]);

                // 2) kirim email OTP
                $this->_send_otp_email($u['email'], $u['name'], $code);

                // 3) simpan state OTP sementara, JANGAN set session 'user' dulu
                $this->session->set_userdata('otp_pending', [
                'user_id' => (int)$u['id'],
                'otp_id'  => $otp_id
                ]);

                // 4) arahkan ke halaman verifikasi OTP
                return redirect('auth/otp');

                $this->session->set_userdata('user', [
                    'id'      => (int)$u['id'],
                    'name'    => $u['name'],
                    'email'   => $u['email'],
                    'role_id' => (int)$u['role_id'],
                    'role'    => $u['role'] ?? null,
                ]);

                return redirect('dashboard');
            }
            
        }

        
        $vals = [
            'img_path'   => FCPATH.'captcha/',         
            'img_url'    => base_url('captcha/'),       
            'img_width'  => 160,
            'img_height' => 50,
            'expiration' => 300,                       
            'word_length'=> 6,
            'expiration' => 60,
            'pool'       => '0123456789',              
           
        ];
        $cap = create_captcha($vals);

        // simpan kata ke session buat cek POST berikutnya
        $this->session->set_userdata('captcha_word', (string)$cap['word']);

        // kirim <img> ke view
        $data['captcha_img'] = $cap['image'];

        $this->load->view('auth/login', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata('user');
        $this->session->sess_regenerate(true);
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0')
                     ->set_header('Cache-Control: post-check=0, pre-check=0', false)
                     ->set_header('Pragma: no-cache');
        redirect('masuk');
    }
        private function _send_otp_email($to, $name, $code)
    {
        $this->email->from('no-reply@warkopabah.local', 'Warkop Abah');
        $this->email->to($to);
        $this->email->subject('Kode OTP Login');
        $this->email->message("Halo $name,\n\nKode OTP Anda: $code\nBerlaku 5 menit.\n\nJika bukan Anda, abaikan email ini.");
        $this->email->send();
    }

}
