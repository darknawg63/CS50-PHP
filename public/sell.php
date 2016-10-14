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
            $price = $stock["price"];

            // Get the client's number of assets of the stock
            $assets = CS50::query("SELECT shares FROM portfolios WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $symbol);
            $investment = $price * floatval($assets[0]["shares"]);
            
            // Update client's portfolio.
            CS50::query("DELETE FROM portfolios WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $symbol);
            CS50::query("UPDATE users SET cash = cash - ? WHERE id = ?", $investment, $_SESSION["id"]);
            //die();
                // retrieve current user's positions (stocks)
            $rows = CS50::query("SELECT id, user_id, symbol, shares FROM portfolios WHERE user_id = ?", $_SESSION["id"]);

            $cash = CS50::query("SELECT cash FROM users WHERE id=?", $_SESSION["id"]);

            if ($cash == 0)
            {
                $cash[0]["cash"] = 0;
            }
            // Okay, so this is duplicating code from index.php, but I'm sick and in the hospital now :(
            setlocale(LC_MONETARY, 'en_US.UTF-8');

            $positions = [];

            foreach ($rows as $row)
            {
                $stock = lookup($row["symbol"]);
                if ($stock !== false)
                {
                    $positions[] = [
                        "name" => $stock["name"],
                        "price" => money_format('%.2n', $stock["price"]),
                        "shares" => $row["shares"],
                        "symbol" => $row["symbol"],
                        "total" => money_format('%.2n', $stock["price"] * $row["shares"])
                    ];
                }
            }

            // render user's portfolio
            render("portfolio.php", ["title" => "Portfolio", "positions" => $positions, "cash" => money_format('%.2n', $cash[0]["cash"])]);
        }
    }
?>
