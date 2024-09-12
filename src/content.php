<section class="component">
    <div class="payment">
        <h2>Checkout</h2>
            <form action="pay.php" method="post">
                 <div class="line">
                    <input type="number" id="" name="amount"  placeholder="Enter amount">
                    </div>
                    <legend id="legend">Payment Method</legend>
                    <?php
                    $pay_menthods = array('Klarna','Stripe','Paypal','Visa');
                    ?>
                <div class="radios">
                
                    <?php
                    foreach($GLOBALS['pay_menthods'] as  $pay_method)
                    { ?>
                    <div class="radio">
                    <input  type="radio" name="payment_method" id="paymentRadio" value="<?php echo $pay_method; ?>">
                    <label  for="klarna">
                    <?php  echo $pay_method; ?> 
                    </label>
                    </div> 
                    <?php } ?> 
                </div>
                <button type="submit" class="valid-button">PROCEED TO PAY</button>
            </form>
    </div>
</section>