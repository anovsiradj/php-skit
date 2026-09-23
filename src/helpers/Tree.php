<?php

namespace anovsiradj\skit\helpers;

/**
 * Bangun struktur tree/flat/parents dari flat rows (array/objek) berelasi parent-field.
 * Sekali jalan O(n) — tidak N+1 seperti pola query-per-level.
 *
 * origin: C:\works\legacy\ditjen-migas-php\app\Helpers\KategoriHelper.php, C:\works\legacy\eplanning\new_eplanning_atr_bpn_2\app\MenuBuilder\RenderFromDatabaseData.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-23
 */
abstract class Tree
{
    /**
     * opts default.
     */
    protected static function opts(array $opts)
    {
        return array_merge([
            'id' => 'id',
            'parent' => 'id_cate',
            'child' => 'tree',
            'deepKey' => 'deep',
            'root' => null,      // null = auto (parent null/''/0/tidak dikenal = root)
            'sort' => null,      // callable utk siblings
        ], $opts);
    }

    protected static function keyOf($row, $key)
    {
        if (is_array($row)) {
            return $row[$key] ?? null;
        }
        return $row->{$key} ?? null;
    }

    protected static function setKey(&$row, $key, $value)
    {
        if (is_array($row)) {
            $row[$key] = $value;
            return;
        }
        $row->{$key} = $value;
    }

    /**
     * susun rows menjadi nested tree; children disimpan di $node[$child],
     * kedalaman (1-based) disimpan di $node[$deepKey].
     *
     * @return array daftar node root
     */
    public static function tree(array $rows, array $opts = [])
    {
        $o = static::opts($opts);
        extract($o, EXTR_SKIP); // $id,$parent,$child,$deepKey,$root,$sort

        $index = [];
        foreach ($rows as $i => $row) {
            $index[(string) static::keyOf($row, $id)] = $i;
        }

        $refs = [];
        foreach (array_keys($rows) as $i) {
            $refs[$i] = &$rows[$i];
            if (empty($refs[$i][$child]) || !is_array($refs[$i][$child])) {
                $refs[$i][$child] = [];
            }
        }

        $autoRoot = ($root === null);
        $tree = [];
        foreach ($rows as $i => $row) {
            $pVal = static::keyOf($row, $parent);
            $selfVal = (string) static::keyOf($row, $id);

            if ($autoRoot) {
                $isRoot = $pVal === null || $pVal === '' || $pVal === 0 || $pVal === '0'
                    || !isset($index[(string) $pVal]) || (string) $pVal === $selfVal;
            } else {
                $isRoot = (string) $pVal === (string) $root;
            }

            if ($isRoot) {
                $tree[] = &$refs[$i];
            } else {
                $refs[$index[(string) $pVal]][$child][] = &$refs[$i];
            }
        }
        unset($row, $refs);

        static::markDeep($tree, $deepKey, $child, 1);

        if ($sort) {
            static::sortTree($tree, $sort, $child);
        }

        return $tree;
    }

    /**
     * @internal isi $deepKey rekursif (aman walau urutan rows tidak parent-first)
     */
    protected static function markDeep(array &$tree, $deepKey, $child, $deep)
    {
        if ($deepKey === null) {
            return;
        }
        foreach ($tree as &$node) {
            static::setKey($node, $deepKey, $deep);
            if (!empty($node[$child])) {
                static::markDeep($node[$child], $deepKey, $child, $deep + 1);
            }
        }
        unset($node);
    }

    /**
     * sortir siblings rekursif.
     */
    public static function sortTree(array &$tree, callable $sort, string $child = 'tree')
    {
        usort($tree, $sort);
        foreach ($tree as &$node) {
            if (!empty($node[$child]) && is_array($node[$child])) {
                static::sortTree($node[$child], $sort, $child);
            }
        }
        unset($node);
        return $tree;
    }

    /**
     * ratakan nested tree kembali menjadi list (pre-order), tanpa isi children.
     */
    public static function flat(array $tree, string $child = 'tree')
    {
        $flat = [];
        $loop = function (array $list) use (&$loop, &$flat, $child) {
            foreach ($list as $item) {
                $kids = static::keyOf($item, $child) ?: [];
                static::setKey($item, $child, []);
                $flat[] = $item;
                $loop($kids);
            }
        };
        $loop($tree);
        return $flat;
    }

    /**
     * rantai parent dari root SAMPAI row yang diminta (termasuk row itu sendiri) — breadcrumb.
     * Loop-guard maks 99 level.
     */
    public static function parents(array $rows, $id, array $opts = [])
    {
        $o = static::opts($opts);

        $index = [];
        foreach ($rows as $row) {
            $index[(string) static::keyOf($row, $o['id'])] = $row;
        }

        $list = [];
        $guard = 0;
        while ($guard++ < 99 && isset($index[(string) $id])) {
            $row = $index[(string) $id];
            $list[] = $row;
            $id = static::keyOf($row, $o['parent']);
        }

        return array_reverse($list);
    }
}
