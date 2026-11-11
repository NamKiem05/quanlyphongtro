<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    protected $signature = 'mail:test {email}';

    protected $description = 'Kiểm tra gửi email SMTP';

    public function handle(): int
    {
        $email = $this->argument('email');

        try {
            Mail::raw(
                'Xin chào! Đây là email kiểm tra SMTP từ hệ thống QuảnLýNhà.',
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Kiểm tra Gmail SMTP - QuảnLýNhà');
                }
            );

            $this->info('Đã gửi email kiểm tra thành công!');
            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error('Gửi email thất bại!');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}