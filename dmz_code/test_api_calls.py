import json
import requests
import pika


def callyoutubesearch(search_string):
    apikey = "AIzaSyAhGC7AmQFhunKRWisNPHOn3A0AqG7R8EU"
    u = "https://www.googleapis.com/youtube/v3/search?key=" + apikey + "&part=snippet&q=" + search_string + " Gameplay"
    resp = requests.get(u)
    search_result = resp.json()
    return search_result


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
        return -99
    else:
        gameidresp_json_b = gameidresp_json["data"][0]
        # print(gameidresp_json_b)
        gameid = gameidresp_json_b["id"]
        print("First call completed, Found a twitch game id!  Game ID = " + gameid)

        streams = requests.get(streamlist_url + gameid, headers=h)
        streams_json = streams.json()
        if not streams_json["data"]:
            print("no streams currently, result set is empty")
            return -88
        else:
            for st in streams_json["data"]:
                print(json.dumps(st))
                # TO DO: parse the response and pass back a list of usernames, stream url's and titles


def getgameslist(steamid, apikey, minutes_filter):
    requrl = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=" + apikey + \
             "&steamid=" + str(steamid) + "&format=json&include_appinfo=1"
    gresp = requests.get(requrl)
    print(requrl)
    operation = "get-games-list"
    gresp2 = gresp.json()
    print(gresp2)
    if not "games" in gresp2["response"]:
        resp_dict = {"operation": operation,
                     "error": "User's game list is not public",
                     "games": [None]}
    else:
        glist = gresp2["response"]["games"]
        glist_f = []
        for i in glist:
            if i["playtime_forever"] <= minutes_filter:  # filter by time played
                glist_f.append(i)  # this is a way safer way to do this
        # print(glist_f)
        resp_dict = {"operation": operation,
                     "games": glist_f}
        # print(msg)
    return resp_dict


t = getgameslist("76561197991305494", "2308E9671CE3A9E02191ED237EA731E0", 600)
print(json.dumps(t))
