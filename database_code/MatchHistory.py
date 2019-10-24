import pika
import json
import pymysql
import datetime


def logtofile(severity, msg):
    t = str(datetime.datetime.now())
    event = t + " | " + severity + " | " + msg + "  \n"
    f = open("database_log.txt", "a+")
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


def recordmatch(game, user1, user2, winner, matchdttm):
    sql = "INSERT INTO IT490_MATCH_HISTORY (GAME_NAME, USER_ONE, USER_TWO, WINNER, MATCH_DTTM, INSERT_DTTM) VALUES ( " \
          "%s, %s, %s, %s, %s, CURRENT_TIMESTAMP() ) ; "
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")

    c = dbconn.cursor()
    r = False
    try:
        c.execute(sql, (game, user1, user2, winner, matchdttm))
        if c.rowcount == 1:
            dbconn.commit()
            r = True
        else:
            r = False
    except:
        print(c.Error())
        dbconn.rollback()
        r = False
    r2 = {"operation": "record-match",
          "game": game,
          "result": r}
    return r2


def geteaderboard(steam64id):
    sql = "SELECT GAME_NAME, USER_ONE, USER_TWO, WINNER, MATCH_DTTM FROM IT490_MATCH_HISTORY WHERE %s in (USER_ONE, " \
          "USER_TWO) ; "
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")
    c = dbconn.cursor()
    c.execute(sql, (steam64id,))
    tmp = c.fetchall()
    if (tmp is None) or (c.rowcount == 0):
        r = {"operation": "get-leaderboard", "data": [None], "error": "result set is empty"}
    else:
        r2 = []
        for i in tmp:
            rowdata = {"game": i[0],
                       "user-one": i[1],
                       "user-two": i[2],
                       "winning-user": i[3],
                       "match-date": i[4]}
            r2.append(rowdata)
        r = {"operation": "get-leaderboard", "data": r2}
    c.close()
    dbconn.close()
    return r


def callback(ch, method, properties, body):
    print(" [x] Received %r" % body)
    result = json.loads(body.decode('utf8'))
    cred2 = pika.PlainCredentials('alex', 'alex')
    connection2 = pika.BlockingConnection(
        pika.ConnectionParameters(host=rmqip, credentials=cred2, virtual_host='leaderboard_results'))
    channel2 = connection2.channel()
    channel2.queue_declare(queue='hello')
    if result["operation"] == "record-match":
        resp = recordmatch(result["game"], result["user-one"], result["user-two"], result["winning-user"],
                           result["match-dttm"])
        if resp["result"]:
            severity = "Info"
        else:
            severity = "Error"
        logmsg = "Attempted to record match for two users, " + result["user-one"] + " , " + str(
            result["user-two"]) + " ; result is " + str(resp["result"])
    elif result["operation"] == "get-leaderboard":
        resp = geteaderboard(result["steam-id"])
        if "error" in resp:
            severity = "Error"
            logmsg = "Tried to get match history but there was none. Steam64 id : " + result["steam-id"]
        else:
            severity = "Info"
            logmsg = "Got match history for steam64 id: " + result["steam-id"]
    else:
        print("message was not understood")
    channel2.basic_publish(exchange='', routing_key='hello', body=json.dumps(resp))
    logtofile(severity, logmsg)
    logtodb(severity, logmsg, '192.168.0.103')
    connection2.close()


rmqip = '192.168.0.105'
# rmqip = "192.168.2.124"
cred = pika.PlainCredentials('alex', 'alex')
connection = pika.BlockingConnection(
    pika.ConnectionParameters(host=rmqip, credentials=cred, virtual_host='leaderboard'))

channel = connection.channel()
channel.queue_declare(queue='hello')
channel.basic_consume(
    queue='hello', on_message_callback=callback, auto_ack=True)
logtofile("Info", "Database script started: MatchHistory.py")
logtodb("Info", "Database script started: MatchHistory.py", '192.168.0.103')

print(' [*] Listening for Match History Messages. To exit press CTRL+C')
channel.start_consuming()
