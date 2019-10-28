# IT490 DMZ VM


## The DMZ machine is set up to listen to the following vhosts on rabbitmq:

 - api: The front-end machine will send requests to this vhost, indicating what parameters and api should be called to display appropriate information on the front-end
 - 
## The DMZ machine is set up to publish to the following vhosts:

 - api_response: After getting a response back from the API needed, the operation will be appended to the request and the message published to the vhost for the front end machine to consume.

## The DMZ machine has one python script that should be running

 - CallAPI.py - listens for API requests, and publishes API responses. The "operation" will indicate what steam, youtube, or twitch endpoint should be called.

If any python script recieves a message it does not understand (this is denoted by the "operation" key-value pair in requests to the machine) then it will log an error that the message was not understood, and return the same operation back to the other machines with an "error" key and note that the message was not understood.



> Written with [StackEdit](https://stackedit.io/).

