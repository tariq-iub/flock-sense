<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FarmExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get expense head IDs (assuming they are seeded from ExpenseSeeder)
        // We'll use common expense IDs for poultry farming

        $farmExpenses = [
            // Feed & Nutrition expenses (most common in poultry)
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 27, // Feed Purchases
                'expense_date' => Carbon::now()->subDays(30),
                'description' => 'Starter feed for new flock - 50 bags',
                'quantity' => 50,
                'unit' => 'bags',
                'unit_cost' => 2500.00,
                'amount' => null, // Will be auto-calculated
                'currency' => 'PKR',
                'reference_no' => 'FD-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 27, // Feed Purchases
                'expense_date' => Carbon::now()->subDays(25),
                'description' => 'Grower feed for developing chicks',
                'quantity' => 75,
                'unit' => 'bags',
                'unit_cost' => 2300.00,
                'amount' => null,
                'currency' => 'PKR',
                'reference_no' => 'FD-2024-002',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 27, // Feed Purchases
                'expense_date' => Carbon::now()->subDays(15),
                'description' => 'Finisher feed for mature birds',
                'quantity' => 100,
                'unit' => 'bags',
                'unit_cost' => 2200.00,
                'amount' => null,
                'currency' => 'PKR',
                'reference_no' => 'FD-2024-003',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Veterinary & Health expenses
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 23, // Veterinary Medicines & Vaccines
                'expense_date' => Carbon::now()->subDays(28),
                'description' => 'Newcastle Disease vaccine - Day 1',
                'quantity' => 5000,
                'unit' => 'doses',
                'unit_cost' => 3.50,
                'amount' => null,
                'currency' => 'PKR',
                'reference_no' => 'VET-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 23, // Veterinary Medicines & Vaccines
                'expense_date' => Carbon::now()->subDays(21),
                'description' => 'IBD vaccine - Day 7',
                'quantity' => 5000,
                'unit' => 'doses',
                'unit_cost' => 4.20,
                'amount' => null,
                'currency' => 'PKR',
                'reference_no' => 'VET-2024-002',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 24, // Veterinary Consultation
                'expense_date' => Carbon::now()->subDays(20),
                'description' => 'Regular flock health inspection',
                'quantity' => null,
                'unit' => null,
                'unit_cost' => null,
                'amount' => 5000.00,
                'currency' => 'PKR',
                'reference_no' => 'VET-2024-003',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 25, // Biosecurity Measures
                'expense_date' => Carbon::now()->subDays(27),
                'description' => 'Disinfectants and fumigation chemicals',
                'quantity' => 20,
                'unit' => 'liters',
                'unit_cost' => 850.00,
                'amount' => null,
                'currency' => 'PKR',
                'reference_no' => 'BIO-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Utilities & Overheads
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 11, // Electricity
                'expense_date' => Carbon::now()->subDays(5),
                'description' => 'Monthly electricity bill for shed lighting and ventilation',
                'quantity' => null,
                'unit' => null,
                'unit_cost' => null,
                'amount' => 45000.00,
                'currency' => 'PKR',
                'reference_no' => 'ELEC-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 12, // Water Supply
                'expense_date' => Carbon::now()->subDays(5),
                'description' => 'Monthly water supply charges',
                'quantity' => null,
                'unit' => null,
                'unit_cost' => null,
                'amount' => 8000.00,
                'currency' => 'PKR',
                'reference_no' => 'WATER-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 13, // Fuel & Gas
                'expense_date' => Carbon::now()->subDays(12),
                'description' => 'LPG for heating during cold nights',
                'quantity' => 100,
                'unit' => 'kg',
                'unit_cost' => 280.00,
                'amount' => null,
                'currency' => 'PKR',
                'reference_no' => 'FUEL-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Labor & Staffing
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 7, // Salaries & Wages
                'expense_date' => Carbon::now()->subDays(1),
                'description' => 'Monthly salary for 3 farm workers',
                'quantity' => null,
                'unit' => null,
                'unit_cost' => null,
                'amount' => 90000.00,
                'currency' => 'PKR',
                'reference_no' => 'SAL-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 8, // Contract/Temporary Labor
                'expense_date' => Carbon::now()->subDays(10),
                'description' => 'Extra labor for shed cleaning and litter removal',
                'quantity' => 5,
                'unit' => 'days',
                'unit_cost' => 1500.00,
                'amount' => null,
                'currency' => 'PKR',
                'reference_no' => 'LAB-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Maintenance & Repairs
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 15, // Machinery & Equipment Maintenance
                'expense_date' => Carbon::now()->subDays(18),
                'description' => 'Feeding system repair and maintenance',
                'quantity' => null,
                'unit' => null,
                'unit_cost' => null,
                'amount' => 12000.00,
                'currency' => 'PKR',
                'reference_no' => 'MAINT-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 16, // Building & Premises Maintenance
                'expense_date' => Carbon::now()->subDays(22),
                'description' => 'Shed roof leak repair',
                'quantity' => null,
                'unit' => null,
                'unit_cost' => null,
                'amount' => 18000.00,
                'currency' => 'PKR',
                'reference_no' => 'MAINT-2024-002',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Raw Material & Input Costs
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 4, // Primary Raw Materials (Day-old chicks)
                'expense_date' => Carbon::now()->subDays(32),
                'description' => 'Day-old broiler chicks - Batch purchase',
                'quantity' => 5000,
                'unit' => 'chicks',
                'unit_cost' => 65.00,
                'amount' => null,
                'currency' => 'PKR',
                'reference_no' => 'CHICK-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 5, // Packing Materials
                'expense_date' => Carbon::now()->subDays(8),
                'description' => 'Plastic crates for bird transport',
                'quantity' => 50,
                'unit' => 'crates',
                'unit_cost' => 450.00,
                'amount' => null,
                'currency' => 'PKR',
                'reference_no' => 'PACK-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Operational Expenses
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 3, // Cleaning & Sanitation Supplies
                'expense_date' => Carbon::now()->subDays(14),
                'description' => 'Sanitizers, brushes, and cleaning equipment',
                'quantity' => null,
                'unit' => null,
                'unit_cost' => null,
                'amount' => 6500.00,
                'currency' => 'PKR',
                'reference_no' => 'CLEAN-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 14, // Waste Disposal
                'expense_date' => Carbon::now()->subDays(7),
                'description' => 'Litter and dead bird disposal service',
                'quantity' => null,
                'unit' => null,
                'unit_cost' => null,
                'amount' => 15000.00,
                'currency' => 'PKR',
                'reference_no' => 'WASTE-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Miscellaneous
            [
                'farm_id' => 1,
                'shed_id' => 1,
                'flock_id' => 9,
                'expense_head_id' => 36, // Insurance
                'expense_date' => Carbon::now()->subDays(30),
                'description' => 'Livestock insurance premium - Monthly',
                'quantity' => null,
                'unit' => null,
                'unit_cost' => null,
                'amount' => 25000.00,
                'currency' => 'PKR',
                'reference_no' => 'INS-2024-001',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('farm_expenses')->insert($farmExpenses);
    }
}
