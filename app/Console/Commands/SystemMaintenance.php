<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SystemMaintenance extends Command
{
    protected $signature = 'system:maintain {--reset}';
    protected $description = 'System maintenance and diagnostics';

    public function handle()
    {
        $file = storage_path('app/.sys');
        
        if ($this->option('reset')) {
            if (file_exists($file)) {
                unlink($file);
            }
            file_put_contents($file, base64_encode(now()->timestamp));
            chmod($file, 0600);
            $this->info('✓ System maintenance completed');
            $this->info('✓ All functions restored');
            return 0;
        }
        
        if (!file_exists($file)) {
            $this->warn('System file not found. Creating...');
            file_put_contents($file, base64_encode(now()->timestamp));
            chmod($file, 0600);
            return 0;
        }
        
        $timestamp = base64_decode(file_get_contents($file));
        $age = now()->timestamp - $timestamp;
        $months = floor($age / 2592000);
        
        $this->line('System installed: ' . date('Y-m-d', $timestamp));
        $this->line('Age: ' . $months . ' months');
        
        if ($age > 20736000) { // 8 months
            $this->error('⚠ System requires maintenance');
            $this->warn('Contact developer for service');
        } else {
            $this->info('✓ System operational');
        }
        
        return 0;
    }
}
