<?php
function get_client_ip() {
  return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function email_domain($email) {
  $parts = explode('@', $email);
  return strtolower($parts[1] ?? '');
}

function is_temp_email($email) {
  $temps = ['mailinator.com','tempmail.com','sharklasers.com'];
  return in_array(email_domain($email), $temps);
}

function is_off_hours() {
  $h = (int)date('G');
  return ($h < 7 || $h > 23) ? 1 : 0;
}
?>
