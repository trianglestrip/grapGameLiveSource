<?php

//require("libs.php");

class panda_pc{

	var $collect = array();

	var $itemCount = 0;

	function grab_single_page($type,$pageId){

		$url ='http://www.panda.tv/ajax_sort?pageno='.$pageId.'&pagenum=120&classification='.$type;
		if($GLOBALS['_DEBUG_'])echo 'start read '.$type.' page:'.$pageId .'  .....<br>';
		$html = curl_get_contents($url);

		$json = json_decode($html,true);

		$dataItems = $json['data']['items'];

		//var_dump();
  		$this->itemCount = 	intval($json['data']['total']);
  		$this->parse_html($dataItems);

	}
	
	function parse_html($html){

		foreach ($html as $item) {
			

			$this->collect[] = array(
				'href' => 'https://www.panda.tv/'.$item['id'] , 
				'title' => $item['name'] , 
				'image' =>  $item['pictures']['img'] , 
				'name' => $item['userinfo']['nickName'] , 
				'views' => intval($item['person_num']),
				'tvName' => '熊猫'
			);

		}

	}

	
	function get_all_pages($type){


		if($GLOBALS['_DEBUG_']){
			$runtime= new runtime;
	 		$runtime->start();
	 	}
	 	$this->grab_single_page($type,1);//计算itemCount
		$page = 2;
		$count = round($this->itemCount/120+0.5);
		if($count < 1){echo 'get panda 【'.$type."】null! ";}
		
		while ($page <= $count) {
			# code...
			$this->grab_single_page($type,$page);
			$page += 1;

		}

		// $sort = array();
	 //    foreach($this->collect as $arr){
	 //      $sort[] = $arr['views'];
	 //    }
	 //    array_multisort($sort, SORT_DESC,$this->collect );	
    	
		if($GLOBALS['_DEBUG_']){
			$runtime->stop();
	  		echo 'parse [panda] 【'.$type."】spend: ".$runtime->spent()." ms<br>";
	  	}

	  	 return $this->collect;
	}

	
}
	// $_pc = new panda_pc();

	// $_pc->get_all_pages('lol');
 ?>
