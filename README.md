# salhud
Heads up display for Public Health data from the Department of Health

# Installation

Install [docker](http://docker.com) for your OS and [docker-compose](https://docs.docker.com/compose/install/).

Then run 
```bash
docker-compose up -d
docker exec salhud_app_1 /bin/bash setup-db.sh
```

Now visit localhost or your boot2docker's ip address in your browser.
