<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author laurent
 */
interface Paginable {
    public static function getByPagination($offset,$limit,$filtre=null);
    public static function countAll($filtre=null);
}
