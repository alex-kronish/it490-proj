<?php 
class YouTube_API
{
	private $API_KEY = 'AIzaSyDo6w5bzKeCI8N2U3F72ErJLwWcQySk1Z4';
	private $URL = 'https://www.youtube.com/';
	private $WATCH = 'watch?v=';

	public function __construct()
	{}

	/* Return each stored video payload info in an array*/
	public function getSearchResults($PAYLOAD)
	{
		$results;
		foreach($PAYLOAD as $ITEM)
		{
			$video = array 
			(
				'video-id' => $ITEM['video-id'];
				'title' => $ITEM['title'];
				'thumbnail' => $ITEM['thumbnail'];
			);
			array_push($results, $video);
		}
		return $results;
	}

	public function echo_html_results($PAYLOAD)
	{
		foreach($PAYLOAD as $ITEM)
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