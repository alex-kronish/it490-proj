import json
import requests
import pika


def getgameslist(steamid, apikey):
    requrl = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=" + apikey + \
             "&steamid=" + steamid + "&format=json&include_appinfo=1"
    gresp = requests.get(requrl)
    operation = "get-games-list"
    gresp2 = gresp.json()["response"]
    glist = gresp2["games"]
    glist_f = "["
    for i in glist:
        if i["playtime_forever"] <= 600:
            glist_f = glist_f + json.dumps(i)
    glist_f = glist_f + ']'
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


def callback(ch, method, properties, body):
    input_msg = json.loads(body)
    op = input_msg["operation"]
    if op == "get-friends-list":
        apikey = input_msg["api-key"]
        steamid = input_msg["steam-id"]
        output_msg = json.dumps(getsteamfriends(steamid, apikey, 'json', 'friend'))
        # print("Published API response from Steam Friends")
    elif op == "get-games-list":
        apikey = input_msg["api-key"]
        steamid = input_msg["steam-id"]
        output_msg = json.dumps(getgameslist(steamid, apikey))
    elif op == "youtube-search":
        print("Not implemented: Youtube Search")
    elif op == "twitch-search:":
        print("Not Implemented: Twitch Search")
    elif op == "get-steam-info":
        apikey = input_msg["api-key"]
        steamid = input_msg["steam-id"]
        output_msg = json.dumps(getsteaminfo(steamid, apikey))
    else:
        print("Message was not understood.  " + str(body))
        output_msg = {
            "operation": op,
            "error": "Unknown Operation"
        }
    connection2 = pika.BlockingConnection(
        pika.ConnectionParameters(host=rmqip, credentials=cred, virtual_host='api_response'))
    chnl = connection2.channel()
    chnl.queue_declare(queue='hello')
    chnl.basic_publish(exchange='', routing_key='hello', body=output_msg)
    connection2.close()
    print("Published API message for " + op)


# getgameslist('76561197995725518', '2308E9671CE3A9E02191ED237EA731E0')
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
