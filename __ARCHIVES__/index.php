<?php

/** apt-get install php5-gmp

/**
	1 = 1
	2 = 2
	3 = 4
	4 = 8
	5 = 16
	6 = 32
	7 = 64
	8 = 128
	9 = 256
	10 = 512
	11 = 1024
	12 = 2048
	13 = 4096
	14 = 8192
	15 = 16384
	16 = 32768
	17 = 65536
	18 = 131072
	19 = 262144
	20 = 524288
	21 = 1048576
	22 = 2097152
	23 = 4194304
	24 = 8388608
	25 = 16777216
	26 = 33554432
	27 = 67108864
	28 = 134217728
	29 = 268435456
	30 = 536870912
	31 = 1073741824
	32 = 2147483648

**/

const BIG_ENDIAN = 1;
const LITTLE_ENDIAN = 2;
const BI_ENDIAN = 4;
const MIDDLE_ENDIAN = 8;

const BINARY_SIZE = 32;
const BINARY_ENDIANNESS = LITTLE_ENDIAN;


function binaryShift($bin, $offset, $way){
	/** Convertis la variable en entrée en Hexadécimal **/
	$hex = bin2hex($bin);
	
	/** Il faut que celle-ci soit paire - On complète la chaine hexa **/
	if(strlen($hex) % 2){
		$hex = "0".$hex;
	}
	
	/** Calculer la taille binaire de l'entrée **/
	$src_size = strlen($hex);
	$oct_size = $src_size / 2;
	$bit_size = $oct_size * 8;
	
	/** Convertion de la chaine en binaire (0 et 1) **/
	$binary = null;
	for($i = 0; $i < strlen($hex); $i += 2){
		$dec = hexdec(substr($hex, $i, 2));
		
		$bitbinary = sprintf('%08s', decbin($dec));
		$binary .= $bitbinary;
	}
	
	/** Ajouter autant de 0 que vaut l'offset fourni **/
	$fill = null;
	for($f = 0; $f < $offset; $f++){
		$fill .= '0';
	}
	
	/** Ajouter le complément du côté souhaité **/
	// On ajout le nombre de zéro voulu
	// On tronque la chaine à la taille mémorisé
	// Le valeur tronqué sont perdu (c'est normal)
	$binary_shift = null;
	switch(strtolower($way)){
		case 'right':
			$binary_shift = $fill.$binary;
			$binary_shift = substr($binary_shift, 0, $bit_size);
		break;
		case 'left':
			$binary_shift = $binary.$fill;
			$binary_shift = substr($binary_shift, $offset);
		break;
	}
	
	
	/** Restituation au format Hexadécimal **/
	$shift_hex = null;
	for($b = 0; $b < strlen($binary_shift); $b += 8){
		$shift_hex .= dechex(bindec(substr($binary_shift, $b, 8)));
	}
	
	/** Si la chaine restitué est plus courte que celle recu, alors ajouter des zéro **/
	if(strlen($shift_hex) < $src_size){
		$missing = $src_size - strlen($shift_hex);
		
		while($missing){
			$shift_hex = '0'.$shift_hex;
			
			$missing--;
		}
	}
	
	return hex2bin($shift_hex);
}

function endian_reverse($binary, $size=null){
	$hex = bin2hex($binary);
	
	/** Compléter la chaine pour qu'elle soit paire **/
	if(strlen($hex) % 2){
		$hex = "0".$hex;
	}
	
	$little_endian = implode('', array_reverse(str_split($hex, 2)));
	
	return hex2bin($little_endian);
}

function binval($binary){
	
}

function valbin($binary){
	
}

function urshift($n, $s) {
    return ($n >= 0) ? ($n >> $s) :
        (($n & 0x7fffffff) >> $s) | 
            (0x40000000 >> ($s - 1));
} 


/** --------------------------------- **/
/** --- Fonction Diffusée (Alias) --- **/
/** --------------------------------- **/
/** Bitiwise Shift Right  **/
function binshr($bin, $offset){
	return binaryShift($bin, $offset, 'right');
}

/** Bitwise Shift Left **/
function binshl($bin, $offset){
	return binaryShift($bin, $offset, 'left');
}

/**

	48 f5 11 01
	
	72 245 17 1
	
	01001000 11110101 00010001 00000001
	
	00100100 01111010 10001000 10000000
	
	00100100 01111010 10001000 10000000
	
	
	00100000 > 00010000
	00100000 > 00100000

**/
	
	// Déclaration des constantes
	const XOR_BITMAP = 0x55555555;
	const TAB_MULTIP = 39916801; // 0x02611501

	// Ouverture du fichier player.gdc
	$file = fopen('player.gdc', 'rb');

	// Aller à la fin du fichier pour compter la longueur
	fseek($file, 0, SEEK_END);// 0 par rapport a la fin

	// Player.gdc = 10197octet = file_length = 10197
	$file_length = ftell($file);

	// Remise du pointeur au début
	fseek($file, 0, SEEK_SET); // 0 par rapport au début

	/** --- Lecture de la clé --- **/
	// Version 1.0.0.5 
	$buffer = fread($file, 4); // "�DT" soit "1d a0 44 54" en hexa big-endian (conform à c++)
	$buffer = unpack('Vk', $buffer); // uint32_t little-endian key

	$k = $buffer['k']; // 1413783581 = 54 44 a0 1d

	echo "8 first octect retrieved as §k = $k <br />";

	$k ^= XOR_BITMAP; // 17954120 = 01 11 f5 48

	echo "§key ^ XOR_BITMAP = $k<br/><br/>";

	$key = $k;
	$table = Array();


	// LOOP_001 : 0x0111f548 >> 1 = 0x0088faa4 * 0x02611501 = 0x7d3c6ea4
	// LOOP_002 : 0x7d3c6ea4 >> 1 = 0x3e9e3752 * 0x02611501 = 0xd339F152
	// LOOP_003 : 0xd339F152 >> 1 = 0x699cf8a9 * 0x02611501 = 0xd339F152

	//
	//$k = $k >> 1;
	//$k *= TAB_MULTIP;
	//

	echo $k = hexdec(0xd339f152);

	//$gmpk = gmp_init();

	//$k = $k >> 1;

	
	
	//echo $k.' '.dechex($k);

	exit;

	for($i = 0; $i < 3; $i++){
		echo dechex($k).' >> 1 = ';
		
		$k = $k >> 1;
		
		echo dechex($k)." * 39916801 = ";
		
		$gmpk = gmp_init($k);
		$gmpm = gmp_init(TAB_MULTIP);
		
		$iLok = gmp_intval(gmp_mul($gmpk, $gmpm));
		$iHik = $k * TAB_MULTIP;
		
		$xLok = dechex($iLok);
		$xHik = dechex($iHik);
		
		$k = hexdec(preserveHi($xLok, $xHik, 1));
		echo dechex($k);
		
		echo "<br />";
	}

	function preserveHi($Lo, $Hi, $offset){
		$max_len = max(strlen($Lo), strlen($Hi));
		$loop = ($offset > $max_len) ? $max_len : $offset;
		
		$Lo = strval($Lo);
		$Hi = strval($Hi);
		
		for($i = ($max_len - 1); $i > ($max_len - $loop - 1); $i--){
			$Hi[$i] = $Lo[$i];
		}
		
		return $Hi;
	}

	//$key = $buffer['key'];

	//echo $buffer;

	//$k = endian_reverse($k);// 54 44 a0 1d en little_endian
	//
	//printf('0x%08X', $k);
	//
	//$k ^= hex2bin('55555555'); //01 11 f5 48 (conform à c++)

	//$data = unpack('VKey', $k);
	//
	//$key = $data['Key'];
	//
	//echo dechex($key);
	//echo "<br />";
	//
	//$t = $key >> 1;
	//
	//echo dechex($t);

	//echo $key;
	//echo dechex($key);

	//$key = $k;
	//$k = $k << ord(1);

	//echo bin2hex($k)."<br />";
	//echo "<br />";
	//$k = binshl($k, 31);

	//$t = binshl($k, 1);

	//echo bin2hex($t);

	//if(!preg_match('#^[0]+$#', bin2hex($t))){
	//	echo "n'est pas null";
	//} else {
	//	echo "est null";
	//}

	//echo bin2hex($k);
	//echo bin2hex($k);

	//$key = $k;
	//$table = Array();
	//
	//for($i = 0; $i < 4; $i++){
	//	echo bin2hex($k);
	//	
	//	if(!preg_match('#^[0]+$#', binshr($k, 1))){
	//		echo " SHR 1 ";
	//		$k = binshr($k, 1);
	//		echo bin2hex($k);
	//	} else if (!preg_match('#^[0]+$#', binshl($k, 31))){
	//		echo " SHL 31 ";
	//		$k = binshl($k, 31);
	//		echo bin2hex($k);
	//	}
	//	
	//	echo "<br />";
	//	
	//	$k = ($k >> 1) | ($k << 31);
	//	$k *= 39916801;
	//	$table[$i] = $k;
	//}
	//
	///** --- read Int --- **/
	//$v = fread($file, 4);
	//$ret = $v ^ $key;
	//
	////for($i = 0; $i < 4; $i++){
	////	$key ^= $table[];
	////}
	//
	//// Ferme le fichier

	// 1413783581 === 5444a01d
	// 
	// 84 68 160 29
	//
	// 1010100 1000100 10100000 11101

	fclose($file);
?>