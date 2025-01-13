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
     */
    public function setEvents($events): static
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
     */
    public function setReferrers($referrers): static
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

    public function setPidInRootline(array $pidInRootline): void
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

    public function setUserAgents(array $userAgents): void
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
     * @return $this
     */
    public function setQuantifier($referrer, array $rawQuantifierConfiguration): static
    {
        $quantifier = $this->getQuantifierForReferrers($referrer, $rawQuantifierConfiguration, 1);
        $quantifier = $this->getQuantifierForUserAgent($quantifier, $rawQuantifierConfiguration);
        $quantifier = $this->getQuantifierForPidInRootline($quantifier, $rawQuantifierConfiguration);
        $this->quantifier = $quantifier;
        return $this;
    }

    /**
     * @param $referrer
     * @param int $quantifier
     * @return int
     */
    protected function getQuantifierForReferrers($referrer, array $rawQuantifierConfiguration, $quantifier)
    {
        foreach ($this->getReferrers() as $referrerPart) {
            $multiplier = 1;
            if (stristr((string) $referrer, (string) $referrerPart) !== false) {
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
     * @return int
     */
    protected function getQuantifierForUserAgent($quantifier, array $rawQuantifierConfiguration)
    {
        $visitorUserAgent = GeneralUtility::getIndpEnv('HTTP_USER_AGENT');
        foreach ($this->getUserAgents() as $userAgentPart) {
            $multiplier = 1;
            if (stristr($visitorUserAgent, (string) $userAgentPart) !== false) {
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
     * @return int
     */
    protected function getQuantifierForPidInRootline($quantifier, array $rawQuantifierConfiguration)
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
