<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\ExcelExportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExportBookingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fileName;

    /**
     * Create a new job instance.
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $bookings = Booking::with(['tour', 'hotel'])->get();

        $mappedBookings = $bookings->map(function ($booking) {
            return [
                $booking->id,
                $booking->customer_name,
                $booking->customer_email,
                $booking->tour->name,
                $booking->hotel->name,
            ];
        })->toArray();

        $headings = ['ID', 'Name', 'Email', 'Tour Name', 'Hotel Name'];

        $exportService = new ExcelExportService($mappedBookings, $headings);

        Excel::store($exportService, $this->fileName, 'public');
    }
}
