<?php

function generate_header(){
    $pages = \App\Models\Page::whereRaw('type = "header" and  parent_page_id is null')->orderBy('orderNumber', 'asc')
        ->get();

    $html = '';

    foreach ($pages as $item) {
       if ($item->is_dropdown !== '1'){
           $html .= " <li class='nav-item'>
                            <a class='nav-link' href='{$item->url}'>{$item->page_title}</a>
                        </li>";
       }else{
           $html .= "<li class='nav-item dropdown'>
                    <a class='nav-link dropdown-toggle' href='/' id='navbarDropdown' role='button'
                       data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        $item->page_title
                    </a>
                    <div class='dropdown-menu' aria-labelledby='navbarDropdown'>
                        ";

           $html .= generate_sub_menu($item->id);
           $html .= "</div>
                </li>";
       }
    }

    return $html;
}

function generate_sub_menu($id){
    $pages = \App\Models\Page::where('parent_page_id', $id)->get();

    $html = '';
    foreach ($pages as $item){
        $html .= "<a class='dropdown-item' href='{$item->url}'>{$item->page_title}</a>";
    }

    return $html;
}

function generate_footer(){

      $pages = \App\Models\Page::whereRaw('type = "footer"')->orderBy('orderNumber', 'asc')
          ->get();
    $html = "";

    foreach ($pages as $item) {
            $html .= "<li><a href=' $item->url '>$item->page_title</a></li>";
    }

    return $html;
}

function get_orgin() {
    return isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : "";
}

function get_passenger($id) {
    $customer = \App\Models\BookingCustomer::where('booking_id', $id)->pluck('customer_name')->toArray();

    if (!empty($customer)){
        return implode(', ', $customer) ;
    }

    return '';
}
