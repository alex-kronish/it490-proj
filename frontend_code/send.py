#!/usr/bin/env python
import pika
import sys
import json

thisList = []
for item in sys.argv[1:]:
	thisList.append(item)

data = {'operation': 'registration','username': thisList[0],'password': thisList[1],'email': thisList[2]}	

message = json.dumps(data)


cred = pika.PlainCredentials('kevin','kevin')

connection = pika.BlockingConnection(
    pika.ConnectionParameters(host='192.168.0.105', credentials=cred, virtual_host='authentication'))
channel = connection.channel()

channel.queue_declare(queue='hello')

channel.basic_publish(exchange='', routing_key='hello', body=message)
print(" [x] Sent 'Hello World!'")
connection.close()