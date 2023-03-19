<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mediacp_response.php';

/**
 * MediaCP API
 *
 * @link https://www.mediacp.net MediaCP
 */
class MediacpApi
{
    ##
    # EDIT REQUIRED Update the below API url or replace it with an appropriate module row variable
    ##
    /**
     * @var string The API URL
     */
    private $apiUrl = '';

    /**
     * @var bool Placeholder description
     */
    private $usessl;

    /**
     * @var string Placeholder description
     */
    private $apikey;
    ##
    # EDIT REQUIRED Update the above variable descriptions
    ##

    // The data sent with the last request served by this API
    private $lastRequest = [];

    /**
     * Initializes the request parameter
     *
     * @param string $hostname Placeholder description
     * @param string $port Placeholder description
     * @param string $usessl Placeholder description
     * @param string $apikey Placeholder description
     */
    ##
    # EDIT REQUIRED Update the above variable descriptions and parameter list below
    ##
    public function __construct($hostname,$port,$usessl,$apikey)
    {
        $this->apiUrl = ($usessl ? 'https://' : 'http://') . $hostname . ':' . $port;
        $this->apikey = $apikey;
    }

    /**
     * Send an API request to Mediacp
     *
     * @param string $route The path to the API method
     * @param array $body The data to be sent
     * @param string $method Data transfer method (POST, GET, PUT, DELETE)
     * @return MediacpResponse
     */
    public function apiRequest($route, array $body = [], $method = 'GET')
    {
        $url = $this->apiUrl . '/' . $route;
        $curl = curl_init();

        switch (strtoupper($method)) {
            case 'DELETE':
                // Set data using get parameters
            case 'GET':
                $url .= empty($body) ? '' : '?' . http_build_query($body);
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                // Use the default behavior to set data fields
            default:
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($body));
                break;
        }

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);

        $headers = [
            'Accept: application/json',
            "Authorization: Bearer {$this->apikey}"
        ];

        ##
        #  Set any neccessary headers here
        ##
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $this->lastRequest = ['content' => $body, 'headers' => $headers];
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            $error = [
                'error' => 'Curl Error',
                'message' => curl_error($curl),
                'status' => 500
            ];
            return new MediacpResponse(['content' => json_encode($error), 'headers' => []]);
        }
        curl_close($curl);

        if ( curl_getinfo($curl, CURLINFO_HTTP_CODE) == 401 ){
            return new MediacpResponse(['content' => json_encode([
                'error' => 'Authentication Error',
                'message' => "The provided API key for the server is not valid.<br /><br />Refer to <a href='https://www.mediacp.net/doc/admin-server-manual/billing/blesta-integration-guide/'>module documentation</a> for more information."
            ]), 'headers' => []]);
        }


        $data = explode("\n", $result);

        // Return request response
        return new MediacpResponse([
            'content' => $data[count($data) - 1],
            'headers' => array_splice($data, 0, count($data) - 1)]
        );
    }

//    The above apiRequest() method is publically accessible and can be used for any necessary requests
//    but it is often useful to define helper functions like the one below for convenience
//
//    /**
//     * Fetch customer info from MediaCP
//     *
//     * @param string $email The email by which to identify the customer and use for login
//     * @return MediacpResponse
//     */
//    public function getUser($email)
//    {
//        return $this->apiRequest('customer/list', ['customers' => [$email]], 'POST');
//    }
}
