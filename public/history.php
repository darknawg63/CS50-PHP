<?php
    # history.php

    // configuration
    require("../includes/config.php");

    // retrieve current user's positions (stocks)
    $rows = CS50::query("SELECT * FROM history WHERE user_id = ?", $_SESSION["id"]);
        $sdate = CS50::query("SELECT `timestamp` FROM history WHERE user_id = ?", $_SESSION["id"]);
        // convert sql string representation to unix time stamp
        $udate = (strtotime($sdate[0]["timestamp"]));
        $datetime = date("d-m-y H:i:s", $udate);

    // We would like to format in US Dollars with a Dollar sign
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

?>