<?php
namespace In2code\Ipandlanguageredirect\Domain\Model;

use In2code\Ipandlanguageredirect\Utility\PageUtility;

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
     * @var array
     */
    protected $pidInRootline = [];

    /**
     * @var float
     */
    protected $quantifier = 1.0;

    /**
     * Action constructor.
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->setEvents($configuration['events']);

        if (array_key_exists('referrers', $configuration)) {
            $this->setReferrers($configuration['referrers']);
        }
        if (array_key_exists('pidInRootline', $configuration)) {
            $this->setPidInRootline($configuration['pidInRootline']);
        }
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
     * @return array
     */
    public function getPidInRootline(): array
    {
        return $this->pidInRootline;
    }

    /**
     * @param array $pidInRootline
     * @return void
     */
    public function setPidInRootline(array $pidInRootline)
    {
        $this->pidInRootline = $pidInRootline;
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
        $pidInRootline = $this->getPidInRootline();
        if (!empty($pidInRootline)) {
            foreach ($pidInRootline as $pid) {
                if (PageUtility::isInCurrentRootline($pid)) {
                    $quantifier = 9999;
                    break;
                }
            }
        }
        $this->quantifier = $quantifier;
        return $this;
    }
}
