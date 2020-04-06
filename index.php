<?php 

    class Restoran {
        public $ime;
        public $adresa;
        public $stolovi = [];

        public function __construct($ime, $adresa) {
            $this->ime = $ime;
            $this->adresa = $adresa;
            $this->dodajStolove();
        }

        public function dodajStolove() {
            for($i = 0; $i < 10; $i++) {
                $this->stolovi[] = new Sto();
            }
        }

    }

    class Konobar {
        public $ime;
        public $jmbg;
        private $plata;

        public function __construct($ime, $jmbg) {
            $this->ime = $ime;
            $this->jmbg = $jmbg;
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

        public function __construct($iznos) {
            $this->iznos = $iznos;
        }

        public function izracunajCenu() {}
    }

    class Porudzbina {
        public $obrok = [];

        public $hrana;
        public $pice;
        public $prilog;

        public function __construct($hrana, $pice, $prilog) {
            $this->hrana = $hrana;
            $this->pice = $pice;
            $this->prilog = $prilog;
        }

        public function dodajObrok() {}

    }

    abstract class Obrok {
        public $naziv; 
        public $cena;

        public function __construct() {}

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


    // $mario = new Restoran('Mario\'s', 'Brace Krkljus 3');
    // var_dump($mario);

    // $porudzbina = new Porudzbina('pica', 'voda', 'ananas');
    // var_dump($porudzbina);

?>