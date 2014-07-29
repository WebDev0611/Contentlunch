<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class HubspotAPI extends AbstractConnection {

  protected $configKey = 'services.hubspot';

  protected $base_url = 'https://api.hubapi.com';

  protected function getClient()
  {
    if ( ! $this->client) {
      $token = $this->getAccessToken();
      $this->client = new Client([
        'base_url' => $this->base_url,
        'defaults' => [
          'config' => [
            'curl' => [
              CURLOPT_SSL_VERIFYPEER => false
            ]
          ],
        ]
      ]);
    }
    return $this->client;
  }

  public function getAccessToken()
  {
    if (empty($this->accountConnection['settings']['token'])) {
      throw new \Exception("Invalid connection");
    }
    $token = $this->accountConnection['settings']['token'];
    return $token;
  }

  public function getIdentifier()
  {
    return null;
  }

  public function getAuthors()
  {
    $client = $this->getClient();
    $token = $this->getAccessToken();
    $response = $client->get('content/api/v2/blog-authors?access_token=' . $token);
    $info = $response->json();
    if ($info) {
      return $info['objects'];
    }
    return null;
  }

  public function getBlogs()
  {
    $client = $this->getClient();
    $token = $this->getAccessToken();
    $response = $client->get('content/api/v2/blogs?access_token=' . $token);
    $info = $response->json();
    return $info;
  }

  public function getTemplates()
  {
    $client = $this->getClient();
    $token = $this->getAccessToken();
    $response = $client->get('content/api/v2/templates?access_token='. $token);
    $info = $response->json();
    $templates = [];
    if ( ! empty($info['objects'])) {
      foreach ($info['objects'] as $key => $template) {
        $templates[] = [
          'id' => $template['id'],
          'label' => $template['label'],
          'path' => $template['path'],
          'template_type' => $template['template_type'],
          'folder' => $template['folder'],
          'type' => $template['type'],
          'category_id' => $template['category_id']
        ];
      }
    }
    return $templates;
  }

  /**
   * @see http://developers.hubspot.com/docs/methods/blogv2/post_blog_posts
   */
  public function postBlog($content)
  {
    $client = $this->getClient();
    $response = ['success' => false, 'response' => []];
    try {
      $token = $this->getAccessToken();
      $blogs = $this->getBlogs();
      $blogID = $blogs['objects'][0]['id'];
      $blogAuthorID = \Input::get('author_id');
      $apiResponse = $client->post('content/api/v2/blog-posts?access_token='. $token, [
        'body' => json_encode([
          'name' => strip_tags($content->title),
          'post_body' => $content->body,
          'content_group_id' => $blogID,
          'blog_author_id' => $blogAuthorID,
          'meta_description' => $content->meta_description,
          'meta_keywords' => $content->meta_keywords,
          'is_draft' => 'true'
        ])
      ]);
      $response['success'] = true;
      $response['response'] = $apiResponse->json();
    } catch (\Exception $e) {
      $response['error'] = $e->getMessage();
    }
    return $response;
  }

  public function postEmailTemplate($content)
  {
    $client = $this->getClient();
    $response = ['success' => false, 'response' => []];
    try {
      $token = $this->getAccessToken();
      // 1 = landing page, 2 = email, 3 = site page
      $categoryId = 2;
      $apiResponse = $client->post('content/api/v2/templates?access_token='. $token, [
        'body' => json_encode([
          'label' => $content->title,
          'category_id' => $categoryId,
          'folder' => 'launch',
          'template_type' => 2,
          'path' => 'custom/email/launch/'. strtolower(str_replace(' ', '-', $content->title)) .'.html',
          'source' => $content->body
        ])
      ]);
      $response['success'] = true;
      $response['response'] = $apiResponse->json();
    } catch (\Exception $e) {
      $response['error'] = $e->getMessage();
    }
    return $response;
  }

  public function postLandingPage($content)
  {
    $client = $this->getClient();
    $response = ['success' => false, 'response' => []];
    try {
      $token = $this->getAccessToken();
      $apiResponse = $client->post('content/api/v2/pages?access_token='. $token, [
        'body' => json_encode([
          'html_title' => $content->title,
          'name' => $content->title,
          'template_path' => 'hubspot_default/landing_page/basic_with_form/2_col_form_left.html',
          'widgets' => [
            'right_column' => [
              'body' => [
                'html' => $content->body
              ],
              'smart_type' => 0,
              'name' => 'right_column',
              'smart_objects' => []
            ]
          ],
          'is_draft' => true,
          'meta_description' => $content->meta_description,
          'meta_keywords' => $content->meta_keywords,
          'subcategory' => 'landing_page',
        ])
      ]);
      $response['success'] = true;
      $response['response'] = $apiResponse->json();
    } catch (\Exception $e) {
      $response['error'] = $e->getMessage();
    }
    return $response;
  }

  public function postSitePage($content)
  {
    $client = $this->getClient();
    $response = ['success' => false, 'response' => []];
    try {
      $token = $this->getAccessToken();
      $apiResponse = $client->post('content/api/v2/pages?access_token='. $token, [
        'body' => json_encode([
          'html_title' => $content->title,
          'name' => $content->title,
          'template_path' => 'hubspot_default/landing_page/basic_with_form/2_col_form_left.html',
          'widgets' => [
            'right_column' => [
              'body' => [
                'html' => $content->body
              ],
              'smart_type' => 0,
              'name' => 'right_column',
              'smart_objects' => []
            ]
          ],
          'is_draft' => true,
          'meta_description' => $content->meta_description,
          'meta_keywords' => $content->meta_keywords,
          'subcategory' => 'site_page',
        ])
      ]);
      $response['success'] = true;
      $response['response'] = $apiResponse->json();
    } catch (\Exception $e) {
      $response['error'] = $e->getMessage();
    }
    return $response;
  }

  public function postContent($content)
  {
    // The frontend is setup to allow posting to hubspot
    // if the base_type is long_html, email, or blog_post
    // 
    $key = $content->content_type()->first()->key;
    switch ($key) {
      case 'blog-post':
        return $this->postBlog($content);
      break;
      case 'email':
      case 'workflow-email':
        return $this->postEmailTemplate($content);
      break;
      case 'landing-page':
        return $this->postLandingPage($content);
      break;
      case 'website-page':
        return $this->postSitePage($content);
      break;
    }
  }

}