<?php
namespace In2code\Ipandlanguageredirect\Domain\Model;

/**
 * Class Action
 */
class Action
{

    /**
     * @var array
     */
    protected $events = [];

    /**
     * @var array
     */
    protected $referrers = [];

    /**
     * @var float
     */
    protected $quantifier = 1.0;

    /**
     * Action constructor.
     * @param array $events
     * @param array $referrers
     */
    public function __construct(array $events, array $referrers)
    {
        $this->setEvents($events);
        $this->setReferrers($referrers);
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param array $events
     * @return Action
     */
    public function setEvents($events)
    {
        $this->events = $events;
        return $this;
    }

    /**
     * @return array
     */
    public function getReferrers()
    {
        return $this->referrers;
    }

    /**
     * @param array $referrers
     * @return Action
     */
    public function setReferrers($referrers)
    {
        $this->referrers = $referrers;
        return $this;
    }

    /**
     * @return float
     */
    public function getQuantifier()
    {
        return $this->quantifier;
    }

    /**
     * @param string $referrer
     * @param array $rawQuantifierConfiguration
     * @return $this
     */
    public function setQuantifier($referrer, array $rawQuantifierConfiguration)
    {
        $quantifier = 1;
        foreach ($this->getReferrers() as $referrerPart) {
            $multiplier = 1;
            if (stristr($referrer, $referrerPart)) {
                // direct match
                $multiplier = (int)$rawQuantifierConfiguration['actions']['referrers']['totalMatch'];
            } elseif ($referrerPart === '*') {
                // wildcardmatch
                $multiplier = (int)$rawQuantifierConfiguration['actions']['referrers']['wildCardMatch'];
            }
            if ($multiplier > 0) {
                $quantifier *= $multiplier;
            }
        }
        $this->quantifier = $quantifier;
        return $this;
    }
}
