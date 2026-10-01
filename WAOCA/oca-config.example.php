<?php
// Konfigurasi OCA Telkom. Jangan isi token di GitHub.
// Isi langsung di server production atau gunakan environment variable.

$OCA_URL = 'https://wa01.ocatelkom.co.id/api/v2/push/message';
$OCA_TOKEN = getenv('OCA_WHATSAPP_TOKEN') ?: '';
?>