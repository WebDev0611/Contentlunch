<?php namespace Launch\Connections\API;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Illuminate\Support\Facades\Config;

/**
 * @see: https://developers.facebook.com/docs/graph-api/reference/v2.0/user/feed
 */
class FacebookAPI extends AbstractConnection {

  protected $configKey = 'services.facebook';

  protected $meData = null;

  protected function getClient()
  {
    if ( ! $this->client) {
      FacebookSession::setDefaultApplication($this->config['key'], $this->config['secret']);
      $this->client = new FacebookSession($this->getAccessToken());
    }
    return $this->client;
  }

  public function getMe()
  {
    if ( ! $this->meData) {
      $session = $this->getClient();
      $this->meData = (new FacebookRequest(
        $session, 'GET', '/me'
      ))->execute()->getGraphObject(GraphUser::className());
    }
    return $this->meData;
  }

  public function getUrl()
  {
    $info = $this->getMe();
    return $info->getProperty('link');
  }

  public function getIdentifier()
  {
    $info = $this->getMe();
    if ($info) {
      return $info->getName();
    }
  }

  /**
   * Post content to the facebook connection as a status message
   * Strip html tags
   *
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
      $params = [
        'message' => $message
      ];
      $upload = $content->upload()->first();
      if ($upload && $upload->media_type == 'image') {
        $params['picture'] = $upload->getUrl();
      }
      $post = (new FacebookRequest($session, 'POST', '/me/feed', $params))
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