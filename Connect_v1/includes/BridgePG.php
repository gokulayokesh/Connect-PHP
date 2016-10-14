<?php
include 'phpseclib/vendor/autoload.php'; class BridgePG { private $_public_key_file; private $_private_key_file; public function __construct() { $this->_public_key_file = __DIR__ . '/res/public_ic.key'; $this->_private_key_file = __DIR__ . '/res/private_ic.key'; } public function set_keys_from_provider($sp577608) { $sp6c8dd4 = ''; if ($sp577608 == 'icici') { $sp6c8dd4 = 'icici'; } $this->_public_key_file = __DIR__ . '/res/' . $sp577608 . '/public_ic.key'; $this->_private_key_file = __DIR__ . '/res/' . $sp577608 . '/private_ic.key'; } private function sp973816($spe4916d) { $sp5099f4 = substr($spe4916d, strlen($spe4916d) - 1, 1); $sp2e0d64 = ord($sp5099f4); if ($sp2e0d64 > 0 && $sp2e0d64 <= 16) { $sp1e0622 = strlen($spe4916d) - $sp2e0d64; $sp84365a = substr($spe4916d, 0, $sp1e0622); return $sp84365a; } return $spe4916d; } private function sp6099a5($sp73a98b, $spcdf756 = 16) { $sp3fc4e1 = $spcdf756 - strlen($sp73a98b) % $spcdf756; return $sp73a98b . str_repeat(chr($sp3fc4e1), $sp3fc4e1); } private function sp912b8b($sp7e7865 = 'This 999666 is!') { $spbfa33b = new \phpseclib\Crypt\RSA(); $spbfa33b->setHash('sha256'); $spbfa33b->setSignatureMode(\phpseclib\Crypt\RSA::SIGNATURE_PKCS1); $spbfa33b->loadKey(file_get_contents($this->_private_key_file)); $sp013ae6 = $spbfa33b->sign($sp7e7865); return base64_encode($sp013ae6); } private function sp0f025a($spb5544c, $sp013ae6) { $spbfa33b = new \phpseclib\Crypt\RSA(); $spbfa33b->setHash('sha256'); $spbfa33b->setSignatureMode(\phpseclib\Crypt\RSA::SIGNATURE_PKCS1); $spbfa33b->loadKey(file_get_contents($this->_public_key_file)); $spaf9e0a = FALSE; $spaf9e0a = @$spbfa33b->verify($spb5544c, $sp013ae6); return $spaf9e0a; } private function spc4ea20($sp7e7865 = 'This 999666 is!') { $spbfa33b = new \phpseclib\Crypt\RSA(); $spbfa33b->setHash('sha1'); $spbfa33b->setMGFHash('sha1'); $spbfa33b->setEncryptionMode(\phpseclib\Crypt\RSA::ENCRYPTION_OAEP); $spbfa33b->loadKey(file_get_contents($this->_public_key_file)); $sp013ae6 = $spbfa33b->sign($sp7e7865); $sp3427d1 = $spbfa33b->encrypt($sp7e7865); return base64_encode($sp3427d1); } private function spfc2485($sp3427d1) { set_time_limit(0); $spbfa33b = new \phpseclib\Crypt\RSA(); $spbfa33b->setHash('sha1'); $spbfa33b->setMGFHash('sha1'); $spbfa33b->setEncryptionMode(\phpseclib\Crypt\RSA::ENCRYPTION_OAEP); $spbfa33b->loadKey(file_get_contents($this->_private_key_file)); $sp7e7865 = ''; try { $sp7e7865 = $spbfa33b->decrypt(base64_decode($sp3427d1)); } catch (Exception $sp7dee82) { } return base64_encode($sp7e7865); } private function sp22abf9($sp7e7865 = 'This 999666 is!', $spe62e22 = '12345678901234567890123456789012') { $spc89692 = '0000000000000000'; $sp0057b4 = $this->sp6099a5($sp7e7865); $sp7e7865 = $sp0057b4; $spe4916d = @mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $spe62e22, $sp7e7865, MCRYPT_MODE_CBC, $spc89692); return base64_encode($spe4916d); } private function spd3cf90($sp3427d1, $spe62e22 = '12345678901234567890123456789012') { $spc89692 = '0000000000000000'; $spe4916d = @mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $spe62e22, base64_decode($sp3427d1), MCRYPT_MODE_CBC, $spc89692); $sp50efcd = $this->sp973816($spe4916d); $spe4916d = $sp50efcd; return base64_encode($spe4916d); } private function spf14f7e($spe7daaf, $sp65fbd9) { $spa18502 = base64_decode($sp65fbd9) . '' . base64_decode($spe7daaf); return base64_encode($spa18502); } private function sp5b9594($spfc0c2b) { $sp323750 = base64_decode($spfc0c2b); $sp55fe03 = substr($sp323750, 128, strlen($sp323750) - 128); return base64_encode(substr(base64_decode($spfc0c2b), 0, 128)); } private function sp914823($spfc0c2b) { $sp323750 = base64_decode($spfc0c2b); $sp55fe03 = substr($sp323750, 128, strlen($sp323750) - 128); return base64_encode($sp55fe03); } public function encrypt_message_for_wallet($sp0ff88d, $spb65ff7 = TRUE) { return $this->sp7345a2($sp0ff88d, $spb65ff7); } private function sp7345a2($sp0ff88d, $spb65ff7 = TRUE) { $sp8f25e8 = ''; $sp6a412a = $this->sp912b8b($sp0ff88d); $sp227a07 = $sp6a412a; if ($spb65ff7) { $sp227a07 = rawurlencode($sp6a412a); } $spa186ba = $sp0ff88d . 'checksum=' . $sp227a07; $sp53b784 = $this->spdd508e(); $sp9bcfea = $this->sp22abf9($spa186ba, $sp53b784); $spc6889c = $this->spc4ea20($sp53b784); $sp8f25e8 = $this->spf14f7e($sp9bcfea, $spc6889c); if ($spb65ff7) { $sp8f25e8 = rawurlencode($sp8f25e8); } return $sp8f25e8; } public function decrypt_wallet_message($sp8f25e8, &$sp0ff88d, $spb65ff7 = TRUE, $spcff5bc = TRUE) { return $this->sp0c5b71($sp8f25e8, $sp0ff88d, $spb65ff7, $spcff5bc); } private function sp0c5b71($sp8f25e8, &$sp0ff88d, $spb65ff7 = TRUE, $spcff5bc = TRUE) { $sp11cbf3 = $sp8f25e8; if ($spb65ff7) { $sp11cbf3 = rawurldecode(urldecode($sp11cbf3)); } $sp16347e = $this->sp5b9594($sp11cbf3); $sp769de9 = $this->sp914823($sp11cbf3); $spaf5e39 = $this->spfc2485($sp16347e); $sp4dbddc = $this->spd3cf90($sp769de9, base64_decode($spaf5e39)); $spa186ba = base64_decode($sp4dbddc); $sp1c9531 = 'checksum='; $sp0ff88d = substr($spa186ba, 0, strpos($spa186ba, $sp1c9531)); $sp95d109 = false; if ($spcff5bc) { $sp316ff3 = substr($spa186ba, strpos($spa186ba, $sp1c9531) + strlen($sp1c9531)); if ($spb65ff7) { $sp316ff3 = rawurldecode(urldecode($sp316ff3)); } $sp95d109 = $this->sp0f025a($sp0ff88d, base64_decode($sp316ff3)); } return $sp95d109; } private function sp114542($sp0ae21e, $sp3b4d7b) { $sp42599b = $sp3b4d7b - $sp0ae21e; if ($sp42599b < 1) { return $sp0ae21e; } $sp1f559f = ceil(log($sp42599b, 2)); $spd1e9b3 = (int) ($sp1f559f / 8) + 1; $sp3bb890 = (int) $sp1f559f + 1; $sp33c47b = (int) (1 << $sp3bb890) - 1; do { $sp4043f5 = hexdec(bin2hex(openssl_random_pseudo_bytes($spd1e9b3))); $sp4043f5 = $sp4043f5 & $sp33c47b; } while ($sp4043f5 >= $sp42599b); return $sp0ae21e + $sp4043f5; } private function spdd508e() { return $this->spfd1d6a(128 / 4); } private function spfd1d6a($spc1fef3) { $sp1065e1 = ''; $sp2438a8 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; $sp2438a8 .= 'abcdefghijklmnopqrstuvwxyz'; $sp2438a8 .= '0123456789'; $sp3b4d7b = strlen($sp2438a8) - 1; for ($sp00741f = 0; $sp00741f < $spc1fef3; $sp00741f++) { $sp1065e1 .= $sp2438a8[$this->sp114542(0, $sp3b4d7b)]; } return $sp1065e1; } public function ping() { echo 'PONG!!'; } public function rsa_decrypt($sp3427d1, $sp0c93be) { set_time_limit(0); $spbfa33b = new \phpseclib\Crypt\RSA(); $spbfa33b->setHash('sha1'); $spbfa33b->setMGFHash('sha1'); $spbfa33b->setEncryptionMode(\phpseclib\Crypt\RSA::ENCRYPTION_PKCS1); $spbfa33b->loadKey($sp0c93be); $sp7e7865 = ''; try { $sp7e7865 = $spbfa33b->decrypt(base64_decode($sp3427d1)); } catch (Exception $sp7dee82) { } return base64_encode($sp7e7865); } }
