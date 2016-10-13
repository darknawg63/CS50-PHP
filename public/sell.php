<?php

    // sell.php
    //
    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // Round up all of the clients assets
        $assets = CS50::query("SELECT symbol, shares FROM portfolios WHERE user_id = ?", $_SESSION["id"]);
        if (empty($assets))
            apologize("No assets found.");
        else
            render("sell_form.php", ["title" => "Sell Stuff", "assets" => $assets]);
    }

    // We would like to format in US Dollars with a Dollar sign
    setlocale(LC_MONETARY, 'en_US.UTF-8');

    // if user reached page via POST (as by submitting a form via POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST["symbol"]))
        {
            $symbol = $_POST["symbol"];
            $stock = lookup($symbol);

            // Get the client's number of shares of the stock
            $shares = CS50::query("SELECT shares FROM portfolios WHERE user_id = ?", $_SESSION["id"]);

            // Update client's portfolio.
            $shrs = CS50::query("UPDATE portfolios SET shares=NULL WHERE user_id = ?", $_SESSION["id"]);
            $csh = CS50::query("UPDATE users SET cash= ? WHERE user_id = ?", (money_format('%.2n', $stock["price"]) * $shares), $_SESSION["id"]);
        }
    }
?>
