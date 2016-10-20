<?php
    # quote.php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("quote_form.php", ["title" => "Get Quote"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    setlocale(LC_MONETARY, 'en_US.UTF-8');
    
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // ensure that we're only matching upper case symbols
        if (!$stock = lookup(strtoupper($_POST["symbol"])))
            apologize("Invalid entry.");
        
        render("quote.php", ["symbol" => strtoupper($stock["symbol"]), "price" => money_format('%.2n', $stock["price"]), "name" => $stock["name"]]);
    }

?>