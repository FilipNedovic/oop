<?php 

    class Restoran {
        public $ime;
        public $adresa;
        public $stolovi = [];
        public $konobari = [];
        public $porudzbine = [];

        public function __construct($ime, $adresa) {
            $this->ime = $ime;
            $this->adresa = $adresa;
            // kreiranje stolova pri instanciranju objekta
            $this->dodajStolove();
        }

        public function dodajStolove() {
            for($i = 0; $i < 10; $i++) {
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

        public function ispostaviRacun() {
            $racun = new Racun();
            $porudzbinaZaNaplatu = $this->porudzbine[0];
            var_dump($porudzbinaZaNaplatu);
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

        public function __construct() {
            $this->brojStola = self::$brojac++ . '.';
        }
    }

    class Racun {
        public $iznos;

        public function izracunajCenu(Array $porudzbina) {           
            $sum = 0;

            for($i = 0; $i <= count($porudzbina); $i++) {
                $sum += $porudzbina[$i]->cena;
            }
            var_dump($sum);
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

    $klopa = new Pizza('Margarita');
    $pice = new GaziraniSokovi('Koka-Kola', '0.25');
    $prilog = new Prilog('majonez');
    $mario->dodajPorudzbinu([$klopa, $pice, $prilog]);
    $mario->ispostaviRacun();
    var_dump($mario);

?>  