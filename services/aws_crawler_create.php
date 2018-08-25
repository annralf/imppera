<?php
include '/var/www/html/enkargo/config/aws_crawler.php';
require_once '/var/www/html/enkargo/config/pdo_connector.php';
/*#
Function Name: Update massive o AWS items from AWS service
Author: rafael alvarez
Date: 13/07/2018
Detail: This funtion get aws_item.php functionsto connect to AWS source and get all item detail about
#*/
$conn = new DataBase();
$k    = 1;

#**************** Log options *******************
#SQL sentence to items update at aws.items table
#$key = $conn->prepare("select distinct (replace(brand,' ','+')) as brand from aws.items where  brand is not null order by brand desc;");
#$key->execute();	
$key="Eco,Ecologic,recycler";
#$key="Nyx,Dildo,3dRose,A&T+Designs,ARTIX,artspoint,Avidlove,Balakie,Boss+Hog,Brisco+Brands,CafePress,CaylayBrady,ChrisBroadhurst,Customized+Girl,DaJun,Debbie's+Designs,Dikoum,DIYthinker,eyeselect,FJCases,FUNNYSHIRTS.ORG,Generic,Health+Solution+Prime,Hollywood+Thread,Hot+Spot,I+Dethrone,INDIGOS+UG,Karma+t+shirts,kwanjai+shop,Leo+Brown,LQQGXL,Marcaus+Paint+Co,MG+Poster,Molandra+Products,MooLuxe,Nevissbags,Oil+Paintings+Canvas+Prints,OILP,Photo+posters,Posters+USA,RichardGallery,Sandy,Sex+Weights+and+Protein+Shakes+Premium,Sh-yolada,SHORE+TRENDZ,Siam+Circus+Adults,Style+Hanger,superkrit,The+Night's+Watch,TooLoud,Torrent+Town,Trojan,SEX+SEX+SEX,Adam+&+Eve,Blush+Novelties,CalExotics,California+Exotic+Novelties,CALIFORNIA+EXOTIC+NOVELTIES,Classic+Erotica,Doc+Johnson,Eden+Pleasure+Products,FeiGu,Fetish+Fantasy,Fleshlight,Golden+Triangle,Hott+Products,K-Y,kwanjai+shop,LeLuv,LifeStyles,Love+and+Vibes,Master+Series,MASTER+SERIES,MooLuxe,Nasstoys,NS+Novelties,O+Yes,Oxballs,Peach-jp,Pipedream,Pipedream+Products,PUMPKIN+CARRIAGE,Raycity,RESTART®,Sandy,Screaming+O,Sex+Toys+Online+Store,SexFlesh,Sh-yolada,SI+Novelties,Siam+Circus+Adults,Strict+Leather,superkrit,System+JO,Tenga,ThinkMax,Thinkmax,Topco,Trinity+Vibes,Trojan,UTIMI,VSP,Wet,YiFeng,3dRose,Accoutrements,Alician,ALPI,Application,Aquarius,Back+to+20s,Beistle,BESTOYARD,Big+Dot+of+Happiness,CafePress,Crayola,Customized+Girl,DC+Comics,Dikoum,Disney,Emotionlin,ensky,Forum+Novelties,FUNNYSHIRTS.ORG,GAMAGO,Generic,Gold+Sin,Good-Looking+Corpse+button+badges+and+stickers,Géneric,Hott+Place,Kaishihui,Keel+Toys,Kheper+Games,Kid+Fun,Kroo,kwanjai+shop,Meijiafei,Merrick+Mint,MFKS+Games,MG+Poster,PickYourImage,Ridley's,Rosy+Women,Sesame+Street,Sh-yolada,Skull+Splitter+Dice,spell,magic+ritual,Spin+Master,Studio+Ghibli,Super+Goddess,superkrit,Tattoodrucker,TINKSKY,Tong+Yue,Vicwin-One,Ogx,Vibrator,Sexual+toy,Admire+My+Skin,AmazonBasics,Aquaphor,Aveeno,Aztec+Secret,Baebody,Beauty+by+Earth,Better+Shea+Butter,Bio-Oil,Bioré,BodyGlide,Burts+Bees,CeraVe,Cosmedica+Skincare,COSRX,Cottonelle,Dove,Downy,Dr+Teals,Dr.+Bronners,Edens+Semilla,Elizavecca,EltaMD,humane,JPNK,KRASR,Leven+Rose,LilyAna+Naturals,Majestic+Pure,Mario+Badescu,Millennium+Tanning+Products,Moroccanoil,NaturSense,Neutrogena,Nexcare,NYX,Olay,Oleavine,Proactiv,PURA+DOR,Pure+Biology,Purely+Northwest,Radha+Beauty,Shea+Moisture,SHILLS,St.+Ives,Tan+Physics,TAYTHI,Tree+of+Life+Beauty,TruSkin+Naturals,Vanicream,Almay,Ardell,AsaVea,Aveeno,Bare+Escentuals,Beauty+Glazed,Beauty+Glzaed,Benefit+Cosmetics,Bestland,blinc,CICI+Beauty,COVERGIRL,DHC,DUO,e.l.f.,e.l.f.+Cosmetics,Elizabeth+Mott,Godefroy,Hairgenics,It+Cosmetics,Jerzees,KESEE,Kose,LOreal+Paris,L.A.+Girl,Lamora,LipSense,Majestic+Pure,Maybelline+New+York,Milani,Millennium+Tanning+Products,Neutrogena,NYX,OCuSOFT,Physicians+Formula,PleasingCare,Pur+Minerals,Pure+Biology,Pure+Body+Naturals,Rapidbrow,RevitaLash+Cosmetics,Revlon,Rimmel,Simply+Naked+Beauty,Sky+Organics,STS,Style+Edit,theBalm,Too+Faced,UCANBE,UV+Glow,AMERICAN+CREW,Andis,Aria+Starr+Beauty,ArtNaturals,Arvazallia,Batiste,Baxter+of+California,Biolage,BioSilk,Braun,Cetaphil+Baby,Denman,DevaCurl,DORCO,DuraComfort+Essentials,eBoot,ELACUCOS,Fanola,HSI+PROFESSIONAL,Invisibobble,Its+a+10,Its+a+10+Haircare,Just+for+Men,Kenra,Kiloline,Kirkland+Signature,Majestic+Pure,Maple+Holistics,MiroPure,Moroccanoil,Nioxin,Nizoral,ORIBE,Paul+Mitchell,PURA+DOR,Pure+Biology,Pureology,Remington,Revlon,Rioa,Rogaine,Scalpmaster,Scunci,Shea+Moisture,Suavecito,TIGI+Cosmetics,Tolco,Tropic+Isle+Living,Ultrax+Labs,Viva+Naturals,Wahl,Active+Wow,Alayna,Aoremon,Arm+&+Hammer,AuraGlow,Baltic+Wonder,Biotène,Braun,Brightdeal,Colgate,Crest,Dental+Duty,Dental+Expert,DentalCare+Labs,DenTek,Dr+Song,Dr.+Tungs,Equinox+International,FineVine,Glide,H2ofloss,Hello+Oral+Care,Jordan,Listerine,MAGINOVO,Majestic+Pure,Nimbus+Microfine,Orahealth,Orajel,Oral+B,OralShine,Parodontax,Pearl+Enterprises,Philips,Philips+Sonicare,Plackers,PlatypusCo.,Plax,Retainer+Brite,Sensodyne,SENSODYNE+PRONAMEL,Sonifresh,Sonimart,SoulGenie,Spry,Sunshine,Sunstar,The+First+Years,TheraBreath,Toms+of+Maine,Twin+Lotus,Artizen,ArtNaturals,Bath+&+Body+Works,BAXIA+TECHNOLOGY,Clinique,Davidoff,Dolce+&+Gabbana,Dolcé+&+Gabbaná,Dolcé+&+Gabbána,doTERRA,Erligpowht,Essential+Oil+Labs,Essentially+KateS,Fabulous+Frannie,First+Botany+Cosmeceuticals,Guy+Laroche,Healing+Solutions,InnoGear,InstaNatural,Jerzees,Kis+OilS,Lagunamoon,Leknes,Luscreal,Majestic+Pure,Maple+Holistics,Mary+Tylor+Naturals,Natrogix,Natural+Riches,Natures+Approach,Neloodony,NOW+Foods,OliveTech,P&J+Trading,Plant+Guru,Plant+Therapy,Plant+Therapy+Essential+Oils,Pure,PURSONIC,Pursonic,Radha+Beauty,RawChemistry,Sky+Organics,Soft+digits,SOLIGT,Sun+Organic,URPOWER,venu,Versace,Versacé,Zen+Breeze,Amope,Andis,Aveeno,Aztec+Secret,baKblade,Bar5F,BEAKEY,Braun,BS-MALL,BTYMS,Clarisonic,Compound+W,Docolor,DuraComfort+Essentials,EcoTools,EmaxDesign,ETEREAUTY,For+Pro,Fromm,Gillette,Gillette+Venus,GILLÈTTE,Honest+Amish,Kirkland+Signature,KRASR,LOreal+Paris,Lifestance,MY+KONJAC+SPONGE,NOW+Foods,Nylea,Organyc,Panasonic,Philips+Norelco,PrettyCare,Q-Tips,Aofeite,Aofit,Aptoco,ATB,BeltPC,Briggs,ECYC,Eforstore,Emma+Ya,EUBUY,ewinever,Extreme+Fit,FOOTLOOSE,Fsing,GOGO,HBpanda,Healsmile,Homax,Homyl,ieasysexy,JERN,JORZILANO,JZL,LifeShop,MagiDeal,MALLCROWN,MyProSupports,NACOLA,Ober,ORIONE,Panda+Superstore,PigPig+Healthy,ROSENICE,ShapEager,shopidea,SODIAL,SODIAL,support+81,Tcare,ThinkMax,Thinkmax,ToneWear,Tonus+Elast,TOOGOO,TOROS-GROUP,uxcell,UZZO,VitaZon,Wonder+Care,YK+Care,ZJchao,4GEAR,AidBrace,Albolene,ARMSTRONG+AMERIKA,AZMED,Babo+Care,Baby+Foot,Blitzu,Bodymate,BraceUP,Bracoo,BSN+Medical,Carpal+Tunnel+Wrist+Brace,Compressions,Copper+Compression,Copper+Fit,Crucial+Compression,Dr.+Arthritis,EXOUS,EzyFit,Futuro,Hampton+Adams,Hip+Mall,IPOW,Kinesio,Kootek,KT+Tape,Liomor,Mars,McDavid,Med+Spec,Mueller,MUSETECH,OPTP,Perfotek,Pfit,Physix+Gear+Sport,Pro+Band+Sports,ProCare,Profoot,ProStretch,Rocktape,ROOCKE,Sable,Sparthos,Sports+Research,Tomight,Ultra+Flex+Athletics,Venom+Sports+Fitness,VIVE,Winzone,Abcstore99,Artlalic,Auch,AuroraX,AUSHEN,AutumnFall,BestMassage,Beyoung,bulk+buys,C.X.Z,Canserin,CLEAVAGE+CONTROL+CLIP,Clinians,CYCTECH®,DENADADANCE,Denman,DevLon+NorthWest,Eight24hours,essie,European+American+Design,Fashionwu,Foot+Petals,GABRIALLA,Generic,Gent+House,GokuStore,HEALTH+LINE+MASSAGE+PRODUCTS,HiiBaby,HomeTek+USA,Homyl,HP95,Human+Touch,Inada,Ivy,Jaguar,Lisa+Colly,Living+Libations,Lormay,lychee,Mirror,Nail+Polish,niceeshop,North+Coast+Medical,OPTP,PigPig+Healthy,Positive+Posture,Pulla,SiamsShop,STCorps7,Ursexyly,WOLF,Ader+Sporting+Goods,AFT-+aofit,AliMed,Aofeite,Aofit,AUG,Ausom,BeatChong,Bilt-Rite+Mastex+Health,CYCTECH®,DIYthinker,DODOING,Emma+Ya,EUBUY,Extreme+Fit,Fitness+Maniac,FLA+Orthopedics,Gaiam,Generic,Homax,HÖTER,IDS+Home,ieasysexy,Insta+Slim,IntelliSkin,+LLC,JORZILANO,KIWI+RATA,Lelinta,LifeShop,LODAY,MagiDeal,MyProSupports,OOFAY,OPTP,Panda+Superstore,PigPig+Healthy,POSMA,ROSENICE,Rosie,ShapEager,shopidea,Thermo+Slim,ToneWear,Tonus+Elast,TOOGOO,unbrand,Ursexyly,uxcell,Vinmin,VitaZon,ZJchao,Real+Techniques,Remington,Revlon,Sallys+Organics,Scalpmaster,Schick,Smooth+Viking+Beard+Care,TAYTHI,Tend+Skin,Tinkle,ToiletTree+Products,TweezerGuru,Vivaplex,Wahl,Wet+Brush,Wholesome+Beauty,AMERICAN+CREW,Andis,Arm+&+Hammer,AXE,Baxter+of+California,BIC,Biotène,Braun,CloSYS,Colgate,Crest,Davidoff,Degree+Men,DenTek,Dial,Dove,Dr.+Collins,Edge,ELLESYE,Feather,Fresh+Balls,Gillette,GILLÈTTE,Glide,Got2b,Guy+Laroche,Kirkland+Signature,LOreal+Paris,Listerine,MANGROOMER,Nair,Neutrogena,Nivea+Men,Old+Spice,Orahealth,Oral+B,Panasonic,Paul+Mitchell,Philips+Norelco,Philips+Sonicare,Proraso,Remington,Schick,Seki+Edge,SENSODYNE+PRONAMEL,Spinbrush,SweatBlock,TheraBreath,TIGI+Cosmetics,Toms+of+Maine,Wahl,STILA,BURBERRY,LOCCITANE,CLARISONIC,MARIO+BADESCU,CALVIN+KLEIN,FOREO,JACK+BLACK,JANE+IREDALE,HUGO+BOSS,TRIA+BEAUTY,BEACHWAVER+CO.,LAURA+GELLER+NEW+YORK,PHYTO,CHI,NUFACE,MONTBLANC,TOPPIK,THE+ART+OF+SHAVING,REVITALASH+COSMETICS,ELIZABETH+ARDEN,ELEMIS,ST.+TROPEZ,T3+MICRO,BUTTER+LONDON,Admire+My+Skin,AmazonBasics,Aquaphor,Aveeno,Aztec+Secret,Baebody,Beauty+by+Earth,Better+Shea+Butter,Bio-Oil,Bioré,BodyGlide,Burts+Bees,CeraVe,Cosmedica+Skincare,COSRX,Cottonelle,Dove,Downy,Dr+Teals,Dr.+Bronners,Edens+Semilla,Elizavecca,EltaMD,humane,JPNK,KRASR,Leven+Rose,LilyAna+Naturals,Majestic+Pure,Mario+Badescu,Millennium+Tanning+Products,Moroccanoil,NaturSense,Neutrogena,Nexcare,NYX,Olay,Oleavine,Proactiv,PURA+DOR,Pure+Biology,Purely+Northwest,Radha+Beauty,Shea+Moisture,SHILLS,St.+Ives,Tan+Physics,TAYTHI,Tree+of+Life+Beauty,TruSkin+Naturals,Vanicream,Almay,Ardell,AsaVea,Aveeno,Bare+Escentuals,Beauty+Glazed,Beauty+Glzaed,Benefit+Cosmetics,Bestland,blinc,CICI+Beauty,COVERGIRL,DHC,DUO,e.l.f.,e.l.f.+Cosmetics,Elizabeth+Mott,Godefroy,Hairgenics,It+Cosmetics,Jerzees,KESEE,Kose,LOreal+Paris,L.A.+Girl,Lamora,LipSense,Majestic+Pure,Maybelline+New+York,Milani,Millennium+Tanning+Products,Neutrogena,NYX,OCuSOFT,Physicians+Formula,PleasingCare,Pur+Minerals,Pure+Biology,Pure+Body+Naturals,Rapidbrow,RevitaLash+Cosmetics,Revlon,Rimmel,Simply+Naked+Beauty,Sky+Organics,STS,Style+Edit,theBalm,Too+Faced,UCANBE,UV+Glow,AMERICAN+CREW,Andis,Aria+Starr+Beauty,ArtNaturals,Arvazallia,Batiste,Baxter+of+California,Biolage,BioSilk,Braun,Cetaphil+Baby,Denman,DevaCurl,DORCO,DuraComfort+Essentials,eBoot,ELACUCOS,Fanola,HSI+PROFESSIONAL,Invisibobble,Its+a+10,Its+a+10+Haircare,Just+for+Men,Kenra,Kiloline,Kirkland+Signature,Majestic+Pure,Maple+Holistics,MiroPure,Moroccanoil,Nioxin,Nizoral,ORIBE,Paul+Mitchell,PURA+DOR,Pure+Biology,Pureology,Remington,Revlon,Rioa,Rogaine,Scalpmaster,Scunci,Shea+Moisture,Suavecito,TIGI+Cosmetics,Tolco,Tropic+Isle+Living,Ultrax+Labs,Viva+Naturals,Wahl,Active+Wow,Alayna,Aoremon,Arm+&+Hammer,AuraGlow,Baltic+Wonder,Biotène,Braun,Brightdeal,Colgate,Crest,Dental+Duty,Dental+Expert,DentalCare+Labs,DenTek,Dr+Song,Dr.+Tungs,Equinox+International,FineVine,Glide,H2ofloss,Hello+Oral+Care,Jordan,Listerine,MAGINOVO,Majestic+Pure,Nimbus+Microfine,Orahealth,Orajel,Oral+B,OralShine,Parodontax,Pearl+Enterprises,Philips,Philips+Sonicare,Plackers,PlatypusCo.,Plax,Retainer+Brite,Sensodyne,SENSODYNE+PRONAMEL,Sonifresh,Sonimart,SoulGenie,Spry,Sunshine,Sunstar,The+First+Years,TheraBreath,Toms+of+Maine,Twin+Lotus,Artizen,ArtNaturals,Bath+&+Body+Works,BAXIA+TECHNOLOGY,Clinique,Davidoff,Dolce+&+Gabbana,Dolcé+&+Gabbaná,Dolcé+&+Gabbána,doTERRA,Erligpowht,Essential+Oil+Labs,Essentially+KateS,Fabulous+Frannie,First+Botany+Cosmeceuticals,Guy+Laroche,Healing+Solutions,InnoGear,InstaNatural,Jerzees,Kis+OilS,Lagunamoon,Leknes,Luscreal,Majestic+Pure,Maple+Holistics,Mary+Tylor+Naturals,Natrogix,Natural+Riches,Natures+Approach,Neloodony,NOW+Foods,OliveTech,P&J+Trading,Plant+Guru,Plant+Therapy,Plant+Therapy+Essential+Oils,Pure,PURSONIC,Pursonic,Radha+Beauty,RawChemistry,Sky+Organics,Soft+digits,SOLIGT,Sun+Organic,URPOWER,venu,Versace,Versacé,Zen+Breeze,Amope,Andis,Aveeno,Aztec+Secret,baKblade,Bar5F,BEAKEY,Braun,BS-MALL,BTYMS,Clarisonic,Compound+W,Docolor,DuraComfort+Essentials,EcoTools,EmaxDesign,ETEREAUTY,For+Pro,Fromm,Gillette,Gillette+Venus,GILLÈTTE,Honest+Amish,Kirkland+Signature,KRASR,LOreal+Paris,Lifestance,MY+KONJAC+SPONGE,NOW+Foods,Nylea,Organyc,Panasonic,Philips+Norelco,PrettyCare,Q-Tips,Real+Techniques,Remington,Revlon,Sallys+Organics,Scalpmaster,Schick,Smooth+Viking+Beard+Care,TAYTHI,Tend+Skin,Tinkle,ToiletTree+Products,TweezerGuru,Vivaplex,Wahl,Wet+Brush,Wholesome+Beauty,AMERICAN+CREW,Andis,Arm+&+Hammer,AXE,Baxter+of+California,BIC,Biotène,Braun,CloSYS,Colgate,Crest,Davidoff,Degree+Men,DenTek,Dial,Dove,Dr.+Collins,Edge,ELLESYE,Feather,Fresh+Balls,Gillette,GILLÈTTE,Glide,Got2b,Guy+Laroche,Kirkland+Signature,LOreal+Paris,Listerine,MANGROOMER,Nair,Neutrogena,Nivea+Men,Old+Spice,Orahealth,Oral+B,Panasonic,Paul+Mitchell,Philips+Norelco,Philips+Sonicare,Proraso,Remington,Schick,Seki+Edge,SENSODYNE+PRONAMEL,Spinbrush,SweatBlock,TheraBreath,TIGI+Cosmetics,Toms+of+Maine,Wahl,STILA,BURBERRY,LOCCITANE,CLARISONIC,MARIO+BADESCU,CALVIN+KLEIN,FOREO,JACK+BLACK,JANE+IREDALE,HUGO+BOSS,TRIA+BEAUTY,BEACHWAVER+CO.,LAURA+GELLER+NEW+YORK,PHYTO,CHI,NUFACE,MONTBLANC,TOPPIK,THE+ART+OF+SHAVING,REVITALASH+COSMETICS,ELIZABETH+ARDEN,ELEMIS,ST.+TROPEZ,T3+MICRO,BUTTER+LONDON,LACOSTE,MUSTELA,XTREME+LASHES,TOMMY+BAHAMA,STRIVECTIN,ELCHIM,COLOR+WOW,BORGHESE,JIMMY+CHOO,PINK+SUGAR,PHILIP+B,SOLANO,RADICAL+SKINCARE,MAKEUP+ERASER,ILUMINAGE,BAXTER+OF+CALIFORNIA,JUICY+COUTURE,PHILOSOPHY+FOR+MEN,SMITH+&+CULT,KENNETH+COLE,GRANDE+COSMETICS,SKYN+ICELAND,VINCENT+LONGO,MAMA+MIO,37+ACTIVES,USLU+AIRLINES,ORLANE+PARIS,TRACIE+MARTYN,SOLEIL+TOUJOURS,ÊSHAVE,TASK+ESSENTIAL,NUXE,JENU,MASQUEOLOGY,WILDFOX,AMOUAGE,COSTUME+NATIONAL,NOODLE+&+BOO,LORAC,MACADAMIA+PROFESSIONAL,MEANINGFUL+BEAUTY,BROWFOOD,BOLIN+WEBB,JILLIAN+DEMPSEY,DERMASURI,COLORESCIENCE,BILLY+JEALOUSY,LAFCO,BABY+QUASAR,MAI+COUTURE,TAN+TOWEL,3LAB,NUBRILLIANCE,PATCHOLOGY,BOUCHERON,CALISTA,VENEFFECT,LANVIN,LIERAC,BELLA+J.,ALORA+AMBIANCE,GULSHA,MARVIS,COMPTOIR+SUD+PACIFIQUE,EMI+JAY,JUDITH+LEIBER,BLISS,JEAN+PATOU,RAW+SPIRIT+FRAGRANCES,ILA,MAKE,LEONOR+GREYL+PARIS,CLEAN,TAMMY+FENDER,CANE+++AUSTIN,ANTICA+FARMACISTA,BEAUTYRX+BY+DR.+SCHULTZ,CATHERINE+MALANDRINO,OSCAR+BLANDI,MONTALE,MILA+MOURSI,ARCHIPELAGO,KAHINA+GIVING+BEAUTY,AHAVA,MËNAJI,ANSR,ESSIE,NATURA+BISSE,INDIE+LEE,SCOTT+BARNES,JUICE+BEAUTY,SUPERSMILE,CLARKS+BOTANICALS,MIO,ANTHONY,BIODERMA,EYEKO,CARON+PARIS,BLOWPRO,KALE+NATURALS,LAZY+PERFECTION+BY+JENNY+PATINKIN,SJÄL,ESCENTRIC+MOLECULES,RITUALS,WRINKLEMD,JAPONESQUE,LUMABELLA,CRABTREE+&+EVELYN,LUMARX,DIEGO+DALLA+PALMA,LOLITA+LEMPICKA,BLINC,LASHFOOD,DR.+ALKAITIS,BRONZE+BUFFER,SOMME+INSTITUTE,LIGHTSTIM,MASON+PEARSON,NURSE+JAMIE+HEALTHY+SKIN+SOLUTIONS,XEN-TAN,STEPHANIE+JOHNSON,PRORASO,CARGO,GEORGIE+BEAUTY,CLOUD+NINE,THE+BROWGAL,JINSOON,MOLTON+BROWN,DDF,SABON,RODIAL,FRASCO+MIRRORS,ERNO+LASZLO,VITA+LIBERATA,LVX,YU-BE,KARUNA,FATBOY+HAIR,GORGEOUS+COSMETICS,JULEP,HAIRMAX,OSMOTICS+COSMECEUTICALS,VIE+LUXE,SHAVEWORKS,LONDONTOWN,THEBALM,FHI+HEAT,ETAT+LIBRE+DORANGE,PMD,LABORATOIRES+FILORGA+PARIS,ZWILLING+J.A.+HENCKELS,SCOTT-VINCENT+BORBA,YOSH,NIA+24,SARA+HAPP,MANCERA,HAMPTON+SUN,AROMACHOLOGY,ZENSATION,TERVEER,SULTRA,PHACE+BIOACTIVE,MENSCIENCE+ANDROCEUTICALS,TALIKA,TENOVERTEN,RECIPE+FOR+MEN,JOUER,JULIETTE+HAS+A+GUN,VINCE+CAMUTO,GIANNA+ROSE,LIFTLAB,ROCHAS,OVANDO,JAMES+READ,JOSIE+BY+JOSIE+NATORI,ENGLISH+LAUNDRY,ZIRH,STACEY+FRASCA,MANUKA+DOCTOR,PERRICONE+MD,SKIN&CO+ROMA,THIRDMAN,HOMMAGE,JOHN+VARVATOS,BE+THE+LIGHT+NEW+YORK+BY+PETRA+NEMCOVA,HYDROPEPTIDE,JOHN+ALLANS,BIOELEMENTS,MICHEL+GERMAIN,RITA+HAZAN,AROMATHERAPY+ASSOCIATES,LORD+&+BERRY,PAYOT,SERGE+NORMANT,GLOTRITION,CUVGET,THE+REFINERY,BRIONI,COMING+SOON,sidebike,car+parts+Toyota,car+parts+Nizan,car+parts+Jeep,car+parts+Chevrolet,car+parts+Ford,car+parts+Mazda,car+parts+Honda,car+parts+Dodge,car+parts+Renault,car+parts+Kia,car+parts+Volkswage,car+parts+Hyundai,car+parts+Suzuki,Coleman,Columbia,Raleigh,Osprey,Razor,Marmot,High+sierra,Cicleops,Bell,Spyder,Allen,Swagtron,Intex,Pearl+izumi,Thule,Camelbak,Sea+Eagle,Oneill,Surftech,ABILicers,+Geval,+prAna,+PRANA,+FMS,+Outmate,+KAVU,Yakima,olar+Bottle,BV,Blitzu,UShake,Malker,Ascher,Vibrelli,AYAMAYA,Base+Camp,BASE+CAMP,Basecamp,BeBeFun,Bell,Bern,Best+Deals,Bingggooo,Black+Clover,BTR,CCTRO,Crazy+Mars,CycleAware,EVT,FMA,Fox+Racing,GIORO,Giro,GoMax,Gomax,Gonex,HiCool,JBM+international,Joovy,Kask,KaZAM,KINGBIKE,Krash,Lazer,Lixada,Micro+Kickboard,Nickelodeon,Nutcase,Níckelodeon,POC,ProRider,PROTEC+Original,Raskullz,Razor,Rázor,Schwinn,SG+Dreamz,shuangjishan,TeamObsidian,Traverse,Triple+Eight,Troy+Lee+Designs,Utopia+Home,Vinciph,Wipeout,Activ+Life,Balance+Buddy,Bianchi,Bikeroo,Bodyguard,BV,Cinelli,Cloud-9,Crank+Brothers,DAWAY,Domain+Cycling,Fizik,FOX+Factory,GoFriend,Imrider,INBIKE,Kissral,KMC,KMCA0,LEBOLIKE,Lizard+Skins,Look,Look+Cycle,Mpow,NIKE,ODIER,Origin8,Otium,Oumers,OUTERDO,Park+Tool,Planet+Bike,RaceFace,RAM+MOUNTS,RAM+Mounts,ReFaXi,RIDE+IT,RockShox,Schwinn,Shimano,SoundPEATS,SRAM,SRAM0,Sunlite,TaoTronics,The+Flying+Wheels,Tilos,TOPCABIN,UShake,Velox"; 

$quantity = 0;
$crawler = new Amazon();
$j          = 1;

	$llave =explode(",", $key);

	foreach ($llave as $k) {	

		$keywords 	= trim($k);

		$url        = "https://www.amazon.com/s/gp/search/ref=sr_nr_p_85_0?fst=as%3Aoff&rh=i%3Aaps%2Ck%3A".$keywords."%2Cp_76%3A2661625011%2Cp_85%3A2470955011&sort=price-desc-rank&keywords=".$keywords."&ie=UTF8&qid=1519921342&rnid=2470954011";
		$aws_result = $crawler->crawler_create($url,1);

        switch ($aws_result['notavaliable']) {
        	case 0:
        		$data =explode(",", $aws_result['skus']);
        		echo "SKU PAGINA 1, producto: ".$keywords."\t\t total paginas:".$aws_result['pages']." \n";

        		foreach ($data as $ku_result) {
					$sku = strtoupper($ku_result);
					$sql = $conn->prepare("select upper(sku) as sku from aws.items where sku = '".$sku."';");
					$sql->execute();
					$sql = $sql->fetch();
					if (!isset($sql[0])) {
						$conn->exec("insert into aws.items (sku, create_date) values ('".$sku."','".date("Y-m-d H:i:s")."');");
						echo $j."-\t".$sku." - insertado - ".date("Y-m-d H:i:s")."\n";
						$j++;
					}else{
						#echo $j."-\t".$sku." - ya existe - ".date("Y-m-d H:i:s")."\n";
					}
					
				}
				sleep(1);
        		#$skus 	= strtoupper($aws_result['skus']);
	       		$pg 	= strtoupper($aws_result['pages']);    

	       		#$pg 	= 400;      	
	       		$conn->close_con();
	        	for ($y =2; $y <= $pg; $y++ ){

		        	#$url2 = "https://www.amazon.com/s/ref=sr_pg_".$y."?fst=as%3Aoff&rh=i%3Aaps%2Ck%3A".$keywords."%2Cp_76%3A2661625011%2Cp_85%3A2470955011&page=".$y."&keywords=".$keywords."&ie=UTF8&qid=1519150114";

	        		#$url2 = "https://www.amazon.com/s/ref=sr_pg_".$y."?fst=as%3Aoff&rh=i%3Aaps%2Ck%3A".$keywords."%2Cp_76%3A2661625011%2Cp_85%3A2470955011&page=".$y."&sort=price-desc-rank&keywords=".$keywords."&ie=UTF8&qid=1519921348";

	        		$url2 = "https://www.amazon.com/s/ref=sr_pg_".$y."?fst=as%3Aoff&rh=i%3Aaps%2Ck%3A".$keywords."%2Cp_76%3A2661625011%2Cp_85%3A2470955011%2Cp_n_condition-type%3A6461716011&page=".$y."&sort=price-desc-rank&keywords=".$keywords."&ie=UTF8&qid=1523033887";

		        	$aws_result2 = $crawler->crawler_create($url,2);
		        	
		        	switch ($aws_result2['notavaliable']) {
			        	case 0:
			 				$data =explode(",", $aws_result2['skus2']);
			        	 	echo "SKU PAGINA ".$y.", producto: ".$keywords."\n";
			        	 	foreach ($data as $ku_result) {
								$sku = strtoupper($ku_result);
								$sql = $conn->prepare("select upper(sku) as sku from aws.items where sku = '".$sku."';");
								$sql->execute();
								$sql = $sql->fetch();
								if (!isset($sql[0])) {
									$conn->exec("insert into aws.items (sku, create_date) values ('".$sku."','".date("Y-m-d H:i:s")."');");
									echo $j."-\t".$sku." - insertado - ".date("Y-m-d H:i:s")."\n";
									$j++;
								}else{
									#echo $j."-\t".$sku." - ya existe - ".date("Y-m-d H:i:s")."\n";
								}
								
							}

			        	break;
			        	case 1:
        					echo  "pagina ".$y." - ".$aws_result2['message']."\n";
			        	break;	
		        	}
		        	sleep(1);
	        	}
	        	/*aca*/

	        break;	
        	case 1:
        		echo  $aws_result['message']." producto: ".$keywords."\n";
        	break;  
        }
	}
