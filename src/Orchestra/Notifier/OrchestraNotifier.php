<?php namespace Orchestra\Notifier;

use Orchestra\Memory\Abstractable\Container;
use Illuminate\Auth\Reminders\RemindableInterface;

class OrchestraNotifier extends Container implements NotifierInterface
{
    /**
     * Mailer instance.
     *
     * @var Mailer
     */
    protected $mailer;

    /**
     * Construct a new Orchestra Platform notifier.
     *
     * @param  Mailer  $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send notification via API.
     *
     * @param  \Illuminate\Auth\Reminders\RemindableInterface  $user
     * @param  string                                          $subject
     * @param  string                                          $view
     * @param  array                                           $data
     * @return boolean
     */
    public function send(RemindableInterface $user, $subject, $view, array $data = array())
    {
        $sent = $this->mailer->push($view, $data, function ($message) use ($user, $subject) {
            $message->to($user->getReminderEmail());
            $message->subject($subject);
        });

        if ($this->isNotQueued()) {
            return (count($sent) > 0);
        }

        return true;
    }

    /**
     * Determine if mailer using queue.
     *
     * @return boolean
     */
    protected function isNotQueued()
    {
        if (! isset($this->memory)) {
            return true;
        }

        return (! $this->memory->get('email.queue', false));
    }
}