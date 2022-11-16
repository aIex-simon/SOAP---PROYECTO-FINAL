<?php

namespace App\Http\Controllers\Soap;

use App\Http\Controllers\Controller;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SoapController extends Controller
{
    public function soap(Request $request) {
        $header = $request->header('Authorization', '');
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiODI2ZTY3MjU2ODY0ZDQwYjQ2ZTQ0ZDc2NDMxZDdkODBkZWE3M2QwMzgzNDkxMDg0YWFhNjU0ODUzNzJhMjAzOTVlY2FhNjViOTc1Y2RkMDMiLCJpYXQiOjE2NjgxMTkxMjEuNTQwMjgxLCJuYmYiOjE2NjgxMTkxMjEuNTQwMjg0LCJleHAiOjE2OTk2NTUxMjEuNTM1Mzg1LCJzdWIiOiIxNDgiLCJzY29wZXMiOltdfQ.Qa7EQMJdVK56McDVrzExLx6RgUMlU-rzjrQ-vCzoDIktS1nGZ56Its6dljLvnYZtHW-XOW9KiLmGBLrkyfueT-phgl2qDZHT9YAwgvKepjUzW3BWok4kIAx5x5wPUPb257ppTwGzXjE86W9TLxYmpTj6BWSIewfV5AjrP0j-q21964iLbjPXm0koVG917xG1JQPPot9Vjd4NsO2_oIdvA8S-ogXQ2SnWBbAuQl_4dYQQ0UQ2cf-VK6SscR-H6f0bqnjdlq_2nRHm9l0Hz6l6XpG-34D0Rj1F7FbEGXNkgFpXMQZIhSTSU25cwTFNtpIyhdrEj-tG8YvygorCPKBNLYoAQIRMlmndc1lwRwcWo67cy1Se1DMcZiXoPN9wO7HS2OAEfKjXcG2mEbdXZ7xcwq5gaby4QOXQzOASjuemHPlIfVOrpqeRTMeZyQyxhNLmyh0qJ7-h-UaQHd27eqksyeFqMOf_aFOcqKM2vx0CMFmTD5nXvqQlTFNxzWzZoWbdbeBJGgmxF3gl3Yt86ICEMh3ijOyWp8-sWIYQbxIF-rXPYd_fZIb17T9unJWcaprUhb_KkntqP4gyElS3Y92iEhAFhc9YfSf43DEwGIB3bA2RQkkS7ScVrQKN_LbN-0C5_UgOKlTK6EBqCPF-w31lDvGSENGuoCNVr9fFPfSCxfY';
        
        if (Str::substr($header, 7) !== $token) {
            return response()->json([
                'msg' => 'Token invalido'
            ], 401);
        }
    
        $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe"
                xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                <soapenv:Header>
                    <wsse:Security>
                        <wsse:UsernameToken>
                            <wsse:Username>OUSTINER</wsse:Username>
                            <wsse:Password>kaforktra</wsse:Password>
                        </wsse:UsernameToken>
                    </wsse:Security>
                </soapenv:Header>
                <soapenv:Body>
                    <ser:sendBill>
                        <fileName>20262825516-RR-20200317-00002.zip</fileName>
                        <contentFile>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</contentFile>
                    </ser:sendBill>
                </soapenv:Body>
            </soapenv:Envelope>';

        $headers = [
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: urn:sendBill",
            "Content-length: " . strlen($xml_post_string),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // converting
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($response === false) {
            throw new Exception(curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);

        return [
            $httpcode,
            $response,
        ];
   }
}
