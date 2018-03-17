<?php
 	
	require("libs.php");
		

		function get_douyu_pageCounts($html){

			$info = '/data-pagecount=\"(\d{1,3})\">/is';
			preg_match($info,$html,$pageCountStr);
			$pageCount = intval($pageCountStr[1]);
			//var_dump($pageCount);
			return $pageCount;
		}

		function get_douyu_singlePage($type,$pageId){

			$preUrl = 'https://m.douyu.com/roomlists?';
			$url = $preUrl.'page='.$pageId.'&type='.$type;
			echo 'start read '.$type.' page:'.$pageId .'  .....<br>';
			$html = curl_get_contents($url,false);

			return get_douyu_pageCounts($html);
		}

		function get_douyu_mobiles($type){

			$runtime= new runtime;
	 		$runtime->start();

			$page = 1;
		
			/*
			<a href="/52" class="live" data-rid="52" data-tid="1" data-fish="1"><img src="https://rpic.douyucdn.cn/acrpic/170826/52_2300.jpg" class="live-feature"><div class="live-title">钻一归来！排位我只想秀</div><div class="live-info"><span class="dy-name">LoveAcFun包子</span><span class="popularity">7.5万</span></div></a>
	<div class="m-row" id="js-list-area" data-url="/roomlists" data-type="LOL" data-page="203" data-pagecount="204">*/
			//$info = '/data-url=\"\/roomlists\" data-type=\"'.$type.' data-page=\"'.$page.'\" 
			//
			
			$count = get_douyu_singlePage($type,$page);
			echo 'total pages: '.$count .' <br>';
			while ($page <= $count) {
				# code...
				$page += 1;
				$count = get_douyu_singlePage($type,$page);
			}
	    	
	

			$runtime->stop();
	  		echo 'parse [douyu] 【'.$type."】spend: ".$runtime->spent()." ms<br>";
		}

		//
		function get_curl_multi_one($mh,$url){

			$ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		    // curl_setopt($ch, CURLOPT_USERAGENT, "Python-urllib/3.5");
		    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.76 Mobile Safari/537.36");
		    curl_multi_add_handle($mh,$ch);

		}

		function get_curl_multis($type){

			$runtime= new runtime;
	 		$runtime->start();

			$preUrl = 'https://m.douyu.com/roomlists?';
			$pageId =1;

			$mh = curl_multi_init();
			$count = get_douyu_singlePage($type,$pageId);
			echo 'total pages: '.$count .' <br>';
			while ( $pageId<= get_douyu_singlePage($type,$pageId)) {
				# code...
				$pageId += 1;
				$url = $preUrl.'page='.$pageId.'&type='.$type;
				get_curl_multi_one($mh,$url);
			}
			//修改后的模型
			do {
			    while (($execrun = curl_multi_exec($mh, $running)) == CURLM_CALL_MULTI_PERFORM) ;

			    //CURLM_OK只是意味着数据传送完毕或者没有数据 可传送
			    if ($execrun != CURLM_OK)
			        break;

			    //curl_multi_info_read 查询批处理句柄是否单独的传输线程中有消息或信息返回。
			    while ($done = curl_multi_info_read($mh)) {

			        $info = curl_getinfo($done['handle']);
			        $tmp_result = curl_multi_getcontent($done['handle']);
			        $error = curl_error($done['handle']);
			        curl_multi_remove_handle($mh, $done['handle']);
			        //var_dump($tmp_result);
			        //可以观察到，只要有url请求成功，就会把数据返回生成文件。
			        //file_put_contents('curl_multi2.log',$tmp_result."\r\n\r\n\r\n\r\n",FILE_APPEND);
			    }
			    
			    if ($running)
			        curl_multi_select($mh);

			} while ($running);

			$runtime->stop();
	  		echo 'parse [douyu] 【'.$type."】spend: ".$runtime->spent()." ms<br>";
		}
		//get_douyu_mobiles('LOL');
		
		get_curl_multis('LOL');

 ?>

 