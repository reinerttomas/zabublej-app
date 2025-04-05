<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Console\Command;

final class EventInitCommand extends Command
{
    protected $signature = 'event:init';

    protected $description = 'Command description';

    public function handle(): void
    {
        foreach ($this->getEvents() as $data) {
            Event::create($data);
        }

        $this->info('Events initialized successfully.');
    }

    private function getEvents(): iterable
    {
        yield [
            'name' => 'Letní tábor pro děti',
            'description' => 'Týdenní letní tábor plný aktivit, her a dobrodružství pro děti ve věku 7-12 let.',
            'start_at' => '2025-05-01 09:00:00',
            'location' => 'Rekreační středisko Slunečnice, Jihlava',
            'contact_person' => 'Jana Nováková',
            'contact_email' => 'jana.novakova@example.com',
            'contact_phone' => '+420 777 123 456',
            'is_multi_person' => true,
            'estimated_children_count' => 30,
            'max_workers' => 1,
            'price' => 15000,
            'reward' => 2500,
            'note' => 'Doprava autobusem zařízena. Ujistěte se, že máte vybavení pro venkovní aktivity.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Narozeninová oslava',
            'description' => 'Organizace zábavného programu s bublinovou show na narozeninové oslavě.',
            'start_at' => '2025-08-18 14:00:00',
            'location' => 'Praha 5, Restaurace U Zelené zahrady',
            'contact_person' => 'Petr Svoboda',
            'contact_email' => 'petr.s@example.com',
            'contact_phone' => '+420 608 222 333',
            'is_multi_person' => false,
            'estimated_children_count' => 12,
            'max_workers' => 1,
            'price' => 3500,
            'reward' => 1200,
            'note' => 'Dítě má alergii na latex, používejte nelatexové balónky.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Firemní akce pro rodiny s dětmi',
            'description' => 'Celodenní program pro děti zaměstnanců během firemního eventu.',
            'start_at' => '2025-09-05 10:00:00',
            'location' => 'Hotel Panorama, Brno',
            'contact_person' => 'Lukáš Tichý',
            'contact_email' => 'lukas.tichy@firma.cz',
            'contact_phone' => '+420 737 888 999',
            'is_multi_person' => true,
            'estimated_children_count' => 45,
            'max_workers' => 2,
            'price' => 22000,
            'reward' => 3500,
            'note' => 'Firemní akce s VIP hosty, profesionální přístup nutný. Potřeba přijet hodinu předem na přípravu.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Městské slavnosti - dětský koutek',
            'description' => 'Zajištění bublinové show a programu pro děti na městských slavnostech.',
            'start_at' => '2025-08-24 13:00:00',
            'location' => 'Náměstí Republiky, Plzeň',
            'contact_person' => 'Městský úřad Plzeň',
            'contact_email' => 'akce@plzen.cz',
            'contact_phone' => '+420 377 111 222',
            'is_multi_person' => true,
            'estimated_children_count' => 100,
            'max_workers' => 2,
            'price' => 35000,
            'reward' => 4000,
            'note' => 'Venkovní akce, nezbytné připravit alternativu v případě deště. Parkování zajištěno.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Mateřská škola Sluníčko - vystoupení',
            'description' => 'Hodinové vystoupení s bublinovou show pro děti v mateřské škole.',
            'start_at' => '2025-10-02 10:30:00',
            'location' => 'MŠ Sluníčko, Liberec',
            'contact_person' => 'Mgr. Alena Veselá',
            'contact_email' => 'reditelka@msslunicko.cz',
            'contact_phone' => '+420 485 100 200',
            'is_multi_person' => false,
            'estimated_children_count' => 25,
            'max_workers' => 2,
            'price' => 3000,
            'reward' => 1000,
            'note' => 'Představení pro dvě třídy dohromady. Doporučuje se přijet 30 minut předem.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Soukromá zahradní párty',
            'description' => 'Zajištění zábavného programu s bublinami pro děti na soukromé zahradní oslavě.',
            'start_at' => '2025-08-08 16:00:00',
            'location' => 'Kamenice nad Lipou, soukromá adresa',
            'contact_person' => 'Marie Dvořáková',
            'contact_email' => 'marie.dvorakova@example.com',
            'contact_phone' => '+420 602 333 444',
            'is_multi_person' => false,
            'estimated_children_count' => 8,
            'max_workers' => 2,
            'price' => 2500,
            'reward' => 1100,
            'note' => 'Malá zahradní party, omezený prostor. Potřeba vlastní vybavení.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Festival rodinného života',
            'description' => 'Celodenní účast na festivalu s aktivitami pro děti a bublinovými workshopy.',
            'start_at' => '2025-09-12 09:00:00',
            'location' => 'Výstaviště Flora, Olomouc',
            'contact_person' => 'Organizační tým festivalu',
            'contact_email' => 'info@festivalrodiny.cz',
            'contact_phone' => '+420 585 444 555',
            'is_multi_person' => true,
            'estimated_children_count' => 200,
            'max_workers' => 2,
            'price' => 40000,
            'reward' => 4500,
            'note' => 'Celý den na nohou, doporučení vzít si náhradní oblečení a dostatek vody.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Základní škola - projektový den',
            'description' => 'Projektový den na základní škole zaměřený na fyziku a chemii hravou formou s bublinami.',
            'start_at' => '2025-11-22 08:00:00',
            'location' => 'ZŠ Komenského, Pardubice',
            'contact_person' => 'Mgr. Jan Hruška',
            'contact_email' => 'hruska@zskomenskeho.cz',
            'contact_phone' => '+420 466 111 333',
            'is_multi_person' => true,
            'estimated_children_count' => 60,
            'max_workers' => 2,
            'price' => 8000,
            'reward' => 2000,
            'note' => 'Projektový den pro 3 třídy, postupně po hodině. Nutné vzdělávací prvky.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Den otevřených dveří - vědecké centrum',
            'description' => 'Prezentace vědeckých principů formou bublinové show a workshopů.',
            'start_at' => '2025-12-15 10:00:00',
            'location' => 'iQLANDIA, Liberec',
            'contact_person' => 'Bc. Martin Koval',
            'contact_email' => 'akce@iqlandia.cz',
            'contact_phone' => '+420 486 222 333',
            'is_multi_person' => true,
            'estimated_children_count' => 120,
            'max_workers' => 2,
            'price' => 12000,
            'reward' => 3000,
            'note' => 'Nutno připravit také teoretické vysvětlení jevů, vzdělávací přesah.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Příměstský tábor - den s bublinami',
            'description' => 'Tematický den pro účastníky příměstského tábora zaměřený na hry s bublinami.',
            'start_at' => '2025-08-23 08:30:00',
            'location' => 'DDM Letná, České Budějovice',
            'contact_person' => 'Petra Sýkorová',
            'contact_email' => 'tabory@ddmcb.cz',
            'contact_phone' => '+420 387 111 222',
            'is_multi_person' => true,
            'estimated_children_count' => 25,
            'max_workers' => 2,
            'price' => 6000,
            'reward' => 2000,
            'note' => 'Celodenní program včetně workshopu výroby bublifuků. Oběd pro personál zajištěn.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Vánoční firemní večírek s programem pro děti',
            'description' => 'Speciální vánoční program s bublinovou show pro děti zaměstnanců během firemního večírku.',
            'start_at' => '2025-12-18 17:00:00',
            'location' => 'Hotel Ambassador, Praha',
            'contact_person' => 'Ing. Kateřina Procházková',
            'contact_email' => 'katerina.prochazkova@examplecorp.cz',
            'contact_phone' => '+420 602 789 456',
            'is_multi_person' => true,
            'estimated_children_count' => 35,
            'max_workers' => 2,
            'price' => 18000,
            'reward' => 3200,
            'note' => 'Slavnostní akce v hlavním sále hotelu. Vhodné společenské oblečení nutností. Program bude probíhat současně s firemním večírkem v oddělené části sálu.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Firemní teambuilding s programem pro děti',
            'description' => 'Celodenní teambuilding s paralelním programem pro děti zaměstnanců včetně bublinové show a aktivit.',
            'start_at' => '2025-09-26 10:00:00',
            'location' => 'Resort Green Valley, Benešov',
            'contact_person' => 'Ing. Petra Machová',
            'contact_email' => 'petra.machova@korporace.cz',
            'contact_phone' => '+420 733 456 789',
            'is_multi_person' => true,
            'estimated_children_count' => 25,
            'max_workers' => 2,
            'price' => 18500,
            'reward' => 3200,
            'note' => 'Exkluzivní akce s požadavkem na vysoký standard služeb. Předběžná domluva vlastních firemních triček.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Festival vědy a techniky - bublinová laboratoř',
            'description' => 'Interaktivní stánek s ukázkami a vysvětlením fyzikálních vlastností bublin na festivalu vědy.',
            'start_at' => '2025-08-14 09:00:00',
            'location' => 'Technická univerzita, Liberec',
            'contact_person' => 'Doc. RNDr. Jakub Černý, Ph.D.',
            'contact_email' => 'jakub.cerny@tul.cz',
            'contact_phone' => '+420 485 351 777',
            'is_multi_person' => true,
            'estimated_children_count' => 180,
            'max_workers' => 2,
            'price' => 16000,
            'reward' => 3600,
            'note' => 'Akademické prostředí, nutná příprava vědeckých vysvětlení a experimentů demonstrujících fyzikální vlastnosti. Materiál k experimentům bude zajištěn univerzitou.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Lázeňský den pro rodiny s dětmi',
            'description' => 'Speciální program v rámci lázeňského dne pro rodiny s dětmi, kombinující bublinovou show s relaxačními prvky.',
            'start_at' => '2025-06-28 13:30:00',
            'location' => 'Lázeňský park, Mariánské Lázně',
            'contact_person' => 'Mgr. Lenka Tichá',
            'contact_email' => 'kulturni.oddeleni@marianskelazne.cz',
            'contact_phone' => '+420 354 922 123',
            'is_multi_person' => true,
            'estimated_children_count' => 90,
            'max_workers' => 2,
            'price' => 12500,
            'reward' => 2800,
            'note' => 'Venkovní akce v lázeňském prostředí. Reprezentativní oblečení nutností. Kombinovat program s tématikou zdraví a relaxace.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Workshop bublinových kouzel pro děti',
            'description' => 'Interaktivní workshop, kde se děti naučí vytvářet různé druhy a velikosti bublin a dozvědí se o fyzikálních principech.',
            'start_at' => '2025-10-25 14:00:00',
            'location' => 'Městská knihovna, Hradec Králové',
            'contact_person' => 'Mgr. Karolína Černá',
            'contact_email' => 'detske.oddeleni@knihovnahk.cz',
            'contact_phone' => '+420 495 123 456',
            'is_multi_person' => false,
            'estimated_children_count' => 18,
            'max_workers' => 1,
            'price' => 4500,
            'reward' => 1800,
            'note' => 'Materiál na workshop zajištěn knihovnou. Vhodné pro děti od 7 let.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Halloweenská párty s bublinovým strašením',
            'description' => 'Speciální halloweenské představení s tematickými bublinami a UV efekty pro děti na strašidelné party.',
            'start_at' => '2025-10-31 17:30:00',
            'location' => 'Komunitní centrum Spektrum, Ostrava',
            'contact_person' => 'Radim Horák',
            'contact_email' => 'akce@kcspektrum.cz',
            'contact_phone' => '+420 596 789 123',
            'is_multi_person' => true,
            'estimated_children_count' => 40,
            'max_workers' => 1,
            'price' => 8500,
            'reward' => 2200,
            'note' => 'UV světla zajištěna pořadatelem. Nutný vlastní kostým v halloweenském stylu.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Otevření nového dětského oddělení nemocnice',
            'description' => 'Slavnostní otevření nového dětského oddělení s programem pro malé pacienty.',
            'start_at' => '2025-11-10 10:00:00',
            'location' => 'Krajská nemocnice, Zlín',
            'contact_person' => 'MUDr. Helena Svobodová',
            'contact_email' => 'h.svobodova@nemocnicezlin.cz',
            'contact_phone' => '+420 577 852 369',
            'is_multi_person' => true,
            'estimated_children_count' => 15,
            'max_workers' => 2,
            'price' => 0,
            'reward' => 1500,
            'note' => 'Charitativní akce - vystoupení zdarma. Speciální hygienické předpisy, bude upřesněno před akcí.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Mikulášská besídka s bublinami',
            'description' => 'Tradiční mikulášská besídka obohacená o speciální bublinovou show s vánočními prvky.',
            'start_at' => '2025-12-05 16:00:00',
            'location' => 'Kulturní dům, Beroun',
            'contact_person' => 'Jiří Novák',
            'contact_email' => 'kultura@beroun.cz',
            'contact_phone' => '+420 311 654 987',
            'is_multi_person' => true,
            'estimated_children_count' => 70,
            'max_workers' => 2,
            'price' => 11000,
            'reward' => 2800,
            'note' => 'Mikrofony a ozvučení zajištěno. Kostýmy čerta a Mikuláše nejsou potřeba, budou zajištěny jiným vystupujícím.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Letní festival zábavy - bublinová zóna',
            'description' => 'Provoz bublinové zóny na velkém letním festivalu pro rodiny s dětmi po celý víkend.',
            'start_at' => '2025-07-19 10:00:00',
            'location' => 'Park Ladronka, Praha',
            'contact_person' => 'Bc. Tomáš Veselý',
            'contact_email' => 'info@letnifestival.cz',
            'contact_phone' => '+420 601 234 567',
            'is_multi_person' => true,
            'estimated_children_count' => 300,
            'max_workers' => 2,
            'price' => 45000,
            'reward' => 5000,
            'note' => 'Třídenní akce, každý den od 10:00 do 18:00. Ubytování pro pracovníky zajištěno v blízkém hotelu.',
            'status' => EventStatus::Published,
        ];

        yield [
            'name' => 'Rozsvěcení vánočního stromu s bublinovou show',
            'description' => 'Doprovodný program při slavnostním rozsvěcení vánočního stromu na náměstí.',
            'start_at' => '2025-12-01 17:00:00',
            'location' => 'Náměstí Míru, Jablonec nad Nisou',
            'contact_person' => 'Městský úřad Jablonec',
            'contact_email' => 'kultura@mestojablonec.cz',
            'contact_phone' => '+420 483 123 456',
            'is_multi_person' => true,
            'estimated_children_count' => 150,
            'max_workers' => 2,
            'price' => 15000,
            'reward' => 3300,
            'note' => 'Venkovní zimní akce, potřeba teplé oblečení. V případě silného mrazu bude program upraven nebo zkrácen.',
            'status' => EventStatus::Published,
        ];
    }
}
