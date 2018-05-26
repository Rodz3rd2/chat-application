<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        $limit = 30;
        $data  = [];

        $pictures = "/img/avatar{n}.png";

        for ($i = 1; $i <= $limit; $i++)
        {
            $email = $faker->email;

            $data[] = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $email,
                'password' => password_hash($email, PASSWORD_DEFAULT),
                'picture' => strtr($pictures, ["{n}" => rand(1, 5)])
            ];

            echo __CLASS__ . " => {$i}/{$limit}\n";
        }

        $this->insert('users', $data);
    }
}
