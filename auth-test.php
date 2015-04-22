<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Sorry, you have to log in.';
    exit;
} else {
    $binderUsername = $_SERVER['PHP_AUTH_USER'];
    $binderPassword = $_SERVER['PHP_AUTH_PW'];
    $context = stream_context_create(array(
    'http' => array(
        'header'  => "Authorization: Basic " . base64_encode("$binderUsername:$binderPassword")
    )
));

$binderURL = 'http://drmc.museum.moma.org/api/aips/12b99b5b-7149-4e23-8c4a-1e5c6d35a9e0';
$binder_header = @get_headers($binderURL, false, $context);
if ($binder_header[0] == 'HTTP/1.1 200 OK') {
    $bindergood = True;
    $binderEndpoint = file_get_contents($binderURL, false, $context);
    $binderjson = json_decode($binderEndpoint, true);
    $binderstatus = $binderjson;
}
else {
    $bindergood = False;
    $binderstatus = $binder_header[0];
};

echo $binderstatus;


}
