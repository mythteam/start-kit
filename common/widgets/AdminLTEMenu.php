<?php

namespace common\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
use yii\helpers\ArrayHelper as Arr;

class AdminLTEMenu extends Menu
{
    /**
     * {@inheritdoc}
     */
    public $linkTemplate = '<a href="{url}">{icon} <span>{label}</span></a>';
    /**
     * @inheritdoc
     */
    public $submenuTemplate = "\n<ul class='treeview-menu' {show}>\n{items}\n</ul>\n";
    /**
     * @inheritdoc
     */
    public $labelTemplate = '<a href="javascript:;">{icon} <span>{label}</span><i class="fa fa-angle-left pull-right"></i></a>';
    /**
     * @inheritdoc
     */
    public $activateParents = true;
    /**
     * @inheritdoc
     */
    public $options = ['class' => 'sidebar-menu'];
    
    public $defaultIconClass = 'fa-circle-o';
    
    /**
     * {@inheritdoc}
     */
    protected function renderItem($item)
    {
        if (isset($item['items'])) {
            $linkTemplate = '<a href="{url}">{icon} <span>{label}</span> <i class="fa fa-angle-left pull-right"></i></a>';
        } else {
            $linkTemplate = $this->linkTemplate;
        }
        
        if (isset($item['url'])) {
            $template = Arr::getValue($item, 'template', $linkTemplate);
            
            $replace = [
                '{url}' => Url::to($item['url']),
                '{label}' => $item['label'],
                '{icon}' => !empty($item['icon']) ? '<i class="fa ' . $item['icon'] . '"></i> ' : '',
            ];
            
            return strtr($template, $replace);
        } else {
            $template = Arr::getValue($item, 'template', $this->labelTemplate);
            
            $replace = [
                '{label}' => $item['label'],
                '{icon}' => !empty($item['icon']) ? '<i class="fa ' . $item['icon'] . '"></i> ' : '',
            ];
            
            return strtr($template, $replace);
        }
    }
    
    /**
     * Recursively renders the menu items (without the container tag).
     *
     * @param array $items the menu items to be rendered recursively
     *
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, Arr::getValue($item, 'options', []));
            $tag = Arr::remove($options, 'tag', 'li');
            $class = [];
            if (isset($item['items'])) {
                $class[] = 'treeview';
            }
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }
            
            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $menu .= strtr($this->submenuTemplate, [
                    '{show}' => $item['active'] ? "style='display: block'" : '',
                    '{items}' => $this->renderItems($item['items']),
                ]);
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }
        
        return implode("\n", $lines);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if (!isset($item['label'])) {
                $item['label'] = '';
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $items[$i]['icon'] = isset($item['icon']) ? $item['icon'] : $this->defaultIconClass;
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }
            if (!isset($item['active'])) {
                if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
        }
        
        return array_values($items);
    }
    
    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     *
     * @param array $item the menu item to be checked
     *
     * @return bool whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }
            if (ltrim($route, '/') !== $this->route) {
                return false;
            }
            $arrayRoute = explode('/', ltrim($route, '/'));
            $arrayThisRoute = explode('/', $this->route);
            if ($arrayRoute[0] !== $arrayThisRoute[0]) {
                return false;
            }
            
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                foreach (array_splice($item['url'], 1) as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }
            
            return true;
        }
        
        return false;
    }
}