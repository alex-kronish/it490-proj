import pika
import json
import pymysql
import datetime
import bcrypt


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


def registeracct(u, p, e):
    print('REGISTER')
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")
    insertsql = "INSERT INTO IT490_USERS (USER_NAME, USER_PASS, USER_EMAIL_ADDR, USER_REGISTRATION_DTTM, " \
                "USER_LAST_LOGIN_DTTM, ADMIN_FLAG) VALUES (%s, %s, %s, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), " \
                "'N' ) ; "
    alreadyreg = "SELECT USER_NAME FROM IT490_USERS WHERE USER_NAME=%s  ;"
    c = dbconn.cursor()
    r = False
    try:
        c.execute(alreadyreg, (u,))
        if c.rowcount < 1:
            c.execute(insertsql, (u, p, e))
            if c.rowcount == 1:
                dbconn.commit()
                r = True
    except:
        dbconn.rollback()
    print(r)
    c.close()
    dbconn.close()
    return r


def attemptlogin(u, p):
    loginsql = "SELECT USER_NAME, USER_PASS FROM IT490_USERS WHERE USER_NAME = %s ;"
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")
    c = dbconn.cursor()
    c.execute(loginsql, (u,))
    tmp = c.fetchone()
    if tmp is None:
        return False

    tmp_pass = tmp[1]
    v = (p == tmp_pass)
    c.close()
    dbconn.close()
    print(v)
    return v


def callback(ch, method, properties, body):
    print(" [x] Received %r" % body)
    result = json.loads(body.decode('utf8'))
    cred2 = pika.PlainCredentials('alex', 'alex')
    connection2 = pika.BlockingConnection(
        pika.ConnectionParameters(host=rmqip, credentials=cred2, virtual_host='authentication_results'))
    channel2 = connection2.channel()
    channel2.queue_declare(queue='hello')
    if result["operation"] == "login":
        login_result = attemptlogin(result["username"], result["password"])
        login_message_tmp = {
            "operation": "login",
            "username": result["username"],
            "result": str(login_result)
        }
        login_message_json = json.dumps(login_message_tmp)
        event_txt = "Login attempt for user " + result["username"] + " ; result is " + str(login_result)
        channel2.basic_publish(exchange='', routing_key='hello', body=login_message_json)
        if login_result:
            event_cd = "Info"
        else:
            event_cd = "Error"
        logtofile(event_cd, event_txt)
        logtodb(event_cd, event_txt, '192.168.0.107')

    elif result["operation"] == "register":
        reg_result = registeracct(result["username"], result["password"], result["email"])
        reg_message_tmp = {
            "operation": "register",
            "username": result["username"],
            "result": str(reg_result)
        }
        reg_message_json = json.dumps(reg_message_tmp)
        channel2.basic_publish(exchange='', routing_key='hello', body=reg_message_json)
        if reg_result:
            event_cd = "Info"
        else:
            event_cd = "Error"
        reg_message_txt = 'Registration attempt for user ' + result["username"] + " ; result is " + str(reg_result)
        logtofile(event_cd, reg_message_txt)
        logtodb(event_cd, reg_message_txt, '192.168.0.107')
    connection2.close()


rmqip = '192.168.0.105'
# rmqip = "192.168.2.124"
cred = pika.PlainCredentials('alex', 'alex')
connection = pika.BlockingConnection(
    pika.ConnectionParameters(host=rmqip, credentials=cred, virtual_host='authentication'))

channel = connection.channel()
channel.queue_declare(queue='hello')
channel.basic_consume(
    queue='hello', on_message_callback=callback, auto_ack=True)
logtofile("Info", "Database script started: RegisterAcct.py")
logtodb("Info", "Database script started: RegisterAcct.py", '192.168.0.107')

print(' [*] Waiting for messages. To exit press CTRL+C')
channel.start_consuming()
