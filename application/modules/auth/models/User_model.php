<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'user';

    public function findByEmail(string $email)
    {
        $this->db->select('u.*, r.role');
        $this->db->from($this->table.' u');
        $this->db->join('user_role r', 'r.id = u.role_id', 'left');
        $this->db->where('u.email', $email);
        return $this->db->get()->row_array();
    }

    public function emailExists(string $email): bool
    {
        return (bool) $this->db->select('id')->from($this->table)
            ->where('email', $email)->limit(1)->get()->num_rows();
    }

    public function create(array $data): int
    {
        $this->db->insert($this->table, $data);
        return (int) $this->db->insert_id();
    }
    public function getById(int $id)
    {
        $this->db->select('u.*, r.role');
        $this->db->from('user u');
        $this->db->join('user_role r','r.id=u.role_id','left');
        $this->db->where('u.id',$id);
        return $this->db->get()->row_array();
    }

    public function createOtp(array $data): int
    {
        $this->db->insert('user_otp_codes', $data);
        return (int)$this->db->insert_id();
    }

    public function getOtpById(int $id)
    {
        return $this->db->get_where('user_otp_codes', ['id'=>$id])->row_array();
    }

    public function bumpOtpAttempt(int $id, bool $failed): void
    {
        if ($failed) {
            $this->db->set('attempts', 'attempts+1', FALSE);
        } else {
            $this->db->set('attempts', 0);
        }
        $this->db->where('id',$id)->update('user_otp_codes');
    }


    public function markOtpUsed(int $id): void
    {
        $this->db->where('id',$id)->update('user_otp_codes', ['used_at'=>date('Y-m-d H:i:s')]);
    }

}
