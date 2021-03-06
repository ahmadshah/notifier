<?php namespace Orchestra\Notifier;

use Illuminate\Support\Manager;

class NotifierManager extends Manager
{
    /**
     * Create Laravel driver.
     *
     * @return OrchestraNotifier
     */
    protected function createLaravelDriver()
    {
        return new LaravelNotifier($this->app['mailer']);
    }

    /**
     * Create Orchestra Platform driver.
     *
     * @return OrchestraNotifier
     */
    protected function createOrchestraDriver()
    {
        $notifier = new OrchestraNotifier($this->app['orchestra.mail']);
        $notifier->attach($this->app['orchestra.memory']->makeOrFallback());

        return $notifier;
    }

    /**
     * Get the default driver.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']->get('orchestra/notifier::driver', 'laravel');
    }

    /**
     * Set the default driver.
     *
     * @param  string  $name
     * @return string
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']->set('orchestra/notifier::driver', $name);
    }
}
