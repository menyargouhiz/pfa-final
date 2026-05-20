<?php
$url = 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&q=80';
$headers = get_headers($url, 1);
if (!$headers) {
    echo "FAIL\n";
    return;
}
if (strpos($headers[0], '200') !== false) {
    echo "OK\n";
} else {
    echo "STATUS: " . $headers[0] . "\n";
}
