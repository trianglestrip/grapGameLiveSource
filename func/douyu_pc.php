<?php


class douyu_pc{

	var $collect = array();



	function grab_single_page($type,$pageId){

		$preUrl = 'https://www.douyu.com/directory/game/';
		$url = $preUrl.$type.'?page='.$pageId.'&isAjax=1';
		if($GLOBALS['_DEBUG_'])echo 'start read '.$type.' page:'.$pageId .'  .....<br>';
		$html = curl_get_contents($url);

  		//return $html;
  		$this->parse_html($html);

	}
	
	function parse_html($html){


		$li = '/<li class([\s\S]*)<\/li>/Us';// 非贪婪模式 匹配每一个
		//preg_match_all($li,$html,$lists);
		preg_match_all($li,$html,$lists);
		// var_dump($lists[1]);
		// var_dump($lists[1]);([\s\S]*)\"(\d{1,8})\"

		// $href = 'data-rid=([\s\S]*)\"(\d{1,2})\"data-tid'; 
		$href = 'href=\"\/(.*)\"'; 
		$title = ' title=\"(.*)\"';
		$image = 'data-original=\"(.*)\"';
		$name = '<span class=\"dy-name ellipsis fl\">(.*)<\/span>';
		$views = '<span class=\"dy-num fr\"  >(.*)<\/span>';
		$pa = '/'.$href.$title.'([\s\S]*)'.$image.'([\s\S]*)'.$name.'([\s\S]*)'.$views.'/Us';
		//$pa = '/'.$href.$title.'([\s\S]*)'.$image.'([\s\S]*)'.$name.'([\s\S]*)'.$views.'/Us';
		//$pa = '/href=\"\/(.*)\" title=\"(.*)\"([\s\S]*)<img data-original=\"(.*)\"([\s\S]*)/Us';
		foreach ($lists[1] as $item) {
			# code...
			preg_match_all($pa,$item,$Str);

			// var_dump($Str[8][0]);
			$viewCount = $Str[8][0];
			if(strpos($viewCount,"万")) {$viewCount = intval(floatval($viewCount)*10000.0);}
			

			$this->collect[] = array(
				'href' => 'https://www.douyu.com/'.$Str[1][0] , 
				'title' => $Str[2][0] , 
				'image' => $Str[4][0] , 
				'name' => $Str[6][0] , 
				'views' => $viewCount,
				'tvName' => '斗鱼'
			);


		}

		// var_dump($this->collect);


	}

	function grab_pageCount($type){

		$url = 'https://www.douyu.com/directory/game/'.$type;
		
		//if($GLOBALS['_DEBUG_'])echo 'start read '.$type.' page:'.$pageId .'  .....<br>';
		$html = curl_get_contents($url);
		//data-href=\"\/directory\/game\/\'.$type.'
		// 非贪婪模式 匹配每一个
		 
		//$li = '/data-href=\"\/directory\/game\/'.$type.'([\s\S]*)data-pagecount=\"(\d{1,2})\"/Us';
		$li = '/data-pagecount=\"(\d{1,2})\"([\s\S]*)data-live-list-type=\"all\"/Us';//
		preg_match($li,$html,$pageCount);
		// var_dump($pageCount);
		//var_dump(intval($pageCount[1]));

  		return intval($pageCount[1]);
  		//$this->parse_html($html);
	}

	function get_all_pages($type){


		if($GLOBALS['_DEBUG_']){
			$runtime= new runtime;
	 		$runtime->start();
	 	}

		$page = 1;
		$count = $this->grab_pageCount($type);
		if($count < 1){echo 'get douyu 【'.$type."】null! ";}

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
	  		echo 'parse [douyu] 【'.$type."】spend: ".$runtime->spent()." ms<br>";
	  	}

	  	return $this->collect;
	}

	
}
	// $_pc = new douyu_pc;
	// $_pc->get_all_pages('LOL');

	// $itemCount = count($_pc->collect);
	
	// for($i = 0;$i < $itemCount;$i++){

	// 	$item = $_pc->collect[$i];
	// 	$str = $i.'->';
	// 	$str = $str.$item['href'].'--';
	// 	$str = $str.$item['title'].'--';
	// 	$str = $str.$item['image'].'--';
	// 	$str = $str.$item['name'].'--';
	// 	$str = $str.$item['views'].'<br>';
	// 	echo $str;
	// }

 ?>
