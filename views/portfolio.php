<div id="middle">
  <table class="table table-striped">

    <thead>
      <tr>
        <th>Symbol</th>
        <th>Name</th>
        <th>Shares</th>
        <th>Price</th>
        <th>TOTAL</th>
      </tr>
    </thead>

    <tbody>

      <!-- <tr><td>MSFT</td><td>Microsoft Corporation</td><td>50</td><td>$57.80</td><td>$2,890.00</td></tr> -->


        <?php foreach ($positions as $position): ?>

            <tr>
                <td><?= $position["symbol"] ?></td>
                <td><?= $position["name"] ?></td>
                <td><?= $position["shares"] ?></td>
                <td><?= $position["price"] ?></td>
                <td><?= $position["total"] ?></td>
            </tr>

        <?php endforeach ?>


      <tr>
        <td colspan="4">CASH</td>
        <td><?= $cash ?></td>
      </tr>

    </tbody>

  </table>
</div>
