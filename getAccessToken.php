<?php
function getAccessToken($clientId, $clientSecret, $apiUrl) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$apiUrl/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$clientSecret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Imprimir el código de estado HTTP y la respuesta para depuración
    echo "HTTP Code: $httpCode<br>";
    echo "Response: <pre>$response</pre>";

    $data = json_decode($response);
    if (isset($data->access_token)) {
        return $data->access_token;
    } else {
        die("Error: Access token not found in response.");
    }
}