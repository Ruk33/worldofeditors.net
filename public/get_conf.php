<?php

if (($_GET["key"] ?? '') !== "SOME-PASSWORD") {
    header('HTTP/1.0 403 Forbidden');
    die("nok");
}

$client_priv = trim(shell_exec("wg genkey"));
$client_pub = trim(shell_exec("echo " . escapeshellarg($client_priv) . " | wg pubkey"));
$psk = trim(shell_exec("wg genpsk"));

$player_id = insert("vpn_client", []) + 10;

$base_ip_long = ip2long("10.7.0.0");
$client_ip = long2ip($base_ip_long + $player_id);

$pub_arg = escapeshellarg($client_pub);
$ip_arg  = escapeshellarg($client_ip . "/32");
$psk_val = escapeshellarg($psk);

$cmd = "echo $psk_val | wg set wg0 peer $pub_arg allowed-ips $ip_arg preshared-key /dev/stdin";
shell_exec($cmd);

$server_pub = trim(shell_exec("wg show wg0 public-key"));
$server_endpoint = "SERVER_IP:51820";

$conf = "[Interface]
PrivateKey = $client_priv
Address = $client_ip/22

[Peer]
PublicKey = $server_pub
PresharedKey = $psk
Endpoint = $server_endpoint
AllowedIPs = 10.7.0.0/22
PersistentKeepalive = 25";

// make this new change permanent
shell_exec("wg showconf wg0 > /etc/wireguard/wg0.conf");

header("X-Robots-Tag: noindex, nofollow");
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="woe.conf"');
echo $conf;
