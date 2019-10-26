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


def recordmatch(self, friend, outcome, qty):
    sql = "INSERT INTO IT490_MATCH_HISTORY (SELF, FRIEND, WINS, LOSSES, INSERT_DTTM) VALUES ( " \
          "%s, %s, %s, %s, CURRENT_TIMESTAMP() ) ; "
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")
    wins = 0
    losses = 0
    if outcome == "won":
        wins = qty
    elif outcome == "lost":
        losses = qty
    c = dbconn.cursor()
    r = False
    try:
        c.execute(sql, (self, friend, wins, losses))
        if c.rowcount == 1:
            dbconn.commit()
            r = True
        else:
            r = False
    except:
        print(c.Error())
        dbconn.rollback()
        r = False
    r2 = {"operation": "match-history",
          "result": str(r)}
    return r2


def getleaderboard(user, friend):
    sql = "SELECT SELF, FRIEND, SUM(WINS) AS WINS, SUM(LOSSES) AS LOSSES FROM IT490_MATCH_HISTORY WHERE SELF= %s  " \
          "AND FRIEND = %s GROUP BY SELF, FRIEND; "
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")
    c = dbconn.cursor()
    c.execute(sql, (user, friend))
    tmp = c.fetchone()
    if (tmp is None) or (c.rowcount == 0):
        r = {"operation": "view-history", "current-user": user, "friend": friend, "wins": "0", "losses": "0"}
    else:
        r = {"operation": "view-history",
             "current-user": tmp[0],
             "friend": tmp[1],
             "wins": str(tmp[2]),
             "losses": str(tmp[3])}
    c.close()
    dbconn.close()
    return r


def callback(ch, method, properties, body):
    # print(" [x] Received %r" % body)
    result = json.loads(body.decode('utf8'))
    if result["operation"] == "match-history":
        resp = recordmatch(result["current-user"], result["friend"], result["outcome"], result["num-matches"])
        if resp["result"]:
            severity = "Info"
        else:
            severity = "Error"
        logmsg = "Attempted to record match for two users, " + result["current-user"] + " , " + str(
            result["friend"]) + " ; result is " + str(resp["result"])
    elif result["operation"] == "view-history":
        resp = getleaderboard(result["current-user"], result["friend"])
        if "error" in resp:
            severity = "Error"
            logmsg = "Tried to get match history but there was none. Users: " + result["current-user"] + " ; " + \
                     result["friend"]
        else:
            severity = "Info"
            logmsg = "Got match history for : " + result["current-user"] + " and " + result["friend"]
    else:
        print("message was not understood")
        logmsg = "Message on the Queue for Match History was not understood, here is the output: " \
                 + str(body.decode('utf8'))
        severity = "Error"
        resp = {
            "operation": result["operation"],
            "error": "Message was not understood"
        }
    cred2 = pika.PlainCredentials('alex', 'alex')
    connection2 = pika.BlockingConnection(
        pika.ConnectionParameters(host=rmqip, credentials=cred2, virtual_host='match_results'))
    channel2 = connection2.channel()
    channel2.queue_declare(queue='hello')
    channel2.basic_publish(exchange='', routing_key='hello', body=json.dumps(resp))
    print("Published MatchResults message " + result["operation"])
    logtofile(severity, logmsg)
    logtodb(severity, logmsg, '192.168.0.103')
    connection2.close()


rmqip = '192.168.0.105'
# rmqip = "192.168.2.124"
cred = pika.PlainCredentials('alex', 'alex')
connection = pika.BlockingConnection(
    pika.ConnectionParameters(host=rmqip, credentials=cred, virtual_host='match_history'))

channel = connection.channel()
channel.queue_declare(queue='hello')
channel.basic_consume(
    queue='hello', on_message_callback=callback, auto_ack=True)
logtofile("Info", "Database script started: MatchHistory.py")
logtodb("Info", "Database script started: MatchHistory.py", '192.168.0.103')

print(' [*] Listening for Match History Messages. To exit press CTRL+C')
channel.start_consuming()
