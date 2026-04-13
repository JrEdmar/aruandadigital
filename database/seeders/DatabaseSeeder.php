<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Event;
use App\Models\House;
use App\Models\Product;
use App\Models\Study;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $senha = Hash::make('password');

        // ----------------------------------------------------------------
        // 1 usuário por role — dados completos
        // ----------------------------------------------------------------
        $admin = User::create([
            'name'             => 'Admin Principal',
            'email'            => 'admin@aruanda.com',
            'password'         => $senha,
            'role'             => 'admin',
            'phone'            => '(11) 99000-0001',
            'cpf'              => '000.000.000-01',
            'birth_date'       => '1980-05-15',
            'points'           => 1200,
            'level'            => 13,
            'lgpd_accepted_at' => now(),
        ]);

        $moderador = User::create([
            'name'             => 'Moderador Geral',
            'email'            => 'moderador@aruanda.com',
            'password'         => $senha,
            'role'             => 'moderador',
            'phone'            => '(11) 99000-0002',
            'cpf'              => '000.000.000-02',
            'birth_date'       => '1985-08-20',
            'points'           => 450,
            'level'            => 5,
            'lgpd_accepted_at' => now(),
        ]);

        $dirigente1 = User::create([
            'name'             => 'Mãe Iracema de Oxalá',
            'email'            => 'dirigente@aruanda.com',
            'password'         => $senha,
            'role'             => 'dirigente',
            'phone'            => '(11) 99001-0001',
            'cpf'              => '111.111.111-11',
            'birth_date'       => '1965-02-10',
            'points'           => 780,
            'level'            => 8,
            'lgpd_accepted_at' => now(),
        ]);

        $dirigente2 = User::create([
            'name'             => 'Pai José de Ogum',
            'email'            => 'dirigente2@aruanda.com',
            'password'         => $senha,
            'role'             => 'dirigente',
            'phone'            => '(21) 99002-0002',
            'cpf'              => '222.222.222-22',
            'birth_date'       => '1970-11-03',
            'points'           => 650,
            'level'            => 7,
            'lgpd_accepted_at' => now(),
        ]);

        $assistente = User::create([
            'name'             => 'Irmão Sebastião',
            'email'            => 'assistente@aruanda.com',
            'password'         => $senha,
            'role'             => 'assistente',
            'phone'            => '(11) 99003-0003',
            'cpf'              => '333.333.333-33',
            'birth_date'       => '1988-07-25',
            'points'           => 340,
            'level'            => 4,
            'lgpd_accepted_at' => now(),
        ]);

        $membro = User::create([
            'name'             => 'Carlos Membro',
            'email'            => 'membro@aruanda.com',
            'password'         => $senha,
            'role'             => 'membro',
            'phone'            => '(11) 99004-0004',
            'cpf'              => '444.444.444-44',
            'birth_date'       => '1992-03-18',
            'points'           => 180,
            'level'            => 2,
            'lgpd_accepted_at' => now(),
        ]);

        $visitante = User::create([
            'name'             => 'João Visitante',
            'email'            => 'visitante@aruanda.com',
            'password'         => $senha,
            'role'             => 'visitante',
            'phone'            => '(11) 99005-0005',
            'cpf'              => '555.555.555-55',
            'birth_date'       => '1995-09-12',
            'points'           => 60,
            'level'            => 1,
            'lgpd_accepted_at' => now(),
        ]);

        $loja = User::create([
            'name'             => 'Loja Oxalá — Artigos Religiosos',
            'email'            => 'loja@aruanda.com',
            'password'         => $senha,
            'role'             => 'loja',
            'phone'            => '(11) 99006-0006',
            'cpf'              => '666.666.666-66',
            'birth_date'       => '1978-12-01',
            'points'           => 0,
            'level'            => 1,
            'lgpd_accepted_at' => now(),
        ]);

        $lojaMaster = User::create([
            'name'             => 'Distribuidora Sagrada',
            'email'            => 'lojamaster@aruanda.com',
            'password'         => $senha,
            'role'             => 'loja_master',
            'phone'            => '(11) 99007-0007',
            'cpf'              => '777.777.777-77',
            'birth_date'       => '1975-04-22',
            'points'           => 0,
            'level'            => 1,
            'lgpd_accepted_at' => now(),
        ]);

        // ----------------------------------------------------------------
        // CASAS (2 — completas)
        // ----------------------------------------------------------------
        $house1 = House::create([
            'owner_id'        => $dirigente1->id,
            'name'            => 'Terreiro Luz de Oxalá',
            'slug'            => 'terreiro-luz-de-oxala',
            'type'            => 'umbanda',
            'description'     => 'Casa de Umbanda fundada em 1978, acolhendo todos com amor e fé. Nossa missão é promover a paz espiritual e o crescimento de cada devoto que nos visita.',
            'spiritual_line'  => 'Linha de Oxalá — trabalhamos com energias de paz, cura e elevação espiritual. Nossa gira segue o ritual da Umbanda Branca.',
            'history'         => 'Fundada pela Mãe Iracema em 1978, após receber a missão de abrir um centro de caridade em São Paulo. Ao longo de mais de 40 anos, a casa acolheu milhares de pessoas em busca de orientação espiritual e cura.',
            'differentials'   => 'Atendimento espiritual gratuito, Estudos mensais, Giras abertas ao público, Apoio social à comunidade, Equipe de médiuns experientes',
            'email'           => 'terreiro@aruanda.com',
            'phone'           => '(11) 3333-0001',
            'whatsapp'        => '11999010001',
            'website'         => 'https://terreirooxala.com.br',
            'facebook'        => 'https://facebook.com/terreirooxala',
            'instagram'       => 'https://instagram.com/terreirooxala',
            'city'            => 'São Paulo',
            'state'           => 'SP',
            'zip_code'        => '05443-000',
            'street'          => 'Rua das Acácias',
            'number'          => '123',
            'complement'      => 'Fundos',
            'neighborhood'    => 'Vila Madalena',
            'latitude'        => -23.5505,
            'longitude'       => -46.6333,
            'capacity'        => 80,
            'schedule'        => 'Sextas-feiras às 20h | Domingos às 10h | Consultas: terças às 19h',
            'foundation_date' => '1978-03-15',
            'status'          => 'active',
            'approved_at'     => now()->subMonths(6),
        ]);

        $house2 = House::create([
            'owner_id'        => $dirigente2->id,
            'name'            => 'Centro Espírita Ogum Beira-Mar',
            'slug'            => 'centro-ogum-beira-mar',
            'type'            => 'umbanda',
            'description'     => 'Centro de Umbanda com forte tradição carioca. Trabalhamos com todas as linhas da Umbanda, com foco na caridade e no desenvolvimento mediúnico dos nossos médiuns.',
            'spiritual_line'  => 'Linha de Ogum — guerreiros da luz, abrindo caminhos e removendo obstáculos. Também trabalhamos com Caboclos e Pretos Velhos.',
            'history'         => 'Fundado pelo Pai José em 1995 na orla de Copacabana, o centro cresceu rapidamente e hoje é referência no Rio de Janeiro. Realiza trabalhos de caridade mensalmente para a comunidade local.',
            'differentials'   => 'Localização privilegiada, Giras públicas mensais, Desenvolvimento mediúnico, Terapias complementares, Biblioteca espiritual',
            'email'           => 'ogum@aruanda.com',
            'phone'           => '(21) 3333-0002',
            'whatsapp'        => '21999020002',
            'instagram'       => 'https://instagram.com/centrobeiramar',
            'city'            => 'Rio de Janeiro',
            'state'           => 'RJ',
            'zip_code'        => '22010-010',
            'street'          => 'Av. Atlântica',
            'number'          => '500',
            'complement'      => 'Sala 12',
            'neighborhood'    => 'Copacabana',
            'latitude'        => -22.9068,
            'longitude'       => -43.1729,
            'capacity'        => 60,
            'schedule'        => 'Sábados às 19h | Consultas: quartas às 18h',
            'foundation_date' => '1995-06-24',
            'status'          => 'active',
            'approved_at'     => now()->subMonths(3),
        ]);

        // ----------------------------------------------------------------
        // MEMBROS DA CASA 1 — existentes
        // ----------------------------------------------------------------
        $house1->members()->attach($dirigente1->id, [
            'role'         => 'dirigente',
            'role_membro'  => 'dirigente auxiliar',
            'status'       => 'active',
            'joined_at'    => now()->subYears(5),
            'house_points' => 2500,
            'house_level'  => 26,
        ]);

        $house1->members()->attach($assistente->id, [
            'role'         => 'assistente',
            'role_membro'  => 'médium',
            'status'       => 'active',
            'joined_at'    => now()->subYears(2),
            'house_points' => 850,
            'house_level'  => 9,
        ]);

        $house1->members()->attach($membro->id, [
            'role'         => 'membro',
            'role_membro'  => 'cambone',
            'status'       => 'active',
            'joined_at'    => now()->subMonths(8),
            'house_points' => 320,
            'house_level'  => 4,
        ]);

        // visitante com solicitação pendente (com mensagem)
        $house1->members()->attach($visitante->id, [
            'role'         => 'membro',
            'role_membro'  => 'médium',
            'status'       => 'pending',
            'message'      => 'Tenho interesse em desenvolver minha mediunidade. Frequento o terreiro há 3 meses como visitante.',
            'joined_at'    => now()->subDays(2),
        ]);

        // ----------------------------------------------------------------
        // MEMBROS DA CASA 2 — existentes
        // ----------------------------------------------------------------
        $house2->members()->attach($dirigente2->id, [
            'role'         => 'dirigente',
            'role_membro'  => 'dirigente auxiliar',
            'status'       => 'active',
            'joined_at'    => now()->subYears(3),
            'house_points' => 1800,
            'house_level'  => 19,
        ]);

        // moderador também é membro da casa 2
        $house2->members()->attach($moderador->id, [
            'role'         => 'membro',
            'role_membro'  => 'médium',
            'status'       => 'active',
            'joined_at'    => now()->subYear(),
            'house_points' => 420,
            'house_level'  => 5,
        ]);

        // ----------------------------------------------------------------
        // NOVOS MEMBROS DA CASA 1 (membro2 a membro11)
        // ----------------------------------------------------------------
        $membrosHouse1Data = [
            ['name' => 'Ana Luz de Oxalá',  'email' => 'membro2@aruanda.com',  'cpf' => '100.200.300-01', 'phone' => '(11) 91000-0002', 'birth_date' => '1990-03-15', 'points' => 220, 'level' => 3, 'role_membro' => 'médium',              'house_points' => 480, 'house_level' => 6],
            ['name' => 'Beatriz Santos',    'email' => 'membro3@aruanda.com',  'cpf' => '100.200.300-02', 'phone' => '(11) 91000-0003', 'birth_date' => '1988-07-22', 'points' => 190, 'level' => 2, 'role_membro' => 'médium',              'house_points' => 360, 'house_level' => 5],
            ['name' => 'Carlos Medeiros',   'email' => 'membro4@aruanda.com',  'cpf' => '100.200.300-03', 'phone' => '(11) 91000-0004', 'birth_date' => '1993-11-05', 'points' => 160, 'level' => 2, 'role_membro' => 'médium',              'house_points' => 300, 'house_level' => 4],
            ['name' => 'Diego Fonseca',     'email' => 'membro5@aruanda.com',  'cpf' => '100.200.300-04', 'phone' => '(11) 91000-0005', 'birth_date' => '1995-02-18', 'points' => 130, 'level' => 2, 'role_membro' => 'médium',              'house_points' => 250, 'house_level' => 3],
            ['name' => 'Elena Rodrigues',   'email' => 'membro6@aruanda.com',  'cpf' => '100.200.300-05', 'phone' => '(11) 91000-0006', 'birth_date' => '1991-09-30', 'points' => 200, 'level' => 2, 'role_membro' => 'cambone',             'house_points' => 420, 'house_level' => 5],
            ['name' => 'Fernanda Lima',     'email' => 'membro7@aruanda.com',  'cpf' => '100.200.300-06', 'phone' => '(11) 91000-0007', 'birth_date' => '1987-04-12', 'points' => 175, 'level' => 2, 'role_membro' => 'cambone',             'house_points' => 380, 'house_level' => 5],
            ['name' => 'Gustavo Oliveira',  'email' => 'membro8@aruanda.com',  'cpf' => '100.200.300-07', 'phone' => '(11) 91000-0008', 'birth_date' => '1994-06-28', 'points' => 150, 'level' => 2, 'role_membro' => 'cambone',             'house_points' => 280, 'house_level' => 4],
            ['name' => 'Hélio Nascimento',  'email' => 'membro9@aruanda.com',  'cpf' => '100.200.300-08', 'phone' => '(11) 91000-0009', 'birth_date' => '1989-12-03', 'points' => 120, 'level' => 1, 'role_membro' => 'cambone',             'house_points' => 200, 'house_level' => 3],
            ['name' => 'Iracema Júnior',    'email' => 'membro10@aruanda.com', 'cpf' => '100.200.300-09', 'phone' => '(11) 91000-0010', 'birth_date' => '1996-08-14', 'points' => 250, 'level' => 3, 'role_membro' => 'dirigente auxiliar', 'house_points' => 580, 'house_level' => 7],
            ['name' => 'João Carvalho',     'email' => 'membro11@aruanda.com', 'cpf' => '100.200.300-10', 'phone' => '(11) 91000-0011', 'birth_date' => '1986-01-25', 'points' => 230, 'level' => 3, 'role_membro' => 'dirigente auxiliar', 'house_points' => 540, 'house_level' => 7],
        ];

        foreach ($membrosHouse1Data as $i => $data) {
            $user = User::create([
                'name'             => $data['name'],
                'email'            => $data['email'],
                'password'         => $senha,
                'role'             => 'membro',
                'phone'            => $data['phone'],
                'cpf'              => $data['cpf'],
                'birth_date'       => $data['birth_date'],
                'points'           => $data['points'],
                'level'            => $data['level'],
                'lgpd_accepted_at' => now(),
            ]);

            $house1->members()->attach($user->id, [
                'role'         => 'membro',
                'role_membro'  => $data['role_membro'],
                'status'       => 'active',
                'joined_at'    => now()->subMonths(rand(3, 18)),
                'house_points' => $data['house_points'],
                'house_level'  => $data['house_level'],
            ]);
        }

        // ----------------------------------------------------------------
        // NOVOS MEMBROS DA CASA 2 (membro12 a membro21)
        // ----------------------------------------------------------------
        $membrosHouse2Data = [
            ['name' => 'Luana Ferreira',  'email' => 'membro12@aruanda.com', 'cpf' => '200.300.400-01', 'phone' => '(21) 92000-0012', 'birth_date' => '1991-05-20', 'points' => 210, 'level' => 3, 'role_membro' => 'médium',              'house_points' => 460, 'house_level' => 6],
            ['name' => 'Marco Antônio',   'email' => 'membro13@aruanda.com', 'cpf' => '200.300.400-02', 'phone' => '(21) 92000-0013', 'birth_date' => '1985-10-08', 'points' => 185, 'level' => 2, 'role_membro' => 'médium',              'house_points' => 340, 'house_level' => 5],
            ['name' => 'Nina Barros',     'email' => 'membro14@aruanda.com', 'cpf' => '200.300.400-03', 'phone' => '(21) 92000-0014', 'birth_date' => '1993-03-17', 'points' => 155, 'level' => 2, 'role_membro' => 'médium',              'house_points' => 290, 'house_level' => 4],
            ['name' => 'Osvaldo Costa',   'email' => 'membro15@aruanda.com', 'cpf' => '200.300.400-04', 'phone' => '(21) 92000-0015', 'birth_date' => '1988-08-29', 'points' => 140, 'level' => 2, 'role_membro' => 'médium',              'house_points' => 260, 'house_level' => 3],
            ['name' => 'Priscila Alves',  'email' => 'membro16@aruanda.com', 'cpf' => '200.300.400-05', 'phone' => '(21) 92000-0016', 'birth_date' => '1990-12-11', 'points' => 195, 'level' => 2, 'role_membro' => 'cambone',             'house_points' => 400, 'house_level' => 5],
            ['name' => 'Quintino Rocha',  'email' => 'membro17@aruanda.com', 'cpf' => '200.300.400-06', 'phone' => '(21) 92000-0017', 'birth_date' => '1986-06-04', 'points' => 170, 'level' => 2, 'role_membro' => 'cambone',             'house_points' => 360, 'house_level' => 5],
            ['name' => 'Rosa Meireles',   'email' => 'membro18@aruanda.com', 'cpf' => '200.300.400-07', 'phone' => '(21) 92000-0018', 'birth_date' => '1994-09-23', 'points' => 145, 'level' => 2, 'role_membro' => 'cambone',             'house_points' => 270, 'house_level' => 4],
            ['name' => 'Sandro Batista',  'email' => 'membro19@aruanda.com', 'cpf' => '200.300.400-08', 'phone' => '(21) 92000-0019', 'birth_date' => '1992-01-16', 'points' => 110, 'level' => 1, 'role_membro' => 'cambone',             'house_points' => 180, 'house_level' => 3],
            ['name' => 'Tânia Mendes',    'email' => 'membro20@aruanda.com', 'cpf' => '200.300.400-09', 'phone' => '(21) 92000-0020', 'birth_date' => '1983-07-07', 'points' => 240, 'level' => 3, 'role_membro' => 'dirigente auxiliar', 'house_points' => 560, 'house_level' => 7],
            ['name' => 'Ulisses Freitas', 'email' => 'membro21@aruanda.com', 'cpf' => '200.300.400-10', 'phone' => '(21) 92000-0021', 'birth_date' => '1979-11-30', 'points' => 220, 'level' => 3, 'role_membro' => 'dirigente auxiliar', 'house_points' => 520, 'house_level' => 6],
        ];

        foreach ($membrosHouse2Data as $i => $data) {
            $user = User::create([
                'name'             => $data['name'],
                'email'            => $data['email'],
                'password'         => $senha,
                'role'             => 'membro',
                'phone'            => $data['phone'],
                'cpf'              => $data['cpf'],
                'birth_date'       => $data['birth_date'],
                'points'           => $data['points'],
                'level'            => $data['level'],
                'lgpd_accepted_at' => now(),
            ]);

            $house2->members()->attach($user->id, [
                'role'         => 'membro',
                'role_membro'  => $data['role_membro'],
                'status'       => 'active',
                'joined_at'    => now()->subMonths(rand(3, 18)),
                'house_points' => $data['house_points'],
                'house_level'  => $data['house_level'],
            ]);
        }

        // ----------------------------------------------------------------
        // NOVOS VISITANTES PENDENTES NA CASA 1 (visitante2 a visitante11)
        // ----------------------------------------------------------------
        $visitantesHouse1Data = [
            [
                'name'       => 'Amanda Celestino',
                'email'      => 'devoto2@aruanda.com',
                'cpf'        => '300.400.500-01',
                'phone'      => '(11) 93000-0002',
                'birth_date' => '1997-04-18',
                'role_membro' => 'médium',
                'message'    => 'Sinto um chamado espiritual muito forte e acredito que este terreiro é o lugar certo para desenvolver minha mediunidade. Frequento giras há alguns meses e me sinto acolhida aqui.',
                'days_ago'   => 1,
            ],
            [
                'name'       => 'Bruno Matos',
                'email'      => 'devoto3@aruanda.com',
                'cpf'        => '300.400.500-02',
                'phone'      => '(11) 93000-0003',
                'birth_date' => '1993-09-05',
                'role_membro' => 'cambone',
                'message'    => 'Desejo ingressar nesta casa para servir com dedicação como cambone. Tenho grande respeito pela tradição da Umbanda e quero contribuir para os trabalhos espirituais.',
                'days_ago'   => 2,
            ],
            [
                'name'       => 'Claudia Serra',
                'email'      => 'devoto4@aruanda.com',
                'cpf'        => '300.400.500-03',
                'phone'      => '(11) 93000-0004',
                'birth_date' => '1990-01-22',
                'role_membro' => 'médium',
                'message'    => 'Venho buscar orientação espiritual e desenvolvimento da minha mediunidade nesta casa de luz. Os Orixás me guiaram até aqui e confio na missão desta casa.',
                'days_ago'   => 3,
            ],
            [
                'name'       => 'David Pinheiro',
                'email'      => 'devoto5@aruanda.com',
                'cpf'        => '300.400.500-04',
                'phone'      => '(11) 93000-0005',
                'birth_date' => '1988-06-14',
                'role_membro' => 'cambone',
                'message'    => 'Quero fazer parte desta família espiritual. Estou pronto para auxiliar nos trabalhos como cambone, contribuindo com humildade e dedicação para a caridade.',
                'days_ago'   => 4,
            ],
            [
                'name'       => 'Estela Monteiro',
                'email'      => 'devoto6@aruanda.com',
                'cpf'        => '300.400.500-05',
                'phone'      => '(11) 93000-0006',
                'birth_date' => '1995-11-30',
                'role_membro' => 'médium',
                'message'    => 'Desde criança sinto a presença das entidades. Agora, adulta, busco uma casa séria para desenvolver minha espiritualidade de forma responsável e comprometida.',
                'days_ago'   => 5,
            ],
            [
                'name'       => 'Felipe Azevedo',
                'email'      => 'devoto7@aruanda.com',
                'cpf'        => '300.400.500-06',
                'phone'      => '(11) 93000-0007',
                'birth_date' => '1992-03-08',
                'role_membro' => 'cambone',
                'message'    => 'Acompanhei várias sessões aqui no terreiro e me encantei com a seriedade e caridade dos trabalhos. Peço a oportunidade de me tornar cambone desta casa.',
                'days_ago'   => 6,
            ],
            [
                'name'       => 'Graça Teixeira',
                'email'      => 'devoto8@aruanda.com',
                'cpf'        => '300.400.500-07',
                'phone'      => '(11) 93000-0008',
                'birth_date' => '1985-08-19',
                'role_membro' => 'médium',
                'message'    => 'Minha família tem tradição na Umbanda e encontrei neste terreiro os mesmos valores que aprendi desde pequena. Desejo desenvolver minha mediunidade aqui com muito axé.',
                'days_ago'   => 7,
            ],
            [
                'name'       => 'Hudson Pereira',
                'email'      => 'devoto9@aruanda.com',
                'cpf'        => '300.400.500-08',
                'phone'      => '(11) 93000-0009',
                'birth_date' => '1999-05-02',
                'role_membro' => 'cambone',
                'message'    => 'Após um período difícil na minha vida, encontrei paz neste terreiro. Quero retribuir toda a ajuda recebida servindo como cambone e auxiliando outros que precisam.',
                'days_ago'   => 8,
            ],
            [
                'name'       => 'Isabela Cruz',
                'email'      => 'devoto10@aruanda.com',
                'cpf'        => '300.400.500-09',
                'phone'      => '(11) 93000-0010',
                'birth_date' => '1994-12-25',
                'role_membro' => 'médium',
                'message'    => 'Tenho recebido incorporações há dois anos e preciso de orientação adequada. Confio na experiência da Mãe Iracema e dos médiuns desta casa para me guiar nessa jornada.',
                'days_ago'   => 9,
            ],
            [
                'name'       => 'Jorge Leal',
                'email'      => 'devoto11@aruanda.com',
                'cpf'        => '300.400.500-10',
                'phone'      => '(11) 93000-0011',
                'birth_date' => '1980-07-11',
                'role_membro' => 'cambone',
                'message'    => 'Vim até esta casa após indicação de um amigo que recebeu atendimento espiritual aqui. Quero ser parte ativa desta comunidade como cambone e crescer espiritualmente.',
                'days_ago'   => 10,
            ],
        ];

        foreach ($visitantesHouse1Data as $data) {
            $user = User::create([
                'name'             => $data['name'],
                'email'            => $data['email'],
                'password'         => $senha,
                'role'             => 'visitante',
                'phone'            => $data['phone'],
                'cpf'              => $data['cpf'],
                'birth_date'       => $data['birth_date'],
                'points'           => 50,
                'level'            => 1,
                'lgpd_accepted_at' => now(),
            ]);

            $house1->members()->attach($user->id, [
                'role'        => 'membro',
                'role_membro' => $data['role_membro'],
                'status'      => 'pending',
                'message'     => $data['message'],
                'joined_at'   => now()->subDays($data['days_ago']),
            ]);
        }

        // ----------------------------------------------------------------
        // NOVOS VISITANTES PENDENTES NA CASA 2 (visitante12 a visitante21)
        // ----------------------------------------------------------------
        $visitantesHouse2Data = [
            [
                'name'       => 'Karla Nunes',
                'email'      => 'devoto12@aruanda.com',
                'cpf'        => '400.500.600-01',
                'phone'      => '(21) 94000-0012',
                'birth_date' => '1996-02-14',
                'role_membro' => 'médium',
                'message'    => 'Ogum me trouxe até este centro e sinto que aqui é o meu lugar. Desejo desenvolver minha mediunidade sob a orientação do Pai José e das entidades que trabalham nesta casa.',
                'days_ago'   => 1,
            ],
            [
                'name'       => 'Leonardo Dias',
                'email'      => 'devoto13@aruanda.com',
                'cpf'        => '400.500.600-02',
                'phone'      => '(21) 94000-0013',
                'birth_date' => '1991-10-20',
                'role_membro' => 'cambone',
                'message'    => 'Admiro profundamente o trabalho de caridade realizado por este centro. Gostaria de contribuir como cambone, auxiliando nas giras e nas atividades espirituais.',
                'days_ago'   => 2,
            ],
            [
                'name'       => 'Marina Campos',
                'email'      => 'devoto14@aruanda.com',
                'cpf'        => '400.500.600-03',
                'phone'      => '(21) 94000-0014',
                'birth_date' => '1998-06-09',
                'role_membro' => 'médium',
                'message'    => 'Busco aprimorar minha espiritualidade em um ambiente de respeito e seriedade. Este centro me acolheu nas consultas e agora desejo me comprometer como membra efetiva.',
                'days_ago'   => 3,
            ],
            [
                'name'       => 'Natan Ribeiro',
                'email'      => 'devoto15@aruanda.com',
                'cpf'        => '400.500.600-04',
                'phone'      => '(21) 94000-0015',
                'birth_date' => '1987-04-03',
                'role_membro' => 'cambone',
                'message'    => 'Tenho vivenciado muitas passagens espirituais e busco uma casa séria para me orientar. Quero servir como cambone, aprender com os mais experientes e praticar a caridade.',
                'days_ago'   => 4,
            ],
            [
                'name'       => 'Olívia Sousa',
                'email'      => 'devoto16@aruanda.com',
                'cpf'        => '400.500.600-05',
                'phone'      => '(21) 94000-0016',
                'birth_date' => '1993-08-27',
                'role_membro' => 'médium',
                'message'    => 'Os Pretos Velhos deste centro me orientaram em um momento muito difícil. Como forma de gratidão e compromisso espiritual, peço para fazer parte desta família de luz.',
                'days_ago'   => 5,
            ],
            [
                'name'       => 'Paulo Machado',
                'email'      => 'devoto17@aruanda.com',
                'cpf'        => '400.500.600-06',
                'phone'      => '(21) 94000-0017',
                'birth_date' => '1984-01-16',
                'role_membro' => 'cambone',
                'message'    => 'Cresci em família umbandista e sempre tive o desejo de servir em um terreiro. Este centro representa tudo o que aprendi sobre respeito, caridade e evolução espiritual.',
                'days_ago'   => 6,
            ],
            [
                'name'       => 'Quezia Viana',
                'email'      => 'devoto18@aruanda.com',
                'cpf'        => '400.500.600-07',
                'phone'      => '(21) 94000-0018',
                'birth_date' => '1997-11-05',
                'role_membro' => 'médium',
                'message'    => 'Recebo mensagens há algum tempo e preciso de um ambiente seguro para desenvolver essa habilidade. Encontrei no Centro Ogum Beira-Mar a seriedade e acolhimento que procuro.',
                'days_ago'   => 7,
            ],
            [
                'name'       => 'Rodrigo Cunha',
                'email'      => 'devoto19@aruanda.com',
                'cpf'        => '400.500.600-08',
                'phone'      => '(21) 94000-0019',
                'birth_date' => '1989-03-23',
                'role_membro' => 'cambone',
                'message'    => 'Frequento este centro há seis meses como visitante. Sinto que já faço parte desta família e gostaria de oficializar meu ingresso como cambone para servir com mais dedicação.',
                'days_ago'   => 8,
            ],
            [
                'name'       => 'Simone Araújo',
                'email'      => 'devoto20@aruanda.com',
                'cpf'        => '400.500.600-09',
                'phone'      => '(21) 94000-0020',
                'birth_date' => '1982-09-12',
                'role_membro' => 'médium',
                'message'    => 'Após anos afastada da religião, senti o chamado para retornar ao axé. Este centro foi indicado por um guia espiritual em consulta e peço a graça de fazer parte desta casa.',
                'days_ago'   => 9,
            ],
            [
                'name'       => 'Thiago Borges',
                'email'      => 'devoto21@aruanda.com',
                'cpf'        => '400.500.600-10',
                'phone'      => '(21) 94000-0021',
                'birth_date' => '1995-05-31',
                'role_membro' => 'cambone',
                'message'    => 'Sou estudante de filosofia e me aprofundei nos fundamentos da Umbanda. Quero vivenciar a prática espiritual neste centro de referência, servindo com humildade como cambone.',
                'days_ago'   => 10,
            ],
        ];

        foreach ($visitantesHouse2Data as $data) {
            $user = User::create([
                'name'             => $data['name'],
                'email'            => $data['email'],
                'password'         => $senha,
                'role'             => 'visitante',
                'phone'            => $data['phone'],
                'cpf'              => $data['cpf'],
                'birth_date'       => $data['birth_date'],
                'points'           => 50,
                'level'            => 1,
                'lgpd_accepted_at' => now(),
            ]);

            $house2->members()->attach($user->id, [
                'role'        => 'membro',
                'role_membro' => $data['role_membro'],
                'status'      => 'pending',
                'message'     => $data['message'],
                'joined_at'   => now()->subDays($data['days_ago']),
            ]);
        }

        // ----------------------------------------------------------------
        // EVENTOS (5 — completos, inalterados)
        // ----------------------------------------------------------------
        $event1 = Event::create([
            'house_id'        => $house1->id,
            'created_by'      => $dirigente1->id,
            'name'            => 'Gira de Caboclos — Lua Cheia',
            'slug'            => 'gira-caboclos-lua-cheia',
            'description'     => 'Gira especial de Caboclos em homenagem à lua cheia. Uma noite de muito axé, cura e consultas espirituais. Traga flores e frutas como oferendas. Roupa branca obrigatória.',
            'rules'           => "- Roupa branca obrigatória\n- Não trazer bebidas alcoólicas\n- Chegar com 15 minutos de antecedência\n- Respeitar o momento das giras\n- Celulares no silencioso",
            'recommendations' => "- Vela branca\n- Frutas da época\n- Roupas limpas e confortáveis\n- Vir em jejum leve (não obrigatório)",
            'starts_at'       => now()->addDays(7)->setTime(20, 0),
            'ends_at'         => now()->addDays(7)->setTime(23, 0),
            'price'           => 0,
            'capacity'        => 60,
            'status'          => 'open',
            'visibility'      => 'public',
            'address'         => 'Rua das Acácias, 123 — Vila Madalena, São Paulo/SP',
        ]);

        $event2 = Event::create([
            'house_id'    => $house1->id,
            'created_by'  => $dirigente1->id,
            'name'        => 'Festa de Oxum — Homenagem às Mães',
            'slug'        => 'festa-oxum-homenagem-maes',
            'description' => 'Celebração anual em homenagem a Oxum e às mães. Um evento especial com gira, palestra sobre o feminino sagrado e confraternização. Evento familiar aberto ao público.',
            'rules'       => "- Roupas nas cores de Oxum (dourado/amarelo) são bem-vindas\n- Evento familiar — crianças acompanhadas\n- Ingresso revertido para manutenção do terreiro",
            'starts_at'   => now()->addDays(21)->setTime(15, 0),
            'ends_at'     => now()->addDays(21)->setTime(20, 0),
            'price'       => 15.00,
            'capacity'    => 80,
            'status'      => 'open',
            'visibility'  => 'public',
            'address'     => 'Rua das Acácias, 123 — Vila Madalena, São Paulo/SP',
        ]);

        $event3 = Event::create([
            'house_id'    => $house1->id,
            'created_by'  => $dirigente1->id,
            'name'        => 'Palestra: Introdução à Umbanda',
            'slug'        => 'palestra-introducao-umbanda',
            'description' => 'Palestra introdutória sobre a filosofia e prática da Umbanda. Ideal para novos frequentadores e curiosos. Abordamos os fundamentos da religião, os Orixás, entidades e rituais.',
            'rules'       => "- Evento aberto ao público\n- Reserva de vagas recomendada\n- Perguntas ao final da palestra",
            'starts_at'   => now()->addDays(14)->setTime(19, 0),
            'ends_at'     => now()->addDays(14)->setTime(21, 30),
            'price'       => 0,
            'capacity'    => 40,
            'status'      => 'open',
            'visibility'  => 'public',
            'address'     => 'Rua das Acácias, 123 — Vila Madalena, São Paulo/SP',
        ]);

        $event4 = Event::create([
            'house_id'    => $house2->id,
            'created_by'  => $dirigente2->id,
            'name'        => 'Gira de Exu — Abertura de Caminhos',
            'slug'        => 'gira-exu-abertura-caminhos',
            'description' => 'Gira de Exu para abertura de caminhos e desobstrução espiritual. Trabalho sério de caridade com consultas individuais. Venha com sua intenção e aberto ao recebimento das mensagens.',
            'rules'       => "- Roupa preta ou vermelha\n- Proibido menores de 18 anos sem responsável\n- Consultas por ordem de chegada\n- Respeito total durante os trabalhos",
            'starts_at'   => now()->addDays(10)->setTime(21, 0),
            'ends_at'     => now()->addDays(11)->setTime(0, 0),
            'price'       => 0,
            'capacity'    => 50,
            'status'      => 'open',
            'visibility'  => 'public',
            'address'     => 'Av. Atlântica, 500, Sala 12 — Copacabana, Rio de Janeiro/RJ',
        ]);

        $event5 = Event::create([
            'house_id'    => $house2->id,
            'created_by'  => $dirigente2->id,
            'name'        => 'Workshop: Ervas Sagradas na Umbanda',
            'slug'        => 'workshop-ervas-sagradas-umbanda',
            'description' => 'Workshop prático sobre o uso de ervas sagradas nos rituais de Umbanda. Aprenda a identificar, colher e utilizar as principais ervas do nosso panteão. Inclui material didático.',
            'rules'       => "- Vagas limitadas — confirme presença\n- Traga caderno para anotações\n- Proibido gravar sem autorização",
            'starts_at'   => now()->addDays(30)->setTime(9, 0),
            'ends_at'     => now()->addDays(30)->setTime(13, 0),
            'price'       => 35.00,
            'capacity'    => 25,
            'status'      => 'open',
            'visibility'  => 'public',
            'address'     => 'Av. Atlântica, 500, Sala 12 — Copacabana, Rio de Janeiro/RJ',
        ]);

        // Inscrições nos eventos
        $membro->events()->attach($event1->id, ['status' => 'registered']);
        $membro->events()->attach($event2->id, ['status' => 'registered']);
        $visitante->events()->attach($event1->id,  ['status' => 'registered']);
        $assistente->events()->attach($event1->id, ['status' => 'registered']);
        $assistente->events()->attach($event3->id, ['status' => 'registered']);

        // ----------------------------------------------------------------
        // ESTUDOS (3, inalterados)
        // ----------------------------------------------------------------
        Study::create([
            'house_id'     => $house1->id,
            'created_by'   => $admin->id,
            'title'        => 'Origens da Umbanda',
            'slug'         => 'origens-da-umbanda',
            'description'  => 'Conheça a história e as raízes da Umbanda no Brasil.',
            'content_type' => 'text',
            'content_body' => "A Umbanda é uma religião brasileira que surgiu no início do século XX, incorporando elementos do espiritismo kardecista, das religiões afro-brasileiras, do catolicismo e das tradições indígenas.\n\nSua fundação oficial é atribuída ao médium Zélio Fernandino de Moraes, em 1908, na cidade de Niterói, Rio de Janeiro.\n\nA religião tem como base a caridade, o amor ao próximo e a evolução espiritual através do serviço ao outro.",
            'category'     => 'História',
            'points'       => 10,
            'order_column' => 1,
            'published'    => true,
        ]);

        Study::create([
            'house_id'     => $house1->id,
            'created_by'   => $admin->id,
            'title'        => 'Os Orixás: Divindades da Natureza',
            'slug'         => 'orixas-divindades-natureza',
            'description'  => 'Aprenda sobre os principais Orixás e seus domínios na natureza.',
            'content_type' => 'text',
            'content_body' => "Os Orixás são entidades espirituais que regem as forças da natureza e aspectos da vida humana.\n\nOxalá — O pai criador, representa a paz e a pureza. Sua cor é o branco.\n\nIemanjá — Rainha dos mares, protetora dos pescadores e das famílias. Cor: azul e branco.\n\nXangô — Orixá da justiça e do trovão. Cor: vermelho e branco.\n\nOgum — Guerreiro que abre caminhos. Cor: azul e vermelho.\n\nOxum — Orixá das águas doces e do amor. Cor: dourado e amarelo.",
            'category'     => 'Orixás',
            'points'       => 15,
            'order_column' => 2,
            'published'    => true,
        ]);

        Study::create([
            'house_id'     => $house2->id,
            'created_by'   => $admin->id,
            'title'        => 'Entidades: Caboclos e Pretos Velhos',
            'slug'         => 'entidades-caboclos-pretos-velhos',
            'description'  => 'Conheça as entidades que trabalham na Umbanda e seus ensinamentos.',
            'content_type' => 'text',
            'content_body' => "As entidades são espíritos que baixam nos terreiros para dar consultas e praticar a caridade.\n\nCaboclos — Espíritos de índios brasileiros, trabalham com força e cura pelas ervas. São energéticos e assertivos.\n\nPretos Velhos — Espíritos de escravos africanos, trabalham com paciência, humildade e sabedoria. Transmitem paz e conforto.",
            'category'     => 'Entidades',
            'points'       => 15,
            'order_column' => 3,
            'published'    => true,
        ]);

        // ----------------------------------------------------------------
        // PRODUTOS (4, inalterados)
        // ----------------------------------------------------------------
        Product::create([
            'store_id'    => $loja->id,
            'name'        => 'Vela Branca 7 Dias',
            'slug'        => 'vela-branca-7-dias',
            'description' => 'Vela votiva branca para 7 dias. Ideal para trabalhos de paz e proteção.',
            'price'       => 12.90,
            'stock'       => 50,
            'category'    => 'Velas',
            'status'      => 'active',
        ]);

        Product::create([
            'store_id'    => $loja->id,
            'name'        => 'Defumador Ervas Sagradas',
            'slug'        => 'defumador-ervas-sagradas',
            'description' => 'Incenso natural com ervas sagradas para defumação de ambientes.',
            'price'       => 18.50,
            'stock'       => 30,
            'category'    => 'Incensos',
            'status'      => 'active',
        ]);

        Product::create([
            'store_id'    => $loja->id,
            'name'        => 'Guia de Oxum — Colar Ritual',
            'slug'        => 'guia-oxum-colar-ritual',
            'description' => 'Guia ritual nas cores de Oxum (dourado e amarelo). Miçangas importadas.',
            'price'       => 45.00,
            'stock'       => 15,
            'category'    => 'Guias',
            'status'      => 'active',
        ]);

        Product::create([
            'store_id'        => $lojaMaster->id,
            'name'            => 'Livro: Umbanda Para Todos',
            'slug'            => 'livro-umbanda-para-todos',
            'description'     => 'Introdução completa à Umbanda para iniciantes e curiosos.',
            'price'           => 39.90,
            'wholesale_price' => 25.00,
            'stock'           => 20,
            'category'        => 'Livros',
            'is_wholesale'    => true,
            'status'          => 'active',
        ]);

        // ----------------------------------------------------------------
        // CONQUISTAS (5, inalteradas)
        // ----------------------------------------------------------------
        Achievement::create([
            'key'             => 'primeiro-passo',
            'name'            => 'Primeiro Passo',
            'description'     => 'Complete seu cadastro no Aruanda Digital.',
            'icon'            => '👣',
            'points_required' => 0,
        ]);

        Achievement::create([
            'key'             => 'devoto-fiel',
            'name'            => 'Devoto Fiel',
            'description'     => 'Acumule 100 pontos na plataforma.',
            'icon'            => '⭐',
            'points_required' => 100,
        ]);

        Achievement::create([
            'key'             => 'estudante-da-fe',
            'name'            => 'Estudante da Fé',
            'description'     => 'Leia seu primeiro estudo.',
            'icon'            => '📚',
            'points_required' => 10,
        ]);

        Achievement::create([
            'key'             => 'participante',
            'name'            => 'Participante',
            'description'     => 'Inscreva-se em seu primeiro evento.',
            'icon'            => '🎉',
            'points_required' => 20,
        ]);

        Achievement::create([
            'key'             => 'membro-da-comunidade',
            'name'            => 'Membro da Comunidade',
            'description'     => 'Entre em uma casa ou templo.',
            'icon'            => '🏠',
            'points_required' => 30,
        ]);
    }
}
