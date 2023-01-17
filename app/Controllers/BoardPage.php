<?php
namespace App\Controllers;
class BoardPage extends BaseController
{

    public function index()
    {
	$db = \Config\Database::connect("default");
        $result = $db->query('select id,author,content,updatetime,createtime from boardTable where id='.$_GET['id'].';')->getResultArray();
        $result_json = json_encode($result);
        #var_dump($result_json);
        return $result_json;
    }
}