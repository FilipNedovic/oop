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

            $porudzbinaZaNaplatu = $this->neplacenePorudzbine;
            $racun->izracunajCenu($porudzbinaZaNaplatu);
            
            // racun za naplatu
            return $racun->iznos;
        }

        function platiRacun(int $iznos) {
            $racunZaNaplatu = $this->ispostaviRacun();

            if($iznos < $racunZaNaplatu) {
                echo 'Nemate dovoljno novca';
            } else {
                array_shift($this->neplacenePorudzbine);
                echo 'Uspesno ste platili Vas racun';
            }
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
                $sum += $porudzbina[0][$i]->cena * $porudzbina[0][$i]->kolicina;
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
        public $kolicina;

        public function __construct($naziv, $kolicina) {
            $this->naziv = $naziv;
            $this->kolicina = $kolicina;
        }

    }

    class Hrana extends Obrok {
        public function __construct($naziv, $kolicina) {
            parent::__construct($naziv, $kolicina);
            $this->cena = rand(300, 600);
        }
    }

    class Pizza extends Hrana {}

    class Pasta extends Hrana {}

    class Pice extends Obrok {
        public $zapremina;

        public function __construct($naziv, $kolicina, $zapremina) {
            parent::__construct($naziv, $kolicina);
            $this->cena = rand(150, 500);
            $this->zapremina = $zapremina;
        }
    }

    class GaziraniSok extends Pice {}
    
    class NegaziraniSok extends Pice {}
    
    class Voda extends Pice {}

    class Prilog extends Obrok {
        public function __construct($naziv, $kolicina) {
            parent::__construct($naziv, $kolicina);
            $this->cena = rand(20, 100);
        }
    }


    $mario = new Restoran('Mario\'s', 'Brace Krkljus 3');

    $mile = new Konobar('Mile', '0992873380001');
    $mario->dodajKonobara($mile);

    $porudzbina1 = [
        $pizzaCappriciosa = new Pizza('Capricciosa', 1), 
        $kecap = new Prilog('kecap', 1), 
        $origano = new Prilog('origano', 1), 
        $pastaItaliana = new Pasta('Pasta Italiana', 1), 
        $kola = new GaziraniSok('kola', 2, 0.25)
    ];

    $porudzbina2 = [
        $pizzaSiciliana = new Pizza('Pizza Siciliana', 1), 
        $pastaCarbonara = new Pasta('Pasta Carbonara', 1),
        $sok = new NegaziraniSok('sok', 1, 0.25)
    ];

    $porudzbina3 = [
        $pizzaCappriciosa = new Pizza('Pizza Capricciosa', 3), 
        $kecap = new Prilog('kecap', 2), 
        $gaziraniSok = new GaziraniSok('gazirani sok', 1, 0.3),
        $negaziraniSok = new NegaziraniSok('negazirani sok', 1, 0.5),
        $voda = new Voda('voda', 1, 'casa')
    ];

    // $mario->naruciObrok($porudzbina1);
    // $mario->platiRacun(100);

?>  

