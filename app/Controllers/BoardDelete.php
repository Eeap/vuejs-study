<?php
namespace App\Controllers;
class BoardDelete extends BaseController
{

    public function index()
    {
	    parse_str(file_get_contents('php://input'),$post_vars);
	    $db = \Config\Database::connect("default");
	    $data=array_keys($post_vars);
        $query = 'delete from boardTable where id = '.$data[0].';';
        $result = $db->query($query);
        return "success";
    }
}