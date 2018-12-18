<?php
/**
 *
 +--------------------------------------------------
 * 切割字符
 +--------------------------------------------------
 * @param $str	字符
 * @param $n	需要留下的字数
 */
function cut($str, $n){
	$length = mb_strlen($str,'UTF8');
	if($n < $length){
		return mb_substr($str, 0, $n, 'UTF-8').'...';
	}else{
		return mb_substr($str, 0, $n, 'UTF-8');
	}
}
/**
 *
 +--------------------------------------------------
 * 二维数组根据某个字段排序
 +--------------------------------------------------
 * @param $direction	排序顺序标志 SORT_DESC 降序；SORT_ASC  升序；
 * @param $field	排序字段
 * @param $list	  二维数组
 */
function two_sort($list,$field,$direction){
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
?>