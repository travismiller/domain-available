<?php

define('APP_DIR', __DIR__.'/..');

require_once APP_DIR.'/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(APP_DIR);
$dotenv->load();

function endsWith( $str, $sub ) {
   return ( substr( $str, strlen( $str ) - strlen( $sub ) ) === $sub );
}

$domain = isset($_GET['d'])
        ? trim(strtolower($_GET['d']))
        : '';

if ($domain) {
  $DOMAIN = strtoupper($domain);
  $result = trim(shell_exec(APP_DIR.'/bin/available '.escapeshellarg($domain)));
  $available = endsWith($result, 'AVAILABLE');
  $color = $available ? '009900' : '990000';
  $title = sprintf("$DOMAIN is %s", $available ? 'available!' : 'not available.');
  $canonical = sprintf("https://%s/?d=%s", getenv('BASE_URL'), $DOMAIN);
} else {
  $DOMAIN = 'Available?';
  $color = '000099';
  $title = 'Is that domain available?';
  $canonical = sprintf("https://%s/", getenv('BASE_URL'));
}

$img = "https://via.placeholder.com/350x150/$color/ffffff/?text=$DOMAIN";

?>
<html prefix="og: http://ogp.me/ns#">
<head>
<title><?php echo $title; ?></title>
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:description" content="See if that domain is available." />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo $canonical; ?>" />
<meta property="og:image" content="<?php echo $img; ?>" />
</head>

<body>
  <h1><?php echo $title; ?></h1>
  <p><img src="<?php echo $img; ?>"></p>
</body>

</html>
