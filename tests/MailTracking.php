<?php

trait MailTracking
{
    protected $emails = [];

    /** @before */
    public function setUpEmail()
    {
        parent::setUp();

        Mail::getSwiftMailer()
            ->registerPlugin(new TestingMailEventListener($this));
    }

    public function addEmail(Swift_Message $email)
    {
        $this->emails[] = $email;
    }

    protected function seeEmailWasSent()
    {
        $this->assertNotEmpty($this->emails, 'No emails were sent!');

        return $this;
    }

    protected function seeEmailsSent($count)
    {
        $this->assertCount(
            $count,
            $this->emails,
            "Expected $count emails. Got " . count($this->emails) . '.'
        );

        return $this;
    }

    protected function seeNoEmailWasSent()
    {
        $this->assertEmpty($this->emails, 'No emails were expected.');
    }

    protected function seeEmailTo($to, Swift_Message $message = null)
    {
        $this->assertArrayHasKey(
            $to,
            $this->getEmail($message)->getTo(),
            "No email was sent to $to."
        );

        return $this;
    }

    protected function seeEmailFrom($from, Swift_Message $message = null)
    {
        $this->assertArrayHasKey(
            $from,
            $this->getEmail($message)->getFrom(),
            "No email was sent from $from."
        );

        return $this;
    }

    protected function seeEmailEquals($body, Swift_Message $message = null)
    {
        $this->assertEquals(
            $body,
            $this->getEmail($message)->getBody(),
            "No email with the provided body was sent."
        );
    }

    protected function seeEmailContains($excerpt, Swift_Message $message = null)
    {
        $this->assertContains(
            $excerpt,
            $this->getEmail($message)->getBody(),
            "No email with the provided excerpt was sent."
        );
    }

    protected function seeEmailSubject($subject, Swift_Message $message = null)
    {
        $this->assertEquals(
            $subject,
            $this->getEmail($message)->getSubject(),
            "No '$subject' subject found in the email."
        );
    }

    protected function getEmail(Swift_Message $message = null)
    {
        $this->seeEmailWasSent();

        $message = $message ?: $this->lastEmail();

        return $message;
    }

    protected function lastEmail()
    {
        return end($this->emails);
    }
}
