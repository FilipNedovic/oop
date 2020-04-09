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

    $mario->naruciObrok([$pizzaCappriciosa, $kola, $kecap]);
    $mario->platiRacun(1500);
    // var_dump($mario->stolovi);
    // var_dump($mario);

    // echo '<pre>';
    //     var_dump();
    // echo '</pre>';

?>  

