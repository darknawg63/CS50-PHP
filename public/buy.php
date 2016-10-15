<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("buy_form.php", ["title" => "Splurge?"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // gather the stock's information, ensuring that we only that symbols are upper cased
        $stock = lookup($_POST["symbol"]);
        $symbol =  strtoupper($_POST["symbol"]);
        // handle unsuccessful cases first
        if (empty($stock))
        {
            apologize("Symbol not found");
        }
        
        if ($shares = $_POST["shares"] < 1)
            apologize("You're not splurging?");
            
        // check for whole increments os shares
        if (!preg_match("/^\d+$/", $_POST["shares"]))
            apologize("Shares must be in whole increments.");

        // calculate cost of shares
        $cost = $_POST["shares"] * $stock["price"]; 
        // check client's cash
        $cash = CS50::query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);

        if ($cash[0]["cash"] < $cost)
            apologize("Insufficient funds.");

        #-- all checks have passed
        
        // add purchased shares to client's portfolio'
        CS50::query("INSERT INTO portfolios (user_id, symbol, shares) 
                     VALUES(?, ?, ?) ON DUPLICATE KEY 
                     UPDATE shares = shares + VALUES(shares)", 
                     $_SESSION["id"], $symbol, $_POST["shares"]);
        
        // debit the client's balance, because there are no free lunches
        CS50::query("UPDATE users SET cash = cash - '$cost' WHERE id = ?", $_SESSION["id"]);
        redirect("/");
    }

?>

