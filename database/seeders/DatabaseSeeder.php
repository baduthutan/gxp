<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Charter;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::table('admins')->insert([
            'name'       => 'Admin',
            'username'   => 'admin',
            'password'   => Hash::make('admin'),
            'photo'      => 'img/admin_pp/default.jpg',
            'email'      => Str::random(10) . '@gmail.com',
            'role'       => 'super admin',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ]);

        DB::table('banners')->insert([
            [
                'picture'    => 'img/slider/1.jpg',
                'url'        => '#',
                'is_active'  => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'picture'    => 'img/slider/2.jpg',
                'url'        => '#',
                'is_active'  => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'picture'    => 'img/slider/3.jpg',
                'url'        => '#',
                'is_active'  => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]
        ]);

        DB::table('master_areas')->insert([
            [
                'name'       => 'Jakarta - Bandara Internasional Soekarno-Hatta',
                'area_type'  => 'airport',
                'is_active'  => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'name'       => 'Jakarta Utara',
                'area_type'  => 'city',
                'is_active'  => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'name'       => 'Jakarta Timur',
                'area_type'  => 'city',
                'is_active'  => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'name'       => 'Jakarta Selatan',
                'area_type'  => 'city',
                'is_active'  => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'name'       => 'Jakarta Barat',
                'area_type'  => 'city',
                'is_active'  => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'name'       => 'Jakarta Pusat',
                'area_type'  => 'city',
                'is_active'  => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
        ]);

        DB::table('master_sub_areas')->insert([
            [
                'master_area_id' => 1,
                'name'           => 'Terminal 1A',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 1,
                'name'           => 'Terminal 1B',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 1,
                'name'           => 'Terminal 2D',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 1,
                'name'           => 'Terminal 2E',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 1,
                'name'           => 'Terminal 2F',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 1,
                'name'           => 'Terminal 3',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 2,
                'name'           => 'Cilincing',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 2,
                'name'           => 'Kelapa Gading',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 2,
                'name'           => 'Koja',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 2,
                'name'           => 'Pademangan',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 2,
                'name'           => 'Penjaringan',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 2,
                'name'           => 'Tanjung Priok',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 3,
                'name'           => 'Cakung',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 3,
                'name'           => 'Cipayung',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 3,
                'name'           => 'Ciracas',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 3,
                'name'           => 'Duren Sawit',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 3,
                'name'           => 'Jatinegara',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 3,
                'name'           => 'Kramat Jati',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 3,
                'name'           => 'Makasar',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 3,
                'name'           => 'Pasar Rebo',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 3,
                'name'           => 'Pulo Gadung',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 4,
                'name'           => 'Cilandak',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 4,
                'name'           => 'Jagakarsa',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 4,
                'name'           => 'Kebayoran baru',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 4,
                'name'           => 'Kebayoran Lama',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 4,
                'name'           => 'Mampang Prapatan',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 4,
                'name'           => 'Pancoran',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 4,
                'name'           => 'Pasar Minggu',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 4,
                'name'           => 'Pesanggrahan',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 4,
                'name'           => 'Setiabudi',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 4,
                'name'           => 'Tebet',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 5,
                'name'           => 'Cengkareng',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 5,
                'name'           => 'Grogol Petamburan',
                'is_active'      => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'master_area_id' => 5,
                'name'           => 'Taman Sari',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 5,
                'name'           => 'Tambora',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 5,
                'name'           => 'Kebon Jeruk',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 5,
                'name'           => 'Kalideres',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 5,
                'name'           => 'Palmerah',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 5,
                'name'           => 'Kembangan',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 6,
                'name'           => 'Cempaka Putih',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 6,
                'name'           => 'Gambir',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 6,
                'name'           => 'Johar Baru',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 6,
                'name'           => 'Kemayoran',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 6,
                'name'           => 'Menteng',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 6,
                'name'           => 'Sawah Besar',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 6,
                'name'           => 'Senen',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                'master_area_id' => 6,
                'name'           => 'Tanah Abang',
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
        ]);

        DB::table('master_special_areas')->insert([
            [
                'master_sub_area_id' => 7,
                'regional_name'        => "Kali Baru",
                'first_person_price' => 10,
                'extra_person_price' => 5,
                'is_active'          => true,
                'notes'              => null,
                'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'         => null,
            ],
            [
                'master_sub_area_id' => 7,
                'regional_name'        => "Cilincing",
                'first_person_price' => 10,
                'extra_person_price' => 5,
                'is_active'          => true,
                'notes'              => null,
                'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'         => null,
            ],
            [
                'master_sub_area_id' => 7,
                'regional_name'        => "Samper Barat",
                'first_person_price' => 10,
                'extra_person_price' => 5,
                'is_active'          => true,
                'notes'              => null,
                'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'         => null,
            ],
            [
                'master_sub_area_id' => 7,
                'regional_name'        => "Samper Timur",
                'first_person_price' => 10,
                'extra_person_price' => 5,
                'is_active'          => true,
                'notes'              => null,
                'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'         => null,
            ],
            [
                'master_sub_area_id' => 7,
                'regional_name'        => "Sukapura",
                'first_person_price' => 10,
                'extra_person_price' => 5,
                'is_active'          => true,
                'notes'              => null,
                'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'         => null,
            ],
            [
                'master_sub_area_id' => 7,
                'regional_name'        => "Rorotan",
                'first_person_price' => 10,
                'extra_person_price' => 5,
                'is_active'          => true,
                'notes'              => null,
                'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'         => null,
            ],
            [
                'master_sub_area_id' => 7,
                'regional_name'        => "Marunda",
                'first_person_price' => 10,
                'extra_person_price' => 5,
                'is_active'          => true,
                'notes'              => null,
                'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'         => null,
            ],
        ]);

        DB::table('pages')->insert([
            [
                'slug'         => 'privacy',
                'page_title'   => 'Privacy',
                'page_content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat, provident.'
            ],
            [
                'slug'         => 'term-and-condition',
                'page_title'   => 'Term and Condition',
                'page_content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores recusandae exercitationem ipsa ab amet! Sed tempore maxime officiis possimus molestiae!'
            ]
        ]);


        for ($i = 1; $i <= 23; $i += 3) {
            $dt = Carbon::createFromFormat('H:i:s', '00:00:00')->addHours($i);
            $data = [
                'from_type'               => 'airport',
                'from_master_area_id'     => 1,
                'from_master_sub_area_id' => null,
                'to_master_area_id'       => 2,
                'to_master_sub_area_id'   => 7,
                'vehicle_name'            => 'Avanza 1',
                'vehicle_number'          => 'B 1234 CCD',
                'time_departure'          => $dt->format("H:00:00"),
                'is_active'               => true,
                'photo'                   => 'img/vehicle/default.png',
                'price'                   => 25,
                'driver_contact'          => '+62123456789',
                'notes'                   => 'Lorem ipsum dolor sit amet.',
                'total_seat'              => 20,
                'luggage_price'           => 5,
                'created_at'              => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'              => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'              => null,
            ];
            DB::table('schedule_shuttles')->insert($data);
        }

        for ($i = 1; $i <= 23; $i += 3) {
            $dt = Carbon::createFromFormat('H:i:s', '00:00:00')->addHours($i);
            $data = [
                'from_type'               => 'city',
                'from_master_area_id'     => 2,
                'from_master_sub_area_id' => 7,
                'to_master_area_id'       => 1,
                'to_master_sub_area_id'   => null,
                'vehicle_name'            => 'Avanza 2',
                'vehicle_number'          => 'B 9876 CCD',
                'time_departure'          => $dt->format("H:00:00"),
                'is_active'               => true,
                'photo'                   => 'img/vehicle/default.png',
                'price'                   => 25,
                'driver_contact'          => '+62123456789',
                'notes'                   => 'Lorem ipsum dolor sit amet.',
                'total_seat'              => 20,
                'luggage_price'           => 5,
                'created_at'              => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'              => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'              => null,
            ];
            DB::table('schedule_shuttles')->insert($data);
        }

        DB::table('agents')->insert([
            [
                'name'     => 'Agent 1',
                'password' => 'agent1',
            ],
            [
                'name'     => 'Agent 2',
                'password' => 'agent2',
            ],
        ]);

        DB::table('vouchers')->insert([
            [
                // 'agent_id'       => 1,
                'name'           => 'Agent 1 Discount 10%',
                'code'           => 'agent1',
                'date_start'     => '2022-09-01',
                'date_expired'   => '2022-10-31',
                'discount_type'  => 'percentage',
                'discount_value' => 10,
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
            [
                // 'agent_id'       => 2,
                'name'           => 'Agent 2 Discount $5',
                'code'           => 'agent2',
                'date_start'     => '2022-09-01',
                'date_expired'   => '2022-10-31',
                'discount_type'  => 'value',
                'discount_value' => 5,
                'is_active'      => true,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'     => null,
            ],
        ]);

        $charter                          = new Charter();
        $charter->from_type               = 'airport';
        $charter->from_master_area_id     = 1;
        $charter->from_master_sub_area_id = null;
        $charter->to_master_area_id       = 2;
        $charter->to_master_sub_area_id   = 7;
        $charter->vehicle_name            = 'Avanza 1';
        $charter->vehicle_number          = 'B 1234 CCD';
        $charter->total_seat              = 20;
        $charter->photo                   = 'img/vehicle/default.png';
        $charter->price                   = 100;
        $charter->is_available            = true;
        $charter->driver_contact          = '+62123456789';
        $charter->notes                   = 'Lorem ipsum dolor sit amet.';
        $charter->save();

        $charter                          = new Charter();
        $charter->from_type               = 'airport';
        $charter->from_master_area_id     = 1;
        $charter->from_master_sub_area_id = null;
        $charter->to_master_area_id       = 2;
        $charter->to_master_sub_area_id   = 8;
        $charter->vehicle_name            = 'Avanza 2';
        $charter->vehicle_number          = 'B 9876 CCD';
        $charter->total_seat              = 20;
        $charter->photo                   = 'img/vehicle/default.png';
        $charter->price                   = 100;
        $charter->is_available            = true;
        $charter->driver_contact          = '+62123456789';
        $charter->notes                   = 'Lorem ipsum dolor sit amet.';
        $charter->save();

        $charter                          = new Charter();
        $charter->from_type               = 'city';
        $charter->from_master_area_id     = 2;
        $charter->from_master_sub_area_id = 7;
        $charter->to_master_area_id       = 1;
        $charter->to_master_sub_area_id   = null;
        $charter->vehicle_name            = 'Avanza 1';
        $charter->vehicle_number          = 'B 1234 CCD';
        $charter->total_seat              = 20;
        $charter->photo                   = 'img/vehicle/default.png';
        $charter->price                   = 100;
        $charter->is_available            = true;
        $charter->driver_contact          = '+62123456789';
        $charter->notes                   = 'Lorem ipsum dolor sit amet.';
        $charter->save();

        $charter                          = new Charter();
        $charter->from_type               = 'city';
        $charter->from_master_area_id     = 2;
        $charter->from_master_sub_area_id = 8;
        $charter->to_master_area_id       = 1;
        $charter->to_master_sub_area_id   = null;
        $charter->vehicle_name            = 'Avanza 2';
        $charter->vehicle_number          = 'B 9876 CCD';
        $charter->total_seat              = 20;
        $charter->photo                   = 'img/vehicle/default.png';
        $charter->price                   = 100;
        $charter->is_available            = true;
        $charter->driver_contact          = '+62123456789';
        $charter->notes                   = 'Lorem ipsum dolor sit amet.';
        $charter->save();
    }
}
