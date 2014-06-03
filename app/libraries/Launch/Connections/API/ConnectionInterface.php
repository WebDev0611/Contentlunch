<?php namespace Launch\Connections\API;

interface Connection
{
    /**
     * Do whatever setup we need to set up the SDK to make
     * connections to the API
     * @param array $accountConnection Settings passed from the database
     */
    public function __construct(array $accountConnection);

    /**
     * Returns a list of friends/connections/followers
     * @return array List of friends/connections/followers
     */
    public function getFriends($page = 0, $perPage = 1000);

    // probably needs more
    /**
     * Post the provided content to the connections
     * @param  string $content The content to publish
     * @return Response        200 on success, an error from ConnectionConnector::responseError on failure
     */
    public function postContent($content);

    /**
     * Send a direct message to the IDs passed in with the provided message data
     * @param  array  $ids       Array of IDs of recipients
     * @param  array  $message   Array of message details [subject, body]
     * @param  int    $contentID ID of the content to associate the guest with
     * @return Response        200 on success, an error from ConnectionConnector::responseError on failure
     */
    public function sendDirectMessage(array $friends, array $message, $contentID, $contentType);
}
