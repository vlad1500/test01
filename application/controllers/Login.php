<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function index()	{
	    $username = $this->input->post('username');
        $password = md5($this->input->post('password'));

        $query = $this->db->query('SELECT id, username, password FROM users');
        $newdata = [];
        $found = false;
        foreach ($query->result_array() as $row){
            if($username == $row['username'] && $password == $row['password']){
                $newdata = array(
                    'id' => $row['id'],
                    'username' => $row['username'],
                    'password' => $row['password'],
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($newdata);
                $found = true;
                break;
            }
        }
        echo $found;
	}
    public function logout(){
        $thisArr = ['id','name','password','logged_in'];
        $this->session->unset_userdata($thisArr);
        echo true;
    }
}
