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
            for($i = 1; $i <= 4; $i++) {
                $this->stolovi[] = new Sto();
            }
        }

        public function dodajKonobara(Konobar $konobar) {
            $this->konobari[] = $konobar;
        }

        public function dodajPorudzbinu(Porudzbina $porudzbina) {
            $this->porudzbine[] = $porudzbina;
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

        public function izracunajCenu(Porudzbina $porudzbina) {               
            $sum = 0;      
            for($i = 0; $i < count($porudzbina->obroci); $i++) {
                $sum += $porudzbina->obroci[$i]->ispostaviCenu();
            }

            $this->iznos = $sum;
            Loger::logujIzdavanjeRacuna($this->iznos);
        }
    }

    class Porudzbina {
        public $obroci = [];
        public $obrok;
        public $placeno = false;

        public function __construct(Obrok $obrok) {
            $this->dodajObrok($obrok);
            Loger::logujKreiranjePorudzbine($this->obroci);
        }

        public function dodajObrok(Obrok $obrok) {
            $this->obroci[] = $obrok;
        }

        public function plati() {
            $racun = new Racun();
            $racun->izracunajCenu($this);

            $this->placeno = true;
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

        public function ispostaviCenu() {
            return $this->cena * $this->kolicina;
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

        public function __construct($naziv, $zapremina, $kolicina) {
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

    class Loger {
        public static function logujKreiranjePorudzbine($porudzbina) {
            echo 'Porudzbina: datum ' . (new DateTime())->format('d.m.Y H:i') . ' ' . print_r($porudzbina) . '<br>';
        }

        public static function logujIzdavanjeRacuna($racun) {
            echo 'Racun: datum ' . (new DateTime())->format('d.m.Y H:i') . ' naplata ' . $racun;
        }
    }


    $mario = new Restoran('Mario\'s', 'Brace Krkljus 3');

    $mile = new Konobar('Mile', '0992873380001');
    $mario->dodajKonobara($mile);

    $porudzbina1 = new Porudzbina(new Pizza('Capricciosa', 1), new Pasta('Pasta Italiana', 2), new GaziraniSok('kola', 0.25, 1));

    // $porudzbina1->dodajObrok(new Pizza('Capricciosa', 1));
    // $porudzbina1->dodajObrok(new Pasta('Pasta Italiana', 2));
    // $porudzbina1->dodajObrok(new GaziraniSok('kola', 0.25, 1));

    $porudzbina1->plati();



?>  

