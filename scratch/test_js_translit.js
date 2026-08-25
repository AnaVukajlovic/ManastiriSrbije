const LAT_TO_CYR = {
    'A':'А','B':'Б','V':'В','G':'Г','D':'Д','Đ':'Ђ','E':'Е','Ž':'Ж','Z':'З','I':'И',
    'J':'Ј','K':'К','L':'Л','M':'М','N':'Н','O':'О','P':'П','R':'Р','S':'С','T':'Т',
    'Ć':'Ћ','U':'У','F':'Ф','H':'Х','C':'Ц','Č':'Ч','Š':'Ш',
    'a':'а','b':'б','v':'в','g':'г','d':'д','đ':'ђ','e':'е','ž':'ж','z':'з','i':'и',
    'j':'ј','k':'к','l':'л','m':'м','n':'н','o':'о','p':'п','r':'р','s':'с','t':'т',
    'ć':'ћ','u':'у','f':'ф','h':'х','c':'ц','č':'ч','š':'ш'
};

const CYR_TO_LAT = {
    'А':'A','Б':'B','В':'V','Г':'G','Д':'D','Ђ':'Đ','Е':'E','Ж':'Ž','З':'Z','И':'I',
    'Ј':'J','К':'K','Л':'L','Љ':'Lj','М':'M','Н':'N','Њ':'Nj','О':'O','П':'P','Р':'R',
    'С':'S','Т':'T','Ћ':'Ć','У':'U','Ф':'F','Х':'H','Ц':'C','Ч':'Č','Џ':'Dž','Ш':'Š',
    'а':'a','б':'b','в':'v','г':'g','д':'d','ђ':'đ','е':'e','ж':'ž','з':'z','и':'i',
    'ј':'j','к':'k','л':'l','љ':'lj','м':'m','н':'n','њ':'nj','о':'o','п':'p','р':'r',
    'с':'s','т':'t','ћ':'ć','у':'u','ф':'f','х':'h','ц':'c','ч':'č','џ':'dž','ш':'š'
};

function latToCyr(text) {
    if (!text) return text;
    let result = String(text);
    result = result
        .replace(/Dž/g, 'Џ')
        .replace(/DŽ/g, 'Џ')
        .replace(/dž/g, 'џ')
        .replace(/Lj/g, 'Љ')
        .replace(/LJ/g, 'Љ')
        .replace(/lj/g, 'љ')
        .replace(/Nj/g, 'Њ')
        .replace(/NJ/g, 'Њ')
        .replace(/nj/g, 'њ');
    return result.replace(/[A-Za-zĐđŽžĆćČčŠš]/g, ch => LAT_TO_CYR[ch] || ch);
}

function cyrToLat(text) {
    if (!text) return text;
    return String(text).replace(/[\u0400-\u04FF]/g, ch => CYR_TO_LAT[ch] || ch);
}

function formatLightboxCaption(caption, mode) {
    if (!caption) return '';
    let desc = caption;
    let source = '';

    const htmlMatch = caption.match(/^(.*?)(?:<br\s*\/?>)?\s*<small[^>]*>(?:<em>)?(?:\*|\()?([^*()<>\n]+(?:\.rs|[a-zA-Z0-9\s\.\-_]+))(?:\*|\))?(?:<\/em>)?<\/small>$/i);
    if (htmlMatch) {
        desc = htmlMatch[1].trim();
        source = htmlMatch[2].trim();
    }

    source = source.replace(/^[(*\s]+/, '').replace(/[)*\s]+$/, '').trim();
    if (source && !source.toLowerCase().startsWith('izvor:') && !source.toLowerCase().startsWith('извор:')) {
        source = 'Izvor: ' + source;
    }

    if (mode === 'cyr') {
        desc = latToCyr(desc);
        source = latToCyr(source);
    } else {
        desc = cyrToLat(desc);
        source = cyrToLat(source);
    }

    return `<div class="mon-lightbox__caption-desc">${desc}</div><div class="mon-lightbox__caption-source" style="color: #eab308; font-style: italic; margin-top: 4px;">*${source}*</div>`;
}

const testCaps = [
    'Crkva Svete Trojice u manastiru Bjele Vode kod Ljubovije sa zvonikom i uređenom portom<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>',
    'Pogled kroz kapijski luk na manastirski konak i crkvu Rođenja Presvete Bogorodice u Čokešini<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>'
];

console.log('=== TEST CYRILLIC ===');
testCaps.forEach(c => console.log(formatLightboxCaption(c, 'cyr')));

console.log('\n=== TEST LATIN ===');
testCaps.forEach(c => console.log(formatLightboxCaption(c, 'lat')));
