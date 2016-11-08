<?

if ($path[0] == "Prod") {
    if ($path[3] == "Attr") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_attribute.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_attribute.php';
            include $r_path . 'scripts/info_prod_attribute.php';
        }
    } else if ($path[3] == "Attr") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_attribute.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_attribute.php';
            include $r_path . 'scripts/info_prod_attribute.php';
        }
    } else if ($path[3] == "Feat") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_features.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_features.php';
            include $r_path . 'scripts/info_prod_features.php';
        }
    } else if ($path[3] == "Spec") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_specs.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_specs.php';
            include $r_path . 'scripts/info_prod_specs.php';
        }
    } else if ($path[3] == "Review") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_review.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_review.php';
            include $r_path . 'scripts/info_prod_review.php';
        }
    } else if ($path[3] == "Rating") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_rating.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_rating.php';
            include $r_path . 'scripts/info_prod_rating.php';
        }
    } else if ($path[3] == "Images") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_images.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_image.php';
            if ($cont == "query") {
                include $r_path . 'scripts/info_products.php';
                include $r_path . 'scripts/query_prod_images.php';
            } else {
                include $r_path . 'scripts/info_prod_image.php';
            }
        }
    } else if ($path[3] == "Movies") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_movies.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_movies.php';
            if ($cont == "query") {
                include $r_path . 'scripts/info_products.php';
                include $r_path . 'scripts/query_prod_movies.php';
            } else {
                include $r_path . 'scripts/info_prod_movies.php';
            }
        }
    } else if ($path[3] == "Sounds") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_sounds.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_sounds.php';
            if ($cont == "query") {
                include $r_path . 'scripts/info_products.php';
                include $r_path . 'scripts/query_prod_sounds.php';
            } else {
                include $r_path . 'scripts/info_prod_sounds.php';
            }
        }
    } else if ($path[3] == "Groups") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_groups.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_groups.php';
            include $r_path . 'scripts/info_prod_groups.php';
        }
    } else if ($path[3] == "Specl") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_specl.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_specl.php';
            include $r_path . 'scripts/info_prod_specl.php';
        }
    } else if ($path[3] == "Sel") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_sel_group.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            if ($cont == "view") {
                $cont = "edit";
            }
            include $r_path . 'scripts/save_prod_sel_group.php';
            if ($cont == "query") {
                include $r_path . 'scripts/info_products.php';
                include $r_path . 'scripts/query_prod_sel_group.php';
            } else {
                include $r_path . 'scripts/info_prod_sel_group.php';
            }
        }
    } else if ($path[3] == "Disc") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_discount_codes.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_discount_codes.php';
            include $r_path . 'scripts/info_prod_discount_codes.php';
        }
    } else if ($path[3] == "Doc") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_products.php';
            include $r_path . 'scripts/query_prod_documents.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_prod_documents.php';
            if ($cont == "query") {
                include $r_path . 'scripts/info_products.php';
                include $r_path . 'scripts/query_prod_documents.php';
            } else {
                include $r_path . 'scripts/info_prod_documents.php';
            }
        }
    } else if ($path[3] == "Rel") {
        $cont = "view";
        if ($path[1] == "Bord") {
            include $r_path . 'scripts/info_borders.php';
        } else {
            include $r_path . 'scripts/info_products.php';
        }
        include $r_path . 'scripts/save_prod_relationship.php';
        include $r_path . 'scripts/info_prod_relationship.php';
        $cont = "edit";
    } else if ($path[3] == "Key") {
        $cont = "view";
        if ($path[1] == "Bord") {
            include $r_path . 'scripts/info_borders.php';
        } else {
            include $r_path . 'scripts/info_products.php';
        }
        include $r_path . 'scripts/save_prod_keywords.php';
        include $r_path . 'scripts/info_prod_keywords.php';
        $cont = "edit";
    } else if ($path[3] == "Warnt") {
        $cont = "view";
        if ($path[1] == "Bord") {
            include $r_path . 'scripts/info_borders.php';
        } else {
            include $r_path . 'scripts/info_products.php';
        }
        include $r_path . 'scripts/save_prod_warranty.php';
        include $r_path . 'scripts/info_prod_warranty.php';
        $cont = "edit";
    } else if ($path[3] == "Bill") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_manufactures.php';
            include $r_path . 'scripts/query_man_bill.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_man_bill.php';
            include $r_path . 'scripts/info_man_bill.php';
        }
    } else if ($path[3] == "Ship") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_manufactures.php';
            include $r_path . 'scripts/query_man_ship.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_man_ship.php';
            include $r_path . 'scripts/info_man_ship.php';
        }
    } else if ($path[3] == "Whare") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_manufactures.php';
            include $r_path . 'scripts/query_man_ware.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_man_ware.php';
            include $r_path . 'scripts/info_man_ware.php';
        }
    } else if ($path[3] == "Cont") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_manufactures.php';
            include $r_path . 'scripts/query_man_cont.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_man_cont.php';
            include $r_path . 'scripts/info_man_cont.php';
        }
    } else if ($path[3] == "Sale") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_manufactures.php';
            include $r_path . 'scripts/query_man_sale.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_man_sale.php';
            include $r_path . 'scripts/info_man_sale.php';
        }
    } else if ($path[3] == "Acct") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_manufactures.php';
            include $r_path . 'scripts/query_man_rep.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_man_rep.php';
            include $r_path . 'scripts/info_man_rep.php';
        }
    } else if ($path[1] == "Serv") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_services.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_services.php';
        }
    } else if ($path[1] == "Prod") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_products.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_products.php';
        }
    } else if ($path[1] == "Bord") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_borders.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_borders.php';
        }
    } else if ($path[1] == "Cat") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_categories.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_category.php';
            if ($cont == "view") {
                include $r_path . 'scripts/query_categories.php';
            }
        }
    } else if ($path[1] == "Discount") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_discount_codes.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_discount_codes.php';
            include $r_path . 'scripts/info_discount_codes.php';
        }
    } else if ($path[1] == "Notes") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_event_notifications.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_event_notifications.php';
            include $r_path . 'scripts/info_event_notifications.php';
        }
    } else if ($path[1] == "Pricing Group") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_pricing_groups.php';
        // } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
        } else if ($cont == "view" || $cont == "edit") {
            include $r_path . 'scripts/save_pricing_group.php';
            include $r_path . 'scripts/info_pricing_group.php';
        }
    } else if ($path[1] == "Feat") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_features.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_features.php';
            if ($cont == "view") {
                include $r_path . 'scripts/query_features.php';
            }
        }
    } else if ($path[1] == "Spec") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_specs.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_specs.php';
            if ($cont == "view") {
                include $r_path . 'scripts/query_specs.php';
            }
        }
    } else if ($path[1] == "Attrib") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_attribute.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_attribute.php';
            if ($cont == "view") {
                include $r_path . 'scripts/query_attribute.php';
            }
        }
    } else if ($path[1] == "Group") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_groups.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_group.php';
            if ($cont == "view") {
                include $r_path . 'scripts/query_groups.php';
            }
        }
    } else if ($path[1] == "Specl") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_specl.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_specl.php';
            include $r_path . 'scripts/info_specl.php';
        }
    } else if ($path[1] == "Sel") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_selections.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_selections.php';
            include $r_path . 'scripts/info_selections.php';
        }
    } else if ($path[1] == "Man") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_manufactures.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_manufactures.php';
        }
    }
} else if ($path[0] == "Ntfc") {
    if ($path[1] == "Ntfc") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_notifications.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_notifications.php';
            if ($cont == "query") {
                include $r_path . 'scripts/query_notifications.php';
            } else {
                include $r_path . 'scripts/info_notifications.php';
            }
        }
    }
} else if ($path[0] == "BillShip") {
    if ($path[1] == "Credit") {
        include $r_path . 'scripts/query_credit.php';
    } else if ($path[1] == "Ship") {
        include $r_path . 'scripts/query_shipping.php';
    } else if ($path[1] == "State") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_state_taxes.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_state_taxes.php';
        }
    } else if ($path[1] == "Count") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_county_taxes.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_county_taxes.php';
        }
    }
} else if ($path[0] == "Cust") {
    if ($path[3] == "Proj") {
        if ($path[5] == "Time") {
            if ($cont == "query") {
                include $r_path . 'scripts/info_project.php';
                include $r_path . 'scripts/query_proj_timesheet.php';
            } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
                include $r_path . 'scripts/save_proj_timesheet.php';
                include $r_path . 'scripts/info_proj_timesheet.php';
            }
        } else {
            if ($cont == "query") {
                include $r_path . 'scripts/info_customer.php';
                include $r_path . 'scripts/query_cust_projects.php';
            } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
                include $r_path . 'scripts/info_project.php';
            }
        }
    } else if ($path[3] == "Inv") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_customer.php';
            include $r_path . 'scripts/query_cust_photo_invoices.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_cust_photo_invoices.php';
            include $r_path . 'scripts/info_cust_photo_invoices.php';
        }
    } else if ($path[3] == "Rept") {
        include $r_path . 'scripts/info_customer.php';
        include $r_path . 'scripts/save_rept_commision.php';
        include $r_path . 'scripts/info_rept_commision.php';
    } else if ($path[3] == "Inv") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_customer.php';
            //include $r_path.'scripts/query_man_sale.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            //include $r_path.'scripts/save_man_sale.php';
            //include $r_path.'scripts/info_man_sale.php';
        }
    } else if ($path[3] == "Order") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_customer.php';
            //include $r_path.'scripts/query_man_sale.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            //include $r_path.'scripts/save_man_sale.php';
            //include $r_path.'scripts/info_man_sale.php';
        }
    } else if ($path[3] == "Contr") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_customer.php';
            //include $r_path.'scripts/query_man_sale.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            //include $r_path.'scripts/save_man_sale.php';
            //include $r_path.'scripts/info_man_sale.php';
        }
    } else if ($path[3] == "Cont") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_customer.php';
            //include $r_path.'scripts/query_man_sale.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            //include $r_path.'scripts/save_man_sale.php';
            //include $r_path.'scripts/info_man_sale.php';
        }
    } else if ($path[3] == "Bill") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_customer.php';
            //include $r_path.'scripts/query_man_sale.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            //include $r_path.'scripts/save_man_sale.php';
            //include $r_path.'scripts/info_man_sale.php';
        }
    } else if ($path[3] == "Ship") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_customer.php';
            //include $r_path.'scripts/query_man_sale.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            //include $r_path.'scripts/save_man_sale.php';
            //include $r_path.'scripts/info_man_sale.php';
        }
    } else if ($path[1] == "Online") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_customers_online.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_customers.php';
            include $r_path . 'scripts/info_customer.php';
        }
    } else{//if ($path[1] == "All" || $path[1] == "Inactive" || $path[1] == "Trial" || $path[1] == "Unsigned" || $path[1] == "Deleted" || $path[1] == "Hold"){
        if ($cont == "query") {
            include $r_path . 'scripts/query_customers.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_customers.php';
            include $r_path . 'scripts/info_customer.php';
        }
    } /*else if ($path[1] == "Hold" ) {
        if ($cont == "query") {
            include $r_path . 'scripts/query_customers.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_customer.php';
        }
    }*/
} else if ($path[0] == "Evnt") {
    if ($path[1] == "Act") {
        include $r_path . 'scripts/save_active_event.php';
        include $r_path . 'scripts/info_active_event.php';
    }
} else if ($path[0] == "Inv") {
    if ($path[1] == "Paid") {
        include $r_path . 'scripts/query_invoice_paid.php';
    } elseif ($path[1] == "Open" || $path[1] == "Ship" || $path[1] == "All") {
        if ($cont == "search") {
            include $r_path . 'scripts/search_admin_photo_invoices.php';
        } else if ($cont == "query") {
            //include $r_path.'scripts/query_invoice.php';
            include $r_path . 'scripts/query_admin_photo_invoices.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_admin_photo_invoices.php';
            include $r_path . 'scripts/info_admin_photo_invoices.php';
        }
    } else if ($path[1] == "All") { 
        include $r_path . 'scripts/query_invoice_all.php';
    }
} else if ($path[0] == "Rept") {
    if ($path[1] == "Daily") {
        if (isset($_GET['id'])) {
            include $r_path . 'scripts/view_rept_daily.php';
        } else {
            include $r_path . 'scripts/query_rept_daily.php';
        }
    } else if ($path[1] == "Monthly") {
        if (isset($_GET['id'])) {
            include $r_path . 'scripts/view_rept_month.php';
        } else {
            include $r_path . 'scripts/query_rept_month.php';
        }
    } else if ($path[1] == "Man") {
        if (isset($_GET['id'])) {
            include $r_path . 'scripts/view_rept_man.php';
        } else {
            include $r_path . 'scripts/query_rept_man.php';
        }
    } else if ($path[1] == "High") {
        if (isset($_GET['id'])) {
            include $r_path . 'scripts/view_rept_high.php';
        } else {
            include $r_path . 'scripts/query_rept_high.php';
        }
    } else if ($path[1] == "Comm") {
        include $r_path . 'scripts/save_rept_commision.php';
        include $r_path . 'scripts/info_rept_commision.php';
    } else if ($path[1] == "Tax") {
        include $r_path . 'scripts/save_rept_taxes.php';
        include $r_path . 'scripts/info_rept_taxes.php';
    }
} else if ($path[0] == "Proj") {
    if ($path[3] == "Time") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_project.php';
            include $r_path . 'scripts/query_proj_timesheet.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_proj_timesheet.php';
            include $r_path . 'scripts/info_proj_timesheet.php';
        }
    } else if ($path[3] == "Asset") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_project.php';
            include $r_path . 'scripts/query_proj_asset.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_proj_asset.php';
            include $r_path . 'scripts/info_proj_asset.php';
        }
    } else if ($path[3] == "Supp") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_project.php';
            include $r_path . 'scripts/query_proj_supp.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_proj_supp.php';
            include $r_path . 'scripts/info_proj_supp.php';
        }
    } else if ($path[3] == "Img") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_project.php';
            //include $r_path.'scripts/query_proj_timesheet.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            //include $r_path.'scripts/save_proj_timesheet.php';
            //include $r_path.'scripts/info_proj_timesheet.php';
        }
    } else if ($path[3] == "Mov") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_project.php';
            //include $r_path.'scripts/query_proj_timesheet.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            //include $r_path.'scripts/save_proj_timesheet.php';
            //include $r_path.'scripts/info_proj_timesheet.php';
        }
    } else if ($path[3] == "Sound") {
        if ($cont == "query") {
            include $r_path . 'scripts/info_project.php';
            //include $r_path.'scripts/query_proj_timesheet.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            //include $r_path.'scripts/save_proj_timesheet.php';
            //include $r_path.'scripts/info_proj_timesheet.php';
        }
    } else if (($path[1] == "All") || ($path[1] == "Open")) {
        if ($cont == "query") {
            include $r_path . 'scripts/query_projects.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_project.php';
        }
    } else if ($path[1] == "Cat") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_proj_categories.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_proj_category.php';
            if ($cont == "view") {
                include $r_path . 'scripts/query_proj_categories.php';
            }
        }
    }
// -------------------------------------------------------------------  Websites
} else if ($path[0] == "Web") {
    if ($path[1] == "Home") {
        include $r_path . 'scripts/save_web_home.php';
        include $r_path . 'scripts/info_web_home.php';
    } else if ($path[1] == "Page") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_web_pages.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_web_page.php';
            include $r_path . 'scripts/info_web_page.php';
        }
    } else if ($path[1] == "Order") {
        include $r_path . 'scripts/save_web_page_order.php';
        include $r_path . 'scripts/info_web_page_order.php';
    } else if ($path[1] == "Save") {
        include 'scripts/save_web_page_publish.php';
    } else if ($path[1] == "Evnt_Cont") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_web_event_con.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_web_event_con.php';
            include $r_path . 'scripts/info_web_event_con.php';
        }
    } else if ($path[1] == "Evnt") {
        if ($cont == "calendar") {
            include $r_path . 'scripts/calendar_events.php';
        } else if ($cont == "query") {
            include $r_path . 'scripts/query_web_events.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_web_events.php';
            include $r_path . 'scripts/info_web_events.php';
        }
    } else if ($path[1] == "Evnt_Cont") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_web_event_con.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_web_event_con.php';
            include $r_path . 'scripts/info_web_event_con.php';
        }
    } else if ($path[1] == "Evnt_Loc") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_web_event_loc.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_web_event_loc.php';
            include $r_path . 'scripts/info_web_event_loc.php';
        }
    } else if ($path[1] == "Quest") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_web_questbook.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_web_questbook.php';
            include $r_path . 'scripts/info_web_questbook.php';
        }
    } else if ($path[1] == "Links") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_web_links.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_web_links.php';
            include $r_path . 'scripts/info_web_links.php';
        }
    } else if ($path[1] == "News") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_web_news.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_web_news.php';
            include $r_path . 'scripts/info_web_news.php';
        }
    } else if ($path[1] == "Press") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_web_press.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_web_press.php';
            include $r_path . 'scripts/info_web_press.php';
        }
    } else if ($path[1] == "Hot") {
        include $r_path . 'scripts/save_web_hotpress.php';
        include $r_path . 'scripts/info_web_hotpress.php';
    } else if ($path[1] == "Letter") {
        include $r_path . 'scripts/save_newsletter.php';
        include $r_path . 'scripts/info_newsletter.php';
    } else if ($path[1] == "Mail") {
        include $r_path . 'scripts/query_mailing_list.php';
    } else if ($path[1] == "Career") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_web_career.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_web_career.php';
            include $r_path . 'scripts/info_web_career.php';
        }
    } else if ($path[1] == "ReqType") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_web_req_type.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_web_req_type.php';
            include $r_path . 'scripts/info_web_req_type.php';
        }
    } else if ($path[1] == "Stats") {
        include $r_path . 'scripts/info_stats.php';
    }
// -------------------------------------------------------------------  Custom Forms
} else if ($path[0] == "CustForm") {
    if ($path[3] == "Feild") {
        if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_cust_feilds.php';
            if ($cont == "view" && count($path) < 4) {
                include $r_path . 'scripts/info_cust_form.php';
                include $r_path . 'scripts/query_cust_feilds.php';
            } else {
                include $r_path . 'scripts/info_cust_feilds.php';
            }
        }
    } else if ($path[1] == "Form") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_cust_form.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/info_cust_form.php';
            if ($cont == "view") {
                include $r_path . 'scripts/query_cust_feilds.php';
            }
        }
    }
// -------------------------------------------------------------------  Administration
} else if ($path[0] == "Admin") {
    if ($path[1] == "User") {
        if ($loginsession[1] <= 1) {
            if ($cont == "query") {
                include $r_path . 'scripts/query_users.php';
            } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
                include $r_path . 'scripts/save_users.php';
                include $r_path . 'scripts/info_users.php';
            }
        } else {
            $cont = "edit";
            include $r_path . 'scripts/save_users.php';
            include $r_path . 'scripts/info_users.php';
        }
    } else if ($path[1] == "Emp") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_employees.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_employees.php';
            include $r_path . 'scripts/info_employees.php';
        }
    } else if ($path[1] == "Type") {
        if ($cont == "query") {
            include $r_path . 'scripts/query_employee_types.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'scripts/save_employees_types.php';
            include $r_path . 'scripts/info_employees_types.php';
        }
    }
}
?>