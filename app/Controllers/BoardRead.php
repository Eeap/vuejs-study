<?php

namespace App\Controllers;
class BoardRead extends BaseController
{
    // public function __construct(){
    //     parent::__construct();
    //     $this->load->database();
    // }
    public function index()
    {
        $db = \Config\Database::connect("default");
	$result = $db->query('select id,author,content,updatetime,createtime from boardTable')->getResultArray();
	$result_json = json_encode($result);
	#var_dump($result_json);
        return $result_json;
    }
}