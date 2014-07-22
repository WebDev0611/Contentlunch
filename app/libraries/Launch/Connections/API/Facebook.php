<?php namespace Launch\Connections\API;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Illuminate\Support\Facades\Config;

class FacebookAPI extends AbstractConnection {

  protected $configKey = 'services.facebook';

  protected function getClient()
  {
    if ( ! $this->client) {
      FacebookSession::setDefaultApplication($this->config['key'], $this->config['secret']);
      $this->client = new FacebookSession($this->getAccessToken());
    }
    return $this->client;
  }

  public function getIdentifier()
  {
    return null;
  }

  /**
   * Post content to the facebook connection as a status message
   * Strip html tags
   * @see: https://developers.facebook.com/docs/graph-api/reference/v2.0/user/feed
   * @todo: Support for pages, groups
   * @todo: Add attachments?
   * @todo: Return response
   */
  public function postContent($content) {
    // No html allowed here
    $message = strip_tags($content->body);
    $session = $this->getClient();
    $response = ['success' => true, 'response' => []];
    try {
      // /me translates to user_id of the person or page_id of the page
      // that the access token is mapped to
      $post = (new FacebookRequest($session, 'POST', '/me/feed', [
          'message' => $message
        ]))
        ->execute()
        ->getGraphObject(GraphUser::className());
      $response['response'] = $post->asArray();
    } catch (FacebookRequestException $e) {
      $response['success'] = false;
      $response['response'] = $e->getResponse();
      $response['error'] = $e->getMessage();
    } catch (\Exception $e) {
      // Error 
      $response['success'] = false;
      $response['response'] = [];
      $response['error'] = $e->getMessage();
    }
    return $response;
  }

}