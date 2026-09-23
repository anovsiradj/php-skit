<?php

namespace anovsiradj\skit\helpers;

/**
 * Builder array menu nested (link/title/dropdown) — framework-agnostic.
 * Render HTML-nya jadi urusan view layer.
 *
 * origin: C:\works\legacy\eplanning\new_eplanning_atr_bpn_2\app\MenuBuilder\MenuBuilder.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-23
 */
class MenuBuilder
{
    protected array $menu = [];
    protected int $deep = 0;

    protected function element($id, $slug, $name, $icon, $iconType, $sequence, array $extra = [])
    {
        $el = array_merge([
            'id' => $id,
            'slug' => $slug,
            'name' => $name,
            'hasIcon' => !(empty($icon)),
            'sequence' => $sequence,
        ], $extra);

        if (!empty($icon)) {
            $el['icon'] = $icon;
            $el['iconType'] = $iconType;
        }

        return $el;
    }

    /**
     * sisipkan element ke dropdown paling dalam yang sedang dibuka.
     */
    protected function pushToLastDropdown(array $element, int $offset = 0)
    {
        $menu = &$this->menu;
        $z = 1;
        while (!empty($menu)) {
            $last = count($menu) - 1;
            if (!isset($menu[$last]['elements'])) {
                break;
            }
            if ($z == $this->deep - $offset) {
                $menu[$last]['elements'][] = $element;
                return true;
            }
            $menu = &$menu[$last]['elements'];
            $z++;
        }
        // fallback: taruh di root
        $this->menu[] = $element;
        return false;
    }

    public function addLink($id, $name, $href, $icon = false, $iconType = 'bootstrap-icons', $sequence = 0)
    {
        $el = $this->element($id, 'link', $name, $icon, $iconType, $sequence, ['href' => $href]);
        if ($this->deep > 0) {
            $this->pushToLastDropdown($el);
        } else {
            $this->menu[] = $el;
        }
        return $this;
    }

    public function addTitle($id, $name, $icon = false, $iconType = 'bootstrap-icons', $sequence = 0)
    {
        $this->menu[] = $this->element($id, 'title', $name, $icon, $iconType, $sequence);
        return $this;
    }

    public function beginDropdown($id, $name, $icon = false, $iconType = 'bootstrap-icons', $sequence = 0)
    {
        $el = $this->element($id, 'dropdown', $name, $icon, $iconType, $sequence, ['elements' => []]);
        if ($this->deep === 0) {
            $this->menu[] = $el;
        } else {
            $this->pushToLastDropdown($el, 0);
        }
        $this->deep++;
        return $this;
    }

    public function endDropdown()
    {
        $this->deep = max(0, $this->deep - 1);
        return $this;
    }

    public function getResult(): array
    {
        return $this->menu;
    }

    /**
     * bangun builder dari rows (hasil Tree::tree) — konversi node {name,href,children...}.
     */
    public static function fromRows(array $treeRows, array $map = []): static
    {
        $map = array_merge([
            'id' => 'id', 'name' => 'name', 'href' => 'href',
            'icon' => 'icon', 'child' => 'tree',
        ], $map);

        $builder = new static();
        $walk = function (array $nodes) use (&$walk, $builder, $map) {
            foreach ($nodes as $node) {
                $kids = $node[$map['child']] ?? [];
                $id = $node[$map['id']] ?? null;
                $name = $node[$map['name']] ?? '';
                $href = $node[$map['href']] ?? '#';
                $icon = $node[$map['icon']] ?? false;
                if (!empty($kids)) {
                    $builder->beginDropdown($id, $name, $icon);
                    $walk($kids);
                    $builder->endDropdown();
                } else {
                    $builder->addLink($id, $name, $href, $icon);
                }
            }
        };
        $walk($treeRows);

        return $builder;
    }
}
