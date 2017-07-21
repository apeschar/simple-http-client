<?php

namespace KiboIT\SimpleHTTP;

/**
 * @author Milko Kosturkov
 */
class SimpleHTTPClient {

    private $baseURL;

    public function setBaseURL($url) {
        $this->baseURL = $url;
    }

    public function get($url) {
        return $this->doRequest('GET', $url);
    }

    public function head($url) {
        return $this->doRequest('HEAD', $url);
    }

    public function post($url, $data) {
        return $this->doRequest('POST', $url, $data);
    }

    public function put($url, $data) {
        return $this->doRequest('PUT', $url, $data);
    }

    public function delete($url) {
        return $this->doRequest('DELETE', $url);
    }

    private function doRequest($method, $url, $body = null) {
        if (isset ($this->baseURL)) {
            $url = $this->baseURL . $url;
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (!is_null($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        $response = curl_exec($ch);
        if ($response === false) {
            $error = sprintf("(%s) %s", curl_errno($ch), curl_error($ch));
            throw new SimpleHTTPException("cURL error: $error");
        } else {
            $result = ['code' => curl_getinfo($ch, CURLINFO_HTTP_CODE), 'body' => $response];
        }
        curl_close($ch);
        return $result;
    }
}
