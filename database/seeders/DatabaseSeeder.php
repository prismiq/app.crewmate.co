<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{User,Crew,Vessel,CertificateType,Certificate};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::query()->exists()) {
            User::factory()->create([
                'name' => 'Demo Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
        }

        $types = collect([
            ['name' => 'STCW Basic Safety Training', 'code' => 'STCW', 'validity_months' => 60],
            ['name' => 'ENG1 Medical Certificate', 'code' => 'ENG1', 'validity_months' => 24],
            ['name' => 'Passport', 'code' => 'PASS', 'validity_months' => 120],
            ['name' => "Seafarer's Discharge Book", 'code' => 'SDB', 'validity_months' => 60],
            ['name' => 'Security Awareness Training', 'code' => 'SAT', 'validity_months' => 60],
        ])->map(fn($t)=> CertificateType::firstOrCreate(['name'=>$t['name']], $t));

        if (!Crew::query()->exists()) {
            for ($i=1;$i<=5;$i++) {
                Crew::create([
                    'crew_code' => 'C'.str_pad($i,4,'0',STR_PAD_LEFT),
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'email' => null,
                    'rank' => fake()->randomElement(['Deckhand','Officer','Captain']),
                    'nationality' => fake()->country(),
                ]);
            }
        }

        Vessel::firstOrCreate(['name' => 'Nautilus']);

        // Seed a few certificates with varying expiry
        $crew = Crew::first();
        if ($crew) {
            $today = now();
            $map = [
                ['type' => 'STCW Basic Safety Training', 'issue' => $today->copy()->subYears(3), 'expiry' => $today->copy()->addYears(2)],
                ['type' => 'ENG1 Medical Certificate', 'issue' => $today->copy()->subYear(), 'expiry' => $today->copy()->addMonths(2)],
                ['type' => 'Passport', 'issue' => $today->copy()->subYears(8), 'expiry' => $today->copy()->addYear()],
            ];
            foreach ($map as $m) {
                $type = CertificateType::where('name', $m['type'])->first();
                $c = Certificate::create([
                    'crew_id' => $crew->id,
                    'certificate_type_id' => $type->id,
                    'issue_date' => $m['issue'],
                    'expiry_date' => $m['expiry'],
                    'status' => 'valid',
                ]);
                $c->status = $c->computeStatus();
                $c->save();
            }
        }
    }
}
