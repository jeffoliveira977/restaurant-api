<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Table;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\OrderItem;
use Faker\Factory as Faker;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->count(3)->create();
        
       Table::factory()->count(10)->create();

        $appetizers  = MenuCategory::create([
            'name' => 'Entradas',
            'description' => 'Pequenos pratos para começar a refeição',
        ]);

        $mainCourses = MenuCategory::create([
            'name' => 'Pratos Principais',
            'description' => 'Deliciosos pratos principais',
        ]);

        $desserts = MenuCategory::create([
            'name' => 'Sobremesas',
            'description' => 'Doces para finalizar a refeição',
        ]);

        $beverages = MenuCategory::create([
            'name' => 'Bebidas',
            'description' => 'Bebidas para complementar a refeição',
        ]);


        MenuItem::create([
            'name' => 'Pão de Queijo',
            'description' => 'Bolinhos de queijo assados, típicos de Minas Gerais',
            'price' => 6.50,
            'category_id' => $appetizers ->id,
            'preparation_time' => 15,
        ]);

        MenuItem::create([
            'name' => 'Coxinha',
            'description' => 'Massa frita recheada com frango desfiado e catupiry',
            'price' => 7.00,
            'category_id' => $appetizers ->id,
            'preparation_time' => 20,
        ]);

        MenuItem::create([
            'name' => 'Feijoada',
            'description' => 'Ensopado de feijão preto com diversas carnes de porco e boi',
            'price' => 28.90,
            'category_id' => $mainCourses ->id,
            'preparation_time' => 60,
        ]);

        MenuItem::create([
            'name' => 'Moqueca de Camarão',
            'description' => 'Ensopado de camarões com leite de coco, azeite de dendê, pimentões e tomate',
            'price' => 32.50,
            'category_id' => $mainCourses ->id,
            'preparation_time' => 40,
        ]);

        MenuItem::create([
            'name' => 'Bobó de Camarão',
            'description' => 'Creme de mandioca com camarões, leite de coco e azeite de dendê',
            'price' => 30.00,
            'category_id' => $mainCourses->id,
            'preparation_time' => 45,
        ]);

        MenuItem::create([
            'name' => 'Pudim de Leite Condensado',
            'description' => 'Sobremesa clássica brasileira feita com leite condensado',
            'price' => 8.00,
            'category_id' => $desserts->id,
            'preparation_time' => 50,
        ]);

        MenuItem::create([
            'name' => 'Brigadeiro',
            'description' => 'Doce tradicional brasileiro feito com leite condensado, chocolate e granulado',
            'price' => 3.50,
            'category_id' => $desserts->id,
            'preparation_time' => 10,
        ]);

        MenuItem::create([
            'name' => 'Suco de Maracujá',
            'description' => 'Suco natural de maracujá',
            'price' => 5.00,
            'category_id' => $beverages->id,
            'preparation_time' => 5,
        ]);

        MenuItem::create([
            'name' => 'Guaraná',
            'description' => 'Refrigerante brasileiro feito com extrato da fruta guaraná',
            'price' => 4.50,
            'category_id' => $beverages->id,
            'preparation_time' => 2,
        ]);

        MenuItem::create([
            'name' => 'Açaí na Tigela',
            'description' => 'Purê de açaí congelado com banana e granola',
            'price' => 12.00,
            'category_id' => $desserts->id,
            'preparation_time' => 7,
        ]);

        // Create customers
        Customer::factory()->count(10)->create();

        $faker = Faker::create();
        $orders = [];
 
        for ($i = 0; $i < 10; $i++) {
            $numberOfItems = $faker->numberBetween(1, 5);

            $orderItems = [];
            for ($j = 0; $j < $numberOfItems; $j++) {
                $orderItems[] = [
                    'menu_item_id' => $faker->numberBetween(1, 10),
                    'quantity' => $faker->numberBetween(1, 3),
                    'notes' => null
                ];
            }

            $orders[] = [
                'table_id' => $faker->numberBetween(1, 10),
                'customer_id' => $faker->numberBetween(1, 10),
                'notes' => null,
                'items' => $orderItems,
            ];
        }

        foreach ($orders as $orderData) {

            $order = Order::create([
                'table_id' => $orderData['table_id'],
                'customer_id' => $orderData['customer_id'],
                'waiter_id' => $user->random()->id,
                'status' => $faker->randomElement(['pending', 'in_progress', 'completed']),
                'total_amount' => 0,
                'notes' => null,
            ]);
        
            // Update table status
            $table = Table::find($orderData['table_id']);
            $table->update(['status' => 'occupied']);

            $totalAmount = 0;

            // Create order items
            foreach ($orderData['items'] as $item) {
                $menuItem = MenuItem::find($item['menu_item_id']);
                $subtotal = $menuItem->price * $item['quantity'];
                $totalAmount += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $menuItem->price,
                    'subtotal' => $subtotal,
                    'notes' => $item['notes'] ?? null,
                    'status' => 'pending',
                ]);
            }

            $order->update(['total_amount' => $totalAmount]);
        }   
    }
}