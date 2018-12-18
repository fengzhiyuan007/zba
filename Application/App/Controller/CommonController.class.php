<?php

namespace App\Controller;

use Think\Controller;

class CommonController extends Controller {
	public function two_sort($list,$field,$direction){
		$sort = array(    
	            'direction' => $direction, //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序    
	            'field'     => $field,       //排序字段    
	    );    
	    $arrSort = array();    
	    foreach($list AS $uniqid => $row){    
	        foreach($row AS $key=>$value){    
	            $arrSort[$key][$uniqid] = $value;    
	        }    
	    }    
	    if($sort['direction']){    
	        array_multisort($arrSort[$sort['field']], constant($sort['direction']), $list);    
	    } 
	    return $list;   

	}
}