<?php

$review_per_page = 10;
if(isset($_GET['reviewPerPage']))
{
	$review_per_page = $_GET['reviewPerPage'];
}

$offset = 0;
if(isset($_GET['loaded']))
{
	$offset = $review_per_page*$_GET['loaded'];
}


if(!isset($_GET['totalreviews']))
{
	return false;
}
else{
	$totalReviews = $_GET['totalreviews'];
}

$html = '';
if($_GET['loaded'] < ceil($totalReviews/$review_per_page)){
// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://test.localfeedbackloop.com/api',
    CURLOPT_USERAGENT => 'Fetching Business',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        'apiKey' => '61067f81f8cf7e4a1f673cd230216112',
        'noOfReviews' => $review_per_page,
		'internal' => 1,
		'yelp' => 1,
		'google' => 1,
		'offset' => $offset,
		'threshold' => 1,
		
		
    )
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);
$data = json_decode($resp);

if(isset($data->reviews)):
	foreach($data->reviews as $review):
		$html .= '<div class="reviews_list">
		<div class="date_opt">
		<b>'.@$review->customer_name.'</b>
		<small>'.@$review->date_of_submission.'</small>     
		</div>
		<ul class="icon_col">';
					$rating = ceil(@$review->rating);
					if(!empty($rating)):
						for($i=1; $i<=5; $i++):
							if($i < $rating):
							 $html .= '<li><a href="#"><i class="fa fa-star"></i> </a> </li>';
							elseif($i == $rating):
								if(is_float(@$review->rating)):
									$html .= '<li><a href="#"><i class="fa fa-star-half-o"></i> </a> </li>';
								else:
									$html .= '<li><a href="#"><i class="fa fa-star"></i> </a> </li>';
								endif;
							else:
								$html .= '<li><a href="#"><i class="fa fa-star-o"></i> </a> </li>';
							endif;					
						endfor;
					else:
					endif;
		$html .= '</ul>
		 <h2><p>'.@$review->description.'</p>
		</div>';
	endforeach;
else:
die(1);	
endif;
}
else{
	die(0);
}
echo $html;
?>