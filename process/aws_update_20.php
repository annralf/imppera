<?php
include '/var/www/html/enkargo/services/aws_update.php';
$update_var = new aws_update("AKIAIHXHQKG5ZBHAWKOQ","68YvA/bNX937ahKA35C0vlgVZi2cQ0L/Oa081d/2","santiespi2000-20");
$conn = new Database();

$conn->close_con();

$hora_actual = strtotime(date("H:i"));
$hora_limite = strtotime( "23:00" );

while ($hora_actual < $hora_limite) { 

	$update_var->execute_update("select sku from  aws.items where sku in ('B06X6HGKMD',
'B074GNG2J5',
'B00LGZ8ELK',
'B0110NI2WS',
'B06WP79YXG',
'B01ANWY514',
'B00F575N1E',
'B00TVICV8Q',
'B06XNJL8WM',
'B00HUZYNGA',
'B07B6JRBL4',
'B01BH5GGQS',
'B00K2C5U74',
'B01EBPVU4O',
'B071WH83T2',
'B017UK34G4',
'B01LXRWL0T',
'B00UV37SG0',
'B01F6VS3ZG',
'1484774191',
'B06VW46VR4',
'B01M368408',
'B06XXRKXPK',
'B011EBNORE',
'B06XJJ2PQB',
'B0186EM5WC',
'B017DFJXN4',
'B01COGM5YQ',
'B07423Y7Z4',
'B01EHSMIB4',
'B001O1M6II',
'B074PP3H5G',
'B01HTKIO3I',
'B07BC7BMHY',
'B01H57VZ22',
'B00JVGOS6Q',
'B01LW94UBH',
'B071R5CLRD',
'B018XG0M2I',
'B0739ZPDQ8',
'B06WVL8PH5',
'B01MSWCTDO',
'B06ZZRPHJ1',
'B073B25BJZ',
'B010RZEZY4',
'B01LDL14WC',
'B0002DUQDQ',
'B017JMD8YG',
'B01LYSLG82',
'B01HKKH7I0',
'B01N0QP8E0',
'B0762JWZLG',
'B00898SRSM',
'B00FRFS6PO',
'B009L6MA3Y',
'B071FLBB1R',
'B01N20PROC',
'B00ON4F7SE',
'B016PFQTQC',
'B076V921CT',
'B01BEB4TY6',
'B06XD5W4RY',
'B00EV5DB7E',
'B01EIHGND8',
'B00VS987JI',
'B009VUPIKM',
'B078MF6YR7',
'B071Z3HS3M',
'B01ITQYCP0',
'B01MYD6PRW',
'B076MBMYZ8',
'B073JJBMQX',
'B06XWYFK3J',
'B071DWY8RL',
'B01ATZO8R6',
'B0108U507Y',
'B016M9KLMO',
'B01NBVHN5Y',
'B00A2UQUXY',
'B01M2319VR',
'B079TZZZ36',
'B013LKNSFE',
'B015AA4H44',
'B005QKNUOW',
'B000JQZ7G2',
'B01IOY3IAC',
'B074JLGH56',
'B071FZHX75',
'B00OA1B32O',
'B06XJCWP3G',
'B01N5JB1ZU',
'B0711KWHFL',
'B01E5D4N14',
'B00BZFUL9E',
'B0164IAA42',
'B06Y6MCMYG',
'B00CTSVDPW',
'B071JHR2MY',
'B06XW5NQDD',
'B06XW5HKC9',
'B00N2SUC1Y',
'B0187HZODK',
'B002ODISD2',
'B079KZMTZR',
'B015JRP4W2',
'B01KKRJCGK',
'B01LZERC2W',
'B0734TJY69',
'B01MDQZ97N',
'B01B50L4YO',
'B00A297L3I',
'B00N8DOAWA',
'B00YF0KUDS',
'B01GLYBTP8',
'B01EGN8EXQ',
'B01L6SSCTK',
'B00DFUTY1I',
'B01DDO6PVM',
'B00DTOFGKI',
'B00NIDA43Y',
'B004C2TPB2',
'B004Z2NSRQ',
'B00IRP67MK',
'B000HROXOA',
'B0038LX8WU',
'B00ER2TI5A',
'B0078K39SA',
'B0721VTS88',
'B00ZR0XHH6',
'B075VLWW7K',
'B01GNVF8S8',
'B0064OG4PQ',
'B0739QD3WM',
'B01IG7CLWI',
'B00N1XCA9M',
'B01F9LIFA6',
'B01M3YXWLB',
'B071WBVTT2',
'B017B8FNUK',
'B000VOCQPW',
'B015OR9R8O',
'B072R5X8KN',
'B0036Q7MV0',
'B076599WGK',
'B01BKWJ86I',
'B079L65QD1',
'B01HUJ3HGM',
'B01K6V6VBE',
'B004J0ZGYM',
'B01HSZUQWQ',
'B011D3EVKM',
'B00F31IIRI',
'B01N0SFTJY',
'B00BFAOW6W',
'B01CQN4W8Y',
'B01LWNKMIA',
'B015WNP5MC',
'B0017LZBPK',
'B00NXQQ9Z8',
'B00TO5UT6M',
'B076BMH33G',
'B06W55PKSJ',
'B01J0RW05G',
'B01M66N9VY',
'B01M30J0K3',
'B01M088Q22',
'B06XCF7QFR',
'B01NCL7G3U',
'B073GX4XWW',
'B00CGSLHI8',
'B073QH76W7',
'B01N0BCN9B',
'B019SVEO4E',
'B071JSCPWG',
'B013HEU0SC',
'B076BYR5MQ',
'B01J0RWHRW',
'B00JJIOJ7E',
'B06XS8TWXP',
'B00JHCF4B2',
'B00UTIFCVA',
'B00XU2NOK8',
'B010N3I4HY',
'B071VZXSPC',
'B01N7P8CGK',
'B00OIM7H70',
'B072K7YWTJ',
'B01KO4DYV8',
'B016RB87TG',
'B01M6A77MD',
'B07D1K1Z8K',
'B072NCDDN4',
'B003PGE98K',
'B01BTBU4P4',
'B01LMOHYO2',
'B005HWAUOM',
'B01IMQAVDY',
'B01DOPSIVA',
'B01N645WL9',
'B010OMOEVO',
'B01NCYDSG2',
'B07335T1VW',
'B019VJ85CA',
'B005CMSLJ8',
'B0010BQB6A',
'B019NWA2GM',
'B01J7EJWWQ',
'B017QSUAYE',
'B01LEA89C0',
'B00PJL88U4',
'B0070Z7KME',
'B000WQJKM6',
'B00VOE1R58',
'B06XH9G144',
'B01MY15BZ3',
'B06ZXSRCK7',
'B00UNPH8LG');","massive");

	$hora_actual = strtotime(date("H:i"));
	$conn->close_con();
}

echo "hora limite de proceso alcanzada\n";
$conn->close_con();