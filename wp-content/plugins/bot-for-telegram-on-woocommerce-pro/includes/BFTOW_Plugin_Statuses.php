<?php


class BFTOW_Plugin_Statuses
{
    private $status;

    const ACTIVATED = 'activated';
    const NOT_ACTIVATED = 'not_activated';

    /**
     * @throws Exception
     */
    public function __construct($status = null)
    {
        $this->validateStatus($status ?: self::NOT_ACTIVATED);
        $this->status = $status ?: self::NOT_ACTIVATED;
    }

    public function isActivated() : bool
    {
		return 'activated';
        return $this->status === self::ACTIVATED;
    }

    public function isNotActivated(): bool
    {
        return !$this->isActivated() || $this->status === self::NOT_ACTIVATED;
    }

    /**
     * @throws Exception
     */
    private function validateStatus($status)
    {
        if ($status && !in_array($status, [self::NOT_ACTIVATED, self::ACTIVATED])) {
            throw new \Exception('Invalid status provided!');
        }
    }
}
