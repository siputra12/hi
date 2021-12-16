<?php
## Coded By : Tra
$passDefault = "Asdqwe123";
$defaultFile = "listNomor.txt";
echo "1. Ekstrak Nomor\n2. Jalankan Bot (Harus Sudah Ekstrak Nomor)\n?Pilih Menu	:	";
if(trim(fgets(STDIN)) == 1){
	InputNo:
		echo "?Input Nmr	:	";
		$nmr = trim(fgets(STDIN));
		if(substr($nmr, 0, 2) != 62){
			echo "**Nomor harus dengan 62, contoh : 6281234567890\n";
			goto InputNo;
		}
	$extension = array(".", "+");
	for($b=0;$b<2;$b++){
		$listNomor = execute("ext", $nmr, $extension[$b]);
		for($a=0;$a<count($listNomor);$a++){
			$no = $listNomor[$a];
			if(substr($no, 0, 2) != 62) continue;
			@file_put_contents($defaultFile, "$no\n", FILE_APPEND);
		}
	}
	echo "**Nomor Disimpan Di File $defaultFile\n";
}else{
	$listNomor = @explode("\n", file_get_contents($defaultFile));
	$bNmr = str_replace(array("+","."), "", $listNomor[0]);
	$count = count($listNomor);
	if(execute("cekServer") == "sibuk") exit("\nSorry Brow Sistem Sibuk!!!\n");
	echo "***********************\nBase Nomor	:	$bNmr\nJmlh Ekstrak	:	$count\n***********************\nLanjut(y/n)	:	";
	if(trim(fgets(STDIN)) == "y"){
		echo "?Input Ref	:	";
		$ref = trim(fgets(STDIN));
		for($a=0;$a<$count;$a++){
			if(execute("cekServer") == "sibuk") exit("\nSorry Brow Sistem Sibuk!!!\n");
			if($a>=1){
				echo "?Lanjut(y/n)	:	";
				if(trim(fgets(STDIN)) == "n"){
					echo "STOPPPEDD!!!";
					break;
				}
			}
			$num = $a+1;
			$bbbbbbbbbbbbbbbb = 2;
			$nope = $listNomor[$a];
			echo "\n#################### $num\n";
			echo "**Mengirim Sms Ke $nope";
			SendOtp:
				$cek = execute("sendSms3", $nope);
			if($cek == "success"){
				echo " Sukses\n";
				echo "?Input Otp	:	";
				$otp = trim(fgets(STDIN));
				$validasi = execute("verifyCode", $nope, $otp);
				$username = execute("randStr", 8, "", "", "", "", array("gd","y"));
				$reg = execute("register", $nope, $passDefault, $username, $ref);
				$code = "Gagal!";
				if(!empty($reg)){
					$code = $reg;
					delFromListFile($nope, $defaultFile, "\n");
				}
				echo "**Code : $code\n";
			}else{
				echo "\n**Kesalahan Saat Mengirim Code!\n";
				$bbbbbbbbbbbbbbb = 10;
				Loop:
					$bbbbbbbbbbbbb = $bbbbbbbbbbbbbbb;
					if($bbbbbbbbbbbbbbb < 10) $bbbbbbbbbbbbb = "0$bbbbbbbbbbbbbbb";
					echo "**Mengulang Dalam $bbbbbbbbbbbbb\r";
					$bbbbbbbbbbbbbbb -= 1;
					sleep(1);
					if($bbbbbbbbbbbbbbb >= 1) goto Loop;
				echo "**Mengirim Ulang Kode Sms ke $nope";
				$bbbbbbbbbbbbbbbb -= 1;
				if($bbbbbbbbbbbbbbbb <= 0){
					echo "\n**Gagal Kirim Otp 2x, Ganti Nomor\n";
					delFromListFile($nope, $defaultFile, "\n");
					continue;
				}else{
					goto SendOtp;
				}
			}
			echo "####################\n";
		}
	}else{
		echo "Berhenti.\n";
	}
}
function execute($cmd, $a = null, $b = null, $c = null, $d = null, $e = null, $g = null){
	$url = "https://botfor.fun/hi/hi_api.php?cmd=$cmd&a=$a&b=$b&c=$c&d=$d&e=$e";
	if($g != null) $url .= "&{$g[0]}={$g[1]}";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$headers = array();
	$headers[] = 'Host: botfor.fun';
	$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36';
	$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
	$headers[] = 'Accept-Language: en-US,en;q=0.9';
	$headers[] = 'Connection: close';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
function delFromListFile($str, $file, $pm){
	return @file_put_contents($file, str_replace($str.$pm, "", @file_get_contents($file)));
}
