<?php

use Illuminate\Support\Facades\Mail;

class ExampleTest extends TestCase
{
    use MailTracking;

    /**
     * @test
     */
    public function an_email_is_sent()
    {
        Mail::raw('Hello World', function ($message) {
            $message->to('foo@bar');
            $message->from('biz@bar');
        });

        $this->seeEmailWasSent()
            ->seeEmailTo('foo@bar')
            ->seeEmailFrom('biz@bar');
    }

    /**
     * @test
     */
    public function two_emails_are_sent()
    {
        Mail::raw('Hello World', function ($message) {
            $message->to('foo@bar');
            $message->from('biz@bar');
        });

        Mail::raw('Howdy World', function ($message) {
            $message->to('foo@bar');
            $message->from('biz@bar');
        });

        $this->seeEmailsSent(2);
    }

    /**
     * @test
     */
    public function no_email_was_sent()
    {
        $this->seeNoEmailWasSent();
    }

    /**
     * @test
     */
    public function check_email_body()
    {
        Mail::raw('Hello World', function ($message) {
            $message->to('foo@bar');
            $message->from('biz@bar');
            $message->subject('Test 123');
        });

        $this->seeEmailEquals('Hello World');
    }

    /**
     * @test
     */
    public function check_email_body_contains()
    {
        Mail::raw('Hello World', function ($message) {
            $message->to('foo@bar');
            $message->from('biz@bar');
            $message->subject('Test 123');
        });

        $this->seeEmailContains('World');
    }

    /**
     * @test
     */
    public function check_email_subject()
    {
        Mail::raw('Hello World', function ($message) {
            $message->to('foo@bar');
            $message->from('biz@bar');
            $message->subject('Test 123');
        });

        $this->seeEmailSubject('Test 123');
    }
}
