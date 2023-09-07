<?php

namespace In2code\Ipandlanguageredirect\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ActionSet
 */
class ActionSet
{
    /**
     * @var Action[]
     */
    protected $actions = [];

    /**
     * @var array
     */
    protected $rawQuantifierConfiguration = [];

    /**
     * @var array
     */
    protected $rawActionsConfiguration = [];

    /**
     * ActionSet constructor.
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->rawQuantifierConfiguration = $configuration['quantifier'];
        $this->rawActionsConfiguration = $configuration['actions'];
        foreach ($this->rawActionsConfiguration as $actionConfiguration) {
            $action = GeneralUtility::makeInstance(
                Action::class,
                $actionConfiguration
            );
            $this->addAction($action);
        }
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        $events = ['none'];
        $bestFittingAction = $this->getBestFittingAction();
        if ($bestFittingAction !== null) {
            $events = $bestFittingAction->getEvents();
        }
        return $events;
    }

    /**
     * Calculate quantifiers for Configuration
     *
     * @param string $referrer
     */
    public function calculateQuantifiers($referrer = '')
    {
        $actions = $this->getActions();
        foreach ($actions as $action) {
            $action->setQuantifier($referrer, $this->rawQuantifierConfiguration);
        }
    }

    /**
     * Return action with the highest quantifier
     *
     * @return Action|null
     */
    protected function getBestFittingAction()
    {
        $bestAction = null;
        foreach ($this->getActions() as $action) {
            /** @var Action $bestAction */
            if ($bestAction === null || $action->getQuantifier() > $bestAction->getQuantifier()) {
                $bestAction = $action;
            }
        }
        return $bestAction;
    }

    /**
     * @return Action[]
     */
    protected function getActions()
    {
        return $this->actions;
    }

    /**
     * @param $actions
     * @return $this
     */
    protected function setActions($actions)
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * @param Action $action
     * @return $this
     */
    protected function addAction(Action $action)
    {
        $this->actions[] = $action;
        return $this;
    }
}
