import sqlite3

zicka_data = [
    {
        "id": 206,
        "image": "images/monasteries/blagovestenje-ovcar.jpg",
        "gallery": [
            ("images/monasteries/blagovestenje-ovcar.jpg", "Crkva Blagoveštenja pod Ovčarom sa kamenim zidovima i drvenim tremom <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/blagovestenje-ovcar_gal_1.jpg", "Pogled na manastirski hram i bujno zelenilo Ovčarsko-kablarske klisure <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/blagovestenje-ovcar_gal_2.jpg", "Manastirski konaci sa tradicionalnim lučnim tremom i belom fasadom <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/blagovestenje-ovcar_gal_3.jpg", "Mermerna spomen-ploča sa istorijatom manastira Blagoveštenje <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 207,
        "image": "images/monasteries/dubrava.jpg",
        "gallery": [
            ("images/monasteries/dubrava.jpg", "Kompleks manastira Dubrava na Zlatiboru u kanjonu Uvca <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/dubrava_gal_1.jpg", "Crkva Svetog Vasilija Ostroškog sa kamenim zvonikom i novim konacima <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/dubrava_gal_2.jpg", "Crkva Svetog Pantelejmona i uređena porta manastira Dubrava <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/dubrava_gal_3.jpg", "Drveni letnjikovac i vidikovac sa pogledom na kanjon reke Uvac <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 208,
        "image": "images/monasteries/godovik.jpg",
        "gallery": [
            ("images/monasteries/godovik.jpg", "Crkva Svetog Đorđa u Godoviku sa konusnim kubetom i kamenom fasadom <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/godovik_gal_1.jpg", "Porta crkve Svetog Đorđa sa kamenim zvonikom okružena šumom <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/godovik_gal_2.jpg", "Visoki kameni zvonik sa lučnim otvorima u Godoviku kod Požege <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/godovik_gal_3.jpg", "Stara kamena spomen-česma u manastirskoj porti u Godoviku <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 209,
        "image": "images/monasteries/gradac.jpg",
        "gallery": [
            ("images/monasteries/gradac.jpg", "Pogled na Bogorodičinu crkvu manastira Gradac kroz kameni ulazni luk <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/gradac_gal_1.jpg", "Monumentalni hram Blagoveštenja, zadužbina kraljice Jelene Anžujske <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/gradac_gal_2.jpg", "Kameni ostaci srednjovekovnih bedema i trpezarije u kompleksu Gradac <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/gradac_gal_3.jpg", "Zapadna fasada crkve sa gotizovanim mermernim portalom <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 210,
        "image": "images/monasteries/ilinje-ovcar.jpg",
        "gallery": [
            ("images/monasteries/ilinje-ovcar.jpg", "Crkva Svetog proroka Ilije na brdu iznad Ovčar Banje <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/ilinje-ovcar_gal_1.jpg", "Hram Svetog Ilije u prirodnom okruženju Ovčarsko-kablarske klisure <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/ilinje-ovcar_gal_2.jpg", "Drveni zvonik na uzvišenju u porti manastira Ilinje <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/ilinje-ovcar_gal_3.jpg", "Manastirski konak i uređena cvetna porta na padini Ovčara <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 211,
        "image": "images/monasteries/isposnica-svetog-save.jpg",
        "gallery": [
            ("images/monasteries/isposnica-svetog-save.jpg", "Kameni portal i ulaz u Gornju isposnicu Svetog Save uklesan u stene Čemerna <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/isposnica-svetog-save_gal_1.jpg", "Kula i kelije Gornje isposnice Svetog Save visoko u liticama iznad Studenice <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/isposnica-svetog-save_gal_2.jpg", "Pogled sa litice na kanjon Studenice i šumovite obronke planine Čemerno <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/isposnica-svetog-save_gal_3.jpg", "Drveni mostić i prilaz pećinskoj crkvi Svetog Đorđa u steni <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 212,
        "image": "images/monasteries/jezevica.jpg",
        "gallery": [
            ("images/monasteries/jezevica.jpg", "Crkva Svetog Nikole u Ježevici podno planine Jelice sa baroknim zvonikom <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/jezevica_gal_1.jpg", "Karakteristična kupola i krovna konstrukcija hrama Svetog Nikole <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/jezevica_gal_2.jpg", "Tradicionalni manastirski konak sa belom fasadom i baštom <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/jezevica_gal_3.jpg", "Stari kameni nadgrobni spomenik (krajputaš) u porti manastira Ježevica <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 213,
        "image": "images/monasteries/jovanje-ovcar-kablar.jpg",
        "gallery": [
            ("images/monasteries/jovanje-ovcar-kablar.jpg", "Hram Rođenja Svetog Jovana Krstitelja u meandru Zapadne Morave <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/jovanje-ovcar-kablar_gal_1.jpg", "Pogled na kupolu crkve i zvonik manastira Jovanje iz cvetne porte <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/jovanje-ovcar-kablar_gal_2.jpg", "Zvonik, konak i pomoćni objekti u manastirskom kompleksu Jovanje <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/jovanje-ovcar-kablar_gal_3.jpg", "Čudotvorna ikona Presvete Bogorodice Brzopomoćnice u manastiru Jovanje <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 214,
        "image": "images/monasteries/klisura.jpg",
        "gallery": [
            ("images/monasteries/klisura.jpg", "Crkva Svetog arhangela Gavrila u manastiru Klisura u Dobračama kod Arilja <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/klisura_gal_1.jpg", "Manastirska porta sa drvenim zvonikom, mostićem i hramom u Klisuri <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/klisura_gal_2.jpg", "Zidna freska u unutrašnjosti hrama Svetog arhangela Gavrila <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/klisura_gal_3.jpg", "Freskopis sa prikazom Svetih otaca i mučenika u manastiru Klisura <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 215,
        "image": "images/monasteries/kovilje.jpg",
        "gallery": [
            ("images/monasteries/kovilje.jpg", "Kompleks manastira Kovilje pod Golijom sa crkvom Svetog Nikole <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/kovilje_gal_1.jpg", "Ulazna kapija i zvonik manastirskog kompleksa Kovilje <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/kovilje_gal_2.jpg", "Čudotvorna ikona Presvete Bogorodice u pećinskoj kapeli u Kovilju <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/kovilje_gal_3.jpg", "Unutrašnjost drevne pećinske crkve Svetih arhangela uzidane u stenu <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 216,
        "image": "images/monasteries/moravci.jpg",
        "gallery": [
            ("images/monasteries/moravci.jpg", "Crkva Rođenja Presvete Bogorodice u manastiru Moravci kod Ljiga <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/moravci_gal_1.jpg", "Stari kameni nadgrobni spomenici i spomen-obeležja u porti Moravaca <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/moravci_gal_2.jpg", "Visoki kameni zvonik manastirske crkve u Moravcima <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/moravci_gal_3.jpg", "Pogled na zvonik i krovnu konstrukciju crkve iz porte manastira <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 217,
        "image": "images/monasteries/nikolje-ovcar-kablar.jpg",
        "gallery": [
            ("images/monasteries/nikolje-ovcar-kablar.jpg", "Manastir Nikolje pod Kablarom sa Miloševim konakom i crkvom Svetog Nikole <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/nikolje-ovcar-kablar_gal_1.jpg", "Hram Svetog Nikole i stari drveni bunar u uređenoj porti Nikolja <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/nikolje-ovcar-kablar_gal_2.jpg", "Raskošni rezbareni i oslikani ikonostas crkve Svetog Nikole <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/nikolje-ovcar-kablar_gal_3.jpg", "Očuvani srednjovekovni freskopis u unutrašnjosti hrama manastira Nikolje <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 218,
        "image": "images/monasteries/nova-pavlica.jpg",
        "gallery": [
            ("images/monasteries/nova-pavlica.jpg", "Crkva Vavedenja Presvete Bogorodice u Novoj Pavlici, zadužbina braće Musić <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/nova-pavlica_gal_1.jpg", "Pogled na manastirski hram i kameni zvonik Nove Pavlice u dolini Ibra <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/nova-pavlica_gal_2.jpg", "Unutrašnjost hrama sa ikonostasom i kamenim svodovima <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/nova-pavlica_gal_3.jpg", "Zvonik i travnata porta manastira Nova Pavlica <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 219,
        "image": "images/monasteries/preobrazenje-ovcar-kablar.jpg",
        "gallery": [
            ("images/monasteries/preobrazenje-ovcar-kablar.jpg", "Crkva Preobraženja Gospodnjeg pod Ovčarom sa konacima i portom <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/preobrazenje-ovcar-kablar_gal_1.jpg", "Zvonik na padini unutar manastirskog kompleksa Preobraženje <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/preobrazenje-ovcar-kablar_gal_2.jpg", "Prilazna staza i tabla dobrodošlice na ulazu u manastir Preobraženje <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/preobrazenje-ovcar-kablar_gal_3.jpg", "Unutrašnjost hrama sa ikonostasom, Bogorodičinim tronom i pevnicom <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 220,
        "image": "images/monasteries/pridvorica.jpg",
        "gallery": [
            ("images/monasteries/pridvorica.jpg", "Crkva Preobraženja Gospodnjeg u Pridvorici kod Ivanjice u raškom stilu <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/pridvorica_gal_1.jpg", "Zapadni kameni portal sa lunetom iznad ulaza u manastir Pridvorica <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/pridvorica_gal_2.jpg", "Raskošni rezbareni ikonostas u unutrašnjosti crkve u Pridvorici <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/pridvorica_gal_3.jpg", "Oltarska apsida i klesani kameni zidovi hrama u Pridvorici <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 221,
        "image": "images/monasteries/raca.jpg",
        "gallery": [
            ("images/monasteries/raca.jpg", "Crkva Vaznesenja Hristovog u manastiru Rača kod Bajine Bašte, zadužbina kralja Dragutina <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/raca_gal_1.jpg", "Spomen-česma i natpis u manastirskoj porti manastira Rača <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/raca_gal_2.jpg", "Raskošni visoki ikonostas crkve Vaznesenja Hristovog u Rači <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/raca_gal_3.jpg", "Freskopis sa kompozicijom na svodu unutar hrama u Rači <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 222,
        "image": "images/monasteries/rujan.jpg",
        "gallery": [
            ("images/monasteries/rujan.jpg", "Crkva Svetog Đorđa, visoki zvonik i crveni konaci manastira Rujan kod Bioske <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/rujan_gal_1.jpg", "Monumentalni kameni zvonik manastira Rujan sa drvenom konstrukcijom <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/rujan_gal_2.jpg", "Crkva Svetog Đorđa građena u raškom stilu od kamena i crvene opeke <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/rujan_gal_3.jpg", "Apsida hrama Svetog Đorđa i grobno mesto episkopa Hrizostoma <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 223,
        "image": "images/monasteries/sabor.jpg",
        "gallery": [
            ("images/monasteries/sabor.jpg", "Crkva Sabora Srpskih Svetitelja sa drvenim zvonikom u Bukovici kod Ivanjice <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/sabor_gal_1.jpg", "Ulazna kapija sa drvenim krstom i ogradom manastira u Bukovici <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/sabor_gal_2.jpg", "Spomen-ploča sa natpisom Manastira Sabora Srpskih Svetitelja <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/sabor_gal_3.jpg", "Manastirski konak sa tremom u prostranoj porti manastira <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 224,
        "image": "images/monasteries/savinac.jpg",
        "gallery": [
            ("images/monasteries/savinac.jpg", "Crkva Svetog Save na Savincu kod Takova, zadužbina kneza Miloša Obrenovića iz 1819. godine <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/savinac_gal_1.jpg", "Večernji pogled na osvetljeni hram Svetog Save na Savincu <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/savinac_gal_2.jpg", "Kupola i krovna konstrukcija crkve Svetog Save <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/savinac_gal_3.jpg", "Kompleks Savinca sa crkvom, zvonikom i drvenim letnjikovcem pored reke Dičine <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 225,
        "image": "images/monasteries/sretenje.jpg",
        "gallery": [
            ("images/monasteries/sretenje.jpg", "Crkva Sretenja Gospodnjeg visoko na padinama planine Ovčar sa belom fasadom <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/sretenje_gal_1.jpg", "Zvonik manastira Sretenje sa pogledom na šumovite vrhove Ovčara <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/sretenje_gal_2.jpg", "Spomen-ploča na zidu hrama sa svedočanstvom o obnovi manastira <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/sretenje_gal_3.jpg", "Panoramski pogled na manastirski kompleks Sretenje sa konacima i portom <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 226,
        "image": "images/monasteries/stara-pavlica.jpg",
        "gallery": [
            ("images/monasteries/stara-pavlica.jpg", "Prenemanjićka crkva Svetog Petra u Staroj Pavlici na litici iznad reke Ibar <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/stara-pavlica_gal_1.jpg", "Kamena fasada i očuvana kupola hrama Stara Pavlica <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/stara-pavlica_gal_2.jpg", "Pogled na crkvu Svetog Petra sa padine iznad pruge Kraljevo-Raška <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/stara-pavlica_gal_3.jpg", "Kamena bifora sa lučnim otvorom i zvonom u Staroj Pavlici <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 227,
        "image": "images/monasteries/stubal.jpg",
        "gallery": [
            ("images/monasteries/stubal.jpg", "Crkva Svete Petke u manastiru Stubal kod Kraljeva sa uređenim parkom <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/stubal_gal_1.jpg", "Veliki krst na vidikovcu iznad manastirskog kompleksa Stubal <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/stubal_gal_2.jpg", "Kivot i spomen-obeležje u manastirskoj porti u Stublu <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/stubal_gal_3.jpg", "Čudotvorni Sveti kamen (Stubal) pod natkrivenim kamenim stubovima <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 228,
        "image": "images/monasteries/studenica.jpg",
        "gallery": [
            ("images/monasteries/studenica.jpg", "Bogorodičina crkva u Studenici od belog mermera, majka svih srpskih manastira <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/studenica_gal_1.jpg", "Kraljeva crkva Svetih Joakima i Ane, zvonik i konaci manastira Studenica <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/studenica_gal_2.jpg", "Pogled na južni portal i mermernu fasadu Bogorodičine crkve u Studenici <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/studenica_gal_3.jpg", "Kameni temelji i zidine srednjovekovne Nemanjine trpezarije u Studenici <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 229,
        "image": "images/monasteries/sveta_trojica_ovcar.jpg",
        "gallery": [
            ("images/monasteries/sveta_trojica_ovcar.jpg", "Crkva Svete Trojice pod Ovčarom u Dučalovićima sa kamenom fasadom i kupolom <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/sveta_trojica_ovcar_gal_1.jpg", "Arhivska fotografija hrama Svete Trojice pod Ovčarom <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/sveta_trojica_ovcar_gal_2.jpg", "Tradicionalni konak sa drvenim doksatom u porti manastira Sveta Trojica <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/sveta_trojica_ovcar_gal_3.jpg", "Pogled na manastirski kompleks kroz gustu šumu na padinama Ovčara <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 230,
        "image": "images/monasteries/trnava.jpg",
        "gallery": [
            ("images/monasteries/trnava.jpg", "Crkva Blagoveštenja u Trnavi kod Čačka na padinama planine Jelice <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/trnava_gal_1.jpg", "Hram u Trnavi sa kamenom fasadom i pomoćnim manastirskim zgradama <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/trnava_gal_2.jpg", "Pogled na konak i travnatu portu manastira Trnava <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/trnava_gal_3.jpg", "Zvonik i spomen-obeležje u porti manastira Trnava <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 231,
        "image": "images/monasteries/uspenje-kablar.jpg",
        "gallery": [
            ("images/monasteries/uspenje-kablar.jpg", "Crkva Uspenja Presvete Bogorodice na Jovanjskom brdu podignuta 1939. godine <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/uspenje-kablar_gal_1.jpg", "Pogled na manastirski kompleks i kulu Gradina iznad klisure <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/uspenje-kablar_gal_2.jpg", "Hram Uspenja sa osmougaonom kupolom po uzoru na ohridsku crkvu <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/uspenje-kablar_gal_3.jpg", "Arhivska fotografija crkve Uspenja i okolnih stena <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 232,
        "image": "images/monasteries/uvac.jpg",
        "gallery": [
            ("images/monasteries/uvac.jpg", "Crkva Rođenja Presvete Bogorodice u manastiru Uvac u kanjonu reke Uvac <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/uvac_gal_1.jpg", "Panoramski pogled na manastirski hram i visoke vrhove Zlatibora <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/uvac_gal_2.jpg", "Kanjon reke Uvac sa pogledom na manastirski kompleks odozgo <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/uvac_gal_3.jpg", "Kameni zid i ulazna kapija manastira Uvac <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 233,
        "image": "images/monasteries/vavedenje-ovcar.jpg",
        "gallery": [
            ("images/monasteries/vavedenje-ovcar.jpg", "Crkva Vavedenja Presvete Bogorodice na ulazu u Ovčarsko-kablarsku klisuru <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/vavedenje-ovcar_gal_1.jpg", "Beli zvonik i konaci manastira Vavedenje <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/vavedenje-ovcar_gal_2.jpg", "Umetnička fotografija hrama i zvonika u Vavedenju <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/vavedenje-ovcar_gal_3.jpg", "Kupola crkve Vavedenja sa krstom <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 234,
        "image": "images/monasteries/vaznesenje-ovcar.jpg",
        "gallery": [
            ("images/monasteries/vaznesenje-ovcar.jpg", "Crkva Vaznesenja Gospodnjeg na padinama Ovčara sa zvonikom <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/vaznesenje-ovcar_gal_1.jpg", "Kupola hrama Vaznesenja pod Ovčarom <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/vaznesenje-ovcar_gal_2.jpg", "Monaško groblje i kameni zvonik u manastirskoj porti Vaznesenja <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/vaznesenje-ovcar_gal_3.jpg", "Unutrašnji prostor i kivot u manastiru Vaznesenje <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 235,
        "image": "images/monasteries/voljavca-bresnica.jpg",
        "gallery": [
            ("images/monasteries/voljavca-bresnica.jpg", "Hram Svetog arhangela Gavrila i Karađorđev konak u manastiru Voljavča kod Bresnice <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/voljavca-bresnica_gal_1.jpg", "Crkva i konaci u prostranoj zelenoj porti manastira Voljavča kod Bresnice <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/voljavca-bresnica_gal_2.jpg", "Kamena kupola hrama Svetog arhangela u Voljavči <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/voljavca-bresnica_gal_3.jpg", "Visoki zvonik od kamena i crvene opeke u manastiru Voljavča <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 236,
        "image": "images/monasteries/vracevsnica.jpg",
        "gallery": [
            ("images/monasteries/vracevsnica.jpg", "Panoramski pogled na manastir Vraćevšnica podno Rudnika sa konacima i baroknim zvonikom <small>*(Izvor: commons.wikimedia.org)*</small>", 0),
            ("images/monasteries/vracevsnica_gal_1.jpg", "Konaci manastira Vraćevšnica sa cvetnom alejom i travnjakom <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/vracevsnica_gal_2.jpg", "Barokni zvonik i prilaz manastirskom hramu Svetog Đorđa <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/vracevsnica_gal_3.jpg", "Kameni pločnik i dvorište manastirskog kompleksa Vraćevšnica <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 237,
        "image": "images/monasteries/vujan.jpg",
        "gallery": [
            ("images/monasteries/vujan.jpg", "Crkva Svetog arhangela Gavrila pod planinom Vujan sa drvenim tremom <small>*(Izvor: manastiri.rs)*</small>", 0),
            ("images/monasteries/vujan_gal_1.jpg", "Visoki kameni osmougaoni zvonik sa satom u manastiru Vujan <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/vujan_gal_2.jpg", "Gornja etaža zvonika sa satom i krstom u manastiru Vujan <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/vujan_gal_3.jpg", "Spomen-česma Nikole Lunjevice u porti manastira Vujan <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 238,
        "image": "images/monasteries/zgodacica.jpg",
        "gallery": [
            ("images/monasteries/zgodacica.jpg", "Crkva Svetog Dimitrija sa konakom od crvene opeke u manastiru Zgodačica pod snegom <small>*(Izvor: eparhija-zicka.rs)*</small>", 0),
            ("images/monasteries/zgodacica_gal_1.png", "Novi konak i manastirski kompleks Zgodačica kod Kraljeva <small>*(Izvor: commons.wikimedia.org)*</small>", 1)
        ]
    },
    {
        "id": 239,
        "image": "images/monasteries/zica.jpg",
        "gallery": [
            ("images/monasteries/zica.jpg", "Crkva Svetog Spasa u Žiči sa prepoznatljivom crvenom fasadom, sedište prve srpske arhiepiskopije <small>*(Izvor: commons.wikimedia.org)*</small>", 0),
            ("images/monasteries/zica_gal_1.jpg", "Mala crkva Svetih apostola Petra i Pavla sa plavom kupolom u porti Žiče <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/zica_gal_2.jpg", "Visoki zvonik i ulazna kula manastira Žiča <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/zica_gal_3.jpg", "Oltarska apsida i crvena fasada hrama Svetog Spasa u Žiči <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    },
    {
        "id": 254,
        "image": "images/monasteries/stjenik.jpg",
        "gallery": [
            ("images/monasteries/stjenik.jpg", "Crkva Svetog Jovana Krstitelja u manastiru Stjenik pod planinom Jelom sa drvenim zvonikom i potokom <small>*(Izvor: commons.wikimedia.org)*</small>", 0),
            ("images/monasteries/stjenik_gal_1.jpg", "Kameni prozor sa lučnim otvorom (bifora) u manastiru Stjenik <small>*(Izvor: commons.wikimedia.org)*</small>", 1),
            ("images/monasteries/stjenik_gal_2.jpg", "Kameni hram Svetog Jovana Krstitelja pokriven kamenim pločama <small>*(Izvor: commons.wikimedia.org)*</small>", 2),
            ("images/monasteries/stjenik_gal_3.jpg", "Drveni zvonik i manastirski ambijent u gustoj šumi pod Jelom <small>*(Izvor: commons.wikimedia.org)*</small>", 3)
        ]
    }
]

import os

for db_path in ['database/database.sqlite', 'storage/database.sqlite']:
    if not os.path.exists(db_path):
        continue
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    
    # Provera tačnog naziva kolone za sliku u tabeli monasteries (image ili image_url)
    c.execute("PRAGMA table_info(monasteries)")
    columns = [col[1] for col in c.fetchall()]
    img_col = "image_url" if "image_url" in columns else "image"

    for item in zicka_data:
        m_id = item['id']
        c.execute(f"UPDATE monasteries SET {img_col} = ? WHERE id = ?", (item['image'], m_id))
        c.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))
        for img_url, caption, sort_order in item['gallery']:
            c.execute("INSERT INTO monastery_images (monastery_id, url, caption, sort_order) VALUES (?, ?, ?, ?)", (m_id, img_url, caption, sort_order))
    
    conn.commit()
    conn.close()
    print(f"Uspešno ažurirana baza: {db_path} za svih 35 manastira Žičke eparhije!")