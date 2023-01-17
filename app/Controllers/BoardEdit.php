<?php
namespace App\Controllers;
class BoardEdit extends BaseController
{
    // public function __construct(){
    //     parent::__construct();
    //     $this->load->database();
    // }
    public function index()
    {
	    $id=$_GET['id'];
	    $data['id']=$id;
        return view('boardPage',$data);
    }
}