<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $data =
        [
            [
                'brand' => 'Samsung',
                'model' => 'Galaxy S12',
                'price' => 1179.90,
                'color' => 'Black',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Samsung',
                'model' => 'Galaxy S12',
                'price' => 1079.90,
                'color' => 'Black',
                'memoryStorage' => '128GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Samsung',
                'model' => 'Galaxy S12',
                'price' => 1199.90,
                'color' => 'White',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Samsung',
                'model' => 'Galaxy S11+',
                'price' => 999.90,
                'color' => 'Black',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Samsung',
                'model' => 'Galaxy S11+',
                'price' => 1029.90,
                'color' => 'White',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Samsung',
                'model' => 'Note 12',
                'price' => 1290.90,
                'color' => 'Black',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Xiaomi',
                'model' => 'Redmi Note 9',
                'price' => 179.90,
                'color' => 'Black',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Xiaomi',
                'model' => 'Redmi Note 9 Pro',
                'price' => 209.90,
                'color' => 'Green',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Xiaomi',
                'model' => 'Mi 11',
                'price' => 550.00,
                'color' => 'Grey',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Xiaomi',
                'model' => 'Mi 11T Pro',
                'price' => 359.90,
                'color' => 'Black',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Xiaomi',
                'model' => 'Mi 11 Light',
                'price' => 279.90,
                'color' => 'White',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Apple',
                'model' => 'Iphone 13 Pro Max',
                'price' => 1179.90,
                'color' => 'Black',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Apple',
                'model' => 'Iphone 13 Pro',
                'price' => 879.90,
                'color' => 'Black',
                'memoryStorage' => '256GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],
            [
                'brand' => 'Apple',
                'model' => 'Iphone 13 Pro',
                'price' => 879.90,
                'color' => 'Black',
                'memoryStorage' => '128GB',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.'
            ],

        ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        foreach ($this->data as $row) {

            $product = new Product();

            $product->setBrand($row['brand'])
                ->setModel($row['model'])
                ->setColor($row['color'])
                ->setMemoryStorage($row['memoryStorage'])
                ->setPrice($row['price'])
                ->setDescription($row['description']);


            $manager->persist($product);

        }

        for ($c = 0; $c < 6; $c++){
            $customer = new Customer();
            $customer ->setName($faker->firstName)
                ->setEmail($faker->email)
                ->setPassword(password_hash("password",PASSWORD_BCRYPT));
            $manager->persist($customer);

            for ($u = 0; $u < mt_rand(1, 5); $u++) {
                $user = new User();
                $user->setFirstname($faker->firstName)
                    ->setLastname($faker->lastName)
                    ->setEmail($faker->email)
                    ->setCustomer($customer);
                $manager->persist($user);
            }
        }

        $manager->flush();
    }
}
