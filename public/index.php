<?php

    // configuration
    require("../includes/config.php");

    // retrieve user's balance
    $cash = CS50::query("SELECT cash FROM users WHERE id=?", $_SESSION["id"]);

    if ($cash == 0)
    {
        $cash[0]["cash"] = 0;
    }

    // We would like to format in US Dollars with a Dollar sign
    setlocale(LC_MONETARY, 'en_US.UTF-8');

    // render portfolio
    render("portfolio.php", ["title" => "Portfolio", "id" => $_SESSION["id"],
             "cash" => money_format('%.2n',$cash[0]["cash"])]);

?>
