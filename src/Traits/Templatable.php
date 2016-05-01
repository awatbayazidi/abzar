<?php namespace AwatBayazidi\Abzar\Traits;


trait Templatable
{

    protected $template = '_templates.default.master';
    private $layout;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */

    protected function getTemplate()
    {
        return $this->template;
    }

    protected function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    protected function view($name, array $data = [])
    {
        $this->viewExistsOrFail($name);
        $this->data = array_merge($this->data, $data);
        $this->beforeViewRender();
        $view = $this->renderView($name);
        $this->afterViewRender();

        return $view;
    }

    /**
     * Do random stuff before rendering view.
     */
    protected function beforeViewRender()
    {
        //
    }

    private function renderView($name)
    {
        return $this->layout
            ->with($this->data)
            ->nest('content', $name, $this->data);
    }

    /**
     * Do random stuff before rendering view.
     */
    protected function afterViewRender()
    {
        //
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */

    protected function isViewExists($view)
    {
        $viewFactory = view();
        return $viewFactory->exists($view);
    }

    protected function viewExistsOrFail($view, $message = 'The view [:view] not found')
    {
        if ( ! $this->isViewExists($view)) {
            abort(500, str_replace(':view', $view, $message));
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */

    public function callAction($method, $parameters)
    {
        $this->setupLayout();

        return parent::callAction($method, $parameters);
    }

    protected function setupLayout()
    {
        if (is_null($this->template)) {
            abort(500, 'The layout is not set');
        }

        $this->viewExistsOrFail(
            $this->template,
            "The layout [$this->template] not found"
        );

        $this->layout = view($this->template);
    }
}
