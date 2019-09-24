#!/usr/bin/env python
import pika
cred = pika.PlainCredentials('kevin','kevin')

connection = pika.BlockingConnection(
    pika.ConnectionParameters(host='192.168.0.105', credentials=cred, virtual_host='vhost'))
channel = connection.channel()

channel.queue_declare(queue='hello')

channel.basic_publish(exchange='', routing_key='hello', body='Hello World!')
print(" [x] Sent 'Hello World!'")
connection.close()