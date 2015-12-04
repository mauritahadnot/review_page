<?php
$reviewsperpage = 10;
$json = file_get_contents('http://test.localfeedbackloop.com/api?apiKey=61067f81f8cf7e4a1f673cd230216112&noOfReviews=10&internal=1&yelp=1&google=1&offset=50&threshold=1');
$data = json_decode($json);

$totalReviews = $data->business_info->total_rating->total_no_of_reviews;
$pages = ceil($totalReviews/$reviewsperpage);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Review_page</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="logo"><a href="#"><img src="images/logo.png" /></a></div>
		<div class="col-sm-6 pull-right">
         <div class="col-sm-7">
          <h4> <?php echo @$data->business_info->business_name;?></h4>
          <p><a href="#"><i class="fa fa-map-marker"></i> <?php echo @$data->business_info->business_address;?></a></p>
          <p><a href="#"><i class="fa fa-phone"></i><?php echo @$data->business_info->business_phone;?></a></p>
         </div>
         <div class="col-sm-5 pull-right">
         <div class="rating">
          <ul class="icon_col">
           <li><a href="#"><i class="fa fa-star"></i> </a> </li>
           <li><a href="#"><i class="fa fa-star"></i> </a> </li>
           <li><a href="#"><i class="fa fa-star"></i> </a> </li>
           <li><a href="#"><i class="fa fa-star"></i> </a> </li>
           <li><a href="#"><i class="fa fa-star-half-o"></i> </a> </li>
          </ul><br />
          <p><a href="#reviews"><?php echo @$data->business_info->total_rating->total_no_of_reviews;?>  Reviews</a></p>
         </div></div>
         </div>
        </div>		
        <div id="reviews" class="reviews">
            <h1>Reviews</h1>
			<?php
			if(isset($data->reviews)):
				foreach($data->reviews as $review):
			?>
            <div class="reviews_list">
                <div class="date_opt">
                 <b><?php echo @$review->customer_name;?></b>
                <small><?php echo @$review->date_of_submission;?></small>     
                </div>
                <ul class="icon_col">
				<?php
					$rating = ceil(@$review->rating);
					if(!empty($rating)):
						for($i=1; $i<=5; $i++):
							if($i < $rating):
							?>
							<li><a href="#"><i class="fa fa-star"></i> </a> </li>
							<?php
							elseif($i == $rating):
								if(is_float(@$review->rating)):
								?>
									<li><a href="#"><i class="fa fa-star-half-o"></i> </a> </li>
								<?php
								else:
								?>
									<li><a href="#"><i class="fa fa-star"></i> </a> </li>
								<?php
								endif;
							else:
							?>
								<li><a href="#"><i class="fa fa-star-o"></i> </a> </li>
							<?php
							endif;					
						endfor;
					else:
					?>
					
					<?php
					endif;
				?>
                </ul>
                <h2><p><?php echo @$review->description;?></p>
            </div>
            <?php
				endforeach;
			
			endif;	
			?>
			
        </div>
         <div class="paginatn_sec">
          <nav>
		  <?php
		  if(!empty($pages)):
		  ?>
           <ul class="pagination">
             <li><a href="javascript:void(0);" aria-label="Previous" class="previous"><span aria-hidden="true">&laquo;</span></a></li>
			 <?php
			  for($i=1;$i<=$pages;$i++):
			  ?>
				 <li <?php echo ($i==1)?'class="active"':'';?>><a href="javascript:void(0);" class="list"><?php echo $i; ?></a></li>
			 <?php
			  endfor;
			  ?>
             <li><a href="javascript:void(0);" aria-label="Next" class="next"><span aria-hidden="true">&raquo;</span></a></li>
          </ul>
		  <?php
		  endif;
		  ?>
        </nav>
         </div>
    </div>
</div>

<script type="text/javascript">
$(function(){
	$('.list').on('click',function(){
		$.loadmore(parseInt($(this).text())-1);		
		$('.list').parent().removeClass('active');
		$(this).parent().addClass('active');
	});
	
	$('.previous').on('click',function(){
		var min = 1;
		var activeIndex = $('.active').index();
			if(activeIndex != min)
			{
				var page = parseInt($('.active').index())-2;
				$.loadmore(page);
				$('.list').parent().removeClass('active');
				$('.list').parent().eq(page).addClass('active');
			}
	});
	
	$('.next').on('click',function(){
		var max = $('.list').size();
		var activeIndex = $('.active').index();
		
			if(activeIndex != max)
			{
				var page = parseInt($('.active').index());
				$.loadmore(page);
				$('.list').parent().removeClass('active');
				$('.list').parent().eq(page).addClass('active');
			}
	});
	
	$.loadmore = function(page){
		if(!page)
		{
			page = 0;
		}
		var data = {reviewPerPage:<?php echo $reviewsperpage; ?>,loaded:page,totalreviews:<?php echo $totalReviews?>};
		$.ajax({
		  url: "./api.php",
		  data: data,
		  success: function(response) {
			//called when successful
            if (response != "") {
				if(response == 0)
				{
					$('div#lastPostsLoader').html('<p>No more reviews...</p>');
				}
				if(response == 1)
				{
					$(".reviews").append('<p>No reviews available</p>');
				}
				else{
					$('.reviews_list').remove();
					$(".reviews").append(response);
				}
                
            }
            $('div#lastPostsLoader').empty();
		  },
		});
	}
});
</script>  
</body>
</html>
