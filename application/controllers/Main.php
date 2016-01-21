<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	public function index()	{
	    if($this->session->has_userdata('username') && $this->session->has_userdata('id'))
    		$this->load->view('main');
        else
            $this->load->view('login');
	}
    public function getfriends() {
        $parent_id = $this->input->get('id');
        $query = $this->db->query("SELECT * FROM friends WHERE parent_id='$parent_id'");
        echo json_encode($query->result_array());
    }
    public function gettweets() {
        require_once('./application/libraries/TwitterAPIExchange.php');

        $user = $this->input->get('user');
        $settings = array(
            'oauth_access_token' => "1535420712-vPAuTtFp6rigutJCG5q34hQSRM2vJ2RtWWoQYYF",
            'oauth_access_token_secret' => "surt9ElcgSaAcFYlAGEfHgTSpooQPXp6Uxm6Pgxq5ZGkr",
            'consumer_key' => "Ux25WfrtoLn46ZTUUU5uKKkhO",
            'consumer_secret' => "ui1e98fQZ625BrQAKWzPTYsWgQTtZ7VXUcpJlV8DWe5quGWRmx"
        );
        $requestMethod = 'GET';
        $url1="https://api.twitter.com/1.1/statuses/user_timeline.json";

        $sc_name = $user;
        $count ='5';

        $getfield = '?screen_name='.$sc_name.'&exclude_replies=true&include_rts=true&contributor_details=false';

        $twitter = new TwitterAPIExchange($settings);
        $tweets  = $twitter->setGetfield($getfield)->buildOauth($url1, $requestMethod)->performRequest();

        $tweetarray = json_decode($tweets);
        echo json_encode($tweetarray);
    }
    public function addfriend() {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $twitter = $this->input->post('twitter');
        $data = array(
            'name' => $name,
            'email' => $email,
            'twitter' => $twitter,
            'parent_id' => $this->session->has_userdata('id')
        );
        if($this->session->has_userdata('id')){
            $this->db->insert('friends', $data);
            echo true;
        }
        else echo false;
    }
    public function editfriend() {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $twitter = $this->input->post('twitter');
        $data = array(
            'name' => $name,
            'email' => $email,
            'twitter' => $twitter
        );
        if($this->session->has_userdata('id') && $id){
            $this->db->set($data);
            $this->db->where('id', $id);
            $this->db->update('friends');
            echo true;
        }
        else echo false;
    }
    public function delfriend() {
        $id = $this->input->post('id');
        if($this->session->has_userdata('id') && $id){
            $this->db->where('id', $id);
            $this->db->delete('friends');
            echo true;
        }
        else echo false;
    }
}
