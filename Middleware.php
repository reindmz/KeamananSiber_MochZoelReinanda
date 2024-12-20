<?php
class Middleware {
    private $webServiceUrl = "http://localhost/WSInventaris/Server.php";
    private $rateLimit = 100; // Jumlah maksimum permintaan per menit
    private $requestLogs = [];

    /**
     * Handle the incoming request from the client.
     *
     * @param array $clientRequest Request in JSON format.
     * @return string Response to the client in JSON format.
     */
    public function handleRequest($clientRequest) {
        $this->checkRateLimit();
        $this->validateRequest($clientRequest);

        // Convert JSON request to SOAP XML
        $soapRequest = $this->jsonToSoap($clientRequest);

        // Send SOAP request to the web service
        $response = $this->sendSoapRequest($soapRequest);

        return $response;
    }

    /**
     * Validate the incoming client request.
     *
     * @param array $request The client request.
     * @throws Exception If the request is invalid.
     */
    private function validateRequest($request) {
        if (empty($request['action']) || empty($request['data'])) {
            throw new Exception("Request tidak valid. Harap sertakan 'action' dan 'data'.");
        }

        // Tambahkan validasi lainnya sesuai kebutuhan
        // Contoh: Validasi format data tertentu
        if (!is_array($request['data'])) {
            throw new Exception("Format 'data' harus berupa array.");
        }
    }

    /**
     * Implementasi rate limiting untuk API.
     */
    private function checkRateLimit() {
        $currentTime = time();
        $this->requestLogs = array_filter($this->requestLogs, function($timestamp) use ($currentTime) {
            return ($currentTime - $timestamp) <= 60; // Hanya simpan log dalam 1 menit terakhir
        });

        if (count($this->requestLogs) >= $this->rateLimit) {
            throw new Exception("Rate limit tercapai. Harap coba lagi nanti.");
        }

        $this->requestLogs[] = $currentTime;
    }

    /**
     * Convert JSON request to SOAP XML.
     */
    private function jsonToSoap($json) {
        // Implementasi konversi JSON ke SOAP XML
        return "<soapRequest>...</soapRequest>"; // Contoh placeholder
    }

    /**
     * Send SOAP request to the web service.
     */
    private function sendSoapRequest($soapRequest) {
        // Implementasi pengiriman request SOAP
        return "<soapResponse>...</soapResponse>"; // Contoh placeholder
    }
}
?>
