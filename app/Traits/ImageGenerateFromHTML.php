<?php

namespace App\Traits;

trait ImageGenerateFromHTML
{
    private function createImage($certificate){

        // https://htmlcsstoimage.com/ -- get api credential from this site
        //User ID: d6852a71-bb55-47c6-8e58-e08acbc0d393
        //API Key: 52a9f073-5945-4ab5-a4cc-d000a10e156e

$base_url = url('/');
$html = <<<EOD
<h2>Hello</h2>
EOD;

$css = <<<EOD
.certificate-frame{
  padding: 2px;
}
EOD;

$google_fonts = "Roboto";

        $data = array('html'=>$html,
            'css'=>$css,
            'google_fonts'=>$google_fonts);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://hcti.io/v1/image");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_POST, 1);
// Retrieve your user_id and api_key from https://htmlcsstoimage.com/dashboard
        curl_setopt($ch, CURLOPT_USERPWD, "d6852a71-bb55-47c6-8e58-e08acbc0d393" . ":" . "52a9f073-5945-4ab5-a4cc-d000a10e156e");

        $headers = array();
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        $res = json_decode($result,true);
        return $res['url'];
    }
}
