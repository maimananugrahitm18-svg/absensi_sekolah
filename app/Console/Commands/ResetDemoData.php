<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResetDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:reset {--force : Jalankan tanpa konfirmasi interaktif}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mereset ulang database sistem absensi ke kondisi awal data demo (fresh seed)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Memulai proses reset database demo...');

        Artisan::call('migrate:fresh', [
            '--seed'  => true,
            '--force' => true,
        ]);

        Artisan::call('optimize:clear');

        $this->info('✅ Database demo berhasil di-reset kembali ke data awal yang bersih!');
        return Command::SUCCESS;
    }
}
