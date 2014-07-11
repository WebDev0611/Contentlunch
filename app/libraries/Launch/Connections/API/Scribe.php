<?php namespace Launch\Connections\API;

class ScribeAPI {
    private $_apiKey;
    private $_accountConnection;

    public function __construct(array $accountConnection) {
        $this->_accountConnection = $accountConnection;

        $config = Config::get('services.scribe');
        $this->_apiKey = $config['key'];
    }


    // API URLs
    function suggestionsURL() {
        return 'http://api.scribeseo.com/analysis/kw/suggestions?apikey='.$this->_apiKey;
    }
    function detailsURL() {
        return 'http://api.scribeseo.com/analysis/kw/detail?apikey='.$this->_apiKey;
    }
    function contentAnalysisURL() {
        return 'http://api.scribeseo.com/analysis/content?apikey='.$this->_apiKey;
    }
    function linkScoreURL() {
        return 'http://api.scribeseo.com/analysis/link?apikey='.$this->_apiKey.'&type=scr';
    }
    function linkExternalURL() {
        return 'http://api.scribeseo.com/analysis/link?apikey='.$this->_apiKey.'&type=ext';
    }
    function linkInternalURL() {
        return 'http://api.scribeseo.com/analysis/link?apikey='.$this->_apiKey.'&type=int';
    }
    function linkSocialURL() {
        return 'http://api.scribeseo.com/analysis/link?apikey='.$this->_apiKey.'&type=soc';
    }
    function userApiURL() {
        return 'http://api.scribeseo.com/membership/user/detail?apikey='.$this->_apiKey;
    }


    private function _request($url, $method, $postFields = null) {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($method == 'POST') curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));

        $result = curl_exec($ch);
        $headers = curl_getinfo($ch);
        return array(
            'success' => $result && $headers['http_code'] == 200,
            'response' => json_decode($result, true)
        );
    }

    public function keywordSuggestions($query) {
        $params = array('query' => $query);
        return $this->_request($this->suggestionsURL(), 'POST', $params);
    }


    public function details($query, $domain) {
        $params = array(
            'query'  => $query,
            'domain' => $domain
        );
        return $this->_request($this->detailsURL(), 'POST', $params);
    }

    public function contentAnalysis($htmlTitle, $htmlDescription, $htmlHeadline, $htmlBody, $domain, $targetedKeyword = null) {
        $params = array(
            'htmlTitle'       => urlencode($htmlTitle),
            'htmlDescription' => urlencode($htmlDescription),
            'htmlHeadline'    => urlencode($htmlHeadline),
            'htmlBody'        => urlencode($htmlBody),
            'domain'          => urlencode($domain)
        );

        if ($targetedKeyword) $params['targetedKeyword'] = urlencode($targetedKeyword);

        return $this->_request($this->contentAnalysisURL(), 'POST', $params);
    }

    function linkScore($query, $domain) {
        $params = array(
            'query'  => $query,
            'domain' => $domain
        );
        return $this->_request($this->linkScoreURL(), 'POST', $params);
    }

    function linkExternal($query, $domain) {
        $params = array(
            'query'  => $query,
            'domain' => $domain
        );
        return $this->_request($this->linkExternalURL(), 'POST', $params);
    }

    function linkInternal($query, $domain) {
        $params = array(
            'query'  => $query,
            'domain' => $domain
        );
        return $this->_request($this->linkInternalURL(), 'POST', $params);
    }

    function linkSocial($query, $domain) {
        $params = array(
            'query'  => $query,
            'domain' => $domain
        );
        return $this->_request($this->linkSocialURL(), 'POST', $params);
    }

    function userAPI(){
        return $this->_request($this->userApiURL(), 'GET');
    }

}

?>