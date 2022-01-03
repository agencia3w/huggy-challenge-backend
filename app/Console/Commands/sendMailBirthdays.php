<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reader;
use App\Mail\EmailBirthdays;

class sendMailBirthdays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:birthdays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily mail birthdays';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $readers = Reader::allFromCache()->filter(function ($reader) {
            return $reader->birthday->isBirthday();
        })
            ->map(function (Reader $reader) {
                return [
                    'reader' => $reader->name,
                    'email' => $reader->email,
                    'books' => $reader->books_count,
                    'pages' => $reader->books->sum('pages')
                ];
            });

        foreach ($readers as $reader) {
            \Mail::to($reader['email'])->send(new EmailBirthdays($reader['reader'], $reader['books'], $reader['pages']));
        }
    }
}
