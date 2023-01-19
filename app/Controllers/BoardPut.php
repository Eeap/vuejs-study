<?php
namespace App\Controllers;
class BoardPut extends BaseController
{

    public function index()
    {
	    date_default_timezone_set('Asia/Seoul');
        parse_str(file_get_contents('php://input'),$post_vars);
	    $db = \Config\Database::connect("default");
	    $id=$_GET['id'];
        foreach($post_vars as $key=>$value)
        {
            $obj = json_decode($key,true);
            $data=array_values($obj);
            $author=(string)$data[0];
            $content=(string)$data[1];
            $query = 'update boardTable set author="'.$author.'", content="'.$content.'",updatetime="'.date("Y-m-d H:i:s").'" where id='.$id.';';
            $q=str_replace('_',' ',$query);
            $result=$db->query($q);
            return $result;
        }
    }
}