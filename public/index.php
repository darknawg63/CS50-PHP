<?php

    // configuration
    require("../includes/config.php");

    // retrieve current user's balance
    $cash = CS50::query("SELECT cash FROM users WHERE id=?", $_SESSION["id"]);

    if ($cash == 0)
    {
        $cash[0]["cash"] = 0;
    }

    // retrieve current user's positions (stocks)
    $rows = CS50::query("SELECT id, user_id, symbol, shares FROM portfolios WHERE user_id = ?", $_SESSION["id"]);

    $positions = [];

    foreach ($rows as $row)
    {
        $stock = lookup($row["symbol"]);
        if ($stock !== false)
        {
            $positions[] = [
                "name" => $stock["name"],
                "price" => $stock["price"],
                "shares" => $row["shares"],
                "symbol" => $row["symbol"]
            ];
        }
    }
    // We would like to format in US Dollars with a Dollar sign
    setlocale(LC_MONETARY, 'en_US.UTF-8');

    // render portfolio
    render("portfolio.php", ["title" => "Portfolio", "id" => $_SESSION["id"], 
        "cash" => money_format('%.2n',$cash[0]["cash"])]);

?>
