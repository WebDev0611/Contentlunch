#!/bin/bash

sudo mv ../supervisor-programs/*.conf /etc/supervisor.d/
sudo supervisorctl reload