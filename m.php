<?php  
error_reporting(0);
include 'curl.php';


function referral($ref) {
	$fake_name = curl('https://fakenametool.net/generator/random/id_ID/indonesia');
	preg_match_all('/<td>(.*?)<\/td>/s', $fake_name, $result);

	$name = $result[1][0];
	$alamat = $result[1][2];
	$base = ['0878', '0813', '0838', '0851', '0853'];
	$rand_base = array_rand($base);
	$number = $base[$rand_base].number(8);
	$domain = ['carpin.org', 'novaemail.com'];
	$rand = array_rand($domain);
	$email = str_replace(' ', '', strtolower($name)).'@'.$domain[$rand];
	$username = explode('@', $email);
	$password = random(8);



	$headers = [
		'Host: gm88k.com',
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0',
		'Accept: */*',
		'Accept-Language: id,en-US;q=0.7,en;q=0.3',
		'Connection: keep-alive',
		'X-Requested-With: XMLHttpRequest'
	];

	$register = curl('https://gm88k.com/index/user/do_register.html', 'user_name='.$username[0].'&tel='.$number.'&pwd='.$password.'&deposit_pwd='.$password.'&invite_code='.$ref, $headers);


	if (stripos($register, '"info":"Transaksi berhasil"')) {
		echo "\n[!] Try to create account\n";
		echo $data = '[!] Success create | '.$number." | ".$password."\n";
		$fh = fopen("result_asu.txt", "a");
		fwrite($fh, $data);
		fclose($fh);
		flush();

		echo "[!] Try to login\n";
		$login = curl('https://gm88k.com/index/user/do_login.html', 'tel='.$number.'&pwd='.$password.'&jizhu=1', $headers);
		$cookies = getcookies($login);

		if (stripos($login, '"info":"login berhasil!"')) {
			echo "[!] Login success\n";
			$headerx = [
				'Host: gm88k.com',
				'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0',
				'Accept: */*',
				'Accept-Language: id,en-US;q=0.7,en;q=0.3',
				'Connection: keep-alive',
				'Cookie: s9671eded='.$cookies['s9671eded'].'; tel='.$cookies['tel'].'; pwd='.$cookies['pwd'],
				'X-Requested-With: XMLHttpRequest'
			];

			$address = curl('https://gm88k.com/index/my/edit_address', 'area=id&address='.$alamat, $headerx);

			if (stripos($address, '"info":"Operasi berhasil"')) {

				for ($i = 0; $i < 30 ; $i++) {
					a:
					$add = curl('https://gm88k.com/index/rot_order/submit_order.html?cid=1&m=0.127095457810890'.number(2), null, $headerx);
					preg_match('/"oid":"(.*?)"/s', $add, $oid);


					$order_info = curl('https://gm88k.com/index/order/order_info', 'id='.$oid[1], $headerx);
					preg_match('/"add_id":(.*?),"/s', $order_info, $add_id);
					$do = curl('https://gm88k.com/index/order/do_order', 'oid='.$oid[1].'&add_id='.$add_id[1], $headerx);
					if (stripos($do, '"info":"Operasi berhasil!"')) {
						echo "[!] Transaksi sukses\n";
					} else {
						echo "[!] Transaksi gagal coba ulang\n";
						goto a;
					}

				}


			}

		} else {
			echo "[!] Login failed\n";
		}


	} else {
		echo "[!] Failed\n";
	}
}

echo '[?] Referral code : ';
$ref = trim(fgets(STDIN));

while (true) {
   referral($ref); 
}




?>