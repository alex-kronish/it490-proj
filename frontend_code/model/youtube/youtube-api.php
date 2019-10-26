<?php 

class YouTube_API
{
	private $API_KEY = 'AIzaSyDo6w5bzKeCI8N2U3F72ErJLwWcQySk1Z4';
	private $URL = 'https://www.youtube.com/';
	private $WATCH = 'watch?v=';
	private $SEARCH_RESULTS=[];

	public function __construct()
	{}

	/* Return each stored video payload info in an array*/
	public function json_recurse_search_results($PAYLOAD)
	{
		$array = array();
		$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($PAYLOAD),RecursiveIteratorIterator::SELF_FIRST);
		foreach ($jsonIterator as $key => $val)
		    if(is_array($val) && array_key_exists('snippet', $val) && array_key_exists('videoId', $val['id'])){
		    	array_push(
		    		$array, array(
		    			'title' => $val['snippet']['title'],
		    			'thumbnail' => $val['snippet']['thumbnails']['default']['url'],
		    			'video-id' => $val['id']['videoId']
		    		)
		    	);
		    }
		$this->SEARCH_RESULTS = $array;
	}

	/* Return the array of search results */
	public function get_search_results_array()
	{
		return $this->SEARCH_RESULTS;
	}

	/* Produce/Consume data for a API request to search videos based on search terms */
	public function get_search_results($TERMS, $CALLBACK)
	{
		$key_terms = implode(',', $TERMS);
		$data = array (
			'operation' => 'youtube-search',
			'game' => $key_terms,
		);
		$data = json_encode($data);
		produceMessage($data, 'api', 'hello');
		consume('youtube-search', 'api_response', 'hello', function($response, $channel, $connection) use($CALLBACK){
			#Remove next line, only for testing!
			//$response = json_decode(file_get_contents('/var/www/html/it490-proj/frontend_code/data/youtube-search-results.json'), true);	
			$result = $this->json_recurse_search_results($response);
			$channel->close();
			$connection->close();
			if(is_callable($CALLBACK))
				call_user_func($CALLBACK, $result);
		});
	}

	/* Echo HTML row of YouTube search results */
	public function echo_html_results()
	{
		foreach($this->get_search_results_array() as $ITEM)
		{
			$video_id = $ITEM['video-id'];
			$thumbnail = $ITEM['thumbnail'];
			$title = $ITEM['title'];
			$youtube_link = $this->URL.$this->WATCH.$video_id;

			$html_string = 
			"<div class=\"col-md-3\">
				<a href=\"".$youtube_link."\">
					<div class=\"card mb-4 box-shadow\">
						<img class=\"card-img-top\" alt=\"Card image cap\" src=\" ".$thumbnail."\" 
						>
						<div class=\"card-body\">
							<p class=\"card-text\">".$title."</p>
							<div class=\"d-flex justify-content-between align-items-center\">
								<small class=\"text-muted\">9 mins</small>
							</div>
						</div>
					</div>
				</a>
			</div>";
			echo $html_string;
		}
	}
}
?>