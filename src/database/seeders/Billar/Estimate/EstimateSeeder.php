<?php

namespace Database\Seeders\Billar\Estimate;

use App\Models\Billar\Estimates\Estimate;
use App\Models\Billar\Estimates\EstimateDetails;
use App\Models\Billar\Recurring\RecurringCycle;
use App\Models\Core\Auth\User;
use Carbon\Carbon;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class EstimateSeeder extends Seeder
{
    use DisableForeignKeys;

    public function run()
    {
        $this->disableForeignKeys();

        $user = User::query()->where('id', '!=', 1)->pluck('id')->toArray();
        $createdBy = User::query()->pluck('id')->toArray();

        $estimate = [
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 1,
                'date' => Carbon::now()->subMonth(),
                'status_id' => 10,
                'sub_total' => 5500, // 1-2
                'total' => 5500,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 2,
                'date' => Carbon::now()->subMonths(3),
                'status_id' => 10,
                'sub_total' => 3000, //3
                'total' => 3000,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 3,
                'date' => Carbon::now()->subMonths(6),
                'status_id' => 10,
                'sub_total' => 5000, //3-4
                'total' => 5000,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 4,
                'date' => Carbon::now()->subYears(1),
                'status_id' => 10,
                'sub_total' => 2900, //5-6
                'total' => 2900,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 5,
                'date' => date('Y-m-d', strtotime('-2 day')),
                'status_id' => 10,
                'sub_total' => 2700, // 7-8
                'total' => 2700,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 6,
                'date' => date('Y-m-d', strtotime('-3 day')),
                'status_id' => 10,
                'sub_total' => 1100, //9
                'total' => 1100,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 7,
                'date' => date('Y-m-d', strtotime('-6 day')),
                'status_id' => 10,
                'sub_total' => 220, // 10-11
                'total' => 220,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 8,
                'date' => date('Y-m-d'),
                'status_id' => 10,
                'sub_total' => 280, // 12-13
                'total' => 280,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 9,
                'date' => date('Y-m-d', strtotime('-21 day')),
                'status_id' => 10,
                'sub_total' => 1600, //14
                'total' => 1600,
                'created_by' => $createdBy[array_rand($createdBy)]
            ], [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 10,
                'date' => date('Y-m-d'),
                'status_id' => 10,
                'sub_total' => 3300, //14-15
                'total' => 3300,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 11,
                'date' => date('Y-m-d', strtotime('3 day')),
                'status_id' => 10,
                'sub_total' => 2800, // 6-7
                'total' => 2800,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 12,
                'date' => date('Y-m-d', strtotime('-14 day')),
                'status_id' => 10,
                'sub_total' => 2900, // 15-2
                'total' => 2900,
                'created_by' => $createdBy[array_rand($createdBy)]
            ], [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 13,
                'date' => date('Y-m-d', strtotime('-16 day')),
                'status_id' => 10,
                'sub_total' => 270, // 10-11
                'total' => 270,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 14,
                'date' => date('Y-m-d'),
                'status_id' => 10,
                'sub_total' => 1400, //11-12-13
                'total' => 1400,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],
            [
                'client_id' => $user[array_rand($user)],
                'estimate_number' => 15,
                'date' => date('Y-m-d', strtotime('-6 day')),
                'status_id' => 10,
                'sub_total' => 3600, //14-4
                'total' => 3600,
                'created_by' => $createdBy[array_rand($createdBy)]
            ],

        ];

        Estimate::query()->insert($estimate);


        $estimateDetails = [
            [
                'estimate_id' => 1,
                'product_id' => 1,
                'quantity' => 1,
                'price' => 5000,
            ],
            [
                'estimate_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
                'price' => 500,
            ],
            [
                'estimate_id' => 2,
                'product_id' => 3,
                'quantity' => 1,
                'price' => 3000,
            ],
            [
                'estimate_id' => 3,
                'product_id' => 4,
                'quantity' => 1,
                'price' => 2000,
            ],
            [
                'estimate_id' => 4,
                'product_id' => 5,
                'quantity' => 1,
                'price' => 1500,
            ],
            [
                'estimate_id' => 4,
                'product_id' => 6,
                'quantity' => 1,
                'price' => 1400,
            ],
            [
                'estimate_id' => 5,
                'product_id' => 7,
                'quantity' => 1,
                'price' => 1400,
            ],
            [
                'estimate_id' => 5,
                'product_id' => 8,
                'quantity' => 1,
                'price' => 1300,
            ],
            [
                'estimate_id' => 6,
                'product_id' => 9,
                'quantity' => 1,
                'price' => 1100,
            ],
            [
                'estimate_id' => 7,
                'product_id' => 10,
                'quantity' => 1,
                'price' => 100,
            ],
            [
                'estimate_id' => 7,
                'product_id' => 11,
                'quantity' => 1,
                'price' => 120,
            ],
            [
                'estimate_id' => 8,
                'product_id' => 12,
                'quantity' => 1,
                'price' => 150,
            ],
            [
                'estimate_id' => 8,
                'product_id' => 13,
                'quantity' => 1,
                'price' => 130,
            ],
            [
                'estimate_id' => 9,
                'product_id' => 14,
                'quantity' => 1,
                'price' => 1600,
            ],
            [
                'estimate_id' => 10,
                'product_id' => 14,
                'quantity' => 1,
                'price' => 1600,
            ], [
                'estimate_id' => 10,
                'product_id' => 15,
                'quantity' => 1,
                'price' => 1700,
            ],
            [
                'estimate_id' => 11,
                'product_id' => 6,
                'quantity' => 1,
                'price' => 1400,
            ],
            [
                'estimate_id' => 11,
                'product_id' => 7,
                'quantity' => 1,
                'price' => 1400,
            ], [
                'estimate_id' => 12,
                'product_id' => 2,
                'quantity' => 1,
                'price' => 500,
            ],
            [
                'estimate_id' => 12,
                'product_id' => 15,
                'quantity' => 1,
                'price' => 1700,
            ], [
                'estimate_id' => 13,
                'product_id' => 10,
                'quantity' => 1,
                'price' => 100,
            ], [
                'estimate_id' => 13,
                'product_id' => 11,
                'quantity' => 1,
                'price' => 120,
            ], [
                'estimate_id' => 14,
                'product_id' => 11,
                'quantity' => 1,
                'price' => 120,
            ], [
                'estimate_id' => 14,
                'product_id' => 12,
                'quantity' => 1,
                'price' => 150,
            ], [
                'estimate_id' => 14,
                'product_id' => 13,
                'quantity' => 1,
                'price' => 130,
            ], [
                'estimate_id' => 15,
                'product_id' => 14,
                'quantity' => 1,
                'price' => 1600,
            ], [
                'estimate_id' => 15,
                'product_id' => 4,
                'quantity' => 1,
                'price' => 2000,
            ],
        ];

        EstimateDetails::query()->insert($estimateDetails);
    }
}
