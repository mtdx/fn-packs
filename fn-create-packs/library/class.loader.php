<?php

class FNCP_Loader
{
    protected $actions;
    protected $filters;

    public function __construct()
    {

        $this->actions = array();
        $this->filters = array();
    }

    /**
     * @param $hook
     * @param $component
     * @param $callback
     */
    public function add_action($hook, $component, $callback)
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback);
    }

    /**
     * @param $hooks
     * @param $hook
     * @param $component
     * @param $callback
     * @return array
     */
    private function add($hooks, $hook, $component, $callback)
    {
        $hooks[] = array(
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback
        );
        return $hooks;
    }

    /**
     * @param $hook
     * @param $component
     * @param $callback
     */
    public function add_filter($hook, $component, $callback)
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback);
    }

    public function run()
    {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']));
        }
        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']));
        }
    }
}