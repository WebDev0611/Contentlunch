<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\WriterAccessComment;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWriterAccessCommentNotification extends Job implements ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    protected $comment;

    /**
     * Create a new job instance.
     * @param WriterAccessComment $comment
     */
    public function __construct (WriterAccessComment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     * @param Mailer $mailer
     * @return void
     */
    public function handle (Mailer $mailer)
    {
        $data = [
            'order_id' => $this->comment->order_id,
            'order_title' => $this->comment->order_title,
            'sender'      => '',
            'message'     => $this->comment->note,
            'timestamp'   => date('Y-m-d H:i', strtotime($this->comment->timestamp)),
        ];

        if ($this->comment->editor_name) {
            $data['sender'] = $this->comment->editor_name;
        }
        elseif ($this->comment->writer_name) {
            $data['sender'] = $this->comment->writer_name;
        }

        echo "\nSending email notification for comment with id of '" . $this->comment->id . "'... ";

        $mailer->send('emails.writeraccess_comment', ['data' => $data], function ($message) {
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                ->to($this->comment->user->email)
                ->subject('New Message from WriterAccess');
        });

        $this->comment->client_notified = true;
        $this->comment->save();
    }
}
