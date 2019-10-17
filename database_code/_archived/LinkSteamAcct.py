import pika
import json
import datetime
import pymysql


def linksteamacct(username, steam64_id):
    get_userid_sql = "SELECT USER_ID FROM IT490_USERS WHERE USER_NAME= %s;"
    insert_sql = "INSERT INTO IT490_STEAM_USER (USER_ID, STEAM64_ID, INSERT_DTTM) VALUES (%s, %s, CURRENT_TIMESTAMP());"
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")
    c = dbconn.cursor()
    c.execute(get_userid_sql, (username,))
    tmp = c.fetchone()
    if tmp is None:
        return False
    userid = tmp[0]
    print(userid)
    c.execute(insert_sql, (userid, steam64_id))
    if c.rowcount == 1:
        dbconn.commit()
        r = True
    else:
        dbconn.rollback()
        r = False
    c.close()
    dbconn.close()
    return r


def getsteamid(username):
    get_steam_sql = "SELECT STEAM64_ID FROM IT490_USERS U JOIN IT490_STEAM_USER S on S.USER_ID = U.USER_ID WHERE " \
                    "U.USER_NAME = %s  ; "
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")
    c = dbconn.cursor()
    c.execute(get_steam_sql, (username,))
    tmp = c.fetchone()
    if tmp is None:
        c.close()
        dbconn.close()
        return -1
    steam_id = tmp[0]
    print(steam_id)
    c.close()
    dbconn.close()
    return steam_id


def logtofile(severity, msg):
    t = str(datetime.datetime.now())
    event = t + " | " + severity + " | " + msg + "  \n"
    f = open("database_log.txt", "a+")
    f.write(event)
    f.close()


def logtodb(severity, msg, ipaddr):
    severity_dict = {
        "Info": 0,
        "Warning": 1,
        "Error": 2
    }
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


def callback(ch, method, properties, body):
    msg = json.loads(body)
    # operation: linksteam or operation: getsteamid
    if msg["operation"] == "linksteam":
        print("Attempting to link user to steam....")
        username = msg["username"]
        steam64_id = msg["steamid"]
        r = linksteamacct(username, steam64_id)
        log_msg = "Attempted to link local user " + username + "to steam64:" + steam64_id + " ; result is " + str(r)
        if r:
            severity = "Info"
        else:
            severity = "Error"
        logtodb(severity, log_msg, '192.168.0.103')
        logtofile(severity, log_msg)
        result = r
    elif msg["operation"] == "getsteamid":
        print("Attempting to find the steam id for the user")
        username = msg["username"]
        r = getsteamid(username)
        if r == -1:
            print("Couldn't find an associated steam id")
            severity = "Error"
            log_msg = "Attempted to get the steam ID for user " + \
                      msg["username"] + "but none was found. Returned -1 as an error code"
        else:
            print("Got a steam ID back")
            severity = "Info"
            log_msg = "Attempted to get the steam ID for user " + msg["username"] + " and got " + str(r)
        logtodb(severity, log_msg, '192.168..0.103')
        logtofile(severity, log_msg)
        result = r
    else:
        print("Message was not understood")
        print(json.dumps(msg))
        severity = "Warning"
        log_msg = "Message pulled off the queue by the Steam script was not understood... message: " + json.dumps(msg)
        logtodb(severity, log_msg, '192.168.0.103')
        logtofile(severity, log_msg)
        op = msg["operation"]
        result = -1
    response_msg = {"operation": msg["operation"],
                    "result": str(result)}
    connection2 = pika.BlockingConnection(
        pika.ConnectionParameters(host=rmqip, credentials=cred, virtual_host='steamid_results'))
    channel2 = connection2.channel()
    channel2.queue_declare(queue='hello')
    channel2.basic_publish(exchange='', routing_key='hello', body=json.dumps(response_msg))
    print("published results to the queue")
    connection2.close()


# rmqip = '192.168.0.105'
rmqip = "192.168.2.124"
cred = pika.PlainCredentials('alex', 'alex')
connection = pika.BlockingConnection(
    pika.ConnectionParameters(host=rmqip, credentials=cred, virtual_host='steamid'))
channel = connection.channel()
channel.queue_declare(queue='hello')
channel.basic_consume(
    queue='hello', on_message_callback=callback, auto_ack=True)
logtofile("Info", "Database script started: LinkSteamAcct.py")
logtodb("Info", "Database script started: LinkSteamAcct.py", '192.168.0.103')

print(' [*] Listening for SteamID messages. To exit press CTRL+C')
channel.start_consuming()
