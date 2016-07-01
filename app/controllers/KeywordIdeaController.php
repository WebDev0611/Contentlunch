<?php

class KeywordIdeaController extends \BaseController {

	public function index()
	{
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function search($keyword, $page = 0)
	{
		  function send_output( $out ){
		  	echo json_encode( $out );
		  	exit;
		  }

		  $output_structure = array('long'=>array(), 'short'=>array());

		  if(!isset($keyword)){
		    send_output( $output_structure );
		  }

		  require_once 'Google/Api/Ads/AdWords/Lib/AdWordsUser.php';

		  $user = new AdWordsUser();

		  // Log every SOAP XML request and response.
		  $user->LogAll();

		  // Get the service, which loads the required classes.
		  $targetingIdeaService = $user->GetService('TargetingIdeaService', 'v201603');
		  // Create selector.
		  $selector = new TargetingIdeaSelector();
		  $selector->requestType = 'IDEAS';
		  $selector->ideaType = 'KEYWORD';

		  //should include a date range, to get long/short tail
		  $selector->requestedAttributeTypes = array('KEYWORD_TEXT', 'SEARCH_VOLUME', 'TARGETED_MONTHLY_SEARCHES');
		  // Create seed keyword.

		  // Create related to query search parameter.
		  $relatedToQuerySearchParameter = new RelatedToQuerySearchParameter();
		  $relatedToQuerySearchParameter->queries = array($keyword);
		  $selector->searchParameters[] = $relatedToQuerySearchParameter;
		  
		  //language - can revisit for i8n
		  $languageParameter = new LanguageSearchParameter();
		  $english = new Language();
		  $english->id = 1000;
		  $languageParameter->languages = array($english);
		  $selector->searchParameters[] = $languageParameter;

		  // Create network search parameter (optional).
		  $networkSetting = new NetworkSetting();
		  $networkSetting->targetGoogleSearch = true;
		  $networkSetting->targetSearchNetwork = false;
		  $networkSetting->targetContentNetwork = false;
		  $networkSetting->targetPartnerSearchNetwork = false;

		  $networkSearchParameter = new NetworkSearchParameter();
		  $networkSearchParameter->networkSetting = $networkSetting;
		  $selector->searchParameters[] = $networkSearchParameter;

		  // Set selector paging (required by this service).
		  $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
		  
		  $keyword_result_long_tail  = array();
		  $keyword_result_short_tail = array();
		  
		  //main do loop handles pagination
		  do {
		    // Make the get request.
		    $page = $targetingIdeaService->get($selector);
		    // Display results.
		    if (isset($page->entries)) {

		    	//iteratoe through results
		     	foreach ($page->entries as $targetingIdea) {
		     		$keyword_result_object = array();
			        $data = MapUtils::GetMap($targetingIdea->data);

			        $keyword_ideas = $data['KEYWORD_TEXT']->value;

			        $search_volume = isset($data['SEARCH_VOLUME']->value) ? $data['SEARCH_VOLUME']->value : 0;

			        $monthly_searches = $data['TARGETED_MONTHLY_SEARCHES']->value;

			        $keyword_result_object['keyword']  = $keyword_ideas;
			        $word_num = count( explode(' ', $keyword_ideas ) );
			        $keyword_result_object['volume']   = $search_volume;
			       // $keyword_result_object['monthly']  = $monthly_searches;

			        //crude but fairly appropriate way of separating long/short tail?
			        if($word_num > 2){
			        	array_push($keyword_result_long_tail,$keyword_result_object  );
			        }else{
			        	array_push($keyword_result_short_tail, $keyword_result_object );
			        }

		     	}
				
				//cmp function for the usort
				function cmp($a, $b){ return ( $a['volume'] < $b['volume'] ); }

				//user defined sort function
				usort($keyword_result_long_tail, "cmp");
				usort($keyword_result_short_tail, "cmp");

		     	$output_structure['long']  = $keyword_result_long_tail;
		     	$output_structure['short'] = $keyword_result_short_tail;

		     	send_output($output_structure);

		    } else {
		      send_output( $output_structure );
		    }

		    // Advance the paging index.
		    $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;

		    //dont like this while, its gross, should change
		  } while ($page->totalNumEntries > $selector->paging->startIndex);

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		echo 'create endpoint';
	//	exit;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
