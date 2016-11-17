<?php

namespace Modularity\Helper;

class Curl
{
    /**
     * Curl request
     * @param  string $type        Request type
     * @param  string $url         Request url
     * @param  array $data         Request data
     * @param  string $contentType Content type
     * @param  array $headers      Request headers
     * @return string              The request response
     */

    private static $cacheKey;

    public static function request($type, $url, $data = null, $contentType = 'json', $headers = null)
    {

        //Create cache key as a reference
        self::$cacheKey = self::createCacheKey($type, $url, $data, $contentType, $headers);

        //Return cached data
        if (self::getCachedResponse() !== false) {
            return self::getCachedResponse();
        }

        //Arguments are stored here
        $arguments = null;

        switch (strtoupper($type)) {
            /**
             * Method: GET
             */
            case 'GET':
                // Append $data as querystring to $url
                if (is_array($data)) {
                    $url .= '?' . http_build_query($data);
                }

                // Set curl options for GET
                $arguments = array(
                    CURLOPT_RETURNTRANSFER      => true,
                    CURLOPT_HEADER              => false,
                    CURLOPT_FOLLOWLOCATION      => true,
                    CURLOPT_SSL_VERIFYPEER      => false,
                    CURLOPT_SSL_VERIFYHOST      => false,
                    CURLOPT_URL                 => $url,
                    CURLOPT_CONNECTTIMEOUT_MS  => 1500
                );

                break;

            /**
             * Method: POST
             */
            case 'POST':
                // Set curl options for POST
                $arguments = array(
                    CURLOPT_RETURNTRANSFER      => 1,
                    CURLOPT_URL                 => $url,
                    CURLOPT_POST                => 1,
                    CURLOPT_HEADER              => false,
                    CURLOPT_POSTFIELDS          => http_build_query($data),
                    CURLOPT_CONNECTTIMEOUT_MS  => 3000
                );

                break;
        }

        /**
         * Set up headers if given
         */
        if ($headers) {
            $arguments[CURLOPT_HTTPHEADER] = $headers;
        }

        /**
         * Do the actual curl
         */
        $ch = curl_init();
        curl_setopt_array($ch, $arguments);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = curl_exec($ch);
        curl_close($ch);

        /**
         * Cache response
         */
        self::storeResponse($response);

        /**
         * Return the response
         */
        return $response;
    }

    /**
     * Create Cache key
     * @param  string $type        Request type
     * @param  string $url         Request url
     * @param  array $data         Request data
     * @param  string $contentType Content type
     * @param  array $headers      Request headers
     * @return string              The cache key
     */

    public static function createCacheKey($type, $url, $data = null, $contentType = 'json', $headers = null)
    {
        self::$cacheKey = "curl_cache_".md5($type.$url.(is_array($data) ? implode($data, "") : $data).$contentType.(is_array($headers) ? implode($headers, "") : $headers));
        return self::$cacheKey;
    }

    /**
     * Get cached response
     * @return string       The request response from cache
     */

    public static function getCachedResponse()
    {
        return get_transient(self::$cacheKey);
    }

    /**
     * Store response in cache
     * @param $response     Response to save in cache
     * @param $minutes      Number of minutes to cache response
     * @return string       The request response from cache
     */

    public static function storeResponse($response, $minutes = 15)
    {
        if (!empty($response) && !is_null($response)) {
            return set_transient(self::$cacheKey, $response, 60*$minutes);
        } else {
            return false;
        }
    }
}
