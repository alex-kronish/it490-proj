<?php 


class Twitch_API
{
	private $CLIENT_ID = 'xmrz4pm4zrhb5z6npgpcodj4bbjd8l';
	private $STREAM_RESULTS = [];

	public function __construct(){}

	/* Produce/Consume stream results of search-terms from API */
	public function get_stream_results($NAME, $CALLBACK)
	{
		$data = array
		(
			'operation' => 'twitch-search',
			'game' => $NAME
		);
		$data = json_encode($data);
		produceMessage($data, 'api', 'hello');
		consume('twitch-search', 'api_response', 'hello', function($response, $channel, $connection) use($CALLBACK){
			#Remove next line, only for testing!
			//$response = json_decode(file_get_contents('/var/www/html/it490-proj/frontend_code/data/twitch-streams.json'), true);	
			$result = $this->json_recurse_stream_results($response);
			$channel->close();
			$connection->close();
			if(is_callable($CALLBACK))
				call_user_func($CALLBACK, $result);
		});
	}

	/* Recursively store each stream's channel data into another array */
	public function json_recurse_stream_results($PAYLOAD)
	{
		$array = array();
		$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($PAYLOAD),RecursiveIteratorIterator::SELF_FIRST);
		foreach ($jsonIterator as $key => $val)
		    if(is_array($val) && array_key_exists('user_name', $val)){
		    	array_push($array, 
		    		array(
		    			'url' => "https://www.twitch.tv/".$val['user_name'],
		    			'streamer' => $val['user_name'], 
		    			'title' => $val['title'],
		    			'logo' => str_replace('-{width}x{height}', "", $val['thumbnail_url'])
		    		)
		    	);
		    }
		$this->STREAM_RESULTS = $array;
	}

	/* Return stream-results array */
	public function get_stream_results_array()
	{
		return $this->STREAM_RESULTS;
	}

	/* Echo HTML row of YouTube search results */
	public function echo_html_results()
	{
		foreach($this->get_stream_results_array() as $ITEM)
		{
			$url = $ITEM['url'];
			$streamer = $ITEM['streamer'];
			$title = $ITEM['title'];
			$logo = $ITEM['logo'];

			$html_string = 
			"<div class=\"col-md-3\">
				<a href=\"".$url."\">
					<div class=\"card mb-4 box-shadow\">
						<img class=\"card-img-top\" alt=\"Card image cap\" src=\" ".$logo."\" 
						>
						<div class=\"card-body\">
							<p class=\"card-text\">".$streamer.': '.$title."</p>
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