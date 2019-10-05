import pika
import json
import pymysql
import time

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
        if c.rowcount <= 1:
            c.execute(insertsql, (u, p, e))
            if c.rowcount == 1:
                dbconn.commit()
                r = True
    except:
        dbconn.rollback()
    print(r)
    return r


def attemptlogin(u, p):
    loginsql = "SELECT USER_NAME, USER_PASS FROM IT490_USERS WHERE USER_NAME = %s ;"
    dbconn = pymysql.connect("localhost", "IT490_DBUSER", "IT490", "IT490_MYSTERY_STEAM_THEATER")
    c = dbconn.cursor()
    c.execute(loginsql, (u,))
    tmp = c.fetchone()
    tmp_pass = tmp["USER_PASS"]
    v = (p == tmp_pass)
    return v


def callback(ch, method, properties, body):
    print(" [x] Received %r" % body)
    result = json.loads(body.decode('utf8'))
    cred2 = pika.PlainCredentials('alex', 'alex')
    connection2 = pika.BlockingConnection(
        pika.ConnectionParameters(host='192.168.0.105', credentials=cred2, virtual_host='authentication_results'))
    channel2 = connection2.channel()
    channel2.queue_declare(queue='hello')
    if result["operation"] == "login":
        login_result = attemptlogin(result["username"], result["password"])
        login_message_tmp = {
            "operation": "login",
            "username": result["username"],
            "result": login_result
        }
        login_message_json = json.dumps(login_message_tmp)
        channel2.basic_publish(exchange='', routing_key='hello', body=login_message_json)
        t = time.ctime(time.time())
        event = t + "  " + login_message_json
        f = open("database_log.txt", "a+")
        f.write(event)

    elif result["operation"] == "registration":
        reg_result = registeracct(result["username"], result["password"], result["email"])
        reg_message_tmp = {
            "operation": "register",
            "username": result["username"],
            "result": reg_result
        }
        reg_message_json = json.dumps(reg_message_tmp)
        channel2.basic_publish(exchange='', routing_key='hello', body=reg_message_json)



cred = pika.PlainCredentials('alex', 'alex')
connection = pika.BlockingConnection(
    pika.ConnectionParameters(host='192.168.0.105', credentials=cred, virtual_host='authentication'))

channel = connection.channel()
channel.queue_declare(queue='hello')
channel.basic_consume(
    queue='hello', on_message_callback=callback, auto_ack=True)

print(' [*] Waiting for messages. To exit press CTRL+C')
channel.start_consuming()


