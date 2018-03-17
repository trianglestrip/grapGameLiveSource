<?php
 require("header.php");
 require("combine.php");

 ?>



  <ul id="live-list-contentbox" class="clearfix play-list" style="padding: 60px 20px 0px;"> 
    
    <?php 
      $itemCount = count($stack);

      $singlePageCount = 120; 

      $startIndex = ($page - 1)*$singlePageCount;

      $Ncount = 0;
      while($Ncount<$singlePageCount): 

        $index = $startIndex+$Ncount;
        if($index +1 > $itemCount ){break;}
        $item = $stack[$index];
    ?>                                         
    <li class=" " >
            <a href="<?php echo $item['href']; ?>" title="<?php echo $item['title']; ?>"  target="_blank" >
                <span class="imgbox">
                    <b></b>
                   <i class="black"></i>
                    <img data-original="<?php echo $item['image']; ?>"  width="283" height="163" style="display: block;">
                </span>

                <div class="mes">
                    <div class="mes-tit">
                        <h3 class="ellipsis"><?php echo $item['title']; ?></h3>
                        <span class="tag ellipsis"><?php echo $item['tvName']; ?></span>
                    </div>
                    <p><span class="dy-name ellipsis fl"><?php echo $item['name']; ?></span>
                        <span class="dy-num fr"><?php echo sprintf("%d", $item['views']); ?></span>
                     </p>
                </div>
            </a>
        
      </li>
    <?php $Ncount++; endwhile; ?>
    </ul>   
    <div id="pager" class="tcd-page-code">
      <div id="countPage" ><?php echo round($itemCount/$singlePageCount+0.5); ?></div>
      <div id="nowPage" ><?php echo $page;?></div>
    </div>
</div> 

  <script src="https://cdn.bootcss.com/jquery/1.8.2/jquery.min.js"></script>
  <script src="https://cdn.bootcss.com/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
  <script src="js/jQuery.pager.js"></script>
  <script type="text/javascript">

    var title =$("#title").text() ;
    var pageCount =parseInt($("#countPage").text()) ;
    var nowPage=parseInt($("#nowPage").text());
    $(function(){

      $(".imgbox img").lazyload({ 
        effect : "fadeIn" ,
        //failurelimit : 10,
        threshold: 200, 
        placeholder : "img/list-item-def-thumb.gif"
      }); 

      $("#pager").pager({ pagenumber: nowPage, pagecount: pageCount, buttonClickCallback: PageClick });
    
    });
    var PageClick = function(pageclickednumber) {
        $("#pager").pager({
            pagenumber: pageclickednumber, pagecount: pageCount, buttonClickCallback: PageClick
        });
       
        window.location.href = "index.php?id=<?php echo $type.'&page='?>"+pageclickednumber;
    };
</script>
<?php

 require("footer.php");
?>