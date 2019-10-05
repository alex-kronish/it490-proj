#!/usr/bin/env python
import pika


cred = pika.PlainCredentials('kevin','kevin')


connection = pika.BlockingConnection(
    pika.ConnectionParameters(host='192.168.0.105', credentials=cred, virtual_host='authentication'))
channel = connection.channel()

channel.queue_declare(queue='hello')


def callback(ch, method, properties, body):
    print(" [x] Received %r" % body)


channel.basic_consume(
    queue='hello', on_message_callback=callback, auto_ack=True)

print(' [*] Waiting for messages. To exit press CTRL+C')
channel.start_consuming()

