<?php
include '/var/www/html/enkargo/config/aws_item.php';
require_once '/var/www/html/enkargo/config/pdo_connector.php';
/*#
Function Name: Update massive o AWS items from AWS service
Author: Ana Guere
Date: 13/07/2017
Detail: This funtion get aws_item.php functionsto connect to AWS source and get all item detail about
#*/

$aws  = new amazonManager('AKIAJYFFGK3OUHCTTD2Q','H988c7di0ORaS4UwyO76bFFcAKHgWuq/4zAcONvs','Tobon90-20');	
$conn = new DataBase();
$k    = 1;

#********************************************************* Log options *********************************************************
$array = array();

#Sentencia SQL para busquedas de sku
#$search_index = array("All", "Usb", "Wine", "Wireless", "ArtsAndCrafts", "Miscellaneous", "Electronics", "Jewelry", "Photo", "Shoes", "Automotive", "Vehicles", "Pantry", "MusicalInstruments", "DigitalMusic", "FashionBaby", "FashionGirls", "HomeGarden", "FashionWomen", "VideoGames", "FashionMen", "Kitchen", "Video", "Software", "Beauty", "Grocery", "FashionBoys", "Industrial", "PetSupplies", "OfficeProducts", "Watches", "Luggage", "OutdoorLiving", "Toys", "SportingGoods", "PCHardware", "Collectibles", "Handmade", "Fashion", "Tools", "Baby", "Apparel", "Appliances", "LawnAndGarden", "WirelessAccessories", "Blended", "HealthPersonalCare", "Classical", "Sport", "Luxury", "fashion", "Men", "Woman", "Boy", "Girl", "Black", "White", "Collections", "Multi-Function", "Digital", "Analog", "Accessories");


$palabras=array("Baebody","Nyx","Dildo","3dRose","A&T Designs","ARTIX","artspoint","Avidlove","Balakie","Boss Hog","Brisco Brands","CafePress","CaylayBrady","ChrisBroadhurst","Customized Girl","DaJun","Debbie's Designs","Dikoum","DIYthinker","eyeselect","FJCases","FUNNYSHIRTS.ORG","Generic","Health Solution Prime","Hollywood Thread","Hot Spot","I Dethrone","INDIGOS UG","Karma t shirts","kwanjai shop","Leo Brown","LQQGXL","Marcaus Paint Co","MG Poster","Molandra Products","MooLuxe","Nevissbags","Oil Paintings Canvas Prints","OILP","Photo posters","Posters USA","RichardGallery","Sandy","Sex Weights and Protein Shakes Premium","Sh-yolada","SHORE TRENDZ","Siam Circus Adults","Style Hanger","superkrit","The Night's Watch","TooLoud","Torrent Town","Trojan","SEX SEX SEX","Adam & Eve","Blush Novelties","CalExotics","California Exotic Novelties","CALIFORNIA EXOTIC NOVELTIES","Classic Erotica","Doc Johnson","Eden Pleasure Products","FeiGu","Fetish Fantasy","Fleshlight","Golden Triangle","Hott Products","K-Y","kwanjai shop","LeLuv","LifeStyles","Love and Vibes","Master Series","MASTER SERIES","MooLuxe","Nasstoys","NS Novelties","O Yes","Oxballs","Peach-jp","Pipedream","Pipedream Products","PUMPKIN CARRIAGE","Raycity","RESTART®","Sandy","Screaming O","Sex Toys Online Store","SexFlesh","Sh-yolada","SI Novelties","Siam Circus Adults","Strict Leather","superkrit","System JO","Tenga","ThinkMax","Thinkmax","Topco","Trinity Vibes","Trojan","UTIMI","VSP","Wet","YiFeng","3dRose","Accoutrements","Alician","ALPI","Application","Aquarius","Back to 20s","Beistle","BESTOYARD","Big Dot of Happiness","CafePress","Crayola","Customized Girl","DC Comics","Dikoum","Disney","Emotionlin","ensky","Forum Novelties","FUNNYSHIRTS.ORG","GAMAGO","Generic","Gold Sin","Good-Looking Corpse button badges and stickers","Géneric","Hott Place","Kaishihui","Keel Toys","Kheper Games","Kid Fun","Kroo","kwanjai shop","Meijiafei","Merrick Mint","MFKS Games","MG Poster","PickYourImage","Ridley's","Rosy Women","Sesame Street","Sh-yolada","Skull Splitter Dice","spell,magic ritual","Spin Master","Studio Ghibli","Super Goddess","superkrit","Tattoodrucker","TINKSKY","Tong Yue","Vicwin-One","Ogx","Vibrator","Sexual toy","Admire My Skin","AmazonBasics","Aquaphor","Aveeno","Aztec Secret","Baebody","Beauty by Earth","Better Shea Butter","Bio-Oil","Bioré","BodyGlide","Burts Bees","CeraVe","Cosmedica Skincare","COSRX","Cottonelle","Dove","Downy","Dr Teals","Dr. Bronners","Edens Semilla","Elizavecca","EltaMD","humane","JPNK","KRASR","Leven Rose","LilyAna Naturals","Majestic Pure","Mario Badescu","Millennium Tanning Products","Moroccanoil","NaturSense","Neutrogena","Nexcare","NYX","Olay","Oleavine","Proactiv","PURA DOR","Pure Biology","Purely Northwest","Radha Beauty","Shea Moisture","SHILLS","St. Ives","Tan Physics","TAYTHI","Tree of Life Beauty","TruSkin Naturals","Vanicream","Almay","Ardell","AsaVea","Aveeno","Bare Escentuals","Beauty Glazed","Beauty Glzaed","Benefit Cosmetics","Bestland","blinc","CICI Beauty","COVERGIRL","DHC","DUO","e.l.f.","e.l.f. Cosmetics","Elizabeth Mott","Godefroy","Hairgenics","It Cosmetics","Jerzees","KESEE","Kose","LOreal Paris","L.A. Girl","Lamora","LipSense","Majestic Pure","Maybelline New York","Milani","Millennium Tanning Products","Neutrogena","NYX","OCuSOFT","Physicians Formula","PleasingCare","Pur Minerals","Pure Biology","Pure Body Naturals","Rapidbrow","RevitaLash Cosmetics","Revlon","Rimmel","Simply Naked Beauty","Sky Organics","STS","Style Edit","theBalm","Too Faced","UCANBE","UV Glow","AMERICAN CREW","Andis","Aria Starr Beauty","ArtNaturals","Arvazallia","Batiste","Baxter of California","Biolage","BioSilk","Braun","Cetaphil Baby","Denman","DevaCurl","DORCO","DuraComfort Essentials","eBoot","ELACUCOS","Fanola","HSI PROFESSIONAL","Invisibobble","Its a 10","Its a 10 Haircare","Just for Men","Kenra","Kiloline","Kirkland Signature","Majestic Pure","Maple Holistics","MiroPure","Moroccanoil","Nioxin","Nizoral","ORIBE","Paul Mitchell","PURA DOR","Pure Biology","Pureology","Remington","Revlon","Rioa","Rogaine","Scalpmaster","Scunci","Shea Moisture","Suavecito","TIGI Cosmetics","Tolco","Tropic Isle Living","Ultrax Labs","Viva Naturals","Wahl","Active Wow","Alayna","Aoremon","Arm & Hammer","AuraGlow","Baltic Wonder","Biotène","Braun","Brightdeal","Colgate","Crest","Dental Duty","Dental Expert","DentalCare Labs","DenTek","Dr Song","Dr. Tungs","Equinox International","FineVine","Glide","H2ofloss","Hello Oral Care","Jordan","Listerine","MAGINOVO","Majestic Pure","Nimbus Microfine","Orahealth","Orajel","Oral B","OralShine","Parodontax","Pearl Enterprises","Philips","Philips Sonicare","Plackers","PlatypusCo.","Plax","Retainer Brite","Sensodyne","SENSODYNE PRONAMEL","Sonifresh","Sonimart","SoulGenie","Spry","Sunshine","Sunstar","The First Years","TheraBreath","Toms of Maine","Twin Lotus","Artizen","ArtNaturals","Bath & Body Works","BAXIA TECHNOLOGY","Clinique","Davidoff","Dolce & Gabbana","Dolcé & Gabbaná","Dolcé & Gabbána","doTERRA","Erligpowht","Essential Oil Labs","Essentially KateS","Fabulous Frannie","First Botany Cosmeceuticals","Guy Laroche","Healing Solutions","InnoGear","InstaNatural","Jerzees","Kis OilS","Lagunamoon","Leknes","Luscreal","Majestic Pure","Maple Holistics","Mary Tylor Naturals","Natrogix","Natural Riches","Natures Approach","Neloodony","NOW Foods","OliveTech","P&J Trading","Plant Guru","Plant Therapy","Plant Therapy Essential Oils","Pure","PURSONIC","Pursonic","Radha Beauty","RawChemistry","Sky Organics","Soft digits","SOLIGT","Sun Organic","URPOWER","venu","Versace","Versacé","Zen Breeze","Amope","Andis","Aveeno","Aztec Secret","baKblade","Bar5F","BEAKEY","Braun","BS-MALL","BTYMS","Clarisonic","Compound W","Docolor","DuraComfort Essentials","EcoTools","EmaxDesign","ETEREAUTY","For Pro","Fromm","Gillette","Gillette Venus","GILLÈTTE","Honest Amish","Kirkland Signature","KRASR","LOreal Paris","Lifestance","MY KONJAC SPONGE","NOW Foods","Nylea","Organyc","Panasonic","Philips Norelco","PrettyCare","Q-Tips","Aofeite","Aofit","Aptoco","ATB","BeltPC","Briggs","ECYC","Eforstore","Emma Ya","EUBUY","ewinever","Extreme Fit","FOOTLOOSE","Fsing","GOGO","HBpanda","Healsmile","Homax","Homyl","ieasysexy","JERN","JORZILANO","JZL","LifeShop","MagiDeal","MALLCROWN","MyProSupports","NACOLA","Ober","ORIONE","Panda Superstore","PigPig Healthy","ROSENICE","ShapEager","shopidea","SODIAL","SODIAL","support 81","Tcare","ThinkMax","Thinkmax","ToneWear","Tonus Elast","TOOGOO","TOROS-GROUP","uxcell","UZZO","VitaZon","Wonder Care","YK Care","ZJchao","4GEAR","AidBrace","Albolene","ARMSTRONG AMERIKA","AZMED","Babo Care","Baby Foot","Blitzu","Bodymate","BraceUP","Bracoo","BSN Medical","Carpal Tunnel Wrist Brace","Compressions","Copper Compression","Copper Fit","Crucial Compression","Dr. Arthritis","EXOUS","EzyFit","Futuro","Hampton Adams","Hip Mall","IPOW","Kinesio","Kootek","KT Tape","Liomor","Mars","McDavid","Med Spec","Mueller","MUSETECH","OPTP","Perfotek","Pfit","Physix Gear Sport","Pro Band Sports","ProCare","Profoot","ProStretch","Rocktape","ROOCKE","Sable","Sparthos","Sports Research","Tomight","Ultra Flex Athletics","Venom Sports Fitness","VIVE","Winzone","Abcstore99","Artlalic","Auch","AuroraX","AUSHEN","AutumnFall","BestMassage","Beyoung","bulk buys","C.X.Z","Canserin","CLEAVAGE CONTROL CLIP","Clinians","CYCTECH®","DENADADANCE","Denman","DevLon NorthWest","Eight24hours","essie","European American Design","Fashionwu","Foot Petals","GABRIALLA","Generic","Gent House","GokuStore","HEALTH LINE MASSAGE PRODUCTS","HiiBaby","HomeTek USA","Homyl","HP95","Human Touch","Inada","Ivy","Jaguar","Lisa Colly","Living Libations","Lormay","lychee","Mirror","Nail Polish","niceeshop","North Coast Medical","OPTP","PigPig Healthy","Positive Posture","Pulla","SiamsShop","STCorps7","Ursexyly","WOLF","Ader Sporting Goods","AFT- aofit","AliMed","Aofeite","Aofit","AUG","Ausom","BeatChong","Bilt-Rite Mastex Health","CYCTECH®","DIYthinker","DODOING","Emma Ya","EUBUY","Extreme Fit","Fitness Maniac","FLA Orthopedics","Gaiam","Generic","Homax","HÖTER","IDS Home","ieasysexy","Insta Slim","IntelliSkin, LLC","JORZILANO","KIWI RATA","Lelinta","LifeShop","LODAY","MagiDeal","MyProSupports","OOFAY","OPTP","Panda Superstore","PigPig Healthy","POSMA","ROSENICE","Rosie","ShapEager","shopidea","Thermo Slim","ToneWear","Tonus Elast","TOOGOO","unbrand","Ursexyly","uxcell","Vinmin","VitaZon","ZJchao","Real Techniques","Remington","Revlon","Sallys Organics","Scalpmaster","Schick","Smooth Viking Beard Care","TAYTHI","Tend Skin","Tinkle","ToiletTree Products","TweezerGuru","Vivaplex","Wahl","Wet Brush","Wholesome Beauty","AMERICAN CREW","Andis","Arm & Hammer","AXE","Baxter of California","BIC","Biotène","Braun","CloSYS","Colgate","Crest","Davidoff","Degree Men","DenTek","Dial","Dove","Dr. Collins","Edge","ELLESYE","Feather","Fresh Balls","Gillette","GILLÈTTE","Glide","Got2b","Guy Laroche","Kirkland Signature","LOreal Paris","Listerine","MANGROOMER","Nair","Neutrogena","Nivea Men","Old Spice","Orahealth","Oral B","Panasonic","Paul Mitchell","Philips Norelco","Philips Sonicare","Proraso","Remington","Schick","Seki Edge","SENSODYNE PRONAMEL","Spinbrush","SweatBlock","TheraBreath","TIGI Cosmetics","Toms of Maine","Wahl","STILA","BURBERRY","LOCCITANE","CLARISONIC","MARIO BADESCU","CALVIN KLEIN","FOREO","JACK BLACK","JANE IREDALE","HUGO BOSS","TRIA BEAUTY","BEACHWAVER CO.","LAURA GELLER NEW YORK","PHYTO","CHI","NUFACE","MONTBLANC","TOPPIK","THE ART OF SHAVING","REVITALASH COSMETICS","ELIZABETH ARDEN","ELEMIS","ST. TROPEZ","T3 MICRO","BUTTER LONDON","LACOSTE","MUSTELA","XTREME LASHES","TOMMY BAHAMA","STRIVECTIN","ELCHIM","COLOR WOW","BORGHESE","JIMMY CHOO","PINK SUGAR","PHILIP B","SOLANO","RADICAL SKINCARE","MAKEUP ERASER","ILUMINAGE","BAXTER OF CALIFORNIA","JUICY COUTURE","PHILOSOPHY FOR MEN","SMITH & CULT","KENNETH COLE","GRANDE COSMETICS","SKYN ICELAND","VINCENT LONGO","MAMA MIO","37 ACTIVES","USLU AIRLINES","ORLANE PARIS","TRACIE MARTYN","SOLEIL TOUJOURS","ÊSHAVE","TASK ESSENTIAL","NUXE","JENU","MASQUEOLOGY","WILDFOX","AMOUAGE","COSTUME NATIONAL","NOODLE & BOO","LORAC","MACADAMIA PROFESSIONAL","MEANINGFUL BEAUTY","BROWFOOD","BOLIN WEBB","JILLIAN DEMPSEY","DERMASURI","COLORESCIENCE","BILLY JEALOUSY","LAFCO","BABY QUASAR","MAI COUTURE","TAN TOWEL","3LAB","NUBRILLIANCE","PATCHOLOGY","BOUCHERON","CALISTA","VENEFFECT","LANVIN","LIERAC","BELLA J.","ALORA AMBIANCE","GULSHA","MARVIS","COMPTOIR SUD PACIFIQUE","EMI JAY","JUDITH LEIBER","BLISS","JEAN PATOU","RAW SPIRIT FRAGRANCES","ILA","MAKE","LEONOR GREYL PARIS","CLEAN","TAMMY FENDER","CANE   AUSTIN","ANTICA FARMACISTA","BEAUTYRX BY DR. SCHULTZ","CATHERINE MALANDRINO","OSCAR BLANDI","MONTALE","MILA MOURSI","ARCHIPELAGO","KAHINA GIVING BEAUTY","AHAVA","MËNAJI","ANSR","ESSIE","NATURA BISSE","INDIE LEE","SCOTT BARNES","JUICE BEAUTY","SUPERSMILE","CLARKS BOTANICALS","MIO","ANTHONY","BIODERMA","EYEKO","CARON PARIS","BLOWPRO","KALE NATURALS","LAZY PERFECTION BY JENNY PATINKIN","SJÄL","ESCENTRIC MOLECULES","RITUALS","WRINKLEMD","JAPONESQUE","LUMABELLA","CRABTREE & EVELYN","LUMARX","DIEGO DALLA PALMA","LOLITA LEMPICKA","BLINC","LASHFOOD","DR. ALKAITIS","BRONZE BUFFER","SOMME INSTITUTE","LIGHTSTIM","MASON PEARSON","NURSE JAMIE HEALTHY SKIN SOLUTIONS","XEN-TAN","STEPHANIE JOHNSON","PRORASO","CARGO","GEORGIE BEAUTY","CLOUD NINE","THE BROWGAL","JINSOON","MOLTON BROWN","DDF","SABON","RODIAL","FRASCO MIRRORS","ERNO LASZLO","VITA LIBERATA","LVX","YU-BE","KARUNA","FATBOY HAIR","GORGEOUS COSMETICS","JULEP","HAIRMAX","OSMOTICS COSMECEUTICALS","VIE LUXE","SHAVEWORKS","LONDONTOWN","THEBALM","FHI HEAT","ETAT LIBRE DORANGE","PMD","LABORATOIRES FILORGA PARIS","ZWILLING J.A. HENCKELS","SCOTT-VINCENT BORBA","YOSH","NIA 24","SARA HAPP","MANCERA","HAMPTON SUN","AROMACHOLOGY","ZENSATION","TERVEER","SULTRA","PHACE BIOACTIVE","MENSCIENCE ANDROCEUTICALS","TALIKA","TENOVERTEN","RECIPE FOR MEN","JOUER","JULIETTE HAS A GUN","VINCE CAMUTO","GIANNA ROSE","LIFTLAB","ROCHAS","OVANDO","JAMES READ","JOSIE BY JOSIE NATORI","ENGLISH LAUNDRY","ZIRH","STACEY FRASCA","MANUKA DOCTOR","PERRICONE MD","SKIN&CO ROMA","THIRDMAN","HOMMAGE","JOHN VARVATOS","BE THE LIGHT NEW YORK BY PETRA NEMCOVA","HYDROPEPTIDE","JOHN ALLANS","BIOELEMENTS","MICHEL GERMAIN","RITA HAZAN","AROMATHERAPY ASSOCIATES","LORD & BERRY","PAYOT","SERGE NORMANT","GLOTRITION","CUVGET","THE REFINERY","BRIONI","COMING SOON","sidebike","car parts Toyota","car parts Nizan","car parts Jeep","car parts Chevrolet","car parts Ford","car parts Mazda","car parts Honda","car parts Dodge","car parts Renault","car parts Kia","car parts Volkswage","car parts Hyundai","car parts Suzuki","Coleman","Columbia","Raleigh","Osprey","Razor","Marmot","High sierra","Cicleops","Bell","Spyder","Allen","Swagtron","Intex","Pearl izumi","Thule","Camelbak","Sea Eagle","Oneill","Surftech","ABILicers"," Geval"," prAna"," PRANA"," FMS"," Outmate"," KAVU","Yakima","olar Bottle","BV","Blitzu","UShake","Malker","Ascher","Vibrelli","AYAMAYA","Base Camp","BASE CAMP","Basecamp","BeBeFun","Bell","Bern","Best Deals","Bingggooo","Black Clover","BTR","CCTRO","Crazy Mars","CycleAware","EVT","FMA","Fox Racing","GIORO","Giro","GoMax","Gomax","Gonex","HiCool","JBM international","Joovy","Kask","KaZAM","KINGBIKE","Krash","Lazer","Lixada","Micro Kickboard","Nickelodeon","Nutcase","Níckelodeon","POC","ProRider","PROTEC Original","Raskullz","Razor","Rázor","Schwinn","SG Dreamz","shuangjishan","TeamObsidian","Traverse","Triple Eight","Troy Lee Designs","Utopia Home","Vinciph","Wipeout","Activ Life","Balance Buddy","Bianchi","Bikeroo","Bodyguard","BV","Cinelli","Cloud-9","Crank Brothers","DAWAY","Domain Cycling","Fizik","FOX Factory","GoFriend","Imrider","INBIKE","Kissral","KMC","KMCA0","LEBOLIKE","Lizard Skins","Look","Look Cycle","Mpow","NIKE","ODIER","Origin8","Otium","Oumers","OUTERDO","Park Tool","Planet Bike","RaceFace","RAM MOUNTS","RAM Mounts","ReFaXi","RIDE IT","RockShox","Schwinn","Shimano","SoundPEATS","SRAM","SRAM0","Sunlite","TaoTronics","The Flying Wheels","Tilos","TOPCABIN","UShake","Velox"); 


$search_index = array("Appliances","ArtsAndCrafts","Automotive","Baby","Beauty","Blended","Books","Collectibles","Electronics","Fashion","FashionBaby","FashionBoys","FashionGirls","FashionMen","FashionWomen","GiftCards","Grocery","HealthPersonalCare","HomeGarden","Industrial","KindleStore","LawnAndGarden","Luggage","MP3Downloads","Magazines","Merchants","MobileApps","Movies","Music","MusicalInstruments","OfficeProducts","PCHardware","PetSupplies","Software","SportingGoods","Tools","Toys","UnboxVideo","VideoGames","Wine","Wireless");

$conn->close_con();
$j = 1;

for ($i = 0; $i < count($search_index); $i++) {
	
	$quantity = 0;
	foreach ($palabras as $k) {

		$keywords = trim($k);
		$sort='salesrank';
		if ($search_index[$i]=='All' || $search_index[$i]=='Blended' || $search_index[$i]=='Merchants' || $search_index[$i]=='Magazines' ){
			$sort='';
		}
		if ($search_index[$i]=='Luggage' || $search_index[$i]=='Fashion' || $search_index[$i]=='FashionBaby' || $search_index[$i]=='FashionBoys' || $search_index[$i]=='FashionGirls' || $search_index[$i]=='FashionMen' || $search_index[$i]=='FashionWomen' ){
			$sort='popularity-rank';
		}
		if ($search_index[$i]=='MobileApps' || $search_index[$i]=='Movies' || $search_index[$i]=='Wine' ){
			$sort='relevancerank';
		}

		$quantity = $aws->main_search($search_index[$i], $keywords, $sort);
		$array    = array();
		if ($quantity > 5) {
			$quantity = 5;
		}
		echo "begin transaction for ".$search_index[$i]." - ".$keywords." - quantity: ".$quantity."\n";
		$conn->beginTransaction();
		for ($y = 1; $y <= $quantity; $y++) {
			foreach ($aws->item_search($search_index[$i], $keywords, $y, $sort) as $aws_result) {
				$sku = strtoupper($aws_result['asin']);
				$key = $conn->prepare("select upper(sku) as sku from aws.items where sku = '".$sku."';");
				$key->execute();
				$key = $key->fetch();
				if (!isset($key[0])) {
					$conn->exec("insert into aws.items (sku, create_date,update_date) values ('".$sku."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."');");
					echo $j." \t- ".$sku." - insertado - ".date("Y-m-d H:i:s")."\n";
					$j++;
				}
			}
			sleep(1);
		}
		$conn->commit();
		echo "end commit\n";
		$conn->close_con();
	}
}
$conn->close_con();