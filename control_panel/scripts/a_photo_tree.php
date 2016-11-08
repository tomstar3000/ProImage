<?
if (isset($_GET['uploaded']) && $_GET['uploaded'] == "true") {

    $eid = clean_variable(substr($_GET['id'], 10), true);

    $query_get_id = "SELECT `event_id` FROM `cust_customers` 
		INNER JOIN `photo_event` 
			ON `photo_event`.`cust_id` = `cust_customers`.`cust_id` 
		WHERE `cust_session` = '$loginsession[0]' 
			AND `photo_event`.`event_id` = '$eid' 
		ORDER BY `event_updated` DESC 
		LIMIT 0,1";
    $get_id = mysql_query($query_get_id, $cp_connection) or die(mysql_error());
    $row_get_id = mysql_fetch_assoc($get_id);

    $path = array("Events", "Events", $row_get_id['event_id']);

    $cont = "choose";

    mysql_free_result($get_id);
} else if (isset($_GET['updated']) && $_GET['updated'] == "true") {

    $eid = clean_variable(substr($_GET['id'], 10), true);

    $query_get_id = "SELECT `photo_event`.`event_id`,`group_id` FROM `cust_customers` INNER JOIN `photo_event` ON `photo_event`.`cust_id` = `cust_customers`.`cust_id` INNER JOIN `photo_event_group` ON `photo_event_group`.`event_id` = `photo_event`.`event_id` WHERE `cust_session` = '$loginsession[0]' AND `photo_event`.`event_id` = '$eid' ORDER BY `group_updated` DESC LIMIT 0,1";
    $get_id = mysql_query($query_get_id, $cp_connection) or die(mysql_error());
    $row_get_id = mysql_fetch_assoc($get_id);

    $path = array("Events", "Events", $row_get_id['event_id'], "Groups", $row_get_id['group_id']);
    $cont = "view";
}
if ($path[0] == "Renew") {
    if ($path[1] == "Info") {
        if ($cont == "edit") {
            require_once($r_path . 'scripts/save_photo_billing.php');
            if ($cont == "view") {
                require_once($r_path . 'scripts/save_photo_renew.php');
                require_once($r_path . 'scripts/info_photo_renew.php');
            }
            require_once($r_path . 'scripts/info_photo_billing.php');
            if ($cont == "view") {
                ?>

                <br clear="all" />
                <div id="Save_Btn">
                    <input type="button" name="btnSave" id="btnSave" value="Renew" alt="Renew" onmouseup="document.getElementById('Controller').value = 'Renew';
                                            this.disabled = true;
                                            this.form.submit();" />
                </div>
                <?
            }
        } else {
            require_once($r_path . 'scripts/save_photo_renew.php');
            require_once($r_path . 'scripts/info_photo_renew.php');
            require_once($r_path . 'scripts/save_photo_billing.php');
            require_once($r_path . 'scripts/info_photo_billing.php');
            ?>
            <br clear="all" />
            <div id="Save_Btn">
                <input type="button" name="btnSave" id="btnSave" value="Renew" alt="Renew" onmouseup="document.getElementById('Controller').value = 'Renew';
                                        this.disabled = true;
                                        this.form.submit();" />
            </div>
            <?
        }
    }
} else if ($path[0] == "Contact") {
    if ($path[1] == "Comment") {
        require_once($r_path . 'scripts/info_photo_contact.php');
    } else if ($path[1] == "Report") {
        require_once($r_path . 'scripts/info_photo_error.php');
    } else if ($path[1] == "Request") {
        require_once($r_path . 'scripts/info_photo_error.php');
    }
} else if ($path[0] == "Events") {
    if (isset($path[3]) && $path[3] == "Groups") {
        if ((isset($_POST['Controller']) && $_POST['Controller'] == "Move") || (isset($_POST['Mover']) && $_POST['Mover'] == "true")) {
            if (isset($_POST['Groups_items'])) {
                require_once($r_path . 'scripts/save_group_event_switcher.php');
                require_once($r_path . 'scripts/info_group_event_switcher.php');
            } else {
                require_once($r_path . 'scripts/save_image_group_switcher.php');
                require_once($r_path . 'scripts/info_image_group_switcher.php');
            }
        } else if ($cont == "upload" || $cont == "massupload") {
            require_once($r_path . 'scripts/save_image_uploader.php');
            require_once($r_path . 'scripts/info_image_uploader.php');
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            require_once($r_path . 'scripts/save_photo_groups.php');
            if ($cont == "add") {
                require_once($r_path . 'scripts/save_image_uploader.php');
            }
            require_once($r_path . 'scripts/info_photo_groups.php');
            if ($cont == "view") {
                if (count($path) < 8)
                    require_once($r_path . 'scripts/query_photo_groups.php');
                require_once($r_path . 'scripts/query_image_groups.php');
            }
        }
    } else if (isset($path[3]) && $path[3] == "Disc") {
        if ($cont == "query") {
            require_once($r_path . 'scripts/save_photo_events.php');
            require_once($r_path . 'scripts/info_photo_events.php');
            require_once($r_path . 'scripts/query_photo_event_codes.php');
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            require_once($r_path . 'scripts/save_photo_event_codes.php');
            if ($cont == "query") {
                require_once($r_path . 'scripts/save_photo_events.php');
                require_once($r_path . 'scripts/info_photo_events.php');
                require_once($r_path . 'scripts/query_photo_event_codes.php');
            } else {
                require_once($r_path . 'scripts/info_photo_event_codes.php');
            }
        }
    } else if (isset($path[3]) && $path[3] == "Msg") {
        if ($cont == "query") {
            require_once($r_path . 'scripts/save_photo_events.php');
            require_once($r_path . 'scripts/info_photo_events.php');
            require_once($r_path . 'scripts/query_photo_event_msg_board.php');
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            require_once($r_path . 'scripts/save_photo_event_msg_board.php');
            if ($cont == "query") {
                require_once($r_path . 'scripts/save_photo_events.php');
                require_once($r_path . 'scripts/info_photo_events.php');
                require_once($r_path . 'scripts/query_photo_event_msg_board.php');
            } else {
                require_once($r_path . 'scripts/info_photo_event_msg_board.php');
            }
        }
    } else if (isset($path[3]) && $path[3] == "Not") {
        if ($cont == "query") {
            require_once($r_path . 'scripts/save_photo_events.php');
            require_once($r_path . 'scripts/info_photo_events.php');
            require_once($r_path . 'scripts/query_photo_event_questbook.php');
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            require_once($r_path . 'scripts/save_photo_event_questbook.php');
            if ($cont == "query") {
                require_once($r_path . 'scripts/save_photo_events.php');
                require_once($r_path . 'scripts/info_photo_events.php');
                require_once($r_path . 'scripts/query_photo_event_questbook.php');
            } else {
                require_once($r_path . 'scripts/info_photo_event_questbook.php');
            }
        }
    } else if ($path[1] == "Expired") {
        require_once($r_path . 'scripts/query_photo_exp_events.php');
    } else if ($path[1] == "Notes") {
        if ($cont == "query") {
            require_once($r_path . 'scripts/query_photo_event_questbook_2.php');
        } else {
            require_once($r_path . 'scripts/save_photo_event_questbook_2.php');
            require_once($r_path . 'scripts/info_photo_event_questbook_2.php');
        }
    } else if ($path[1] == "Events") {
        if ((isset($_POST['Controller']) && $_POST['Controller'] == "Move") || (isset($_POST['Mover']) && $_POST['Mover'] == "true")) {
            require_once($r_path . 'scripts/save_group_event_switcher.php');
            require_once($r_path . 'scripts/info_group_event_switcher.php');
        } else if ($cont == "upload") {
            require_once($r_path . 'scripts/save_image_uploader.php');
            require_once($r_path . 'scripts/info_image_uploader.php');
        } else if ($cont == "query") {
            require_once($r_path . 'scripts/query_photo_events.php');
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            require_once($r_path . 'scripts/save_photo_events.php');
            if ($cont == "upload") {
                if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "msie") === false) {
                    array_push($path, "Groups");
                    $cont = "add";
                    $deactive = true;
                    require_once($r_path . 'scripts/save_photo_groups.php');
                    require_once($r_path . 'scripts/save_image_uploader.php');
                    require_once($r_path . 'scripts/info_photo_groups.php');
                } else {
                    require_once($r_path . 'scripts/save_image_uploader.php');
                    require_once($r_path . 'scripts/info_image_uploader.php');
                }
            } else {
                require_once($r_path . 'scripts/info_photo_events.php');
                if ($cont == "view") {
                    array_push($path, "Groups");
                    require_once($r_path . 'scripts/query_photo_groups.php');
                }
            }
        } else if ($cont == "choose") {
            require_once($r_path . 'scripts/save_photo_image_list.php');
            if ($cont == "view") {
                require_once($r_path . 'scripts/save_photo_events.php');
                require_once($r_path . 'scripts/info_photo_events.php');
                array_push($path, "Groups");
                require_once($r_path . 'scripts/query_photo_groups.php');
            } else {
                require_once($r_path . 'scripts/info_photo_image_list.php');
            }
        }
    }
} else if ($path[0] == "Disc") {
    if ($path[1] == "Disc") {
        if ($cont == "query") {
            require_once($r_path . 'scripts/query_photo_discount_codes.php');
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            require_once($r_path . 'scripts/save_photo_discount_codes.php');
            require_once($r_path . 'scripts/info_photo_discount_codes.php');
        }
    } else if ($path[1] == "Preset") {
        if ($cont == "query") {
            require_once($r_path . 'scripts/query_photo_message_board.php');
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            if (!isset($path[3]))
                $path[] = "Disc";
            require_once($r_path . 'scripts/query_photo_event_codes.php');
        }
    }
} else if ($path[0] == "Orders") {
    if ($path[1] == "Open" || $path[1] == "All") {
        if ($cont == "search") {
            require_once($r_path . 'scripts/search_photo_invoices.php');
        } else if ($cont == "query") {
            require_once($r_path . 'scripts/query_photo_invoices.php');
        } else if ($cont == "view") {
            require_once($r_path . 'scripts/save_photo_invoices.php');
            require_once($r_path . 'scripts/info_photo_invoices.php');
        } else if ($cont == "ToLab") {
            require_once($r_path . 'scripts/get_photo_invoices.php');
            require_once($r_path . 'scripts/save_photo_invoices.php');

            $encnum = $InvEnc;
            ob_start();
            $old_path = $r_path;
            $r_path .= "../";
            require_once($r_path . 'checkout/invoice.php');
            $r_path = $old_path;
            $page = ob_get_contents();
            ob_end_clean();

            require_once $r_path . 'scripts/fnct_phpmailer.php';

            $Email = $row_get_photo['cust_email'];
            $reciever = "info@photoexpresspro.com";

            $mail = new PHPMailer();
            $mail->Host = "smtp.proimagesoftware.com";
            $mail->IsHTML(true);
            $mail->IsSendMail();
            $mail->Sender = "info@proimagesoftware.com";
            $mail->Hostname = "proimagesoftware.com";
            $mail->From = $Email;
            $mail->AddAddress($reciever);
            $mail->FromName = $Email;
            $mail->Subject = "Invoice";
            $mail->Body = $page;
            $mail->Send();
            $Emailsent = true;

            $toChad = "PhotoToLab: " . $row_get_photo['invoice_num'] . " : " . $reciever . " - " . $Email;
            $mail = new PHPMailer();
            $mail->Host = "smtp.proimagesoftware.com";
            //$mail -> IsHTML(true);
            $mail->IsSendMail();
            $mail->Sender = "info@proimagesoftware.com";
            $mail->Hostname = "proimagesoftware.com";
            $mail->From = "info@photoexpress.com";
            $mail->FromName = "info@photoexpress.com";
            $mail->AddAddress("development@proimagesoftware.com");
            $mail->Subject = "Debugging";
            $mail->Body = $toChad;
            $mail->Send();
            $Emailsent = true;

            require_once($r_path . 'scripts/info_photo_invoices.php');
        }
    }
} else if ($path[0] == "Customers") {
    if ($path[1] == "All") {
        if ($cont == "search") {
            require_once($r_path . 'scripts/search_photo_customers.php');
        } else if ($cont == "query") {
            require_once($r_path . 'scripts/query_photo_customers.php');
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            if (count($path) > 3) {
                $temppath = $path;
                $path = array($path[0], $path[1], $path[3]);
                require_once($r_path . 'scripts/save_photo_invoices.php');
                $path = $temppath;
                require_once($r_path . 'scripts/info_photo_invoices.php');
            } else {
                require_once($r_path . 'scripts/save_photo_customers.php');
                require_once($r_path . 'scripts/info_photo_customers.php');
                require_once($r_path . 'scripts/query_photo_client_invoices.php');
            }
        }
    }
} else if ($path[0] == "Guest") {
    if ($path[1] == "All") {
        if ($cont == "email") {
            require_once($r_path . 'scripts/save_photo_questbook.php');
            require_once($r_path . 'scripts/info_photo_questbook.php');
        } else if ($cont == "query") {
            require_once($r_path . 'scripts/query_photo_questbook.php');
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            require_once($r_path . 'scripts/query_photo_questbook_list.php');
        }
    } else if ($path[1] == "Board") {
        if (count($path) < 3 && $cont == "query") {
            require_once($r_path . 'scripts/query_photo_message_board.php');
        } else if (count($path) <= 4 && $cont == "query") {
            if (!isset($path[3]))
                $path[] = "Msg";
            $cont = "query";
            require_once($r_path . 'scripts/query_photo_event_msg_board.php');
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            require_once($r_path . 'scripts/save_photo_event_msg_board.php');
            require_once($r_path . 'scripts/info_photo_event_msg_board.php');
        }
    }
} else if ($path[0] == "Report") {
    if ($path[1] == "Comm") {
        include $r_path . 'scripts/save_rept_commision.php';
        include $r_path . 'scripts/info_rept_commision.php';
    }
} else if ($path[0] == "Price") {
    if ($path[1] == "All") {
        if ($cont == "query") {
            require_once($r_path . 'scripts/query_photo_pricing.php');
        } else if ($cont == "view" || $cont == "add" || $cont == "edit") {
            require_once($r_path . 'scripts/save_photo_pricing.php');
            if ($cont == "query") {
                require_once($r_path . 'scripts/query_photo_pricing.php');
            } else {
                require_once($r_path . 'scripts/info_photo_pricing.php');
            }
        }
    }
} else if ($path[0] == "Web") {
    if ($path[1] == "Home") {
        require_once($r_path . 'scripts/save_homepage.php');
        require_once($r_path . 'scripts/info_homepage.php');
    } else if ($path[1] == "Bio") {
        require_once($r_path . 'scripts/save_biopage.php');
        require_once($r_path . 'scripts/info_biopage.php');
    } else if ($path[1] == "Ftr") {
        require_once($r_path . 'scripts/save_footer.php');
        require_once($r_path . 'scripts/info_footer.php');
    }
} else if ($path[0] == "Pers") {
    if ($path[1] == "Info") {
        require_once($r_path . 'scripts/save_photo_info.php');
        require_once($r_path . 'scripts/info_photo_info.php');
    }
} else if ($path[0] == "Bill") {
    if ($path[1] == "Info") {
        require_once($r_path . 'scripts/save_photo_billing.php');
        require_once($r_path . 'scripts/info_photo_billing.php');
    }
} else if ($path[0] == "Help") {
    if ($path[1] == "Help") {
        require_once($r_path . 'scripts/info_photo_help.php');
    } else if ($path[1] == "Disc") {
        require_once($r_path . 'scripts/info_photo_help_disc.php');
    } else if ($path[1] == "Gues") {
        require_once($r_path . 'scripts/info_photo_help_guest.php');
    } else if ($path[1] == "Prod") {
        require_once($r_path . 'scripts/info_photo_help_prod.php');
    } else if ($path[1] == "Web") {
        require_once($r_path . 'scripts/info_photo_help_web.php');
    }
} else if ($path[0] == "Update") {
    if ($path[1] == "Update") {
        require_once($r_path . 'scripts/info_photo_update_list.php');
    }
}
?>
