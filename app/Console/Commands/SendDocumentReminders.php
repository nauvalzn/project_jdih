<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentReminderMail;
use App\Models\Document;
use Carbon\Carbon;

class SendDocumentReminders extends Command
{
    protected $signature = 'reminders:send {--email=* : Email penerima reminder (default dari user)}';
    protected $description = 'Send document reminders (human readable months + days)';

    public function handle()
    {
        $today = Carbon::now('Asia/Jakarta');

        $emails = $this->option('email');
        if (empty($emails)) {
            $emails = ['muhammad.sihabuddin259@smk.belajar.id'];
        }

        $documents = Document::where('jenis_dokumen', 5)->where('status_verifikasi', 2)->get();

        foreach ($documents as $document) {
            if (!$document->periode_berlaku) {
                continue;
            }

            $expiredAt = Carbon::parse($document->tanggal_penetapan)->addYears($document->periode_berlaku);

            $totalDays = (int) Carbon::now()->diffInDays($expiredAt, false);

            if ($totalDays < 0) {
                $text = 'sudah expired ' . abs($totalDays) . ' hari yang lalu';
            } elseif ($totalDays < 30) {
                $text = 'akan expired ' . $totalDays . ' hari lagi';
            } else {
                // Hitung bulan + sisa hari presisi kalender
                $months = 0;
                $tempDate = Carbon::now();
                while ($tempDate->addMonth()->lessThanOrEqualTo($expiredAt)) {
                    $months++;
                }
                $tempDate = Carbon::now()->addMonths($months);
                $days = $tempDate->diffInDays($expiredAt);

                if ($days > 0) {
                    $text = "akan expired {$months} bulan {$days} hari lagi";
                } else {
                    $text = "akan expired {$months} bulan lagi";
                }
            }

            foreach ($emails as $email) {
                Mail::to($email)->send(new DocumentReminderMail($document, $text, $expiredAt));
                $this->info("Pengingat dikirimkan ke: {$email} untuk dokumen '{$document->judul}' ({$text})");
            }
        }

        $this->info('Reminders check completed.');
    }
}
