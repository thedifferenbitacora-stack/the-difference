<?php
/**
 * AGENTE NORMALIZADOR MULTILINGÜE - THE DIFFERENCE
 * Versión corregida - Regex compatible con PHP/PCRE
 */

set_time_limit(300);
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$baseDir = dirname(__DIR__, 3);
$bitacoraFile = $baseDir . '/config/bitacora.json';
$memoryDir = $baseDir . '/.memory/conversations';

if (!is_dir($memoryDir)) {
    mkdir($memoryDir, 0755, true);
}

// TÉRMINOS DE DOMINIO POR IDIOMA
$diccionariosDominio = [
    'es' => ['ontologia', 'proceso', 'sistema', 'plataforma', 'trazabilidad', 'bitacora', 'huella', 'presencia', 'observacion', 'reflexion', 'intuicion', 'sintesis', 'pensamiento', 'critico', 'individuo', 'sujeto', 'valor', 'diferencia', 'autonomia', 'tecnologia', 'tecnico', 'simbolico', 'contenido', 'narrativa', 'desarrollo', 'codigo', 'arquitectura', 'infraestructura', 'quantum', 'laboratorio', 'utero', 'gestacion', 'emergencia', 'agencia', 'productora', 'sello', 'centro', 'herramienta', 'metodologia', 'fundacion', 'institucion', 'alianza', 'inteligencia', 'artificial', 'espejo', 'autoridad', 'tiempo', 'salud', 'bienestar', 'neurodivergencia', 'umbral', 'apertura', 'observar', 'habitar', 'conflicto', 'transformacion', 'evolucion', 'dato', 'informacion', 'conocimiento', 'educacion', 'pedagogia', 'aprendizaje', 'cultura', 'arte', 'estetica', 'comunidad', 'ecosistema'],
    
    'en' => ['ontology', 'ontological', 'process', 'system', 'platform', 'traceability', 'logbook', 'footprint', 'presence', 'observation', 'reflection', 'intuition', 'synthesis', 'thinking', 'critical', 'individual', 'subject', 'value', 'difference', 'autonomy', 'technology', 'technical', 'symbolic', 'content', 'narrative', 'development', 'code', 'architecture', 'infrastructure', 'quantum', 'laboratory', 'womb', 'gestation', 'emergence', 'agency', 'producer', 'label', 'center', 'tool', 'methodology', 'foundation', 'institution', 'alliance', 'intelligence', 'artificial', 'mirror', 'authority', 'time', 'health', 'wellbeing', 'neurodivergence', 'threshold', 'opening', 'observe', 'inhabit', 'conflict', 'transformation', 'evolution', 'data', 'information', 'knowledge', 'education', 'pedagogy', 'learning', 'culture', 'art', 'aesthetics', 'community', 'ecosystem'],
    
    'pt' => ['ontologia', 'ontologico', 'processo', 'sistema', 'plataforma', 'rastreabilidade', 'diario', 'pegada', 'presenca', 'observacao', 'reflexao', 'intuicao', 'sintese', 'pensamento', 'critico', 'individuo', 'sujeito', 'valor', 'diferenca', 'autonomia', 'tecnologia', 'tecnico', 'simbolico', 'conteudo', 'narrativa', 'desenvolvimento', 'codigo', 'arquitetura', 'infraestrutura', 'quantico', 'laboratorio', 'utero', 'gestacao', 'emergencia', 'agencia', 'produtora', 'selo', 'centro', 'ferramenta', 'metodologia', 'fundacao', 'instituicao', 'alianca', 'inteligencia', 'artificial', 'espelho', 'autoridade', 'tempo', 'saude', 'bem-estar', 'neurodivergencia', 'limiar', 'abertura', 'observar', 'habitar', 'conflito', 'transformacao', 'evolucao', 'dado', 'informacao', 'conhecimento', 'educacao', 'pedagogia', 'aprendizagem', 'cultura', 'arte', 'estetica', 'comunidade', 'ecossistema'],
    
    'fr' => ['ontologie', 'ontologique', 'processus', 'systeme', 'plateforme', 'tracabilite', 'journal', 'empreinte', 'presence', 'observation', 'reflexion', 'intuition', 'synthese', 'pensee', 'critique', 'individu', 'sujet', 'valeur', 'difference', 'autonomie', 'technologie', 'technique', 'symbolique', 'contenu', 'recit', 'developpement', 'code', 'architecture', 'infrastructure', 'quantique', 'laboratoire', 'uterus', 'gestation', 'emergence', 'agence', 'producteur', 'label', 'centre', 'outil', 'methodologie', 'fondation', 'institution', 'alliance', 'intelligence', 'artificielle', 'miroir', 'autorite', 'temps', 'sante', 'bien-etre', 'neurodivergence', 'seuil', 'ouverture', 'observer', 'habiter', 'conflit', 'transformation', 'evolution', 'donnee', 'information', 'connaissance', 'education', 'pedagogie', 'apprentissage', 'culture', 'art', 'esthetique', 'communaute', 'ecosysteme'],
    
    'de' => ['ontologie', 'ontologisch', 'prozess', 'system', 'plattform', 'ruckverfolgbarkeit', 'protokoll', 'fussabdruck', 'prasenz', 'beobachtung', 'reflexion', 'intuition', 'synthese', 'denken', 'kritisch', 'individuum', 'subjekt', 'wert', 'unterschied', 'autonomie', 'technologie', 'technisch', 'symbolisch', 'inhalt', 'erzahlung', 'entwicklung', 'code', 'architektur', 'infrastruktur', 'quanten', 'labor', 'gebarmutter', 'schwangerschaft', 'entstehung', 'agentur', 'produzent', 'label', 'zentrum', 'werkzeug', 'methodik', 'stiftung', 'institution', 'allianz', 'intelligenz', 'kunstlich', 'spiegel', 'autoritat', 'zeit', 'gesundheit', 'wohlbefinden', 'neurodivergenz', 'schwelle', 'offnung', 'beobachten', 'bewohnen', 'konflikt', 'transformation', 'evolution', 'daten', 'information', 'wissen', 'bildung', 'padagogik', 'lernen', 'kultur', 'kunst', 'asthetik', 'gemeinschaft', 'okosystem'],
    
    'it' => ['ontologia', 'ontologico', 'processo', 'sistema', 'piattaforma', 'tracciabilita', 'registro', 'impronta', 'presenza', 'osservazione', 'riflessione', 'intuizione', 'sintesi', 'pensiero', 'critico', 'individuo', 'soggetto', 'valore', 'differenza', 'autonomia', 'tecnologia', 'tecnico', 'simbolico', 'contenuto', 'narrazione', 'sviluppo', 'codice', 'architettura', 'infrastruttura', 'quantistico', 'laboratorio', 'utero', 'gestazione', 'emergenza', 'agenzia', 'produttore', 'etichetta', 'centro', 'strumento', 'metodologia', 'fondazione', 'istituzione', 'alleanza', 'intelligenza', 'artificiale', 'specchio', 'autorita', 'tempo', 'salute', 'benessere', 'neurodivergenza', 'soglia', 'apertura', 'osservare', 'abitare', 'conflitto', 'trasformazione', 'evoluzione', 'dato', 'informazione', 'conoscenza', 'educazione', 'pedagogia', 'apprendimento', 'cultura', 'arte', 'estetica', 'comunita', 'ecosistema'],
    
    'ru' => ['ontologiya', 'protsess', 'sistema', 'platforma', 'prosledzhivayemost', 'zhurnal', 'sled', 'prisutstviye', 'nablyudeniyе', 'razmyshleniye', 'intuitsiya', 'sintez', 'myshleniye', 'kriticheskiy', 'individ', 'subyekt', 'tsennost', 'razlichiye', 'avtonomiya', 'tekhnologiya', 'tekhnicheskiy', 'simvolicheskiy', 'soderzhaniye', 'povestvovaniye', 'razvitiye', 'kod', 'arkhitektura', 'infrastruktura', 'kvantovyy', 'laboratoriya', 'agentstvo', 'proizvoditel', 'tsentr', 'instrument', 'metodologiya', 'fond', 'institut', 'intellekt', 'iskusstvennyy', 'zerkalo', 'avtoritet', 'vremya', 'zdorovye', 'blagopoluchiye', 'porog', 'otkrytiye', 'nablyudat', 'konflikt', 'transformatsiya', 'evolyutsiya', 'dannyye', 'informatsiya', 'znaniye', 'obrazovaniye', 'kultura', 'iskusstvo', 'soobshchestvo'],
    
    'ko' => ['jonjaeron', 'gwa jeong', 'siseutem', 'peullaes', 'chujeokganeungseong', 'ilji', 'heunjeok', 'jonjae', 'gwanchal', 'seongchal', 'jikgwan', 'jonghab', 'sago', 'bipan-jeok', 'gaein', 'juche', 'gachi', 'chai', 'jayulseong', 'gisul', 'gisul-jeok', 'sangjing-jeok', 'naeyong', 'seosa', 'gaebal', 'kodeu', 'geonchuk', 'inpeura', 'yangja', 'heomsil', 'jagung', 'tansaeng', 'deungjang', 'daeriin', 'saengsanja', 'reibeul', 'senteo', 'dogu', 'bangbeoblon', 'jaedan', 'gigwan', 'dongmaeng', 'jineung', 'ingong', 'geo-ul', 'gwonwi', 'sigan', 'geongang', 'welbing', 'singyeongdayangseong', 'munkeo', 'gaebang', 'gwanchalhada', 'geojuhada', 'galtung', 'byeonhwa', 'jinhwa', 'deiteo', 'jeongbo', 'jisik', 'gyoyuk', 'gyoyukhak', 'hakseub', 'munhwa', 'yesul', 'mihak', 'gongdongche', 'saengtaegye'],
    
    'zh' => ['bentilun', 'benti', 'guocheng', 'xitong', 'pingtai', 'ke zhusuxing', 'rizhi', 'henji', 'cunzai', 'guancha', 'fanshi', 'zhijue', 'zonghe', 'siwei', 'pipanxing', 'geti', 'zhuti', 'jiazhi', 'chayi', 'zizhuxing', 'jishu', 'jishude', 'xiangzhengxing', 'neirong', 'xushi', 'fazhan', 'daima', 'jiagou', 'jichusheshi', 'liangzi', 'shiyan', 'zigong', 'yunyu', 'yongxian', 'daili', 'shengchanzhe', 'biaoqian', 'zhongxin', 'gongju', 'fangfalun', 'jijinhui', 'jigou', 'tongmeng', 'zhineng', 'rengong', 'jingzi', 'quanwei', 'shijian', 'jiankang', 'fuzhi', 'shenjingduoyangxing', 'menkan', 'kaifang', 'guancha', 'juzhu', 'chongtu', 'zhuanhua', 'yanhua', 'shuju', 'xinxi', 'zhishi', 'jiaoyu', 'jiaoyuxue', 'xuexi', 'wenhua', 'yishu', 'meixue', 'shequ', 'shengtaixitong'],
    
    'ja' => ['sonzairon', 'sonzaironteki', 'purosesu', 'shisutemu', 'purattofomu', 'tsuisekikanousei', 'kiroku', 'konseki', 'sonzai', 'kansatsu', 'seisatsu', 'chokkan', 'sougou', 'shikou', 'hihanteki', 'kojin', 'shutai', 'kachi', 'sai', 'jiritsusei', 'gijutsu', 'gijutsuteki', 'shouchouteki', 'naiyou', 'monogatari', 'kaihatsu', 'koodo', 'kenchiku', 'infura', 'ryoushi', 'jikken', 'shikyuu', 'tai sei', 'souhatsu', 'dairi', 'seisansha', 'raberu', 'sentaa', 'tsuuru', 'houhouron', 'zaidan', 'kikan', 'doumei', 'chinou', 'jinkouteki', 'kagami', 'keni', 'jikan', 'kenkou', 'fukushi', 'shinkeitayousei', 'ikichi', 'kaihou', 'kansatsu', 'sumu', 'katto', 'henyou', 'shinka', 'deeta', 'jouhou', 'chishiki', 'kyouiku', 'kyouikugaku', 'gakushuu', 'bunka', 'geijutsu', 'bigaku', 'kyoudoutai', 'seitaikei'],
    
    'da' => ['ontologi', 'ontologisk', 'proces', 'system', 'platform', 'sporbarhed', 'logbog', 'aftryk', 'tilstedevarelse', 'observation', 'refleksion', 'intuition', 'syntese', 'taenkning', 'kritisk', 'individ', 'subjekt', 'vaerdi', 'forskel', 'autonomi', 'teknologi', 'teknisk', 'symbolsk', 'indhold', 'fortaelling', 'udvikling', 'kode', 'arkitektur', 'infrastruktur', 'kvante', 'laboratorium', 'livmoder', 'svangerskab', 'fremkomst', 'agentur', 'producent', 'maerke', 'center', 'vaerktoj', 'metodologi', 'fond', 'institution', 'alliance', 'intelligens', 'kunstig', 'spejl', 'autoritet', 'tid', 'sundhed', 'trivsel', 'neurodiversitet', 'taerskel', 'aábning', 'observere', 'bebo', 'konflikt', 'transformation', 'evolution', 'data', 'information', 'viden', 'uddannelse', 'paedagogik', 'laering', 'kultur', 'kunst', 'aestetik', 'faellesskab', 'okosystem'],
    
    'sv' => ['ontologi', 'ontologisk', 'process', 'system', 'plattform', 'sparbarhet', 'loggbok', 'avtryck', 'narvaro', 'observation', 'reflektion', 'intuition', 'syntes', 'tankande', 'kritisk', 'individ', 'subjekt', 'varde', 'skillnad', 'autonomi', 'teknologi', 'teknisk', 'symbolisk', 'innehall', 'berattelse', 'utveckling', 'kod', 'arkitektur', 'infrastruktur', 'kvant', 'laboratorium', 'livmoder', 'graviditet', 'framvaxt', 'byra', 'producent', 'etikett', 'centrum', 'verktyg', 'metodik', 'stiftelse', 'institution', 'allians', 'intelligens', 'artificiell', 'spegel', 'auktoritet', 'tid', 'halsa', 'valbefinnande', 'neurodiversitet', 'troskel', 'oppning', 'observera', 'bo', 'konflikt', 'transformation', 'evolution', 'data', 'information', 'kunskap', 'utbildning', 'pedagogik', 'larande', 'kultur', 'konst', 'estetik', 'gemenskap', 'ekosystem'],
    
    'fi' => ['ontologia', 'ontologinen', 'prosessi', 'jarjestelma', 'alusta', 'jaljitettävyys', 'loki', 'jalki', 'lasnäolo', 'havainto', 'reflektio', 'intuitio', 'synteesi', 'ajattelu', 'kriittinen', 'yksilo', 'subjekti', 'arvo', 'ero', 'autonomia', 'teknologia', 'tekninen', 'symbolinen', 'sisalto', 'kertomus', 'kehitys', 'koodi', 'arkkitehtuuri', 'infrastruktuuri', 'kvantti', 'laboratorio', 'kohtu', 'raskaus', 'synty', 'toimija', 'tuottaja', 'tunniste', 'keskus', 'tyokalu', 'metodologia', 'saatio', 'instituutio', 'liittouma', 'alykkyys', 'tekoaly', 'peili', 'auktoriteetti', 'aika', 'terveys', 'hyvinvointi', 'neurodiversiteetti', 'kynnys', 'avaus', 'havainnoida', 'asua', 'konflikti', 'muutos', 'evoluutio', 'data', 'informaatio', 'tieto', 'koulutus', 'pedagogiikka', 'oppiminen', 'kulttuuri', 'taide', 'estetiikka', 'yhteiso', 'ekosysteemi'],
    
    'is' => ['fraedi', 'fraedilegur', 'ferli', 'kerfi', 'pallur', 'rekjanleiki', 'annall', 'fotspor', 'vera', 'athugun', 'hugsun', 'innsaei', 'samsetning', 'gagnrynin', 'einstaklingur', 'vidfangsefni', 'gildi', 'munur', 'sjalfstaedi', 'taekni', 'taeknilegur', 'taeknraenn', 'efni', 'saga', 'throun', 'kodi', 'arkitektur', 'innvidir', 'skammta', 'rannsoknarstofa', 'leg', 'medganga', 'uppruni', 'umbod', 'framleidandi', 'merki', 'midstod', 'verkfaeri', 'adferdafraedi', 'sjodur', 'stofnun', 'bandalag', 'greind', 'gervigreind', 'speglill', 'vald', 'timi', 'heilsa', 'velferd', 'taugafraedilegur', 'throskuldur', 'opnun', 'athuga', 'bua', 'deila', 'umbreyting', 'throun', 'gogn', 'upplysingar', 'thekking', 'menntun', 'uppeldi', 'nam', 'menning', 'list', 'fagurfraedi', 'samfelag', 'vistkerfi'],
    
    'no' => ['ontologi', 'ontologisk', 'prosess', 'system', 'plattform', 'sporbarhet', 'logg', 'avtrykk', 'tilstedevarelse', 'observasjon', 'refleksjon', 'intuisjon', 'syntese', 'tenkning', 'kritisk', 'individ', 'subjekt', 'verdi', 'forskjell', 'autonomi', 'teknologi', 'teknisk', 'symbolsk', 'innhold', 'fortelling', 'utvikling', 'kode', 'arkitektur', 'infrastruktur', 'kvante', 'laboratorium', 'livmor', 'svangerskap', 'fremvekst', 'byra', 'produsent', 'etikett', 'senter', 'verktoy', 'metodikk', 'stiftelse', 'institusjon', 'allianse', 'intelligens', 'kunstig', 'speil', 'autoritet', 'tid', 'helse', 'velvare', 'neurodiversitet', 'terskel', 'apning', 'observere', 'bebo', 'konflikt', 'transformasjon', 'evolusjon', 'data', 'informasjon', 'kunnskap', 'utdanning', 'pedagogikk', 'laering', 'kultur', 'kunst', 'estetikk', 'fellesskap', 'okosystem'],
    
    'nl' => ['ontologie', 'ontologisch', 'proces', 'systeem', 'platform', 'traceerbaarheid', 'logboek', 'voetafdruk', 'aanwezigheid', 'observatie', 'reflectie', 'intuitie', 'synthese', 'denken', 'kritisch', 'individu', 'subject', 'waarde', 'verschil', 'autonomie', 'technologie', 'technisch', 'symbolisch', 'inhoud', 'verhaal', 'ontwikkeling', 'code', 'architectuur', 'infrastructuur', 'kwantum', 'laboratorium', 'baarmoeder', 'zwangerschap', 'opkomst', 'agentschap', 'producent', 'label', 'centrum', 'gereedschap', 'methodologie', 'stichting', 'instelling', 'alliantie', 'intelligentie', 'kunstmatig', 'spiegel', 'autoriteit', 'tijd', 'gezondheid', 'welzijn', 'neurodiversiteit', 'drempel', 'opening', 'observeren', 'bewonen', 'conflict', 'transformatie', 'evolutie', 'gegevens', 'informatie', 'kennis', 'onderwijs', 'pedagogiek', 'leren', 'cultuur', 'kunst', 'esthetiek', 'gemeenschap', 'ecosysteem']
];

// STOPWORDS SIMPLIFICADAS
$stopwords = [
    'es' => ['el', 'la', 'los', 'las', 'un', 'una', 'de', 'del', 'al', 'en', 'con', 'por', 'para', 'que', 'y', 'o', 'es', 'son', 'se', 'su', 'sus', 'mi', 'mis', 'tu', 'tus', 'me', 'te', 'le', 'les', 'nos', 'os', 'como', 'mas', 'sin', 'sobre', 'entre', 'no', 'si', 'pero', 'porque', 'cuando', 'donde', 'quien', 'cual', 'este', 'esta', 'estos', 'estas', 'ese', 'esa', 'esos', 'esas', 'muy', 'tan', 'tanto', 'poco', 'mucho', 'mas', 'menos', 'ya', 'aun', 'todavia', 'siempre', 'nunca', 'jamas', 'tambien', 'tampoco', 'solo', 'solamente', 'incluso', 'ademas', 'aunque', 'mientras', 'durante', 'despues', 'antes', 'luego', 'entonces', 'asi', 'bien', 'mal', 'mejor', 'peor', 'bueno', 'malo', 'grande', 'pequeno', 'nuevo', 'viejo', 'primero', 'ultimo', 'otro', 'otra', 'otros', 'otras', 'mismo', 'misma', 'mismos', 'mismas', 'cada', 'todo', 'toda', 'todos', 'todas', 'algun', 'alguna', 'algunos', 'algunas', 'ningun', 'ninguna', 'varios', 'varias'],
    
    'en' => ['the', 'a', 'an', 'and', 'or', 'but', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'must', 'shall', 'can', 'to', 'of', 'in', 'for', 'on', 'with', 'at', 'by', 'from', 'as', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'between', 'under', 'again', 'further', 'then', 'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 'than', 'too', 'very', 'just', 'also', 'now', 'about', 'which', 'this', 'that', 'these', 'those', 'i', 'you', 'he', 'she', 'it', 'we', 'they', 'what', 'who', 'whom', 'my', 'your', 'his', 'her', 'its', 'our', 'their'],
    
    'pt' => ['o', 'a', 'os', 'as', 'um', 'uma', 'de', 'do', 'da', 'dos', 'das', 'em', 'no', 'na', 'nos', 'nas', 'por', 'para', 'com', 'sem', 'sobre', 'entre', 'que', 'e', 'ou', 'mas', 'porque', 'quando', 'como', 'onde', 'quem', 'qual', 'este', 'esta', 'estes', 'estas', 'esse', 'essa', 'esses', 'essas', 'muito', 'muita', 'muitos', 'muitas', 'pouco', 'pouca', 'poucos', 'poucas', 'mais', 'menos', 'ja', 'ainda', 'sempre', 'nunca', 'tambem', 'bem', 'mal', 'melhor', 'pior', 'bom', 'boa', 'bons', 'boas', 'grande', 'grandes', 'pequeno', 'pequena', 'pequenos', 'pequenas', 'novo', 'nova', 'novos', 'novas', 'velho', 'velha', 'primeiro', 'primeira', 'ultimo', 'ultima', 'outro', 'outra', 'outros', 'outras', 'mesmo', 'mesma', 'mesmos', 'mesmas', 'cada', 'todo', 'toda', 'todos', 'todas', 'algum', 'alguma', 'alguns', 'algumas', 'nenhum', 'nenhuma', 'varios', 'varias'],
    
    'fr' => ['le', 'la', 'les', 'un', 'une', 'des', 'de', 'du', 'en', 'dans', 'au', 'aux', 'par', 'pour', 'avec', 'sans', 'sur', 'entre', 'que', 'et', 'ou', 'mais', 'parce', 'quand', 'comme', 'ou', 'qui', 'quel', 'quelle', 'ce', 'cet', 'cette', 'ces', 'mon', 'ma', 'mes', 'ton', 'ta', 'tes', 'son', 'sa', 'ses', 'notre', 'nos', 'votre', 'vos', 'leur', 'leurs', 'tres', 'plus', 'moins', 'aussi', 'deja', 'encore', 'toujours', 'jamais', 'maintenant', 'alors', 'donc', 'puis', 'apres', 'avant', 'pendant', 'depuis', 'bien', 'mal', 'mieux', 'pire', 'bon', 'bonne', 'bons', 'bonnes', 'grand', 'grande', 'grands', 'grandes', 'petit', 'petite', 'petits', 'petites', 'nouveau', 'nouvelle', 'nouveaux', 'nouvelles', 'vieux', 'vieille', 'premier', 'premiere', 'dernier', 'derniere', 'autre', 'autres', 'meme', 'memes', 'chaque', 'tout', 'toute', 'tous', 'toutes', 'quelque', 'quelques', 'aucun', 'aucune', 'plusieurs'],
    
    'de' => ['der', 'die', 'das', 'ein', 'eine', 'von', 'zu', 'mit', 'bei', 'nach', 'aus', 'auf', 'an', 'in', 'fur', 'uber', 'unter', 'zwischen', 'und', 'oder', 'aber', 'weil', 'dass', 'wenn', 'wie', 'wo', 'wer', 'was', 'dieser', 'diese', 'dieses', 'mein', 'meine', 'dein', 'deine', 'sein', 'seine', 'ihr', 'ihre', 'unser', 'unsere', 'sehr', 'mehr', 'weniger', 'auch', 'schon', 'noch', 'immer', 'nie', 'niemals', 'jetzt', 'dann', 'also', 'so', 'gut', 'schlecht', 'besser', 'schlechter', 'gross', 'grosse', 'klein', 'kleine', 'neu', 'neue', 'alt', 'alte', 'erste', 'letzte', 'andere', 'selbe', 'eigene', 'jede', 'alle', 'einige', 'mehrere', 'viele', 'wenige'],
    
    'it' => ['il', 'la', 'i', 'le', 'un', 'una', 'di', 'del', 'della', 'dei', 'delle', 'in', 'nel', 'nella', 'nei', 'nelle', 'con', 'senza', 'su', 'tra', 'fra', 'per', 'da', 'a', 'che', 'e', 'o', 'ma', 'perche', 'quando', 'come', 'dove', 'chi', 'quale', 'quali', 'questo', 'questa', 'questi', 'queste', 'quel', 'quella', 'quei', 'quelle', 'mio', 'mia', 'miei', 'mie', 'tuo', 'tua', 'tuoi', 'tue', 'suo', 'sua', 'suoi', 'sue', 'nostro', 'nostra', 'nostri', 'nostre', 'loro', 'molto', 'molta', 'molti', 'molte', 'poco', 'poca', 'pochi', 'poche', 'piu', 'meno', 'gia', 'ancora', 'sempre', 'mai', 'adesso', 'ora', 'allora', 'cosi', 'bene', 'male', 'meglio', 'peggio', 'buono', 'buona', 'buoni', 'buone', 'grande', 'grandi', 'piccolo', 'piccola', 'piccoli', 'piccole', 'nuovo', 'nuova', 'nuovi', 'nuove', 'vecchio', 'vecchia', 'primo', 'prima', 'ultimo', 'ultima', 'altro', 'altra', 'altri', 'altre', 'stesso', 'stessa', 'stessi', 'stesse', 'ogni', 'tutto', 'tutta', 'tutti', 'tutte', 'qualche', 'alcuni', 'alcune', 'nessuno', 'nessuna'],
    
    'ru' => ['в', 'и', 'не', 'на', 'с', 'по', 'за', 'что', 'как', 'так', 'то', 'все', 'это', 'быть', 'был', 'была', 'было', 'были', 'он', 'она', 'оно', 'они', 'мы', 'вы', 'я', 'ты', 'его', 'ее', 'их', 'наш', 'ваш', 'мой', 'твой', 'этот', 'тот', 'эти', 'тех', 'из', 'от', 'до', 'для', 'при', 'под', 'над', 'перед', 'после', 'между', 'через', 'без', 'о', 'об', 'но', 'или', 'если', 'когда', 'где', 'кто', 'который', 'какой', 'такой', 'сам', 'самый', 'уже', 'еще', 'тоже', 'также', 'даже', 'ведь', 'лишь', 'только', 'почти', 'совсем', 'очень', 'более', 'менее', 'всегда', 'никогда', 'иногда', 'часто', 'редко', 'сейчас', 'потом', 'затем', 'после', 'перед', 'раньше', 'позже', 'сначала', 'хороший', 'плохой', 'большой', 'маленький', 'новый', 'старый', 'первый', 'последний', 'другой', 'другие', 'разные'],
    
    'ko' => ['은', '는', '이', '가', '을', '를', '에', '의', '로', '으로', '와', '과', '에서', '까지', '부터', '도', '만', '한', '두', '세', '네', '다섯', '여섯', '일곱', '여덟', '아', '열', '백', '천', '만', '해', '년', '월', '일', '시', '분', '초', '중', '고', '대', '소', '신', '구', '도', '부', '면', '리', '동', '가', '로', '길', '거리', '통', '마을', '읍', '시', '군', '구', '층', '호', '번지', '아파트', '빌라', '주택', '상가', '빌딩', '타워', '센터', '플라자', '스퀘어', '몰', '마켓', '스토어', '숍', '브랜드', '제품', '상품', '서비스', '고객', '사용자', '회원', '아이디', '비밀번호', '이메일', '전화번호', '주소', '이름', '성별', '나이', '생일'],
    
    'zh' => ['的', '了', '在', '是', '我', '不', '有', '和', '就', '都', '而', '及', '与', '着', '或', '一个', '这个', '那个', '没有', '可以', '可能', '应该', '如果', '因为', '所以', '但是', '而且', '虽然', '可是', '既然', '由于', '因此', '因而', '从而', '进而', '于是', '然后', '接着', '随后', '最后', '最终', '首先', '其次', '再次', '另外', '此外', '总之', '总而言之', '总的来说', '一般', '通常', '经常', '常常', '总是', '老是', '一直', '始终', '永远', '从来', '从未', '决不', '绝不', '一定', '必定', '必然', '肯定', '也许', '或许', '大概', '大约', '左右', '上下', '前后', '里外', '内外', '中间', '之间', '之中', '之内', '之外', '以外', '以上', '以下', '之前', '之后', '以前', '以后', '从前', '现在', '目前', '当前', '当今', '如今', '现今', '今日', '今天', '明天', '后天', '昨天', '前天', '上周', '下周', '上月', '下月', '去年', '明年', '前年', '后年', '今年', '本年'],
    
    'ja' => ['の', 'に', 'は', 'を', 'た', 'が', 'で', 'て', 'と', 'し', 'れ', 'さ', 'ある', 'いる', 'も', 'する', 'から', 'な', 'こと', 'として', 'い', 'や', 'れる', 'など', 'なっ', 'ない', 'この', 'ため', 'その', 'あっ', 'よう', 'また', 'もの', 'という', 'あり', 'まで', 'られ', 'なる', 'へ', 'か', 'だ', 'これ', 'によって', 'により', 'おり', 'より', 'による', 'ず', 'なり', 'られる', 'において', 'ば', 'なかっ', 'なく', 'しかし', 'について', 'せ', 'だっ', 'その後', 'できる', 'それ', 'う', 'ので', 'なお', 'のみ', 'でき', 'き', 'つ', 'における', 'および', 'いう', 'さらに', 'でも', 'ら', 'たり', 'その他', 'に関する', 'たち', 'ます', 'ん', 'なら', 'に対して', '特に', 'せる', '及び', 'これら', 'とき', 'では', 'にて', 'ほか', 'ながら', 'うち', 'そして', 'とともに', 'ただし', 'かつて', 'それぞれ', 'または', 'お', 'ほど', 'ものの', 'に対する', 'ほとんど', 'と共に', 'なお', 'のみ', 'その他', '場合', 'いくつか', 'に対して', '特に', 'できるだけ', 'に関する', 'について', 'といった', 'ただし', 'いう', 'なお', 'のみ', 'ほか', 'べき', 'て', 'は', 'も', 'の', 'に', 'を', 'と', 'が', 'で', 'す', 'る', 'だ', 'れ', 'た', 'い', 'う', 'え', 'お', 'か', 'き', 'く', 'け', 'こ', 'さ', 'し', 'す', 'せ', 'そ', 'ざ', 'じ', 'ず', 'ぜ', 'ぞ', 'だ', 'ぢ', 'づ', 'で', 'ど', 'た', 'ち', 'つ', 'て', 'と', 'な', 'に', 'ぬ', 'ね', 'の', 'は', 'ひ', 'ふ', 'へ', 'ほ', 'ま', 'み', 'む', 'め', 'も', 'や', 'ゆ', 'よ', 'ら', 'り', 'る', 'れ', 'ろ', 'わ', 'を', 'ん', 'あ', 'い', 'う', 'え', 'お'],
    
    'da' => ['og', 'i', 'en', 'det', 'er', 'som', 'pa', 'den', 'til', 'af', 'for', 'med', 'ikke', 'der', 'var', 'han', 'har', 'et', 'fra', 'hun', 'sig', 'men', 'have', 'også', 'sin', 'skal', 'ham', 'over', 'ved', 'om', 'hvad', 'ud', 'så', 'kan', 'sine', 'efter', 'når', 'blive', 'være', 'bliver', 'var', 'blev', 'god', 'godt', 'gode', 'stor', 'store', 'lille', 'små', 'nye', 'gamle', 'første', 'sidste', 'anden', 'andre', 'samme', 'egen', 'hver', 'alle', 'nogle', 'flere', 'mange', 'få'],
    
    'sv' => ['och', 'i', 'en', 'det', 'är', 'som', 'på', 'den', 'till', 'av', 'för', 'med', 'inte', 'der', 'var', 'han', 'har', 'ett', 'från', 'hon', 'sig', 'men', 'ha', 'också', 'sin', 'ska', 'honom', 'över', 'vid', 'om', 'vad', 'ut', 'så', 'kan', 'sina', 'efter', 'när', 'bli', 'vara', 'blir', 'var', 'blev', 'god', 'gott', 'goda', 'stor', 'stora', 'liten', 'små', 'nya', 'gamla', 'första', 'sista', 'andra', 'andre', 'samma', 'egen', 'varje', 'alla', 'några', 'flera', 'många', 'få'],
    
    'fi' => ['ja', 'on', 'ei', 'se', 'että', 'oli', 'kuin', 'minä', 'hän', 'tämä', 'olla', 'sinä', 'mutta', 'kun', 'mitä', 'voi', 'hänen', 'kanssa', 'myös', 'sekä', 'oli', 'itse', 'voi', 'sekä', 'joka', 'niin', 'sillä', 'jos', 'vain', 'nyt', 'kaikki', 'uusi', 'vanha', 'pieni', 'suuri', 'ensimmäinen', 'viimeinen', 'toinen', 'muu', 'sama', 'jokin', 'joku', 'jotkut', 'kaikki', 'monet', 'useat', 'harvat', 'hyvä', 'huono', 'parempi', 'huonompi', 'paras', 'huonoin'],
    
    'is' => ['og', 'i', 'er', 'sem', 'að', 'hann', 'hún', 'það', 'var', 'á', 'til', 'af', 'með', 'ekki', 'var', 'hafa', 'eða', 'en', 'þeir', 'þær', 'þau', 'getur', 'geta', 'fyrir', 'frá', 'við', 'um', 'að', 'þá', 'því', 'hvernig', 'hvað', 'hver', 'hverjar', 'hverjir', 'þessi', 'þetta', 'þessar', 'þessir', 'minn', 'mín', 'þinn', 'þín', 'sinn', 'sín', 'okkar', 'ykkar', 'þeirra', 'mjög', 'meira', 'minna', 'líka', 'einnig', 'alltaf', 'aldrei', 'stundum', 'oft', 'sjaldan', 'nú', 'þá', 'síðan', 'áður', 'fyrst', 'síðast', 'annar', 'aðrir', 'sami', 'sömu', 'eiginn', 'eigin', 'hver', 'allir', 'allar', 'allt', 'nokkrir', 'nokkrar', 'nokkurt', 'margir', 'margar', 'fáir', 'færri', 'góður', 'góð', 'slæmur', 'slæm', 'betri', 'verri', 'bestur', 'verstur', 'stór', 'stórt', 'lítill', 'lítið', 'nýr', 'nýtt', 'gamall', 'gamalt', 'fyrsti', 'síðasti'],
    
    'no' => ['og', 'i', 'en', 'det', 'er', 'som', 'på', 'den', 'til', 'av', 'for', 'med', 'ikke', 'der', 'var', 'han', 'har', 'ett', 'fra', 'hun', 'seg', 'men', 'ha', 'også', 'sin', 'skal', 'ham', 'over', 'ved', 'om', 'hvad', 'ut', 'så', 'kan', 'sine', 'etter', 'når', 'bli', 'være', 'blir', 'var', 'ble', 'god', 'godt', 'gode', 'stor', 'store', 'liten', 'små', 'ny', 'nye', 'gammel', 'gamle', 'første', 'siste', 'annen', 'andre', 'samme', 'egen', 'hver', 'alle', 'noen', 'flere', 'mange', 'få'],
    
    'nl' => ['de', 'het', 'een', 'en', 'van', 'in', 'is', 'dat', 'die', 'op', 'te', 'met', 'voor', 'als', 'bij', 'aan', 'was', 'zijn', 'hem', 'haar', 'hun', 'wij', 'jullie', 'zij', 'ik', 'jij', 'hij', 'ze', 'er', 'niet', 'maar', 'of', 'om', 'wat', 'wie', 'waar', 'wanneer', 'hoe', 'deze', 'deze', 'dit', 'dat', 'die', 'mijn', 'jouw', 'zijn', 'haar', 'ons', 'jullie', 'hun', 'heel', 'meer', 'minder', 'ook', 'al', 'nog', 'altijd', 'nooit', 'soms', 'vaak', 'zelden', 'nu', 'dan', 'later', 'eerder', 'eerst', 'laatst', 'ander', 'andere', 'zelfde', 'eigen', 'elk', 'alle', 'enkele', 'sommige', 'veel', 'weinig', 'goed', 'slecht', 'beter', 'slechter', 'best', 'slechtst', 'groot', 'grote', 'klein', 'kleine', 'nieuw', 'nieuwe', 'oud', 'oude', 'eerste', 'laatste']
];

// DETECCIÓN DE IDIOMA
function detectarIdioma($texto, $stopwords) {
    $texto = mb_strtolower($texto, 'UTF-8');
    $palabras = preg_split('/\s+/', $texto);
    
    $puntajes = [];
    foreach ($stopwords as $idioma => $lista) {
        $puntajes[$idioma] = 0;
        foreach ($palabras as $palabra) {
            if (in_array($palabra, $lista)) {
                $puntajes[$idioma]++;
            }
        }
    }
    
    arsort($puntajes);
    $mejorIdioma = key($puntajes);
    
    if ($puntajes[$mejorIdioma] < count($palabras) * 0.05) {
        return 'desconocido';
    }
    
    return $mejorIdioma;
}

// ANÁLISIS SEMÁNTICO MULTILINGÜE - CORREGIDO
function analisisSemanticoMultilingue($texto, $diccionariosDominio, $stopwords) {
    $idioma = detectarIdioma($texto, $stopwords);
    
    $diccionario = $diccionariosDominio[$idioma] ?? $diccionariosDominio['es'];
    $stopwordsIdioma = $stopwords[$idioma] ?? $stopwords['es'];
    
    $textoNormalizado = mb_strtolower($texto, 'UTF-8');
    
    // CORRECCIÓN: Regex compatible con PCRE - captura letras unicode
    preg_match_all('/[\p{L}\p{N}]+/u', $textoNormalizado, $palabras);
    $palabras = $palabras[0] ?? [];
    
    // Filtrar palabras
    $palabrasFiltradas = array_filter($palabras, function($p) use ($stopwordsIdioma) {
        return !in_array($p, $stopwordsIdioma) && strlen($p) > 3;
    });
    
    if (empty($palabrasFiltradas)) {
        $palabrasFiltradas = [];
    }
    
    $frecuencia = array_count_values($palabrasFiltradas);
    arsort($frecuencia);
    
    $terminosEncontrados = [];
    foreach ($diccionario as $termino) {
        if (stripos($textoNormalizado, $termino) !== false) {
            $terminosEncontrados[] = $termino;
        }
    }
    
    $conceptos = array_unique(array_merge(
        array_slice($terminosEncontrados, 0, 5),
        array_slice(array_keys($frecuencia), 0, 5)
    ));
    $conceptos = array_slice($conceptos, 0, 7);
    
    $oraciones = preg_split('/[.!?]+/', $texto);
    $resumen = trim($oraciones[0] ?? substr($texto, 0, 150)) . '.';
    
    return [
        'idioma_detectado' => $idioma,
        'conceptos' => $conceptos,
        'palabras_clave' => array_slice(array_keys($frecuencia), 0, 7),
        'terminos_dominio' => $terminosEncontrados,
        'resumen' => $resumen,
        'total_palabras' => count($palabras),
        'total_filtradas' => count($palabras) - count($palabrasFiltradas)
    ];
}

// EJECUCIÓN PRINCIPAL
try {
    function leerJSON($archivo) {
        if (!file_exists($archivo)) {
            return [];
        }
        return json_decode(file_get_contents($archivo), true) ?? [];
    }
    
    $bitacora = leerJSON($bitacoraFile);
    
    if (empty($bitacora)) {
        echo json_encode(['success' => false, 'mensaje' => 'No hay bitácoras'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $resultados = [];
    $idiomasDetectados = [];
    
    foreach ($bitacora as $entrada) {
        $titulo = $entrada['titulo'] ?? '';
        $contenido = $entrada['contenido'] ?? '';
        $textoCompleto = $titulo . "\n\n" . $contenido;
        
        $analisis = analisisSemanticoMultilingue($textoCompleto, $diccionariosDominio, $stopwords);
        
        $idioma = $analisis['idioma_detectado'];
        if (!isset($idiomasDetectados[$idioma])) {
            $idiomasDetectados[$idioma] = 0;
        }
        $idiomasDetectados[$idioma]++;
        
        $normalizado = [
            'id' => $entrada['id'],
            'titulo' => $titulo,
            'fecha_original' => $entrada['fecha'] ?? date('Y-m-d H:i:s'),
            'tipo_pensamiento_original' => $entrada['tipo_pensamiento'] ?? 'general',
            'analisis' => $analisis,
            'metadata' => [
                'proceso' => $entrada['proceso'] ?? 'general',
                'categoria' => $entrada['categoria'] ?? 'general',
                'sujeto' => $entrada['sujeto'] ?? 'anonimo',
                'idioma' => $idioma
            ],
            'relaciones' => $entrada['relacionado_a'] ?? [],
            'fecha_procesamiento' => date('Y-m-d H:i:s'),
            'version_agente' => '3.0.1-multilingual-fix',
            'fuente' => 'bitacora'
        ];
        
        $archivoMemoria = $memoryDir . '/' . $entrada['id'] . '.json';
        file_put_contents($archivoMemoria, json_encode($normalizado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $resultados[] = $normalizado;
    }
    
    echo json_encode([
        'success' => true,
        'mensaje' => 'Normalización multilingüe completada',
        'estadisticas' => [
            'total_entradas' => count($bitacora),
            'procesadas' => count($resultados),
            'idiomas_detectados' => $idiomasDetectados,
            'version' => '3.0.1-multilingual-fix',
            'idiomas_soportados' => array_keys($diccionariosDominio)
        ],
        'resultados' => $resultados
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>