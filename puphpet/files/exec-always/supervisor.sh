#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
sudo cp -a "$DIR/../supervisor-programs/." /etc/supervisor.d/
sudo supervisorctl reload
