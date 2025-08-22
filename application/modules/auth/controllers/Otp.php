<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Otp extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth/User_model','user');
        $this->load->library(['session','form_validation','email']);
        $this->load->helper(['url','form']);
    }

    public function index()
    {
        $ctx = $this->session->userdata('otp_pending');
        if (!$ctx) return redirect('masuk');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('code', 'OTP', 'required|exact_length[6]|numeric');
            if ($this->form_validation->run()) {
                $otp = $this->user->getOtpById((int)$ctx['otp_id']);
                if (!$otp || $otp['user_id'] != $ctx['user_id']) {
                    $this->session->set_flashdata('message','<div class="alert alert-danger">Sesi OTP tidak valid.</div>');
                    return redirect('masuk');
                }
                if ($otp['used_at'] !== null || strtotime($otp['expires_at']) < time()) {
                    $this->session->set_flashdata('message','<div class="alert alert-danger">OTP kadaluarsa/terpakai.</div>');
                    return redirect('auth/otp');
                }
                if ($otp['attempts'] >= $otp['max_attempts']) {
                    $this->session->set_flashdata('message','<div class="alert alert-danger">Terlalu banyak percobaan. Silakan login ulang.</div>');
                    $this->session->unset_userdata('otp_pending');
                    return redirect('masuk');
                }

                $input = $this->input->post('code', true);
                $ok = password_verify($input, $otp['code_hash']);
                $this->user->bumpOtpAttempt($otp['id'], !$ok);

                if (!$ok) {
                    $this->session->set_flashdata('message','<div class="alert alert-danger">Kode OTP salah.</div>');
                    return redirect('auth/otp');
                }

                // tandai used & set session user final
                $this->user->markOtpUsed($otp['id']);
                $u = $this->user->getById((int)$ctx['user_id']);

                $this->session->unset_userdata('otp_pending');
                $this->session->set_userdata('user', [
                    'id'=>(int)$u['id'],'name'=>$u['name'],'email'=>$u['email'],
                    'role_id'=>(int)$u['role_id'],'role'=>$u['role'] ?? null
                ]);

                return redirect('dashboard');
            }
        }

        $this->load->view('auth/otp_verify');
    }

    public function resend()
    {
        $ctx = $this->session->userdata('otp_pending');
        if (!$ctx) return redirect('masuk');

        // (opsional) rate limit resend: implementasi sederhana — abaikan untuk scope awal

        $u = $this->user->getById((int)$ctx['user_id']);
        $code = str_pad((string)random_int(0,999999), 6, '0', STR_PAD_LEFT);
        $hash = password_hash($code, PASSWORD_BCRYPT);

        $otp_id = $this->user->createOtp([
            'user_id'    => (int)$u['id'],
            'code_hash'  => $hash,
            'expires_at' => date('Y-m-d H:i:s', time()+300),
            'ip'         => $this->input->ip_address(),
            'user_agent' => substr($this->input->user_agent(),0,255),
        ]);

        $this->session->set_userdata('otp_pending', ['user_id'=>(int)$u['id'], 'otp_id'=>$otp_id]);
        $this->_send_otp_email($u['email'], $u['name'], $code);

        $this->session->set_flashdata('message','<div class="alert alert-success">OTP baru telah dikirim.</div>');
        return redirect('auth/otp');
    }

    private function _send_otp_email($to,$name,$code)
    {
        $this->email->from('no-reply@warkopabah.local','Warkop Abah');
        $this->email->to($to);
        $this->email->subject('Kode OTP Login');
        $this->email->message("Halo $name,\n\nKode OTP Anda: $code\nBerlaku 5 menit.");
        $this->email->send();
    }
}
