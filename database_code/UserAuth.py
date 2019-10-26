import pika
import json
import pymysql
import datetime


# import bcrypt


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


def registeracct(u, p, e, s):
    print('REGISTER')
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")
    insertsql = "INSERT INTO IT490_USERS (USER_NAME, USER_PASS, USER_EMAIL_ADDR, USER_REGISTRATION_DTTM, " \
                "USER_LAST_LOGIN_DTTM, ADMIN_FLAG) VALUES (%s, %s, %s, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), " \
                "'N' ) ; "
    alreadyreg = "SELECT USER_ID FROM IT490_USERS WHERE USER_NAME=%s  ;"
    insert_steamid = "INSERT INTO IT490_STEAM_USER (USER_ID, STEAM64_ID, INSERT_DTTM) VALUES (%s, %s, " \
                     "CURRENT_TIMESTAMP()); "
    c = dbconn.cursor()
    r = False
    try:
        c.execute(alreadyreg, (u,))
        if c.rowcount < 1:
            c.execute(insertsql, (u, p, e))
            if c.rowcount == 1:
                dbconn.commit()
                c.execute(alreadyreg, (u,))
                tmp = c.fetchone()
                userid = tmp[0]
                print(userid)
                dbconn.commit()
                r = True
            c.execute(insert_steamid, (userid, s))
            if c.rowcount == 1:
                dbconn.commit()
                r = True
    except:
        print(c.Error())
        dbconn.rollback()

    print(r)
    c.close()
    dbconn.close()
    return r


def attemptlogin(u, p):
    print("LOGIN")
    loginsql = "SELECT USER_NAME, USER_PASS, STEAM64_ID FROM IT490_USERS U JOIN IT490_STEAM_USER S on S.USER_ID = " \
               "U.USER_ID WHERE USER_NAME = %s ; "
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")
    c = dbconn.cursor()
    c.execute(loginsql, (u,))
    tmp = c.fetchone()
    if tmp is None:
        return -1
    tmp_pass = tmp[1]
    v = (p == tmp_pass)
    if not v:
        return -1
    steamid = tmp[2]
    c.close()
    dbconn.close()
    #print(v)
    #print(steamid)
    return steamid


def callback(ch, method, properties, body):
    #print(" [x] Received %r" % body)
    result = json.loads(body.decode('utf8'))
    cred2 = pika.PlainCredentials('alex', 'alex')
    connection2 = pika.BlockingConnection(
        pika.ConnectionParameters(host=rmqip, credentials=cred2, virtual_host='authentication_results'))
    channel2 = connection2.channel()
    channel2.queue_declare(queue='hello')
    if result["operation"] == "login":
        steamid = attemptlogin(result["username"], result["password"])
        if steamid == -1 or steamid is None:
            login_result = False
        else:
            login_result = True
        login_message_tmp = {
            "operation": "login",
            "username": result["username"],
            "result": str(login_result),
            "steam-id": steamid
        }
        login_message_json = json.dumps(login_message_tmp)
        event_txt = "Login attempt for user " + result["username"] + " ; result is " + str(login_result)
        channel2.basic_publish(exchange='', routing_key='hello', body=login_message_json)
        if login_result:
            event_cd = "Info"
        else:
            event_cd = "Error"
        logtofile(event_cd, event_txt)
        logtodb(event_cd, event_txt, '192.168.0.103')

    elif result["operation"] == "register":
        reg_result = registeracct(result["username"], result["password"], result["email"], result["steam-id"])
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
        logtodb(event_cd, reg_message_txt, '192.168.0.103')
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
logtofile("Info", "Database script started: UserAuth.py")
logtodb("Info", "Database script started: UserAuth.py", '192.168.0.103')

print(' [*] Listening for Authentication Messages. To exit press CTRL+C')
channel.start_consuming()
