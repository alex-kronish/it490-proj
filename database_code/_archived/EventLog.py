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


def logtodb(severity, event_text, server_ip):
    severity_cd = severity_dict[severity]
    insertsql = "INSERT INTO IT490_EVENT_LOG (EVENT_DTTM, EVENT_CODE, EVENT_MESSAGE_TEXT, EVENT_SERVER_IP) VALUES (" \
                "CURRENT_TIMESTAMP(), %s, %s, %s) ; "
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")
    try:
        c = dbconn.cursor()
        c.execute(insertsql, (severity_cd, event_text, server_ip))
        if c.rowcount == 1:
            dbconn.commit()
            print("wrote message to logs table")
    except (pymysql.err.DatabaseError, pymysql.err.IntegrityError, pymysql.MySQLError) as ex:
        dbconn.rollback()
        print("database error:  " + str(ex))
    c.close()
    dbconn.close()


def callback(ch, method, properties, body):
    print("message recieved... trying to insert")
    logmsg_json = json.loads(body.decode("utf8"))
    severity = logmsg_json["severity"]
    severity_cd = severity_dict[severity]
    event_text = logmsg_json["event_text"]
    serverip = logmsg_json["server"]
    logtodb(severity, event_text, serverip)


rmqip = '192.168.0.105'
severity_dict = {
    "Info": 0,
    "Warning": 1,
    "Error": 2
}
# rmqip = "192.168.2.124"
cred4 = pika.PlainCredentials('alex', 'alex')
connection4 = pika.BlockingConnection(
    pika.ConnectionParameters(host=rmqip, credentials=cred4, virtual_host='central_logs'))
channel4 = connection4.channel()
channel4.queue_declare(queue='hello')
channel4.basic_consume(
    queue='hello', on_message_callback=callback, auto_ack=True)
print(" [*] Listening for logging events. Press CTRL+C to exit.")
logtofile("Info", "Database script started: EventLog.py")
logtodb("Info", "Database script started: EventLog.py", '192.168.0.103')
channel4.start_consuming()
