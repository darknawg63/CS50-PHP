<?php

    // sell.php
    //
    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("sell_form.php", ["title" => "Sell Stuff"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Round up all of the clients assets
        $assets = CS50::query("SELECT symbol, shares FROM portfolios WHERE user_id = ?", $_SESSION["id"]);
        if (empty($assets))
            apologize("No assets found.");
        else
        if (!empty($assets))
        {
            render("sell_form.php", ["title" => "Sell stuff", "assets" => $assets]);
        }
    }
    // Notice: Undefined variable: assets in /home/darknawg63/Code/PHP/cs50/pset7/views/sell_form.php
    // After continuously getting the above error, it occurred to me that the query was only executed
    // during a post
?>
