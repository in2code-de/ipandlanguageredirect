<?php

namespace In2code\Ipandlanguageredirect\Domain\Model;

use In2code\Ipandlanguageredirect\Utility\PageUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
     * @var array
     */
    protected $userAgents = [];

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
        if (array_key_exists('userAgent', $configuration)) {
            $this->setUserAgents($configuration['userAgent']);
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
    public function getPidInRootline()
    {
        return $this->pidInRootline;
    }

    /**
     * @param array $pidInRootline
     */
    public function setPidInRootline(array $pidInRootline)
    {
        $this->pidInRootline = $pidInRootline;
    }

    /**
     * @return array
     */
    public function getUserAgents()
    {
        return $this->userAgents;
    }

    /**
     * @param array $userAgents
     */
    public function setUserAgents(array $userAgents)
    {
        $this->userAgents = $userAgents;
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
        $quantifier = $this->getQuantifierForReferrers($referrer, $rawQuantifierConfiguration, 1);
        $quantifier = $this->getQuantifierForUserAgent($quantifier, $rawQuantifierConfiguration);
        $quantifier = $this->getQuantifierForPidInRootline($quantifier, $rawQuantifierConfiguration);
        $this->quantifier = $quantifier;
        return $this;
    }

    /**
     * @param $referrer
     * @param array $rawQuantifierConfiguration
     * @param int $quantifier
     * @return int
     */
    protected function getQuantifierForReferrers($referrer, $rawQuantifierConfiguration, $quantifier)
    {
        foreach ($this->getReferrers() as $referrerPart) {
            $multiplier = 1;
            if (stristr($referrer, $referrerPart) !== false) {
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
        return $quantifier;
    }

    /**
     * @param int $quantifier
     * @param array $rawQuantifierConfiguration
     * @return int
     */
    protected function getQuantifierForUserAgent($quantifier, $rawQuantifierConfiguration)
    {
        $visitorUserAgent = GeneralUtility::getIndpEnv('HTTP_USER_AGENT');
        foreach ($this->getUserAgents() as $userAgentPart) {
            $multiplier = 1;
            if (stristr($visitorUserAgent, $userAgentPart) !== false) {
                // direct match
                $multiplier = (int)$rawQuantifierConfiguration['actions']['userAgents']['totalMatch'];
            } elseif ($userAgentPart === '*') {
                // wildcardmatch
                $multiplier = (int)$rawQuantifierConfiguration['actions']['userAgents']['wildCardMatch'];
            }
            if ($multiplier > 0) {
                $quantifier *= $multiplier;
            }
        }
        return $quantifier;
    }

    /**
     * @param int $quantifier
     * @param array $rawQuantifierConfiguration
     * @return int
     */
    protected function getQuantifierForPidInRootline($quantifier, $rawQuantifierConfiguration)
    {
        $pidInRootline = $this->getPidInRootline();
        if (!empty($pidInRootline)) {
            foreach ($pidInRootline as $pid) {
                if (PageUtility::isInCurrentRootline($pid)) {
                    $quantifier = (int)$rawQuantifierConfiguration['actions']['pidInRootline'];
                    break;
                }
            }
        }
        return $quantifier;
    }
}
