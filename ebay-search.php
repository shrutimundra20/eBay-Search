<?php 

header('Access-Control-Allow-Origin: *');
	
	$url="http://svcs.ebay.com/services/search/FindingService/v1?siteid=0?OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&SECURITY-APPNAME=Universi-279b-4fa0-a082-d658ab8d3a10&GLOBAL-ID=EBAY-US&outputSelector[1]=SellerInfo&outputSelector[2]=PictureURLSuperSize&outputSelector[3]=StoreInfo&paginationInput.pageNumber=".(int)$_GET['pageNum'];
	
	$j=0;

	
	
		$res='';
		$kwi=$_GET['key'];
		$tempkeya=urlencode($kwi);
		$tempa="&keywords=".$tempkeya;
		$url.=$tempa;
		$sorting=$_GET['sort'];
		$tempa="&sortOrder=".$sorting;
		$url.=$tempa;
		$res=$_GET['res_vari'];
		$tempa="&paginationInput.entriesPerPage=".$res;
		$url.=$tempa;
		$mini=$_GET['from_pri'];
		$maxi=$_GET['to_pri'];
		
		if($maxi!='')
		{
			
			$tempa="&itemFilter(".$j.").name=MaxPrice&itemFilter(".$j.").value=".$maxi;
			$url.=$tempa;
			$j++;

		}
		if($mini!='')
		{
			$tempa="&itemFilter(".$j.").name=MinPrice&itemFilter(".$j.").value=".$mini;
			$url.=$tempa;
			$j++;
		}
		
if (isset($_GET['condition']) && is_array($_GET['condition']))
{
   $i=0;
	
  
   	$condtest_var="&itemFilter(".$j.").name=Condition";
      if (in_array('new', $_GET['condition']))
      {
      	$condtest_var.="&itemFilter(".$j.").value(".$i.")=1000";
      	$i++;
      }
      if (in_array('used', $_GET['condition']))
      {
      	$condtest_var.="&itemFilter(".$j.").value(".$i.")=3000";
      	$i++;
      }
      if (in_array('verygood', $_GET['condition']))
      {
      	$condtest_var.="&itemFilter(".$j.").value(".$i.")=4000";
      	$i++;
      }
      if (in_array('good', $_GET['condition']))
      {
      	$condtest_var.="&itemFilter(".$j.").value(".$i.")=5000";
      	$i++;
      }
      if (in_array('acceptable', $_GET['condition']))
      {
      	$condtest_var.="&itemFilter(".$j.").value(".$i.")=6000";
      	$i++;
      }
      
   $url.=$condtest_var;
   $j++;
}	

  if (isset($_GET['Format_buy']) && is_array($_GET['Format_buy']))
{
   $i=0;
	
  

   	$buyingtest_var="&itemFilter(".$j.").name=ListingType";
      if (in_array('now', $_GET['Format_buy']))
      {
      	$buyingtest_var.="&itemFilter(".$j.").value(".$i.")=FixedPrice";
      	$i++;
      }
      if (in_array('auction', $_GET['Format_buy']))
      {
      	$buyingtest_var.="&itemFilter(".$j.").value(".$i.")=Auction";
      	$i++;
      }
      if (in_array('classified', $_GET['Format_buy']))
      {
      	$buyingtest_var.="&itemFilter(".$j.").value(".$i.")=Classified";
      	$i++;
      }
 
      
   $url.=$buyingtest_var;
   $j++;
}

	if(isset($_GET['seller_sell']))
		{		
			
		$tempa="&itemFilter(".$j.").name=ReturnsAcceptedOnly&itemFilter(".$j.").value=true";
		$url.=$tempa;
		$j++;
	}

if(isset($_GET['ship_free']))
{
		$tempa="&itemFilter(".$j.").name=FreeShippingOnly&itemFilter(".$j.").value=true";
		$url.=$tempa;
		$j++;
	}

	if(isset($_GET['exp_ship']))
		{
		$tempa="&itemFilter(".$j.").name=ExpeditedShippingType&itemFilter(".$j.").value=Expedited";
		$url.=$tempa;
		$j++;
	}
		$maxhandlingdaysavailable=$_GET['maximum_no_days'];
		if($maxhandlingdaysavailable!='')
		{
			$tempa="&itemFilter(".$j.").name=MaxHandlingTime&itemFilter(".$j.").value=".$maxhandlingdaysavailable;
			$url.=$tempa;
			$j++;

		}
		


$loop=0;
//echo$url;
$fetchxml = simplexml_load_file($url);

if(($fetchxml->paginationOutput->totalEntries)==0)
{
	$global_array= array('ack'=>'No Resuts Found');
}
else{

	
if ($fetchxml->ack == "Success") {

$count=0;
$page_count=0;
$item_count=0;
$temp_array_count=0;

$global_array['ack']= (string)$fetchxml->ack;
$global_array["resultCount"]=(string)$fetchxml->paginationOutput->totalEntries;
$global_array["pageNumber"]=(string)$fetchxml->paginationOutput->pageNumber;
$global_array["itemCount"]=(string)$fetchxml->paginationOutput->entriesPerPage;
foreach($fetchxml->searchResult->item as $item){
	$itemNum = "item".$item_count;
	//echo $item->title."<br><br><br><br>";
	$arr['basicInfo'] = array("title"=>(string)$item->title,"viewItemURL"=>(string)$item->viewItemURL,
			"galleryURL"=>(string)$item->galleryURL,
			"pictureURLSuperSize"=>(string)$item->pictureURLSuperSize,
			"convertedCurrentPrice"=>(string)$item->sellingStatus->convertedCurrentPrice,
			"shippingServiceCost"=>(string)$item->shippingInfo->shippingServiceCost,
			"conditionDisplayName"=>(string)$item->condition->conditionDisplayName,
			"listingType"=>(string)$item->listingInfo->listingType,
			"location"=>(string)$item->location,
			"categoryName"=>(string)$item->primaryCategory->categoryName,
			"topRatedListing"=>(string)$item->topRatedListing);
			
			$arr["sellerInfo"] = array("sellerUserName"=>(string)$item->sellerInfo->sellerUserName,
			"feedbackScore"=>(string)$item->sellerInfo->feedbackScore,
			"positiveFeedbackPercent"=>(string)$item->sellerInfo->positiveFeedbackPercent,
			"feedbackRatingStar"=>(string)$item->sellerInfo->feedbackRatingStar,
			"topRatedSeller"=>(string)$item->sellerInfo->topRatedSeller,
			"sellerStoreName"=>(string)$item->storeInfo->storeName,
			"sellerStoreURL"=>(string)$item->storeInfo->storeURL);
			
			$arr["shippingInfo"] = array("shippingType"=>(string)$item->shippingInfo->shippingType,
									"shipToLocations"=>(string)$item->shippingInfo->shipToLocations,
									"expeditedShipping"=>(string)$item->shippingInfo->expeditedShipping,
									"oneDayShippingAvailable"=>(string)$item->shippingInfo->oneDayShippingAvailable,
									"returnsAccepted"=>(string)$item->returnsAccepted,
									"handlingTime"=>(string)$item->shippingInfo->handlingTime
									);
									
	$global_array[$itemNum] = $arr; 
	$item_count++;
	}
	
	

	}
	 }
$json= json_encode($global_array);
	$json = urldecode(str_replace('//',"",$json));
	$json_fixquotes=urldecode(str_replace('\"',"&quot;",$json));
	$jq=stripslashes($json_fixquotes);

	echo $jq;
?>