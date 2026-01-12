<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestEmailCommand extends Command
{
    protected $signature = 'email:test {email}';
    protected $description = 'Test email configuration';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info('Testing email configuration...');
        $this->info('Sending to: ' . $email);
        
        try {
            Mail::raw('Test email dari Sistem Kesiswaan', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email - Sistem Kesiswaan');
            });
            
            $this->info('✓ Email sent successfully!');
            $this->info('Check your inbox (and spam folder)');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Email failed: ' . $e->getMessage());
            Log::error('Email test failed: ' . $e->getMessage());
            
            return 1;
        }
    }
}
