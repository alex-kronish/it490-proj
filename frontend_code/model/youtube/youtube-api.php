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
}
?>