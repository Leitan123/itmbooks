<?php

/**
 * Created by PhpStorm.
 * User: Gowtham
 * Date: 5/4/2016
 * Time: 3:34 PM
 */
class Front_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('text');
        if (!defined('IMG')) define('IMG', base_url('images') . "/");
        if (!defined('UP')) define('UP', base_url('uploads') . "/");
        if (!defined('UPT')) define('UPT', base_url('uploads/thumbs') . "/");
    }

    function view($page, $data = [])
    {
//        $this->load->model('Brand_model', 'brand');
////        $data['brands_home'] = $this->brand->order_by("Order", "ASC")->limit(7)->get_all();
//        $data['brands'] = $this->brand->order_by("Order", "ASC")->get_all();
//
////        $this->load->model('Events_model', 'event');
//        $data['events'] = $this->event->order_by("Order", "ASC")->limit(3)->get_all();
//
//        $this->load->model('Sri_lanka_News_model', 'slnews');
//        $data['sl_news'] = $this->slnews->order_by("Order", "ASC")->limit(5)->get_all();

        $this->load->model('Category_model', 'category');
        $data['categories'] = $this->category->order_by("Order", "ASC")->get_all();
        $data['categories_menu'] = $this->category->order_by("Order", "ASC")->get_all();



//         $this->load->model('Subcategory_model', 'subcategory');
//        $data['menu'] = $this->category->with('sub')->order_by("Order", "ASC")->limit(10)->get_all();

        $this->load->model('Product_model', 'product');
        $data['foot_products'] = $this->product->order_by(8,'RANDOM')->limit(8)->get_all();


        $this->load->view($page, $data);
    }

    static function convertCurrency($amount, $from, $to){
        $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
        $data = file_get_contents($url);
        preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
        $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
        return round($converted, 3);
    }


}