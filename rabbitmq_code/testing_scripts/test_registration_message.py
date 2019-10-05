import pika
import datetime
import json

cred2 = pika.PlainCredentials('alex', 'alex')
connection = pika.BlockingConnection(pika.ConnectionParameters('localhost', credentials=cred2,
                                                               virtual_host="authentication"))
channel = connection.channel()

msg = {"operation": "registration", "username": "ak2354",
       "password": "fakepass", "email": "ak2354@njit.edu"}
msginput = json.dumps(msg)
channel.queue_declare('hello')
channel.basic_publish(exchange='', routing_key='hello', body=msginput)
print(" [x] sent ", msginput)
output_file = "mq_send.log"
logging_string = "Sent message " + msginput + " at " + str(datetime.datetime.now()) + "\n"
f = open(output_file, "a+")
f.write(logging_string)
f.close

print("exiting....")
connection.close()
