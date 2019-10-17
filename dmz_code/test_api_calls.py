import json
import requests
import pika


def callyoutubesearch(search_string):
    apikey = "AIzaSyAhGC7AmQFhunKRWisNPHOn3A0AqG7R8EU"
    u = "https://www.googleapis.com/youtube/v3/search?key=" + apikey + "&part=snippet&q=" + search_string + " Gameplay"
    resp = requests.get(u)
    print(resp.json())
    return resp


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


calltwitchsearch("Yakuza Kiwami")
callyoutubesearch("Yakuza Kiwami")
