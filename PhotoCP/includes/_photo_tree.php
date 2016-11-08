<?
if (isset($_POST['Search']) && strlen(trim($_POST['Search'])) > 0) {
    include $r_path . 'includes/info_search.php';
} else if ($path[0] == "Evnt") {
    if ((!isset($path[1]) || $path[1] == "Evnt") && count($path) < 4) {
        if (!isset($path[1]))
            $path[1] = "Evnt";
        if ($cont == "add") {
            include $r_path . 'includes/save_event_create.php';
            if ($path[3] == "ImgsGrps") {
                ?>
                <script type="text/javascript">set_form('', '<? echo implode(",", $path); ?>', 'add', '<? echo $sort; ?>', '<? echo $rcrd; ?>');</script>
                <?
            } else {
                include $r_path . 'includes/info_event_create.php';
            }
        } else if ($cont == "query") {
            include $r_path . 'includes/query_event_list.php';
            include $r_path . 'includes/query_release_event_list.php';
        } else if ($cont == "view" || $cont == "edit") {
            include $r_path . 'includes/save_event_info.php';
            include $r_path . 'includes/info_event_info.php';
        }
    } else if ($path[3] == "ImgsGrps") {
        include $r_path . 'includes/info_images_groups.php';
    } else if ($path[3] == "Present") {
        include $r_path . 'includes/save_event_presentation.php';
        include $r_path . 'includes/info_event_presentation.php';
    } else if ($path[3] == "Market") {
        include $r_path . 'includes/save_event_marketing.php';
        include $r_path . 'includes/info_event_marketing.php';
    } else if ($path[3] == "DiscCodes") {
        include $r_path . 'includes/query_preset_discount_codes.php';
    } else if ($path[3] == "GiftCerts") {
        if ($cont == "query") {
            include $r_path . 'includes/query_gift_certs.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'includes/save_gift_certs.php';
            include $r_path . 'includes/info_gift_certs.php';
        }
    } else if ($path[3] == "Coupns") {
        include $r_path . 'includes/query_preset_coupons.php';
    } else if ($path[3] == "Board") {
        if ($cont == "query") {
            include $r_path . 'includes/query_msg_board.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'includes/save_msg_board.php';
            if ($cont == "query") {
                include $r_path . 'includes/query_msg_board.php';
            } else {
                include $r_path . 'includes/info_msg_board.php';
            }
        }
    } else if ($path[3] == "Guest") {
        /** Removed on 2013-11-18 */
//        if (isset($path[6]) && $path[6] == "Clone") {
//            include $r_path . 'includes/query_event_notifications_clone.php';
//        } else if ($path[4] == "Note") {
//            include $r_path . 'includes/save_event_notifications.php';
//            if ($path[1] == "Note" && count($path) == 2) {
//                include $r_path . 'includes/query_all_event_notifications.php';
//            } else {
//                if ($cont == "query") {
//                    include $r_path . 'includes/query_event_guestbook.php';
//                    include $r_path . 'includes/query_event_notifications.php';
//                } else {
//                    include $r_path . 'includes/info_event_notifications.php';
//                }
//            }
//        } else {
        if ($cont == "query") {
            include $r_path . 'includes/query_event_guestbook.php';
            //include $r_path . 'includes/query_event_notifications.php';
        } else {
            include $r_path . 'includes/save_event_guestbook.php';
            include $r_path . 'includes/info_event_guestbook.php';
        }
//        }
    } else if ($path[3] == "Note") {
        if (count($path) > 4) {
            if ($cont == "view" || $cont == "add" || $cont == "edit") {
                include $r_path . 'includes/save_event_notifications.php';
                if ($cont == "query") {
                    include $r_path . 'includes/query_event_notifications.php';
                } else {
                    include $r_path . 'includes/info_event_notifications.php';
                }
            }
        } else {
            include $r_path . 'includes/query_event_notifications.php';
        }
    } else if ($path[3] == "Reprt") {
        include $r_path . 'includes/save_report_commision.php';
        include $r_path . 'includes/info_report_commision.php';
    } else if ($path[1] == "Mrkt") {
        if ($cont == "query") {
            include $r_path . 'includes/save_query_events_marketing.php';
            include $r_path . 'includes/info_event_marketing.php';
            include $r_path . 'includes/query_events_marketing.php';
        }
    } else if ($path[1] == "Note") {
        if ($cont == "query") {
            include $r_path . 'includes/query_all_event_notifications.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'includes/save_event_notifications.php';
            if ($cont == "query") {
                include $r_path . 'includes/query_all_event_notifications.php';
            } else {
                include $r_path . 'includes/info_event_notifications.php';
            }
        }
    }
} else if ($path[0] == "Clnt") {
    if ($path[1] == "Date") {
        //include $r_path.'includes/save_event_guestbook.php';
        include $r_path . 'includes/info_customer_spcl_evnts.php';
    } else if ($path[1] == "Search") {
        include $r_path . 'includes/search_customers.php';
    } else if ($path[1] == "Note") {
        if ($cont == "query") {
            include $r_path . 'includes/query_all_event_notifications.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'includes/save_event_notifications.php';
            if ($cont == "query") {
                include $r_path . 'includes/query_all_event_notifications.php';
            } else {
                include $r_path . 'includes/info_event_notifications.php';
            }
        }
    } else if ($path[1] == "Guest") {
        if ($cont == "query") {
            include $r_path . 'includes/query_events_nodel.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            if (count($path) == 3) {
//                if (!isset($path[3]))
//                    $path[] = "Guest";
                include $r_path . 'includes/query_event_guestbook.php';
//                include $r_path . 'includes/query_event_notifications.php';
//            } else if ($path[4] == "Note") {
//                include $r_path . 'includes/save_event_notifications.php';
//                if ($path[1] == "Note" && count($path) == 2) {
//                    include $r_path . 'includes/query_all_event_notifications.php';
//                } else {
//                    if ($cont == "query") {
//                        include $r_path . 'includes/query_event_guestbook.php';
//                        include $r_path . 'includes/query_event_notifications.php';
//                    } else {
//                        include $r_path . 'includes/info_event_notifications.php';
//                    }
//                }
            } else {
                include $r_path . 'includes/save_event_guestbook.php';
                include $r_path . 'includes/info_event_guestbook.php';
            }
        }
    } else if ($path[1] == "Board") {
        if (count($path) < 3) {
            include $r_path . 'includes/query_events_nodel.php';
        } else if (count($path) == 3) {
            if (!isset($path[3]))
                $path[] = "Board";
            include $r_path . 'includes/query_msg_board.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            if (!isset($path[3]))
                $path[] = "Board";
            include $r_path . 'includes/save_msg_board.php';
            if ($cont == "query") {
                include $r_path . 'includes/query_msg_board.php';
            } else {
                include $r_path . 'includes/info_msg_board.php';
            }
        }
    } else if ($path[1] == "Clnt") {
        if ($cont == "query") {
            include $r_path . 'includes/query_customers.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            if (count($path) > 3 && ($path[3] == "SpclEvnts" || $path[4] == "SpclEvnts")) {
                include $r_path . 'includes/save_customer_spcl_evnts.php';
                include $r_path . 'includes/info_customer_spcl_evnts.php';
            } else if (count($path) > 3 && ($path[3] == "Relat" || $path[4] == "Relat")) {
                include $r_path . 'includes/save_customer_relation.php';
                include $r_path . 'includes/info_customer_relation.php';
            } else if (count($path) > 3 && $path[3] == "Inv") {
                include $r_path . 'includes/save_customer_invoices.php';
                include $r_path . 'includes/info_customer_invoices.php';
            } else {
                include $r_path . 'includes/save_customers.php';
                include $r_path . 'includes/info_customers.php';
                if ($cont == "view") {
                    include $r_path . 'includes/query_customer_invoices.php';
                    include $r_path . 'includes/query_customer_merchant.php';
                    include $r_path . 'includes/query_customer_spcl_evnts.php';
                    include $r_path . 'includes/query_customer_relation.php';
                }
            }
        }
    } else if ($path[1] == "SrchSvd") {
        include $r_path . 'includes/query_customers_saved_search.php';
    }
} else if ($path[0] == "Busn") {
    if ($path[1] == "Open" || $path[1] == "All") {
        if ($cont == "search") {
            include $r_path . 'includes/search_invoices.php';
        } else if ($cont == "query") {
            include $r_path . 'includes/query_invoices.php';
        } else if ($cont == "view" || $cont == "ToLab") {
            include $r_path . 'includes/save_customer_invoices.php';
            include $r_path . 'includes/info_customer_invoices.php';
        }
    } else if ($path[1] == "Busn") {
        include $r_path . 'includes/save_report_commision.php';
        include $r_path . 'includes/info_report_commision.php';
    } else if ($path[1] == "Disc") {
        if ($cont == "query") {
            include $r_path . 'includes/query_discount_codes.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'includes/save_discount_codes.php';
            include $r_path . 'includes/info_discount_codes.php';
        }
    } else if ($path[1] == "Gift") {
        if ($cont == "query") {
            include $r_path . 'includes/query_gift_certs.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'includes/save_gift_certs.php';
            include $r_path . 'includes/info_gift_certs.php';
        }
    } else if ($path[1] == "Pre") {
        include $r_path . 'includes/query_preset_discount_codes.php';
//        if ($cont == "query") {
//            include $r_path . 'includes/query_events_nodel.php';
//        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
//            if (!isset($path[3]))
//                $path[] = "Market";
//            include $r_path . 'includes/query_preset_discount_codes.php';
//        }
    } else if ($path[1] == "Mrkt") {
        if ($cont == "query") {
            include $r_path . 'includes/save_query_events_marketing.php';
            include $r_path . 'includes/info_event_marketing.php';
            include $r_path . 'includes/query_events_marketing.php';
        }
    } else if ($path[1] == "Note") {
        if ($cont == "query") {
            include $r_path . 'includes/query_all_event_notifications.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'includes/save_event_notifications.php';
            if ($cont == "query") {
                include $r_path . 'includes/query_all_event_notifications.php';
            } else {
                include $r_path . 'includes/info_event_notifications.php';
            }
        }
    } else if ($path[1] == "Prcn") {
        if ($cont == "query") {
            include $r_path . 'includes/query_pricing.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'includes/save_pricing.php';
            include $r_path . 'includes/info_pricing.php';
        }
    } else if ($path[1] == "Mrch") {
        if ($cont == "query") {
            include $r_path . 'includes/query_merchant.php';
        } else {
            include $r_path . 'includes/save_merchant.php';
            include $r_path . 'includes/info_merchant.php';
        }
    } else if ($path[1] == "Past") {
        if ($cont == "query") {
            include $r_path . 'includes/query_merchant_process.php';
        } else {
            include $r_path . 'includes/save_merchant.php';
            include $r_path . 'includes/info_merchant.php';
        }
    }
} else if ($path[0] == "Web") {
    if ($path[1] == "Home") {
        include $r_path . 'includes/save_homepage.php';
        include $r_path . 'includes/info_homepage.php';
    } else if ($path[1] == "Bio") {
        include $r_path . 'includes/save_biopage.php';
        include $r_path . 'includes/info_biopage.php';
    } else if ($path[1] == "Ftr") {
        include $r_path . 'includes/save_footer.php';
        include $r_path . 'includes/info_footer.php';
    }
} else if ($path[0] == "Pers") {
    if ($path[1] == "Info") {
        include $r_path . 'includes/save_personal_info.php';
        include $r_path . 'includes/info_personal_info.php';
    } else if ($path[1] == "Bill") {
        include $r_path . 'includes/save_billing.php';
        include $r_path . 'includes/info_billing.php';
    } else if ($path[1] == "Renew") {
        include $r_path . 'includes/query_customers_months_paid.php';
        include $r_path . 'includes/save_renew.php';
        include $r_path . 'includes/info_renew.php';
    } else if ($path[1] == "Cancel") {
        include $r_path . 'includes/save_cancel.php';
        include $r_path . 'includes/info_cancel.php';
    }
} else if ($path[0] == "Comm") {
    if ($path[1] == "Update") {
        include $r_path . 'includes/info_update_list.php';
    } else if ($path[1] == "Help") {
        include $r_path . 'includes/info_help.php';
    } else if ($path[1] == "DiscHelp") {
        include $r_path . 'includes/info_help_disc.php';
    } else if ($path[1] == "GuestHelp") {
        include $r_path . 'includes/info_help_guest.php';
    } else if ($path[1] == "ProdHelp") {
        include $r_path . 'includes/info_help_prod.php';
    } else if ($path[1] == "WebHelp") {
        include $r_path . 'includes/info_help_web.php';
    }
} else if ($path[0] == "Contact") {
    if ($path[1] == "Comment") {
        include $r_path . 'includes/info_contact.php';
    } else if ($path[1] == "Report") {
        include $r_path . 'includes/info_error.php';
    } else if ($path[1] == "Request") {
        include $r_path . 'includes/info_error.php';
    }
} else if ($path[0] == "Photo") {
    if ($path[1] == "Photo") {
        if ($cont == "query") {
            include $r_path . 'includes/query_photographer.php';
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            include $r_path . 'includes/save_photographer_info.php';
            include $r_path . 'includes/info_photographer_info.php';
        }
    }
} else if ($path[0] == "Prods") {
    include $r_path . 'includes/info_prices.php';
} else if ($path[0] == "Album") {
    include $r_path . 'includes/info_album.php';
} else {
    include $r_path . 'includes/save_welcome.php';
    include $r_path . 'includes/info_welcome.php';
}
?>
