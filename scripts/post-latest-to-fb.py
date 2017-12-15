import datetime
import facebook


def smart_truncate(content="No Content", length=100, suffix='...'):
    #    if len(content) <= length:
    #        return content
    #    else:
    return ' '.join(content[:length + 1].split(' ')[0:-1]) + suffix


print("\nInitializing the Advanced Facebook Posting Engine" + " @ " + str(datetime.datetime.now()))

# request = requests.get('https://graph.facebook.com/oauth/access_token?%20client_id=313960255732232&client_secret=57dd0ce3498e4dac473ad74fd042d0d6&%20grant_type=fb_exchange_token&%20fb_exchange_token=EAAEdi5GEOggBAKHtVeg4GZAnB9ckImXDPkg0K7R6tLAI4a6H9FwzyZCilU9HtPqRXp4qWtz65uJIZBIH602Iqm8PMZBZAsSLRTanPfAdbPZA0j1rTkasbHhwvx7uYAUfRIe7pkX3aHYc2jVzLZBbZAhT0H0keFZAhUx9WZBIwVEzycqIrc9ZBQTpsPxuC5ZCxFZAPM3sZD')
#
# if request.status_code != 200:
#    print "Bombed out with no 200... " + str(request.status_code)
#    print request.content
#    exit(1)
# else:
#    token = request.json()['access_token']
#    graph = facebook.GraphAPI(access_token=token, version='2.7')

token = "EAAMbOen5kJQBADxozFy5ZAjdeJi0lgXjbnnZBGxd5zQ1shy9M4jPMgjRDnThWwVnzxS6Ox5bwW7B8medonZBhxLYrpUtmLZAXEPAIAuiLHDY2jzjZC4hufG5v4QzP5FV5vvGnzJVM3n7ZCjjTiSYDHEd8EXw5Tn9ZAI42nEUqQmZCgZDZD"
graph = facebook.GraphAPI(access_token=token, version='2.7')

print(str(graph))

attachment = {
    'name': "Testing",
    'link': "",
    'caption': "Just a test",
    'description': "Just describing a test",
    'picture': ""
}

print str(graph.put_wall_post(message="Testing", attachment=attachment, profile_id='1927410714186430')['id'])

#print str(graph.delete_object(id=''))

