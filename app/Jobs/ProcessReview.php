<?php

namespace App\Jobs;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessReview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $workID,
        private readonly string $review,
        private readonly string $score,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        sleep(20);
        //Per fare qualche test adesso vado solo ad inserirlo in tabella
        $review = Review::create([
            'work_id' => $this->workID,
            'review' => $this->review,
            'score' => $this->score,
            'work_info' => [],
        ]);

    }
}
