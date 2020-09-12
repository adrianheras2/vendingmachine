<?php

namespace InterfaceAdapters\Adapter;


class ApiAdapter
{
    public function adaptActions(string $actions): array
    {
        $aActions = explode(',', $actions);
        $aActions = array_map(function($e){
            return trim($e);
        }, $aActions);
        return $aActions;
    }
}
