<?php
 require("func/libs.php");
 require("func/douyu_pc.php");
 require("func/panda_pc.php");

    //合并不同平台 同一个游戏
      
      $obj = array(

          'douyu' => new douyu_pc(),
          'panda' => new panda_pc()

        );
    

      $type = isset($_GET['id']) ? $_GET['id'] : 'lol' ;

      $page = isset($_GET['page']) ? intval($_GET['page']) : 1 ;

      $stack =  array();
      $start = 0;
      foreach ($obj as $key => $object) {
        

        $className = $GLOBALS['_CONFIG_'][$type][$key];
        if($className == ''){continue;}
        $item_array = $object->get_all_pages( $className);
      
        $len =count($item_array);
        for($i = 0 ; $i < $len; $i++) {
          $stack[$start+$i] = $item_array[$i];
        }

        $start = $len;
        // var_dump(count($item_array));
        // var_dump(count($stack));
        
      }
     // var_dump(count($stack));return;
     

      $sort = array();
      foreach($stack as $arr){
        $sort[] = $arr['views'];
      }
      array_multisort($sort, SORT_DESC,$stack );

      // function quick_sort_swap(&$array, $start, $end) {
      //  if($end <= $start) return;
      //  $key = $array[$start];
      //  $left = $start;
      //  $right = $end;
      //  while($left < $right) {
      //   while($left < $right && $array[$right] > $key)
      //    $right--;
      //   $array[$left] = $array[$right];
      //   while($left < $right && $array[$left] < $key)
      //    $left++;
      //   $array[$right] = $array[$left];
      //  }
      //  $array[$right] = $key;
      //  quick_sort_swap(&$array, $start, $right - 1);
      //  quick_sort_swap(&$array, $right+1, $end);
      // }
      

?>