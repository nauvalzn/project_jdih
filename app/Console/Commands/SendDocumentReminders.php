<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentReminderMail;
use App\Models\Document;
use Carbon\Carbon;

class SendDocumentReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send document reminders (6 months before period expires)';

    public function handle()
    {
        $today = Carbon::now('Asia/Jakarta');

        // Ambil dokumen Perizinan yang diverifikasi
        $documents = Document::where('jenis_dokumen', 5)
            ->where('status_verifikasi', 2)
            ->get();

        foreach ($documents as $document) {
            if (!$document->periode_berlaku) {
                continue;
            }

            $expiredAt = Carbon::parse($document->tanggal_penetapan)
                ->addYears($document->periode_berlaku);

            $reminderAt = $expiredAt->copy()->subMonths(6);

            // 🔹 Kirim reminder jika hari ini sudah sama atau lewat tanggal reminder, tapi dokumen belum expired
            if ($today->greaterThanOrEqualTo($reminderAt) && $today->lessThan($expiredAt)) {
                
                $monthsLeft = $today->diffInMonths($expiredAt, false);
                $monthsLeft = (int) round($monthsLeft);

                if ($monthsLeft < 0) {
                    $monthsText = 'sudah expired ' . abs($monthsLeft) . ' bulan yang lalu';
                } elseif ($monthsLeft > 0) {
                    $monthsText = 'akan expired ' . $monthsLeft . ' bulan lagi';
                } else {
                    $monthsText = 'akan expired bulan ini';
                }

                Mail::to('rsudkesjaprovjabar@gmail.com')->send(new DocumentReminderMail($document, $monthsText, $expiredAt));
                $this->info("Pengingat dikirimkan untuk dokumen : '{$document->judul}' ({$monthsText})");
            } else {
                $this->info("Belum waktunya reminder untuk dokumen: '{$document->judul}' (ReminderAt: {$reminderAt->toDateString()})");
            }
        }

        $this->info('Reminders check completed.');
    }
}
