<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Charter;
use Illuminate\Support\Carbon;
use App\Models\ScheduleShuttle;
use Illuminate\Console\Command;

class ExpiredCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Booking Expired';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bookings = Booking::where('booking_status', '=', 'pending')->get();
        $now = Carbon::now();

        foreach ($bookings as $booking) {
            $id            = $booking->id;
            $schedule_id   = $booking->schedule_id;
            $schedule_type = $booking->schedule_type;
            $qty_adult     = $booking->qty_adult;
            $qty_baby      = $booking->qty_baby;
            $current       = Carbon::parse($booking->created_at);

            echo $now->format('Y-m-d H:i:s') . "<br/>";
            echo $current->format('Y-m-d H:i:s') . "<br/>";

            echo $current->diffInMinutes($now) . "<br />";

            if ($current->diffInMinutes($now) >= 10) {
                $exec                 = Booking::find($id);
                $exec->booking_status = "expired";
                $exec->payment_status = "failed";
                $exec->save();

                if ($schedule_type == "shuttle") {
                    $ss             = ScheduleShuttle::find($schedule_id);
                    $ss->total_seat = $ss->total_seat + $qty_adult + $qty_baby;
                    $ss->save();
                } else {
                    // $ss               = Charter::find($schedule_id);
                    // $ss->is_available = true;
                    // $ss->save();
                }
            }
        }
        return Command::SUCCESS;
    }
}
