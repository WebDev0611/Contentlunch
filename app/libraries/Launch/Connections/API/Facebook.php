<?php namespace Launch\Connections\API;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Illuminate\Support\Facades\Config;

class FacebookAPI implements Connection
{

  private $accountConnection;
  private $fbConfig = [];

  public function __construct(array $accountConnection) {
    // Setup connection to the API (SDK ?)
    $this->accountConnection = $accountConnection;
    $this->fbConfig = Config::get('services.facebook');
    FacebookSession::setDefaultApplication($this->fbConfig['key'], $this->fbConfig['secret']);
  }

  public function getFriends($page = 0, $perPage = 1000) {
    // Not needed ? 
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
    $session = new FacebookSession($this->accountConnection['settings']['token']->getAccessToken());
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
      $response['response']['data'] = $e->getResponse();
      $response['response']['error'] = $e->getMessage();
    } catch (\Exception $e) {
      // Error 
      $response['success'] = false;
      $response['response']['error'] = $e->getMessage();
    }
    return $response;
  }

  public function sendDirectMessage(array $friends, array $message, $contentID, $contentType, $accountID) {
    // Not needed ?
  }

}