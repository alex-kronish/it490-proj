# Front End Documentation

Tech used: Bootstrap 4.3.1, jQuery 3.4.1, AMQP Library, Php, AJAX

Purpose: Build the UI of every web page for the site and manage the client to handle producing/consuming data from the messaging broker. 

Pages: consist of home, steam, twitch, youtube, sign-in, registration, friend-page, tutorial-page.

Requests:request to the queue consists of several functions that help parse and handle the payload to display it to the UI. Producer/Consumer messaging is initiated followed by parsing the data to search for the information required for the web page. Data is sent back to the web page and handled through HTML/jQuery. 

Features: 
- Able to sign-in
- Register
- Via Steam ID:
	- View games list
	- View Friends list
	- Update/View match history with friend
	- See game information including game discounts on the steam store
	- Compare Leaderboard achievements with friend
	- See their games list

- Via Twitch
	- View videos related to the game title via tags/search-terms

- Via YouTube
	- View videos related to the game title via tags/search-terms
	