<?php
$json = file_get_contents('http://test.localfeedbackloop.com/api?apiKey=61067f81f8cf7e4a1f673cd230216112&noOfReviews=10&internal=1&yelp=1&google=1&offset=50&threshold=1');
$data = json_decode($json);

$totalReviews = $data->business_info->total_rating->total_no_of_reviews;
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
         <div class="load_btn">
              <a href="javascript:void(0);" onclick="lastAddedLiveFunc();"><button type="button" class="btn btn-info btn-lg">More Reviews</button></a>
			  <div id="lastPostsLoader"></div>
         </div>
    </div>
</div>

<script type="text/javascript">

	var loaded = 1;
    function lastAddedLiveFunc()
    {
		$(document).ready(function(){
        $('div#lastPostsLoader').html('<img src="./images/ajax-loader.gif"/>');
		 var data = {reviewPerPage:10,loaded:loaded,totalreviews:<?php echo $totalReviews?>};		 
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
					$(".reviews").html('<p>No reviews available</p>');
				}
				else{
					$(".reviews").append(response);
				}
                
            }
            $('div#lastPostsLoader').empty();
		  },
		});
		loaded++;
        });
    };
</script>  
</body>
</html>
