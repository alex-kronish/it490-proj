# IT490 Database VM


## The Database machine is set up to listen to the following vhosts on rabbitmq:

 - authentication: this vhost is populated with messages by the front end relating to user account authentication, handling both registration and login
 - match_history: this vhost is populated with messages by the front end relating to match history and leaderboards, both returning and gathering the match history
 - central_logs: any other machine in the setup can use this. This takes the log messages and stores them in the database. No response is returned to the front-end.

## The database machine is set up to publish to the following vhosts:

 - authentication_result: publishes a message to the front-end that
   registration was a success or failure, or that login was a success or
   failure. This will return the steam64 id on successful login as this
   is required for the API calls and must be stored.
 - match_history_result: publishes messages indicating insert success,
   or the output of the database query to fetch history

## The database machine has 3 python scripts that should be running:

 - UserAuth.py - listens for, and publishes responses related to user authentication requests
 - MatchHistory.py - listens for, and publishes responses related to recording or displaying match history between two steam users
 - EventLogs.py - listens for logging messages and writes them to the database. Any VM can write to this vhost, including the DB machine itself.

If any python script recieves a message it does not understand (this is denoted by the "operation" key-value pair in requests to the machine) then it will log an error that the message was not understood, and return the same operation back to the other machines with an "error" key and note that the message was not understood.

## Backups
There is a script that can be run on-demand or scheduled with a cron job that will take a backup of the database and dump it to a *.sql file. This is located in the /database_code/_backups/ folder. It will automatically append a timestamp to the end of the file name.

> Written with [StackEdit](https://stackedit.io/).

