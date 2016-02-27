# Database

Credentials are in commonkey.

It's replicated in two availability zones for redundancy, performs regular backups, and we have all the nice managed features Amazon gives us.

It's running MariaDB which is the open source MySql fork (Oracle bought MySql and essentially stopped working on it a few years ago, the MariaDB people picked it up to kept it going forward, think of it as a 10% faster MySql).

SSH through one of the servers to access it (sequel pro makes this easy)

# Redis

Redis server using Elasticache and servers are pointing to that.
As I talked with Micah, I plan on pointing the queue system to it instead of BeanstalkD (don't confuse with EB, totally different) but haven't tested that out yet so it's still todo.

# Elastic Beanstalk


## Environments

contentlaunch-prod
host: http://app.contentlaunch.com/
db: cl_prod
git branch: master
redis: contentlaunch-prod.i9zt5p.0001.use1.cache.amazonaws.com
redis cache prefix: cl


contentlaunch-dev
host: http://dev.contentlaunch.com/
db: cl_dev  [currently running a copy of cl_prod taken on 2/27/16]
git branch: development
redis: contentlaunch-prod.i9zt5p.0001.use1.cache.amazonaws.com (SAME AS PROD)
redis cache prefix: cldev

## EB Primer

To set up eb cli

http://docs.aws.amazon.com/elasticbeanstalk/latest/dg/eb-cli3-install.html

Set up your credentials
~/.elasticbeanstalk/aws_credential_file
AWSAccessKeyId=AKXXXXXXXXXXXXXXXXXXXXX
AWSSecretKey=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

To update an evironment

1. Checkout appropriate branch
2. Make changes
3. Commit changes
4. eb deploy

To check status

eb status

# Health Checks

http://dev.contentlaunch.com/status/db
Pings the database

http://dev.contentlaunch.com/status/redis
Pings the redis server

http://dev.contentlaunch.com/status/all
Pings both db and redis



# DNS

Currently registered at FastDomain
Using media temple dns servers that point at AWS resources

Target / TODO:
Transfer registration to AWS
Use AWS/Route53 dns servers

