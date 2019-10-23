import json
import requests
import pika
import datetime


def logtofile(severity, msg):
    t = str(datetime.datetime.now())
    event = t + " | " + severity + " | " + msg + "  \n"
    f = open("dmz_log.txt", "a+")
    f.write(event)
    f.close()


def logtodb(severity, msg, ipaddr):
    cred3 = pika.PlainCredentials('alex', 'alex')
    connection3 = pika.BlockingConnection(
        pika.ConnectionParameters(host=rmqip, credentials=cred3, virtual_host='central_logs'))
    channel3 = connection3.channel()
    channel3.queue_declare(queue='hello')
    logmsg = {"operation": "eventlog",
              "server": ipaddr,
              "severity": severity,
              "event_text": msg}
    logmsg_json = json.dumps(logmsg)
    channel3.basic_publish(exchange='', routing_key='hello', body=logmsg_json)
    connection3.close()


def getgameinfo(appid):
    gameurl = "https://store.steampowered.com/api/appdetails?appids=" + appid + "&cc=us&l=en"
    game = requests.get(gameurl)
    gameresp = game.json()
    r = {"operation": "get-game-info"}
    r.update(gameresp)
    return r


def getgameslist(steamid, apikey, minutes_filter):
    requrl = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=" + apikey + \
             "&steamid=" + steamid + "&format=json&include_appinfo=1"
    gresp = requests.get(requrl)
    operation = "get-games-list"
    gresp2 = gresp.json()["response"]
    glist = gresp2["games"]
    glist_f = []
    for i in glist:
        if i["playtime_forever"] <= minutes_filter:  # filter by time played
            glist_f.append(i)  # this is a way safer way to do this
    # print(glist_f)
    resp_dict = {"operation": operation,
                 "games": glist_f}
    # print(msg)
    return resp_dict


def getsteamfriends(steamid, apikey, fmt, relationship):
    requrl = "http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=" \
             + apikey + "&format=" + fmt + "&relationship=" + relationship + "&steamid=" + steamid
    resp = requests.get(requrl)
    api_output = resp.json()
    api_friendslist = api_output["friendslist"]
    api_fr = api_friendslist
    tmp_array = ""
    for i in api_fr["friends"]:
        tmp_array = tmp_array + i["steamid"] + ","
        # print(i["steamid"])
    flist = tmp_array[:-1]  # kill the stray comma at the end
    # print(flist)
    operation = "get-friends-list"
    api_response = api_friendslist
    api_response.update({"operation": operation})
    requrl2 = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" \
              + apikey + "&format=" + fmt + "&steamids=" + flist
    resp2 = requests.get(requrl2)
    flist_resp = resp2.json()
    flist_resp2 = flist_resp["response"]
    flist_resp3 = flist_resp2["players"]
    # print(flist_resp3) # trying to clean up this response sucks man
    response_dict = {
        "operation": operation,
        "friends": flist_resp3
    }
    return response_dict


def getsteaminfo(steamid, apikey):
    requrl2 = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" \
              + apikey + "&format=json&steamids=" + steamid
    resp2 = requests.get(requrl2)
    user_resp = resp2.json()
    user_resp2 = user_resp["response"]
    user_resp3 = user_resp2["players"]
    response_dict = {
        "operation": "get-steam-info",
        "friends": user_resp3
    }
    return response_dict


def callyoutubesearch(search_string):
    apikey = "AIzaSyAhGC7AmQFhunKRWisNPHOn3A0AqG7R8EU"
    u = "https://www.googleapis.com/youtube/v3/search?key=" + apikey + "&part=snippet&q=" \
        + search_string + " Gameplay&type=video"
    # print(u)
    resp = requests.get(u)
    search_result = resp.json()
    tmp = []
    '''for i in search_result["items"]:
        print(i)
        videoid = i["id"]["videoId"]
        thumb = i["snippet"]["thumbnails"]["high"]["url"]
        title = i["snippet"]["title"]
        published = i["snippet"]["publishedAt"]
        channeltitle = i["snippet"]["channelTitle"]
        tmp_dict = {"title": title,
                    "thumbnail": thumb,
                    "published-date": published,
                    "videoid": videoid,
                    "channel": channeltitle,
                    "url": ("https://youtu.be/" + videoid)}
        tmp.append(tmp_dict)'''
    r = {"operation": "youtube-search"}
    r.update(search_result)
    return r


def calltwitchsearch(search_string):
    h = {
        "Client-ID": "c6kq3s5z91jfty8guhawa9sparb0ob"
    }
    streamlist_url = "https://api.twitch.tv/helix/streams?game_id="
    gamesearch_url = "https://api.twitch.tv/helix/games?name=" + search_string
    # search response is data/id
    gameidresp = requests.get(gamesearch_url, headers=h)
    gameidresp_json = gameidresp.json()
    if not gameidresp_json["data"]:
        print("result set is empty")
        r = {"operation": "twitch-search", "error": "Game was not found"}
    else:
        gameid = gameidresp_json["data"][0]["id"]
        print("First call completed, Found a twitch game id!  Game ID = " + gameid)
        streams = requests.get(streamlist_url + gameid, headers=h)
        streams_json = streams.json()
        if not streams_json["data"]:
            print("no streams currently, result set is empty")
            r = {"operation": "twitch-search", "error": "No streams active for this game"}
        else:
            '''a = []
            for st in streams_json["data"]:
                username = st["user_name"]
                thumbnail = st["thumbnail_url"].replace("{width}", "480", 1).replace("{height}", "360", 1)
                title = st["title"]
                startedat = st["started_at"]
                tmp_dict = {
                    "username": username,
                    "title": title,
                    "started-at": startedat,
                    "thumbnail": thumbnail,
                    "url": ("https://twitch.tv/" + username)
                }
                a.append(tmp_dict)'''
            r = {"operation": "twitch-search"}
            r.update(streams_json)
    return r


def callback(ch, method, properties, body):
    input_msg = json.loads(body)
    op = input_msg["operation"]
    severity = "Info"
    if op == "get-friends-list":
        apikey = input_msg["api-key"]
        steamid = input_msg["steam-id"]
        output_msg = json.dumps(getsteamfriends(steamid, apikey, 'json', 'friend'))
        # print("Published API response from Steam Friends")
        logmsg = "Steam API call to get friends list was made."
    elif op == "get-games-list":
        apikey = input_msg["api-key"]
        steamid = input_msg["steam-id"]
        minutes = 600
        output_msg = json.dumps(getgameslist(steamid, apikey, minutes))
        logmsg = "Steam API call to get games list was made. Filter condition less than " + str(
            minutes) + " minutes played"
    elif op == "youtube-search":
        # searchstr = "Yakuza Kiwami"
        searchstr = input_msg["game"]
        output_msg = json.dumps(callyoutubesearch(searchstr))
        logmsg = "Youtube API call for game: " + searchstr + " was made"
    elif op == "twitch-search:":
        # searchstr = "Super Mario 64"
        searchstr = input_msg["game"]
        output_msg = json.dumps(calltwitchsearch(searchstr))
        logmsg = "Twitch API call for game: " + searchstr + " was made"
    elif op == "get-steam-info":
        apikey = input_msg["api-key"]
        steamid = input_msg["steam-id"]
        output_msg = json.dumps(getsteaminfo(steamid, apikey))
        logmsg = "Steam API Call for single user info was made."
    elif op == "get-game-info":
        appid = input_msg["app-id"]
        output_msg = getgameinfo(appid)
        logmsg = "Got price info for game, appid = " + appid
    else:
        print("Message was not understood.  " + str(body))
        output_msg_a = {
            "operation": op,
            "error": "Unknown Operation"
        }
        output_msg = json.dumps(output_msg_a)
        severity = "Error"
        logmsg = "Message on the API Queue was not understood. Here is the output: " + json.dumps(body)
    logtofile(severity, logmsg)
    logtodb(severity, logmsg, '192.168.0.106')
    connection2 = pika.BlockingConnection(
        pika.ConnectionParameters(host=rmqip, credentials=cred, virtual_host='api_response'))
    chnl = connection2.channel()
    chnl.queue_declare(queue='hello')
    chnl.basic_publish(exchange='', routing_key='hello', body=output_msg)
    connection2.close()
    print("Published API message for " + op)


rmqip = '192.168.0.105'
# rmqip = "192.168.2.124"
cred = pika.PlainCredentials('alex', 'alex')
connection = pika.BlockingConnection(
    pika.ConnectionParameters(host=rmqip, credentials=cred, virtual_host='api'))
channel = connection.channel()
channel.queue_declare(queue='hello')
channel.basic_consume(
    queue='hello', on_message_callback=callback, auto_ack=True)
print(' [*] Listening for API Messages. To exit press CTRL+C')
channel.start_consuming()
