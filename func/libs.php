<?php

	header('Content-Type:text/html;charset=utf-8');
	set_time_limit(0);

	
	$_CONFIG_ = array(
		'lol' => array(
			'name' => '英雄联盟',
			'douyu' => 'LOL',
			'panda' => 'lol'
		),
		'jdqs'=> array(
			'name' => '绝地求生',
			'douyu' => 'jdqs',
			'panda' => 'pubg'
		),
		'dnf'=> array(
			'name' => 'DNF',
			'douyu' => 'DNF',
			'panda' => 'dnf'
		),
		'zhuji'=> array(
			'name' => '主机',
			'douyu' => 'TVgame',
			'panda' => 'zhuji'
		),
		'hw'=> array(
			'name' => '户外',
			'douyu' => 'HW',
			'panda' => 'hwzb'
		),
		'show'=> array(
			'name' => '娱乐星秀',
			'douyu' => '',
			'panda' => 'yzdr'
		)
	);


	$PC_AGENT = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.3226.400 QQBrowser/9.6.11681.400";
	$MB_AGENT = "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.76 Mobile Safari/537.36";

	$_DEBUG_ = false;

	class runtime{ 

        var $StartTime = 0; 
        var $StopTime = 0; 
     
        function get_microtime() 
        { 
            list($usec, $sec) = explode(' ', microtime()); 
            return ((float)$usec + (float)$sec); 
        } 
     
        function start() 
        { 
            $this->StartTime = $this->get_microtime(); 
        } 
     
        function stop() 
        { 
            $this->StopTime = $this->get_microtime(); 
        } 
     
        function spent() 
        { 
            return round(($this->StopTime - $this->StartTime) * 1000, 1); 
        } 
	}

	function curl_get_contents($url,$isPc = true){

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	    $agentStr = $isPc ? 'PC_AGENT' : 'MB_AGENT';
	    curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS[$agentStr]);
	    
	    $buffer = curl_exec($ch);
	    if(curl_errno($ch)) { echo 'Curl error: ' . curl_error($ch).'<br>'; }
      	curl_close($ch);

	    $ret = $buffer === false || empty($buffer) ? "" : $buffer;

	    return $ret;
	}


?>