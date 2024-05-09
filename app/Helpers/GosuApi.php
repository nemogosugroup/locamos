<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Log;

class GosuApi
{
    protected $url;
    public function __construct()
    {        
        //$this->url = config('app.env') == 'local' ? 'http://localhost:27698/' : 'https://apis.gosu.vn/';
        $this->url = 'https://apis.gosu.vn/';
    }
    public function GosuCURL($url, $method = 'GET')
    {
        $ch = curl_init();

        if ($ch === false) {
            // handle initialization error
            return null;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        if ($method == 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        } else if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        }


        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            Log::channel('daily')->info(date("Y-m-d H:i:s") . "-" . curl_error($ch) . "- url: " . $url . " - server output " . json_decode($response) . "\n");
        }
        if (!curl_version()['features'] && CURL_VERSION_SSL) {
            Log::channel('daily')->info(date("Y-m-d H:i:s")." SSL is not supported with this cURL installation "."- url: ".$url." - server output ".json_decode($response)."\n");
        }

        curl_close($ch);

        return json_decode($response);
    }

    public function GosuGetData( $get_url, $params = null){
        try {
            $url = $this->url.$get_url;
            if(is_array($params)){
                $count = 0;
                foreach ($params as $key => $value) {    
                    if($count==0){
                        $url .='?'.$key.'='.$value;
                    }else{
                        $url .= '&'.$key.'='.$value;
                    }
                    $count++;
                }    
            }
            $data = $this->GosuCURL($url, 'GET');
            return $data;
        } catch (\Exception $e) {
            $results = array(
                'success'    => false,
                'status'     => 500,
                'mgs'        => $e->getMessage(),
            );
            return $results;
        }
    }
}