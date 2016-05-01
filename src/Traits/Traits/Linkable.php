<?php

namespace Tshafer\Traits\Traits;

/**
 * Class Linkable.
 */
trait Linkable
{
    /**
     * @return string
     */
    public function getTableLinks()
    {
        $segments = request()->segments();

        if (!in_array('trashed', $segments)) {
            return implode(' ', [
              $this->getViewLink(),
              $this->getEditLInk(),
              $this->getDeleteLink(),
            ]);
        } else {
            return implode(' ', [
              $this->getRestoreLink(),
              $this->getDestroyLink(),
            ]);
        }
    }

    /**
     * @return string
     */
    public function getViewLink()
    {
        $route = $this->getPrefix().$this->getResourceName().'.show';
        if ($this->hasRoute($route)) {
            return link_to_route($route, 'View', $this->id,
              ['class' => 'btn btn-xs btn-primary']);
        }
    }

    /**
     * Get the prefix name.
     *
     * @return string
     */
    protected function getPrefix()
    {
        return isset($this->prefix) ? $this->prefix.'.' : 'admin.';
    }

    /**
     * Get the resource name.
     *
     * @return string
     */
    protected function getResourceName()
    {
        return $this->resourceName ?: strtolower(str_plural(class_basename($this)));
    }

    /**
     * @param $route
     *
     * @return bool
     */
    protected function hasRoute($route)
    {
        if (route()->has($route)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getEditLInk()
    {
        $route = $this->getPrefix().$this->getResourceName().'.edit';
        if ($this->hasRoute($route)) {
            return link_to_route($route, 'Edit', $this->id,
              ['class' => 'btn btn-xs btn-info']);
        }
    }

    /**
     * @return string
     */
    public function getDeleteLink()
    {
        $route = $this->getPrefix().$this->getResourceName().'.destroy';
        if ($this->hasRoute($route)) {
            return open([
              'route' => [$route, $this->id],
              'method' => 'delete',
              'style' => 'display: inline;',
            ])
            .submit('Delete', ['class' => 'btn btn-xs btn-danger'])
            .closedir();
        }
    }

    /**
     * @return string
     */
    public function getRestoreLink()
    {
        $route = $this->getPrefix().$this->getResourceName().'.restore';
        if ($this->hasRoute($route)) {
            return open([
              'route' => [$route, $this->id],
              'method' => 'patch',
              'style' => 'display: inline;',
            ])
            .submit('Restore', ['class' => 'btn btn-xs btn-danger'])
            .closedir();
        }
    }

    /**
     * @return string
     */
    public function getDestroyLink()
    {
        $route = $this->getPrefix().$this->getResourceName().'.forcedestroy';
        if ($this->hasRoute($route)) {
            return open([
              'route' => [$route, $this->id],
              'method' => 'post',
              'style' => 'display: inline;',
            ]).submit('Delete',
              ['class' => 'btn btn-xs btn-danger']).closedir();
        }
    }
}
