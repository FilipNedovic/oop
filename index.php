<?php 

    class Restoran {
        public $ime;
        public $adresa;

        public $stolovi = [];
        public $konobari = [];
        public $porudzbine = [];
        public $neplacenePorudzbine = [];

        public function __construct($ime, $adresa) {
            $this->ime = $ime;
            $this->adresa = $adresa;
            // kreiranje stolova pri instanciranju objekta
            $this->dodajStolove();
        }

        public function dodajStolove() {
            for($i = 1; $i <= 4; $i++) {
                $this->stolovi[] = new Sto();
            }
        }

        public function dodajKonobara(Konobar $konobar) {
            $this->konobari[] = $konobar;
        }

        public function dodajPorudzbinu(Array $porudzbina) {
            $novaPorudzbina = new Porudzbina();
            $novaPorudzbina->dodajObrok($porudzbina);

            $this->porudzbine[] = $novaPorudzbina->obrok;
        }

        public function naruciObrok(Array $porudzbina) {
            // zauzmi sto
            $stoKojiNarucuje = array_shift($this->stolovi);

            // povezi sto sa porudzbinom
            $this->dodajPorudzbinu($porudzbina);
            $naruceniObrok = array_shift($this->porudzbine);
            $stoKojiNarucuje->porudzbinaStola = $naruceniObrok;

            // spremi porudzbinu za naplatu
            $this->neplacenePorudzbine[] = $naruceniObrok[0];      
        }

        public function ispostaviRacun() {
            $racun = new Racun();

            $porudzbinaZaNaplatu = $this->porudzbine[0];
            $racun->izracunajCenu($porudzbinaZaNaplatu);
        }
    }

    class Konobar {
        public $ime;
        public $jmbg;
        private $plata = '50000';

        public function __construct($ime, $jmbg) {
            $this->ime = $ime;
            $this->jmbg = $jmbg;
        }

        public function getPlata() {
            return $this->plata;
        }

        public function setPlata($plata) {
            $this->plata = $plata;
        }
    }

    class Sto {
        public static $brojac = 1;
        public $brojStola;
        public $porudzbinaStola;

        public function __construct() {
            $this->brojStola = self::$brojac++;
        }
    }

    class Racun {
        public $iznos;

        public function izracunajCenu(Array $porudzbina) {     
            $sum = 0;      
            for($i = 0; $i < count($porudzbina[0]); $i++) {
                $sum += $porudzbina[0][$i]->cena;
            }

            $this->iznos = $sum;
        }
    }

    class Porudzbina {
        public $obrok = [];
        public $hrana;
        public $pice;
        public $prilog;

        public function dodajObrok(Array $obrok) {
            $this->obrok[] = $obrok;
        }
    }

    abstract class Obrok {
        public $naziv; 
        public $cena;

        public function __construct($naziv) {
            $this->naziv = $naziv;
        }

    }

    class Hrana extends Obrok {
        public function __construct($naziv) {
            parent::__construct($naziv);
            $this->cena = rand(300, 600);
        }
    }

    class Pizza extends Hrana {}

    class Pasta extends Hrana {}

    class Pice extends Obrok {
        public $zapremina;

        public function __construct($naziv, $zapremina) {
            parent::__construct($naziv);
            $this->cena = rand(150, 500);
            $this->zapremina = $zapremina;
        }
    }

    class GaziraniSokovi extends Pice {}
    
    class NegaziraniSokovi extends Pice {}
    
    class Voda extends Pice {}

    class Prilog extends Obrok {
        public function __construct($naziv) {
            parent::__construct($naziv);
            $this->cena = rand(20, 100);
        }
    }


    $mario = new Restoran('Mario\'s', 'Brace Krkljus 3');

    $mile = new Konobar('Mile', '0992873380001');
    $mario->dodajKonobara($mile);

    $pizzMargherita = new Pizza('Margherita');
    $pizzaCappriciosa = new Pizza('Capricciosa');
    $pizzaQuattroStagioni = new Pizza('Quattro Stagioni');
    $pizzaFungi = new Pizza('Fungi');

    $pastaCarbonara = new Pasta('Pasta Carbonara');
    $pastaItaliana = new Pasta('Pasta Italiana');
    $pastaGricia = new Pasta('Pasta alla Gricia');
    $pastaVesuviana = new Pasta('Pasta Vesuviana');
    $pastaCipolla = new Pasta('Pasta alla Cipolla');

    $kola = new GaziraniSokovi('Koka-Kola', '0.25');
    $djus = new NegaziraniSokovi('djus', '0.3');
    $voda = new Voda('voda', '0.25');

    $origano = new Prilog('origano');
    $kecap = new Prilog('kecap');
    $sir = new Prilog('sir');

    // $mario->dodajPorudzbinu([$klopa, $pice, $prilog]);
    // $mario->ispostaviRacun();
    $mario->naruciObrok([$pizzaCappriciosa, $kola, $kecap]);
    // var_dump($mario->stolovi);
    // var_dump($mario);

?>  

<!-- Simulirati rad ovog sistema na sledeći način:
1) Kreirati 4 stola numerisana brojevima od 1 do 4.
2) Kreirati 4 različite pizze, 5 pasti, 3 različita pića i 5 priloga. (imena generisati).
3) Kreirati tri porudžbine za prethodno kreirane stavke (ugledati se ili iskoristiti primer
ispod):
a) Sto broj 1
i) Pizza Capricciosa + kecap + origano,
ii) Pasta Italiana + extra cheese,
iii) 2 x Coca cola 0.5,
b) Sto broj 2
i) Pizza Siciliana,
ii) Pasta Carbonara,
iii) negazirani sok 0.25
c) Sto broj 3
i) 3 x Pizza Capricciosa + 2x kecap
ii) Gazirani sok 0.3, negazirani sok 0.5, čaša vode
4) Naplatiti prvu i treću porudžbinu.
5) Pokušati poručivanje Pizza Capricciosa za sto broj 2 (očekuje se da se baci izuzetak).
6) Naplatiti drugu porudžbinu
7) Pokušati poručivanje Pizza Capricciosa za sto broj 2 (očekuje se uspešno kreiranje
porudžbine bez izuzetka). -->
