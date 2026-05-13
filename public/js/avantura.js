const JAVA_GAME_API_BASE = "http://127.0.0.1:8081/api/game";


const GAME_CONFIG = {
    maxTurns: 12,
    minStat: 0,
    criticalThreshold: 2,
    strongThreshold: 8,
    startStats: {
        snaga: 5,
        mudrost: 5,
        vera: 5,
        ugled: 5,
        zlato: 6,
        stabilnost: 5
    },
    winRequirements: {
        stabilnost: 4,
        ugled: 8,
        vera: 8,
        mudrost: 8,
        zlato: 3
    }
};

let gameState = {};
let currentTurn = 1;
let currentSessionId = null;
let currentScene = null;
let lastEnding = null;
let scriptMode = localStorage.getItem("pv_script_mode") || "lat";
let settings = {
    animations: true,
    showEffects: true,
    showWarnings: true
};

const LAT_TO_CYR_PAIRS = [
    ["D\u017E", "\u040F"], ["D\u017D", "\u040F"], ["d\u017E", "\u045F"],
    ["Lj", "\u0409"], ["LJ", "\u0409"], ["lj", "\u0459"],
    ["Nj", "\u040A"], ["NJ", "\u040A"], ["nj", "\u045A"]
];

const LAT_TO_CYR = {
    "A": "\u0410", "B": "\u0411", "V": "\u0412", "G": "\u0413", "D": "\u0414", "\u0110": "\u0402", "E": "\u0415", "\u017D": "\u0416", "Z": "\u0417", "I": "\u0418",
    "J": "\u0408", "K": "\u041A", "L": "\u041B", "M": "\u041C", "N": "\u041D", "O": "\u041E", "P": "\u041F", "R": "\u0420", "S": "\u0421", "T": "\u0422",
    "\u0106": "\u040B", "U": "\u0423", "F": "\u0424", "H": "\u0425", "C": "\u0426", "\u010C": "\u0427", "\u0160": "\u0428",
    "a": "\u0430", "b": "\u0431", "v": "\u0432", "g": "\u0433", "d": "\u0434", "\u0111": "\u0452", "e": "\u0435", "\u017E": "\u0436", "z": "\u0437", "i": "\u0438",
    "j": "\u0458", "k": "\u043A", "l": "\u043B", "m": "\u043C", "n": "\u043D", "o": "\u043E", "p": "\u043F", "r": "\u0440", "s": "\u0441", "t": "\u0442",
    "\u0107": "\u045B", "u": "\u0443", "f": "\u0444", "h": "\u0445", "c": "\u0446", "\u010D": "\u0447", "\u0161": "\u0448"
};

const CYR_TO_LAT = {
    "\u0410": "A", "\u0411": "B", "\u0412": "V", "\u0413": "G", "\u0414": "D", "\u0402": "\u0110", "\u0415": "E", "\u0416": "\u017D", "\u0417": "Z", "\u0418": "I",
    "\u0408": "J", "\u041A": "K", "\u041B": "L", "\u0409": "Lj", "\u041C": "M", "\u041D": "N", "\u040A": "Nj", "\u041E": "O", "\u041F": "P", "\u0420": "R",
    "\u0421": "S", "\u0422": "T", "\u040B": "\u0106", "\u0423": "U", "\u0424": "F", "\u0425": "H", "\u0426": "C", "\u0427": "\u010C", "\u040F": "D\u017E", "\u0428": "\u0160",
    "\u0430": "a", "\u0431": "b", "\u0432": "v", "\u0433": "g", "\u0434": "d", "\u0452": "\u0111", "\u0435": "e", "\u0436": "\u017E", "\u0437": "z", "\u0438": "i",
    "\u0458": "j", "\u043A": "k", "\u043B": "l", "\u0459": "lj", "\u043C": "m", "\u043D": "n", "\u045A": "nj", "\u043E": "o", "\u043F": "p", "\u0440": "r",
    "\u0441": "s", "\u0442": "t", "\u045B": "\u0107", "\u0443": "u", "\u0444": "f", "\u0445": "h", "\u0446": "c", "\u0447": "\u010D", "\u045F": "d\u017E", "\u0448": "\u0161"
};const scenes = [
    {
        id: "susna-godina",
        type: "Narod",
        objective: "SaÃ„Âuvaj poverenje naroda bez uruÃ…Â¡avanja riznice.",
        chapter: "Poglavlje I Ã¢â‚¬â€ Uspon",
        characterState: "Narod paÃ…Â¾ljivo posmatra tvoje prve poteze",
        title: "SuÃ…Â¡na godina",
        text: "SuÃ…Â¡a je pogodila sela. Narod traÃ…Â¾i pomoÃ„â€¡, dok riznica nije neograniÃ„Âena. Tvoja odluka Ã„â€¡e uticati na poverenje ljudi i stanje drÃ…Â¾ave.",
        choices: [
            {
                text: "Otvori drÃ…Â¾avne Ã…Â¾itnice i pomozi narodu",
                detail: "Velika pomoÃ„â€¡ narodu, ali ozbiljan troÃ…Â¡ak za riznicu.",
                log: "Otvorio si Ã…Â¾itnice i spasao mnoga domaÃ„â€¡instva.",
                effects: { ugled: 2, stabilnost: 1, zlato: -2, vera: 1 }
            },
            {
                text: "SaÃ„Âuvaj zalihe za vojsku i dvor",
                detail: "JaÃ„ÂaÃ…Â¡ disciplinu i rezervu, ali narod gubi poverenje.",
                log: "OdluÃ„Âio si da saÃ„ÂuvaÃ…Â¡ zalihe za krizna vremena i vojsku.",
                effects: { zlato: 1, ugled: -2, stabilnost: -1, snaga: 1 }
            },
            {
                text: "Podeli pomoÃ„â€¡ samo najugroÃ…Â¾enijima",
                detail: "Umeren put izmeÃ„â€˜u milosti i opstanka drÃ…Â¾ave.",
                log: "PokuÃ…Â¡ao si da napraviÃ…Â¡ ravnoteÃ…Â¾u izmeÃ„â€˜u milosti i opstanka riznice.",
                effects: { ugled: 1, mudrost: 1, zlato: -1 }
            },
            {
                text: "ZatraÃ…Â¾i pomoÃ„â€¡ manastira i eparhija",
                detail: "OtkljuÃ„Âava se samo ako imaÃ…Â¡ dovoljno vere.",
                log: "Uspela si da poveÃ…Â¾eÃ…Â¡ duhovne centre i pomoÃ„â€¡ je stigla narodu bez prevelikog udara na riznicu.",
                effects: { vera: 1, ugled: 2, stabilnost: 1, zlato: -1 },
                requirements: { vera: 6 }
            }
        ]
    },
    {
        id: "granica",
        type: "Odbrana",
        objective: "Obuzdaj krizu na granici bez Ã…Â¡irenja unutraÃ…Â¡nje nestabilnosti.",
        chapter: "Poglavlje I Ã¢â‚¬â€ Uspon",
        characterState: "Tvrda odluka sada moÃ…Â¾e saÃ„Âuvati ili ugroziti presto",
        title: "Nemiri na granici",
        text: "Na granici se javljaju upadi. MoraÃ…Â¡ odluÃ„Âiti da li Ã„â€¡eÃ…Â¡ odmah pokazati silu ili paÃ…Â¾ljivo proceniti situaciju.",
        choices: [
            {
                text: "PoÃ…Â¡alji vojsku i uÃ„Âvrsti granicu",
                detail: "Brza sila vraÃ„â€¡a red, ali koÃ…Â¡ta zlata.",
                log: "PojaÃ„Âao si granicu i pokazao odluÃ„Ânost.",
                effects: { snaga: 2, zlato: -1, stabilnost: 1 }
            },
            {
                text: "PoÃ…Â¡alji izaslanike i izvidnicu",
                detail: "Oprezan pristup donosi mudrost i vreme.",
                log: "Najpre si izabrao oprez i prikupljanje informacija.",
                effects: { mudrost: 2, snaga: -1, stabilnost: 1 }
            },
            {
                text: "Osloni se na lokalne velikaÃ…Â¡e",
                detail: "Ã…Â tediÃ…Â¡ riznicu, ali slabi centar vlasti.",
                log: "Prepustio si deo odgovornosti lokalnim gospodarima.",
                effects: { zlato: 1, stabilnost: -1, ugled: -1 }
            },
            {
                text: "LiÃ„Âno povedi vojsku na granicu",
                detail: "RiziÃ„Âan, ali snaÃ…Â¾an potez za ugled i autoritet.",
                log: "Tvoj liÃ„Âni odlazak na granicu podigao je moral i uÃ„Âvrstio veru u tvoju odluÃ„Ânost.",
                effects: { snaga: 2, ugled: 2, stabilnost: 1, zlato: -2 },
                requirements: { snaga: 6 }
            }
        ]
    },
    {
        id: "manastir",
        type: "Vera",
        objective: "Odredi kakvu duhovnu osnovu Ã…Â¾eliÃ…Â¡ da daÃ…Â¡ svojoj vladavini.",
        chapter: "Poglavlje I Ã¢â‚¬â€ Uspon",
        characterState: "Duhovni savet moÃ…Â¾e promeniti tok cele vladavine",
        title: "Savet duhovnika",
        text: "Iguman uglednog manastira poziva te na razgovor. PredlaÃ…Â¾e ti da razmisliÃ…Â¡ o tome kakvu drÃ…Â¾avu Ã…Â¾eliÃ…Â¡ da ostaviÃ…Â¡ iza sebe.",
        choices: [
            {
                text: "OtiÃ„â€˜i u manastir i posluÃ…Â¡aj savet",
                detail: "JaÃ„ÂaÃ…Â¡ veru, ugled i unutraÃ…Â¡nju stabilnost karaktera.",
                log: "Posetio si manastir i stekao unutraÃ…Â¡nji mir i Ã…Â¡iru sliku vlasti.",
                effects: { vera: 2, mudrost: 1, ugled: 1 }
            },
            {
                text: "PoÃ…Â¡alji darove, ali ostani na dvoru",
                detail: "PoÃ…Â¡tujeÃ…Â¡ Crkvu, ali zadrÃ…Â¾avaÃ…Â¡ politiÃ„Âku kontrolu.",
                log: "Pokazao si poÃ…Â¡tovanje prema Crkvi, ali si ostao u politiÃ„Âkom srediÃ…Â¡tu.",
                effects: { vera: 1, zlato: -1, ugled: 1 }
            },
            {
                text: "Odgodi susret zbog drÃ…Â¾avnih poslova",
                detail: "Prednost dajeÃ…Â¡ vlasti, ali gubiÃ…Â¡ duhovnu dubinu.",
                log: "DrÃ…Â¾avni poslovi dobili su prednost nad duhovnim savetom.",
                effects: { snaga: 1, vera: -1, mudrost: -1 }
            },
            {
                text: "Povedi i naslednike i vlastelu u manastir",
                detail: "Veliki simboliÃ„Âki potez koji zahteva ugled.",
                log: "Spojio si duhovni autoritet i drÃ…Â¾avnu reprezentaciju, ostavljajuÃ„â€¡i snaÃ…Â¾an utisak na dvor i narod.",
                effects: { vera: 2, mudrost: 1, ugled: 2 },
                requirements: { ugled: 6 }
            }
        ]
    },
    {
        id: "pobuna-vlastela",
        type: "Dvor",
        objective: "SaÃ„Âuvaj autoritet bez raspada odnosa sa vlastelom.",
        chapter: "Poglavlje II Ã¢â‚¬â€ IskuÃ…Â¡enja",
        characterState: "Plemstvo proverava granice tvoje moÃ„â€¡i",
        title: "Nezadovoljstvo vlastele",
        text: "Deo vlastele smatra da im dajeÃ…Â¡ premalo uticaja. MoraÃ…Â¡ odluÃ„Âiti da li Ã„â€¡eÃ…Â¡ ih umiriti, slomiti ili mudro balansirati.",
        choices: [
            {
                text: "OdrÃ…Â¾aj drÃ…Â¾avni sabor i sasluÃ…Â¡aj ih",
                detail: "Dijalogu dajeÃ…Â¡ prednost nad pritiskom.",
                log: "Okupio si vlastelu i dao im oseÃ„â€¡aj uÃ„ÂeÃ…Â¡Ã„â€¡a u drÃ…Â¾avnim poslovima.",
                effects: { mudrost: 2, ugled: 1, stabilnost: 1 }
            },
            {
                text: "Zapreti kaznama i vojnom silom",
                detail: "Strah donosi red, ali ostavlja gorÃ„Âinu.",
                log: "UÃ„Âvrstio si autoritet, ali je strah ostavio trag.",
                effects: { snaga: 2, stabilnost: 1, ugled: -2 }
            },
            {
                text: "Dodeli im privilegije iz riznice",
                detail: "KupujeÃ…Â¡ mir, ali slabiÃ…Â¡ blagajnu i poÃ…Â¡tovanje.",
                log: "Privremeno si smirio nezadovoljne velikaÃ…Â¡e darovima i povlasticama.",
                effects: { zlato: -2, stabilnost: 1, ugled: -1 }
            },
            {
                text: "Postavi mudrog posrednika izmeÃ„â€˜u dvora i vlastele",
                detail: "Potrebna je mudrost da bi ovo uspelo.",
                log: "Posredovanjem si smanjio tenzije bez otvorenog sukoba i bez velikog troÃ…Â¡ka.",
                effects: { mudrost: 2, stabilnost: 1, ugled: 1 },
                requirements: { mudrost: 6 }
            }
        ]
    },
    {
        id: "trgovina",
        type: "Privreda",
        objective: "OjaÃ„Âaj ekonomiju bez ugroÃ…Â¾avanja stabilnosti i poverenja.",
        chapter: "Poglavlje II Ã¢â‚¬â€ IskuÃ…Â¡enja",
        characterState: "Riznica moÃ…Â¾e postati temelj napretka ili izvor novog bunta",
        title: "TrgovaÃ„Âki putevi",
        text: "Pojavila se prilika da ojaÃ„ÂaÃ…Â¡ trgovinu sa susednim oblastima. To moÃ…Â¾e doneti bogatstvo, ali i novu zavisnost od saveznika.",
        choices: [
            {
                text: "UloÃ…Â¾i u puteve i bezbednost trgovine",
                detail: "DugoroÃ„Âna dobit i jaÃ„Âa drÃ…Â¾ava.",
                log: "UloÃ…Â¾io si u razvoj trgovine i povezanost zemlje.",
                effects: { zlato: 2, mudrost: 1, stabilnost: 1 }
            },
            {
                text: "Oporezuj trgovce radi brzog prihoda",
                detail: "Puniji trezor, ali veÃ„â€¡e nezadovoljstvo.",
                log: "Riznica se brzo puni, ali nezadovoljstvo raste.",
                effects: { zlato: 2, ugled: -1, stabilnost: -1 }
            },
            {
                text: "Daj povlastice manastirskim posedima",
                detail: "JaÃ„ÂaÃ…Â¡ duhovne centre i njihov uticaj.",
                log: "OjaÃ„Âao si duhovne centre i njihovu ekonomsku osnovu.",
                effects: { vera: 2, zlato: -1, ugled: 1 }
            },
            {
                text: "Otvoreno pozovi strane trgovce pod zaÃ…Â¡titu krune",
                detail: "Veliki dobitak, ali samo ako je drÃ…Â¾ava dovoljno stabilna.",
                log: "Tvoj otvoreni poziv trgovcima osnaÃ…Â¾io je privredu i krunu uÃ„Âinio centrom trgovine.",
                effects: { zlato: 3, ugled: 1, stabilnost: 1 },
                requirements: { stabilnost: 5 }
            }
        ]
    },
    {
        id: "skola",
        type: "Znanje",
        objective: "OdluÃ„Âi da li Ã„â€¡eÃ…Â¡ ulagati u buduÃ„â€¡nost ili u trenutnu moÃ„â€¡.",
        chapter: "Poglavlje II Ã¢â‚¬â€ IskuÃ…Â¡enja",
        characterState: "Znanje se ne vidi odmah, ali menja vekove",
        title: "Pismenost i prepisivaÃ„Âi",
        text: "Na dvoru se predlaÃ…Â¾e da podrÃ…Â¾iÃ…Â¡ Ã…Â¡kole i prepisivanje knjiga. To ne donosi brzu korist, ali moÃ…Â¾e promeniti buduÃ„â€¡nost zemlje.",
        choices: [
            {
                text: "Osnuj Ã…Â¡kolu i podrÃ…Â¾i prepisivaÃ„Âe",
                detail: "Spora, ali velika investicija u buduÃ„â€¡nost drÃ…Â¾ave.",
                log: "PodrÃ…Â¾ao si znanje i pismenost kao temelj dugoroÃ„Âne snage drÃ…Â¾ave.",
                effects: { mudrost: 2, vera: 1, zlato: -1, ugled: 1 }
            },
            {
                text: "Usmeri sredstva vojsci umesto uÃ„Âenju",
                detail: "Brza snaga, ali manje mudrosti.",
                log: "Prednost si dao sili i brzom jaÃ„Âanju odbrane.",
                effects: { snaga: 2, mudrost: -1 }
            },
            {
                text: "Dopusti da to finansiraju manastiri i vlastela",
                detail: "SmanjujeÃ…Â¡ troÃ…Â¡ak, ali i zaslugu.",
                log: "Izbegao si veliki troÃ…Â¡ak, ali i punu zaslugu za taj razvoj.",
                effects: { zlato: 1, mudrost: 1 }
            },
            {
                text: "Okupi uÃ„Âene ljude na dvoru i pokreni prepisivaÃ„Âku radionicu",
                detail: "MoÃ„â€¡an kulturni potez ako imaÃ…Â¡ i veru i mudrost.",
                log: "Na dvoru si stvorio jezgro znanja koje Ã„â€¡e nadÃ…Â¾iveti tvoju vladavinu.",
                effects: { mudrost: 2, vera: 1, ugled: 2, zlato: -1 },
                requirements: { mudrost: 6, vera: 6 }
            }
        ]
    },
    {
        id: "bolest",
        type: "Kriza",
        objective: "Reaguj na krizu brzo, ali bez stvaranja panike i haosa.",
        chapter: "Poglavlje III Ã¢â‚¬â€ NasleÃ„â€˜e",
        characterState: "Narod sada gleda da li si zaista dorastao kruni",
        title: "Bolest u gradu",
        text: "U velikom gradu Ã…Â¡iri se bolest. Ako reagujeÃ…Â¡ presporo, izgubiÃ„â€¡eÃ…Â¡ ljude i poverenje. Ako reagujeÃ…Â¡ preoÃ…Â¡tro, nastaje strah i haos.",
        choices: [
            {
                text: "Organizuj pomoÃ„â€¡, leÃ„ÂiliÃ…Â¡ta i red",
                detail: "Skupo, ali humano i stabilno reÃ…Â¡enje.",
                log: "Brzo si organizovao pomoÃ„â€¡ i umirio grad.",
                effects: { stabilnost: 2, ugled: 1, zlato: -1, mudrost: 1 }
            },
            {
                text: "Zatvori grad i uvedi stroge mere",
                detail: "Red po cenu nelagodnosti i straha.",
                log: "OÃ„Âuvao si red, ali meÃ„â€˜u ljudima raste nelagodnost.",
                effects: { stabilnost: 1, snaga: 1, ugled: -1 }
            },
            {
                text: "Prepusti lokalnim stareÃ…Â¡inama da reÃ…Â¡e problem",
                detail: "Ã…Â tediÃ…Â¡ riznicu, ali rizikujeÃ…Â¡ raspad poverenja.",
                log: "Odgovornost si prebacio na lokalne voÃ„â€˜e, uz neizvestan ishod.",
                effects: { zlato: 1, stabilnost: -2, ugled: -1 }
            },
            {
                text: "Otvori manastirske bolnice i liÃ„Âno poÃ…Â¡alji pomoÃ„â€¡",
                detail: "Velik ugled i vera, ali zahteva resurse.",
                log: "Tvoja pomoÃ„â€¡ je spojila veru, red i brigu za narod u jednom snaÃ…Â¾nom potezu.",
                effects: { vera: 2, ugled: 2, stabilnost: 1, zlato: -2 },
                requirements: { zlato: 2 }
            }
        ]
    },
    {
        id: "zaduzbina",
        type: "NasleÃ„â€˜e",
        objective: "Izaberi kakav trag Ã…Â¾eliÃ…Â¡ da ostaviÃ…Â¡ za sobom.",
        chapter: "Poglavlje III Ã¢â‚¬â€ NasleÃ„â€˜e",
        characterState: "Poslednji potez odreÃ„â€˜uje kako Ã„â€¡e te istorija pamtiti",
        title: "Trenutak odluke o zaduÃ…Â¾bini",
        text: "Pred kraj vladavine dolazi vreme da pokaÃ…Â¾eÃ…Â¡ kakvo nasleÃ„â€˜e ostavljaÃ…Â¡. HoÃ„â€¡eÃ…Â¡ li podiÃ„â€¡i zaduÃ…Â¾binu, uÃ„Âvrstiti tvrÃ„â€˜avu ili saÃ„Âuvati bogatstvo za buduÃ„â€¡e sukobe?",
        choices: [
            {
                text: "Podigni manastir-zaduÃ…Â¾binu",
                detail: "Duhovno i kulturno nasleÃ„â€˜e za buduÃ„â€¡a pokolenja.",
                log: "OdluÃ„Âio si da ostaviÃ…Â¡ duhovni i kulturni trag za buduÃ„â€¡a pokolenja.",
                effects: { vera: 2, ugled: 2, zlato: -3 }
            },
            {
                text: "UÃ„Âvrsti tvrÃ„â€˜avu i vojsku",
                detail: "Sigurnost i odbrana postaju tvoj zavrÃ…Â¡ni peÃ„Âat.",
                log: "OdluÃ„Âio si da buduÃ„â€¡nost zemlje obezbediÃ…Â¡ kroz silu i odbranu.",
                effects: { snaga: 2, stabilnost: 1, zlato: -2 }
            },
            {
                text: "SaÃ„Âuvaj riznicu i odloÃ…Â¾i veliko delo",
                detail: "Oprez ti Ã„Âuva trezor, ali slabi istorijski trag.",
                log: "Izabrao si oprez, ali bez velikog simboliÃ„Âkog nasleÃ„â€˜a.",
                effects: { zlato: 1, ugled: -1 }
            },
            {
                text: "Podigni i zaduÃ…Â¾binu i Ã…Â¡kolu uz podrÃ…Â¡ku Crkve i dvora",
                detail: "NajveÃ„â€¡i zavrÃ…Â¡ni potez, ali samo za dostojnog vladara.",
                log: "Tvoja zavrÃ…Â¡na odluka spojila je veru, mudrost i ugled u delo koje Ã„â€¡e vekovima nositi tvoje ime.",
                effects: { vera: 2, mudrost: 2, ugled: 2, zlato: -3 },
                requirements: { vera: 8, mudrost: 8, ugled: 8, zlato: 3 }
            }
        ]
    }
];

const randomEvents = [
    {
        id: "karavan",
        title: "PljaÃ„Âka karavana",
        text: "Karavan sa robom napadnut je na putu. Ako reagujeÃ…Â¡, narod vidi tvoju odluÃ„Ânost, ali to koÃ…Â¡ta.",
        triggerTurns: [2, 5],
        chance: 0.5,
        choices: [
            { text: "PoÃ…Â¡alji straÃ…Â¾u i nadoknadi gubitke trgovcima", log: "Brza reakcija vratila je poverenje trgovaca u tvoju vlast.", effects: { ugled: 1, stabilnost: 1, zlato: -1 } },
            { text: "Kazni lokalne stareÃ…Â¡ine zbog nemara", log: "Pokazao si strogoÃ„â€¡u i zahtevao odgovornost na terenu.", effects: { snaga: 1, stabilnost: 1, ugled: -1 } },
            { text: "IgnoriÃ…Â¡i incident i Ã„Âuvaj riznicu", log: "Trgovci su tvoje Ã„â€¡utanje doÃ…Â¾iveli kao slabost ili ravnoduÃ…Â¡nost.", effects: { zlato: 1, ugled: -2 } }
        ]
    },
    {
        id: "pozar",
        title: "PoÃ…Â¾ar u varoÃ…Â¡i",
        text: "Veliki poÃ…Â¾ar zahvatio je deo varoÃ…Â¡i. Narod oÃ„Âekuje hitnu reakciju dvora.",
        triggerTurns: [3, 6],
        chance: 0.45,
        choices: [
            { text: "PoÃ…Â¡alji pomoÃ„â€¡ i sredstva za obnovu", log: "Dvorske zalihe i ljudi su brzo poslati u pomoÃ„â€¡.", effects: { ugled: 2, stabilnost: 1, zlato: -2 } },
            { text: "Organizuj lokalne vlasti da same saniraju Ã…Â¡tetu", log: "Prepustio si reÃ…Â¡avanje poÃ…Â¾ara niÃ…Â¾im strukturama vlasti.", effects: { zlato: 1, stabilnost: -1, ugled: -1 } }
        ]
    }
];

document.addEventListener("DOMContentLoaded", () => {
    bindUI();
    syncSettingsFromUI();
    forceInitialState();
});

function byId(id) {
    return document.getElementById(id);
}

function latToCyr(text) {
    let result = String(text || "");
    LAT_TO_CYR_PAIRS.forEach(([lat, cyr]) => {
        result = result.replaceAll(lat, cyr);
    });
    return result.replace(/[A-Za-z\u0110\u0111\u017D\u017E\u0106\u0107\u010C\u010D\u0160\u0161]/g, ch => LAT_TO_CYR[ch] || ch);
}

function cyrToLat(text) {
    return String(text || "").replace(/[\u0400-\u04FF]/g, ch => CYR_TO_LAT[ch] || ch);
}

function convertText(text, mode) {
    return mode === "cyr" ? latToCyr(cyrToLat(text)) : cyrToLat(text);
}

function shouldSkipScriptConversion(el) {
    if (!el) return true;
    return ["SCRIPT", "STYLE", "TEXTAREA", "CODE", "PRE"].includes(el.tagName);
}

function convertNodeScript(node, mode) {
    if (node.nodeType === Node.TEXT_NODE) {
        if (!node.nodeValue.trim() || shouldSkipScriptConversion(node.parentElement)) return;
        node.nodeValue = convertText(node.nodeValue, mode);
        return;
    }

    if (node.nodeType !== Node.ELEMENT_NODE || shouldSkipScriptConversion(node)) return;

    ["title", "aria-label", "placeholder"].forEach(attr => {
        if (node.hasAttribute(attr)) {
            node.setAttribute(attr, convertText(node.getAttribute(attr), mode));
        }
    });

    node.childNodes.forEach(child => convertNodeScript(child, mode));
}

function applyScriptMode(mode) {
    scriptMode = mode;
    localStorage.setItem("pv_script_mode", mode);
    convertNodeScript(document.body, mode);
    byId("scriptLatBtn")?.classList.toggle("is-active", mode === "lat");
    byId("scriptCyrBtn")?.classList.toggle("is-active", mode === "cyr");
}

function setActiveScreen(screenId) {
    ["startScreen", "gameScreen", "endScreen"].forEach(id => {
        const el = byId(id);
        if (!el) return;

        el.classList.remove("active");
        el.classList.add("hidden");
    });

    const target = byId(screenId);
    if (target) {
        target.classList.remove("hidden");
        target.classList.add("active");
    }
}

function forceInitialState() {
    setActiveScreen("startScreen");
    closePauseModal(true);
    closeHelpModal(true);

    const settingsBox = byId("startSettingsBox");
    const guideBox = byId("guideBox");

    if (settingsBox) settingsBox.classList.add("hidden");
    if (guideBox) guideBox.classList.remove("open");
}

function isGameScreenActive() {
    return byId("gameScreen")?.classList.contains("active");
}

function isStartScreenActive() {
    return byId("startScreen")?.classList.contains("active");
}

function bindUI() {
    byId("scriptLatBtn")?.addEventListener("click", () => applyScriptMode("lat"));
    byId("scriptCyrBtn")?.addEventListener("click", () => applyScriptMode("cyr"));

    byId("startGameBtn")?.addEventListener("click", startGame);
    byId("toggleGuideBtn")?.addEventListener("click", toggleGuide);
    byId("openSettingsBtn")?.addEventListener("click", toggleStartSettings);

    byId("fullscreenBtn")?.addEventListener("click", toggleFullscreen);
    byId("topFullscreenBtn")?.addEventListener("click", toggleFullscreen);
    byId("floatingHelpBtn")?.addEventListener("click", openHelpModal);
    byId("closeHelpBtn")?.addEventListener("click", closeHelpModal);
    byId("modalFullscreenBtn")?.addEventListener("click", toggleFullscreen);
    byId("endFullscreenBtn")?.addEventListener("click", toggleFullscreen);

    byId("pauseBtn")?.addEventListener("click", () => {
        if (isGameScreenActive()) openPauseModal();
    });

    byId("backToMenuBtn")?.addEventListener("click", backToMenu);
    byId("restartBtn")?.addEventListener("click", startGame);
    byId("playAgainBtn")?.addEventListener("click", startGame);
    byId("endMenuBtn")?.addEventListener("click", backToMenu);

    byId("resumeBtn")?.addEventListener("click", () => closePauseModal());
    byId("modalRestartBtn")?.addEventListener("click", () => {
        closePauseModal(true);
        startGame();
    });
    byId("modalMenuBtn")?.addEventListener("click", () => {
        closePauseModal(true);
        backToMenu();
    });

    [
        "animToggle",
        "effectsToggle",
        "warningToggle",
        "pauseAnimToggle",
        "pauseEffectsToggle",
        "pauseWarningToggle"
    ].forEach(id => {
        byId(id)?.addEventListener("change", syncSettingsFromUI);
    });

    document.addEventListener("keydown", (e) => {
        if (e.key !== "Escape") return;
        if (!isGameScreenActive()) return;

        const help = byId("helpModal");
        if (help?.classList.contains("active")) {
            closeHelpModal();
            return;
        }

        const pause = byId("pauseModal");
        if (pause?.classList.contains("active")) {
            closePauseModal();
        } else {
            openPauseModal();
        }
    });

    byId("pauseModal")?.addEventListener("click", (e) => {
        if (e.target === byId("pauseModal")) {
            closePauseModal();
        }
    });

    applyScriptMode(scriptMode);
}

function setCheckboxValue(id, value) {
    const el = byId(id);
    if (el) el.checked = value;
}

function syncSettingsFromUI() {
    const anim =
        byId("pauseAnimToggle")?.checked ??
        byId("animToggle")?.checked ??
        true;

    const effects =
        byId("pauseEffectsToggle")?.checked ??
        byId("effectsToggle")?.checked ??
        true;

    const warnings =
        byId("pauseWarningToggle")?.checked ??
        byId("warningToggle")?.checked ??
        true;

    settings.animations = !!anim;
    settings.showEffects = !!effects;
    settings.showWarnings = !!warnings;

    setCheckboxValue("animToggle", settings.animations);
    setCheckboxValue("effectsToggle", settings.showEffects);
    setCheckboxValue("warningToggle", settings.showWarnings);

    setCheckboxValue("pauseAnimToggle", settings.animations);
    setCheckboxValue("pauseEffectsToggle", settings.showEffects);
    setCheckboxValue("pauseWarningToggle", settings.showWarnings);
}

function initialState() {
    return { ...GAME_CONFIG.startStats };
}

function normalizeEffect(effect = {}) {
    return {
        snaga: effect.snaga || 0,
        mudrost: effect.mudrost || 0,
        vera: effect.vera || 0,
        ugled: effect.ugled || 0,
        zlato: effect.zlato || 0,
        stabilnost: effect.stabilnost || 0
    };
}

function normalizeScene(scene) {
    if (!scene) return null;

    return {
        id: scene.id,
        type: scene.type,
        objective: scene.objective,
        chapter: scene.chapter,
        characterState: scene.characterState,
        title: scene.title,
        text: scene.description || scene.text,
        choices: (scene.options || scene.choices || []).map(option => ({
            id: option.id,
            text: option.text,
            detail: option.detail,
            log: option.consequenceText || option.log,
            effects: normalizeEffect(option.effect || option.effects),
            requirements: option.requirements ? normalizeEffect(option.requirements) : null
        }))
    };
}

async function apiStartGame() {
    const response = await fetch(`${JAVA_GAME_API_BASE}/start`, { method: "POST" });
    if (!response.ok) throw new Error("Java servis nije vratio uspesan start odgovor.");
    return response.json();
}

async function apiChooseOption(optionId) {
    const response = await fetch(`${JAVA_GAME_API_BASE}/choose`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ sessionId: currentSessionId, optionId })
    });

    if (!response.ok) throw new Error("Java servis nije vratio uspesan choose odgovor.");
    return response.json();
}

async function startGame() {
    try {
        const data = await apiStartGame();

        currentSessionId = data.sessionId;
        gameState = data.playerState || initialState();
        currentTurn = data.currentTurn || 1;
        GAME_CONFIG.maxTurns = data.maxTurns || GAME_CONFIG.maxTurns;
        currentScene = normalizeScene(data.scenario);
        lastEnding = null;
    } catch (error) {
        console.error(error);
        toast("Java servis nije dostupan. Pokreni ruler-game-service na portu 8081.", "minus");
        return;
    }

    byId("startSettingsBox")?.classList.add("hidden");
    byId("guideBox")?.classList.remove("open");

    clearLog();
    addLog("Tvoja vladavina je po\u010Dela. Svaka odluka \u0107e oblikovati sudbinu dr\u017Eave.");
    addLog("Cilj: sa\u010Duvaj stabilnost dr\u017Eave i izgradi nasle\u0111e dostojno krune.");

    updateStatsUI();
    updateEndingHint();
    clearEffectsPreview();
    hideWarning();
    updateCharacterMood();
    closePauseModal(true);

    setActiveScreen("gameScreen");
    renderScene();
    toast("Nova partija je po\u010Dela.");
}

function backToMenu() {
    closePauseModal(true);
    clearEffectsPreview();
    currentSessionId = null;
    currentScene = null;
    lastEnding = null;
    forceInitialState();
}

function toggleGuide() {
    if (!isStartScreenActive()) return;
    byId("guideBox")?.classList.toggle("open");
}

function toggleStartSettings() {
    if (!isStartScreenActive()) return;
    byId("startSettingsBox")?.classList.toggle("hidden");
}

function openPauseModal() {
    if (!isGameScreenActive()) return;
    syncSettingsFromUI();

    const modal = byId("pauseModal");
    if (!modal) return;

    modal.classList.remove("hidden");
    modal.classList.add("active");
}

function closePauseModal(silent = false) {
    const modal = byId("pauseModal");
    if (!modal) return;

    modal.classList.remove("active");
    modal.classList.add("hidden");

    if (!silent) syncSettingsFromUI();
}


function openHelpModal() {
    const modal = byId("helpModal");
    if (!modal) return;

    modal.classList.remove("hidden");
    modal.classList.add("active");
    modal.setAttribute("aria-hidden", "false");
    applyScriptMode(scriptMode);
}

function closeHelpModal(silent = false) {
    const modal = byId("helpModal");
    if (!modal) return;

    modal.classList.remove("active");
    modal.classList.add("hidden");
    modal.setAttribute("aria-hidden", "true");

    if (!silent) applyScriptMode(scriptMode);
}
function toggleFullscreen() {
    const root = document.documentElement;
    if (!document.fullscreenElement) {
        root.requestFullscreen?.();
    } else {
        document.exitFullscreen?.();
    }
}

function renderScene() {
    if (currentTurn > GAME_CONFIG.maxTurns) {
        finishGame();
        return;
    }

    const scene = currentScene || scenes[currentTurn - 1];
    if (!scene) {
        finishGame();
        return;
    }

    const panel = byId("storyPanel");
    if (settings.animations) panel?.classList.add("is-switching");

    setTimeout(() => {
        byId("sceneType").innerText = scene.type;
        byId("sceneTitle").innerText = scene.title;
        byId("sceneText").innerText = scene.text;
        byId("objectiveText").innerText = scene.objective || "Vladaj pa\u017Eljivo i o\u010Duvaj ravnote\u017Eu dr\u017Eave.";
        byId("progressLabel").innerText = `Korak ${currentTurn} od ${GAME_CONFIG.maxTurns}`;
        byId("chapterLabel").innerText = scene.chapter || "Put vladara";
        byId("characterState").innerText = scene.characterState || "Sudbina dr\u017Eave je u tvojim rukama";

        renderChoices(scene.choices);
        updateWarnings();
        updateCharacterMood();
        applyScriptMode(scriptMode);

        if (settings.animations) panel?.classList.remove("is-switching");
    }, settings.animations ? 180 : 0);
}

function renderChoices(choices) {
    const box = byId("choicesBox");
    if (!box) return;

    box.innerHTML = "";

    choices.forEach(choice => {
        const btn = document.createElement("button");
        btn.type = "button";
        btn.className = "pv-choice-btn";

        const check = meetsChoiceRequirements(choice);

        if (!check.allowed) {
            btn.classList.add("is-disabled");
            btn.disabled = true;
        }

        const effectsHtml = formatEffectsHtml(choice.effects);
        const lockHtml = !check.allowed
            ? `<span class="pv-lock-note">ZakljuÄano: ${check.reason}</span>`
            : "";

        btn.innerHTML = `
            <span class="pv-choice-text">${choice.text}</span>
            ${choice.detail ? `<span class="pv-choice-detail">${choice.detail}</span>` : ""}
            ${effectsHtml ? `<span class="pv-choice-effects">${effectsHtml}</span>` : ""}
            ${lockHtml}
        `;

        btn.addEventListener("mouseenter", () => showEffectsPreview(choice.effects));
        btn.addEventListener("mouseleave", clearEffectsPreview);
        btn.addEventListener("focus", () => showEffectsPreview(choice.effects));

        btn.addEventListener("click", () => {
            if (!check.allowed) return;
            applyChoice(choice);
        });

        box.appendChild(btn);
    });
}

function meetsChoiceRequirements(choice) {
    if (choice.requirements) {
        for (const stat in choice.requirements) {
            const requiredValue = choice.requirements[stat] || 0;
            if (requiredValue > 0 && (gameState[stat] ?? 0) < requiredValue) {
                return {
                    allowed: false,
                    reason: `${labelForStat(stat)} mora biti najmanje ${requiredValue}`
                };
            }
        }
    }

    if (choice.effects) {
        for (const stat in choice.effects) {
            const next = (gameState[stat] ?? 0) + choice.effects[stat];
            if (next < GAME_CONFIG.minStat) {
                return {
                    allowed: false,
                    reason: `${labelForStat(stat)} bi pao ispod dozvoljene granice`
                };
            }
        }
    }

    return { allowed: true, reason: "" };
}

function showEffectsPreview(effects) {
    if (!settings.showEffects) {
        clearEffectsPreview();
        return;
    }

    const box = byId("effectsPreview");
    if (!box) return;

    const chips = Object.entries(effects || {}).filter(([, value]) => value !== 0).map(([key, value]) => {
        const sign = value > 0 ? "+" : "";
        return `<span class="pv-effect-chip ${value >= 0 ? "plus" : "minus"}">${labelForStat(key)} ${sign}${value}</span>`;
    });

    if (!chips.length) {
        clearEffectsPreview();
        return;
    }

    box.innerHTML = `
        <div class="pv-effects-title">Efekti odluke</div>
        <div class="pv-effects-row">${chips.join("")}</div>
    `;
    box.classList.remove("hidden");
}

function clearEffectsPreview() {
    const box = byId("effectsPreview");
    if (!box) return;
    box.innerHTML = "";
    box.classList.add("hidden");
}

function formatEffects(effects) {
    return Object.entries(effects)
        .filter(([, value]) => value !== 0)
        .map(([key, value]) => `${labelForStat(key)} ${value > 0 ? "+" : ""}${value}`)
        .join(" â€¢ ");
}

function formatEffectsHtml(effects) {
    return Object.entries(effects || {})
        .filter(([, value]) => value !== 0)
        .map(([key, value]) => {
            const sign = value > 0 ? "+" : "";
            const tone = value > 0 ? "plus" : "minus";
            return `<span class="pv-mini-effect ${tone}">${labelForStat(key)} ${sign}${value}</span>`;
        })
        .join("");
}

function labelForStat(key) {
    return {
        snaga: "Snaga",
        mudrost: "Mudrost",
        vera: "Vera",
        ugled: "Ugled",
        zlato: "Zlato",
        stabilnost: "Stabilnost"
    }[key] || key;
}

async function applyChoice(choice) {
    const deltas = [];
    const oldState = { ...gameState };

    if (currentSessionId && choice.id) {
        try {
            const response = await apiChooseOption(choice.id);

            if (response.playerState) {
                gameState = response.playerState;
            }

            ["snaga", "mudrost", "vera", "ugled", "zlato", "stabilnost"].forEach(stat => {
                const delta = (gameState[stat] ?? 0) - (oldState[stat] ?? 0);
                if (delta !== 0) deltas.push({ stat, value: delta });
            });

            addLog(response.consequenceText || choice.log || choice.text);
            addDetailedLog(choice.text, deltas);
            updateStatsUI();
            updateEndingHint();
            updateWarnings();
            updateCharacterMood();
            clearEffectsPreview();
            showDeltaToasts(deltas);

            if (response.gameOver) {
                lastEnding = response;
                finishGame(false, response);
                return;
            }

            currentTurn = response.currentTurn || currentTurn + 1;
            currentScene = normalizeScene(response.nextScenario);
            renderScene();
            return;
        } catch (error) {
            console.error(error);
            toast("Veza sa Java servisom je prekinuta.", "minus");
            return;
        }
    }

    for (const stat in choice.effects) {
        const oldValue = gameState[stat];
        gameState[stat] += choice.effects[stat];
        if (gameState[stat] < GAME_CONFIG.minStat) gameState[stat] = GAME_CONFIG.minStat;
        const delta = gameState[stat] - oldValue;
        if (delta !== 0) deltas.push({ stat, value: delta });
    }

    addLog(choice.log);
    addDetailedLog(choice.text, deltas);
    updateStatsUI();
    updateEndingHint();
    updateWarnings();
    updateCharacterMood();
    clearEffectsPreview();
    showDeltaToasts(deltas);

    if (isImmediateLoss()) {
        finishGame(true);
        return;
    }

    const randomEvent = getTriggeredRandomEvent(currentTurn);
    currentTurn++;

    if (randomEvent) {
        renderRandomEvent(randomEvent);
        return;
    }

    renderScene();
}

function getTriggeredRandomEvent(turn) {
    const possible = randomEvents.filter(event => event.triggerTurns.includes(turn));
    if (!possible.length) return null;
    const picked = possible[Math.floor(Math.random() * possible.length)];
    return Math.random() <= picked.chance ? picked : null;
}

function renderRandomEvent(event) {
    byId("sceneType").innerText = "Neo\u010Dekivani doga\u0111aj";
    byId("sceneTitle").innerText = event.title;
    byId("sceneText").innerText = event.text;
    byId("objectiveText").innerText = "Reaguj na iznenadni doga\u0111aj bez naru\u0161avanja toka vladavine.";
    byId("progressLabel").innerText = `Vanredni doga\u0111aj posle poteza ${currentTurn - 1}`;
    byId("characterState").innerText = "Iznenadna kriza tra\u017Ei brzu i promi\u0161ljenu odluku";

    const box = byId("choicesBox");
    box.innerHTML = "";

    event.choices.forEach(choice => {
        const btn = document.createElement("button");
        btn.type = "button";
        btn.className = "pv-choice-btn";

        btn.innerHTML = `
            <span class="pv-choice-text">${choice.text}</span>
            <small>${formatEffects(choice.effects)}</small>
        `;

        btn.addEventListener("mouseenter", () => showEffectsPreview(choice.effects));
        btn.addEventListener("mouseleave", clearEffectsPreview);
        btn.addEventListener("click", () => applyRandomChoice(choice, event));

        box.appendChild(btn);
    });

    toast("Vanredni doga\u0111aj!");
}

function applyRandomChoice(choice, event) {
    const deltas = [];

    for (const stat in choice.effects) {
        const oldValue = gameState[stat];
        gameState[stat] += choice.effects[stat];
        if (gameState[stat] < GAME_CONFIG.minStat) gameState[stat] = GAME_CONFIG.minStat;
        const delta = gameState[stat] - oldValue;
        if (delta !== 0) deltas.push({ stat, value: delta });
    }

    addLog(`Vanredni doga\u0111aj: ${event.title}`);
    addLog(choice.log);
    addDetailedLog(choice.text, deltas);
    updateStatsUI();
    updateEndingHint();
    updateWarnings();
    updateCharacterMood();
    clearEffectsPreview();
    showDeltaToasts(deltas);

    if (isImmediateLoss()) {
        finishGame(true);
        return;
    }

    renderScene();
}

function addDetailedLog(choiceText, deltas) {
    if (!deltas.length) return;

    const formatted = deltas
        .map(item => `${labelForStat(item.stat)} ${item.value > 0 ? "+" : ""}${item.value}`)
        .join(" \u2022 ");

    addLog(`Izbor: ${choiceText}`);
    addLog(`Posledice: ${formatted}`);
}

function showDeltaToasts(deltas) {
    deltas.forEach((item, index) => {
        setTimeout(() => {
            toast(`${labelForStat(item.stat)} ${item.value > 0 ? "+" : ""}${item.value}`, item.value >= 0 ? "plus" : "minus");
        }, index * 120);
    });
}

function toast(message, type = "neutral") {
    const stack = byId("toastStack");
    if (!stack) return;

    const el = document.createElement("div");
    el.className = `pv-toast ${type}`;
    el.innerText = message;
    stack.appendChild(el);

    setTimeout(() => el.classList.add("show"), 10);
    setTimeout(() => {
        el.classList.remove("show");
        setTimeout(() => el.remove(), 260);
    }, 2200);
}

function updateStatsUI() {
    ["snaga", "mudrost", "vera", "ugled", "zlato", "stabilnost"].forEach(stat => {
        const valueEl = byId(`stat-${stat}`);
        const cardEl = document.querySelector(`.pv-stat[data-stat="${stat}"]`);
        if (!valueEl || !cardEl) return;

        valueEl.innerText = gameState[stat];
        cardEl.classList.remove("is-critical", "is-good");

        if (gameState[stat] <= GAME_CONFIG.criticalThreshold) {
            cardEl.classList.add("is-critical");
        } else if (gameState[stat] >= GAME_CONFIG.strongThreshold) {
            cardEl.classList.add("is-good");
        }
    });

    byId("stat-turn").innerText = `${Math.min(currentTurn, GAME_CONFIG.maxTurns)} / ${GAME_CONFIG.maxTurns}`;
}

function updateWarnings() {
    const warningBar = byId("warningBar");
    if (!warningBar) return;

    if (!settings.showWarnings) {
        hideWarning();
        return;
    }

    const warnings = [];
    if (gameState.stabilnost <= GAME_CONFIG.criticalThreshold) warnings.push("Stabilnost dr\u017Eave je kriti\u010Dno niska.");
    if (gameState.ugled <= GAME_CONFIG.criticalThreshold) warnings.push("Narod i dvor gube poverenje u tvoju vlast.");
    if (gameState.zlato <= 1) warnings.push("Riznica je skoro prazna.");
    if (gameState.vera <= 1) warnings.push("Duhovni autoritet krune je ozbiljno uzdrman.");

    if (!warnings.length) {
        hideWarning();
        return;
    }

    warningBar.innerText = warnings.join(" ");
    warningBar.classList.remove("hidden");
}

function hideWarning() {
    if (byId("warningBar")) {
        byId("warningBar").classList.add("hidden");
        byId("warningBar").innerText = "";
    }
}

function updateCharacterMood() {
    const nameEl = byId("characterName");
    const stateEl = byId("characterState");
    if (!nameEl || !stateEl) return;

    nameEl.innerText = getRulerTitle();

    if (gameState.stabilnost <= 2 || gameState.ugled <= 2) {
        stateEl.innerText = "Kruna je uzdrmana i dr\u017Eava ose\u0107a tvoju slabost";
        return;
    }

    if (gameState.vera >= 8 && gameState.mudrost >= 8) {
        stateEl.innerText = "Tvoja vlast odi\u0161e smireno\u0161\u0107u, dostojanstvom i duhovnom snagom";
        return;
    }

    if (gameState.snaga >= 8) {
        stateEl.innerText = "Dvor i vojska u tebi vide odlu\u010Dnog i sna\u017Enog vladara";
        return;
    }

    if (gameState.ugled >= 8) {
        stateEl.innerText = "Narod sve vi\u0161e veruje tvojoj kruni i tvojoj re\u010Di";
        return;
    }

    stateEl.innerText = "Sudbina dr\u017Eave je i dalje u tvojim rukama";
}

function getRulerTitle() {
    if (gameState.vera >= gameState.snaga && gameState.vera >= gameState.mudrost) return "Duhovni vladar";
    if (gameState.mudrost >= gameState.snaga) return "Mudri vladar";
    return "Ratni\u010Dki vladar";
}

function updateEndingHint() {
    const hint = byId("endingHint");
    if (!hint) return;

    const missing = [];
    if (gameState.ugled < GAME_CONFIG.winRequirements.ugled) missing.push("ugled");
    if (gameState.vera < GAME_CONFIG.winRequirements.vera) missing.push("vera");
    if (gameState.mudrost < GAME_CONFIG.winRequirements.mudrost) missing.push("mudrost");
    if (gameState.stabilnost < GAME_CONFIG.winRequirements.stabilnost) missing.push("stabilnost");
    if (gameState.zlato < GAME_CONFIG.winRequirements.zlato) missing.push("zlato");

    hint.innerText = !missing.length
        ? "Na dobrom si putu ka velikom zavr\u0161etku"
        : `Jo\u0161 mora\u0161 da oja\u010Da\u0161: ${missing.join(", ")}`;
}

function isImmediateLoss() {
    return gameState.stabilnost <= 0 || gameState.ugled <= 0;
}

function finishGame(earlyLoss = false, serverEnding = null) {
    const result = serverEnding
        ? {
            badge: serverEnding.endingBadge || "Kraj vladavine",
            title: serverEnding.endingTitle || serverEnding.gameOverMessage || "Igra je zavr\u0161ena",
            text: serverEnding.gameOverMessage || "Tvoje odluke oblikovale su sudbinu dr\u017Eave.",
            summary: serverEnding.endingSummary || serverEnding.consequenceText || "Vladavina je zavr\u0161ena."
        }
        : getEndingResult(earlyLoss);

    byId("endBadge").innerText = result.badge;
    byId("endTitle").innerText = result.title;
    byId("endText").innerText = result.text;
    byId("endSummary").innerHTML = `
        <div class="pv-end-summary-box">
            <strong>Rezime vladavine:</strong>
            <span>${result.summary}</span>
        </div>
    `;

    byId("endStats").innerHTML = `
        <div class="pv-end-stat"><span>Snaga</span><strong>${gameState.snaga}</strong></div>
        <div class="pv-end-stat"><span>Mudrost</span><strong>${gameState.mudrost}</strong></div>
        <div class="pv-end-stat"><span>Vera</span><strong>${gameState.vera}</strong></div>
        <div class="pv-end-stat"><span>Ugled</span><strong>${gameState.ugled}</strong></div>
        <div class="pv-end-stat"><span>Zlato</span><strong>${gameState.zlato}</strong></div>
        <div class="pv-end-stat"><span>Stabilnost</span><strong>${gameState.stabilnost}</strong></div>
    `;

    closePauseModal(true);
    setActiveEnd();
    toast(result.title);
}

function setActiveEnd() {
    byId("startScreen")?.classList.add("hidden");
    byId("gameScreen")?.classList.add("hidden");
    byId("endScreen")?.classList.remove("hidden");
    byId("endScreen")?.classList.add("active");
}

function getEndingResult(earlyLoss) {
    if (earlyLoss) {
        return {
            badge: "Pad drÃ…Â¾ave",
            title: "Izgubio si poverenje naroda",
            text: "Tvoja vlast nije izdrÃ…Â¾ala teret loÃ…Â¡ih odluka. Stabilnost zemlje i poverenje ljudi su se uruÃ…Â¡ili pre nego Ã…Â¡to si uspeo da ostaviÃ…Â¡ veliko delo.",
            summary: "DrÃ…Â¾ava je poklekla pre kraja vladavine, a narod je prestao da veruje tvojoj kruni."
        };
    }

    const grandWin =
        gameState.stabilnost >= 5 &&
        gameState.ugled >= 9 &&
        gameState.vera >= 9 &&
        gameState.mudrost >= 9 &&
        gameState.zlato >= 3;

    const win =
        gameState.stabilnost >= GAME_CONFIG.winRequirements.stabilnost &&
        gameState.ugled >= GAME_CONFIG.winRequirements.ugled &&
        gameState.vera >= GAME_CONFIG.winRequirements.vera &&
        gameState.mudrost >= GAME_CONFIG.winRequirements.mudrost &&
        gameState.zlato >= GAME_CONFIG.winRequirements.zlato;

    if (grandWin) {
        return {
            badge: "Zlatno nasleÃ„â€˜e",
            title: "Ostao si upamÃ„â€¡en kao veliki vladar i ktitor",
            text: "Tvoja vladavina spojila je duhovnost, mudrost, ugled i drÃ…Â¾avniÃ„Âku snagu. Iza sebe si ostavio delo koje nadÃ…Â¾ivljava vekove.",
            summary: "Postigao si najviÃ…Â¡i zavrÃ…Â¡etak i ostavio nasleÃ„â€˜e dostojno velikih vladara."
        };
    }

    if (win && gameState.vera >= gameState.snaga && gameState.vera >= gameState.mudrost) {
        return {
            badge: "Duhovno nasleÃ„â€˜e",
            title: "Postao si veliki ktitor",
            text: "Uspeo si da oÃ„ÂuvaÃ…Â¡ drÃ…Â¾avu i ostaviÃ…Â¡ zaduÃ…Â¾binu po kojoj Ã„â€¡eÃ…Â¡ biti pamÃ„â€¡en. Tvoja vera, ugled i mudrost nadÃ…Â¾iveli su vreme.",
            summary: "Ostavio si trag ne samo u drÃ…Â¾avnim poslovima, veÃ„â€¡ i u duhovnom i kulturnom pamÃ„â€¡enju naroda."
        };
    }

    if (win && gameState.mudrost >= gameState.snaga) {
        return {
            badge: "Mudra vladavina",
            title: "Postao si mudri vladar",
            text: "DrÃ…Â¾avu si vodio paÃ…Â¾ljivo, promiÃ…Â¡ljeno i stabilno. Narod te pamti kao vladara koji je umeo da balansira izmeÃ„â€˜u sile, vere i razuma.",
            summary: "Vladao si razumno, sa merom i oseÃ„â€¡ajem za dugoroÃ„Âno dobro drÃ…Â¾ave."
        };
    }

    if (win) {
        return {
            badge: "Gvozdena kruna",
            title: "Postao si ratniÃ„Âki vladar",
            text: "DrÃ…Â¾avu si odrÃ…Â¾ao snaÃ…Â¾nom i sigurnom. Tvoja vlast poÃ„Âivala je na sili, redu i odluÃ„Ânosti, a ime ti je ostalo u hronikama.",
            summary: "Tvoj peÃ„Âat ostao je u vojnoj snazi, redu i zaÃ…Â¡titi zemlje."
        };
    }

    if (gameState.stabilnost >= 4 && gameState.ugled >= 5) {
        return {
            badge: "PreÃ…Â¾ivela vladavina",
            title: "SaÃ„Âuvao si presto, ali ne i veliko nasleÃ„â€˜e",
            text: "DrÃ…Â¾ava je opstala, ali tvoje ime nije zasijalo punim sjajem. Nedostajao je joÃ…Â¡ jedan korak ka velikom delu.",
            summary: "Presto je opstao, ali istorija te nije upamtila meÃ„â€˜u najveÃ„â€¡ima."
        };
    }

    return {
        badge: "Slabo nasleÃ„â€˜e",
        title: "Vladavina bez velikog traga",
        text: "Uspeo si da izdrÃ…Â¾iÃ…Â¡ do kraja, ali bez snage, vere ili mudrosti potrebne da te istorija posebno zapamti.",
        summary: "IzdrÃ…Â¾ao si do kraja, ali bez dela koje bi nadÃ…Â¾ivelo tvoje vreme."
    };
}

function addLog(text) {
    const log = byId("gameLog");
    if (!log) return;

    const item = document.createElement("div");
    item.className = "pv-log-item";

    const time = document.createElement("strong");
    time.innerText = `Potez ${Math.min(currentTurn, GAME_CONFIG.maxTurns)}: `;

    const message = document.createElement("span");
    message.innerText = text;

    item.appendChild(time);
    item.appendChild(message);
    log.prepend(item);
}

function clearLog() {
    const log = byId("gameLog");
    if (log) log.innerHTML = "";
}


/* =========================
   FINAL FLOW + ESCAPE ROOM
   ========================= */

let lastMainResult = null;
let escapeIndex = 0;
let escapeMistakes = 0;
let escapeSecondsLeft = 300;
let escapeTimerInterval = null;

const escapeRooms = [
    {
        title: "Manastirska odaja",
        type: "Istorijsko pitanje",
        clue: "Na pergamentu pi\u0161e: po\u010Detak velike zadu\u017Ebine vezuje se za rodona\u010Delnika dinastije.",
        question: "Ko je ktitor manastira Studenica?",
        answers: ["stefan nemanja", "nemanja"],
        hint: "Tra\u017Ei se ime vladara iz dinastije Nemanji\u0107a."
    },
    {
        title: "Riznica krune",
        type: "Kod",
        clue: "Tri vrednosti iz tvoje vladavine otvaraju kov\u010Deg: mudrost, snaga i vera. Upi\u0161i sveti broj koji ozna\u010Dava puno\u0107u i zavr\u0161etak isku\u0161enja.",
        question: "Koji broj otklju\u010Dava drugi pe\u010Dat?",
        answers: ["12", "dvanaest"],
        hint: "Igra Put vladara ima toliko glavnih poteza."
    },
    {
        title: "Dvorana nasle\u0111a",
        type: "Finalni trag",
        clue: "Poslednji natpis ka\u017Ee: vlast bez naroda je prazna, vlast bez vere je kratka, vlast bez znanja je slepa.",
        question: "Koja vrednost najbolje opisuje vladara koji meri pre nego \u0161to odlu\u010Di?",
        answers: ["mudrost", "mudrost ", "mudri vladar"],
        hint: "To je jedan od glavnih parametara igre."
    }
];

setActiveScreen = function(screenId) {
    ["startScreen", "introScreen", "gameScreen", "endScreen", "unlockScreen", "escapeScreen", "finalScreen"].forEach(id => {
        const el = byId(id);
        if (!el) return;
        el.classList.remove("active");
        el.classList.add("hidden");
    });

    const target = byId(screenId);
    if (target) {
        target.classList.remove("hidden");
        target.classList.add("active");
    }
}

startGame = function() {
    closePauseModal(true);
    closeHelpModal(true);
    stopEscapeTimer();
    setActiveScreen("introScreen");
    applyScriptMode(scriptMode);
}

async function startDecisionGame() {
    try {
        const data = await apiStartGame();

        currentSessionId = data.sessionId;
        gameState = data.playerState || initialState();
        currentTurn = data.currentTurn || 1;
        GAME_CONFIG.maxTurns = data.maxTurns || GAME_CONFIG.maxTurns;
        currentScene = normalizeScene(data.scenario);
        lastEnding = null;
        lastMainResult = null;
    } catch (error) {
        console.error(error);
        toast("Java servis nije dostupan. Pokreni ruler-game-service na portu 8081.", "minus");
        return;
    }

    byId("startSettingsBox")?.classList.add("hidden");
    byId("guideBox")?.classList.remove("open");

    clearLog();
    addLog("Tvoja vladavina je po\u010Dela. Svaka odluka \u0107e oblikovati sudbinu dr\u017Eave.");
    addLog("Cilj: sa\u010Duvaj stabilnost dr\u017Eave i izgradi nasle\u0111e dostojno krune.");

    updateStatsUI();
    updateEndingHint();
    clearEffectsPreview();
    hideWarning();
    updateCharacterMood();
    closePauseModal(true);

    setActiveScreen("gameScreen");
    renderScene();
    toast("Nova partija je po\u010Dela.");
}

backToMenu = function() {
    closePauseModal(true);
    closeHelpModal(true);
    stopEscapeTimer();
    clearEffectsPreview();
    currentSessionId = null;
    currentScene = null;
    lastEnding = null;
    lastMainResult = null;
    forceInitialState();
}

setActiveEnd = function() {
    setActiveScreen("endScreen");
}

function isMainVictory() {
    return gameState.stabilnost >= 4 && gameState.ugled >= 5 && gameState.vera >= 5 && gameState.mudrost >= 5;
}

finishGame = function(earlyLoss = false, serverEnding = null) {
    const forcedLoss = earlyLoss || isImmediateLoss() || currentTurn < GAME_CONFIG.maxTurns;
    const result = getEndingResult(forcedLoss);
    lastMainResult = result;

    byId("endBadge").innerText = result.badge;
    byId("endTitle").innerText = result.title;
    byId("endText").innerText = result.text;
    byId("endSummary").innerHTML = `
        <div class="pv-end-summary-box">
            <strong>Rezime vladavine:</strong>
            <span>${result.summary}</span>
        </div>
    `;

    byId("endStats").innerHTML = buildStatBars();

    const escapeBtn = byId("continueEscapeBtn");
    const playAgain = byId("playAgainBtn");
    if (escapeBtn) escapeBtn.classList.toggle("hidden", !result.escapeUnlocked);
    if (playAgain) playAgain.classList.toggle("hidden", result.escapeUnlocked);

    closePauseModal(true);
    setActiveEnd();
    toast(result.title);
    applyScriptMode(scriptMode);
}

function buildStatBars() {
    return [
        ["Snaga", gameState.snaga || 0],
        ["Mudrost", gameState.mudrost || 0],
        ["Vera", gameState.vera || 0],
        ["Ugled", gameState.ugled || 0],
        ["Zlato", gameState.zlato || 0],
        ["Stabilnost", gameState.stabilnost || 0]
    ].map(([label, value]) => {
        const width = Math.max(0, Math.min(100, value * 10));
        return `
            <div class="pv-end-stat pv-stat-bar">
                <span>${label}</span>
                <strong>${value}</strong>
                <div class="pv-bar-track"><i style="width:${width}%"></i></div>
            </div>
        `;
    }).join("");
}

getEndingResult = function(earlyLoss) {
    if (earlyLoss) {
        return {
            badge: "Poraz",
            title: "Vladavina je propala",
            text: "Kruna nije izdr\u017Eala teret odluka. Stabilnost ili poverenje naroda pali su prenisko.",
            summary: "Poku\u0161aj ponovo i odr\u017Ei ravnote\u017Eu izme\u0111u snage, mudrosti, vere, ugleda i riznice.",
            escapeUnlocked: false
        };
    }

    const grandWin = gameState.stabilnost >= 6 && gameState.ugled >= 9 && gameState.vera >= 8 && gameState.mudrost >= 8 && gameState.zlato >= 3;
    const win = isMainVictory();

    if (grandWin) {
        return {
            badge: "Pobeda",
            title: "Ostala si upam\u0107ena kao veliki vladar i ktitor",
            text: "Tvoja vladavina spojila je duhovnost, mudrost, ugled i dr\u017Eavni\u010Dku snagu.",
            summary: "Otklju\u010Dan je zavr\u0161ni izazov: manastirski escape room.",
            escapeUnlocked: true
        };
    }

    if (win && gameState.vera >= gameState.snaga && gameState.vera >= gameState.mudrost) {
        return {
            badge: "Pobeda",
            title: "Postala si duhovni vo\u0111a",
            text: "Sa\u010Duvala si dr\u017Eavu i ostavila zadu\u017Ebinu po kojoj se pamti tvoja kruna.",
            summary: "Otklju\u010Dan je zavr\u0161ni izazov: manastirski escape room.",
            escapeUnlocked: true
        };
    }

    if (win && gameState.mudrost >= gameState.snaga) {
        return {
            badge: "Pobeda",
            title: "Postala si mudri vladar",
            text: "Dr\u017Eavu si vodila promi\u0161ljeno, stabilno i sa merom.",
            summary: "Otklju\u010Dan je zavr\u0161ni izazov: manastirski escape room.",
            escapeUnlocked: true
        };
    }

    if (win) {
        return {
            badge: "Pobeda",
            title: "Postala si sna\u017Ean vladar",
            text: "Dr\u017Eavu si odr\u017Eala sigurnom i dovoljno stabilnom za poslednji izazov.",
            summary: "Otklju\u010Dan je zavr\u0161ni izazov: manastirski escape room.",
            escapeUnlocked: true
        };
    }

    return {
        badge: "Nedovoljno nasle\u0111e",
        title: "Presto je opstao, ali zavr\u0161ni izazov nije otklju\u010Dan",
        text: "Igra je zavr\u0161ena, ali vrednosti nisu dovoljno uravnote\u017Eene za ulazak u tajnu odaju.",
        summary: "Za escape room odr\u017Ei stabilnost, ugled, veru i mudrost iznad kriti\u010Dnog nivoa.",
        escapeUnlocked: false
    };
}

function showEscapeUnlock() {
    setActiveScreen("unlockScreen");
    applyScriptMode(scriptMode);
}

function startEscapeRoom() {
    escapeIndex = 0;
    escapeMistakes = 0;
    escapeSecondsLeft = 300;
    setActiveScreen("escapeScreen");
    renderEscapeRoom();
    startEscapeTimer();
    applyScriptMode(scriptMode);
}

function renderEscapeRoom() {
    const room = escapeRooms[escapeIndex];
    if (!room) {
        finishEscapeRoom(true);
        return;
    }

    byId("escapeRoomLabel").innerText = `Soba ${escapeIndex + 1} od ${escapeRooms.length}`;
    byId("escapeTitle").innerText = room.title;
    byId("escapeType").innerText = room.type;
    byId("escapeClueText").innerText = room.clue;
    byId("escapeQuestion").innerText = room.question;
    byId("escapeFeedback").innerText = "";
    const input = byId("escapeAnswer");
    if (input) {
        input.value = "";
        setTimeout(() => input.focus(), 80);
    }
}

function normalizeAnswer(value) {
    return String(value || "")
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/\s+/g, " ")
        .trim();
}

function submitEscapeAnswer() {
    const room = escapeRooms[escapeIndex];
    const answer = normalizeAnswer(byId("escapeAnswer")?.value);
    const ok = room.answers.map(normalizeAnswer).includes(answer);
    const feedback = byId("escapeFeedback");

    if (!answer) {
        if (feedback) feedback.innerText = "Upi\u0161i odgovor da bi nastavila.";
        return;
    }

    if (!ok) {
        escapeMistakes += 1;
        if (feedback) feedback.innerText = "Nije ta\u010Dno. Pogledaj trag i poku\u0161aj ponovo.";
        toast("Neta\u010Dno", "minus");
        return;
    }

    toast("Ta\u010Dno", "plus");
    escapeIndex += 1;
    if (escapeIndex >= escapeRooms.length) {
        finishEscapeRoom(true);
    } else {
        renderEscapeRoom();
    }
}

function showEscapeHint() {
    const room = escapeRooms[escapeIndex];
    const feedback = byId("escapeFeedback");
    if (feedback && room) feedback.innerText = room.hint;
}

function startEscapeTimer() {
    stopEscapeTimer();
    renderEscapeTimer();
    escapeTimerInterval = setInterval(() => {
        escapeSecondsLeft -= 1;
        renderEscapeTimer();
        if (escapeSecondsLeft <= 0) finishEscapeRoom(false);
    }, 1000);
}

function stopEscapeTimer() {
    if (escapeTimerInterval) clearInterval(escapeTimerInterval);
    escapeTimerInterval = null;
}

function renderEscapeTimer() {
    const min = Math.floor(Math.max(0, escapeSecondsLeft) / 60);
    const sec = Math.max(0, escapeSecondsLeft) % 60;
    const el = byId("escapeTimer");
    if (el) el.innerText = `${String(min).padStart(2, "0")}:${String(sec).padStart(2, "0")}`;
}

function finishEscapeRoom(success) {
    stopEscapeTimer();
    setActiveScreen("finalScreen");

    byId("finalBadge").innerText = success ? "Kona\u010Dna pobeda" : "Zavr\u0161ni izazov nije uspeo";
    byId("finalTitle").innerText = success ? "\u010Cestitamo!" : "Odaja se zatvorila";
    byId("finalText").innerText = success
        ? "Zavr\u0161ila si Put vladara i re\u0161ila zavr\u0161ni izazov."
        : "Nisi uspela da re\u0161i\u0161 zagonetke na vreme.";
    byId("finalSummary").innerHTML = `
        <div class="pv-end-summary-box">
            <strong>${lastMainResult?.title || "Put vladara"}</strong>
            <span>${success ? "Tvoje nasle\u0111e je potvr\u0111eno u tajnoj odaji." : "Glavna vladavina ostaje zabele\u017Eena, ali zavr\u0161ni pe\u010Dat nije otvoren."}</span>
        </div>
    `;
    byId("finalStats").innerHTML = buildStatBars();
    applyScriptMode(scriptMode);
}

document.addEventListener("DOMContentLoaded", () => {
    byId("continueIntroBtn")?.addEventListener("click", startDecisionGame);
    byId("introBackBtn")?.addEventListener("click", backToMenu);
    byId("continueEscapeBtn")?.addEventListener("click", showEscapeUnlock);
    byId("enterEscapeBtn")?.addEventListener("click", startEscapeRoom);
    byId("unlockMenuBtn")?.addEventListener("click", backToMenu);
    byId("submitEscapeBtn")?.addEventListener("click", submitEscapeAnswer);
    byId("hintEscapeBtn")?.addEventListener("click", showEscapeHint);
    byId("escapeMenuBtn")?.addEventListener("click", backToMenu);
    byId("finalAgainBtn")?.addEventListener("click", startGame);
    byId("finalMenuBtn")?.addEventListener("click", backToMenu);
    byId("escapeAnswer")?.addEventListener("keydown", (event) => {
        if (event.key === "Enter") submitEscapeAnswer();
    });
});
/* =========================
   ESCAPE ROOM VISUAL REFERENCE PASS
   ========================= */

escapeRooms.splice(0, escapeRooms.length,
    {
        title: "Manastirska odaja",
        type: "Zagonetka 1",
        mode: "text",
        clue: "Na pergamentu pi\u0161e: velika zadu\u017Ebina po\u010Dinje imenom rodona\u010Delnika dinastije.",
        question: "Ko je ktitor manastira Studenica?",
        answers: ["stefan nemanja", "nemanja"],
        hint: "Tra\u017Ei se ime vladara iz dinastije Nemanji\u0107a."
    },
    {
        title: "Kov\u010Deg pe\u010Data",
        type: "Zagonetka 2",
        mode: "code",
        clue: "Pe\u010Dati vladavine pokazuju trag: mudrost, snaga, narod i duhovnost. Redosled brojeva sa starog svitka otvara kov\u010Deg.",
        question: "Unesi \u010Detvorocifreni kod zavr\u0161nog izazova.",
        answers: ["3572"],
        hint: "Kod je prikazan kao niz na plo\u010Dicama ispod statova: 3 5 7 2."
    },
    {
        title: "Dvorana nasle\u0111a",
        type: "Finalni pe\u010Dat",
        mode: "text",
        clue: "Poslednji natpis ka\u017Ee: vlast bez naroda je prazna, vlast bez vere je kratka, vlast bez znanja je slepa.",
        question: "Koja vrednost najbolje opisuje vladara koji meri pre nego \u0161to odlu\u010Di?",
        answers: ["mudrost", "mudri vladar"],
        hint: "To je jedan od glavnih parametara igre."
    }
);

function escapeStatRows() {
    const rows = [
        ["Mudrost", gameState.mudrost || 0, "\u269C"],
        ["Snaga", gameState.snaga || 0, "\u2694"],
        ["Narod", gameState.ugled || 0, "\u2665"],
        ["Duhovnost", gameState.vera || 0, "\u271D"]
    ];

    return rows.map(([label, value, icon]) => {
        const width = Math.max(8, Math.min(100, value * 10));
        return `
            <div class="pv-escape-stat-row">
                <span>${icon} ${label}</span>
                <div class="pv-escape-stat-track"><i style="width:${width}%"></i></div>
                <strong>${value}</strong>
            </div>
        `;
    }).join("");
}

function escapeCodeSlots(code = "3572") {
    return `<div class="pv-code-slots">${code.split("").map(number => `<span>${number}</span>`).join("")}</div>`;
}

renderEscapeRoom = function() {
    const room = escapeRooms[escapeIndex];
    if (!room) {
        finishEscapeRoom(true);
        return;
    }

    byId("escapeRoomLabel").innerText = `Soba ${escapeIndex + 1} od ${escapeRooms.length}`;
    byId("escapeTitle").innerText = room.title;
    byId("escapeType").innerText = room.type;
    byId("escapeQuestion").innerText = room.question;
    byId("escapeFeedback").innerText = "";

    const clue = byId("escapeClueText");
    if (clue) {
        clue.innerHTML = `
            <span>${room.clue}</span>
            ${room.mode === "code" ? `<div class="pv-escape-stat-board">${escapeStatRows()}</div>${escapeCodeSlots("3572")}` : ""}
        `;
    }

    const input = byId("escapeAnswer");
    if (input) {
        input.value = "";
        input.maxLength = room.mode === "code" ? 4 : 42;
        input.inputMode = room.mode === "code" ? "numeric" : "text";
        input.placeholder = room.mode === "code" ? "Unesi kod" : "Unesi odgovor";
        input.classList.toggle("is-code", room.mode === "code");
        setTimeout(() => input.focus(), 80);
    }

    applyScriptMode(scriptMode);
};

submitEscapeAnswer = function() {
    const room = escapeRooms[escapeIndex];
    const answer = normalizeAnswer(byId("escapeAnswer")?.value);
    const ok = room.answers.map(normalizeAnswer).includes(answer);
    const feedback = byId("escapeFeedback");

    if (!answer) {
        if (feedback) feedback.innerText = "Upi\u0161i odgovor da bi nastavila.";
        return;
    }

    if (!ok) {
        escapeMistakes += 1;
        if (feedback) feedback.innerText = room.mode === "code"
            ? "Kod nije ta\u010Dan. Pogledaj plo\u010Dice i statove na svitku."
            : "Nije ta\u010Dno. Pogledaj trag i poku\u0161aj ponovo.";
        toast("Neta\u010Dno", "minus");
        return;
    }

    toast("Ta\u010Dno", "plus");
    escapeIndex += 1;
    if (escapeIndex >= escapeRooms.length) {
        finishEscapeRoom(true);
    } else {
        byId("escapeFeedback").innerText = "Pe\u010Dat je otvoren. Nastavi...";
        setTimeout(renderEscapeRoom, 520);
    }
};

finishEscapeRoom = function(success) {
    stopEscapeTimer();
    setActiveScreen("finalScreen");

    byId("finalBadge").innerText = success ? "Sjajno!" : "Zavr\u0161ni izazov nije uspeo";
    byId("finalTitle").innerText = success ? "Re\u0161ila si sve zagonetke" : "Odaja se zatvorila";
    byId("finalText").innerText = success
        ? "Zavr\u0161ila si Put vladara i zavr\u0161ni izazov."
        : "Nisi uspela da re\u0161i\u0161 zagonetke na vreme.";
    byId("finalSummary").innerHTML = `
        <div class="pv-end-summary-box pv-final-parchment">
            <strong>${lastMainResult?.title || "Put vladara"}</strong>
            <span>${success ? "Tvoje nasle\u0111e je potvr\u0111eno, a tajna odaja je otvorila poslednji pe\u010Dat." : "Glavna vladavina ostaje zabele\u017Eena, ali zavr\u0161ni pe\u010Dat nije otvoren."}</span>
        </div>
    `;
    byId("finalStats").innerHTML = buildStatBars();
    applyScriptMode(scriptMode);
};
/* =========================
   NO FLICKER / NO HOVER PREVIEW
   ========================= */

showEffectsPreview = function() {
    const box = byId("effectsPreview");
    if (!box) return;
    box.innerHTML = "";
    box.classList.add("hidden");
};

clearEffectsPreview = function() {
    const box = byId("effectsPreview");
    if (!box) return;
    box.innerHTML = "";
    box.classList.add("hidden");
};
/* =========================
   HINT SYSTEM + CLEAN GAMEPLAY
   ========================= */

let hintCredits = 3;
let pendingHintChoice = null;
let pendingHintQuestion = null;

const historyHintQuestions = [
    {
        question: "Ko je podigao manastir Studenicu?",
        answers: ["stefan nemanja", "nemanja"],
        note: "Studenica je jedna od najzna\u010Dajnijih zadu\u017Ebina Stefana Nemanje."
    },
    {
        question: "Kako se zvala srpska srednjovekovna vladarska porodica Stefana Nemanje?",
        answers: ["nemanjici", "nemanji\u0107i", "nemanjic"],
        note: "Dinastija Nemanji\u0107a obele\u017Eila je veliki deo srpskog srednjeg veka."
    },
    {
        question: "Koji srpski car je poznat po Zakoniku?",
        answers: ["dusan", "du\u0161an", "car dusan", "car du\u0161an", "stefan dusan", "stefan du\u0161an"],
        note: "Du\u0161anov zakonik je jedan od najva\u017Enijih pravnih spomenika srednjovekovne Srbije."
    },
    {
        question: "Koji manastir je zadu\u017Ebina kralja Milutina na Kosovu?",
        answers: ["gracanica", "gra\u010Danica"],
        note: "Gra\u010Danica je zadu\u017Ebina kralja Milutina."
    }
];

function ensureHintUI() {
    if (byId("hintButton")) return;

    const button = document.createElement("button");
    button.type = "button";
    button.id = "hintButton";
    button.className = "pv-hint-button";
    button.setAttribute("aria-label", "Savet");
    button.title = "Savet";
    button.innerHTML = `<span>?</span><strong id="hintCount">3</strong>`;
    document.body.appendChild(button);

    const modal = document.createElement("section");
    modal.id = "hintModal";
    modal.className = "pv-modal pv-hint-modal hidden";
    modal.setAttribute("aria-hidden", "true");
    modal.innerHTML = `
        <div class="pv-modal-card pv-hint-card">
            <div class="pv-modal-badge">Savet vladarskog ve\u0107a</div>
            <h3 class="pv-modal-title" id="hintTitle">Pomo\u0107</h3>
            <p class="pv-modal-text" id="hintText"></p>
            <div class="pv-hint-quiz hidden" id="hintQuizBox">
                <label for="hintQuizAnswer" id="hintQuizQuestion"></label>
                <input type="text" id="hintQuizAnswer" class="pv-riddle-input" autocomplete="off" placeholder="Unesi odgovor">
                <div class="pv-escape-feedback" id="hintQuizFeedback"></div>
            </div>
            <div class="pv-end-actions pv-hint-actions">
                <button type="button" class="pv-btn pv-btn-primary" id="applyHintBtn">Primeni savet</button>
                <button type="button" class="pv-btn pv-btn-secondary hidden" id="answerHintQuizBtn">Potvrdi odgovor</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="closeHintBtn">Zatvori</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    button.addEventListener("click", openHintModal);
    byId("closeHintBtn")?.addEventListener("click", closeHintModal);
    byId("applyHintBtn")?.addEventListener("click", applyHintChoice);
    byId("answerHintQuizBtn")?.addEventListener("click", answerHintQuiz);
    byId("hintQuizAnswer")?.addEventListener("keydown", event => {
        if (event.key === "Enter") answerHintQuiz();
    });
    modal.addEventListener("click", event => {
        if (event.target === modal) closeHintModal();
    });

    updateHintButton();
}

function updateHintButton() {
    const count = byId("hintCount");
    if (count) count.innerText = String(Math.max(0, hintCredits));

    const button = byId("hintButton");
    if (!button) return;
    button.classList.toggle("is-empty", hintCredits <= 0);
    button.classList.toggle("hidden", !isGameScreenActive());
}

function scoreChoiceForHint(choice) {
    const effects = choice.effects || {};
    const weights = {
        stabilnost: 3,
        ugled: 2.4,
        mudrost: 2.1,
        vera: 1.9,
        snaga: 1.4,
        zlato: 1.1
    };

    let score = 0;
    Object.entries(effects).forEach(([stat, value]) => {
        const current = gameState[stat] ?? 0;
        const dangerBonus = current <= 3 && value > 0 ? 2.2 : 1;
        const penalty = value < 0 && current <= 3 ? 2.5 : 1;
        score += value * (weights[stat] || 1) * dangerBonus * penalty;
    });

    if (choice.requirements && !meetsChoiceRequirements(choice).allowed) score -= 100;
    return score;
}

function getBestHintChoice() {
    const scene = currentScene || scenes[currentTurn - 1];
    const choices = (scene?.choices || []).filter(choice => meetsChoiceRequirements(choice).allowed);
    if (!choices.length) return null;
    return choices.slice().sort((a, b) => scoreChoiceForHint(b) - scoreChoiceForHint(a))[0];
}

function openHintModal() {
    if (!isGameScreenActive()) return;
    ensureHintUI();

    pendingHintChoice = getBestHintChoice();
    const modal = byId("hintModal");
    const quizBox = byId("hintQuizBox");
    const applyBtn = byId("applyHintBtn");
    const answerBtn = byId("answerHintQuizBtn");

    if (!pendingHintChoice) {
        byId("hintTitle").innerText = "Nema dostupnog saveta";
        byId("hintText").innerText = "Trenutno nema dozvoljene opcije za automatski savet.";
        quizBox?.classList.add("hidden");
        answerBtn?.classList.add("hidden");
        applyBtn?.classList.add("hidden");
    } else if (hintCredits > 0) {
        byId("hintTitle").innerText = `Besplatan savet (${hintCredits} preostalo)`;
        byId("hintText").innerText = `Najmudriji potez sada je: "${pendingHintChoice.text}". ${pendingHintChoice.detail || "Ovaj izbor najbolje \u010Duva ravnote\u017Eu vladavine."}`;
        quizBox?.classList.add("hidden");
        answerBtn?.classList.add("hidden");
        applyBtn?.classList.remove("hidden");
    } else {
        pendingHintQuestion = historyHintQuestions[Math.floor(Math.random() * historyHintQuestions.length)];
        byId("hintTitle").innerText = "Osvoji dodatni savet";
        byId("hintText").innerText = "Besplatni saveti su potro\u0161eni. Odgovori ta\u010Dno na istorijsko pitanje da dobije\u0161 jo\u0161 jedan savet.";
        byId("hintQuizQuestion").innerText = pendingHintQuestion.question;
        byId("hintQuizAnswer").value = "";
        byId("hintQuizFeedback").innerText = "";
        quizBox?.classList.remove("hidden");
        answerBtn?.classList.remove("hidden");
        applyBtn?.classList.add("hidden");
        setTimeout(() => byId("hintQuizAnswer")?.focus(), 80);
    }

    modal?.classList.remove("hidden");
    modal?.classList.add("active");
    modal?.setAttribute("aria-hidden", "false");
    applyScriptMode(scriptMode);
}

function closeHintModal() {
    const modal = byId("hintModal");
    modal?.classList.remove("active");
    modal?.classList.add("hidden");
    modal?.setAttribute("aria-hidden", "true");
}

function applyHintChoice() {
    if (!pendingHintChoice) return;
    if (hintCredits > 0) hintCredits -= 1;
    updateHintButton();
    closeHintModal();
    applyChoice(pendingHintChoice);
}

function answerHintQuiz() {
    const answer = normalizeAnswer(byId("hintQuizAnswer")?.value);
    const ok = pendingHintQuestion?.answers.map(normalizeAnswer).includes(answer);
    const feedback = byId("hintQuizFeedback");
    if (!answer) {
        if (feedback) feedback.innerText = "Upi\u0161i odgovor.";
        return;
    }

    if (!ok) {
        if (feedback) feedback.innerText = "Nije ta\u010Dno. Poku\u0161aj kasnije sa drugim pitanjem.";
        toast("Odgovor nije ta\u010Dan", "minus");
        return;
    }

    hintCredits = 1;
    updateHintButton();
    if (feedback) feedback.innerText = `${pendingHintQuestion.note} Dobili ste jedan novi savet.`;
    byId("answerHintQuizBtn")?.classList.add("hidden");
    byId("applyHintBtn")?.classList.remove("hidden");
    byId("hintTitle").innerText = "Savet je osvojÐµÐ½";
    byId("hintText").innerText = `Najmudriji potez sada je: "${pendingHintChoice.text}". ${pendingHintChoice.detail || "Ovaj izbor najbolje \u010Duva ravnote\u017Eu vladavine."}`;
}

const originalHintSetActiveScreen = setActiveScreen;
setActiveScreen = function(screenId) {
    originalHintSetActiveScreen(screenId);
    ensureHintUI();
    updateHintButton();
};

document.addEventListener("DOMContentLoaded", () => {
    ensureHintUI();
});
/* =========================
   STAT INFO POPUPS + TURN FIX
   ========================= */

const statInfo = {
    snaga: {
        title: "Snaga",
        text: "Pokazuje vojnu mo\u0107, odlu\u010Dnost i sposobnost da odbrani\u0161 granice. Koristi se za ratne i krizne odluke. Ako je niska, dr\u017Eava deluje slabo."
    },
    mudrost: {
        title: "Mudrost",
        text: "Pokazuje promi\u0161ljenost, diplomatiju i dugoro\u010Dno planiranje. Otklju\u010Dava pametnije izbore i bolje zavr\u0161etke."
    },
    vera: {
        title: "Vera",
        text: "Pokazuje duhovni autoritet vladara i odnos sa Crkvom. Va\u017Ena je za manastire, zadu\u017Ebine i moral naroda."
    },
    ugled: {
        title: "Ugled",
        text: "Pokazuje koliko ti veruju narod, dvor i vlastela. Ako padne prenisko, vladavina mo\u017Ee da propadne."
    },
    zlato: {
        title: "Zlato",
        text: "Predstavlja riznicu. Tro\u0161i se na pomo\u0107 narodu, vojsku, gradnju i re\u0161avanje kriza. Bez zlata su neke odluke zaklju\u010Dane."
    },
    stabilnost: {
        title: "Stabilnost",
        text: "Pokazuje mir i poredak u dr\u017Eavi. Ako padne na nulu, nastaje haos i igra se zavr\u0161ava porazom."
    },
    turn: {
        title: "Potez",
        text: "Pokazuje gde se nalazi\u0161 u toku igre. Svaka odluka pomera igru za jedan korak, ukupno ima 12 poteza."
    }
};

function ensureStatInfoUI() {
    if (byId("statInfoModal")) return;
    const modal = document.createElement("section");
    modal.id = "statInfoModal";
    modal.className = "pv-modal pv-stat-info-modal hidden";
    modal.setAttribute("aria-hidden", "true");
    modal.innerHTML = `
        <div class="pv-modal-card pv-stat-info-card">
            <div class="pv-modal-badge">Obja\u0161njenje</div>
            <h3 class="pv-modal-title" id="statInfoTitle"></h3>
            <p class="pv-modal-text" id="statInfoText"></p>
            <div class="pv-end-actions pv-modal-actions">
                <button type="button" class="pv-btn pv-btn-primary" id="closeStatInfoBtn">Razumem</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    byId("closeStatInfoBtn")?.addEventListener("click", closeStatInfo);
    modal.addEventListener("click", event => {
        if (event.target === modal) closeStatInfo();
    });
}

function bindStatInfoCards() {
    ensureStatInfoUI();
    document.querySelectorAll(".pv-stat[data-stat]").forEach(card => {
        if (card.dataset.infoBound === "1") return;
        card.dataset.infoBound = "1";
        card.setAttribute("role", "button");
        card.setAttribute("tabindex", "0");
        const open = () => openStatInfo(card.dataset.stat);
        card.addEventListener("click", open);
        card.addEventListener("keydown", event => {
            if (event.key === "Enter" || event.key === " ") {
                event.preventDefault();
                open();
            }
        });
    });
}

function openStatInfo(stat) {
    const info = statInfo[stat];
    if (!info) return;
    ensureStatInfoUI();
    byId("statInfoTitle").innerText = info.title;
    byId("statInfoText").innerText = info.text;
    const modal = byId("statInfoModal");
    modal?.classList.remove("hidden");
    modal?.classList.add("active");
    modal?.setAttribute("aria-hidden", "false");
    applyScriptMode(scriptMode);
}

function closeStatInfo() {
    const modal = byId("statInfoModal");
    modal?.classList.remove("active");
    modal?.classList.add("hidden");
    modal?.setAttribute("aria-hidden", "true");
}

const originalStatUpdateStatsUI = updateStatsUI;
updateStatsUI = function() {
    originalStatUpdateStatsUI();
    bindStatInfoCards();
};

const originalStatApplyChoice = applyChoice;
applyChoice = async function(choice) {
    const oldTurn = currentTurn;
    await originalStatApplyChoice(choice);
    if (currentTurn === oldTurn && lastEnding?.currentTurn) {
        currentTurn = lastEnding.currentTurn;
        updateStatsUI();
    }
};

document.addEventListener("DOMContentLoaded", bindStatInfoCards);
/* =========================
   LEVELS + MAIN TIMER PASS
   ========================= */

const PV_LEVEL_COUNTS = [3, 3, 4, 5, 6, 7, 8, 9, 10, 15];
const PV_TOTAL_MAIN_SECONDS = 35 * 60;
let mainSecondsLeft = PV_TOTAL_MAIN_SECONDS;
let mainTimerInterval = null;

function totalPlannedTurns() {
    return PV_LEVEL_COUNTS.reduce((sum, count) => sum + count, 0);
}

function getLevelProgress(turn = currentTurn) {
    const safeTurn = Math.max(1, Math.min(turn || 1, GAME_CONFIG.maxTurns || totalPlannedTurns()));
    let cursor = 0;
    for (let index = 0; index < PV_LEVEL_COUNTS.length; index += 1) {
        const count = PV_LEVEL_COUNTS[index];
        if (safeTurn <= cursor + count) {
            return {
                level: index + 1,
                question: safeTurn - cursor,
                levelTotal: count,
                global: safeTurn,
                total: GAME_CONFIG.maxTurns || totalPlannedTurns(),
                levelStart: cursor + 1,
                levelEnd: cursor + count
            };
        }
        cursor += count;
    }
    return {
        level: PV_LEVEL_COUNTS.length,
        question: PV_LEVEL_COUNTS[PV_LEVEL_COUNTS.length - 1],
        levelTotal: PV_LEVEL_COUNTS[PV_LEVEL_COUNTS.length - 1],
        global: safeTurn,
        total: GAME_CONFIG.maxTurns || totalPlannedTurns(),
        levelStart: cursor - PV_LEVEL_COUNTS[PV_LEVEL_COUNTS.length - 1] + 1,
        levelEnd: cursor
    };
}

function formatMainTime(seconds) {
    const safe = Math.max(0, seconds || 0);
    const minutes = Math.floor(safe / 60).toString().padStart(2, "0");
    const rest = Math.floor(safe % 60).toString().padStart(2, "0");
    return `${minutes}:${rest}`;
}

function ensureMainTimerUI() {
    if (byId("mainTimer")) return;
    const turnCard = document.querySelector('.pv-stat[data-stat="turn"]');
    if (!turnCard || !turnCard.parentElement) return;
    const card = document.createElement("div");
    card.className = "pv-stat pv-stat-timer";
    card.dataset.stat = "time";
    card.innerHTML = '<span>Vreme</span><strong id="mainTimer">35:00</strong>';
    turnCard.insertAdjacentElement("afterend", card);
}

function updateLevelUI() {
    ensureMainTimerUI();
    const progress = getLevelProgress();
    const turnEl = byId("stat-turn");
    if (turnEl) turnEl.innerText = `${progress.level}: ${progress.question}/${progress.levelTotal}`;

    const progressEl = byId("progressLabel");
    if (progressEl) {
        progressEl.innerText = `Nivo ${progress.level} - pitanje ${progress.question} od ${progress.levelTotal} | ukupno ${progress.global}/${progress.total}`;
    }

    const chapter = byId("chapterLabel");
    if (chapter && (!currentScene?.chapter || !currentScene.chapter.includes("Nivo"))) {
        chapter.innerText = `Nivo ${progress.level} - pitanje ${progress.question}/${progress.levelTotal}`;
    }

    const timer = byId("mainTimer");
    if (timer) {
        timer.innerText = formatMainTime(mainSecondsLeft);
        const timerCard = timer.closest(".pv-stat");
        timerCard?.classList.toggle("is-critical", mainSecondsLeft <= 120);
        timerCard?.classList.toggle("is-good", mainSecondsLeft > 900);
    }
}

function startMainTimer() {
    stopMainTimer();
    mainTimerInterval = setInterval(() => {
        if (!isGameScreenActive()) return;
        mainSecondsLeft -= 1;
        updateLevelUI();
        if (mainSecondsLeft <= 0) {
            stopMainTimer();
            finishGame(true);
            toast("Vreme je isteklo", "minus");
        }
    }, 1000);
}

function stopMainTimer() {
    if (mainTimerInterval) clearInterval(mainTimerInterval);
    mainTimerInterval = null;
}

const originalLevelUpdateStatsUI = updateStatsUI;
updateStatsUI = function() {
    originalLevelUpdateStatsUI();
    updateLevelUI();
};

const originalLevelRenderScene = renderScene;
renderScene = function() {
    originalLevelRenderScene();
    updateLevelUI();
};

const originalLevelStartDecisionGame = startDecisionGame;
startDecisionGame = async function() {
    stopMainTimer();
    mainSecondsLeft = PV_TOTAL_MAIN_SECONDS;
    GAME_CONFIG.maxTurns = totalPlannedTurns();
    await originalLevelStartDecisionGame();
    if (isGameScreenActive()) {
        GAME_CONFIG.maxTurns = Math.max(GAME_CONFIG.maxTurns || 0, totalPlannedTurns());
        updateLevelUI();
        startMainTimer();
    }
};

const originalLevelFinishGame = finishGame;
finishGame = function(earlyLoss = false, serverEnding = null) {
    stopMainTimer();
    originalLevelFinishGame(earlyLoss, serverEnding);
};

const originalLevelBackToMenu = backToMenu;
backToMenu = function() {
    stopMainTimer();
    originalLevelBackToMenu();
};

statInfo.time = {
    title: "Vreme",
    text: "Ima\u0161 35 minuta da zavr\u0161i\u0161 svih 10 nivoa. Kada vreme istekne, vladavina se zavr\u0161ava porazom. Timer se zaustavlja kada do\u0111e\u0161 do rezultata ili escape room-a."
};

statInfo.turn = {
    title: "Nivo i pitanje",
    text: "Igra ima 10 nivoa. Prva dva nivoa imaju po 3 pitanja, zatim nivoi postaju du\u017Ei i te\u017Ei, a nivo 10 ima 15 pitanja. Escape room se otklju\u010Dava tek posle svih 70 odluka."
};

document.addEventListener("DOMContentLoaded", updateLevelUI);

/* =========================
   INTERACTIVE ESCAPE ROOM
   ========================= */

let escapeInventory = [];
let escapeFlags = {};
let escapeMessage = "Klikni predmete u sobi i prona\u0111i tragove.";

const escapeRoomFlow = [
    {
        key: "library",
        label: "Soba 1 od 3",
        title: "Manastirska biblioteka",
        type: "Istra\u017Eivanje",
        objective: "Prona\u0111i stari klju\u010D i polovinu pe\u010Data za vrata riznice.",
        hint: "Po\u010Dni od stola. Knjiga ti govori za\u0161to je klju\u010D va\u017Ean, a kov\u010Deg se ne otvara bez njega.",
        hotspots: [
            { id: "desk", label: "Sto", x: 23, y: 62, action: "searchDesk" },
            { id: "book", label: "Knjiga", x: 47, y: 47, action: "readBook" },
            { id: "chest", label: "Kov\u010Deg", x: 77, y: 68, action: "openFirstChest" },
            { id: "door", label: "Vrata", x: 91, y: 38, action: "leaveLibrary" }
        ]
    },
    {
        key: "treasury",
        label: "Soba 2 od 3",
        title: "Riznica pe\u010Data",
        type: "Kod i inventar",
        objective: "Prona\u0111i redosled brojeva, otvori sef i uzmi zlatni pe\u010Dat.",
        hint: "Svitak i ikone kriju redosled. Kod je 3572, ali prvo klikni svitak da lik dobije trag.",
        hotspots: [
            { id: "scroll", label: "Svitak", x: 30, y: 42, action: "readScroll" },
            { id: "icons", label: "Ikone", x: 62, y: 31, action: "inspectIcons" },
            { id: "safe", label: "Sef", x: 74, y: 67, action: "focusCode" },
            { id: "gate", label: "Prolaz", x: 91, y: 43, action: "leaveTreasury" }
        ],
        puzzle: "code"
    },
    {
        key: "sanctum",
        label: "Soba 3 od 3",
        title: "Dvorana nasle\u0111a",
        type: "Finalni pe\u010Dat",
        objective: "Postavi tri simbola vladavine pravilnim redom i otvori zavr\u0161na vrata.",
        hint: "Redosled je ono \u0161to je odr\u017Ealo tvoju vladavinu: Mudrost, Vera, Narod.",
        hotspots: [
            { id: "altar", label: "Oltar", x: 50, y: 58, action: "inspectAltar" },
            { id: "symbols", label: "Simboli", x: 31, y: 34, action: "inspectSymbols" },
            { id: "finalDoor", label: "Izlaz", x: 85, y: 42, action: "finishSanctum" }
        ],
        puzzle: "symbols"
    }
];

function hasEscapeItem(item) {
    return escapeInventory.includes(item);
}

function addEscapeItem(item, label) {
    if (hasEscapeItem(item)) {
        escapeMessage = `${label} je ve\u0107 u inventaru.`;
        return;
    }
    escapeInventory.push(item);
    escapeMessage = `U inventar je dodato: ${label}.`;
    toast(label, "plus");
}

function setEscapeMessage(message, tone = "") {
    escapeMessage = message;
    const feedback = byId("escapeFeedback");
    if (feedback) {
        feedback.innerText = message;
        feedback.dataset.tone = tone;
    }
}

function escapeItemLabel(item) {
    return {
        oldKey: "Stari klju\u010D",
        sealHalf: "Polovina pe\u010Data",
        wax: "Vosak",
        goldSeal: "Zlatni pe\u010Dat",
        finalSeal: "Pe\u010Dat nasle\u0111a"
    }[item] || item;
}

function renderEscapeInventory() {
    if (!escapeInventory.length) {
        return '<div class="pv-escape-empty">Inventar je prazan</div>';
    }
    return escapeInventory.map(item => `<span class="pv-inventory-chip">${escapeItemLabel(item)}</span>`).join("");
}

function renderEscapePuzzle(room) {
    if (room.puzzle === "code") {
        const unlocked = !!escapeFlags.safeOpen;
        return `
            <div class="pv-escape-puzzle ${unlocked ? "is-solved" : ""}">
                <div class="pv-puzzle-title">Sef riznice</div>
                <div class="pv-code-slots"><span>3</span><span>5</span><span>7</span><span>2</span></div>
                <div class="pv-code-entry">
                    <input type="text" id="escapeCodeInput" maxlength="4" inputmode="numeric" autocomplete="off" placeholder="Unesi kod">
                    <button type="button" class="pv-mini-action" id="escapeCodeBtn">Otvori</button>
                </div>
            </div>
        `;
    }

    if (room.puzzle === "symbols") {
        const solved = !!escapeFlags.symbolsSolved;
        return `
            <div class="pv-escape-puzzle ${solved ? "is-solved" : ""}">
                <div class="pv-puzzle-title">Redosled simbola</div>
                <div class="pv-symbol-order" id="symbolOrder">
                    ${["Mudrost", "Vera", "Narod"].map(value => `<button type="button" class="pv-symbol-token" data-symbol="${value}">${value}</button>`).join("")}
                </div>
                <button type="button" class="pv-mini-action" id="symbolConfirmBtn">Postavi pe\u010Date</button>
            </div>
        `;
    }

    return '<div class="pv-escape-puzzle"><div class="pv-puzzle-title">Tragovi</div><p>Predmeti u sobi reaguju na klik.</p></div>';
}

function renderInteractiveEscapeRoom() {
    const room = escapeRoomFlow[escapeIndex];
    if (!room) {
        finishEscapeRoom(true);
        return;
    }

    byId("escapeRoomLabel").innerText = room.label;
    byId("escapeTitle").innerText = room.title;
    byId("escapeType").innerText = room.type;

    const container = document.querySelector(".pv-escape-room");
    if (!container) return;

    container.innerHTML = `
        <div class="pv-escape-stage" data-room="${room.key}">
            <div class="pv-room-art pv-room-${room.key}" aria-label="${room.title}">
                <div class="pv-room-depth"></div>
                ${room.hotspots.map(hotspot => `
                    <button type="button" class="pv-hotspot ${escapeFlags[hotspot.id] ? "is-used" : ""}" style="left:${hotspot.x}%;top:${hotspot.y}%;" data-action="${hotspot.action}">
                        <span>${hotspot.label}</span>
                    </button>
                `).join("")}
            </div>
        </div>
        <aside class="pv-escape-side">
            <div class="pv-escape-clue">
                <div class="pv-objective-title">Cilj sobe</div>
                <p id="escapeClueText">${room.objective}</p>
            </div>
            <div class="pv-inventory-panel">
                <div class="pv-objective-title">Inventar</div>
                <div class="pv-inventory-list" id="escapeInventoryList">${renderEscapeInventory()}</div>
            </div>
            ${renderEscapePuzzle(room)}
            <div class="pv-escape-feedback" id="escapeFeedback" aria-live="polite">${escapeMessage}</div>
            <div class="pv-end-actions pv-escape-actions">
                <button type="button" class="pv-btn pv-btn-secondary" id="hintEscapeBtn">Trag</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="escapeMenuBtn">Meni</button>
            </div>
        </aside>
    `;

    container.querySelectorAll(".pv-hotspot").forEach(button => {
        button.addEventListener("click", () => handleEscapeAction(button.dataset.action));
    });
    byId("hintEscapeBtn")?.addEventListener("click", showEscapeHint);
    byId("escapeMenuBtn")?.addEventListener("click", backToMenu);
    byId("escapeCodeBtn")?.addEventListener("click", submitEscapeCode);
    byId("escapeCodeInput")?.addEventListener("keydown", event => {
        if (event.key === "Enter") submitEscapeCode();
    });
    byId("symbolConfirmBtn")?.addEventListener("click", submitSymbolPuzzle);
    byId("symbolOrder")?.addEventListener("click", event => {
        const token = event.target.closest(".pv-symbol-token");
        if (!token) return;
        token.classList.toggle("is-selected");
    });

    applyScriptMode(scriptMode);
}

function handleEscapeAction(action) {
    switch (action) {
        case "searchDesk":
            escapeFlags.desk = true;
            addEscapeItem("oldKey", "Stari klju\u010D");
            break;
        case "readBook":
            escapeFlags.book = true;
            setEscapeMessage("U knjizi pi\u0161e: klju\u010D otvara kov\u010Deg, ali pe\u010Dat otvara put dalje.", "plus");
            break;
        case "openFirstChest":
            if (!hasEscapeItem("oldKey")) {
                setEscapeMessage("Kov\u010Deg je zaklju\u010Dan. Prvo prona\u0111i klju\u010D.", "minus");
                break;
            }
            escapeFlags.chest = true;
            addEscapeItem("sealHalf", "Polovina pe\u010Data");
            break;
        case "leaveLibrary":
            if (!hasEscapeItem("oldKey") || !hasEscapeItem("sealHalf")) {
                setEscapeMessage("Vrata tra\u017Ee klju\u010D i polovinu pe\u010Data iz kov\u010Dega.", "minus");
                break;
            }
            nextEscapeRoom("Vrata biblioteke su se otvorila.");
            return;
        case "readScroll":
            escapeFlags.scroll = true;
            setEscapeMessage("Na svitku stoji niz: 3 - 5 - 7 - 2. Sef \u010Deka isti redosled.", "plus");
            break;
        case "inspectIcons":
            escapeFlags.icons = true;
            addEscapeItem("wax", "Vosak");
            break;
        case "focusCode":
            setEscapeMessage("Sef ima \u010Detiri mesta za broj. Potra\u017Ei niz na svitku.");
            byId("escapeCodeInput")?.focus();
            break;
        case "leaveTreasury":
            if (!hasEscapeItem("goldSeal")) {
                setEscapeMessage("Prolaz se ne otvara bez zlatnog pe\u010Data iz sefa.", "minus");
                break;
            }
            nextEscapeRoom("Riznica je ostala iza tebe. Finalna dvorana je blizu.");
            return;
        case "inspectAltar":
            escapeFlags.altar = true;
            setEscapeMessage("Na oltaru su tri udubljenja: Mudrost, Vera i Narod.", "plus");
            break;
        case "inspectSymbols":
            escapeFlags.symbols = true;
            setEscapeMessage("Simboli se moraju postaviti redom: prvo odluka, zatim duhovni temelj, pa narod.", "plus");
            break;
        case "finishSanctum":
            if (!escapeFlags.symbolsSolved || !hasEscapeItem("finalSeal")) {
                setEscapeMessage("Finalna vrata tra\u017Ee pravilno postavljene simbole i pe\u010Dat nasle\u0111a.", "minus");
                break;
            }
            finishEscapeRoom(true);
            return;
        default:
            setEscapeMessage("Ovde nema ni\u010Deg korisnog.");
    }
    renderInteractiveEscapeRoom();
}

function submitEscapeCode() {
    const value = normalizeAnswer(byId("escapeCodeInput")?.value);
    if (!escapeFlags.scroll) {
        setEscapeMessage("Mo\u017Ee\u0161 naga\u0111ati, ali prvo bi trebalo prona\u0107i svitak sa tragom.", "minus");
        return;
    }
    if (value !== "3572") {
        escapeMistakes += 1;
        setEscapeMessage("Kod nije ta\u010Dan. Pogledaj redosled na svitku.", "minus");
        toast("Neta\u010Dan kod", "minus");
        return;
    }
    escapeFlags.safeOpen = true;
    addEscapeItem("goldSeal", "Zlatni pe\u010Dat");
    renderInteractiveEscapeRoom();
}

function submitSymbolPuzzle() {
    const selected = Array.from(document.querySelectorAll(".pv-symbol-token.is-selected")).map(item => item.dataset.symbol);
    if (selected.join("|") !== "Mudrost|Vera|Narod") {
        escapeMistakes += 1;
        setEscapeMessage("Redosled nije dobar. Trag ka\u017Ee: odluka, duhovni temelj, narod.", "minus");
        toast("Pogre\u0161an redosled", "minus");
        return;
    }
    escapeFlags.symbolsSolved = true;
    addEscapeItem("finalSeal", "Pe\u010Dat nasle\u0111a");
    renderInteractiveEscapeRoom();
}

function nextEscapeRoom(message) {
    toast("Soba otvorena", "plus");
    escapeIndex += 1;
    escapeMessage = message;
    renderInteractiveEscapeRoom();
}

startEscapeRoom = function() {
    escapeIndex = 0;
    escapeMistakes = 0;
    escapeSecondsLeft = 420;
    escapeInventory = [];
    escapeFlags = {};
    escapeMessage = "Klikni predmete u sobi i prona\u0111i tragove.";
    setActiveScreen("escapeScreen");
    renderInteractiveEscapeRoom();
    startEscapeTimer();
    applyScriptMode(scriptMode);
};

renderEscapeRoom = renderInteractiveEscapeRoom;

showEscapeHint = function() {
    const room = escapeRoomFlow[escapeIndex];
    if (!room) return;
    setEscapeMessage(room.hint, "plus");
};

submitEscapeAnswer = function() {
    if (escapeIndex === 1) submitEscapeCode();
    if (escapeIndex === 2) submitSymbolPuzzle();
};

finishEscapeRoom = function(success) {
    stopEscapeTimer();
    setActiveScreen("finalScreen");

    byId("finalBadge").innerText = success ? "Kona\u010Dna pobeda" : "Zavr\u0161ni izazov nije uspeo";
    byId("finalTitle").innerText = success ? "Otvorila si tajnu odaju" : "Odaja se zatvorila";
    byId("finalText").innerText = success
        ? "Zavr\u0161ila si Put vladara, prona\u0161la pe\u010Dat nasle\u0111a i re\u0161ila escape room."
        : "Vreme je isteklo pre nego \u0161to su svi pe\u010Dati postavljeni.";
    byId("finalSummary").innerHTML = `
        <div class="pv-end-summary-box pv-final-parchment">
            <strong>${lastMainResult?.title || "Put vladara"}</strong>
            <span>${success ? "Tvoje nasle\u0111e je potvr\u0111eno: klju\u010D, pe\u010Dat i mudrost otvorili su poslednja vrata." : "Glavna vladavina ostaje zabele\u017Eena, ali zavr\u0161ni pe\u010Dat nije otvoren."}</span>
            <span>Gre\u0161ke u escape room-u: ${escapeMistakes}</span>
        </div>
    `;
    byId("finalStats").innerHTML = buildStatBars();
    applyScriptMode(scriptMode);
};

/* =========================
   REALISTIC ROOM + LEVEL TRANSITIONS
   ========================= */

let escapeActionLocked = false;
let pendingRoomMotion = "";
let lastRenderedLevel = 1;

function ensureTransitionOverlay() {
    if (byId("roomTransitionOverlay")) return;
    const overlay = document.createElement("div");
    overlay.id = "roomTransitionOverlay";
    overlay.className = "pv-room-transition hidden";
    overlay.innerHTML = `
        <div class="pv-transition-gate"></div>
        <div class="pv-transition-copy">
            <span id="roomTransitionBadge">Prolaz otvoren</span>
            <strong id="roomTransitionTitle">Ulazi\u0161 u slede\u0107u odaju...</strong>
        </div>
    `;
    document.body.appendChild(overlay);
}

function ensureLevelTransitionOverlay() {
    if (byId("levelTransitionOverlay")) return;
    const overlay = document.createElement("div");
    overlay.id = "levelTransitionOverlay";
    overlay.className = "pv-level-transition hidden";
    overlay.innerHTML = `
        <div class="pv-level-seal"></div>
        <div class="pv-transition-copy">
            <span id="levelTransitionBadge">Nivo zavr\u0161en</span>
            <strong id="levelTransitionTitle">Otvara se slede\u0107e poglavlje</strong>
        </div>
    `;
    document.body.appendChild(overlay);
}

function playRoomTransition(title, callback) {
    ensureTransitionOverlay();
    const overlay = byId("roomTransitionOverlay");
    byId("roomTransitionTitle").innerText = title || "Ulazi\u0161 u slede\u0107u odaju...";
    overlay?.classList.remove("hidden");
    overlay?.classList.add("active");
    setTimeout(() => {
        callback?.();
        setTimeout(() => {
            overlay?.classList.remove("active");
            overlay?.classList.add("hidden");
        }, 360);
    }, 520);
}

function playLevelTransition(level) {
    ensureLevelTransitionOverlay();
    const overlay = byId("levelTransitionOverlay");
    byId("levelTransitionTitle").innerText = `Nivo ${level} po\u010Dinje`;
    overlay?.classList.remove("hidden");
    overlay?.classList.add("active");
    setTimeout(() => {
        overlay?.classList.remove("active");
        overlay?.classList.add("hidden");
    }, 980);
}

function decorateEscapeRoomMotion() {
    const roomArt = document.querySelector(".pv-room-art");
    if (!roomArt) return;
    roomArt.classList.toggle("is-chest-open", !!escapeFlags.chest);
    roomArt.classList.toggle("is-safe-open", !!escapeFlags.safeOpen);
    roomArt.classList.toggle("is-final-open", !!escapeFlags.symbolsSolved);
    if (pendingRoomMotion) {
        roomArt.classList.add(pendingRoomMotion);
        const motion = pendingRoomMotion;
        pendingRoomMotion = "";
        setTimeout(() => roomArt.classList.remove(motion), 720);
    }
}

const originalMotionRenderInteractiveEscapeRoom = renderInteractiveEscapeRoom;
renderInteractiveEscapeRoom = function() {
    originalMotionRenderInteractiveEscapeRoom();
    decorateEscapeRoomMotion();
};
renderEscapeRoom = renderInteractiveEscapeRoom;

function animateEscapeAction(action, callback) {
    if (escapeActionLocked) return;
    escapeActionLocked = true;
    const button = document.querySelector(`.pv-hotspot[data-action="${action}"]`);
    const roomArt = document.querySelector(".pv-room-art");
    button?.classList.add("is-activating");
    roomArt?.classList.add("is-inspecting");
    setTimeout(() => {
        button?.classList.remove("is-activating");
        roomArt?.classList.remove("is-inspecting");
        callback?.();
        escapeActionLocked = false;
    }, 260);
}

handleEscapeAction = function(action) {
    animateEscapeAction(action, () => {
        switch (action) {
            case "searchDesk":
                escapeFlags.desk = true;
                pendingRoomMotion = "is-item-found";
                addEscapeItem("oldKey", "Stari klju\u010D");
                break;
            case "readBook":
                escapeFlags.book = true;
                pendingRoomMotion = "is-pages-open";
                setEscapeMessage("Knjiga se otvara. U njoj pi\u0161e: klju\u010D otvara kov\u010Deg, ali pe\u010Dat otvara put dalje.", "plus");
                break;
            case "openFirstChest":
                if (!hasEscapeItem("oldKey")) {
                    pendingRoomMotion = "is-locked-shake";
                    setEscapeMessage("Kov\u010Deg se ne pomera. Prvo prona\u0111i klju\u010D.", "minus");
                    break;
                }
                escapeFlags.chest = true;
                pendingRoomMotion = "is-chest-opening";
                addEscapeItem("sealHalf", "Polovina pe\u010Data");
                break;
            case "leaveLibrary":
                if (!hasEscapeItem("oldKey") || !hasEscapeItem("sealHalf")) {
                    pendingRoomMotion = "is-locked-shake";
                    setEscapeMessage("Vrata tra\u017Ee klju\u010D i polovinu pe\u010Data iz kov\u010Dega.", "minus");
                    break;
                }
                nextEscapeRoom("Vrata biblioteke su se otvorila.");
                return;
            case "readScroll":
                escapeFlags.scroll = true;
                pendingRoomMotion = "is-scroll-open";
                setEscapeMessage("Svitak se odmotava. Na njemu stoji niz: 3 - 5 - 7 - 2.", "plus");
                break;
            case "inspectIcons":
                escapeFlags.icons = true;
                pendingRoomMotion = "is-item-found";
                addEscapeItem("wax", "Vosak");
                break;
            case "focusCode":
                pendingRoomMotion = escapeFlags.safeOpen ? "" : "is-safe-focus";
                setEscapeMessage("Sef ima \u010Detiri mesta za broj. Potra\u017Ei niz na svitku.");
                byId("escapeCodeInput")?.focus();
                break;
            case "leaveTreasury":
                if (!hasEscapeItem("goldSeal")) {
                    pendingRoomMotion = "is-locked-shake";
                    setEscapeMessage("Prolaz se ne otvara bez zlatnog pe\u010Data iz sefa.", "minus");
                    break;
                }
                nextEscapeRoom("Riznica je ostala iza tebe. Finalna dvorana je blizu.");
                return;
            case "inspectAltar":
                escapeFlags.altar = true;
                pendingRoomMotion = "is-altar-lit";
                setEscapeMessage("Sve\u0107e na oltaru se pale. Tri udubljenja \u010Dekaju: Mudrost, Vera i Narod.", "plus");
                break;
            case "inspectSymbols":
                escapeFlags.symbols = true;
                pendingRoomMotion = "is-symbols-glow";
                setEscapeMessage("Simboli zasvetle. Redosled je: odluka, duhovni temelj, narod.", "plus");
                break;
            case "finishSanctum":
                if (!escapeFlags.symbolsSolved || !hasEscapeItem("finalSeal")) {
                    pendingRoomMotion = "is-locked-shake";
                    setEscapeMessage("Finalna vrata tra\u017Ee pravilno postavljene simbole i pe\u010Dat nasle\u0111a.", "minus");
                    break;
                }
                playRoomTransition("Finalna vrata se otvaraju", () => finishEscapeRoom(true));
                return;
            default:
                setEscapeMessage("Ovde nema ni\u010Deg korisnog.");
        }
        renderInteractiveEscapeRoom();
    });
};

submitEscapeCode = function() {
    const value = normalizeAnswer(byId("escapeCodeInput")?.value);
    if (!escapeFlags.scroll) {
        pendingRoomMotion = "is-locked-shake";
        setEscapeMessage("Mo\u017Ee\u0161 naga\u0111ati, ali prvo prona\u0111i svitak sa tragom.", "minus");
        renderInteractiveEscapeRoom();
        return;
    }
    if (value !== "3572") {
        escapeMistakes += 1;
        pendingRoomMotion = "is-locked-shake";
        setEscapeMessage("Kod nije ta\u010Dan. Sef ostaje zatvoren.", "minus");
        toast("Neta\u010Dan kod", "minus");
        renderInteractiveEscapeRoom();
        return;
    }
    escapeFlags.safeOpen = true;
    pendingRoomMotion = "is-safe-opening";
    addEscapeItem("goldSeal", "Zlatni pe\u010Dat");
    renderInteractiveEscapeRoom();
};

submitSymbolPuzzle = function() {
    const selected = Array.from(document.querySelectorAll(".pv-symbol-token.is-selected")).map(item => item.dataset.symbol);
    if (selected.join("|") !== "Mudrost|Vera|Narod") {
        escapeMistakes += 1;
        pendingRoomMotion = "is-locked-shake";
        setEscapeMessage("Redosled nije dobar. Pe\u010Dati se vra\u0107aju na mesto.", "minus");
        toast("Pogre\u0161an redosled", "minus");
        renderInteractiveEscapeRoom();
        return;
    }
    escapeFlags.symbolsSolved = true;
    pendingRoomMotion = "is-final-unlocking";
    addEscapeItem("finalSeal", "Pe\u010Dat nasle\u0111a");
    renderInteractiveEscapeRoom();
};

nextEscapeRoom = function(message) {
    toast("Soba otvorena", "plus");
    const nextRoom = escapeRoomFlow[escapeIndex + 1];
    playRoomTransition(nextRoom ? `Ulazi\u0161 u: ${nextRoom.title}` : "Finalna odaja se otvara", () => {
        escapeIndex += 1;
        escapeMessage = message;
        renderInteractiveEscapeRoom();
    });
};

const originalMotionApplyChoice = applyChoice;
applyChoice = async function(choice) {
    const before = getLevelProgress(currentTurn).level;
    await originalMotionApplyChoice(choice);
    const after = getLevelProgress(currentTurn).level;
    if (isGameScreenActive() && after > before && after !== lastRenderedLevel) {
        lastRenderedLevel = after;
        playLevelTransition(after);
    }
};

const originalMotionStartDecisionGame = startDecisionGame;
startDecisionGame = async function() {
    lastRenderedLevel = 1;
    await originalMotionStartDecisionGame();
};

document.addEventListener("DOMContentLoaded", () => {
    ensureTransitionOverlay();
    ensureLevelTransitionOverlay();
});

/* =========================
   ESCAPE ROOM CLARITY / MISSION SYSTEM
   ========================= */

let escapeIntroSeen = false;

function escapeRoomChecklist(roomKey) {
    const checks = {
        library: [
            ["Prona\u0111i stari klju\u010D", hasEscapeItem("oldKey")],
            ["Pro\u010Ditaj knjigu sa tragom", !!escapeFlags.book],
            ["Otvori kov\u010Deg", !!escapeFlags.chest],
            ["Uzmi polovinu pe\u010Data", hasEscapeItem("sealHalf")],
            ["Otvori vrata biblioteke", hasEscapeItem("oldKey") && hasEscapeItem("sealHalf")]
        ],
        treasury: [
            ["Prona\u0111i svitak sa kodom", !!escapeFlags.scroll],
            ["Uzmi vosak kod ikona", hasEscapeItem("wax")],
            ["Unesi kod u sef", !!escapeFlags.safeOpen],
            ["Uzmi zlatni pe\u010Dat", hasEscapeItem("goldSeal")],
            ["Otvori prolaz ka finalnoj dvorani", hasEscapeItem("goldSeal")]
        ],
        sanctum: [
            ["Proveri oltar", !!escapeFlags.altar],
            ["Pro\u010Ditaj trag kod simbola", !!escapeFlags.symbols],
            ["Postavi redosled: Mudrost, Vera, Narod", !!escapeFlags.symbolsSolved],
            ["Uzmi Pe\u010Dat nasle\u0111a", hasEscapeItem("finalSeal")],
            ["Otvori izlaz", !!escapeFlags.symbolsSolved && hasEscapeItem("finalSeal")]
        ]
    };
    return checks[roomKey] || [];
}

function escapeRoomNextStep(roomKey) {
    const firstOpen = escapeRoomChecklist(roomKey).find(([, done]) => !done);
    return firstOpen ? firstOpen[0] : "Soba je spremna. Otvori prolaz dalje.";
}

function renderEscapeChecklist(roomKey) {
    return escapeRoomChecklist(roomKey).map(([label, done]) => `
        <div class="pv-escape-step ${done ? "is-done" : ""}">
            <span>${done ? "\u2713" : ""}</span>
            <strong>${label}</strong>
        </div>
    `).join("");
}

function escapeRoomHowTo(roomKey) {
    if (roomKey === "library") {
        return "Ovo je prva soba. Klikni predmete na slici, prona\u0111i klju\u010D, zatim njime otvori kov\u010Deg. Vrata dalje se otvaraju tek kada ima\u0161 i klju\u010D i pe\u010Dat.";
    }
    if (roomKey === "treasury") {
        return "Ovo je soba sa kodom. Prvo prona\u0111i trag, zatim unesi kod u sef. Kada dobije\u0161 zlatni pe\u010Dat, prolaz dalje postaje dostupan.";
    }
    return "Ovo je finalna soba. Mora\u0161 shvatiti redosled simbola, postaviti pe\u010Date i tek onda otvoriti izlaz.";
}

function showEscapeIntroModal() {
    let modal = byId("escapeIntroModal");
    if (!modal) {
        modal = document.createElement("section");
        modal.id = "escapeIntroModal";
        modal.className = "pv-modal pv-escape-intro-modal hidden";
        modal.setAttribute("aria-hidden", "true");
        modal.innerHTML = `
            <div class="pv-modal-card pv-escape-intro-card">
                <div class="pv-modal-badge">Zavr\u0161ni izazov</div>
                <h3 class="pv-modal-title">Cilj escape room-a</h3>
                <div class="pv-modal-text pv-escape-intro-text">
                    <p><strong>Glavni cilj:</strong> prona\u0111i Pe\u010Dat nasle\u0111a i iza\u0111i iz tajne odaje pre isteka vremena.</p>
                    <p><strong>Kako se igra:</strong> klik\u0107e\u0161 predmete na slici sobe, skuplja\u0161 ih u inventar i koristi\u0161 tragove da otklju\u010Da\u0161 slede\u0107u prostoriju.</p>
                    <p><strong>Redosled:</strong> biblioteka \u2192 riznica \u2192 dvorana nasle\u0111a \u2192 izlaz.</p>
                </div>
                <div class="pv-end-actions pv-modal-actions">
                    <button type="button" class="pv-btn pv-btn-primary" id="closeEscapeIntroBtn">Razumem, kreni</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        byId("closeEscapeIntroBtn")?.addEventListener("click", () => {
            modal.classList.remove("active");
            modal.classList.add("hidden");
            modal.setAttribute("aria-hidden", "true");
        });
    }
    modal.classList.remove("hidden");
    modal.classList.add("active");
    modal.setAttribute("aria-hidden", "false");
    applyScriptMode(scriptMode);
}

const originalClarityRenderInteractiveEscapeRoom = renderInteractiveEscapeRoom;
renderInteractiveEscapeRoom = function() {
    const room = escapeRoomFlow[escapeIndex];
    if (!room) {
        finishEscapeRoom(true);
        return;
    }

    byId("escapeRoomLabel").innerText = room.label;
    byId("escapeTitle").innerText = room.title;
    byId("escapeType").innerText = room.type;

    const container = document.querySelector(".pv-escape-room");
    if (!container) return;

    container.innerHTML = `
        <div class="pv-escape-stage" data-room="${room.key}">
            <div class="pv-room-art pv-room-${room.key}" aria-label="${room.title}">
                <div class="pv-room-depth"></div>
                <div class="pv-room-goal-ribbon">
                    <span>Trenutni cilj</span>
                    <strong>${escapeRoomNextStep(room.key)}</strong>
                </div>
                ${room.hotspots.map(hotspot => `
                    <button type="button" class="pv-hotspot ${escapeFlags[hotspot.id] ? "is-used" : ""}" style="left:${hotspot.x}%;top:${hotspot.y}%;" data-action="${hotspot.action}">
                        <span>${hotspot.label}</span>
                    </button>
                `).join("")}
            </div>
        </div>
        <aside class="pv-escape-side pv-escape-side-clear">
            <div class="pv-escape-mission">
                <div class="pv-objective-title">Misija</div>
                <h3>Prona\u0111i Pe\u010Dat nasle\u0111a i iza\u0111i iz odaje</h3>
                <p>${escapeRoomHowTo(room.key)}</p>
            </div>
            <div class="pv-escape-checklist">
                <div class="pv-objective-title">Koraci</div>
                ${renderEscapeChecklist(room.key)}
            </div>
            <div class="pv-inventory-panel">
                <div class="pv-objective-title">Inventar</div>
                <div class="pv-inventory-list" id="escapeInventoryList">${renderEscapeInventory()}</div>
            </div>
            ${renderEscapePuzzle(room)}
            <div class="pv-escape-feedback" id="escapeFeedback" aria-live="polite">${escapeMessage}</div>
            <div class="pv-end-actions pv-escape-actions">
                <button type="button" class="pv-btn pv-btn-secondary" id="hintEscapeBtn">Trag</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="escapeGoalBtn">Cilj</button>
                <button type="button" class="pv-btn pv-btn-secondary" id="escapeMenuBtn">Meni</button>
            </div>
        </aside>
    `;

    container.querySelectorAll(".pv-hotspot").forEach(button => {
        button.addEventListener("click", () => handleEscapeAction(button.dataset.action));
    });
    byId("hintEscapeBtn")?.addEventListener("click", showEscapeHint);
    byId("escapeGoalBtn")?.addEventListener("click", showEscapeIntroModal);
    byId("escapeMenuBtn")?.addEventListener("click", backToMenu);
    byId("escapeCodeBtn")?.addEventListener("click", submitEscapeCode);
    byId("escapeCodeInput")?.addEventListener("keydown", event => {
        if (event.key === "Enter") submitEscapeCode();
    });
    byId("symbolConfirmBtn")?.addEventListener("click", submitSymbolPuzzle);
    byId("symbolOrder")?.addEventListener("click", event => {
        const token = event.target.closest(".pv-symbol-token");
        if (!token) return;
        token.classList.toggle("is-selected");
    });

    decorateEscapeRoomMotion();
    applyScriptMode(scriptMode);
};
renderEscapeRoom = renderInteractiveEscapeRoom;

const originalClarityStartEscapeRoom = startEscapeRoom;
startEscapeRoom = function() {
    originalClarityStartEscapeRoom();
    escapeIntroSeen = true;
    setTimeout(showEscapeIntroModal, 180);
};

showEscapeHint = function() {
    const room = escapeRoomFlow[escapeIndex];
    if (!room) return;
    setEscapeMessage(`${room.hint} Slede\u0107i korak: ${escapeRoomNextStep(room.key)}.`, "plus");
};

/* =========================
   ESCAPE ROOM CLICK FIX
   ========================= */

function runEscapeActionNow(action) {
    escapeActionLocked = false;
    switch (action) {
        case "searchDesk":
            escapeFlags.desk = true;
            pendingRoomMotion = "is-item-found";
            addEscapeItem("oldKey", "Stari klju\u010D");
            break;
        case "readBook":
            escapeFlags.book = true;
            pendingRoomMotion = "is-pages-open";
            setEscapeMessage("Knjiga se otvara. U njoj pi\u0161e: klju\u010D otvara kov\u010Deg, ali pe\u010Dat otvara put dalje.", "plus");
            break;
        case "openFirstChest":
            if (!hasEscapeItem("oldKey")) {
                pendingRoomMotion = "is-locked-shake";
                setEscapeMessage("Kov\u010Deg se ne pomera. Prvo prona\u0111i klju\u010D.", "minus");
                break;
            }
            escapeFlags.chest = true;
            pendingRoomMotion = "is-chest-opening";
            addEscapeItem("sealHalf", "Polovina pe\u010Data");
            break;
        case "leaveLibrary":
            if (!hasEscapeItem("oldKey") || !hasEscapeItem("sealHalf")) {
                pendingRoomMotion = "is-locked-shake";
                setEscapeMessage("Vrata tra\u017Ee klju\u010D i polovinu pe\u010Data iz kov\u010Dega.", "minus");
                break;
            }
            nextEscapeRoom("Vrata biblioteke su se otvorila.");
            return;
        case "readScroll":
            escapeFlags.scroll = true;
            pendingRoomMotion = "is-scroll-open";
            setEscapeMessage("Svitak se odmotava. Na njemu stoji niz: 3 - 5 - 7 - 2.", "plus");
            break;
        case "inspectIcons":
            escapeFlags.icons = true;
            pendingRoomMotion = "is-item-found";
            addEscapeItem("wax", "Vosak");
            break;
        case "focusCode":
            pendingRoomMotion = escapeFlags.safeOpen ? "" : "is-safe-focus";
            setEscapeMessage("Sef ima \u010Detiri mesta za broj. Potra\u017Ei niz na svitku.");
            byId("escapeCodeInput")?.focus();
            break;
        case "leaveTreasury":
            if (!hasEscapeItem("goldSeal")) {
                pendingRoomMotion = "is-locked-shake";
                setEscapeMessage("Prolaz se ne otvara bez zlatnog pe\u010Data iz sefa.", "minus");
                break;
            }
            nextEscapeRoom("Riznica je ostala iza tebe. Finalna dvorana je blizu.");
            return;
        case "inspectAltar":
            escapeFlags.altar = true;
            pendingRoomMotion = "is-altar-lit";
            setEscapeMessage("Sve\u0107e na oltaru se pale. Tri udubljenja \u010Dekaju: Mudrost, Vera i Narod.", "plus");
            break;
        case "inspectSymbols":
            escapeFlags.symbols = true;
            pendingRoomMotion = "is-symbols-glow";
            setEscapeMessage("Simboli zasvetle. Redosled je: odluka, duhovni temelj, narod.", "plus");
            break;
        case "finishSanctum":
            if (!escapeFlags.symbolsSolved || !hasEscapeItem("finalSeal")) {
                pendingRoomMotion = "is-locked-shake";
                setEscapeMessage("Finalna vrata tra\u017Ee pravilno postavljene simbole i pe\u010Dat nasle\u0111a.", "minus");
                break;
            }
            playRoomTransition("Finalna vrata se otvaraju", () => finishEscapeRoom(true));
            return;
        default:
            setEscapeMessage("Ovde nema ni\u010Deg korisnog.");
    }
    renderInteractiveEscapeRoom();
}

handleEscapeAction = function(action) {
    const button = document.querySelector(`.pv-hotspot[data-action="${action}"]`);
    const roomArt = document.querySelector(".pv-room-art");
    button?.classList.add("is-activating");
    roomArt?.classList.add("is-inspecting");
    setTimeout(() => runEscapeActionNow(action), 120);
};

document.addEventListener("click", event => {
    const hotspot = event.target.closest?.(".pv-hotspot[data-action]");
    if (!hotspot || !byId("escapeScreen")?.classList.contains("active")) return;
    event.preventDefault();
    event.stopPropagation();
    handleEscapeAction(hotspot.dataset.action);
}, true);

/* =========================
   GUIDED ESCAPE ROOM REBUILD
   ========================= */

const guidedEscapeRooms = [
    {
        key: "library",
        label: "Soba 1 od 3",
        title: "Manastirska biblioteka",
        subtitle: "Prona\u0111i klju\u010D i pe\u010Dat da otvori\u0161 vrata riznice.",
        imageClass: "pv-room-library",
        finalAction: "leaveLibrary",
        steps: [
            {
                id: "desk",
                title: "Pregledaj sto",
                text: "Na stolu mo\u017Ee biti prvi predmet koji pokre\u0107e izlaz iz biblioteke.",
                result: "Prona\u0161la si stari klju\u010D ispod pergamenta.",
                item: "oldKey",
                itemLabel: "Stari klju\u010D"
            },
            {
                id: "book",
                title: "Otvori staru knjigu",
                text: "Knjiga obja\u0161njava \u0161ta klju\u010D otvara i za\u0161to pe\u010Dat treba sa\u010Duvati.",
                result: "U knjizi pi\u0161e: klju\u010D otvara kov\u010Deg, a pe\u010Dat otvara put dalje."
            },
            {
                id: "chest",
                title: "Otklju\u010Daj kov\u010Deg",
                text: "Kov\u010Deg je zaklju\u010Dan. Potreban je stari klju\u010D iz biblioteke.",
                requires: ["oldKey"],
                result: "Kov\u010Deg se otvorio. Unutra je polovina pe\u010Data.",
                item: "sealHalf",
                itemLabel: "Polovina pe\u010Data"
            },
            {
                id: "door",
                title: "Otvori vrata riznice",
                text: "Vrata vode u slede\u0107u prostoriju, ali tra\u017Ee dokaz da si re\u0161ila biblioteku.",
                requires: ["oldKey", "sealHalf"],
                result: "Vrata biblioteke se otvaraju. Ulazi\u0161 u riznicu pe\u010Data.",
                transition: true
            }
        ]
    },
    {
        key: "treasury",
        label: "Soba 2 od 3",
        title: "Riznica pe\u010Data",
        subtitle: "Prona\u0111i kod, otvori sef i uzmi zlatni pe\u010Dat.",
        imageClass: "pv-room-treasury",
        finalAction: "leaveTreasury",
        steps: [
            {
                id: "scroll",
                title: "Pro\u010Ditaj svitak",
                text: "Svitak krije redosled brojeva za sef.",
                result: "Na svitku pi\u0161e: 3 - 5 - 7 - 2. To je kod sefa."
            },
            {
                id: "icons",
                title: "Pregledaj ikone",
                text: "Kod ikona se nalazi vosak koji \u0107e kasnije pomo\u0107i oko pe\u010Data.",
                result: "Uzela si vosak za pe\u010Dat.",
                item: "wax",
                itemLabel: "Vosak"
            },
            {
                id: "safe",
                title: "Otvori sef kodom",
                text: "Unesi kod sa svitka da otvori\u0161 sef.",
                requiresFlags: ["scroll"],
                puzzle: "code",
                result: "Sef se otvorio. Dobila si zlatni pe\u010Dat.",
                item: "goldSeal",
                itemLabel: "Zlatni pe\u010Dat"
            },
            {
                id: "gate",
                title: "Otvori prolaz ka dvorani",
                text: "Prolaz se otvara samo ako ima\u0161 zlatni pe\u010Dat iz sefa.",
                requires: ["goldSeal"],
                result: "Riznica je ostala iza tebe. Ulazi\u0161 u dvoranu nasle\u0111a.",
                transition: true
            }
        ]
    },
    {
        key: "sanctum",
        label: "Soba 3 od 3",
        title: "Dvorana nasle\u0111a",
        subtitle: "Postavi tri vrednosti vladara i otvori finalni izlaz.",
        imageClass: "pv-room-sanctum",
        finalAction: "finish",
        steps: [
            {
                id: "altar",
                title: "Pregledaj oltar",
                text: "Oltar ima tri prazna mesta za vrednosti koje su odr\u017Eale tvoju vladavinu.",
                result: "Na oltaru pi\u0161e: prvo odluka, zatim duhovni temelj, pa narod."
            },
            {
                id: "symbols",
                title: "Re\u0161i redosled simbola",
                text: "Izaberi vrednosti pravim redom: Mudrost, Vera, Narod.",
                requiresFlags: ["altar"],
                puzzle: "symbols",
                result: "Simboli su se uklopili i stvoren je Pe\u010Dat nasle\u0111a.",
                item: "finalSeal",
                itemLabel: "Pe\u010Dat nasle\u0111a"
            },
            {
                id: "finalDoor",
                title: "Otvori finalni izlaz",
                text: "Finalna vrata se otvaraju samo ako ima\u0161 Pe\u010Dat nasle\u0111a.",
                requires: ["finalSeal"],
                result: "Finalna vrata se otvaraju. Escape room je zavr\u0161en.",
                finish: true
            }
        ]
    }
];

function guidedRoom() {
    return guidedEscapeRooms[escapeIndex] || guidedEscapeRooms[0];
}

function isGuidedStepDone(step) {
    return !!escapeFlags[`guided_${guidedRoom().key}_${step.id}`];
}

function markGuidedStepDone(step) {
    escapeFlags[`guided_${guidedRoom().key}_${step.id}`] = true;
    escapeFlags[step.id] = true;
}

function guidedMissing(step) {
    const missingItems = (step.requires || []).filter(item => !hasEscapeItem(item)).map(escapeItemLabel);
    const missingFlags = (step.requiresFlags || []).filter(flag => !escapeFlags[flag]).map(flag => {
        const labels = { scroll: "prvo pro\u010Ditaj svitak", altar: "prvo pregledaj oltar" };
        return labels[flag] || flag;
    });
    return [...missingItems, ...missingFlags];
}

function isGuidedStepLocked(step) {
    return guidedMissing(step).length > 0;
}

function nextGuidedStep() {
    const room = guidedRoom();
    return room.steps.find(step => !isGuidedStepDone(step)) || room.steps[room.steps.length - 1];
}

function renderGuidedSteps(room) {
    const next = nextGuidedStep();
    return room.steps.map((step, index) => {
        const done = isGuidedStepDone(step);
        const locked = isGuidedStepLocked(step);
        const active = next?.id === step.id && !done;
        const missing = guidedMissing(step);
        return `
            <button type="button" class="pv-guided-step ${done ? "is-done" : ""} ${locked ? "is-locked" : ""} ${active ? "is-active" : ""}" data-step="${step.id}">
                <span class="pv-step-number">${done ? "\u2713" : index + 1}</span>
                <span class="pv-step-copy">
                    <strong>${step.title}</strong>
                    <small>${locked ? `Zaklju\u010Dano: potrebno je ${missing.join(", ")}` : step.text}</small>
                </span>
            </button>
        `;
    }).join("");
}

function renderGuidedPuzzle(step) {
    if (!step || isGuidedStepDone(step)) return "";
    if (step.puzzle === "code") {
        return `
            <div class="pv-guided-puzzle">
                <label for="guidedCodeInput">Kod sa svitka</label>
                <div class="pv-code-entry">
                    <input type="text" id="guidedCodeInput" maxlength="4" inputmode="numeric" autocomplete="off" placeholder="3572">
                    <button type="button" class="pv-mini-action" id="guidedCodeBtn">Otvori sef</button>
                </div>
            </div>
        `;
    }
    if (step.puzzle === "symbols") {
        return `
            <div class="pv-guided-puzzle">
                <div class="pv-puzzle-title">Klikni redom: Mudrost, Vera, Narod</div>
                <div class="pv-symbol-order" id="guidedSymbolOrder">
                    ${["Mudrost", "Vera", "Narod"].map(value => `<button type="button" class="pv-symbol-token" data-symbol="${value}">${value}</button>`).join("")}
                </div>
                <button type="button" class="pv-mini-action" id="guidedSymbolBtn">Potvrdi redosled</button>
            </div>
        `;
    }
    return "";
}

function renderGuidedEscapeRoom() {
    const room = guidedRoom();
    byId("escapeRoomLabel").innerText = room.label;
    byId("escapeTitle").innerText = room.title;
    byId("escapeType").innerText = "Jasni escape room";

    const next = nextGuidedStep();
    const container = document.querySelector(".pv-escape-room");
    if (!container) return;

    container.innerHTML = `
        <div class="pv-guided-escape">
            <section class="pv-guided-scene">
                <div class="pv-room-art ${room.imageClass} pv-guided-art" aria-label="${room.title}">
                    <div class="pv-room-depth"></div>
                    <div class="pv-guided-banner">
                        <span>${room.label}</span>
                        <strong>${room.subtitle}</strong>
                    </div>
                </div>
            </section>
            <aside class="pv-guided-panel">
                <div class="pv-guided-goal">
                    <div class="pv-objective-title">Glavni cilj</div>
                    <h3>Prona\u0111i Pe\u010Dat nasle\u0111a i iza\u0111i iz tajne odaje</h3>
                    <p>Soba se re\u0161ava redom. Klikni aktivnu karticu, sakupi predmet u inventar, pa otklju\u010Daj slede\u0107i korak.</p>
                </div>
                <div class="pv-inventory-panel">
                    <div class="pv-objective-title">Inventar</div>
                    <div class="pv-inventory-list" id="escapeInventoryList">${renderEscapeInventory()}</div>
                </div>
                <div class="pv-guided-current">
                    <div class="pv-objective-title">Slede\u0107i potez</div>
                    <strong>${next?.title || "Soba je zavr\u0161ena"}</strong>
                    <span>${escapeMessage}</span>
                </div>
            </aside>
            <section class="pv-guided-actions">
                ${renderGuidedSteps(room)}
                ${renderGuidedPuzzle(next)}
                <div class="pv-guided-controls">
                    <button type="button" class="pv-btn pv-btn-secondary" id="hintEscapeBtn">Trag</button>
                    <button type="button" class="pv-btn pv-btn-secondary" id="escapeGoalBtn">Cilj</button>
                    <button type="button" class="pv-btn pv-btn-secondary" id="escapeMenuBtn">Meni</button>
                </div>
            </section>
        </div>
    `;

    container.querySelectorAll(".pv-guided-step").forEach(button => {
        button.addEventListener("click", () => handleGuidedStep(button.dataset.step));
    });
    byId("guidedCodeBtn")?.addEventListener("click", submitGuidedCode);
    byId("guidedCodeInput")?.addEventListener("keydown", event => {
        if (event.key === "Enter") submitGuidedCode();
    });
    byId("guidedSymbolOrder")?.addEventListener("click", event => {
        const token = event.target.closest(".pv-symbol-token");
        if (!token) return;
        token.classList.toggle("is-selected");
    });
    byId("guidedSymbolBtn")?.addEventListener("click", submitGuidedSymbols);
    byId("hintEscapeBtn")?.addEventListener("click", showGuidedHint);
    byId("escapeGoalBtn")?.addEventListener("click", showEscapeIntroModal);
    byId("escapeMenuBtn")?.addEventListener("click", backToMenu);
    applyScriptMode(scriptMode);
}

function completeGuidedStep(step) {
    markGuidedStepDone(step);
    if (step.item) addEscapeItem(step.item, step.itemLabel);
    escapeMessage = step.result;
    pendingRoomMotion = step.item ? "is-item-found" : "is-pages-open";

    if (step.transition) {
        playRoomTransition(step.result, () => {
            escapeIndex += 1;
            escapeMessage = "Nova soba je otvorena. Nastavi redom kroz korake.";
            renderGuidedEscapeRoom();
        });
        return;
    }

    if (step.finish) {
        playRoomTransition("Finalna vrata se otvaraju", () => finishEscapeRoom(true));
        return;
    }

    renderGuidedEscapeRoom();
}

function handleGuidedStep(stepId) {
    const room = guidedRoom();
    const step = room.steps.find(item => item.id === stepId);
    if (!step) return;
    if (isGuidedStepDone(step)) {
        escapeMessage = "Ovaj korak je ve\u0107 ura\u0111en. Nastavi na slede\u0107i.";
        renderGuidedEscapeRoom();
        return;
    }
    const missing = guidedMissing(step);
    if (missing.length) {
        escapeMistakes += 1;
        escapeMessage = `Jo\u0161 ne mo\u017Ee: potrebno je ${missing.join(", ")}.`;
        pendingRoomMotion = "is-locked-shake";
        renderGuidedEscapeRoom();
        return;
    }
    if (step.puzzle) {
        escapeMessage = "Ovaj korak tra\u017Ei re\u0161avanje polja ispod kartica.";
        renderGuidedEscapeRoom();
        return;
    }
    completeGuidedStep(step);
}

function submitGuidedCode() {
    const room = guidedRoom();
    const step = room.steps.find(item => item.id === "safe");
    const value = normalizeAnswer(byId("guidedCodeInput")?.value);
    if (value !== "3572") {
        escapeMistakes += 1;
        escapeMessage = "Kod nije ta\u010Dan. Pro\u010Ditaj svitak: tamo pi\u0161e 3 - 5 - 7 - 2.";
        pendingRoomMotion = "is-locked-shake";
        renderGuidedEscapeRoom();
        return;
    }
    escapeFlags.safeOpen = true;
    completeGuidedStep(step);
}

function submitGuidedSymbols() {
    const room = guidedRoom();
    const step = room.steps.find(item => item.id === "symbols");
    const selected = Array.from(document.querySelectorAll("#guidedSymbolOrder .pv-symbol-token.is-selected")).map(item => item.dataset.symbol);
    if (selected.join("|") !== "Mudrost|Vera|Narod") {
        escapeMistakes += 1;
        escapeMessage = "Redosled nije dobar. Klikni redom: Mudrost, Vera, Narod.";
        pendingRoomMotion = "is-locked-shake";
        renderGuidedEscapeRoom();
        return;
    }
    escapeFlags.symbolsSolved = true;
    completeGuidedStep(step);
}

function showGuidedHint() {
    const next = nextGuidedStep();
    if (!next) return;
    escapeMessage = `Slede\u0107e uradi: ${next.title}. ${next.text}`;
    renderGuidedEscapeRoom();
}

startEscapeRoom = function() {
    escapeIndex = 0;
    escapeMistakes = 0;
    escapeSecondsLeft = 420;
    escapeInventory = [];
    escapeFlags = {};
    escapeMessage = "Po\u010Dni od prve aktivne kartice: Pregledaj sto.";
    setActiveScreen("escapeScreen");
    renderGuidedEscapeRoom();
    startEscapeTimer();
    setTimeout(showEscapeIntroModal, 180);
    applyScriptMode(scriptMode);
};

renderInteractiveEscapeRoom = renderGuidedEscapeRoom;
renderEscapeRoom = renderGuidedEscapeRoom;
showEscapeHint = showGuidedHint;

/* =========================
   FINAL IMAGE ESCAPE ROOM RESET
   ========================= */

const imageEscapeRooms = [
    {
        key: "library",
        label: "Soba 1 od 3",
        title: "Manastirska biblioteka",
        goal: "Prona\u0111i stari klju\u010D, otvori kov\u010Deg i uzmi polovinu pe\u010Data.",
        hint: "Redosled: sto, knjiga, kov\u010Deg, vrata.",
        zones: [
            { label: "Sto", action: "imgKey", x: 24, y: 68, w: 21, h: 18 },
            { label: "Knjiga", action: "imgBook", x: 47, y: 50, w: 20, h: 17 },
            { label: "Kov\u010Deg", action: "imgChest", x: 75, y: 70, w: 22, h: 18 },
            { label: "Vrata", action: "imgDoor1", x: 89, y: 42, w: 16, h: 34 }
        ]
    },
    {
        key: "treasury",
        label: "Soba 2 od 3",
        title: "Riznica pe\u010Data",
        goal: "Prona\u0111i kod na svitku, otvori sef i uzmi zlatni pe\u010Dat.",
        hint: "Redosled: svitak, ikone, sef, prolaz. Kod je 3572.",
        zones: [
            { label: "Svitak", action: "imgScroll", x: 30, y: 43, w: 20, h: 17 },
            { label: "Ikone", action: "imgIcons", x: 61, y: 31, w: 22, h: 18 },
            { label: "Sef", action: "imgSafe", x: 75, y: 69, w: 22, h: 20 },
            { label: "Prolaz", action: "imgDoor2", x: 90, y: 43, w: 15, h: 34 }
        ]
    },
    {
        key: "sanctum",
        label: "Soba 3 od 3",
        title: "Dvorana nasle\u0111a",
        goal: "Postavi vrednosti vladara pravim redom i otvori finalni izlaz.",
        hint: "Redosled simbola: Mudrost, Vera, Narod.",
        zones: [
            { label: "Oltar", action: "imgAltar", x: 50, y: 58, w: 24, h: 20 },
            { label: "Simboli", action: "imgSymbols", x: 32, y: 34, w: 25, h: 20 },
            { label: "Izlaz", action: "imgExit", x: 85, y: 42, w: 17, h: 34 }
        ]
    }
];

function imageRoom() {
    return imageEscapeRooms[escapeIndex] || imageEscapeRooms[0];
}

function imageItemDone(action) {
    return {
        imgKey: hasEscapeItem("oldKey"),
        imgBook: !!escapeFlags.imageBook,
        imgChest: hasEscapeItem("sealHalf"),
        imgDoor1: hasEscapeItem("oldKey") && hasEscapeItem("sealHalf"),
        imgScroll: !!escapeFlags.imageScroll,
        imgIcons: hasEscapeItem("wax"),
        imgSafe: hasEscapeItem("goldSeal"),
        imgDoor2: hasEscapeItem("goldSeal"),
        imgAltar: !!escapeFlags.imageAltar,
        imgSymbols: hasEscapeItem("finalSeal"),
        imgExit: hasEscapeItem("finalSeal")
    }[action] || false;
}

function imageNextInstruction() {
    const room = imageRoom();
    if (room.key === "library") {
        if (!hasEscapeItem("oldKey")) return "Klikni na sto i uzmi stari klju\u010D.";
        if (!escapeFlags.imageBook) return "Klikni knjigu da dobije\u0161 trag.";
        if (!hasEscapeItem("sealHalf")) return "Klikni kov\u010Deg i otvori ga klju\u010Dem.";
        return "Klikni vrata i pre\u0111i u riznicu.";
    }
    if (room.key === "treasury") {
        if (!escapeFlags.imageScroll) return "Klikni svitak da sazna\u0161 kod.";
        if (!hasEscapeItem("wax")) return "Klikni ikone i uzmi vosak.";
        if (!hasEscapeItem("goldSeal")) return "Klikni sef i unesi kod 3572.";
        return "Klikni prolaz i pre\u0111i u dvoranu nasle\u0111a.";
    }
    if (!escapeFlags.imageAltar) return "Klikni oltar da pro\u010Dita\u0161 finalni trag.";
    if (!hasEscapeItem("finalSeal")) return "Klikni simbole i potvrdi redosled Mudrost, Vera, Narod.";
    return "Klikni izlaz i zavr\u0161i escape room.";
}

function imageInventoryHtml() {
    if (!escapeInventory.length) return '<span class="pv-image-empty">Inventar je prazan</span>';
    return escapeInventory.map(item => `<span class="pv-inventory-chip">${escapeItemLabel(item)}</span>`).join("");
}

function imagePuzzleHtml() {
    const room = imageRoom();
    if (room.key === "treasury" && escapeFlags.imageSafeOpen && !hasEscapeItem("goldSeal")) {
        return `
            <div class="pv-image-puzzle">
                <strong>Unesi kod za sef</strong>
                <div class="pv-code-entry">
                    <input id="imageCodeInput" type="text" maxlength="4" inputmode="numeric" autocomplete="off" placeholder="3572">
                    <button type="button" class="pv-mini-action" id="imageCodeBtn">Otvori</button>
                </div>
            </div>
        `;
    }
    if (room.key === "sanctum" && escapeFlags.imageSymbolsOpen && !hasEscapeItem("finalSeal")) {
        return `
            <div class="pv-image-puzzle">
                <strong>Izaberi redom: Mudrost, Vera, Narod</strong>
                <div class="pv-symbol-order" id="imageSymbolOrder">
                    ${["Mudrost", "Vera", "Narod"].map(value => `<button type="button" class="pv-symbol-token" data-symbol="${value}">${value}</button>`).join("")}
                </div>
                <button type="button" class="pv-mini-action" id="imageSymbolBtn">Potvrdi</button>
            </div>
        `;
    }
    return "";
}

function renderImageEscapeRoom() {
    const room = imageRoom();
    byId("escapeRoomLabel").innerText = room.label;
    byId("escapeTitle").innerText = room.title;
    byId("escapeType").innerText = "Escape room";

    const container = document.querySelector(".pv-escape-room");
    if (!container) return;

    container.innerHTML = `
        <section class="pv-image-escape">
            <div class="pv-image-scene pv-room-${room.key}">
                ${room.zones.map(zone => `
                    <button type="button" class="pv-image-zone ${imageItemDone(zone.action) ? "is-done" : ""}" data-action="${zone.action}" style="left:${zone.x}%;top:${zone.y}%;width:${zone.w}%;height:${zone.h}%">
                        <span>${zone.label}</span>
                    </button>
                `).join("")}
                <div class="pv-image-objective">
                    <span>${room.label}</span>
                    <strong>${room.goal}</strong>
                </div>
            </div>
            <aside class="pv-image-panel">
                <div class="pv-image-current">
                    <span>Slede\u0107i potez</span>
                    <strong>${imageNextInstruction()}</strong>
                    <p id="escapeFeedback">${escapeMessage}</p>
                </div>
                <div class="pv-image-inventory">
                    <span>Inventar</span>
                    <div>${imageInventoryHtml()}</div>
                </div>
                ${imagePuzzleHtml()}
                <div class="pv-image-actions">
                    <button type="button" class="pv-btn pv-btn-secondary" id="hintEscapeBtn">Trag</button>
                    <button type="button" class="pv-btn pv-btn-secondary" id="escapeMenuBtn">Meni</button>
                </div>
            </aside>
        </section>
    `;

    container.querySelectorAll(".pv-image-zone").forEach(button => {
        button.addEventListener("click", () => handleImageEscapeAction(button.dataset.action));
    });
    byId("hintEscapeBtn")?.addEventListener("click", () => {
        escapeMessage = `${room.hint} ${imageNextInstruction()}`;
        renderImageEscapeRoom();
    });
    byId("escapeMenuBtn")?.addEventListener("click", backToMenu);
    byId("imageCodeBtn")?.addEventListener("click", submitImageCode);
    byId("imageCodeInput")?.addEventListener("keydown", event => {
        if (event.key === "Enter") submitImageCode();
    });
    byId("imageSymbolOrder")?.addEventListener("click", event => {
        const token = event.target.closest(".pv-symbol-token");
        if (!token) return;
        token.classList.toggle("is-selected");
    });
    byId("imageSymbolBtn")?.addEventListener("click", submitImageSymbols);
    applyScriptMode(scriptMode);
}

function goToImageRoom(index, message) {
    escapeMessage = message;
    escapeIndex = index;
    renderImageEscapeRoom();
}

function handleImageEscapeAction(action) {
    switch (action) {
        case "imgKey":
            addEscapeItem("oldKey", "Stari klju\u010D");
            escapeMessage = "Uzela si stari klju\u010D. Sada klikni knjigu.";
            break;
        case "imgBook":
            escapeFlags.imageBook = true;
            escapeMessage = "Knjiga ka\u017Ee: klju\u010D otvara kov\u010Deg, pe\u010Dat otvara vrata.";
            break;
        case "imgChest":
            if (!hasEscapeItem("oldKey")) {
                escapeMistakes += 1;
                escapeMessage = "Kov\u010Deg je zaklju\u010Dan. Prvo klikni sto i uzmi klju\u010D.";
                break;
            }
            addEscapeItem("sealHalf", "Polovina pe\u010Data");
            escapeMessage = "Kov\u010Deg je otvoren. Uzela si polovinu pe\u010Data. Sada klikni vrata.";
            break;
        case "imgDoor1":
            if (!hasEscapeItem("oldKey") || !hasEscapeItem("sealHalf")) {
                escapeMistakes += 1;
                escapeMessage = "Vrata tra\u017Ee stari klju\u010D i polovinu pe\u010Data.";
                break;
            }
            goToImageRoom(1, "Vrata biblioteke su otvorena. Sada prona\u0111i kod sefa." );
            return;
        case "imgScroll":
            escapeFlags.imageScroll = true;
            escapeMessage = "Na svitku pi\u0161e kod: 3572.";
            break;
        case "imgIcons":
            addEscapeItem("wax", "Vosak");
            escapeMessage = "Uzela si vosak. Sada klikni sef.";
            break;
        case "imgSafe":
            if (!escapeFlags.imageScroll) {
                escapeMistakes += 1;
                escapeMessage = "Prvo klikni svitak da sazna\u0161 kod.";
                break;
            }
            escapeFlags.imageSafeOpen = true;
            escapeMessage = "Unesi kod 3572 u polje za sef.";
            break;
        case "imgDoor2":
            if (!hasEscapeItem("goldSeal")) {
                escapeMistakes += 1;
                escapeMessage = "Prolaz tra\u017Ei zlatni pe\u010Dat iz sefa.";
                break;
            }
            goToImageRoom(2, "U\u0161la si u dvoranu nasle\u0111a. Prvo klikni oltar." );
            return;
        case "imgAltar":
            escapeFlags.imageAltar = true;
            escapeMessage = "Oltar tra\u017Ei redosled: Mudrost, Vera, Narod.";
            break;
        case "imgSymbols":
            if (!escapeFlags.imageAltar) {
                escapeMistakes += 1;
                escapeMessage = "Prvo klikni oltar da dobije\u0161 trag.";
                break;
            }
            escapeFlags.imageSymbolsOpen = true;
            escapeMessage = "Izaberi redom: Mudrost, Vera, Narod.";
            break;
        case "imgExit":
            if (!hasEscapeItem("finalSeal")) {
                escapeMistakes += 1;
                escapeMessage = "Izlaz tra\u017Ei Pe\u010Dat nasle\u0111a. Prvo re\u0161i simbole.";
                break;
            }
            finishEscapeRoom(true);
            return;
        default:
            escapeMessage = "Ovde nema ni\u010Deg va\u017Enog.";
    }
    renderImageEscapeRoom();
}

function submitImageCode() {
    const value = normalizeAnswer(byId("imageCodeInput")?.value);
    if (value !== "3572") {
        escapeMistakes += 1;
        escapeMessage = "Kod nije ta\u010Dan. Ta\u010Dan kod je 3572.";
        renderImageEscapeRoom();
        return;
    }
    addEscapeItem("goldSeal", "Zlatni pe\u010Dat");
    escapeMessage = "Sef se otvorio. Uzela si zlatni pe\u010Dat. Sada klikni prolaz.";
    renderImageEscapeRoom();
}

function submitImageSymbols() {
    const selected = Array.from(document.querySelectorAll("#imageSymbolOrder .pv-symbol-token.is-selected")).map(item => item.dataset.symbol);
    if (selected.join("|") !== "Mudrost|Vera|Narod") {
        escapeMistakes += 1;
        escapeMessage = "Redosled nije dobar. Ta\u010Dno je: Mudrost, Vera, Narod.";
        renderImageEscapeRoom();
        return;
    }
    addEscapeItem("finalSeal", "Pe\u010Dat nasle\u0111a");
    escapeMessage = "Pe\u010Dat nasle\u0111a je stvoren. Sada klikni izlaz.";
    renderImageEscapeRoom();
}

startEscapeRoom = function() {
    escapeIndex = 0;
    escapeMistakes = 0;
    escapeSecondsLeft = 420;
    escapeInventory = [];
    escapeFlags = {};
    escapeMessage = "Po\u010Dni klikom na sto.";
    byId("roomTransitionOverlay")?.classList.add("hidden");
    byId("levelTransitionOverlay")?.classList.add("hidden");
    setActiveScreen("escapeScreen");
    renderImageEscapeRoom();
    startEscapeTimer();
    applyScriptMode(scriptMode);
};

renderInteractiveEscapeRoom = renderImageEscapeRoom;
renderEscapeRoom = renderImageEscapeRoom;
showEscapeHint = function() {
    const room = imageRoom();
    escapeMessage = `${room.hint} ${imageNextInstruction()}`;
    renderImageEscapeRoom();
};

/* =========================
   PUT KROZ MANASTIRE - MINI GAMES MODE
   Replaces the old escape room with a map-based educational adventure.
   ========================= */

let monasteryIndex = 0;
let monasteryScore = 0;
let monasteryBadges = [];
let monasteryState = {};

const monasteryJourney = [
    {
        id: "studenica",
        name: "Studenica",
        mode: "Graditelj manastira",
        goal: "Izgradi zadu\u017Ebinu deo po deo i nau\u010Di \u0161ta \u010Dini manastirski kompleks.",
        image: "manastirska-biblioteka.jpg",
        badge: "Graditelj zadu\u017Ebine",
        type: "build",
        pieces: ["Temelj", "Zidovi", "Kupola", "Freske", "Biblioteka"],
        lesson: "Manastiri nisu bili samo crkve, ve\u0107 mesta pismenosti, umetnosti i pomaganja narodu."
    },
    {
        id: "zica",
        name: "\u017Di\u010Da",
        mode: "Kruna Nemanji\u0107a",
        goal: "Sakupi simbole koji predstavljaju vladarsku odgovornost.",
        image: "dvorana-nasledja.jpg",
        badge: "\u010Cuvar krune",
        type: "crown",
        required: ["Ma\u010D", "Knjiga", "Krst", "Povelja"],
        options: ["Ma\u010D", "Knjiga", "Krst", "Povelja", "Zlatnik", "\u0160tit", "Manastir"],
        lesson: "\u017Di\u010Da je vezana za krunisanje srpskih kraljeva i ideju dr\u017Eavnosti."
    },
    {
        id: "mileseva",
        name: "Mile\u0161eva",
        mode: "Manastirska radionica",
        goal: "Obnovi o\u0161te\u0107enu fresku tako \u0161to vra\u0107a\u0161 delove na mesto.",
        image: "manastirska-biblioteka.jpg",
        badge: "Obnovitelj freske",
        type: "restore",
        fragments: ["Oreol", "Knjiga", "Krilo", "Natpis", "Pozadina"],
        lesson: "Freske su prenosile pri\u010De, veru i istorijsko pam\u0107enje kroz slike."
    },
    {
        id: "gracanica",
        name: "Gra\u010Danica",
        mode: "Tajna povelja",
        goal: "Prona\u0111i i sastavi izgubljenu povelju vladara.",
        image: "riznica-pecata.jpg",
        badge: "\u010Cuvar povelje",
        type: "charter",
        fragments: ["Po\u010Detak", "Dar", "Pe\u010Dat", "Zavet"],
        lesson: "Povelja je va\u017Ean dokument kojim vladar potvr\u0111uje darove, prava i obaveze."
    },
    {
        id: "decani",
        name: "De\u010Dani",
        mode: "\u010Cuvar zadu\u017Ebine",
        goal: "Sa\u010Duvaj manastir tako \u0161to pravi ljudi re\u0161avaju prave probleme.",
        image: "dvorana-nasledja.jpg",
        badge: "\u010Cuvar zadu\u017Ebine",
        type: "guardian",
        pairs: [
            ["O\u0161te\u0107en zid", "Majstor"],
            ["Knjige u opasnosti", "Pisar"],
            ["Bolest u selu", "Lekar"],
            ["Nemir na putu", "Vojnik"]
        ],
        helpers: ["Majstor", "Monah", "Vojnik", "Lekar", "Trgovac", "Pisar"],
        lesson: "Manastir je opstajao zahvaljuju\u0107i radu mnogih ljudi: majstora, monaha, pisara i za\u0161titnika."
    },
    {
        id: "sopocani",
        name: "Sopo\u0107ani",
        mode: "Trgovac na manastirskom putu",
        goal: "Vodi karavan i rasporedi zalihe da put do manastira uspe.",
        image: "riznica-pecata.jpg",
        badge: "Mudri putnik",
        type: "caravan",
        events: [
            ["Pokvaren most", "Majstori"],
            ["Gladno selo", "Hrana"],
            ["Opasan prolaz", "Stra\u017Ea"],
            ["Manastirska biblioteka", "Knjige"]
        ],
        supplies: ["Majstori", "Hrana", "Stra\u017Ea", "Knjige", "Zlato", "Lekovi"],
        lesson: "Putevi su povezivali manastire, sela, trgovinu i znanje."
    },
    {
        id: "pec",
        name: "Pe\u0107ka patrijar\u0161ija",
        mode: "Branitelji zadu\u017Ebine",
        goal: "Rasporedi branitelje i sa\u010Duvaj zadu\u017Ebinu od opasnosti.",
        image: "dvorana-nasledja.jpg",
        badge: "Branitelj nasle\u0111a",
        type: "defenders",
        threats: [
            ["Po\u017Ear", "Majstor"],
            ["Nesta\u0161ica hrane", "Trgovac"],
            ["Napad razbojnika", "Vojnik"],
            ["O\u0161te\u0107ena knjiga", "Pisar"]
        ],
        defenders: ["Majstor", "Monah", "Vojnik", "Lekar", "Trgovac", "Pisar"],
        lesson: "Kulturno nasle\u0111e se \u010Duva znanjem, radom, hrabro\u0161\u0107u i brigom zajednice."
    },
    {
        id: "final",
        name: "Nasle\u0111e Srbije",
        mode: "Veliki pe\u010Dat puta",
        goal: "Sastavi zavr\u0161ni pe\u010Dat od osvojenih simbola.",
        image: "dvorana-nasledja.jpg",
        badge: "Mladi \u010Duvar nasle\u0111a",
        type: "seal",
        symbols: ["Kruna", "Knjiga", "Freska", "Povelja", "Zadu\u017Ebina", "\u0160tit"],
        lesson: "Znanje o nasle\u0111u postaje vredno tek kada ga \u010Duvamo i prenosimo dalje."
    }
];

function currentMonastery() {
    return monasteryJourney[monasteryIndex] || monasteryJourney[0];
}

function getMiniState() {
    const id = currentMonastery().id;
    monasteryState[id] ||= { selected: [], pairs: {}, done: false, message: "Zapo\u010Dni mini-igru." };
    return monasteryState[id];
}

function isUnlockedMonastery(index) {
    return index <= monasteryIndex;
}

function renderMonasteryAdventure() {
    const item = currentMonastery();
    const state = getMiniState();
    byId("escapeRoomLabel").innerText = `Lokacija ${monasteryIndex + 1} od ${monasteryJourney.length}`;
    byId("escapeTitle").innerText = "Put kroz manastire Srbije";
    byId("escapeType").innerText = item.mode;

    const container = document.querySelector(".pv-escape-room");
    if (!container) return;
    container.innerHTML = `
        <section class="pm-game">
            <aside class="pm-map">
                <div class="pm-map-title">Mapa puta</div>
                <div class="pm-map-list">
                    ${monasteryJourney.map((place, index) => `
                        <button type="button" class="pm-map-node ${index === monasteryIndex ? "is-active" : ""} ${monasteryState[place.id]?.done ? "is-done" : ""}" ${isUnlockedMonastery(index) ? "" : "disabled"} data-index="${index}">
                            <span>${index + 1}</span>
                            <strong>${place.name}</strong>
                        </button>
                    `).join("")}
                </div>
            </aside>
            <main class="pm-board">
                <div class="pm-hero" style="background-image: linear-gradient(180deg, rgba(10,3,4,.12), rgba(10,3,4,.38)), url('/images/game/${item.image}')">
                    <div class="pm-hero-copy">
                        <span>${item.name}</span>
                        <h3>${item.mode}</h3>
                        <p>${item.goal}</p>
                    </div>
                </div>
                <div class="pm-play">
                    ${renderMiniGame(item, state)}
                </div>
            </main>
            <aside class="pm-side">
                <div class="pm-card">
                    <span>Cilj</span>
                    <p>${item.goal}</p>
                </div>
                <div class="pm-card">
                    <span>Poruka</span>
                    <p id="escapeFeedback">${state.message}</p>
                </div>
                <div class="pm-card">
                    <span>Nau\u010Dili smo</span>
                    <p>${state.done ? item.lesson : "Zavr\u0161i zadatak da otklju\u010Da\u0161 kratko obja\u0161njenje."}</p>
                </div>
                <div class="pm-badges">
                    <span>Osvojene zna\u010Dke</span>
                    <div>${monasteryBadges.length ? monasteryBadges.map(badge => `<strong>${badge}</strong>`).join("") : "<em>Jo\u0161 nema zna\u010Dki</em>"}</div>
                </div>
                <div class="pm-actions">
                    <button type="button" class="pv-btn pv-btn-secondary" id="hintEscapeBtn">Trag</button>
                    <button type="button" class="pv-btn pv-btn-secondary" id="escapeMenuBtn">Meni</button>
                </div>
            </aside>
        </section>
    `;
    bindMonasteryAdventure();
    applyScriptMode(scriptMode);
}

function renderMiniGame(item, state) {
    if (item.type === "build") return renderTokenMini(item, state, item.pieces, "Izgradi delove zadu\u017Ebine", "build");
    if (item.type === "crown") return renderTokenMini(item, state, item.options, "Izaberi simbole za krunu", "crown");
    if (item.type === "restore") return renderTokenMini(item, state, item.fragments, "Vrati delove freske", "restore");
    if (item.type === "charter") return renderTokenMini(item, state, item.fragments, "Sastavi delove povelje", "charter");
    if (item.type === "guardian") return renderPairMini(item, state, item.pairs, item.helpers, "Dodeli pomaga\u010Da problemu");
    if (item.type === "caravan") return renderPairMini(item, state, item.events, item.supplies, "Dodeli zalihu doga\u0111aju na putu");
    if (item.type === "defenders") return renderPairMini(item, state, item.threats, item.defenders, "Dodeli branitelja opasnosti");
    if (item.type === "seal") return renderTokenMini(item, state, item.symbols, "Sastavi veliki pe\u010Dat", "seal");
    return "";
}

function renderTokenMini(item, state, tokens, title, action) {
    return `
        <div class="pm-mini-head">
            <h3>${title}</h3>
            <p>${state.done ? "Zadatak je zavr\u0161en." : "Klikni sve potrebne delove. Svaki klik dodaje deo u tvoje nasle\u0111e."}</p>
        </div>
        <div class="pm-token-grid">
            ${tokens.map(token => `<button type="button" class="pm-token ${state.selected.includes(token) ? "is-selected" : ""}" data-token="${token}" data-action="${action}">${token}</button>`).join("")}
        </div>
        <div class="pm-progress"><i style="width:${Math.round((state.selected.length / tokens.length) * 100)}%"></i></div>
        <button type="button" class="pv-btn pv-btn-primary pm-complete" data-complete="tokens" ${state.selected.length >= requiredTokenCount(item) || state.done ? "" : "disabled"}>Zavr\u0161i mini-igru</button>
    `;
}

function requiredTokenCount(item) {
    if (item.type === "crown") return item.required.length;
    if (item.type === "seal") return item.symbols.length;
    return (item.pieces || item.fragments || []).length;
}

function renderPairMini(item, state, pairs, choices, title) {
    return `
        <div class="pm-mini-head">
            <h3>${title}</h3>
            <p>Prvo klikni problem, zatim klikni pravi izbor ispod. Dobri spojevi se ozna\u010Davaju zeleno.</p>
        </div>
        <div class="pm-pair-layout">
            <div class="pm-problems">
                ${pairs.map(([problem, answer]) => `<button type="button" class="pm-problem ${state.activeProblem === problem ? "is-active" : ""} ${state.pairs[problem] === answer ? "is-done" : ""}" data-problem="${problem}">${problem}</button>`).join("")}
            </div>
            <div class="pm-choices">
                ${choices.map(choice => `<button type="button" class="pm-choice" data-choice="${choice}">${choice}</button>`).join("")}
            </div>
        </div>
        <button type="button" class="pv-btn pv-btn-primary pm-complete" data-complete="pairs" ${pairs.every(([p, a]) => state.pairs[p] === a) || state.done ? "" : "disabled"}>Zavr\u0161i mini-igru</button>
    `;
}

function bindMonasteryAdventure() {
    document.querySelectorAll(".pm-map-node:not(:disabled)").forEach(button => {
        button.addEventListener("click", () => {
            monasteryIndex = Number(button.dataset.index);
            renderMonasteryAdventure();
        });
    });
    document.querySelectorAll(".pm-token").forEach(button => {
        button.addEventListener("click", () => handleTokenClick(button.dataset.token));
    });
    document.querySelectorAll(".pm-problem").forEach(button => {
        button.addEventListener("click", () => {
            const state = getMiniState();
            state.activeProblem = button.dataset.problem;
            state.message = `Izabran problem: ${button.dataset.problem}. Sada izaberi re\u0161enje.`;
            renderMonasteryAdventure();
        });
    });
    document.querySelectorAll(".pm-choice").forEach(button => {
        button.addEventListener("click", () => handlePairChoice(button.dataset.choice));
    });
    document.querySelector(".pm-complete")?.addEventListener("click", finishCurrentMiniGame);
    byId("hintEscapeBtn")?.addEventListener("click", showMonasteryHint);
    byId("escapeMenuBtn")?.addEventListener("click", backToMenu);
}

function handleTokenClick(token) {
    const item = currentMonastery();
    const state = getMiniState();
    if (state.done) return;
    if (item.type === "crown" && !item.required.includes(token)) {
        state.message = `${token} je lep simbol, ali za ovu krunu prvo treba: ${item.required.join(", ")}.`;
        renderMonasteryAdventure();
        return;
    }
    if (!state.selected.includes(token)) state.selected.push(token);
    state.message = `Dodato: ${token}.`;
    renderMonasteryAdventure();
}

function handlePairChoice(choice) {
    const item = currentMonastery();
    const state = getMiniState();
    if (!state.activeProblem) {
        state.message = "Prvo izaberi problem sa leve strane.";
        renderMonasteryAdventure();
        return;
    }
    const expected = (item.pairs || item.events || item.threats).find(([problem]) => problem === state.activeProblem)?.[1];
    if (choice !== expected) {
        state.message = `${choice} nije najbolje re\u0161enje za: ${state.activeProblem}. Poku\u0161aj drugo.`;
        renderMonasteryAdventure();
        return;
    }
    state.pairs[state.activeProblem] = choice;
    state.message = `Ta\u010Dno: ${choice} re\u0161ava problem "${state.activeProblem}".`;
    state.activeProblem = null;
    renderMonasteryAdventure();
}

function finishCurrentMiniGame() {
    const item = currentMonastery();
    const state = getMiniState();
    state.done = true;
    state.message = item.lesson;
    if (!monasteryBadges.includes(item.badge)) monasteryBadges.push(item.badge);
    monasteryScore += 10;
    toast(item.badge, "plus");

    if (monasteryIndex >= monasteryJourney.length - 1) {
        finishMonasteryJourney();
        return;
    }
    monasteryIndex += 1;
    renderMonasteryAdventure();
}

function showMonasteryHint() {
    const item = currentMonastery();
    const state = getMiniState();
    if (["guardian", "caravan", "defenders"].includes(item.type)) {
        const pairs = item.pairs || item.events || item.threats;
        const next = pairs.find(([p, a]) => state.pairs[p] !== a);
        state.message = next ? `Trag: "${next[0]}" najbolje re\u0161ava "${next[1]}".` : "Sve je re\u0161eno. Klikni zavr\u0161i.";
    } else if (item.type === "crown") {
        state.message = `Trag: za krunu izaberi ${item.required.join(", ")}.`;
    } else {
        state.message = "Trag: klikni sve delove i prati traku napretka.";
    }
    renderMonasteryAdventure();
}

function finishMonasteryJourney() {
    stopEscapeTimer();
    setActiveScreen("finalScreen");
    byId("finalBadge").innerText = "Mladi \u010Duvar nasle\u0111a";
    byId("finalTitle").innerText = "Put kroz manastire je zavr\u0161en";
    byId("finalText").innerText = "Pro\u0161la si mapu manastira, zavr\u0161ila mini-igre i sakupila zna\u010Dke znanja.";
    byId("finalSummary").innerHTML = `
        <div class="pv-end-summary-box pv-final-parchment">
            <strong>Osvojene zna\u010Dke: ${monasteryBadges.length}</strong>
            <span>${monasteryBadges.join(" | ")}</span>
            <span>Rezultat: ${monasteryScore} poena</span>
        </div>
    `;
    byId("finalStats").innerHTML = buildStatBars();
    applyScriptMode(scriptMode);
}

startEscapeRoom = function() {
    monasteryIndex = 0;
    monasteryScore = 0;
    monasteryBadges = [];
    monasteryState = {};
    escapeSecondsLeft = 900;
    setActiveScreen("escapeScreen");
    renderMonasteryAdventure();
    startEscapeTimer();
    applyScriptMode(scriptMode);
};

renderEscapeRoom = renderMonasteryAdventure;
showEscapeHint = showMonasteryHint;

/* =========================
   START SCREEN MODE CHOICE
   ========================= */

function startMiniGamesMode() {
    closePauseModal(true);
    closeHelpModal(true);
    stopEscapeTimer();
    stopMainTimer?.();
    lastMainResult = { title: "Put kroz manastire Srbije" };
    gameState = {
        snaga: 8,
        mudrost: 8,
        vera: 8,
        ugled: 8,
        zlato: 8,
        stabilnost: 8
    };
    startEscapeRoom();
}

document.addEventListener("DOMContentLoaded", () => {
    byId("startMiniGamesBtn")?.addEventListener("click", startMiniGamesMode);
});

/* =========================
   POLISHED GAME-LIKE MINI GAMES UI
   ========================= */

const pmIcons = {
    "Studenica": "\u26EA",
    "\u017Di\u010Da": "\uD83D\uDC51",
    "Mile\u0161eva": "\uD83C\uDFA8",
    "Gra\u010Danica": "\uD83D\uDCDC",
    "De\u010Dani": "\uD83D\uDEE1\uFE0F",
    "Sopo\u0107ani": "\uD83D\uDC0E",
    "Pe\u0107ka patrijar\u0161ija": "\uD83D\uDD6F\uFE0F",
    "Nasle\u0111e Srbije": "\u2728",
    "Temelj": "\uD83E\uDDF1",
    "Zidovi": "\uD83E\uDDF1",
    "Kupola": "\u26EA",
    "Freske": "\uD83C\uDFA8",
    "Biblioteka": "\uD83D\uDCD6",
    "Ma\u010D": "\u2694\uFE0F",
    "Knjiga": "\uD83D\uDCD6",
    "Krst": "\u271D\uFE0F",
    "Povelja": "\uD83D\uDCDC",
    "Zlatnik": "\uD83E\uDE99",
    "\u0160tit": "\uD83D\uDEE1\uFE0F",
    "Manastir": "\u26EA",
    "Oreol": "\uD83C\uDF1F",
    "Krilo": "\uD83E\uDEBD",
    "Natpis": "\u270D\uFE0F",
    "Pozadina": "\uD83D\uDDBC\uFE0F",
    "Po\u010Detak": "\uD83D\uDCDC",
    "Dar": "\uD83C\uDF81",
    "Pe\u010Dat": "\uD83D\uDD4C",
    "Zavet": "\u2728",
    "Majstor": "\uD83D\uDEE0\uFE0F",
    "Monah": "\uD83D\uDD6F\uFE0F",
    "Vojnik": "\uD83D\uDEE1\uFE0F",
    "Lekar": "\u2695\uFE0F",
    "Trgovac": "\uD83E\uDE99",
    "Pisar": "\u270D\uFE0F",
    "Hrana": "\uD83C\uDF5E",
    "Stra\u017Ea": "\uD83D\uDEE1\uFE0F",
    "Majstori": "\uD83D\uDEE0\uFE0F",
    "Knjige": "\uD83D\uDCDA",
    "Zlato": "\uD83E\uDE99",
    "Lekovi": "\u2695\uFE0F",
    "Kruna": "\uD83D\uDC51",
    "Freska": "\uD83C\uDFA8",
    "Zadu\u017Ebina": "\u26EA"
};

function iconFor(value) {
    return pmIcons[value] || "\u2726";
}

function selectedCountFor(item, state) {
    if (["guardian", "caravan", "defenders"].includes(item.type)) {
        const pairs = item.pairs || item.events || item.threats || [];
        return pairs.filter(([p, a]) => state.pairs[p] === a).length;
    }
    return state.selected.length;
}

function totalCountFor(item) {
    if (["guardian", "caravan", "defenders"].includes(item.type)) {
        return (item.pairs || item.events || item.threats || []).length;
    }
    return requiredTokenCount(item);
}

function renderGameVisual(item, state) {
    if (item.type === "build") {
        const pieces = ["Temelj", "Zidovi", "Kupola", "Freske", "Biblioteka"];
        return `
            <div class="pm-world pm-world-build">
                <div class="pm-sky"></div>
                <div class="pm-ground"></div>
                <div class="pm-building">
                    <div class="pm-build-piece pm-foundation ${state.selected.includes("Temelj") ? "is-built" : ""}"></div>
                    <div class="pm-build-piece pm-walls ${state.selected.includes("Zidovi") ? "is-built" : ""}"></div>
                    <div class="pm-build-piece pm-dome ${state.selected.includes("Kupola") ? "is-built" : ""}"></div>
                    <div class="pm-build-piece pm-fresco ${state.selected.includes("Freske") ? "is-built" : ""}"></div>
                    <div class="pm-build-piece pm-library ${state.selected.includes("Biblioteka") ? "is-built" : ""}"></div>
                </div>
                <div class="pm-world-label">${pieces.filter(p => state.selected.includes(p)).length}/5 delova zadu\u017Ebine</div>
            </div>
        `;
    }

    if (item.type === "crown") {
        const picked = item.required.filter(symbol => state.selected.includes(symbol));
        return `
            <div class="pm-world pm-world-crown">
                <div class="pm-crown-shape">\uD83D\uDC51</div>
                <div class="pm-crown-slots">
                    ${item.required.map(symbol => `<span class="${state.selected.includes(symbol) ? "is-filled" : ""}">${state.selected.includes(symbol) ? iconFor(symbol) : "?"}</span>`).join("")}
                </div>
                <div class="pm-world-label">${picked.length}/${item.required.length} simbola krune</div>
            </div>
        `;
    }

    if (item.type === "restore") {
        return `
            <div class="pm-world pm-world-fresco">
                ${item.fragments.map(fragment => `<div class="pm-fresco-tile ${state.selected.includes(fragment) ? "is-restored" : ""}">${state.selected.includes(fragment) ? iconFor(fragment) : ""}</div>`).join("")}
                <div class="pm-world-label">Obnova freske</div>
            </div>
        `;
    }

    if (item.type === "charter") {
        return `
            <div class="pm-world pm-world-charter">
                ${item.fragments.map(fragment => `<div class="pm-charter-piece ${state.selected.includes(fragment) ? "is-restored" : ""}">${state.selected.includes(fragment) ? fragment : ""}</div>`).join("")}
                <div class="pm-world-label">Tajna povelja</div>
            </div>
        `;
    }

    if (["guardian", "caravan", "defenders"].includes(item.type)) {
        const pairs = item.pairs || item.events || item.threats || [];
        return `
            <div class="pm-world pm-world-tactics">
                ${pairs.map(([problem, answer]) => `<div class="pm-tactic-lane ${state.pairs[problem] === answer ? "is-solved" : ""}"><span>${state.pairs[problem] === answer ? iconFor(answer) : "!"}</span><strong>${problem}</strong></div>`).join("")}
                <div class="pm-world-label">Takti\u010Dki zadaci</div>
            </div>
        `;
    }

    if (item.type === "seal") {
        return `
            <div class="pm-world pm-world-seal">
                <div class="pm-seal-core">\u2728</div>
                <div class="pm-seal-ring">
                    ${item.symbols.map(symbol => `<span class="${state.selected.includes(symbol) ? "is-filled" : ""}">${state.selected.includes(symbol) ? iconFor(symbol) : ""}</span>`).join("")}
                </div>
                <div class="pm-world-label">Veliki pe\u010Dat puta</div>
            </div>
        `;
    }

    return "";
}

renderMiniGame = function(item, state) {
    const visual = renderGameVisual(item, state);
    if (item.type === "build") return `${visual}${renderTokenMini(item, state, item.pieces, "Izgradi zadu\u017Ebinu", "build")}`;
    if (item.type === "crown") return `${visual}${renderTokenMini(item, state, item.options, "Sastavi krunu Nemanji\u0107a", "crown")}`;
    if (item.type === "restore") return `${visual}${renderTokenMini(item, state, item.fragments, "Obnovi fresku", "restore")}`;
    if (item.type === "charter") return `${visual}${renderTokenMini(item, state, item.fragments, "Sastavi tajnu povelju", "charter")}`;
    if (item.type === "guardian") return `${visual}${renderPairMini(item, state, item.pairs, item.helpers, "Sa\u010Duvaj zadu\u017Ebinu")}`;
    if (item.type === "caravan") return `${visual}${renderPairMini(item, state, item.events, item.supplies, "Vodi karavan kroz Srbiju")}`;
    if (item.type === "defenders") return `${visual}${renderPairMini(item, state, item.threats, item.defenders, "Rasporedi branitelje")}`;
    if (item.type === "seal") return `${visual}${renderTokenMini(item, state, item.symbols, "Sastavi zavr\u0161ni pe\u010Dat", "seal")}`;
    return "";
}

renderTokenMini = function(item, state, tokens, title, action) {
    const current = selectedCountFor(item, state);
    const total = totalCountFor(item);
    return `
        <div class="pm-mini-head pm-mini-head-polished">
            <h3>${iconFor(item.name)} ${title}</h3>
            <p>${state.done ? "Zadatak je zavr\u0161en." : "Klikni predmete, simbole ili delove. Svaki izbor menja scenu iznad."}</p>
        </div>
        <div class="pm-token-grid pm-token-grid-polished">
            ${tokens.map(token => `<button type="button" class="pm-token ${state.selected.includes(token) ? "is-selected" : ""}" data-token="${token}" data-action="${action}"><span>${iconFor(token)}</span><strong>${token}</strong></button>`).join("")}
        </div>
        <div class="pm-progress"><i style="width:${Math.round((current / total) * 100)}%"></i></div>
        <button type="button" class="pv-btn pv-btn-primary pm-complete" data-complete="tokens" ${current >= total || state.done ? "" : "disabled"}>\u2728 Zavr\u0161i mini-igru</button>
    `;
}

renderPairMini = function(item, state, pairs, choices, title) {
    const current = selectedCountFor(item, state);
    const total = totalCountFor(item);
    return `
        <div class="pm-mini-head pm-mini-head-polished">
            <h3>${iconFor(item.name)} ${title}</h3>
            <p>Izaberi problem, pa mu dodeli pravi lik ili resurs. Dobri spojevi se pale zeleno.</p>
        </div>
        <div class="pm-pair-layout pm-pair-layout-polished">
            <div class="pm-problems">
                ${pairs.map(([problem, answer]) => `<button type="button" class="pm-problem ${state.activeProblem === problem ? "is-active" : ""} ${state.pairs[problem] === answer ? "is-done" : ""}" data-problem="${problem}"><span>${state.pairs[problem] === answer ? iconFor(answer) : "\u26A0\uFE0F"}</span><strong>${problem}</strong></button>`).join("")}
            </div>
            <div class="pm-choices">
                ${choices.map(choice => `<button type="button" class="pm-choice" data-choice="${choice}"><span>${iconFor(choice)}</span><strong>${choice}</strong></button>`).join("")}
            </div>
        </div>
        <div class="pm-progress"><i style="width:${Math.round((current / total) * 100)}%"></i></div>
        <button type="button" class="pv-btn pv-btn-primary pm-complete" data-complete="pairs" ${current >= total || state.done ? "" : "disabled"}>\u2728 Zavr\u0161i mini-igru</button>
    `;
}

const originalPolishedRenderMonasteryAdventure = renderMonasteryAdventure;
renderMonasteryAdventure = function() {
    const item = currentMonastery();
    const state = getMiniState();
    byId("escapeRoomLabel").innerText = `\uD83D\uDCCD Lokacija ${monasteryIndex + 1} od ${monasteryJourney.length}`;
    byId("escapeTitle").innerText = "Put kroz manastire Srbije";
    byId("escapeType").innerText = `${iconFor(item.name)} ${item.mode}`;

    const container = document.querySelector(".pv-escape-room");
    if (!container) return;
    const progress = Math.round((selectedCountFor(item, state) / Math.max(1, totalCountFor(item))) * 100);
    container.innerHTML = `
        <section class="pm-game pm-game-polished">
            <aside class="pm-map pm-map-polished">
                <div class="pm-map-title">\uD83D\uDDFA\uFE0F Mapa puta</div>
                <div class="pm-map-list">
                    ${monasteryJourney.map((place, index) => `
                        <button type="button" class="pm-map-node ${index === monasteryIndex ? "is-active" : ""} ${monasteryState[place.id]?.done ? "is-done" : ""}" ${isUnlockedMonastery(index) ? "" : "disabled"} data-index="${index}">
                            <span>${monasteryState[place.id]?.done ? "\u2713" : iconFor(place.name)}</span>
                            <strong>${place.name}</strong>
                        </button>
                    `).join("")}
                </div>
            </aside>
            <main class="pm-board pm-board-polished">
                <div class="pm-hero pm-hero-polished" style="background-image: linear-gradient(180deg, rgba(10,3,4,.10), rgba(10,3,4,.30)), url('/images/game/${item.image}')">
                    <div class="pm-hero-copy">
                        <span>${iconFor(item.name)} ${item.name}</span>
                        <h3>${item.mode}</h3>
                        <p>${item.goal}</p>
                    </div>
                    <div class="pm-xp"><strong>${monasteryScore}</strong><span>XP</span></div>
                </div>
                <div class="pm-play pm-play-polished">
                    ${renderMiniGame(item, state)}
                </div>
            </main>
            <aside class="pm-side pm-side-polished">
                <div class="pm-card pm-card-goal">
                    <span>\uD83C\uDFAF Cilj</span>
                    <p>${item.goal}</p>
                </div>
                <div class="pm-card pm-card-message">
                    <span>\uD83D\uDCE3 Poruka</span>
                    <p id="escapeFeedback">${state.message}</p>
                </div>
                <div class="pm-card pm-card-lesson">
                    <span>\uD83D\uDCD6 Nau\u010Dili smo</span>
                    <p>${state.done ? item.lesson : `Zavr\u0161i zadatak da otklju\u010Da\u0161 obja\u0161njenje. Napredak: ${progress}%`}</p>
                </div>
                <div class="pm-badges">
                    <span>\uD83C\uDFC5 Zna\u010Dke</span>
                    <div>${monasteryBadges.length ? monasteryBadges.map(badge => `<strong>\u2728 ${badge}</strong>`).join("") : "<em>Jo\u0161 nema zna\u010Dki</em>"}</div>
                </div>
                <div class="pm-actions">
                    <button type="button" class="pv-btn pv-btn-secondary" id="hintEscapeBtn">\uD83D\uDCA1 Trag</button>
                    <button type="button" class="pv-btn pv-btn-secondary" id="escapeMenuBtn">\u2302 Meni</button>
                </div>
            </aside>
        </section>
    `;
    bindMonasteryAdventure();
    applyScriptMode(scriptMode);
};

renderEscapeRoom = renderMonasteryAdventure;


/* =========================
   MINI GAME VISIBLE PARTS FIX
   ========================= */

renderTokenMini = function(item, state, tokens, title, action) {
    const current = selectedCountFor(item, state);
    const total = totalCountFor(item);
    return `
        <div class="pm-mini-head pm-mini-head-polished">
            <h3>${iconFor(item.name)} ${title}</h3>
            <p>${state.done ? "Zadatak je zavr\u0161en." : "Klikni velike delove ispod. Svaki klik odmah menja scenu i puni napredak."}</p>
        </div>
        <div class="pm-action-dock" aria-label="Delovi mini-igre">
            ${tokens.map(token => `<button type="button" class="pm-token pm-big-token ${state.selected.includes(token) ? "is-selected" : ""}" data-token="${token}" data-action="${action}"><span>${iconFor(token)}</span><strong>${token}</strong><small>${state.selected.includes(token) ? "Dodato" : "Klikni"}</small></button>`).join("")}
        </div>
        <div class="pm-progress pm-progress-big"><i style="width:${Math.round((current / total) * 100)}%"></i><span>${current}/${total}</span></div>
        <button type="button" class="pv-btn pv-btn-primary pm-complete" data-complete="tokens" ${current >= total || state.done ? "" : "disabled"}>\u2728 Zavr\u0161i mini-igru</button>
    `;
}

renderPairMini = function(item, state, pairs, choices, title) {
    const current = selectedCountFor(item, state);
    const total = totalCountFor(item);
    return `
        <div class="pm-mini-head pm-mini-head-polished">
            <h3>${iconFor(item.name)} ${title}</h3>
            <p>Prvo klikni problem levo, zatim klikni pravog pomaga\u010Da desno. Ta\u010Dni spojevi se pale zeleno.</p>
        </div>
        <div class="pm-pair-layout pm-pair-layout-polished pm-pair-visible">
            <div class="pm-problems">
                ${pairs.map(([problem, answer]) => `<button type="button" class="pm-problem ${state.activeProblem === problem ? "is-active" : ""} ${state.pairs[problem] === answer ? "is-done" : ""}" data-problem="${problem}"><span>${state.pairs[problem] === answer ? iconFor(answer) : "\u26A0\uFE0F"}</span><strong>${problem}</strong><small>${state.pairs[problem] === answer ? answer : "Izaberi"}</small></button>`).join("")}
            </div>
            <div class="pm-choices">
                ${choices.map(choice => `<button type="button" class="pm-choice" data-choice="${choice}"><span>${iconFor(choice)}</span><strong>${choice}</strong></button>`).join("")}
            </div>
        </div>
        <div class="pm-progress pm-progress-big"><i style="width:${Math.round((current / total) * 100)}%"></i><span>${current}/${total}</span></div>
        <button type="button" class="pv-btn pv-btn-primary pm-complete" data-complete="pairs" ${current >= total || state.done ? "" : "disabled"}>\u2728 Zavr\u0161i mini-igru</button>
    `;
}

renderEscapeRoom = renderMonasteryAdventure;


/* =========================
   REAL GAME OBJECTS IN SCENE
   ========================= */

function sceneTokenButton(token, state, action, extraClass = "") {
    const selected = state.selected?.includes(token);
    return `<button type="button" class="pm-token pm-scene-object ${extraClass} ${selected ? "is-selected" : ""}" data-token="${token}" data-action="${action}" title="Klikni: ${token}">
        <span>${iconFor(token)}</span>
        <strong>${token}</strong>
    </button>`;
}

function renderSceneTokenWorld(item, state, title, action, tokens, className) {
    const current = selectedCountFor(item, state);
    const total = totalCountFor(item);
    return `
        <div class="pm-world pm-world-real ${className}">
            <div class="pm-world-task">
                <strong>${title}</strong>
                <span>${current}/${total}</span>
            </div>
            <div class="pm-object-stage">
                ${tokens.map((token, index) => sceneTokenButton(token, state, action, `pm-object-${index + 1}`)).join("")}
            </div>
            <div class="pm-world-label">Klikni predmete u sceni. Izabrani delovi se odmah zaključavaju.</div>
        </div>
    `;
}

renderGameVisual = function(item, state) {
    if (item.type === "build") {
        const selected = state.selected || [];
        return `
            <div class="pm-world pm-world-real pm-build-real">
                <div class="pm-world-task"><strong>Radilište zadužbine</strong><span>${selected.length}/${item.pieces.length}</span></div>
                <div class="pm-build-stage-real">
                    <div class="pm-ground-real"></div>
                    <button type="button" class="pm-token pm-build-object pm-build-foundation ${selected.includes("Temelj") ? "is-selected" : ""}" data-token="Temelj" data-action="build"><span>🧱</span><strong>Temelj</strong></button>
                    <button type="button" class="pm-token pm-build-object pm-build-walls ${selected.includes("Zidovi") ? "is-selected" : ""}" data-token="Zidovi" data-action="build"><span>🏛️</span><strong>Zidovi</strong></button>
                    <button type="button" class="pm-token pm-build-object pm-build-dome ${selected.includes("Kupola") ? "is-selected" : ""}" data-token="Kupola" data-action="build"><span>⛪</span><strong>Kupola</strong></button>
                    <button type="button" class="pm-token pm-build-object pm-build-fresco ${selected.includes("Freske") ? "is-selected" : ""}" data-token="Freske" data-action="build"><span>🎨</span><strong>Freske</strong></button>
                    <button type="button" class="pm-token pm-build-object pm-build-library ${selected.includes("Biblioteka") ? "is-selected" : ""}" data-token="Biblioteka" data-action="build"><span>📚</span><strong>Biblioteka</strong></button>
                    <div class="pm-monastery-preview ${selected.length === item.pieces.length ? "is-complete" : ""}">
                        <i class="base ${selected.includes("Temelj") ? "on" : ""}"></i>
                        <i class="walls ${selected.includes("Zidovi") ? "on" : ""}"></i>
                        <i class="dome ${selected.includes("Kupola") ? "on" : ""}"></i>
                        <i class="fresco ${selected.includes("Freske") ? "on" : ""}"></i>
                        <i class="library ${selected.includes("Biblioteka") ? "on" : ""}"></i>
                    </div>
                </div>
                <div class="pm-world-label">Izgradi manastir deo po deo. Klik na predmet ga ugrađuje u građevinu.</div>
            </div>
        `;
    }

    if (item.type === "crown") {
        return renderSceneTokenWorld(item, state, "Izaberi prave simbole krune", "crown", item.options, "pm-crown-real");
    }

    if (item.type === "restore") {
        return renderSceneTokenWorld(item, state, "Vrati delove freske", "restore", item.fragments, "pm-fresco-real");
    }

    if (item.type === "charter") {
        return renderSceneTokenWorld(item, state, "Sastavi povelju po delovima", "charter", item.fragments, "pm-charter-real");
    }

    if (item.type === "seal") {
        return renderSceneTokenWorld(item, state, "Sakupi simbole velikog pečata", "seal", item.symbols, "pm-seal-real");
    }

    const pairs = item.pairs || item.events || item.threats || [];
    if (pairs.length) {
        return `
            <div class="pm-world pm-world-real pm-tactics-real">
                <div class="pm-world-task"><strong>Takmičarska tabla zadataka</strong><span>${selectedCountFor(item, state)}/${totalCountFor(item)}</span></div>
                <div class="pm-tactics-stage-real">
                    ${pairs.map(([problem, answer]) => `<button type="button" class="pm-problem pm-scene-problem ${state.activeProblem === problem ? "is-active" : ""} ${state.pairs?.[problem] === answer ? "is-done" : ""}" data-problem="${problem}">
                        <span>${state.pairs?.[problem] === answer ? iconFor(answer) : "⚠️"}</span>
                        <strong>${problem}</strong>
                    </button>`).join("")}
                </div>
                <div class="pm-world-label">Klikni opasnost na tabli, pa izaberi pravog pomagača ispod.</div>
            </div>
        `;
    }

    return "";
};

renderEscapeRoom = renderMonasteryAdventure;

/* =========================
   IMMERSIVE MONASTERY GAME STAGE
   ========================= */

(function setupImmersiveMonasteryGame() {
    const extraPlaces = [
        {
            id: "ravanica",
            name: "Ravanica",
            mode: "Manastirska radionica",
            goal: "Izradi predmete iz radionice i otkrij kako su manastiri čuvali znanje, svetlost i zapis.",
            image: "manastirska-biblioteka.jpg",
            badge: "Majstor radionice",
            type: "restore",
            fragments: ["Ikona", "Sveća", "Pečat", "Inicijal", "Svitak"],
            lesson: "Manastirske radionice čuvale su knjige, ikone, pečate i veštinu ručnog rada."
        },
        {
            id: "manasija",
            name: "Manasija",
            mode: "Branitelji zadužbine",
            goal: "Rasporedi ljude na opasnosti i sačuvaj zadužbinu u ograničenom broju poteza.",
            image: "dvorana-nasledja.jpg",
            badge: "Branitelj nasleđa",
            type: "defenders",
            threats: [
                ["Požar u konaku", "Majstor"],
                ["Bolest u selu", "Lekar"],
                ["Pretnja na putu", "Vojnik"],
                ["Nestale knjige", "Pisar"],
                ["Prazna ostava", "Trgovac"]
            ],
            defenders: ["Majstor", "Lekar", "Vojnik", "Pisar", "Trgovac", "Monah"],
            lesson: "Zadužbina se ne čuva samo zidovima, već ljudima koji znaju šta treba uraditi u krizi."
        }
    ];

    extraPlaces.forEach(place => {
        if (!monasteryJourney.some(existing => existing.id === place.id)) monasteryJourney.splice(Math.max(0, monasteryJourney.length - 1), 0, place);
    });

    const miniGameUi = {
        build: {
            verb: "Postavi deo",
            scene: "Radilište zadužbine",
            cue: "Klikni deo u sceni ili iz inventara. Deo se ugrađuje u manastir."
        },
        crown: {
            verb: "Ugradi simbol",
            scene: "Dvorana krunisanja",
            cue: "Izaberi simbole koji pripadaju kruni: mač, knjiga, krst i povelja."
        },
        restore: {
            verb: "Obnovi detalj",
            scene: "Radionica fresaka",
            cue: "Klikni fragmente da obnoviš sliku, zapis ili predmet."
        },
        charter: {
            verb: "Sastavi povelju",
            scene: "Skriptorijum",
            cue: "Sakupi delove povelje da bi se dokument zaključao pečatom."
        },
        guardian: {
            verb: "Dodeli pomagača",
            scene: "Dvorište manastira",
            cue: "Klikni problem u sceni, pa iz inventara izaberi pravog pomagača."
        },
        caravan: {
            verb: "Pošalji zalihu",
            scene: "Manastirski put",
            cue: "Klikni događaj na putu, pa izaberi zalihu koja ga rešava."
        },
        defenders: {
            verb: "Rasporedi branioca",
            scene: "Odbrana zadužbine",
            cue: "Klikni opasnost i dodeli osobu koja najbolje rešava taj problem."
        },
        seal: {
            verb: "Utisni simbol",
            scene: "Dvorana nasleđa",
            cue: "Sakupi sve simbole i zatvori put velikim pečatom."
        }
    };

    function tokensForItem(item) {
        return item.pieces || item.options || item.fragments || item.symbols || [];
    }

    function pairsForItem(item) {
        return item.pairs || item.events || item.threats || [];
    }

    function choicesForItem(item) {
        return item.helpers || item.supplies || item.defenders || [];
    }

    function itemClass(token) {
        return String(token).toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
            .replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
    }

    function renderStageObjects(item, state) {
        const pairs = pairsForItem(item);
        if (pairs.length) {
            return pairs.map(([problem, answer], index) => `
                <button type="button" class="pm-ig-object pm-ig-problem pm-pos-${index + 1} ${state.activeProblem === problem ? "is-active" : ""} ${state.pairs?.[problem] === answer ? "is-done" : ""}" data-problem="${problem}">
                    <span>${state.pairs?.[problem] === answer ? iconFor(answer) : "⚠️"}</span>
                    <strong>${problem}</strong>
                    <small>${state.pairs?.[problem] === answer ? answer : "izaberi"}</small>
                </button>
            `).join("");
        }

        return tokensForItem(item).map((token, index) => `
            <button type="button" class="pm-token pm-ig-object pm-ig-token pm-pos-${index + 1} pm-token-${itemClass(token)} ${state.selected?.includes(token) ? "is-selected" : ""}" data-token="${token}" data-action="${item.type}">
                <span>${iconFor(token)}</span>
                <strong>${token}</strong>
                <small>${state.selected?.includes(token) ? "postavljeno" : "klikni"}</small>
            </button>
        `).join("");
    }

    function renderBuildPreview(item, state) {
        if (item.type !== "build") return "";
        const selected = state.selected || [];
        return `
            <div class="pm-ig-monastery ${selected.length === item.pieces.length ? "is-complete" : ""}">
                <i class="base ${selected.includes("Temelj") ? "on" : ""}"></i>
                <i class="walls ${selected.includes("Zidovi") ? "on" : ""}"></i>
                <i class="dome ${selected.includes("Kupola") ? "on" : ""}"></i>
                <i class="fresco ${selected.includes("Freske") ? "on" : ""}"></i>
                <i class="library ${selected.includes("Biblioteka") ? "on" : ""}"></i>
            </div>
        `;
    }

    function renderSymbolPreview(item, state) {
        if (["build", "guardian", "caravan", "defenders"].includes(item.type)) return "";
        const tokens = tokensForItem(item);
        return `
            <div class="pm-ig-symbol-board ${item.type}">
                ${tokens.slice(0, 7).map(token => `<span class="${state.selected?.includes(token) ? "on" : ""}">${state.selected?.includes(token) ? iconFor(token) : ""}</span>`).join("")}
            </div>
        `;
    }

    function renderInventory(item, state) {
        const pairs = pairsForItem(item);
        if (pairs.length) {
            return choicesForItem(item).map(choice => `
                <button type="button" class="pm-ig-inventory-item pm-choice" data-choice="${choice}">
                    <span>${iconFor(choice)}</span>
                    <strong>${choice}</strong>
                </button>
            `).join("");
        }

        return tokensForItem(item).map(token => `
            <button type="button" class="pm-ig-inventory-item pm-token ${state.selected?.includes(token) ? "is-selected" : ""}" data-token="${token}" data-action="${item.type}">
                <span>${iconFor(token)}</span>
                <strong>${token}</strong>
            </button>
        `).join("");
    }

    function renderLessonOverlay(item, state) {
        if (!state.done) return "";
        return `
            <div class="pm-ig-learn-card">
                <span>📖 Naučili smo</span>
                <p>${item.lesson}</p>
                <button type="button" class="pv-btn pv-btn-primary pm-complete" data-complete="done">Nastavi dalje</button>
            </div>
        `;
    }

    renderMonasteryAdventure = function() {
        const item = currentMonastery();
        const state = getMiniState();
        const ui = miniGameUi[item.type] || miniGameUi.build;
        const current = selectedCountFor(item, state);
        const total = Math.max(1, totalCountFor(item));
        const progress = Math.round((current / total) * 100);

        byId("escapeRoomLabel").innerText = `📍 Lokacija ${monasteryIndex + 1} od ${monasteryJourney.length}`;
        byId("escapeTitle").innerText = item.name;
        byId("escapeType").innerText = item.mode;

        const container = document.querySelector(".pv-escape-room");
        if (!container) return;

        container.innerHTML = `
            <section class="pm-ig-shell" style="--pm-scene:url('/images/game/${item.image}')">
                <div class="pm-ig-bg"></div>

                <header class="pm-ig-hud pm-ig-hud-top">
                    <div class="pm-ig-titlebox">
                        <span>Lokacija ${monasteryIndex + 1}/${monasteryJourney.length}</span>
                        <h2>${iconFor(item.name)} ${item.name}</h2>
                        <p>${item.mode}</p>
                    </div>
                    <div class="pm-ig-stats">
                        <strong>${monasteryScore}</strong><span>XP</span>
                    </div>
                    <div class="pm-ig-stats">
                        <strong>${current}/${total}</strong><span>Napredak</span>
                    </div>
                </header>

                <nav class="pm-ig-map" aria-label="Mapa puta">
                    ${monasteryJourney.map((place, index) => `
                        <button type="button" class="pm-ig-node ${index === monasteryIndex ? "is-active" : ""} ${monasteryState[place.id]?.done ? "is-done" : ""}" ${isUnlockedMonastery(index) ? "" : "disabled"} data-index="${index}" title="${place.name}">
                            <span>${monasteryState[place.id]?.done ? "✓" : iconFor(place.name)}</span>
                            <strong>${index + 1}</strong>
                        </button>
                    `).join("")}
                </nav>

                <main class="pm-ig-scene pm-ig-scene-${item.type}">
                    <div class="pm-ig-scene-label">
                        <span>${ui.scene}</span>
                        <strong>${ui.verb}</strong>
                    </div>
                    <div class="pm-ig-progress"><i style="width:${progress}%"></i></div>
                    ${renderBuildPreview(item, state)}
                    ${renderSymbolPreview(item, state)}
                    ${renderStageObjects(item, state)}
                    <div class="pm-ig-toast" id="escapeFeedback">${state.message || ui.cue}</div>
                    ${renderLessonOverlay(item, state)}
                </main>

                <aside class="pm-ig-quest">
                    <span>🎯 Cilj</span>
                    <p>${item.goal}</p>
                    <small>${ui.cue}</small>
                </aside>

                <aside class="pm-ig-badges">
                    <span>🏅 Značke</span>
                    <p>${monasteryBadges.length ? monasteryBadges.map(badge => `✨ ${badge}`).join(" · ") : "Još nema osvojenih znački"}</p>
                </aside>

                <footer class="pm-ig-belt">
                    <button type="button" class="pm-ig-small-btn" id="hintEscapeBtn">💡 Trag</button>
                    <div class="pm-ig-inventory" aria-label="Inventar">
                        ${renderInventory(item, state)}
                    </div>
                    <button type="button" class="pm-ig-small-btn" id="escapeMenuBtn">⌂ Meni</button>
                    <button type="button" class="pm-ig-finish pm-complete" data-complete="tokens" ${current >= total || state.done ? "" : "disabled"}>✨ Završi</button>
                </footer>
            </section>
        `;

        bindMonasteryAdventure();
        applyScriptMode(scriptMode);
    };

    renderEscapeRoom = renderMonasteryAdventure;
})();

/* =========================
   MINI GAME RESULT STEP
   ========================= */

finishCurrentMiniGame = function() {
    const item = currentMonastery();
    const state = getMiniState();

    if (state.done) {
        if (monasteryIndex >= monasteryJourney.length - 1) {
            finishMonasteryJourney();
            return;
        }
        monasteryIndex += 1;
        renderMonasteryAdventure();
        return;
    }

    state.done = true;
    state.message = item.lesson;
    if (!monasteryBadges.includes(item.badge)) {
        monasteryBadges.push(item.badge);
        monasteryScore += 10;
        toast(`+10 XP · ${item.badge}`, "plus");
    }
    renderMonasteryAdventure();
};

/* =========================
   STORY DRIVEN MINI GAMES
   ========================= */

const pvMiniMissions = {
    build: [
        { token: "Temelj", story: "Majstori mere zemljište i postavljaju temelj. Zadužbina dobija svoje prvo uporište." },
        { token: "Zidovi", story: "Kamen po kamen, zidovi zatvaraju sveti prostor i štite buduću crkvu." },
        { token: "Kupola", story: "Kupola se podiže iznad naosa. Manastir sada dobija prepoznatljiv oblik." },
        { token: "Freske", story: "Slikari unose boju i priču u zidove. Vernici će učiti gledajući freske." },
        { token: "Biblioteka", story: "Prepisivači donose knjige. Zadužbina postaje mesto znanja, ne samo molitve." }
    ],
    crown: [
        { token: "Mač", story: "Mač označava zaštitu države, ali sam ne sme voditi vladara." },
        { token: "Knjiga", story: "Knjiga dodaje znanje. Vladar mora razumeti zakone i narod." },
        { token: "Krst", story: "Krst podseća da vlast ima duhovnu odgovornost." },
        { token: "Povelja", story: "Povelja zaključava odluku u zapis. Kruna je spremna." }
    ],
    restore: [
        { token: "Oreol", story: "Obnovljen je oreol. Lik na fresci ponovo dobija svetlost." },
        { token: "Knjiga", story: "Knjiga se vraća u ruke svetitelja. Poruka slike postaje jasnija." },
        { token: "Krilo", story: "Krilo Belog anđela ponovo se vidi. Freska dobija pokret." },
        { token: "Natpis", story: "Natpis otkriva ime i smisao prikaza." },
        { token: "Pozadina", story: "Poslednji sloj vraća dubinu sceni. Freska je obnovljena." },
        { token: "Ikona", story: "Ikona je očišćena i vraćena na počasno mesto." },
        { token: "Sveća", story: "Svetlost sveće otkriva detalje radionice." },
        { token: "Pečat", story: "Pečat potvrđuje rad radionice." },
        { token: "Inicijal", story: "Ukrašeno početno slovo pretvara zapis u umetnost." },
        { token: "Svitak", story: "Svitak čuva poruku za sledeće generacije." }
    ],
    charter: [
        { token: "Početak", story: "Pisar nalazi uvodne reči povelje." },
        { token: "Dar", story: "U dokument se vraća zapis o daru manastiru." },
        { token: "Pečat", story: "Vladarski pečat potvrđuje da povelja ima snagu." },
        { token: "Zavet", story: "Zavet zatvara povelju. Dokument je sačuvan." }
    ],
    seal: [
        { token: "Kruna", story: "Kruna označava odgovornost vlasti." },
        { token: "Knjiga", story: "Knjiga dodaje znanje kao temelj nasleđa." },
        { token: "Freska", story: "Freska čuva sliku vremena." },
        { token: "Povelja", story: "Povelja čuva pisani dokaz." },
        { token: "Zadužbina", story: "Zadužbina povezuje veru, narod i državu." },
        { token: "Štit", story: "Štit zatvara pečat zaštitom nasleđa." }
    ]
};

const pvPairStories = {
    "Oštećen zid": "Majstor popravlja zid i sprečava da se oštećenje proširi.",
    "Knjige u opasnosti": "Pisar premešta knjige i spasava rukopise.",
    "Bolest u selu": "Lekar organizuje pomoć i brigu za bolesne.",
    "Nemir na putu": "Vojnik obezbeđuje prolaz i vraća mir.",
    "Pokvaren most": "Majstori učvršćuju most da karavan može dalje.",
    "Gladno selo": "Hrana stiže do sela i put se nastavlja bez pobune.",
    "Opasan prolaz": "Straža prati karavan kroz nesiguran kraj.",
    "Manastirska biblioteka": "Knjige se predaju biblioteci i znanje putuje dalje.",
    "Požar": "Majstor zatvara žarište i spasava krov.",
    "Nestašica hrane": "Trgovac pronalazi zalihe za manastir i selo.",
    "Napad razbojnika": "Vojnik odbija opasnost bez širenja panike.",
    "Oštećena knjiga": "Pisar prepisuje oštećene stranice.",
    "Požar u konaku": "Majstor zaustavlja vatru pre nego što zahvati konak.",
    "Pretnja na putu": "Vojnik prati putnike do bezbednog mesta.",
    "Nestale knjige": "Pisar proverava zapise i vraća knjige u riznicu.",
    "Prazna ostava": "Trgovac obnavlja zalihe i manastir može da pomogne narodu."
};

function pvMissionFor(item) {
    const source = pvMiniMissions[item.type] || [];
    const allowed = item.required || item.pieces || item.fragments || item.symbols || [];
    if (!allowed.length) return source;
    return source.filter(step => allowed.includes(step.token));
}

function pvNextStep(item, state) {
    const mission = pvMissionFor(item);
    return mission.find(step => !(state.selected || []).includes(step.token));
}

function pvEnsureStoryState(state) {
    if (!state.log) state.log = [];
    if (typeof state.stepXp !== "number") state.stepXp = 0;
}

handleTokenClick = function(token) {
    const item = currentMonastery();
    const state = getMiniState();
    pvEnsureStoryState(state);
    if (state.done) return;

    const next = pvNextStep(item, state);
    const allowed = item.required || item.pieces || item.fragments || item.symbols || [];

    if (allowed.length && !allowed.includes(token)) {
        state.message = `${token} nije deo ovog zadatka. Potraži sledeći pravi predmet.`;
        state.wrongToken = token;
        renderMonasteryAdventure();
        return;
    }

    if (next && token !== next.token) {
        state.message = `Radnja ima redosled: prvo treba ${next.token}.`;
        state.wrongToken = token;
        renderMonasteryAdventure();
        return;
    }

    if (!state.selected.includes(token)) {
        state.selected.push(token);
        state.lastAction = token;
        state.wrongToken = null;
        state.message = next?.story || `Dodato: ${token}.`;
        state.log.unshift(state.message);
        state.log = state.log.slice(0, 3);
        state.stepXp += 2;
        monasteryScore += 2;
        toast(`+2 XP · ${token}`, "plus");
    }

    renderMonasteryAdventure();
};

handlePairChoice = function(choice) {
    const item = currentMonastery();
    const state = getMiniState();
    pvEnsureStoryState(state);
    if (state.done) return;

    if (!state.activeProblem) {
        state.message = "Prvo klikni problem u sceni, pa onda izaberi pomagača iz inventara.";
        renderMonasteryAdventure();
        return;
    }

    const expected = (item.pairs || item.events || item.threats).find(([problem]) => problem === state.activeProblem)?.[1];
    if (choice !== expected) {
        state.message = `${choice} ne rešava problem "${state.activeProblem}". Pokušaj logičnije rešenje.`;
        state.wrongToken = choice;
        renderMonasteryAdventure();
        return;
    }

    state.pairs[state.activeProblem] = choice;
    state.lastAction = state.activeProblem;
    state.wrongToken = null;
    state.message = pvPairStories[state.activeProblem] || `Tačno: ${choice} rešava problem.`;
    state.log.unshift(state.message);
    state.log = state.log.slice(0, 3);
    state.stepXp += 2;
    monasteryScore += 2;
    state.activeProblem = null;
    toast(`+2 XP · ${choice}`, "plus");
    renderMonasteryAdventure();
};

const pvPreviousRenderMonasteryAdventure = renderMonasteryAdventure;
renderMonasteryAdventure = function() {
    pvPreviousRenderMonasteryAdventure();
    const item = currentMonastery();
    const state = getMiniState();
    pvEnsureStoryState(state);
    const next = pvNextStep(item, state);
    const scene = document.querySelector(".pm-ig-scene");
    if (!scene) return;

    scene.classList.toggle("has-action", Boolean(state.lastAction));
    scene.dataset.next = next?.token || "Završi";

    const existing = scene.querySelector(".pm-ig-storyline");
    existing?.remove();

    const story = document.createElement("div");
    story.className = "pm-ig-storyline";
    story.innerHTML = `
        <div class="pm-ig-hero-token ${state.lastAction ? "is-moving" : ""}">
            <span>${state.lastAction ? iconFor(state.lastAction) : "🧭"}</span>
        </div>
        <div class="pm-ig-story-copy">
            <span>Radnja</span>
            <strong>${next ? `Sledeći korak: ${next.token}` : "Nivo je spreman za završetak"}</strong>
            <p>${state.message || next?.story || "Prati redosled i gradi priču korak po korak."}</p>
        </div>
        <div class="pm-ig-log">
            ${(state.log.length ? state.log : ["Počni misiju klikom na prvi aktivni predmet."]).map(text => `<small>${text}</small>`).join("")}
        </div>
    `;
    scene.appendChild(story);

    document.querySelectorAll(".pm-ig-token, .pm-ig-inventory-item.pm-token").forEach(button => {
        const token = button.dataset.token;
        button.classList.toggle("is-next", Boolean(next && token === next.token));
        button.classList.toggle("is-wrong", Boolean(state.wrongToken && token === state.wrongToken));
    });
};

renderEscapeRoom = renderMonasteryAdventure;

/* =========================
   TAJNA POVELJA - PECINA ISPOSNIKA
   Clean prototype game
   ========================= */

let tpState = null;

const TP_TORCH_ORDER = ["north", "east", "south", "west"];
const TP_TORCH_LABELS = {
    north: "Severna baklja",
    east: "Istočna baklja",
    south: "Južna baklja",
    west: "Zapadna baklja"
};

function resetTajnaPoveljaState() {
    tpState = {
        started: false,
        litTorches: [],
        inventory: [],
        message: "Isposnikova pećina je mračna. Pronađi redosled baklji i otvori tajni prolaz.",
        log: ["Ušao/la si u pećinu. Na zidu se jedva vidi uklesan trag."],
        inscriptionRevealed: false,
        parchmentFound: false,
        stoneMoved: false,
        finished: false,
        wrongTorch: null,
        lastEvent: null
    };
}

function tpAddLog(text) {
    tpState.log.unshift(text);
    tpState.log = tpState.log.slice(0, 4);
}

function tpHasItem(item) {
    return tpState.inventory.includes(item);
}

function tpAddItem(item) {
    if (!tpHasItem(item)) tpState.inventory.push(item);
}

function tpProgress() {
    let score = 0;
    score += tpState.litTorches.length;
    if (tpState.inscriptionRevealed) score += 1;
    if (tpState.parchmentFound) score += 1;
    if (tpState.stoneMoved) score += 1;
    return Math.min(100, Math.round((score / 7) * 100));
}

function tpSetScreenLabels() {
    byId("escapeRoomLabel").innerText = "Nivo 1 - Pećina";
    byId("escapeTitle").innerText = "Tajna povelja";
    const timer = byId("escapeTimer");
    if (timer) timer.innerText = "Pećina isposnika";
}

function startTajnaPovelja() {
    closePauseModal?.(true);
    closeHelpModal?.(true);
    stopEscapeTimer?.();
    stopMainTimer?.();
    resetTajnaPoveljaState();
    setActiveScreen("escapeScreen");
    renderTajnaPovelja();
    applyScriptMode?.(scriptMode);
}

function renderTajnaPovelja() {
    if (!tpState) resetTajnaPoveljaState();
    tpSetScreenLabels();

    const container = document.querySelector(".pv-escape-room");
    if (!container) return;

    const activeStep = tpState.litTorches.length;
    const nextTorch = TP_TORCH_ORDER[activeStep];
    const progress = tpProgress();

    container.innerHTML = `
        <section class="tp-game ${tpState.started ? "is-started" : ""} ${tpState.inscriptionRevealed ? "has-inscription" : ""} ${tpState.stoneMoved ? "has-open-passage" : ""}" aria-label="Tajna povelja - Pećina isposnika">
            <div class="tp-cave-bg"></div>
            <div class="tp-darkness"></div>
            <div class="tp-light-level" style="--light:${Math.max(18, 22 + tpState.litTorches.length * 16)}%"></div>

            <header class="tp-hud">
                <div class="tp-title">
                    <span>Avantura sa zagonetkama</span>
                    <h2>Pećina isposnika</h2>
                    <p>Upali baklje, pročitaj natpis i pomeri kamen koji skriva prolaz.</p>
                </div>
                <div class="tp-progress-ring"><strong>${progress}%</strong><span>istraženo</span></div>
                <button type="button" class="tp-icon-btn" id="tpMenuBtn">Meni</button>
            </header>

            <main class="tp-stage">
                <button type="button" class="tp-hotspot tp-clue-stone" data-action="clue" title="Uklesan trag">
                    <span>✦</span><strong>Uklesan trag</strong>
                </button>

                ${TP_TORCH_ORDER.map((id, index) => `
                    <button type="button" class="tp-hotspot tp-torch tp-torch-${id} ${tpState.litTorches.includes(id) ? "is-lit" : ""} ${tpState.wrongTorch === id ? "is-wrong" : ""} ${nextTorch === id ? "is-next" : ""}" data-torch="${id}" title="${TP_TORCH_LABELS[id]}">
                        <i></i><span>${index + 1}</span><strong>${TP_TORCH_LABELS[id]}</strong>
                    </button>
                `).join("")}

                <button type="button" class="tp-hotspot tp-inscription ${tpState.inscriptionRevealed ? "is-visible" : ""}" data-action="inscription" title="Skriveni natpis">
                    <span>᛭</span><strong>Natpis</strong><small>Pročitaj</small>
                </button>

                <button type="button" class="tp-hotspot tp-parchment ${tpState.parchmentFound ? "is-collected" : ""}" data-action="parchment" title="Deo povelje">
                    <span>📜</span><strong>Deo povelje</strong>
                </button>

                <button type="button" class="tp-hotspot tp-stone ${tpState.stoneMoved ? "is-moved" : ""}" data-action="stone" title="Veliki kamen">
                    <span>⬣</span><strong>Veliki kamen</strong><small>${tpState.stoneMoved ? "Pomerен" : "Pomeri"}</small>
                </button>

                <button type="button" class="tp-hotspot tp-passage ${tpState.stoneMoved ? "is-open" : ""}" data-action="passage" title="Tajni prolaz">
                    <span>🚪</span><strong>Tajni prolaz</strong>
                </button>

                <div class="tp-message ${tpState.lastEvent ? "is-new" : ""}">
                    <span>Radnja</span>
                    <p>${tpState.message}</p>
                </div>

                ${tpState.finished ? `
                    <div class="tp-win-card">
                        <span>🏁 Nivo pređen</span>
                        <h3>Prvi deo povelje je pronađen</h3>
                        <p>Pećina je otvorila put ka sledećoj lokaciji. U punoj verziji ovde sledi varošica ili kovačnica.</p>
                        <button type="button" class="pv-btn pv-btn-primary" id="tpRestartBtn">Igraj ponovo</button>
                    </div>
                ` : ""}
            </main>

            <aside class="tp-quest">
                <span>Cilj nivoa</span>
                <ol>
                    <li class="${tpState.litTorches.length === 4 ? "done" : ""}">Upali 4 baklje pravilnim redosledom.</li>
                    <li class="${tpState.inscriptionRevealed ? "done" : ""}">Pročitaj skriveni natpis.</li>
                    <li class="${tpState.parchmentFound ? "done" : ""}">Uzmi prvi deo povelje.</li>
                    <li class="${tpState.stoneMoved ? "done" : ""}">Pomeri kamen i otvori prolaz.</li>
                </ol>
            </aside>

            <aside class="tp-journal">
                <span>Dnevnik</span>
                ${tpState.log.map(line => `<p>${line}</p>`).join("")}
            </aside>

            <footer class="tp-inventory">
                <button type="button" class="tp-tool" id="tpHintBtn">💡 Trag</button>
                <div class="tp-items">
                    ${tpState.inventory.length ? tpState.inventory.map(item => `<button type="button" class="tp-item">${item === "parchment" ? "📜 Deo povelje" : item}</button>`).join("") : `<em>Inventar je prazan</em>`}
                </div>
                <button type="button" class="tp-tool" id="tpResetBtn">↻ Ponovo</button>
            </footer>

            ${!tpState.started ? `
                <div class="tp-intro">
                    <div class="tp-intro-card">
                        <span>Tajna povelja - nivo 1</span>
                        <h2>Pećina isposnika</h2>
                        <p>U staroj pećini krije se prvi deo povelje. Trag kaže: prvo pogledaj kamen sa zvezdom, zatim prati strane sveta.</p>
                        <button type="button" class="pv-btn pv-btn-primary" id="tpStartBtn">Započni istraživanje</button>
                    </div>
                </div>
            ` : ""}
        </section>
    `;

    bindTajnaPovelja();
    applyScriptMode?.(scriptMode);
}

function bindTajnaPovelja() {
    byId("tpStartBtn")?.addEventListener("click", () => {
        tpState.started = true;
        tpState.message = "Na kamenu se vidi redosled: sever, istok, jug, zapad. Upali baklje tim redom.";
        tpAddLog("Istraživanje je počelo. Uklesan trag pominje strane sveta.");
        renderTajnaPovelja();
    });

    document.querySelectorAll(".tp-torch").forEach(torch => {
        torch.addEventListener("click", () => handleTPTorch(torch.dataset.torch));
    });

    document.querySelectorAll("[data-action]").forEach(node => {
        node.addEventListener("click", () => handleTPAction(node.dataset.action));
    });

    byId("tpHintBtn")?.addEventListener("click", showTPHint);
    byId("tpResetBtn")?.addEventListener("click", () => { resetTajnaPoveljaState(); renderTajnaPovelja(); });
    byId("tpRestartBtn")?.addEventListener("click", () => { resetTajnaPoveljaState(); tpState.started = true; renderTajnaPovelja(); });
    byId("tpMenuBtn")?.addEventListener("click", backToMenu);
}

function handleTPTorch(torchId) {
    if (!tpState.started || tpState.finished) return;
    tpState.lastEvent = null;

    if (tpState.litTorches.includes(torchId)) {
        tpState.message = `${TP_TORCH_LABELS[torchId]} je već upaljena.`;
        renderTajnaPovelja();
        return;
    }

    const expected = TP_TORCH_ORDER[tpState.litTorches.length];
    if (torchId !== expected) {
        tpState.wrongTorch = torchId;
        tpState.litTorches = [];
        tpState.message = `Pogrešan redosled. Pećina se ponovo zamračila. Kreni od severne baklje.`;
        tpAddLog(`Pogrešna baklja: ${TP_TORCH_LABELS[torchId]}. Mehanizam se resetovao.`);
        setTimeout(() => { if (tpState) { tpState.wrongTorch = null; renderTajnaPovelja(); } }, 450);
        renderTajnaPovelja();
        return;
    }

    tpState.litTorches.push(torchId);
    tpState.lastEvent = "torch";
    tpState.message = `${TP_TORCH_LABELS[torchId]} se upalila. Svetlost u pećini je jača.`;
    tpAddLog(`Upaljena je ${TP_TORCH_LABELS[torchId].toLowerCase()}.`);

    if (tpState.litTorches.length === 4) {
        tpState.inscriptionRevealed = true;
        tpState.message = "Sve baklje gore. Na zidu se pojavio skriveni natpis.";
        tpAddLog("Skriveni natpis se pojavio iznad kamenog prolaza.");
    }

    renderTajnaPovelja();
}

function handleTPAction(action) {
    if (!tpState.started || tpState.finished) return;
    tpState.lastEvent = null;

    if (action === "clue") {
        tpState.message = "Uklesano je: 'Put otvara svetlost koja ide od severa ka istoku, zatim jugu i zapadu.'";
        tpAddLog("Pročitan je trag za redosled baklji.");
        renderTajnaPovelja();
        return;
    }

    if (action === "inscription") {
        if (!tpState.inscriptionRevealed) {
            tpState.message = "Natpis se još ne vidi. Moraš prvo upaliti sve baklje pravilnim redosledom.";
            renderTajnaPovelja();
            return;
        }
        tpState.message = "Natpis kaže: 'Povelju čuva kamen koji sluša svetlost.' Ispod natpisa se pojavljuje pergament.";
        tpAddLog("Natpis je otkrio gde se krije prvi deo povelje.");
        renderTajnaPovelja();
        return;
    }

    if (action === "parchment") {
        if (!tpState.inscriptionRevealed) {
            tpState.message = "Pergament je sakriven u tami. Prvo otkrij natpis.";
            renderTajnaPovelja();
            return;
        }
        if (!tpState.parchmentFound) {
            tpState.parchmentFound = true;
            tpAddItem("parchment");
            tpState.lastEvent = "item";
            tpState.message = "Uzela si prvi deo povelje. Sada pokušaj da pomeriš veliki kamen.";
            tpAddLog("U inventar je dodat prvi deo povelje.");
        } else {
            tpState.message = "Deo povelje je već u inventaru.";
        }
        renderTajnaPovelja();
        return;
    }

    if (action === "stone") {
        if (!tpState.parchmentFound) {
            tpState.message = "Kamen se ne pomera. Natpis traži dokaz: prvo uzmi deo povelje.";
            renderTajnaPovelja();
            return;
        }
        if (!tpState.stoneMoved) {
            tpState.stoneMoved = true;
            tpState.lastEvent = "stone";
            tpState.message = "Kamen se pomerio uz težak zvuk. Iza njega se otvorio tajni prolaz.";
            tpAddLog("Otvoren je prolaz ka sledećoj lokaciji.");
        }
        renderTajnaPovelja();
        return;
    }

    if (action === "passage") {
        if (!tpState.stoneMoved) {
            tpState.message = "Prolaz je zatvoren. Pomeri veliki kamen.";
            renderTajnaPovelja();
            return;
        }
        tpState.finished = true;
        tpState.message = "Uspela si. Prvi deo povelje je sačuvan, a put se nastavlja.";
        tpAddLog("Nivo je završen.");
        renderTajnaPovelja();
    }
}

function showTPHint() {
    if (!tpState.started) {
        tpState.started = true;
    }
    if (tpState.litTorches.length < 4) {
        const next = TP_TORCH_ORDER[tpState.litTorches.length];
        tpState.message = `Trag: sledeća je ${TP_TORCH_LABELS[next].toLowerCase()}. Redosled je sever - istok - jug - zapad.`;
    } else if (!tpState.parchmentFound) {
        tpState.message = "Trag: klikni natpis, pa pergament koji se pojavio ispod njega.";
    } else if (!tpState.stoneMoved) {
        tpState.message = "Trag: sada klikni veliki kamen desno od natpisa.";
    } else {
        tpState.message = "Trag: klikni tajni prolaz.";
    }
    renderTajnaPovelja();
}

startMiniGamesMode = startTajnaPovelja;
startEscapeRoom = startTajnaPovelja;
renderEscapeRoom = renderTajnaPovelja;
showEscapeHint = showTPHint;

document.addEventListener("DOMContentLoaded", () => {
    const oldButton = byId("startMiniGamesBtn");
    if (!oldButton) return;
    const newButton = oldButton.cloneNode(true);
    newButton.querySelector("span").innerText = "Puzzle avantura";
    newButton.querySelector("strong").innerText = "Tajna povelja";
    newButton.querySelector("small").innerText = "Istraži pećinu, pali baklje, pronađi povelju i otvori prolaz.";
    oldButton.replaceWith(newButton);
    newButton.addEventListener("click", startTajnaPovelja);
});

/* =========================
   TAJNA POVELJA - GUIDED CLICK FIX
   ========================= */

function tpCurrentTarget() {
    if (!tpState?.started || tpState.finished) return null;
    if (tpState.litTorches.length < TP_TORCH_ORDER.length) {
        return { type: "torch", id: TP_TORCH_ORDER[tpState.litTorches.length], label: TP_TORCH_LABELS[TP_TORCH_ORDER[tpState.litTorches.length]] };
    }
    if (!tpState.inscriptionRevealed) return { type: "action", id: "inscription", label: "Natpis" };
    if (!tpState.parchmentFound) return { type: "action", id: "parchment", label: "Deo povelje" };
    if (!tpState.stoneMoved) return { type: "action", id: "stone", label: "Veliki kamen" };
    return { type: "action", id: "passage", label: "Tajni prolaz" };
}

function tpApplyGuidance() {
    const target = tpCurrentTarget();
    const stage = document.querySelector(".tp-stage");
    if (!stage || !target) return;

    document.querySelectorAll(".tp-hotspot").forEach(node => {
        const isTorch = node.classList.contains("tp-torch");
        const isCurrentTorch = target.type === "torch" && node.dataset.torch === target.id;
        const isCurrentAction = target.type === "action" && node.dataset.action === target.id;
        const alreadyDone = node.classList.contains("is-lit") || node.classList.contains("is-collected") || node.classList.contains("is-moved");
        const utility = node.dataset.action === "clue";
        const active = isCurrentTorch || isCurrentAction || utility || alreadyDone;
        node.classList.toggle("tp-current-target", isCurrentTorch || isCurrentAction);
        node.classList.toggle("tp-locked-target", !active);
    });

    const old = stage.querySelector(".tp-next-banner");
    old?.remove();
    const banner = document.createElement("div");
    banner.className = "tp-next-banner";
    banner.innerHTML = `<span>Sledeći potez</span><strong>${target.label}</strong><small>Klikni samo označen predmet.</small>`;
    stage.appendChild(banner);
}

const tpRenderBeforeGuidance = renderTajnaPovelja;
renderTajnaPovelja = function() {
    tpRenderBeforeGuidance();
    tpApplyGuidance();
};

handleTPTorch = function(torchId) {
    if (!tpState.started || tpState.finished) return;
    tpState.lastEvent = null;

    if (tpState.litTorches.includes(torchId)) {
        tpState.message = `${TP_TORCH_LABELS[torchId]} je već upaljena. Prati označeni sledeći potez.`;
        renderTajnaPovelja();
        return;
    }

    const expected = TP_TORCH_ORDER[tpState.litTorches.length];
    if (torchId !== expected) {
        tpState.wrongTorch = torchId;
        tpState.message = `Još nije red na tu baklju. Sledeća je ${TP_TORCH_LABELS[expected].toLowerCase()}.`;
        tpAddLog(`Pokušaj nije prihvaćen: prvo ide ${TP_TORCH_LABELS[expected].toLowerCase()}.`);
        setTimeout(() => { if (tpState) { tpState.wrongTorch = null; renderTajnaPovelja(); } }, 420);
        renderTajnaPovelja();
        return;
    }

    tpState.litTorches.push(torchId);
    tpState.lastEvent = "torch";
    tpState.wrongTorch = null;
    tpState.message = `${TP_TORCH_LABELS[torchId]} se upalila. Sada prati sledeći označen predmet.`;
    tpAddLog(`Upaljena je ${TP_TORCH_LABELS[torchId].toLowerCase()}.`);

    if (tpState.litTorches.length === 4) {
        tpState.inscriptionRevealed = true;
        tpState.message = "Sve baklje gore. Na zidu se pojavio skriveni natpis. Klikni ga.";
        tpAddLog("Skriveni natpis se pojavio iznad kamenog prolaza.");
    }

    renderTajnaPovelja();
};

/* =========================
   TAJNA POVELJA - MULTI LEVEL ADVENTURE
   ========================= */

const tpAdventureLevels = [
    {
        id: "cave",
        title: "Pećina isposnika",
        subtitle: "Baklje i tajni prolaz",
        kind: "sequence",
        bg: "riznica-pecata.jpg",
        intro: "U pećini se krije prvi trag. Upali baklje pravilnim redosledom, pročitaj natpis i pomeri kamen.",
        sequence: ["Severna baklja", "Istočna baklja", "Južna baklja", "Zapadna baklja", "Natpis", "Deo povelje", "Veliki kamen", "Tajni prolaz"],
        icons: ["🔥", "🔥", "🔥", "🔥", "✦", "📜", "⬣", "🚪"],
        lesson: "Prvi deo povelje pronađen je u pećini. Svetlost i redosled otvaraju prolaz."
    },
    {
        id: "anagram",
        title: "Pergament u skriptorijumu",
        subtitle: "Anagram vladara",
        kind: "anagram",
        bg: "manastirska-biblioteka.jpg",
        intro: "Na starom pergamentu slova su pomešana. Složi ime vladara da otključaš sledeći simbol.",
        letters: ["A", "N", "M", "E", "J", "A", "N"],
        answer: "NEMANJA",
        lesson: "Stefan Nemanja je jedna od ključnih ličnosti srpske srednjovekovne istorije."
    },
    {
        id: "market",
        title: "Varoš na raskršću",
        subtitle: "Skriveni predmeti u gužvi",
        kind: "hidden",
        bg: "background-hall.jpg",
        intro: "Pisar je izgubio svoje stvari u varoši. Pronađi predmete pre nego što trag nestane među ljudima.",
        required: ["Pero pisara", "Kožna torba", "Mali pečat", "Mapa puta"],
        decoys: ["Vrč", "Kesa žita", "Sveća", "Lanterna"],
        lesson: "Pisar, pečat i mapa povezuju putnike, dokumente i vlast u srednjem veku."
    },
    {
        id: "forge",
        title: "Kovačnica pod kulom",
        subtitle: "Iskuj ključ",
        kind: "craft",
        bg: "dvorana-nasledja.jpg",
        intro: "Ključ za staru kapiju je slomljen. U kovačnici moraš da sastaviš i iskuješ novi ključ.",
        sequence: ["Glava ključa", "Telo ključa", "Zubac", "Vatra", "Čekić", "Voda"],
        icons: ["🗝️", "▰", "⌁", "🔥", "🔨", "💧"],
        lesson: "Kovači su bili važni zanatlije: bez alata, brave i oružja nema sigurnog puta."
    },
    {
        id: "fresco",
        title: "Napušteni manastir",
        subtitle: "Puzzle freska",
        kind: "puzzle",
        bg: "manastirska-biblioteka.jpg",
        intro: "Na zidu se nazire oštećena freska. Vrati delove na pravo mesto da se pojavi skriveni znak.",
        pieces: ["Oreol", "Lice", "Knjiga", "Natpis"],
        lesson: "Freske nisu samo ukras, već slikovno pamćenje vremena, vere i ljudi."
    },
    {
        id: "bells",
        title: "Zvonik stare kule",
        subtitle: "Redosled zvona",
        kind: "bells",
        bg: "dvorana-nasledja.jpg",
        intro: "Četiri zvona otvaraju vrata samo ako odsviraš pravi ritam: malo, veliko, srednje, malo.",
        sequence: ["Malo zvono", "Veliko zvono", "Srednje zvono", "Malo zvono"],
        lesson: "Zvona su prenosila poruke zajednici: opasnost, molitvu, početak i kraj dana."
    },
    {
        id: "rebus",
        title: "Tajni znak na vratima",
        subtitle: "Rebus",
        kind: "input",
        bg: "riznica-pecata.jpg",
        intro: "Na vratima stoji rebus: KR + ST. Upiši reč koja otvara bravu.",
        prompt: "KR + ST = ?",
        answer: "KRST",
        lesson: "Krst je jedan od najprepoznatljivijih simbola hrišćanske kulture i srednjovekovne umetnosti."
    },
    {
        id: "crossword",
        title: "Knjiga zagonetki",
        subtitle: "Mini ukrštenica",
        kind: "crossword",
        bg: "manastirska-biblioteka.jpg",
        intro: "Popuni tri pojma i dobićeš završnu reč za kapiju.",
        clues: [
            { clue: "Mesto gde se prepisuju knjige", answer: "SKRIPTORIJUM" },
            { clue: "Vladarski dokument sa pečatom", answer: "POVELJA" },
            { clue: "Zidna slika u crkvi", answer: "FRESKA" }
        ],
        lesson: "Skriptorijum, povelja i freska čuvaju znanje kroz tekst, pečat i sliku."
    }
];

let tpAdventure = null;

function tpNewAdventureState() {
    return {
        level: 0,
        progress: {},
        inventory: ["Prvi trag"],
        log: ["Avantura je počela: povelja je razbijena na više tragova."],
        message: "Započni nivo i prati cilj na sceni.",
        transition: false,
        done: false
    };
}

function tpLevel() {
    return tpAdventureLevels[tpAdventure.level] || tpAdventureLevels[0];
}

function tpLevelState() {
    const level = tpLevel();
    if (!tpAdventure.progress[level.id]) {
        tpAdventure.progress[level.id] = { selected: [], text: "", answers: {}, started: false, complete: false, wrong: null };
    }
    return tpAdventure.progress[level.id];
}

function tpAdventureProgress() {
    const completed = Object.values(tpAdventure.progress).filter(state => state.complete).length;
    return Math.round((completed / tpAdventureLevels.length) * 100);
}

function tpLevelProgress(level, state) {
    if (state.complete) return 100;
    if (["sequence", "craft", "bells"].includes(level.kind)) return Math.round((state.selected.length / level.sequence.length) * 100);
    if (level.kind === "anagram") return Math.round((state.text.length / level.answer.length) * 100);
    if (level.kind === "hidden") return Math.round((state.selected.length / level.required.length) * 100);
    if (level.kind === "puzzle") return Math.round((state.selected.length / level.pieces.length) * 100);
    if (level.kind === "input") return state.text ? 70 : 0;
    if (level.kind === "crossword") return Math.round((Object.keys(state.answers).filter(key => normalizeAnswer(state.answers[key]) === normalizeAnswer(level.clues[key].answer)).length / level.clues.length) * 100);
    return 0;
}

function tpAdventureLog(text) {
    tpAdventure.log.unshift(text);
    tpAdventure.log = tpAdventure.log.slice(0, 5);
}

function tpCompleteLevel() {
    const level = tpLevel();
    const state = tpLevelState();
    if (state.complete) return;
    state.complete = true;
    tpAdventure.inventory.push(`Simbol: ${level.title}`);
    tpAdventure.message = level.lesson;
    tpAdventureLog(`Završen nivo: ${level.title}.`);
    toast?.(`Otključano: ${level.subtitle}`, "plus");
    renderTajnaPovelja();
}

function tpNextAdventureLevel() {
    if (tpAdventure.level >= tpAdventureLevels.length - 1) {
        tpAdventure.done = true;
        tpAdventure.message = "Sakupila si sve tragove prototipa. Sledeće širimo avanturu do 20 nivoa.";
        renderTajnaPovelja();
        return;
    }
    tpAdventure.transition = true;
    renderTajnaPovelja();
    setTimeout(() => {
        tpAdventure.level += 1;
        tpAdventure.transition = false;
        tpAdventure.message = tpLevel().intro;
        tpAdventureLog(`Nova lokacija: ${tpLevel().title}.`);
        renderTajnaPovelja();
    }, 650);
}

startTajnaPovelja = function() {
    closePauseModal?.(true);
    closeHelpModal?.(true);
    stopEscapeTimer?.();
    stopMainTimer?.();
    tpAdventure = tpNewAdventureState();
    setActiveScreen("escapeScreen");
    renderTajnaPovelja();
    applyScriptMode?.(scriptMode);
}

function tpRenderSequence(level, state) {
    const next = level.sequence[state.selected.length];
    return `
        <div class="tp2-object-field tp2-sequence-field">
            ${level.sequence.map((item, index) => `
                <button type="button" class="tp2-object ${state.selected.includes(index) ? "is-done" : ""} ${next === item ? "is-next" : ""} ${state.wrong === index ? "is-wrong" : ""}" data-tp-seq="${index}">
                    <span>${level.icons?.[index] || "✦"}</span>
                    <strong>${item}</strong>
                    <small>${state.selected.includes(index) ? "urađeno" : next === item ? "sledeće" : "zaključano"}</small>
                </button>
            `).join("")}
        </div>
    `;
}

function tpRenderAnagram(level, state) {
    return `
        <div class="tp2-anagram">
            <div class="tp2-answer-slots">${level.answer.split("").map((_, index) => `<span>${state.text[index] || ""}</span>`).join("")}</div>
            <div class="tp2-letter-bank">
                ${level.letters.map((letter, index) => `<button type="button" class="tp2-letter ${state.selected.includes(index) ? "is-used" : ""}" data-tp-letter="${index}">${letter}</button>`).join("")}
            </div>
            <div class="tp2-inline-actions">
                <button type="button" class="tp2-small" data-tp-clear="letters">Obriši</button>
                <button type="button" class="tp2-small is-primary" data-tp-check="anagram">Proveri</button>
            </div>
        </div>
    `;
}

function tpRenderHidden(level, state) {
    const all = [...level.required, ...level.decoys];
    return `
        <div class="tp2-object-field tp2-hidden-field">
            ${all.map((item, index) => `
                <button type="button" class="tp2-object tp2-hidden-object tp2-pos-${index + 1} ${state.selected.includes(item) ? "is-done" : ""} ${state.wrong === item ? "is-wrong" : ""}" data-tp-hidden="${item}">
                    <span>${tpIcon(item)}</span><strong>${item}</strong><small>${state.selected.includes(item) ? "nađeno" : "pronađi"}</small>
                </button>
            `).join("")}
        </div>
    `;
}

function tpRenderPuzzle(level, state) {
    return `
        <div class="tp2-puzzle-board">
            ${level.pieces.map((piece, index) => `<button type="button" class="tp2-tile ${state.selected.includes(piece) ? "is-set" : ""}" data-tp-piece="${piece}"><span>${index + 1}</span><strong>${piece}</strong></button>`).join("")}
        </div>
    `;
}

function tpRenderInput(level, state) {
    return `
        <div class="tp2-input-puzzle">
            <div class="tp2-rebus">${level.prompt}</div>
            <input id="tp2TextInput" value="${state.text || ""}" placeholder="Upiši odgovor" autocomplete="off">
            <button type="button" class="tp2-small is-primary" data-tp-check="input">Potvrdi</button>
        </div>
    `;
}

function tpRenderCrossword(level, state) {
    return `
        <div class="tp2-crossword">
            ${level.clues.map((row, index) => `
                <label class="tp2-cross-row ${normalizeAnswer(state.answers[index]) === normalizeAnswer(row.answer) ? "is-correct" : ""}">
                    <span>${index + 1}. ${row.clue}</span>
                    <input data-tp-cross="${index}" value="${state.answers[index] || ""}" placeholder="odgovor">
                </label>
            `).join("")}
            <button type="button" class="tp2-small is-primary" data-tp-check="crossword">Proveri ukrštenicu</button>
        </div>
    `;
}

function tpRenderLevelBody(level, state) {
    if (["sequence", "craft", "bells"].includes(level.kind)) return tpRenderSequence(level, state);
    if (level.kind === "anagram") return tpRenderAnagram(level, state);
    if (level.kind === "hidden") return tpRenderHidden(level, state);
    if (level.kind === "puzzle") return tpRenderPuzzle(level, state);
    if (level.kind === "input") return tpRenderInput(level, state);
    if (level.kind === "crossword") return tpRenderCrossword(level, state);
    return "";
}

function tpIcon(item) {
    const map = {
        "Pero pisara": "🪶", "Kožna torba": "🎒", "Mali pečat": "🔰", "Mapa puta": "🗺️",
        "Vrč": "🏺", "Kesa žita": "🌾", "Sveća": "🕯️", "Lanterna": "🏮"
    };
    return map[item] || "✦";
}

renderTajnaPovelja = function() {
    if (!tpAdventure) tpAdventure = tpNewAdventureState();
    const level = tpLevel();
    const state = tpLevelState();
    byId("escapeRoomLabel").innerText = `Nivo ${tpAdventure.level + 1}/${tpAdventureLevels.length}`;
    byId("escapeTitle").innerText = "Tajna povelja";
    const timer = byId("escapeTimer");
    if (timer) timer.innerText = level.subtitle;
    const container = document.querySelector(".pv-escape-room");
    if (!container) return;

    const levelProgress = tpLevelProgress(level, state);
    container.innerHTML = `
        <section class="tp2-game tp2-kind-${level.kind}" style="--tp2-bg:url('/images/game/${level.bg}')">
            <div class="tp2-bg"></div>
            <div class="tp2-vignette"></div>
            <header class="tp2-hud">
                <div class="tp2-title"><span>Tajna povelja</span><h2>${level.title}</h2><p>${level.intro}</p></div>
                <div class="tp2-meter"><strong>${levelProgress}%</strong><span>nivo</span></div>
                <div class="tp2-meter"><strong>${tpAdventureProgress()}%</strong><span>put</span></div>
                <button type="button" class="tp2-menu" id="tpMenuBtn">Meni</button>
            </header>
            <main class="tp2-stage">
                <div class="tp2-room-title"><span>${level.subtitle}</span><strong>${tpAdventure.message || level.intro}</strong></div>
                ${tpRenderLevelBody(level, state)}
                ${state.complete ? `<div class="tp2-complete-card"><span>✨ Nivo rešen</span><h3>${level.subtitle}</h3><p>${level.lesson}</p><button type="button" class="pv-btn pv-btn-primary" id="tp2NextBtn">${tpAdventure.level >= tpAdventureLevels.length - 1 ? "Završi prototip" : "Sledeći nivo"}</button></div>` : ""}
                ${tpAdventure.done ? `<div class="tp2-complete-card tp2-final"><span>🏁 Prototip završen</span><h3>Svi tragovi su sakupljeni</h3><p>Ovo je osnova za nastavak do 20 nivoa: pećina, anagram, varoš, kovačnica, freska, zvona, rebus i ukrštenica rade kao različite mehanike.</p><button type="button" class="pv-btn pv-btn-primary" id="tpResetBtn">Igraj ponovo</button></div>` : ""}
                ${tpAdventure.transition ? `<div class="tp2-transition"><span>Otključava se sledeća lokacija...</span></div>` : ""}
            </main>
            <aside class="tp2-quest"><span>Cilj</span><p>${tpGoalText(level, state)}</p></aside>
            <aside class="tp2-journal"><span>Dnevnik</span>${tpAdventure.log.map(line => `<p>${line}</p>`).join("")}</aside>
            <footer class="tp2-inventory"><button type="button" class="tp2-tool" id="tpHintBtn">💡 Trag</button><div>${tpAdventure.inventory.map(item => `<strong>${item}</strong>`).join("")}</div><button type="button" class="tp2-tool" id="tpResetBtn">↻ Ponovo</button></footer>
        </section>
    `;
    bindTajnaPovelja();
    applyScriptMode?.(scriptMode);
}

function tpGoalText(level, state) {
    if (["sequence", "craft", "bells"].includes(level.kind)) return `Klikći označene elemente redom. Sledeće: ${level.sequence[state.selected.length] || "završi nivo"}.`;
    if (level.kind === "anagram") return `Složi reč ${level.answer.length} slova.`;
    if (level.kind === "hidden") return `Pronađi: ${level.required.filter(item => !state.selected.includes(item)).join(", ") || "sve je pronađeno"}.`;
    if (level.kind === "puzzle") return "Vrati sve delove freske na mesto.";
    if (level.kind === "input") return "Reši rebus i potvrdi odgovor.";
    if (level.kind === "crossword") return "Popuni sve pojmove iz mini-ukrštenice.";
    return level.intro;
}

bindTajnaPovelja = function() {
    byId("tpMenuBtn")?.addEventListener("click", backToMenu);
    byId("tpHintBtn")?.addEventListener("click", showTPHint);
    byId("tpResetBtn")?.addEventListener("click", startTajnaPovelja);
    byId("tp2NextBtn")?.addEventListener("click", tpNextAdventureLevel);
    document.querySelectorAll("[data-tp-seq]").forEach(button => button.addEventListener("click", () => tpClickSequence(Number(button.dataset.tpSeq))));
    document.querySelectorAll("[data-tp-letter]").forEach(button => button.addEventListener("click", () => tpClickLetter(Number(button.dataset.tpLetter))));
    document.querySelectorAll("[data-tp-hidden]").forEach(button => button.addEventListener("click", () => tpClickHidden(button.dataset.tpHidden)));
    document.querySelectorAll("[data-tp-piece]").forEach(button => button.addEventListener("click", () => tpClickPiece(button.dataset.tpPiece)));
    document.querySelectorAll("[data-tp-cross]").forEach(input => input.addEventListener("input", () => { tpLevelState().answers[input.dataset.tpCross] = input.value; }));
    byId("tp2TextInput")?.addEventListener("input", event => { tpLevelState().text = event.target.value; });
    document.querySelectorAll("[data-tp-clear]").forEach(button => button.addEventListener("click", () => { const s = tpLevelState(); s.text = ""; s.selected = []; renderTajnaPovelja(); }));
    document.querySelectorAll("[data-tp-check]").forEach(button => button.addEventListener("click", () => tpCheckPuzzle(button.dataset.tpCheck)));
}

function tpClickSequence(index) {
    const level = tpLevel();
    const state = tpLevelState();
    if (state.complete) return;
    const expected = state.selected.length;
    if (index !== expected) {
        state.wrong = index;
        tpAdventure.message = `Nije taj redosled. Sledeće je: ${level.sequence[expected]}.`;
        setTimeout(() => { state.wrong = null; renderTajnaPovelja(); }, 400);
        renderTajnaPovelja();
        return;
    }
    state.selected.push(index);
    tpAdventure.message = `Urađeno: ${level.sequence[index]}.`;
    tpAdventureLog(`${level.title}: ${level.sequence[index]}.`);
    if (state.selected.length === level.sequence.length) tpCompleteLevel(); else renderTajnaPovelja();
}

function tpClickLetter(index) {
    const level = tpLevel();
    const state = tpLevelState();
    if (state.selected.includes(index) || state.complete) return;
    if (state.text.length >= level.answer.length) return;
    state.selected.push(index);
    state.text += level.letters[index];
    tpAdventure.message = `Slova složena: ${state.text}`;
    renderTajnaPovelja();
}

function tpClickHidden(item) {
    const level = tpLevel();
    const state = tpLevelState();
    if (state.complete || state.selected.includes(item)) return;
    if (!level.required.includes(item)) {
        state.wrong = item;
        tpAdventure.message = `${item} nije deo pisarevog traga.`;
        setTimeout(() => { state.wrong = null; renderTajnaPovelja(); }, 420);
        renderTajnaPovelja();
        return;
    }
    state.selected.push(item);
    tpAdventure.message = `Pronađeno: ${item}.`;
    tpAdventureLog(`Varoš: pronađeno ${item}.`);
    if (state.selected.length === level.required.length) tpCompleteLevel(); else renderTajnaPovelja();
}

function tpClickPiece(piece) {
    const level = tpLevel();
    const state = tpLevelState();
    if (state.complete || state.selected.includes(piece)) return;
    state.selected.push(piece);
    tpAdventure.message = `Freska dobija deo: ${piece}.`;
    tpAdventureLog(`Freska: vraćen deo ${piece}.`);
    if (state.selected.length === level.pieces.length) tpCompleteLevel(); else renderTajnaPovelja();
}

function tpCheckPuzzle(kind) {
    const level = tpLevel();
    const state = tpLevelState();
    if (kind === "anagram") {
        if (normalizeAnswer(state.text) === normalizeAnswer(level.answer)) tpCompleteLevel();
        else { tpAdventure.message = "Anagram nije tačan. Probaj ponovo ili koristi trag."; renderTajnaPovelja(); }
    }
    if (kind === "input") {
        if (normalizeAnswer(state.text) === normalizeAnswer(level.answer)) tpCompleteLevel();
        else { tpAdventure.message = "Rebus nije tačan. Pogledaj znakove još jednom."; renderTajnaPovelja(); }
    }
    if (kind === "crossword") {
        const ok = level.clues.every((row, index) => normalizeAnswer(state.answers[index]) === normalizeAnswer(row.answer));
        if (ok) tpCompleteLevel();
        else { tpAdventure.message = "Nisu svi pojmovi tačni. Tačni odgovori postaju označeni."; renderTajnaPovelja(); }
    }
}

showTPHint = function() {
    const level = tpLevel();
    const state = tpLevelState();
    if (["sequence", "craft", "bells"].includes(level.kind)) tpAdventure.message = `Trag: klikni sledeće - ${level.sequence[state.selected.length]}.`;
    else if (level.kind === "anagram") tpAdventure.message = `Trag: ime je ${level.answer[0]}... i ima ${level.answer.length} slova.`;
    else if (level.kind === "hidden") tpAdventure.message = `Trag: traži samo pisareve predmete: ${level.required.join(", ")}.`;
    else if (level.kind === "puzzle") tpAdventure.message = "Trag: klikni sva četiri dela freske. Svaki deo popunjava sliku.";
    else if (level.kind === "input") tpAdventure.message = "Trag: KR i ST zajedno daju reč od 4 slova.";
    else if (level.kind === "crossword") tpAdventure.message = "Trag: odgovori su SKRIPTORIJUM, POVELJA i FRESKA.";
    renderTajnaPovelja();
}

startMiniGamesMode = startTajnaPovelja;
startEscapeRoom = startTajnaPovelja;
renderEscapeRoom = renderTajnaPovelja;
showEscapeHint = showTPHint;


/* =========================
   TAJNA POVELJA - REAL ESCAPE ROOM THINKING MODE
   ========================= */

const tpEscapeHints = {
    cave: [
        "Pogledaj raspored baklji i reči iz uvoda: strane sveta su važnije od brojeva.",
        "Redosled ide kao kretanje po stranama sveta: sever, istok, jug, zapad.",
        "Klikni: Severna baklja, Istočna baklja, Južna baklja, Zapadna baklja."
    ],
    anagram: [
        "Slova grade ime vladara iz dinastije Nemanjića.",
        "Ime počinje slovom N i ima sedam slova.",
        "Rešenje je NEMANJA."
    ],
    market: [
        "Ne traži obične stvari sa pijace, već predmete koji pripadaju pisaru i putniku.",
        "Pisar bi nosio pero, torbu, pečat i mapu.",
        "Pronađi: Pero pisara, Kožna torba, Mali pečat, Mapa puta."
    ],
    forge: [
        "Prvo sastavi oblik ključa, pa ga obradi vatrom i alatom.",
        "Ključ se pravi pre kaljenja: glava, telo, zubac, vatra, čekić, voda.",
        "Klikni redom: Glava ključa, Telo ključa, Zubac, Vatra, Čekić, Voda."
    ],
    fresco: [
        "Freska se rešava kao slagalica: svaki deo vraća jedan sloj slike.",
        "Nema pogrešnog redosleda, samo pronađi i vrati sve delove.",
        "Klikni sva četiri dela: Oreol, Lice, Knjiga, Natpis."
    ],
    bells: [
        "Ritam nije po veličini od najmanjeg do najvećeg. Jedno zvono se ponavlja.",
        "Ritam je: malo, veliko, srednje, malo.",
        "Klikni: Malo zvono, Veliko zvono, Srednje zvono, Malo zvono."
    ],
    rebus: [
        "Spoj slogove bez razmaka.",
        "KR i ST daju verski simbol od četiri slova.",
        "Odgovor je KRST."
    ],
    crossword: [
        "Svi odgovori su pojmovi vezani za knjige, dokumente i zidno slikarstvo.",
        "Traže se: mesto prepisivanja, dokument sa pečatom i zidna slika.",
        "Odgovori su: SKRIPTORIJUM, POVELJA, FRESKA."
    ]
};

function tpEnsureEscapeState(state) {
    if (typeof state.hintsUsed !== "number") state.hintsUsed = 0;
    if (typeof state.mistakes !== "number") state.mistakes = 0;
    if (typeof state.revealHelpUntil !== "number") state.revealHelpUntil = 0;
}

function tpShouldRevealHelp(state) {
    return state.revealHelpUntil && Date.now() < state.revealHelpUntil;
}

function tpEscapeGoal(level, state) {
    const goals = {
        sequence: "Istraži simbole u prostoriji i otkrij pravilan redosled. Pogrešan redosled neće rešiti mehanizam.",
        craft: "Sastavi predmet logičnim zanatskim redosledom. Razmisli šta ide pre obrade vatrom.",
        bells: "Odsviraj ritam koji otvara vrata. Jedno zvono se ponavlja.",
        anagram: "Od ponuđenih slova složi ime koje ima istorijski smisao.",
        hidden: "U sceni pronađi samo predmete koji pripadaju pisaru. Ostali su zamka.",
        puzzle: "Vrati sve delove slike na zid i otkrij skriveni znak.",
        input: "Reši znak na vratima i unesi odgovor.",
        crossword: "Popuni sve pojmove. Svaki tačan pojam otkriva deo završne reči."
    };
    return goals[level.kind] || level.intro;
}

tpGoalText = function(level, state) {
    tpEnsureEscapeState(state);
    if (tpShouldRevealHelp(state)) {
        const hints = tpEscapeHints[level.id] || tpEscapeHints[level.kind] || [];
        return hints[Math.min(state.hintsUsed - 1, hints.length - 1)] || tpEscapeGoal(level, state);
    }
    return tpEscapeGoal(level, state);
};

tpRenderSequence = function(level, state) {
    tpEnsureEscapeState(state);
    const reveal = tpShouldRevealHelp(state);
    const nextIndex = state.selected.length;
    return `
        <div class="tp2-object-field tp2-sequence-field tp2-thinking-field">
            ${level.sequence.map((item, index) => `
                <button type="button" class="tp2-object ${state.selected.includes(index) ? "is-done" : ""} ${reveal && index === nextIndex ? "is-next" : ""} ${state.wrong === index ? "is-wrong" : ""}" data-tp-seq="${index}">
                    <span>${level.icons?.[index] || "✦"}</span>
                    <strong>${item}</strong>
                    <small>${state.selected.includes(index) ? "aktivirano" : "ispitaj"}</small>
                </button>
            `).join("")}
        </div>
    `;
};

tpClickSequence = function(index) {
    const level = tpLevel();
    const state = tpLevelState();
    tpEnsureEscapeState(state);
    if (state.complete) return;

    const expected = state.selected.length;
    if (state.selected.includes(index)) {
        tpAdventure.message = "Taj element je već aktiviran. Potraži sledeći deo mehanizma.";
        renderTajnaPovelja();
        return;
    }

    if (index !== expected) {
        state.wrong = index;
        state.mistakes += 1;
        tpAdventure.message = state.mistakes >= 2
            ? "Mehanizam ne reaguje. Ako zapneš, koristi dugme Trag za postepenu pomoć."
            : "Nešto u redosledu ne odgovara. Posmatraj simbole i pokušaj drugačije.";
        tpAdventureLog(`${level.title}: pogrešan pokušaj na mehanizmu.`);
        setTimeout(() => { state.wrong = null; renderTajnaPovelja(); }, 420);
        renderTajnaPovelja();
        return;
    }

    state.selected.push(index);
    state.wrong = null;
    state.revealHelpUntil = 0;
    tpAdventure.message = `Mehanizam je prihvatio: ${level.sequence[index]}. Nastavi da razmišljaš o sledećem koraku.`;
    tpAdventureLog(`${level.title}: aktivirano ${level.sequence[index]}.`);
    if (state.selected.length === level.sequence.length) tpCompleteLevel(); else renderTajnaPovelja();
};

showTPHint = function() {
    const level = tpLevel();
    const state = tpLevelState();
    tpEnsureEscapeState(state);
    const hints = tpEscapeHints[level.id] || tpEscapeHints[level.kind] || ["Pogledaj pažljivo scenu i probaj logičan redosled."];
    state.hintsUsed = Math.min(state.hintsUsed + 1, hints.length);
    state.revealHelpUntil = Date.now() + 6500;
    tpAdventure.message = `Trag ${state.hintsUsed}/${hints.length}: ${hints[state.hintsUsed - 1]}`;
    tpAdventureLog(`Korišćen trag ${state.hintsUsed}: ${level.title}.`);
    renderTajnaPovelja();
};
