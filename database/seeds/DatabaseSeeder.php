<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        // App\Sigun::truncate();
        $this->call(SigunsTableSeeder::class);
        $this->command->info('Seeded: Siguns table');                   // 충남 시군

        $this->call(UsersTableSeeder::class);
        $this->command->info('Seeded: Users table');                    // 사용자(농협)

        $this->call(ActivatedUsersTableSeeder::class);
        $this->command->info('Seeded: Activated Users table');                    // 사용자(농협)

        $this->call(BudgetsTableSeeder::class);
        $this->command->info('Seeded: Budgets table');                  // 년도별 사업비

        $this->call(SmallFarmersTableSeeder::class);
        $this->command->info('Seeded: Small farmers table');            // 소규모영세농 일손필요농가 모집현황

        $this->call(MachineSupportersTableSeeder::class);
        $this->command->info('Seeded: Machine supporters table');  // 농작업지원 일반지원단 모집현황

        $this->call(LargeFarmersTableSeeder::class);
        $this->command->info('Seeded: Large farmers table');            // 대규모전업농 일손필요농가 모집현황

        $this->call(ManpowerSupportersTableSeeder::class);
        $this->command->info('Seeded: Manpower supporters table');          // 인력지원반 모집현황

        $this->call(StatusEducationPromotionsTableSeeder::class);
        $this->command->info('Seeded: Status education promotions table');  // 농기계지원반 모집현황

        // $this->call(StatusMachineSupportersTableSeeder::class);
        // $this->command->info('Seeded: Machine supporters table');  // 농기계지원반 모집현황

        // $this->call(StatusManpowerSupportersTableSeeder::class);
        // $this->command->info('Seeded: Manpower supporters table');  // 인력지원반 모집현황
        //
        // $this->call(StatusLaborPaymentsTableSeeder::class);
        // $this->command->info('Seeded: Status labor payments table');  // 센터운영비(인건비) 지급현황
        //
        // $this->call(StatusOperatingCostsTableSeeder::class);
        // $this->command->info('Seeded: Status operating costs table');  // 센터운영비(운영비) 지급현황
    }
}
