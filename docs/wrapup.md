Hello future tech guy.  I was the last tech guy.  Here are some parting words that may or may not make your life easier.

# Docs

In this directory:
 accounts.md - some notes I took while working through accounts and subscriptions info
 server-environment.md - quick primer on what I set up through elastic beanstalk
 todo.txt - my personal todo list that I was making as I went along

In Slack:
 I wrote up a recap every week.  Scroll up, read those, they have everything I had been working on in any given week listed out.


# Vagrant

Micah did a great job of getting this in a better place.  One addition that I did was configuring remote debugging, but I left in my ip address hard-coded in the ini files, that'll need to be changed.


# Plans

I had planned a few big things.

First, I had planned on porting the client over to typescript and upgrading to a newer version of angular during the redesign implementation.  The port is started with gulp tasks supporting a dual-language environment until it's all done, but only a few classes were actually ported so there's not much value gained yet.

I was moving user uploaded file storage off the webservers (since they're essentially disposable in EB) and onto Amazon S3.

I hadn't touched the queueing or scheduled jobs yet.

As far as I can tell, there has been fairly little testing on the content connections and you'd do well not to assume any work.  I know Linked In is completely borked because they changed their API (it may not even be possible to do what we want anymore).  I did get wordpress minimally working, and my guess is many of the simple OAuth2 connections will work now.


# Some suggestions:

Security was and still is my biggest concern.  From simple things like strong passwords on third party accounts, through more complicated things like assuring all code paths are properly secured.  I would highly recommend not assuming anything on this front.  Things are all over the place.

It seems the last developer didn't understand what a database transaction is, and I won't be surprised if data across different tables stops making internal sense because of it.
